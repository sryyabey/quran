<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suhuf — {{ __('Tadabbur') }}</title>
    <meta name="description" content="{{ __('hero_desc') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Amiri:ital@0;1&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --teal-dark:  #2d9b84;
            --teal-mid:   #3ab89e;
            --teal-light: #e8f7f4;
            --gold-mid:   #c4982a;
            --gold-light: #fef9ec;
            --cream:      #faf8f3;
            --cream2:     #f3f0e8;
            --text-dark:  #1a1a18;
            --text-mid:   #4a4a45;
            --text-light: #8a8a82;
            --border:     #e2ddd4;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--cream);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ── NAVBAR ─────────────────────────────────────────────── */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 64px;
            background: rgba(250, 248, 243, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }
        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--teal-dark), var(--teal-mid));
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
        }
        .nav-logo-text { font-size: 20px; font-weight: 800; color: var(--teal-dark); letter-spacing: -.3px; }
        .nav-logo-sub  { font-size: 11px; color: var(--text-light); font-weight: 400; margin-top: -2px; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-link {
            padding: 8px 18px; border-radius: 9px; font-size: 14px; font-weight: 600;
            text-decoration: none; transition: all .15s;
        }
        .nav-link-ghost { color: var(--text-mid); }
        .nav-link-ghost:hover { background: var(--cream2); color: var(--teal-dark); }
        .nav-link-solid {
            background: var(--teal-dark); color: #fff;
            border: 1.5px solid var(--teal-dark);
        }
        .nav-link-solid:hover { background: #248a73; border-color: #248a73; }

        /* Language switcher in navbar */
        .nav-lang {
            display: flex; align-items: center; gap: 2px;
            background: var(--cream2); border: 1px solid var(--border);
            border-radius: 8px; padding: 3px;
        }
        .nav-lang-btn {
            font-family: 'Cairo', sans-serif; font-size: 11px; font-weight: 700;
            padding: 3px 8px; border-radius: 5px; text-decoration: none;
            transition: all .15s; color: var(--text-light);
        }
        .nav-lang-btn.active { background: var(--teal-dark); color: #fff; }
        .nav-lang-btn:not(.active):hover { color: var(--teal-dark); }

        /* ── HERO ────────────────────────────────────────────────── */
        .hero {
            min-height: 100vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; padding: 120px 24px 80px;
            position: relative; overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 60% 50% at 50% 0%, rgba(45,155,132,.08) 0%, transparent 70%),
                radial-gradient(ellipse 40% 30% at 80% 80%, rgba(196,152,42,.06) 0%, transparent 60%);
        }
        .hero-pattern {
            position: absolute; inset: 0; pointer-events: none; opacity: .04;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%232d9b84' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 999px;
            background: var(--teal-light); border: 1px solid rgba(45,155,132,.25);
            font-size: 12px; font-weight: 700; color: var(--teal-dark);
            letter-spacing: .5px; text-transform: uppercase;
            margin-bottom: 24px;
        }
        .hero-title {
            font-size: clamp(48px, 8vw, 80px);
            font-weight: 800; line-height: 1.05;
            color: var(--text-dark); letter-spacing: -2px;
            margin-bottom: 8px;
        }
        .hero-title span { color: var(--teal-dark); }
        .hero-subtitle {
            font-family: 'Lora', serif; font-size: clamp(16px, 2vw, 20px);
            color: var(--text-mid); font-style: italic;
            margin-bottom: 28px; letter-spacing: .3px;
        }
        .hero-desc {
            max-width: 520px; font-size: 16px; line-height: 1.8;
            color: var(--text-mid); margin-bottom: 40px;
        }
        .hero-cta { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; margin-bottom: 64px; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 28px; border-radius: 12px;
            font-family: 'Cairo', sans-serif; font-size: 15px; font-weight: 700;
            text-decoration: none; transition: all .18s; border: 2px solid transparent;
            cursor: pointer;
        }
        .btn-primary { background: var(--teal-dark); color: #fff; border-color: var(--teal-dark); }
        .btn-primary:hover { background: #248a73; border-color: #248a73; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(45,155,132,.25); }
        .btn-outline { background: #fff; color: var(--teal-dark); border-color: var(--border); }
        .btn-outline:hover { border-color: var(--teal-mid); background: var(--teal-light); transform: translateY(-1px); }

        .hero-arabic {
            font-family: 'Amiri', serif; font-size: clamp(22px, 3vw, 30px);
            color: var(--text-mid); direction: rtl; line-height: 2;
            opacity: .6; letter-spacing: 1px;
        }

        /* ── STATS ────────────────────────────────────────────────── */
        .stats {
            display: flex; justify-content: center; gap: 0;
            border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
            background: #fff; padding: 0;
        }
        .stat-item {
            flex: 1; max-width: 200px; text-align: center;
            padding: 32px 24px; border-right: 1px solid var(--border);
        }
        .stat-item:last-child { border-right: none; }
        .stat-num { font-size: 32px; font-weight: 800; color: var(--teal-dark); line-height: 1; margin-bottom: 4px; }
        .stat-label { font-size: 12px; color: var(--text-light); font-weight: 600; text-transform: uppercase; letter-spacing: .6px; }

        /* ── FEATURES ────────────────────────────────────────────── */
        .section { padding: 96px 24px; max-width: 1100px; margin: 0 auto; }
        .section-label {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11px; font-weight: 700; color: var(--teal-dark);
            text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(28px, 4vw, 40px); font-weight: 800;
            color: var(--text-dark); letter-spacing: -1px;
            margin-bottom: 16px; line-height: 1.15;
        }
        .section-desc { font-size: 16px; color: var(--text-mid); line-height: 1.8; max-width: 500px; }

        .features-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px; margin-top: 56px;
        }
        .feature-card {
            background: #fff; border: 1px solid var(--border);
            border-radius: 16px; padding: 28px;
            transition: all .2s;
        }
        .feature-card:hover { border-color: var(--teal-mid); box-shadow: 0 8px 32px rgba(45,155,132,.1); transform: translateY(-2px); }
        .feature-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-bottom: 16px;
        }
        .feature-icon.teal  { background: var(--teal-light); color: var(--teal-dark); }
        .feature-icon.gold  { background: var(--gold-light); color: var(--gold-mid); }
        .feature-icon.purple { background: #ede9fe; color: #6d28d9; }
        .feature-icon.blue  { background: #eff6ff; color: #2563eb; }
        .feature-title { font-size: 16px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
        .feature-desc  { font-size: 14px; color: var(--text-mid); line-height: 1.7; }

        /* ── HOW IT WORKS ─────────────────────────────────────────── */
        .how-bg { background: #fff; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
        .steps { display: flex; gap: 0; margin-top: 56px; position: relative; }
        .steps::before {
            content: ''; position: absolute; top: 28px; left: 28px; right: 28px; height: 2px;
            background: linear-gradient(90deg, var(--teal-dark), var(--teal-mid));
            opacity: .2;
        }
        .step { flex: 1; text-align: center; padding: 0 24px; position: relative; }
        .step-num {
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, var(--teal-dark), var(--teal-mid));
            color: #fff; font-size: 20px; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; box-shadow: 0 4px 16px rgba(45,155,132,.3);
        }
        .step-title { font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
        .step-desc  { font-size: 13px; color: var(--text-mid); line-height: 1.7; }

        /* ── MOBILE CTA ───────────────────────────────────────────── */
        .mobile-section {
            background: linear-gradient(135deg, var(--teal-dark) 0%, #1a7a65 100%);
            padding: 80px 24px; text-align: center; position: relative; overflow: hidden;
        }
        .mobile-section::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
        }
        .mobile-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 999px;
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
            font-size: 12px; font-weight: 700; color: rgba(255,255,255,.9);
            letter-spacing: .5px; text-transform: uppercase; margin-bottom: 20px;
        }
        .mobile-title {
            font-size: clamp(28px, 4vw, 42px); font-weight: 800;
            color: #fff; letter-spacing: -1px; margin-bottom: 16px; line-height: 1.2;
        }
        .mobile-desc { font-size: 16px; color: rgba(255,255,255,.8); line-height: 1.8; max-width: 480px; margin: 0 auto 36px; }
        .mobile-stores { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .store-btn {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 12px 22px; border-radius: 12px;
            background: rgba(255,255,255,.12); border: 1.5px solid rgba(255,255,255,.25);
            color: #fff; text-decoration: none; font-size: 14px; font-weight: 600;
            transition: all .18s;
        }
        .store-btn:hover { background: rgba(255,255,255,.2); border-color: rgba(255,255,255,.4); }
        .store-btn i { font-size: 22px; }
        .store-btn-label { text-align: left; }
        .store-btn-sub  { font-size: 10px; opacity: .75; font-weight: 400; display: block; }
        .store-btn-name { font-size: 15px; font-weight: 700; display: block; }
        .coming-soon-tag {
            display: inline-block; font-size: 10px; padding: 2px 8px;
            background: var(--gold-mid); color: #fff; border-radius: 4px;
            font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
            vertical-align: middle; margin-left: 6px;
        }

        /* ── FOOTER ──────────────────────────────────────────────── */
        .footer {
            background: var(--text-dark); color: rgba(255,255,255,.5);
            padding: 48px 48px 32px; text-align: center;
        }
        .footer-logo { font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .footer-tagline { font-family: 'Lora', serif; font-style: italic; font-size: 13px; margin-bottom: 32px; }
        .footer-links { display: flex; justify-content: center; gap: 24px; flex-wrap: wrap; margin-bottom: 32px; }
        .footer-link { color: rgba(255,255,255,.5); text-decoration: none; font-size: 13px; transition: color .15s; }
        .footer-link:hover { color: rgba(255,255,255,.9); }
        .footer-bottom { font-size: 12px; border-top: 1px solid rgba(255,255,255,.08); padding-top: 24px; }

        /* ── RESPONSIVE ──────────────────────────────────────────── */
        @media (max-width: 768px) {
            .nav { padding: 0 20px; }
            .nav-link-ghost { display: none; }
            .stats { flex-wrap: wrap; }
            .stat-item { min-width: 50%; border-bottom: 1px solid var(--border); }
            .steps { flex-direction: column; gap: 32px; }
            .steps::before { display: none; }
            .footer { padding: 40px 20px 24px; }
        }
        @media (max-width: 480px) {
            .hero-cta { flex-direction: column; align-items: center; }
            .btn { width: 100%; max-width: 280px; justify-content: center; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="nav">
    <a href="{{ route('home') }}" class="nav-logo">
        <div class="nav-logo-icon"><i class="ti ti-book-2"></i></div>
        <div>
            <div class="nav-logo-text">Suhuf</div>
            <div class="nav-logo-sub">{{ __('Tadabbur') }}</div>
        </div>
    </a>
    <div class="nav-links">
        <a href="#features" class="nav-link nav-link-ghost">{{ __('Features') }}</a>
        <a href="#how-it-works" class="nav-link nav-link-ghost">{{ __('How It Works?') }}</a>

        <div class="nav-lang">
            <a href="{{ route('locale.switch', 'tr') }}" class="nav-lang-btn {{ app()->getLocale()==='tr' ? 'active' : '' }}">TR</a>
            <a href="{{ route('locale.switch', 'en') }}" class="nav-lang-btn {{ app()->getLocale()==='en' ? 'active' : '' }}">EN</a>
        </div>

        <a href="{{ route('login') }}" class="nav-link nav-link-ghost">{{ __('Sign In') }}</a>
        <a href="{{ route('register') }}" class="nav-link nav-link-solid">{{ __('Start for Free') }}</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-pattern"></div>

    <div class="hero-badge">
        <i class="ti ti-sparkles" style="font-size:13px;"></i>
        {{ __('Quran Study Platform') }}
    </div>

    <h1 class="hero-title">
        {!! __('hero_title') !!}
    </h1>
    <p class="hero-subtitle">{{ __('hero_subtitle') }}</p>
    <p class="hero-desc">
        {{ __('hero_desc') }}
    </p>

    <div class="hero-cta">
        <a href="{{ route('register') }}" class="btn btn-primary">
            <i class="ti ti-user-plus"></i>
            {{ __('Create Free Account') }}
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline">
            <i class="ti ti-login"></i>
            {{ __('Sign In') }}
        </a>
    </div>

    <p class="hero-arabic">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
</section>

<!-- STATS -->
<div class="stats">
    <div class="stat-item">
        <div class="stat-num">114</div>
        <div class="stat-label">{{ __('Sura') }}</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">6.236</div>
        <div class="stat-label">{{ __('Verse') }}</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">77K+</div>
        <div class="stat-label">{{ __('Word Analysis') }}</div>
    </div>
    <div class="stat-item">
        <div class="stat-num">∞</div>
        <div class="stat-label">{{ __('Personal Note') }}</div>
    </div>
</div>

<!-- FEATURES -->
<div id="features">
    <div class="section">
        <div class="section-label"><i class="ti ti-layout-grid" style="font-size:13px;"></i> {{ __('Features') }}</div>
        <h2 class="section-title">{{ __('Everything you need in one place') }}</h2>
        <p class="section-desc">{{ __('features_desc') }}</p>

        <div class="features-grid">

            <div class="feature-card">
                <div class="feature-icon teal"><i class="ti ti-book-2"></i></div>
                <div class="feature-title">{{ __('Quran Text & Translation') }}</div>
                <div class="feature-desc">{{ __('feature_quran_text_desc') }}</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon gold"><i class="ti ti-letters"></i></div>
                <div class="feature-title">{{ __('Word by Word Analysis') }}</div>
                <div class="feature-desc">{{ __('feature_word_analysis_desc') }}</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon purple"><i class="ti ti-notes"></i></div>
                <div class="feature-title">{{ __('Personal Note System') }}</div>
                <div class="feature-desc">{{ __('feature_notes_desc') }}</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon blue"><i class="ti ti-share"></i></div>
                <div class="feature-title">{{ __('Note Sharing') }}</div>
                <div class="feature-desc">{{ __('feature_sharing_desc') }}</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon teal"><i class="ti ti-chart-bar"></i></div>
                <div class="feature-title">{{ __('Study Statistics') }}</div>
                <div class="feature-desc">{{ __('feature_statistics_desc') }}</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon gold"><i class="ti ti-bookmark"></i></div>
                <div class="feature-title">{{ __('Reading Tracking') }}</div>
                <div class="feature-desc">{{ __('feature_tracking_desc') }}</div>
            </div>

        </div>
    </div>
</div>

<!-- HOW IT WORKS -->
<div id="how-it-works" class="how-bg">
    <div class="section">
        <div class="section-label"><i class="ti ti-route" style="font-size:13px;"></i> {{ __('How It Works?') }}</div>
        <h2 class="section-title">{{ __('Start in 3 steps') }}</h2>
        <p class="section-desc">{{ __('how_desc') }}</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-title">{{ __('step1_title') }}</div>
                <div class="step-desc">{{ __('step1_desc') }}</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-title">{{ __('step2_title') }}</div>
                <div class="step-desc">{{ __('step2_desc') }}</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-title">{{ __('step3_title') }}</div>
                <div class="step-desc">{{ __('step3_desc') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- MOBILE APP -->
<section class="mobile-section">
    <div class="mobile-badge">
        <i class="ti ti-device-mobile" style="font-size:13px;"></i>
        {{ __('Coming Soon') }}
    </div>
    <h2 class="mobile-title">{{ __('Also in your pocket') }}</h2>
    <p class="mobile-desc">{{ __('mobile_desc') }}</p>
    <div class="mobile-stores">
        <a href="#" class="store-btn">
            <i class="ti ti-brand-apple"></i>
            <div class="store-btn-label">
                <span class="store-btn-sub">{{ __('Download on App Store') }}</span>
                <span class="store-btn-name">{{ __('iPhone & iPad') }} <span class="coming-soon-tag">{{ __('Coming Soon') }}</span></span>
            </div>
        </a>
        <a href="#" class="store-btn">
            <i class="ti ti-brand-google-play"></i>
            <div class="store-btn-label">
                <span class="store-btn-sub">{{ __('Download on Google Play') }}</span>
                <span class="store-btn-name">{{ __('Android') }} <span class="coming-soon-tag">{{ __('Coming Soon') }}</span></span>
            </div>
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-logo">Suhuf</div>
    <div class="footer-tagline">{{ __('footer_tagline') }}</div>
    <div class="footer-links">
        <a href="{{ route('register') }}" class="footer-link">{{ __('Register') }}</a>
        <a href="{{ route('login') }}" class="footer-link">{{ __('Sign In') }}</a>
        <a href="#features" class="footer-link">{{ __('Features') }}</a>
        <a href="#how-it-works" class="footer-link">{{ __('How It Works?') }}</a>
    </div>
    <div class="footer-bottom">
        © {{ date('Y') }} Suhuf · {{ __('All rights reserved.') }}
    </div>
</footer>

</body>
</html>
