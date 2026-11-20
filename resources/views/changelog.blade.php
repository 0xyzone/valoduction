<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Changelog — {{ $appName }}</title>
    <style>
        :root {
            --bg: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --accent: #d97706;
            --accent-bg: rgba(245, 158, 11, 0.12);
            --accent-border: rgba(245, 158, 11, 0.35);
            --line: #cbd5e1;
            --code-bg: #f1f5f9;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #09090b;
                --surface: #18181b;
                --border: #27272a;
                --text: #f4f4f5;
                --muted: #a1a1aa;
                --accent: #fbbf24;
                --accent-bg: rgba(245, 158, 11, 0.14);
                --accent-border: rgba(245, 158, 11, 0.35);
                --line: #3f3f46;
                --code-bg: #27272a;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 820px;
            margin: 0 auto;
            padding: 3rem 1.25rem 5rem;
        }

        .header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 3rem;
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-title {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .subtitle {
            margin: 0.375rem 0 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: var(--accent-bg);
            color: var(--accent);
            border: 1px solid var(--accent-border);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--text);
            background-color: var(--surface);
            border: 1px solid var(--border);
            transition: border-color 150ms ease, color 150ms ease;
        }

        .nav-link:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: "";
            position: absolute;
            top: 0.5rem;
            bottom: 0.5rem;
            left: 0.4375rem;
            width: 2px;
            background-color: var(--line);
        }

        .release {
            position: relative;
            margin-bottom: 2.5rem;
        }

        .release:last-child {
            margin-bottom: 0;
        }

        .release-node {
            position: absolute;
            left: -2rem;
            top: 0.45rem;
            width: 0.9375rem;
            height: 0.9375rem;
            border-radius: 9999px;
            background-color: var(--surface);
            border: 3px solid var(--line);
        }

        .release--latest .release-node {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-bg);
        }

        .release-card {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.875rem;
            padding: 1.5rem;
        }

        .release-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .release-version-group {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .release-version {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .release-date {
            font-size: 0.875rem;
            color: var(--muted);
        }

        .section-group {
            margin-top: 1.25rem;
        }

        .section-group:first-of-type {
            margin-top: 0;
        }

        .section-badge {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            margin-bottom: 0.5rem;
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
            font-size: 0.85em;
            padding: 0.1rem 0.35rem;
            border-radius: 0.25rem;
            background-color: var(--code-bg);
        }

        .empty-state {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.875rem;
            padding: 2rem;
            text-align: center;
            color: var(--muted);
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header">
            <div>
                <div class="brand-row">
                    <h1 class="brand-title">{{ $appName }} Changelog</h1>
                    <span class="pill">{{ $latestVersion }}</span>
                </div>
                <p class="subtitle">Release timeline tracking major, minor, and macro updates.</p>
            </div>

            <nav class="nav-links" aria-label="Changelog navigation">
                <a class="nav-link" href="{{ url('/') }}">Home</a>
                <a class="nav-link" href="{{ url('/admin') }}">Admin Panel</a>
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
