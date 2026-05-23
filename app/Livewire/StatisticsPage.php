<?php

namespace App\Livewire;

use App\Models\ResearchNote;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class StatisticsPage extends Component
{
    private const MONTHS_TR = [
        1 => 'Oca', 2 => 'Şub',  3 => 'Mar', 4  => 'Nis',
        5 => 'May', 6 => 'Haz',  7 => 'Tem', 8  => 'Ağu',
        9 => 'Eyl', 10 => 'Eki', 11 => 'Kas', 12 => 'Ara',
    ];

    /* ── Özet sayılar ─────────────────────────────────────────────── */

    #[Computed]
    public function summary(): array
    {
        $user = auth()->user();
        if (! $user) {
            return ['total' => 0, 'active_suras' => 0, 'this_month' => 0, 'streak' => 0];
        }

        return [
            'total'        => ResearchNote::where('user_id', $user->id)->count(),
            'active_suras' => ResearchNote::where('user_id', $user->id)->distinct('sura')->count('sura'),
            'this_month'   => ResearchNote::where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'streak'       => $this->calcStreak($user->id),
        ];
    }

    /* ── En çok çalışılan sureler ─────────────────────────────────── */

    #[Computed]
    public function topSuras(): array
    {
        $user = auth()->user();
        if (! $user) {
            return ['items' => collect(), 'max' => 1];
        }

        $items = ResearchNote::where('user_id', $user->id)
            ->selectRaw('sura, COUNT(*) as note_count')
            ->groupBy('sura')
            ->orderByDesc('note_count')
            ->limit(8)
            ->get();

        return [
            'items' => $items,
            'max'   => $items->max('note_count') ?: 1,
        ];
    }

    /* ── Not türü dağılımı ────────────────────────────────────────── */

    #[Computed]
    public function notesByType(): Collection
    {
        $user = auth()->user();
        if (! $user) {
            return collect();
        }

        $total = ResearchNote::where('user_id', $user->id)->count();
        if ($total === 0) {
            return collect();
        }

        return ResearchNote::where('user_id', $user->id)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($r) => [
                'type'    => $r->type,
                'label'   => match ($r->type) {
                    'note'     => 'Not',
                    'footnote' => 'Dipnot',
                    'research' => 'Araştırma',
                    default    => $r->type,
                },
                'count'   => $r->count,
                'percent' => round($r->count / $total * 100),
            ]);
    }

    /* ── Aktivite ısı haritası (son 52 hafta) ─────────────────────── */

    #[Computed]
    public function heatmapWeeks(): array
    {
        $user = auth()->user();
        if (! $user) {
            return [];
        }

        $endDate   = now()->startOfDay();
        $startDate = $endDate->copy()->subWeeks(52)->startOfWeek(Carbon::MONDAY);

        $dailyCounts = ResearchNote::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $weeks   = [];
        $weekIdx = 0;
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            if (! isset($weeks[$weekIdx])) {
                $weeks[$weekIdx] = ['month_label' => null, 'days' => []];
            }

            $dateStr = $current->format('Y-m-d');
            $count   = $dailyCounts[$dateStr] ?? 0;

            if ($current->day === 1) {
                $weeks[$weekIdx]['month_label'] = self::MONTHS_TR[$current->month];
            }

            $weeks[$weekIdx]['days'][] = [
                'date'  => $dateStr,
                'count' => $count,
                'level' => match (true) {
                    $count === 0 => 0,
                    $count === 1 => 1,
                    $count <= 3  => 2,
                    $count <= 6  => 3,
                    default      => 4,
                },
            ];

            if ($current->dayOfWeekIso === 7) {
                $weekIdx++;
            }

            $current->addDay();
        }

        /* Son haftayı 7 güne tamamla */
        $last = count($weeks) - 1;
        while (count($weeks[$last]['days']) < 7) {
            $weeks[$last]['days'][] = null;
        }

        return $weeks;
    }

    /* ── Render ───────────────────────────────────────────────────── */

    public function render(): View
    {
        return view('livewire.statistics-page');
    }

    /* ── Private ──────────────────────────────────────────────────── */

    private function calcStreak(int $userId): int
    {
        $today = now()->startOfDay();

        $dates = ResearchNote::where('user_id', $userId)
            ->where('created_at', '>=', $today->copy()->subDays(366))
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->pluck('date')
            ->flip()
            ->toArray();

        $streak = 0;
        $check  = $today->copy();

        if (! isset($dates[$check->format('Y-m-d')])) {
            $check->subDay();
        }

        while (isset($dates[$check->format('Y-m-d')])) {
            $streak++;
            $check->subDay();
        }

        return $streak;
    }
}
