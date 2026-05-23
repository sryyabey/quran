<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ImportWordTranslations extends Command
{
    protected $signature = 'quran:import-word-translations
                            {--lang=tr : Dil kodu (varsayılan: tr)}
                            {--sura= : Sadece bu sureyi işle (test için)}
                            {--delay=150 : İstekler arası bekleme (ms)}
                            {--retry=3 : Başarısız istekte tekrar sayısı}';

    protected $description = "Quran.com API'den kelime bazlı çevirileri çekip quran_words tablosuna yazar";

    private const API = 'https://api.quran.com/api/v4';

    public function handle(): int
    {
        $lang     = $this->option('lang');
        $onlySura = $this->option('sura') ? (int) $this->option('sura') : null;
        $delayMs  = (int) $this->option('delay');
        $maxRetry = (int) $this->option('retry');
        $column   = 'translation_' . $lang;

        if (! Schema::hasColumn('quran_words', $column)) {
            $this->error("'{$column}' kolonu quran_words tablosunda bulunamadı.");
            return self::FAILURE;
        }

        $query = DB::table('quran_words')
            ->select('sura', 'aya')
            ->distinct()
            ->orderBy('sura')
            ->orderBy('aya');

        if ($onlySura) {
            $query->where('sura', $onlySura);
        }

        $ayahs = $query->get();
        $total = $ayahs->count();

        $this->info("Dil: {$lang} | Toplam ayet: {$total} | Gecikme: {$delayMs}ms");
        $this->newLine();

        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | %message%');
        $bar->setMessage('Başlatılıyor...');
        $bar->start();

        $errors  = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($ayahs as $ayah) {
            $bar->setMessage("Sure {$ayah->sura} · Ayet {$ayah->aya} | Hata: {$errors}");

            $words = $this->fetchWithRetry($ayah->sura, $ayah->aya, $lang, $maxRetry);

            if ($words === null) {
                $errors++;
                Log::warning('quran:import-word-translations hata', [
                    'sura' => $ayah->sura,
                    'aya'  => $ayah->aya,
                ]);
                $bar->advance();
                usleep($delayMs * 1000);
                continue;
            }

            if (empty($words)) {
                $skipped++;
                $bar->advance();
                usleep($delayMs * 1000);
                continue;
            }

            // CASE WHEN ile tek sorguda toplu güncelleme
            $cases  = [];
            $params = [];

            foreach ($words as $w) {
                $cases[]  = 'WHEN position = ? AND sura = ? AND aya = ? THEN ?';
                $params[] = $w['position'];
                $params[] = $ayah->sura;
                $params[] = $ayah->aya;
                $params[] = $w['translation'];
            }

            $params[] = $ayah->sura;
            $params[] = $ayah->aya;

            DB::statement(
                "UPDATE quran_words SET {$column} = CASE " . implode(' ', $cases) . " ELSE {$column} END
                 WHERE sura = ? AND aya = ?",
                $params
            );

            $updated++;
            $bar->advance();
            usleep($delayMs * 1000);
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Durum', 'Sayı'],
            [
                ['Güncellenen ayet', $updated],
                ['Atlanan (boş yanıt)', $skipped],
                ['Hata', $errors],
            ]
        );

        if ($errors > 0) {
            $this->warn("{$errors} ayet çekilemedi. Log dosyasını kontrol edin.");
            $this->line('Hataları yeniden denemek için:');
            $this->line("  php artisan quran:import-word-translations --lang={$lang}");
        } else {
            $this->info('Tüm kelime çevirileri başarıyla aktarıldı.');
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @return array<int, array{position: int, translation: string}>|null
     */
    private function fetchWithRetry(int $sura, int $aya, string $lang, int $maxRetry): ?array
    {
        $attempt = 0;

        while ($attempt < $maxRetry) {
            try {
                $response = Http::timeout(15)
                    ->acceptJson()
                    ->get(self::API . "/verses/by_key/{$sura}:{$aya}", [
                        'words'                     => 'true',
                        'word_fields'               => 'translation_text',
                        'language'                  => $lang,
                        'word_translation_language' => $lang,
                    ]);

                if ($response->successful()) {
                    return collect($response->json('verse.words', []))
                        ->where('char_type_name', 'word')
                        ->map(fn ($w) => [
                            'position'    => (int) $w['position'],
                            'translation' => trim($w['translation']['text'] ?? ''),
                        ])
                        ->filter(fn ($w) => $w['translation'] !== '')
                        ->values()
                        ->all();
                }

                // 429 Too Many Requests — uzun bekle
                if ($response->status() === 429) {
                    $this->newLine();
                    $this->warn('Rate limit! 5 saniye bekleniyor...');
                    sleep(5);
                }
            } catch (\Throwable) {
                // bağlantı hatası — tekrar dene
            }

            $attempt++;
            usleep(500_000); // 0.5s bekle
        }

        return null;
    }
}
