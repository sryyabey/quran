<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Sign In') }} — Suhuf</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Amiri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --teal-dark:  #2d9b84;
            --teal-mid:   #3ab89e;
            --teal-light: #e8f7f4;
            --cream:      #faf8f3;
            --cream2:     #f3f0e8;
            --text-dark:  #1a1a18;
            --text-mid:   #4a4a45;
            --text-light: #8a8a82;
            --border:     #e2ddd4;
            --error-bg:   #fff2f2;
            --error-border: #fca5a5;
            --error-text: #991b1b;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background:
                radial-gradient(ellipse 70% 50% at 70% 20%, rgba(45,155,132,.06) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 20% 80%, rgba(196,152,42,.05) 0%, transparent 60%);
        }

        .page-wrap {
            position: relative; z-index: 1;
            width: 100%; max-width: 420px;
        }

        /* Logo */
        .logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; justify-content: center;
            margin-bottom: 32px;
        }
        .logo-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, var(--teal-dark), var(--teal-mid));
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
            box-shadow: 0 4px 16px rgba(45,155,132,.3);
        }
        .logo-text { font-size: 24px; font-weight: 800; color: var(--teal-dark); letter-spacing: -.5px; }
        .logo-sub  { font-size: 12px; color: var(--text-light); display: block; margin-top: -2px; }

        /* Kart */
        .card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 4px 32px rgba(0,0,0,.06);
        }

        .card-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 28px;
        }
        .card-header-text {}
        .card-title {
            font-size: 22px; font-weight: 800;
            color: var(--text-dark); letter-spacing: -.4px;
            margin-bottom: 4px;
        }
        .card-sub {
            font-size: 14px; color: var(--text-light);
            line-height: 1.6;
        }

        /* Language switcher */
        .lang-switcher {
            display: flex; align-items: center; gap: 2px;
            background: var(--cream2); border: 1px solid var(--border);
            border-radius: 8px; padding: 3px; flex-shrink: 0;
        }
        .lang-btn {
            font-family: 'Cairo', sans-serif; font-size: 11px; font-weight: 700;
            padding: 3px 8px; border-radius: 5px; text-decoration: none;
            transition: all .15s; color: var(--text-light);
        }
        .lang-btn.active { background: var(--teal-dark); color: #fff; }
        .lang-btn:not(.active):hover { color: var(--teal-dark); }

        /* Hata */
        .error-box {
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: var(--error-text);
        }
        .error-box i { font-size: 16px; flex-shrink: 0; }

        /* Başarı mesajı */
        .success-box {
            background: var(--teal-light);
            border: 1px solid rgba(45,155,132,.25);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: var(--teal-dark); font-weight: 600;
        }
        .success-box i { font-size: 16px; flex-shrink: 0; }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 700; color: var(--text-mid);
            margin-bottom: 7px;
        }
        .form-label i { font-size: 14px; color: var(--text-light); }

        .input-wrap { position: relative; }
        .form-input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            font-size: 14px; color: var(--text-dark);
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--teal-dark);
            box-shadow: 0 0 0 3px rgba(45,155,132,.12);
        }
        .form-input.is-error { border-color: var(--error-border); }
        .form-input::placeholder { color: var(--text-light); }

        .input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            font-size: 16px; color: var(--text-light); pointer-events: none;
        }
        .input-toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            font-size: 16px; color: var(--text-light);
            padding: 4px; transition: color .15s;
        }
        .input-toggle:hover { color: var(--teal-dark); }
        .form-input.has-toggle { padding-right: 40px; }

        /* Beni hatırla satırı */
        .form-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; color: var(--text-mid); cursor: pointer;
        }
        .remember-checkbox {
            width: 16px; height: 16px;
            border: 1.5px solid var(--border); border-radius: 4px;
            accent-color: var(--teal-dark); cursor: pointer;
        }

        /* Submit */
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--teal-dark), var(--teal-mid));
            color: #fff; border: none; border-radius: 12px;
            font-family: 'Cairo', sans-serif; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all .18s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 16px rgba(45,155,132,.25);
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(45,155,132,.35);
        }
        .btn-submit:active { transform: translateY(0); }

        /* Ayırıcı */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0; color: var(--text-light); font-size: 12px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* Kayıt linki */
        .register-link {
            text-align: center; font-size: 14px; color: var(--text-mid);
        }
        .register-link a {
            color: var(--teal-dark); font-weight: 700; text-decoration: none;
        }
        .register-link a:hover { text-decoration: underline; }

        /* Ana sayfa */
        .back-link {
            display: flex; align-items: center; justify-content: center; gap: 5px;
            margin-top: 24px; font-size: 13px; color: var(--text-light);
            text-decoration: none; transition: color .15s;
        }
        .back-link:hover { color: var(--teal-dark); }

        /* Ayet */
        .ayah-wrap {
            text-align: center; margin-top: 32px; padding-top: 24px;
            border-top: 1px solid var(--border);
        }
        .ayah-arabic {
            font-family: 'Amiri', serif; font-size: 18px;
            color: var(--text-light); direction: rtl; line-height: 2;
            margin-bottom: 4px;
        }
        .ayah-source {
            font-size: 11px; color: var(--text-light);
            letter-spacing: .3px;
        }
    </style>
