<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Hata') - {{ config('app.name', 'Laravel') }}</title>

    <style>
        :root {
            --teal-dark: #1a6b5a;
            --teal-mid: #2d9b84;
            --teal-light: #e0f4f0;
            --gold-mid: #D4A843;
            --cream: #faf8f3;
            --card: #ffffff;
            --text-dark: #1f2624;
            --text-mid: #5b6763;
            --border: #e2ddd4;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: "Cairo", "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 15% 20%, rgba(45, 155, 132, 0.08), transparent 38%),
                radial-gradient(circle at 80% 85%, rgba(212, 168, 67, 0.10), transparent 34%),
                var(--cream);
            color: var(--text-dark);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .error-card {
            width: min(720px, 100%);
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 18px 50px rgba(26, 107, 90, 0.12);
            overflow: hidden;
        }

        .error-header {
            padding: 20px 24px;
            background: linear-gradient(130deg, var(--teal-dark), var(--teal-mid));
            color: #fff;
            font-weight: 700;
            letter-spacing: .4px;
        }

        .error-content {
            padding: 28px 24px 32px;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--teal-light);
            color: var(--teal-dark);
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .status-code {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--gold-mid);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(24px, 2.8vw, 34px);
            line-height: 1.2;
        }

        p {
            margin: 0;
            color: var(--text-mid);
            line-height: 1.65;
            font-size: 15px;
        }

        .actions {
            margin-top: 24px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid transparent;
            transition: .16s ease;
        }

        .btn-primary {
            background: var(--teal-dark);
            color: #fff;
        }

        .btn-primary:hover {
            background: #145246;
        }

        .btn-secondary {
            color: var(--text-dark);
            border-color: var(--border);
            background: #fff;
        }

        .btn-secondary:hover {
            border-color: var(--teal-mid);
            color: var(--teal-dark);
        }
    </style>
</head>

<body>
    <main class="error-card" role="main">
        <div class="error-header">{{ config('app.name', 'Laravel') }}</div>
        <section class="error-content">
            @yield('content')
        </section>
    </main>
</body>

</html>
