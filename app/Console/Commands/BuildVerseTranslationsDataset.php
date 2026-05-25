<?php

namespace App\Console\Commands;

use App\Models\VerseTranslation;
use Illuminate\Console\Command;
use ZipArchive;

class BuildVerseTranslationsDataset extends Command
{
    protected $signature = 'dataset:verse-translations:build
        {--language=* : Only include the given language codes}
        {--meal-key=* : Only include the given meal keys}';

    protected $description = 'Build a compact zipped verse_translations dataset for mobile download';

    public function handle(): int
    {
        $directory = storage_path('data');
        $tsvPath = $directory.'/verse_translations.dataset.tsv';
        $zipPath = $directory.'/verse_translations.dataset.zip';

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $query = VerseTranslation::query()
            ->select(['id', 'sura', 'aya', 'meal_key', 'language', 'text'])
            ->orderBy('id');

        $languages = array_values(array_filter((array) $this->option('language')));
        $mealKeys = array_values(array_filter((array) $this->option('meal-key')));

        if ($languages !== []) {
            $query->whereIn('language', $languages);
        }

        if ($mealKeys !== []) {
            $query->whereIn('meal_key', $mealKeys);
        }

        $file = fopen($tsvPath, 'wb');

        if ($file === false) {
            $this->error('Could not create dataset TSV file.');

            return self::FAILURE;
        }

        $rowCount = 0;

        $query->chunkById(2000, function ($rows) use (&$file, &$rowCount): void {
            foreach ($rows as $row) {
                $text = preg_replace('/\s+/u', ' ', trim((string) $row->text));
                $line = implode("\t", [
                    $row->sura,
                    $row->aya,
                    $row->meal_key,
                    $row->language,
                    str_replace(["\t", "\r", "\n"], ' ', $text ?? ''),
                ]);

                fwrite($file, $line."\n");
                $rowCount++;
            }
        });

        fclose($file);

        $zip = new ZipArchive();
        $opened = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($opened !== true) {
            @unlink($tsvPath);
            $this->error('Could not create zip dataset file.');

            return self::FAILURE;
        }

        $entryName = 'verse_translations.dataset.tsv';
        $zip->addFile($tsvPath, $entryName);

        if (method_exists($zip, 'setCompressionName')) {
            $zip->setCompressionName($entryName, ZipArchive::CM_DEFLATE, 9);
        }

        $zip->close();
        @unlink($tsvPath);

        $zipSize = is_file($zipPath) ? round(filesize($zipPath) / 1024 / 1024, 2) : 0;
        $this->info("Dataset ready: {$zipPath}");
        $this->info("Rows: {$rowCount}");
        $this->info("Zip size: {$zipSize} MB");

        return self::SUCCESS;
    }
}