</head>
<body>

<div class="page-wrap">

    <!-- Logo -->
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon"><i class="ti ti-book-2"></i></div>
        <div>
            <div class="logo-text">Suhuf</div>
            <span class="logo-sub">{{ __('Tadabbur') }}</span>
        </div>
    </a>

    <!-- Kart -->
    <div class="card">
        <div class="card-header">
            <div class="card-header-text">
                <h1 class="card-title">{{ __('Welcome back') }}</h1>
                <p class="card-sub">{{ __('Sign in to your account to continue.') }}</p>
            </div>
            <div class="lang-switcher">
                <a href="{{ route('locale.switch', 'tr') }}" class="lang-btn {{ app()->getLocale()==='tr' ? 'active' : '' }}">TR</a>
                <a href="{{ route('locale.switch', 'en') }}" class="lang-btn {{ app()->getLocale()==='en' ? 'active' : '' }}">EN</a>
            </div>
        </div>

        {{-- Hata --}}
        @if ($errors->has('email'))
            <div class="error-box">
                <i class="ti ti-alert-circle"></i>
                {{ $errors->first('email') }}
            </div>
        @endif

        {{-- Session mesajı --}}
        @if (session('status'))
            <div class="success-box">
                <i class="ti ti-circle-check"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            {{-- E-posta --}}
            <div class="form-group">
                <label class="form-label" for="email">
                    <i class="ti ti-mail"></i> {{ __('Email') }}
                </label>
                <div class="input-wrap">
                    <i class="ti ti-mail input-icon"></i>
                    <input
                        id="email" name="email" type="email"
                        class="form-input @error('email') is-error @enderror"
                        value="{{ old('email') }}"
                        placeholder="{{ __('email_placeholder') }}"
                        autocomplete="email"
                        autofocus
                        required
                    >
                </div>
            </div>

            {{-- Şifre --}}
            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="ti ti-lock"></i> {{ __('Password') }}
                </label>
                <div class="input-wrap">
                    <i class="ti ti-lock input-icon"></i>
                    <input
                        id="password" name="password" type="password"
                        class="form-input has-toggle"
                        placeholder="{{ __('Your password') }}"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="input-toggle" onclick="togglePass()" tabindex="-1">
                        <i id="eye-icon" class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Beni hatırla --}}
            <div class="form-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" class="remember-checkbox" {{ old('remember') ? 'checked' : '' }}>
                    {{ __('Remember me') }}
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="ti ti-login"></i>
                {{ __('Sign In') }}
            </button>
        </form>

        <div class="divider">{{ __("Don't have an account?") }}</div>

        <div class="register-link">
            <a href="{{ route('register') }}">{{ __('Create a free account') }}</a>
            — {{ __('quick and free') }}
        </div>

        {{-- Ayet --}}
        <div class="ayah-wrap">
            <div class="ayah-arabic">أَفَلَا يَتَدَبَّرُونَ الْقُرْآنَ</div>
            <div class="ayah-source">Nisa 4:82</div>
        </div>
    </div>

    <a href="{{ route('home') }}" class="back-link">
        <i class="ti ti-arrow-left" style="font-size:14px;"></i>
        {{ __('Back to home') }}
    </a>

</div>

<script>
function togglePass() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ti ti-eye-off';
    } else {
        input.type = 'password';
        icon.className = 'ti ti-eye';
    }
}
</script>

</body>
</html>
