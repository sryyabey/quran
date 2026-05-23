<div class="st">
<style>
/* ── Base ───────────────────────────────────────────────────────── */
.st { max-width: 1100px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }

/* ── Page header ────────────────────────────────────────────────── */
.st-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.st-page-title { font-family: 'Cairo', sans-serif; font-size: 22px; font-weight: 700; color: var(--text-dark); margin: 0; display: flex; align-items: center; gap: 9px; }
.st-page-title i { color: var(--teal-mid); font-size: 22px; }
.st-page-sub { font-family: 'Cairo', sans-serif; font-size: 12.5px; color: var(--text-light); margin: 3px 0 0; }

/* ── Stat cards ─────────────────────────────────────────────────── */
.st-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
.st-card {
  background: #fff; border: 1px solid var(--border-strong); border-radius: 14px;
  padding: 1.1rem 1.25rem; display: flex; flex-direction: column; gap: 6px;
  position: relative; overflow: hidden;
}
.st-card::before {
  content: ''; position: absolute; right: -12px; top: -12px;
  width: 70px; height: 70px; border-radius: 50%;
  background: var(--cream2); opacity: .6;
}
.st-card-icon {
  width: 36px; height: 36px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; flex-shrink: 0; position: relative;
}
.st-card-icon.teal   { background: var(--teal-light); color: var(--teal-dark); }
.st-card-icon.gold   { background: var(--gold-light); color: var(--gold); }
.st-card-icon.blue   { background: #eff6ff; color: #1d4ed8; }
.st-card-icon.orange { background: #fff7ed; color: #ea580c; }
.st-card-value { font-family: 'Cairo', sans-serif; font-size: 28px; font-weight: 800; color: var(--text-dark); line-height: 1; }
.st-card-label { font-family: 'Cairo', sans-serif; font-size: 11.5px; color: var(--text-light); font-weight: 600; }
.st-streak-fire { font-size: 14px; }

/* ── Section card ───────────────────────────────────────────────── */
.st-section { background: #fff; border: 1px solid var(--border-strong); border-radius: 16px; overflow: hidden; }
.st-section-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: .85rem 1.25rem; border-bottom: 1px solid var(--border);
  background: var(--cream);
}
.st-section-title {
  font-family: 'Cairo', sans-serif; font-size: 13.5px; font-weight: 700;
  color: var(--text-dark); display: flex; align-items: center; gap: 7px;
}
.st-section-title i { color: var(--teal-mid); font-size: 15px; }
.st-section-badge {
  font-family: 'Cairo', sans-serif; font-size: 10.5px; font-weight: 700;
  color: var(--text-light); background: var(--cream2);
  border: 1px solid var(--border-strong); border-radius: 999px; padding: 2px 9px;
}
.st-section-body { padding: 1.1rem 1.25rem; }

/* ── Heatmap ────────────────────────────────────────────────────── */
.hm-outer { overflow-x: auto; padding-bottom: 4px; }
.hm-month-row { display: flex; gap: 3px; margin-bottom: 4px; }
.hm-month-cell {
  width: 13px; flex-shrink: 0;
  font-family: 'Cairo', sans-serif; font-size: 9.5px; color: var(--text-light);
  white-space: nowrap; overflow: visible;
}
.hm-grid { display: flex; gap: 3px; }
.hm-col { display: flex; flex-direction: column; gap: 3px; }
.hm-cell {
  width: 13px; height: 13px; border-radius: 2px; flex-shrink: 0;
  cursor: default; transition: opacity .1s;
}
.hm-cell:hover { opacity: .75; }
.hm-cell.empty { background: transparent; }
.hm-cell.l0 { background: var(--cream2); border: 1px solid var(--border); }
.hm-cell.l1 { background: rgba(45,155,132,.22); }
.hm-cell.l2 { background: rgba(45,155,132,.45); }
.hm-cell.l3 { background: rgba(45,155,132,.75); }
.hm-cell.l4 { background: var(--teal-dark); }

.hm-footer { display: flex; align-items: center; gap: 6px; margin-top: 10px; }
.hm-legend-label { font-family: 'Cairo', sans-serif; font-size: 10.5px; color: var(--text-light); }
.hm-legend-cells { display: flex; gap: 3px; }

/* ── Bottom grid ────────────────────────────────────────────────── */
.st-bottom { display: grid; grid-template-columns: 3fr 2fr; gap: 12px; }

/* ── Top suras ──────────────────────────────────────────────────── */
.st-sura-row { display: flex; align-items: center; gap: 10px; padding: .5rem 0; }
.st-sura-row + .st-sura-row { border-top: 1px solid var(--border); }
.st-sura-num {
  font-family: 'Cairo', sans-serif; font-size: 10.5px; font-weight: 700;
  color: #fff; background: var(--teal-dark); border-radius: 6px;
  padding: 2px 7px; flex-shrink: 0; min-width: 28px; text-align: center;
}
.st-sura-name { font-family: 'Cairo', sans-serif; font-size: 13px; font-weight: 600; color: var(--text-dark); width: 90px; flex-shrink: 0; }
.st-sura-bar-wrap { flex: 1; background: var(--cream2); border-radius: 999px; height: 8px; overflow: hidden; }
.st-sura-bar { height: 100%; background: linear-gradient(90deg, var(--teal-mid), var(--teal-dark)); border-radius: 999px; transition: width .4s ease; }
.st-sura-count { font-family: 'Cairo', sans-serif; font-size: 12px; font-weight: 700; color: var(--teal-dark); flex-shrink: 0; width: 28px; text-align: right; }

/* ── Notes by type ──────────────────────────────────────────────── */
.st-type-row { display: flex; flex-direction: column; gap: 6px; padding: .65rem 0; }
.st-type-row + .st-type-row { border-top: 1px solid var(--border); }
.st-type-head { display: flex; align-items: center; justify-content: space-between; }
.st-type-badge {
  font-family: 'Cairo', sans-serif; font-size: 11px; font-weight: 700;
  padding: 2px 9px; border-radius: 6px;
}
.st-type-note     { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.st-type-footnote { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.st-type-research { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
.st-type-meta { font-family: 'Cairo', sans-serif; font-size: 12px; color: var(--text-light); }
.st-type-meta strong { color: var(--text-dark); }
.st-type-bar-wrap { background: var(--cream2); border-radius: 999px; height: 7px; overflow: hidden; }
.st-type-bar-note     { height: 100%; background: #3b82f6; border-radius: 999px; }
.st-type-bar-footnote { height: 100%; background: #f59e0b; border-radius: 999px; }
.st-type-bar-research { height: 100%; background: #8b5cf6; border-radius: 999px; }

/* ── Empty ──────────────────────────────────────────────────────── */
.st-empty {
  text-align: center; padding: 2rem 1rem;
  font-family: 'Cairo', sans-serif; color: var(--text-light);
  display: flex; flex-direction: column; align-items: center; gap: 8px;
}
.st-empty i { font-size: 36px; color: var(--sand); }
.st-empty-title { font-size: 14px; font-weight: 600; color: var(--text-mid); }

/* ── Responsive ─────────────────────────────────────────────────── */
@media (max-width: 900px) {
  .st-cards  { grid-template-columns: repeat(2, 1fr); }
  .st-bottom { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
  .st-cards { grid-template-columns: 1fr 1fr; gap: 8px; }
  .st-card  { padding: .85rem 1rem; }
  .st-card-value { font-size: 22px; }
}
</style>

{{-- ── Başlık ────────────────────────────────────────────────────── --}}
<div class="st-header">
  <div>
    <h1 class="st-page-title"><i class="ti ti-chart-bar"></i> {{ __('Statistics') }}</h1>
    <p class="st-page-sub">{{ __('Summary of your study habits') }}</p>
  </div>
</div>

{{-- ── Özet kartlar ─────────────────────────────────────────────── --}}
<div class="st-cards">
  <div class="st-card">
    <div class="st-card-icon teal"><i class="ti ti-notes"></i></div>
    <div class="st-card-value">{{ $this->summary['total'] }}</div>
    <div class="st-card-label">{{ __('Total Notes') }}</div>
  </div>
  <div class="st-card">
    <div class="st-card-icon gold"><i class="ti ti-book-2"></i></div>
    <div class="st-card-value">{{ $this->summary['active_suras'] }}</div>
    <div class="st-card-label">{{ __('Studied Suras') }}</div>
  </div>
  <div class="st-card">
    <div class="st-card-icon blue"><i class="ti ti-calendar-month"></i></div>
    <div class="st-card-value">{{ $this->summary['this_month'] }}</div>
    <div class="st-card-label">{{ __('Added This Month') }}</div>
  </div>
  <div class="st-card">
    <div class="st-card-icon orange"><i class="ti ti-flame"></i></div>
    <div class="st-card-value">
      {{ $this->summary['streak'] }}
      @if($this->summary['streak'] > 0)
        <span class="st-streak-fire">🔥</span>
      @endif
    </div>
    <div class="st-card-label">{{ __('Daily Streak') }}</div>
  </div>
</div>

{{-- ── Aktivite ısı haritası ────────────────────────────────────── --}}
<div class="st-section">
  <div class="st-section-head">
    <div class="st-section-title">
      <i class="ti ti-activity"></i> {{ __('Activity Map') }}
    </div>
    <span class="st-section-badge">{{ __('Last 52 Weeks') }}</span>
  </div>
  <div class="st-section-body">
    @if(empty($this->heatmapWeeks))
      <div class="st-empty">
        <i class="ti ti-chart-dots"></i>
        <div class="st-empty-title">{{ __('No activity yet') }}</div>
      </div>
    @else
      <div class="hm-outer">
        {{-- Ay etiketleri --}}
        <div class="hm-month-row">
          @foreach($this->heatmapWeeks as $week)
            <div class="hm-month-cell">{{ $week['month_label'] ?? '' }}</div>
          @endforeach
        </div>

        {{-- Hücre ızgarası --}}
        <div class="hm-grid">
          @foreach($this->heatmapWeeks as $week)
            <div class="hm-col">
              @foreach($week['days'] as $day)
                @if($day === null)
                  <div class="hm-cell empty"></div>
                @else
                  <div
                    class="hm-cell l{{ $day['level'] }}"
                    title="{{ \Carbon\Carbon::parse($day['date'])->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}: {{ $day['count'] }} {{ __('notes') }}"
                  ></div>
                @endif
              @endforeach
            </div>
          @endforeach
        </div>

        {{-- Lejant --}}
        <div class="hm-footer">
          <span class="hm-legend-label">{{ __('Low') }}</span>
          <div class="hm-legend-cells">
            <div class="hm-cell l0" style="width:13px;height:13px;"></div>
            <div class="hm-cell l1" style="width:13px;height:13px;"></div>
            <div class="hm-cell l2" style="width:13px;height:13px;"></div>
            <div class="hm-cell l3" style="width:13px;height:13px;"></div>
            <div class="hm-cell l4" style="width:13px;height:13px;"></div>
          </div>
          <span class="hm-legend-label">{{ __('High') }}</span>
        </div>
      </div>
    @endif
  </div>
</div>

{{-- ── Alt satır: Sureler + Türler ─────────────────────────────── --}}
<div class="st-bottom">

  {{-- En çok çalışılan sureler --}}
  <div class="st-section">
    <div class="st-section-head">
      <div class="st-section-title">
        <i class="ti ti-trophy"></i> {{ __('Most Studied Suras') }}
      </div>
      <span class="st-section-badge">{{ __('Top 8') }}</span>
    </div>
    <div class="st-section-body">
      @if($this->topSuras['items']->isEmpty())
        <div class="st-empty">
          <i class="ti ti-book-off"></i>
          <div class="st-empty-title">{{ __('No notes yet') }}</div>
        </div>
      @else
        @foreach($this->topSuras['items'] as $row)
          @php $pct = round($row->note_count / $this->topSuras['max'] * 100); @endphp
          <div class="st-sura-row">
            <span class="st-sura-num">{{ $row->sura }}</span>
            <span class="st-sura-name">{{ \App\Livewire\QuranNotesRangePage::getSuraNameStatic($row->sura) }}</span>
            <div class="st-sura-bar-wrap">
              <div class="st-sura-bar" style="width:{{ $pct }}%"></div>
            </div>
            <span class="st-sura-count">{{ $row->note_count }}</span>
          </div>
        @endforeach
      @endif
    </div>
  </div>

  {{-- Not türü dağılımı --}}
  <div class="st-section">
    <div class="st-section-head">
      <div class="st-section-title">
        <i class="ti ti-chart-donut"></i> {{ __('Type Distribution') }}
      </div>
      <span class="st-section-badge">{{ $this->summary['total'] }} {{ __('notes') }}</span>
    </div>
    <div class="st-section-body">
      @if($this->notesByType->isEmpty())
        <div class="st-empty">
          <i class="ti ti-notes-off"></i>
          <div class="st-empty-title">{{ __('No notes yet') }}</div>
        </div>
      @else
        @foreach($this->notesByType as $t)
          <div class="st-type-row">
            <div class="st-type-head">
              <span class="st-type-badge st-type-{{ $t['type'] }}">{{ $t['label'] }}</span>
              <span class="st-type-meta">
                <strong>{{ $t['count'] }}</strong> {{ __('notes') }} · %{{ $t['percent'] }}
              </span>
            </div>
            <div class="st-type-bar-wrap">
              <div class="st-type-bar-{{ $t['type'] }}" style="width:{{ $t['percent'] }}%"></div>
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>

</div>

</div>
