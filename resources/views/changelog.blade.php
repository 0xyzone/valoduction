<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Changelog — {{ $appName }}</title>
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
            --gold: #c89b3c;
            --line: rgba(15, 25, 35, 0.14);
            --code-bg: rgba(15, 25, 35, 0.06);
            --shadow: 0 14px 36px -14px rgba(15, 25, 35, 0.12);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0f1923;
                --surface: rgba(20, 32, 46, 0.76);
                --border: rgba(236, 232, 225, 0.1);
                --text: #ece8e1;
                --muted: #9aa5b1;
                --accent: #ff4655;
                --accent-bg: rgba(255, 70, 85, 0.16);
                --accent-border: rgba(255, 70, 85, 0.38);
                --line: rgba(236, 232, 225, 0.16);
                --code-bg: rgba(236, 232, 225, 0.08);
                --shadow: 0 18px 42px -14px rgba(0, 0, 0, 0.55);
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg);
            background-image:
                radial-gradient(circle at 10% 8%, rgba(255, 70, 85, 0.1), transparent 40%),
                radial-gradient(circle at 90% 14%, rgba(200, 155, 60, 0.1), transparent 38%),
                radial-gradient(circle at 80% 92%, rgba(255, 70, 85, 0.06), transparent 42%);
            background-attachment: fixed;
            color: var(--text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 840px;
            margin: 0 auto;
            padding: 2.5rem 1.25rem 5rem;
        }

        .header-card {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            padding: 1.5rem 1.75rem;
            border-radius: 1.25rem;
            background-color: var(--surface);
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 2.5rem;
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.65rem;
            background: linear-gradient(135deg, #ff4655, #d93442);
            color: #ece8e1;
            font-weight: 800;
            font-size: 0.95rem;
            box-shadow: 0 8px 18px -6px rgba(255, 70, 85, 0.55);
        }

        .brand-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .subtitle {
            margin: 0.35rem 0 0;
            color: var(--muted);
            font-size: 0.925rem;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            background-color: var(--accent-bg);
            color: var(--accent);
            border: 1px solid var(--accent-border);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.95rem;
            border-radius: 0.875rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--text);
            background-color: var(--surface);
            border: 1px solid var(--border);
            transition: border-color 150ms ease, color 150ms ease, transform 120ms ease;
        }

        .nav-link:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-1px);
        }

        .nav-link--primary {
            background: linear-gradient(135deg, #ff4655, #d93442);
            color: #ece8e1;
            border-color: transparent;
            box-shadow: 0 8px 20px -6px rgba(255, 70, 85, 0.45);
        }

        .nav-link--primary:hover {
            color: #ffffff;
            border-color: transparent;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: "";
            position: absolute;
            top: 0.75rem;
            bottom: 0.75rem;
            left: 0.4375rem;
            width: 2px;
            background-color: var(--line);
        }

        .release {
            position: relative;
            margin-bottom: 1.75rem;
        }

        .release:last-child {
            margin-bottom: 0;
        }

        .release-node {
            position: absolute;
            left: -2rem;
            top: 1.25rem;
            width: 0.9375rem;
            height: 0.9375rem;
            border-radius: 9999px;
            background-color: var(--bg);
            border: 3px solid var(--line);
        }

        .release--latest .release-node {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-bg);
        }

        .release-card {
            background-color: var(--surface);
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            padding: 1.5rem 1.65rem;
            box-shadow: var(--shadow);
        }

        .release-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.15rem;
        }

        .release-version-group {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .release-version {
            font-size: 1.25rem;
            font-weight: 800;
            margin: 0;
        }

        .release-date {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
        }

        .section-group {
            margin-top: 1.15rem;
        }

        .section-group:first-of-type {
            margin-top: 0;
        }

        .section-badge {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--accent);
            margin-bottom: 0.45rem;
        }

        .section-list {
            margin: 0;
            padding-left: 1.25rem;
        }

        .section-list li {
            margin-bottom: 0.4rem;
            font-size: 0.9375rem;
        }

        .section-list li:last-child {
            margin-bottom: 0;
        }

        .cl-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.84em;
            padding: 0.12rem 0.4rem;
            border-radius: 0.375rem;
            background-color: var(--code-bg);
        }

        .empty-state {
            background-color: var(--surface);
            backdrop-filter: blur(18px);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            padding: 2rem;
            text-align: center;
            color: var(--muted);
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header-card">
            <div>
                <div class="brand-row">
                    <span class="brand-mark">V</span>
                    <h1 class="brand-title">{{ $appName }} Changelog</h1>
                    <span class="pill">{{ $latestVersion }}</span>
                </div>
                <p class="subtitle">Release timeline tracking major, minor, and macro updates.</p>
            </div>

            <nav class="nav-links" aria-label="Changelog navigation">
                <a class="nav-link" href="{{ url('/') }}">Home</a>
                <a class="nav-link nav-link--primary" href="{{ url('/admin') }}">Admin Panel</a>
            </nav>
        </header>

        @if (count($releases) === 0)
            <div class="empty-state">
                No releases have been recorded in <code>changelog.md</code> yet.
            </div>
        @else
            <section class="timeline" aria-label="Release timeline">
                @foreach ($releases as $release)
                    <article class="release {{ $release['is_latest'] ? 'release--latest' : '' }}">
                        <div class="release-node" aria-hidden="true"></div>
                        <div class="release-card">
                            <div class="release-header">
                                <div class="release-version-group">
                                    <h2 class="release-version">{{ $release['version'] }}</h2>
                                    @if ($release['is_latest'])
                                        <span class="pill">Latest</span>
                                    @endif
                                </div>
                                @if ($release['date'])
                                    <time class="release-date" datetime="{{ $release['date'] }}">{{ $release['date'] }}</time>
                                @endif
                            </div>

                            @foreach ($release['sections'] as $section)
                                <div class="section-group">
                                    <div class="section-badge">{{ $section['type'] }}</div>
                                    <ul class="section-list">
                                        @foreach ($section['items'] as $item)
                                            <li>{{ \App\Support\Changelog::formatInline($item) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </main>
</body>
</html>
