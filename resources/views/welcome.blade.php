<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Valoduction') }}</title>
    <style>
        :root {
            --bg: #ece8e1;
            --surface: rgba(255, 252, 247, 0.74);
            --border: rgba(15, 25, 35, 0.08);
            --text: #0f1923;
            --muted: #5b6570;
            --accent: #ff4655;
            --accent-bg: rgba(255, 70, 85, 0.12);
            --accent-border: rgba(255, 70, 85, 0.35);
            --shadow: 0 20px 50px -18px rgba(15, 25, 35, 0.15);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0f1923;
                --surface: rgba(20, 32, 46, 0.78);
                --border: rgba(236, 232, 225, 0.1);
                --text: #ece8e1;
                --muted: #9aa5b1;
                --accent: #ff4655;
                --accent-bg: rgba(255, 70, 85, 0.16);
                --accent-border: rgba(255, 70, 85, 0.38);
                --shadow: 0 24px 60px -16px rgba(0, 0, 0, 0.6);
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg);
            background-image:
                radial-gradient(circle at 12% 10%, rgba(255, 70, 85, 0.12), transparent 42%),
                radial-gradient(circle at 88% 85%, rgba(200, 155, 60, 0.12), transparent 40%);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        .hero-card {
            width: 100%;
            max-width: 560px;
            background-color: var(--surface);
            backdrop-filter: blur(20px) saturate(165%);
            -webkit-backdrop-filter: blur(20px) saturate(165%);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            padding: 2.25rem;
            box-shadow: var(--shadow);
        }

        .brand-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .brand-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.85rem;
            background: linear-gradient(135deg, #ff4655, #d93442);
            color: #ece8e1;
            font-weight: 800;
            font-size: 1.15rem;
            box-shadow: 0 10px 22px -6px rgba(255, 70, 85, 0.55);
        }

        .brand-name {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.7rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            background-color: var(--accent-bg);
            color: var(--accent);
            border: 1px solid var(--accent-border);
        }

        .lead {
            margin: 0 0 1.75rem;
            color: var(--muted);
            font-size: 0.975rem;
            line-height: 1.6;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.875rem;
        }

        .action-card {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            padding: 1.1rem 1.2rem;
            border-radius: 1.125rem;
            text-decoration: none;
            color: var(--text);
            background-color: var(--surface);
            border: 1px solid var(--border);
            transition: transform 150ms ease, border-color 150ms ease, box-shadow 150ms ease;
        }

        .action-card:hover {
            transform: translateY(-2px);
            border-color: var(--accent);
        }

        .action-card--primary {
            background: linear-gradient(135deg, #ff4655, #d93442);
            color: #ece8e1;
            border-color: transparent;
            box-shadow: 0 12px 26px -8px rgba(255, 70, 85, 0.48);
        }

        .action-card--primary .action-desc {
            color: rgba(236, 232, 225, 0.85);
        }

        .action-title {
            font-size: 0.975rem;
            font-weight: 700;
        }

        .action-desc {
            font-size: 0.825rem;
            color: var(--muted);
        }
    </style>
</head>
<body>
    <main class="hero-card">
        <div class="brand-header">
            <div class="brand-left">
                <span class="brand-mark">V</span>
                <h1 class="brand-name">{{ config('app.name', 'Valoduction') }}</h1>
            </div>
            <a class="pill" href="{{ route('changelog') }}">{{ \App\Support\Changelog::latestVersion() }}</a>
        </div>

        <p class="lead">
            Clean, warm, tactical workspace built with Laravel and Filament. Access the admin panel or explore the release timeline below.
        </p>

        <div class="action-grid">
            <a class="action-card action-card--primary" href="{{ url('/admin') }}">
                <span class="action-title">Open Admin Panel</span>
                <span class="action-desc">Sign in with your username</span>
            </a>

            <a class="action-card" href="{{ route('changelog') }}">
                <span class="action-title">Release Changelog</span>
                <span class="action-desc">View version timeline</span>
            </a>
        </div>
    </main>
</body>
</html>
