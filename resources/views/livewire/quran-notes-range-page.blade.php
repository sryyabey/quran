@php
  /* Sure listesi — Alpine combobox için */
  $suraJsData = collect(\App\Livewire\QuranNotesRangePage::getSuraNames())
      ->map(fn($name, $num) => ['n' => $num, 'l' => $name])
      ->values()->all();
@endphp

<div x-data="{ expandedAyas: {} }" class="qnr">
<style>
/* ── Base ───────────────────────────────────────────────────────── */
.qnr { max-width: 1100px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }

/* ── Page header ────────────────────────────────────────────────── */
.qnr-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.qnr-page-title { font-family: 'Cairo', sans-serif; font-size: 22px; font-weight: 700; color: var(--text-dark); margin: 0; display: flex; align-items: center; gap: 9px; }
.qnr-page-title i { color: var(--teal-mid); font-size: 22px; }
.qnr-page-sub { font-family: 'Cairo', sans-serif; font-size: 12.5px; color: var(--text-light); margin: 3px 0 0; }
.qnr-header-link { display: flex; align-items: center; gap: 5px; font-family: 'Cairo', sans-serif; font-size: 12px; color: var(--text-light); text-decoration: none; transition: color .13s; }
.qnr-header-link:hover { color: var(--teal-dark); }

/* ── Filter card ────────────────────────────────────────────────── */
.qnr-card { background: #fff; border: 1px solid var(--border-strong); border-radius: 16px; overflow: visible; }
.qnr-card-section { padding: 1.1rem 1.25rem; }
.qnr-card-section + .qnr-card-section { border-top: 1px solid var(--border); }
.qnr-section-label {
  font-family: 'Cairo', sans-serif; font-size: 10.5px; font-weight: 700;
  color: var(--text-light); text-transform: uppercase; letter-spacing: .8px;
  margin-bottom: .65rem; display: flex; align-items: center; gap: 6px;
}
.qnr-section-label i { font-size: 12px; }

/* ── Range picker ───────────────────────────────────────────────── */
.qnr-range-row { display: grid; grid-template-columns: 1fr auto 1fr; gap: 10px; align-items: center; }
.qnr-range-arrow {
  display: flex; align-items: center; justify-content: center;
  width: 36px; height: 36px; border-radius: 50%;
  background: var(--cream2); border: 1px solid var(--border-strong);
  color: var(--teal-mid); font-size: 16px; flex-shrink: 0; margin-top: 22px;
}
.qnr-range-side { display: flex; flex-direction: column; gap: 7px; }
.qnr-field-label {
  font-family: 'Cairo', sans-serif; font-size: 11px; font-weight: 700;
  color: var(--text-light); text-transform: uppercase; letter-spacing: .5px;
  display: block; margin-bottom: 1px;
}
.qnr-aya-select {
  width: 100%; padding: 9px 30px 9px 12px;
  border: 1px solid var(--border-strong); border-radius: 10px;
  font-size: 13px; font-family: 'Cairo', sans-serif;
  background: var(--cream) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a7a60' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;
  color: var(--text-dark); appearance: none; cursor: pointer;
  transition: border-color .15s, box-shadow .15s;
}
.qnr-aya-select:focus { outline: none; border-color: var(--teal-mid); box-shadow: 0 0 0 3px rgba(45,155,132,.12); background-color: #fff; }

/* ── Sura Combobox ──────────────────────────────────────────────── */
.sc-wrap { position: relative; }
.sc-trigger {
  width: 100%; display: flex; align-items: center; gap: 8px;
  padding: 9px 12px; border: 1px solid var(--border-strong); border-radius: 10px;
  background: var(--cream); cursor: pointer; text-align: left;
  font-family: 'Cairo', sans-serif; font-size: 13px; color: var(--text-dark);
  transition: border-color .15s, box-shadow .15s, background .1s;
}
.sc-trigger:hover { background: #fff; border-color: var(--teal-mid); }
.sc-trigger:focus { outline: none; border-color: var(--teal-mid); box-shadow: 0 0 0 3px rgba(45,155,132,.12); }
.sc-trigger.open { border-color: var(--teal-mid); background: #fff; box-shadow: 0 0 0 3px rgba(45,155,132,.12); border-bottom-left-radius: 0; border-bottom-right-radius: 0; }
.sc-trigger-num {
  font-size: 10.5px; font-weight: 700; color: #fff;
  background: var(--teal-dark); border-radius: 6px;
  padding: 1px 6px; flex-shrink: 0;
}
.sc-trigger-name { flex: 1; font-weight: 500; }
.sc-trigger-icon { font-size: 14px; color: var(--text-light); flex-shrink: 0; margin-left: auto; transition: transform .15s; }
.sc-trigger.open .sc-trigger-icon { transform: rotate(180deg); }

.sc-dropdown {
  position: absolute; top: 100%; left: 0; right: 0; z-index: 200;
  background: #fff; border: 1px solid var(--teal-mid); border-top: none;
  border-radius: 0 0 12px 12px; box-shadow: 0 8px 24px rgba(26,107,90,.15);
  overflow: hidden;
}
.sc-search-row {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 10px; border-bottom: 1px solid var(--border);
  background: var(--cream);
}
.sc-search-row i { font-size: 14px; color: var(--text-light); flex-shrink: 0; }
.sc-search-input {
  flex: 1; border: none; outline: none; background: transparent;
  font-family: 'Cairo', sans-serif; font-size: 13px; color: var(--text-dark);
}
.sc-search-input::placeholder { color: var(--text-light); }
.sc-count { font-family: 'Cairo', sans-serif; font-size: 10.5px; color: var(--text-light); flex-shrink: 0; }

.sc-list {
  max-height: 260px; overflow-y: auto;
  display: flex; flex-direction: column;
  scrollbar-width: thin; scrollbar-color: var(--sand) transparent;
}
.sc-option {
  display: flex; align-items: center; gap: 9px;
  padding: 8px 12px; cursor: pointer; border: none; background: none;
  width: 100%; text-align: left; transition: background .1s;
}
.sc-option:hover, .sc-option.is-focused { background: var(--teal-light); }
.sc-option.is-selected { background: var(--teal-light); }
.sc-option.is-selected .sc-opt-name { color: var(--teal-dark); font-weight: 700; }
.sc-opt-num {
  font-family: 'Cairo', sans-serif; font-size: 10.5px; font-weight: 700;
  color: var(--text-light); background: var(--cream2); border: 1px solid var(--border-strong);
  border-radius: 6px; padding: 1px 6px; flex-shrink: 0; min-width: 26px; text-align: center;
}
.sc-option.is-selected .sc-opt-num, .sc-option.is-focused .sc-opt-num { background: rgba(45,155,132,.15); border-color: rgba(45,155,132,.3); color: var(--teal-dark); }
.sc-opt-name { font-family: 'Cairo', sans-serif; font-size: 13px; color: var(--text-dark); }
.sc-no-result { padding: 14px 12px; font-family: 'Cairo', sans-serif; font-size: 12.5px; color: var(--text-light); text-align: center; }

/* ── Filter row ─────────────────────────────────────────────────── */
.qnr-filter-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.qnr-type-group { display: flex; gap: 4px; flex-wrap: wrap; }
.qnr-type-btn {
  padding: 6px 13px; border-radius: 8px; border: 1.5px solid var(--border-strong);
  background: #fff; font-family: 'Cairo', sans-serif; font-size: 12px; font-weight: 600;
  color: var(--text-mid); cursor: pointer; transition: all .12s;
  display: inline-flex; align-items: center; gap: 5px;
}
.qnr-type-btn:hover { background: var(--cream2); }
.qnr-type-btn.active-all      { background: var(--text-dark); color: #fff; border-color: var(--text-dark); }
.qnr-type-btn.active-note     { background: #eff6ff; color: #1d4ed8; border-color: #93c5fd; }
.qnr-type-btn.active-footnote { background: #fffbeb; color: #b45309; border-color: #fcd34d; }
.qnr-type-btn.active-research { background: #f5f3ff; color: #6d28d9; border-color: #c4b5fd; }

.qnr-tag-wrap { display: flex; align-items: center; gap: 7px; margin-left: auto; }
.qnr-tag-select {
  padding: 6px 28px 6px 10px; border: 1.5px solid var(--border-strong);
  border-radius: 8px; font-size: 12.5px; font-family: 'Cairo', sans-serif;
  background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a7a60' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 8px center;
  color: var(--text-dark); appearance: none; cursor: pointer;
}
.qnr-tag-select:focus { outline: none; border-color: var(--teal-mid); }

/* ── Actions row ────────────────────────────────────────────────── */
.qnr-actions-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.qnr-btn-load {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 22px; border-radius: 10px;
  background: var(--teal-dark); color: #fff;
  border: none; font-family: 'Cairo', sans-serif; font-size: 13.5px; font-weight: 700;
  cursor: pointer; transition: background .15s;
}
.qnr-btn-load:hover:not(:disabled) { background: var(--teal-mid); }
.qnr-btn-load:disabled { opacity: .45; cursor: not-allowed; }
.qnr-range-invalid {
  display: flex; align-items: center; gap: 6px;
  font-family: 'Cairo', sans-serif; font-size: 12px; color: #b42318;
  background: #fee2e2; border: 1px solid #fca5a5;
  border-radius: 8px; padding: 6px 12px;
}
.qnr-btn-clear {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 16px; border-radius: 10px;
  border: 1.5px solid var(--border-strong); background: #fff;
  color: var(--text-mid); font-family: 'Cairo', sans-serif; font-size: 13px;
  font-weight: 600; cursor: pointer; transition: all .13s;
}
.qnr-btn-clear:hover { background: var(--cream2); }

/* ── Result summary bar ─────────────────────────────────────────── */
.qnr-summary-bar {
  display: flex; align-items: center; justify-content: space-between;
  background: #fff; border: 1px solid var(--border-strong); border-radius: 12px;
  padding: .75rem 1.1rem; flex-wrap: wrap; gap: 10px;
}
.qnr-summary-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.qnr-summary-chip {
  display: inline-flex; align-items: center; gap: 5px;
  font-family: 'Cairo', sans-serif; font-size: 12px; color: var(--text-mid);
  background: var(--cream2); border: 1px solid var(--border-strong);
  border-radius: 8px; padding: 3px 10px;
}
.qnr-summary-chip i { font-size: 12px; color: var(--teal-mid); }
.qnr-summary-count { font-family: 'Cairo', sans-serif; font-size: 14px; font-weight: 700; color: var(--teal-dark); }
.qnr-summary-actions { display: flex; align-items: center; gap: 7px; }
.qnr-print-btn, .qnr-share-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 14px; border-radius: 9px; border: 1.5px solid;
  font-family: 'Cairo', sans-serif; font-size: 12.5px; font-weight: 600;
  cursor: pointer; transition: all .13s; white-space: nowrap;
}
.qnr-print-btn { border-color: var(--border-strong); background: #fff; color: var(--text-mid); }
.qnr-print-btn:hover { background: var(--cream2); }
.qnr-share-btn { border-color: rgba(45,155,132,.35); background: var(--teal-light); color: var(--teal-dark); }
.qnr-share-btn:hover { background: rgba(45,155,132,.2); }

/* ── Sura section ───────────────────────────────────────────────── */
.qnr-sura-section { }
.qnr-sura-header {
  display: flex; align-items: center; gap: 12px;
  background: linear-gradient(135deg, var(--teal-dark) 0%, #0f4a3d 100%);
  border-radius: 14px 14px 0 0; padding: .9rem 1.25rem;
  position: relative; overflow: hidden;
}
.qnr-sura-header::after {
  content: ''; position: absolute; right: -10px; top: -10px;
  width: 80px; height: 80px; background: rgba(255,255,255,.04); border-radius: 50%;
}
.qnr-sura-num-badge {
  width: 36px; height: 36px; border-radius: 10px;
  background: rgba(212,168,67,.2); border: 1px solid rgba(212,168,67,.35);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Cairo', sans-serif; font-size: 13px; font-weight: 700;
  color: var(--gold-mid); flex-shrink: 0; position: relative; z-index: 1;
}
.qnr-sura-title { flex: 1; position: relative; z-index: 1; }
.qnr-sura-name  { font-family: 'Cairo', sans-serif; font-size: 16px; font-weight: 700; color: #fff; }
.qnr-sura-info  { font-family: 'Cairo', sans-serif; font-size: 11px; color: rgba(255,255,255,.5); margin-top: 1px; }
.qnr-sura-note-pill {
  font-family: 'Cairo', sans-serif; font-size: 11px; font-weight: 600;
  color: rgba(255,255,255,.75); background: rgba(255,255,255,.1);
  border: 1px solid rgba(255,255,255,.15); border-radius: 999px;
  padding: 3px 11px; position: relative; z-index: 1; white-space: nowrap;
}

/* ── Aya block ──────────────────────────────────────────────────── */
.qnr-aya-block { border: 1px solid var(--border); border-top: none; }
.qnr-aya-block:last-child { border-radius: 0 0 14px 14px; }
.qnr-aya-header {
  display: flex; align-items: flex-start; gap: 12px;
  padding: .9rem 1.25rem; cursor: pointer;
  background: var(--gold-pale); transition: background .12s;
  border-top: 1px solid var(--border);
}
.qnr-aya-header:hover { background: var(--gold-light); }
.qnr-aya-ref {
  font-family: 'Cairo', sans-serif; font-size: 11px; font-weight: 700;
  color: var(--teal-dark); background: var(--teal-light); border: 1px solid rgba(45,155,132,.22);
  border-radius: 7px; padding: 3px 8px; white-space: nowrap; flex-shrink: 0; margin-top: 5px;
}
.qnr-aya-arabic {
  font-family: 'Amiri', serif; font-size: 22px; line-height: 2;
  color: var(--text-dark); direction: rtl; text-align: right; flex: 1;
}
.qnr-aya-end-mark { color: var(--gold); font-size: 18px; }
.qnr-aya-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; margin-top: 4px; }
.qnr-aya-note-count {
  font-family: 'Cairo', sans-serif; font-size: 10.5px; font-weight: 600;
  color: var(--teal-dark); background: var(--teal-light); border: 1px solid rgba(45,155,132,.2);
  border-radius: 999px; padding: 2px 8px; white-space: nowrap;
}
.qnr-aya-toggle { font-size: 13px; color: var(--text-light); }

/* ── Notes grid ─────────────────────────────────────────────────── */
.qnr-notes-panel {
  display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;
  padding: .9rem 1.25rem; background: #fdfcf9;
}
.qnr-note-card {
  background: #fff; border: 1px solid var(--border); border-radius: 12px;
  display: flex; flex-direction: column; gap: 0;
  overflow: hidden; transition: box-shadow .12s;
  border-left: 3px solid;
}
.qnr-note-card:hover { box-shadow: 0 3px 12px rgba(26,107,90,.08); }
.qnr-note-card.type-note     { border-left-color: #3b82f6; }
.qnr-note-card.type-footnote { border-left-color: #f59e0b; }
.qnr-note-card.type-research { border-left-color: #8b5cf6; }

.qnr-note-head {
  display: flex; align-items: center; gap: 7px;
  padding: .55rem .85rem; border-bottom: 1px solid var(--border);
  background: var(--cream);
}
.qnr-type-badge {
  font-family: 'Cairo', sans-serif; font-size: 10px; font-weight: 700;
  padding: 2px 8px; border-radius: 5px; letter-spacing: .3px; flex-shrink: 0;
}
.qnr-type-note     { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.qnr-type-footnote { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.qnr-type-research { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
.qnr-note-title { font-family: 'Cairo', sans-serif; font-size: 13px; font-weight: 700; color: var(--text-dark); flex: 1; }

.qnr-note-body-wrap { padding: .75rem .85rem; display: flex; flex-direction: column; gap: 8px; flex: 1; }
.qnr-word-chip {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--teal-light); border: 1px solid rgba(45,155,132,.22);
  border-radius: 8px; padding: 4px 10px;
}
.qnr-word-label { font-family: 'Cairo', sans-serif; font-size: 10px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: .4px; }
.qnr-word-ar { font-family: 'Amiri', serif; font-size: 20px; direction: rtl; color: var(--teal-dark); line-height: 1; }

.qnr-note-text { font-family: 'Lora', serif; font-size: 13px; line-height: 1.75; color: var(--text-dark); white-space: pre-wrap; }

.qnr-note-footer { display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap; padding: .5rem .85rem; border-top: 1px solid var(--border); background: var(--cream); }
.qnr-tags { display: flex; gap: 4px; flex-wrap: wrap; }
.qnr-tag { padding: 2px 8px; border-radius: 999px; font-family: 'Cairo', sans-serif; font-size: 10.5px; border: 1px solid var(--border-strong); background: #fff; color: var(--text-mid); }
.qnr-note-date { font-family: 'Cairo', sans-serif; font-size: 10.5px; color: var(--text-light); white-space: nowrap; }

/* ── Empty ──────────────────────────────────────────────────────── */
.qnr-empty { text-align: center; padding: 48px 20px; background: #fff; border: 1px solid var(--border); border-top: none; border-radius: 0 0 14px 14px; }
.qnr-empty-icon  { font-size: 44px; color: var(--sand); margin-bottom: 12px; }
.qnr-empty-title { font-family: 'Cairo', sans-serif; font-size: 16px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px; }
.qnr-empty-sub   { font-family: 'Cairo', sans-serif; font-size: 12.5px; color: var(--text-light); }

/* ── Share modal ────────────────────────────────────────────────── */
.qnr-modal-overlay {
  position: fixed; inset: 0; background: rgba(22,16,8,.55);
  display: flex; align-items: center; justify-content: center;
  padding: 1rem; z-index: 1300;
  backdrop-filter: blur(3px);
}
.qnr-modal {
  width: min(640px, 100%); background: #fff;
  border: 1px solid var(--border-strong); border-radius: 18px; overflow: hidden;
  box-shadow: 0 20px 60px rgba(0,0,0,.2);
}
.qnr-modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1rem 1.25rem; border-bottom: 1px solid var(--border);
  background: var(--cream);
}
.qnr-modal-title { font-family: 'Cairo', sans-serif; font-size: 16px; font-weight: 700; color: var(--teal-dark); margin: 0; display: flex; align-items: center; gap: 8px; }
.qnr-modal-close { border: 1px solid var(--border-strong); background: #fff; border-radius: 8px; width: 30px; height: 30px; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; transition: background .13s; }
.qnr-modal-close:hover { background: var(--cream2); }
.qnr-modal-body { padding: 1.1rem 1.25rem; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.qnr-modal-field { display: flex; flex-direction: column; gap: 5px; }
.qnr-modal-label { font-family: 'Cairo', sans-serif; font-size: 11px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: .4px; }
.qnr-modal-input, .qnr-modal-select {
  border: 1px solid var(--border-strong); border-radius: 9px;
  padding: 8px 11px; font-family: 'Cairo', sans-serif; font-size: 13px;
  color: var(--text-dark); outline: none; transition: border-color .15s;
}
.qnr-modal-input:focus, .qnr-modal-select:focus { border-color: var(--teal-mid); }
.qnr-modal-foot { display: flex; align-items: center; justify-content: flex-end; gap: 8px; padding: .9rem 1.25rem; border-top: 1px solid var(--border); background: var(--cream); }
.qnr-modal-cancel { font-family: 'Cairo', sans-serif; font-size: 13px; background: none; border: 1px solid var(--border-strong); border-radius: 9px; padding: 7px 16px; cursor: pointer; color: var(--text-mid); }
.qnr-modal-submit { font-family: 'Cairo', sans-serif; font-size: 13px; font-weight: 700; background: var(--teal-dark); color: #fff; border: none; border-radius: 9px; padding: 8px 20px; cursor: pointer; display: flex; align-items: center; gap: 6px; }
.qnr-modal-submit:hover { background: var(--teal-mid); }
.qnr-modal-result { padding: .9rem 1.25rem; border-top: 1px solid var(--border); }
.qnr-modal-url { width: 100%; border: 1px solid var(--border-strong); border-radius: 9px; padding: 9px 11px; font-family: monospace; font-size: 12px; color: var(--text-dark); background: var(--cream); cursor: text; }
.qnr-modal-url:focus { outline: none; }
.qnr-modal-socials { display: flex; gap: 7px; flex-wrap: wrap; margin-top: 10px; }
.qnr-social-btn { display: inline-flex; align-items: center; gap: 6px; border: 1.5px solid var(--border-strong); border-radius: 9px; padding: 7px 12px; font-family: 'Cairo', sans-serif; font-size: 12.5px; text-decoration: none; color: var(--text-mid); background: #fff; cursor: pointer; transition: background .13s; }
.qnr-social-btn:hover { background: var(--cream2); }
.qnr-social-btn.wa { border-color: rgba(37,211,102,.45); color: #1f9f4b; }
.qnr-social-btn.fb { border-color: rgba(24,119,242,.45); color: #1a62c5; }
.qnr-social-btn.cp { border-color: rgba(45,155,132,.35); color: var(--teal-dark); }

/* ── Print header ───────────────────────────────────────────────── */
.qnr-print-header { display: none; }

/* ── Responsive ─────────────────────────────────────────────────── */
@media (max-width: 820px) {
  .qnr-range-row { grid-template-columns: 1fr; }
  .qnr-range-arrow { display: none; }
  .qnr-modal-body { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .qnr-notes-panel { grid-template-columns: 1fr; }
  .qnr-aya-arabic  { font-size: 18px; }
  .qnr-summary-bar { flex-direction: column; align-items: flex-start; }
  .qnr-tag-wrap { margin-left: 0; }
}

/* ── Print ──────────────────────────────────────────────────────── */
@media print {
  html, body        { overflow: visible !important; height: auto !important; }
  .app              { display: block !important; height: auto !important; }
  .topbar, .sidebar { display: none !important; }
  .main             { overflow: visible !important; padding: 10mm 14mm !important; background: #fff !important; }
  .qnr-no-print     { display: none !important; }
  .qnr-print-header { display: block !important; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 2px solid #1a6b5a; }
  .qnr-print-title  { font-family: 'Cairo', sans-serif; font-size: 20px; font-weight: 700; color: #1a6b5a; margin: 0 0 4px; }
  .qnr-print-meta   { font-family: 'Cairo', sans-serif; font-size: 12px; color: #8a7a60; }
  .qnr              { max-width: 100% !important; gap: 8px !important; }
  .qnr-sura-header  { background: #1a6b5a !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; border-radius: 8px 8px 0 0 !important; }
  .qnr-sura-section { break-before: page; }
  .qnr-sura-section:first-child { break-before: auto; }
  .qnr-aya-block    { break-inside: avoid; }
  .qnr-notes-panel  { display: grid !important; grid-template-columns: repeat(2,1fr) !important; }
  .qnr-note-card    { break-inside: avoid; page-break-inside: avoid; }
}
</style>

{{-- ──────────────────────────────────────────────────────────────── --}}
{{-- Page Header                                                       --}}
{{-- ──────────────────────────────────────────────────────────────── --}}
<div class="qnr-header qnr-no-print">
  <div>
    <h1 class="qnr-page-title">
      <i class="ti ti-notebook"></i> {{ __('Note Research') }}
    </h1>
    <p class="qnr-page-sub">{{ __('Select a sura and verse range to view your notes on one page.') }}</p>
  </div>
  <a href="{{ route('user.quran-text') }}" class="qnr-header-link">
    <i class="ti ti-book" style="font-size:14px;"></i> {{ __('Back to Quran Text') }}
  </a>
</div>

{{-- ──────────────────────────────────────────────────────────────── --}}
{{-- Filter card                                                       --}}
{{-- ──────────────────────────────────────────────────────────────── --}}
<div class="qnr-card qnr-no-print">

  {{-- ①  Aralık seçimi ──────────────────────────────────────────── --}}
  <div class="qnr-card-section">
    <div class="qnr-section-label"><i class="ti ti-arrows-left-right"></i> {{ __('Verse Range') }}</div>
    <div class="qnr-range-row">

      {{-- Başlangıç --}}
      <div class="qnr-range-side">
        <div>
          <span class="qnr-field-label">{{ __('Start Sura') }}</span>
          <div
            x-data="suraCombo('startSura', {{ $startSura ?? 1 }})"
            class="sc-wrap"
            @click.outside="close()"
          >
            <button
              type="button"
              class="sc-trigger"
              :class="{ open: open }"
              @click="toggle()"
            >
              <span class="sc-trigger-num" x-text="currentNum">{{ $startSura }}</span>
              <span class="sc-trigger-name" x-text="currentName">{{ \App\Livewire\QuranNotesRangePage::getSuraNameStatic($startSura ?? 1) }}</span>
              <i class="ti ti-chevron-down sc-trigger-icon"></i>
            </button>
            <div x-show="open" x-transition.scale.origin.top class="sc-dropdown" @click.stop>
              <div class="sc-search-row">
                <i class="ti ti-search"></i>
                <input
                  type="text" x-ref="q" x-model="query"
                  placeholder="{{ __('Sura name or number...') }}"
                  class="sc-search-input"
                  @keydown="onKey"
                  autocomplete="off"
                >
                <span class="sc-count" x-text="filtered.length + '/114'"></span>
              </div>
              <div class="sc-list" x-ref="list">
                <template x-for="(sura, idx) in filtered" :key="sura.n">
                  <button
                    type="button"
                    class="sc-option"
                    :class="{ 'is-selected': sura.n == val, 'is-focused': idx === focused }"
                    @click="pick(sura)"
                    @mouseenter="focused = idx"
                  >
                    <span class="sc-opt-num" x-text="sura.n"></span>
                    <span class="sc-opt-name" x-text="sura.l"></span>
                  </button>
                </template>
                <div x-show="filtered.length === 0" class="sc-no-result">{{ __('No results found') }}</div>
              </div>
            </div>
          </div>
        </div>

        <div>
          <span class="qnr-field-label">{{ __('Start Verse') }}</span>
          <select wire:model.live="startAya" class="qnr-aya-select">
            @foreach($this->startAyas as $aya)
              <option value="{{ $aya }}">{{ __(':number. Verse', ['number' => $aya]) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Ok --}}
      <div class="qnr-range-arrow"><i class="ti ti-arrow-right"></i></div>

      {{-- Bitiş --}}
      <div class="qnr-range-side">
        <div>
          <span class="qnr-field-label">{{ __('End Sura') }}</span>
          <div
            x-data="suraCombo('endSura', {{ $endSura ?? 114 }})"
            class="sc-wrap"
            @click.outside="close()"
          >
            <button
              type="button"
              class="sc-trigger"
              :class="{ open: open }"
              @click="toggle()"
            >
              <span class="sc-trigger-num" x-text="currentNum">{{ $endSura }}</span>
              <span class="sc-trigger-name" x-text="currentName">{{ \App\Livewire\QuranNotesRangePage::getSuraNameStatic($endSura ?? 114) }}</span>
              <i class="ti ti-chevron-down sc-trigger-icon"></i>
            </button>
            <div x-show="open" x-transition.scale.origin.top class="sc-dropdown" @click.stop>
              <div class="sc-search-row">
                <i class="ti ti-search"></i>
                <input
                  type="text" x-ref="q" x-model="query"
                  placeholder="{{ __('Sura name or number...') }}"
                  class="sc-search-input"
                  @keydown="onKey"
                  autocomplete="off"
                >
                <span class="sc-count" x-text="filtered.length + '/114'"></span>
              </div>
              <div class="sc-list" x-ref="list">
                <template x-for="(sura, idx) in filtered" :key="sura.n">
                  <button
                    type="button"
                    class="sc-option"
                    :class="{ 'is-selected': sura.n == val, 'is-focused': idx === focused }"
                    @click="pick(sura)"
                    @mouseenter="focused = idx"
                  >
                    <span class="sc-opt-num" x-text="sura.n"></span>
                    <span class="sc-opt-name" x-text="sura.l"></span>
                  </button>
                </template>
                <div x-show="filtered.length === 0" class="sc-no-result">{{ __('No results found') }}</div>
              </div>
            </div>
          </div>
        </div>

        <div>
          <span class="qnr-field-label">{{ __('End Verse') }}</span>
          <select wire:model.live="endAya" class="qnr-aya-select">
            @foreach($this->endAyas as $aya)
              <option value="{{ $aya }}">{{ __(':number. Verse', ['number' => $aya]) }}</option>
            @endforeach
          </select>
        </div>
      </div>

    </div>
  </div>

  {{-- ②  Not türü + etiket filtreleri ──────────────────────────── --}}
  <div class="qnr-card-section">
    <div class="qnr-section-label"><i class="ti ti-filter"></i> {{ __('Filters') }}</div>
    <div class="qnr-filter-row">
      <div class="qnr-type-group">
        <button
          type="button" wire:click="$set('filterType', '')"
          class="qnr-type-btn {{ $filterType === '' ? 'active-all' : '' }}"
        ><i class="ti ti-layout-list" style="font-size:13px;"></i> {{ __('All') }}</button>
        <button
          type="button" wire:click="$set('filterType', 'note')"
          class="qnr-type-btn {{ $filterType === 'note' ? 'active-note' : '' }}"
        ><i class="ti ti-notes" style="font-size:13px;"></i> {{ __('Note') }}</button>
        <button
          type="button" wire:click="$set('filterType', 'footnote')"
          class="qnr-type-btn {{ $filterType === 'footnote' ? 'active-footnote' : '' }}"
        ><i class="ti ti-bookmark" style="font-size:13px;"></i> {{ __('Footnote') }}</button>
        <button
          type="button" wire:click="$set('filterType', 'research')"
          class="qnr-type-btn {{ $filterType === 'research' ? 'active-research' : '' }}"
        ><i class="ti ti-microscope" style="font-size:13px;"></i> {{ __('Research') }}</button>
      </div>

      @if($this->userTags->isNotEmpty())
        <div class="qnr-tag-wrap">
          <i class="ti ti-tag" style="font-size:14px; color:var(--text-light);"></i>
          <select wire:model.live="filterTagId" class="qnr-tag-select">
            <option value="">{{ __('All Tags') }}</option>
            @foreach($this->userTags as $tag)
              <option value="{{ $tag->id }}">#{{ $tag->name }}</option>
            @endforeach
          </select>
        </div>
      @endif
    </div>
  </div>

  {{-- ③  Aksiyon satırı ─────────────────────────────────────────── --}}
  <div class="qnr-card-section">
    <div class="qnr-actions-row">
      <button
        type="button"
        class="qnr-btn-load"
        wire:click="load"
        wire:loading.attr="disabled"
        @if(! $this->isRangeValid) disabled @endif
      >
        <span wire:loading.remove wire:target="load">
          <i class="ti ti-search" style="font-size:15px;"></i> {{ __('Load Notes') }}
        </span>
        <span wire:loading wire:target="load">
          <i class="ti ti-loader-2" style="font-size:14px; animation:spin 1s linear infinite;"></i> {{ __('Loading...') }}
        </span>
      </button>

      @if($loaded)
        <button type="button" class="qnr-btn-clear" wire:click="$set('loaded', false)">
          <i class="ti ti-x" style="font-size:13px;"></i> {{ __('Clear') }}
        </button>
      @endif

      @if(! $this->isRangeValid && $startSura && $startAya && $endSura && $endAya)
        <div class="qnr-range-invalid">
          <i class="ti ti-alert-circle" style="font-size:14px;"></i>
          {{ __('End cannot be before start.') }}
        </div>
      @endif

      @if($loaded && $this->totalNoteCount > 0)
        <div style="font-family:'Cairo',sans-serif; font-size:12.5px; color:var(--text-light); margin-left:4px;">
          <span style="font-weight:700; color:var(--teal-dark);">{{ $this->totalNoteCount }}</span> {{ __('notes found') }}
        </div>
      @endif
    </div>
  </div>

</div>

{{-- ──────────────────────────────────────────────────────────────── --}}
{{-- Results                                                           --}}
{{-- ──────────────────────────────────────────────────────────────── --}}
@if($loaded)

  {{-- Print-only header --}}
  <div class="qnr-print-header">
    <h1 class="qnr-print-title">{{ __('Quran Note Research') }}</h1>
    <p class="qnr-print-meta">
      {{ __('Range') }}: {{ $startSura }}:{{ $startAya }} → {{ $endSura }}:{{ $endAya }}
      @if($filterType !== '') · {{ match($filterType) { 'note' => __('Note'), 'footnote' => __('Footnote'), 'research' => __('Research'), default => $filterType } }} @endif
      · {{ $this->totalNoteCount }} {{ __('notes') }} · {{ now()->format('d.m.Y H:i') }}
    </p>
  </div>

  {{-- Summary bar --}}
  <div class="qnr-summary-bar qnr-no-print">
    <div class="qnr-summary-left">
      <span class="qnr-summary-count">{{ $this->totalNoteCount }}</span>
      <span class="qnr-summary-chip">
        <i class="ti ti-arrows-left-right"></i>
        {{ \App\Livewire\QuranNotesRangePage::getSuraNameStatic($startSura) }} {{ $startSura }}:{{ $startAya }}
        →
        {{ \App\Livewire\QuranNotesRangePage::getSuraNameStatic($endSura) }} {{ $endSura }}:{{ $endAya }}
      </span>
      @if($filterType !== '')
        <span class="qnr-summary-chip">
          <i class="ti ti-filter"></i>
          {{ match($filterType) { 'note' => __('Note'), 'footnote' => __('Footnote'), 'research' => __('Research'), default => $filterType } }}
        </span>
      @endif
      @if($filterTagId && $this->userTags->firstWhere('id', $filterTagId))
        <span class="qnr-summary-chip">
          <i class="ti ti-tag"></i>
          #{{ $this->userTags->firstWhere('id', $filterTagId)?->name }}
        </span>
      @endif
    </div>
    <div class="qnr-summary-actions">
      <button type="button" onclick="window.print()" class="qnr-print-btn">
        <i class="ti ti-printer"></i> {{ __('Print') }}
      </button>
      @if($this->groupedNotes->isNotEmpty())
        <button type="button" wire:click="openShareModal" class="qnr-share-btn">
          <i class="ti ti-share"></i> {{ __('Share') }}
        </button>
      @endif
    </div>
  </div>

  {{-- Sura sections --}}
  @if($this->groupedNotes->isEmpty())
    <div style="background:#fff; border:1px solid var(--border); border-radius:14px; text-align:center; padding:3rem 1rem;">
      <div style="font-size:44px; color:var(--sand); margin-bottom:.75rem;"><i class="ti ti-notes-off"></i></div>
      <div style="font-family:'Cairo',sans-serif; font-size:16px; font-weight:600; color:var(--text-mid); margin-bottom:.4rem;">{{ __('No notes found in this range') }}</div>
      <div style="font-family:'Cairo',sans-serif; font-size:12.5px; color:var(--text-light);">{{ __('Try a different range or filter.') }}</div>
    </div>
  @else
    @foreach($this->groupedNotes as $sura => $suraData)
      <div class="qnr-sura-section">

        {{-- Sure başlığı --}}
        <div class="qnr-sura-header">
          <div class="qnr-sura-num-badge">{{ $sura }}</div>
          <div class="qnr-sura-title">
            <div class="qnr-sura-name">{{ $suraData['name'] }}</div>
            <div class="qnr-sura-info">{{ $suraData['ayas']->count() }} {{ __('verses') }} · {{ $suraData['noteCount'] }} {{ __('notes') }}</div>
          </div>
          <span class="qnr-sura-note-pill">
            <i class="ti ti-notes" style="font-size:11px; margin-right:3px;"></i>{{ $suraData['noteCount'] }}
          </span>
        </div>

        {{-- Ayet blokları --}}
        @foreach($suraData['ayas'] as $aya => $ayaData)
          <div class="qnr-aya-block">

            {{-- Ayet başlığı --}}
            <div
              class="qnr-aya-header"
              @click="expandedAyas['{{ $sura }}_{{ $aya }}'] = !(expandedAyas['{{ $sura }}_{{ $aya }}'] ?? true)"
            >
              <span class="qnr-aya-ref">{{ $sura }}:{{ $aya }}</span>
              <div class="qnr-aya-arabic">
                {{ $ayaData['arabic'] }}
                <span class="qnr-aya-end-mark">﴿{{ $aya }}﴾</span>
              </div>
              <div class="qnr-aya-meta">
                <span class="qnr-aya-note-count">{{ $ayaData['notes']->count() }} {{ __('notes') }}</span>
                <i
                  class="ti qnr-aya-toggle"
                  :class="(expandedAyas['{{ $sura }}_{{ $aya }}'] ?? true) ? 'ti-chevron-up' : 'ti-chevron-down'"
                ></i>
              </div>
            </div>

            {{-- Not kartları --}}
            <div
              class="qnr-notes-panel"
              x-show="expandedAyas['{{ $sura }}_{{ $aya }}'] ?? true"
              x-transition
            >
              @foreach($ayaData['notes'] as $note)
                <div class="qnr-note-card type-{{ $note->type }}">
                  <div class="qnr-note-head">
                    <span class="qnr-type-badge qnr-type-{{ $note->type }}">
                      {{ match($note->type) { 'note' => __('Note'), 'footnote' => __('Footnote'), 'research' => __('Research'), default => $note->type } }}
                    </span>
                    @if($note->title)
                      <span class="qnr-note-title">{{ $note->title }}</span>
                    @endif
                  </div>
                  <div class="qnr-note-body-wrap">
                    @if($note->word_position && !empty($note->word_text))
                      <div class="qnr-word-chip">
                        <span class="qnr-word-label">{{ __('Related Word') }}</span>
                        <span class="qnr-word-ar">{{ $note->word_text }}</span>
                      </div>
                    @endif
                    <div class="qnr-note-text">{{ $note->content }}</div>
                  </div>
                  <div class="qnr-note-footer">
                    <div class="qnr-tags">
                      @foreach($note->tags as $tag)
                        <span class="qnr-tag">#{{ $tag->name }}</span>
                      @endforeach
                    </div>
                    <span class="qnr-note-date">{{ $note->updated_at->format('d.m.Y') }}</span>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        @endforeach

      </div>
    @endforeach
  @endif

@endif

{{-- ──────────────────────────────────────────────────────────────── --}}
{{-- Share Modal                                                        --}}
{{-- ──────────────────────────────────────────────────────────────── --}}
@if($shareModalOpen)
  <div class="qnr-modal-overlay" wire:click.self="closeShareModal">
    <div class="qnr-modal">
      <div class="qnr-modal-head">
        <h3 class="qnr-modal-title">
          <i class="ti ti-share" style="font-size:17px;"></i>
          {{ __('Secure Share Link') }}
        </h3>
        <button type="button" class="qnr-modal-close" wire:click="closeShareModal">
          <i class="ti ti-x"></i>
        </button>
      </div>

      <div class="qnr-modal-body">
        <label class="qnr-modal-field" style="grid-column: 1 / -1;">
          <span class="qnr-modal-label">{{ __('Title (optional)') }}</span>
          <input type="text" wire:model="shareTitle" class="qnr-modal-input" placeholder="{{ __('e.g. Bakara research notes...') }}">
        </label>
        <label class="qnr-modal-field">
          <span class="qnr-modal-label">{{ __('Visibility') }}</span>
          <select wire:model="shareVisibility" class="qnr-modal-select">
            <option value="public">{{ __('Public') }}</option>
            <option value="private">{{ __('Only me') }}</option>
          </select>
        </label>
        <label class="qnr-modal-field">
          <span class="qnr-modal-label">{{ __('Expiration') }}</span>
          <select wire:model="shareExpiry" class="qnr-modal-select">
            <option value="1d">{{ __('1 day') }}</option>
            <option value="7d">{{ __('7 days') }}</option>
            <option value="30d">{{ __('30 days') }}</option>
            <option value="none">{{ __('No expiration') }}</option>
          </select>
        </label>
      </div>

      <div class="qnr-modal-foot">
        <button type="button" class="qnr-modal-cancel" wire:click="closeShareModal">{{ __('Cancel') }}</button>
        <button type="button" class="qnr-modal-submit" wire:click="createShareLink">
          <i class="ti ti-link" style="font-size:14px;"></i> {{ __('Create Link') }}
        </button>
      </div>

      @if($generatedShareUrl)
        <div class="qnr-modal-result">
          <div style="font-family:'Cairo',sans-serif; font-size:11px; font-weight:700; color:var(--text-light); text-transform:uppercase; letter-spacing:.4px; margin-bottom:7px;">
            <i class="ti ti-check" style="color:#22c55e; font-size:13px;"></i> {{ __('Share Link') }}
          </div>
          <input readonly class="qnr-modal-url" value="{{ $generatedShareUrl }}" onclick="this.select()">
          <div class="qnr-modal-socials">
            <a class="qnr-social-btn wa" target="_blank" rel="noopener noreferrer"
               href="https://wa.me/?text={{ rawurlencode($generatedShareUrl) }}">
              <i class="ti ti-brand-whatsapp"></i> WhatsApp
            </a>
            <a class="qnr-social-btn fb" target="_blank" rel="noopener noreferrer"
               href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($generatedShareUrl) }}">
              <i class="ti ti-brand-facebook"></i> Facebook
            </a>
            <button type="button" class="qnr-social-btn cp" onclick="qnrCopy('{{ $generatedShareUrl }}', this)">
              <i class="ti ti-copy"></i> {{ __('Copy') }}
            </button>
          </div>
        </div>
      @endif

    </div>
  </div>
@endif

</div>

@push('scripts')
<script>
/* ── Sure listesi (global, Alpine öncesi set edilir) ────────────── */
window.__qnrSuraList = @js($suraJsData);

document.addEventListener('alpine:init', () => {
  Alpine.data('suraCombo', function(wireProp, initialVal) {
    return {
      wireProp: wireProp,
      val: initialVal,   /* local reactive mirror — updates immediately on pick() */
      open: false,
      query: '',
      focused: -1,

      init() {
        this.$watch('query', () => { this.focused = -1; });
      },

      get filtered() {
        const list = window.__qnrSuraList;
        if (!this.query.trim()) return list;
        const q = this.query.trim().toLowerCase();
        return list.filter(s =>
          String(s.n).startsWith(this.query.trim()) ||
          s.l.toLowerCase().includes(q)
        );
      },

      get currentNum() {
        return this.val ?? '?';
      },

      get currentName() {
        const s = window.__qnrSuraList.find(x => x.n == this.val);
        return s ? s.l : '—';
      },

      toggle() {
        if (this.open) { this.close(); return; }
        this.open  = true;
        this.query = '';
        this.focused = -1;
        this.$nextTick(() => this.$refs.q?.focus());
      },

      close() {
        this.open = false;
        this.query = '';
        this.focused = -1;
      },

      pick(sura) {
        this.val = sura.n;                      /* update trigger button instantly */
        this.$wire.set(this.wireProp, sura.n);  /* sync to Livewire */
        this.close();
      },

      onKey(e) {
        const f = this.filtered;
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          this.focused = Math.min(this.focused + 1, f.length - 1);
          this.$nextTick(() => this.$refs.list?.querySelector('.is-focused')?.scrollIntoView({ block: 'nearest' }));
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          this.focused = Math.max(this.focused - 1, 0);
          this.$nextTick(() => this.$refs.list?.querySelector('.is-focused')?.scrollIntoView({ block: 'nearest' }));
        } else if (e.key === 'Enter' && this.focused >= 0) {
          e.preventDefault();
          if (f[this.focused]) this.pick(f[this.focused]);
        } else if (e.key === 'Escape') {
          e.preventDefault();
          this.close();
        }
      }
    };
  });
});

/* ── Loader spinner animasyonu ──────────────────────────────────── */
const styleEl = document.createElement('style');
styleEl.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
document.head.appendChild(styleEl);

/* ── Kopyala fonksiyonu ─────────────────────────────────────────── */
function qnrCopy(url, btn) {
  navigator.clipboard.writeText(url).then(() => {
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="ti ti-check"></i> {{ __('Copied!') }}';
    btn.style.color = '#22c55e';
    setTimeout(() => { btn.innerHTML = orig; btn.style.color = ''; }, 2000);
  });
}
</script>
@endpush
