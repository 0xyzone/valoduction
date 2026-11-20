<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Map Veto Overlay</title>
    <style>
        :root {
            --accent: {{ $accent }};
            --cream: #ece8e1;
            --obsidian: #0f1923;
            --glass: rgba(15, 25, 35, 0.82);
            --border: rgba(236, 232, 225, 0.14);
            --gold: #c89b3c;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 2rem;
            min-height: 100vh;
            font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, -apple-system, sans-serif;
            background: {{ $previewBg === 'dark' ? '#0F1923' : 'transparent' }};
            color: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .overlay-frame {
            width: 100%;
            max-width: 1280px;
            background: var(--glass);
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            padding: 1.75rem 2rem;
            box-shadow: 0 24px 60px -16px rgba(0, 0, 0, 0.65);
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        .team-block {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }
        .team-tag {
            padding: 0.35rem 0.75rem;
            border-radius: 0.75rem;
            font-weight: 800;
            font-size: 0.95rem;
            background: rgba(255, 70, 85, 0.16);
            border: 1px solid rgba(255, 70, 85, 0.4);
            color: var(--cream);
        }
        .team-name {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .center-meta {
            text-align: center;
        }
        .tournament-name {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--accent);
        }
        .match-title {
            font-size: 1.15rem;
            font-weight: 800;
            margin-top: 0.2rem;
        }
        .veto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.9rem;
        }
        .veto-card {
            position: relative;
            border-radius: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            transition: transform 200ms ease, border-color 200ms ease;
        }
        .veto-card--ban { opacity: 0.58; }
        .veto-card--pick {
            border-color: rgba(255, 70, 85, 0.55);
            background: rgba(255, 70, 85, 0.09);
        }
        .veto-card--decider {
            border-color: rgba(200, 155, 60, 0.65);
            background: rgba(200, 155, 60, 0.12);
        }
        .veto-step {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .veto-badge {
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.68rem;
            font-weight: 800;
        }
        .badge-ban { background: rgba(236, 232, 225, 0.12); color: #cbd5e1; }
        .badge-pick { background: var(--accent); color: #ece8e1; }
        .badge-decider { background: var(--gold); color: #0f1923; }
        .veto-map {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .veto-side {
            font-size: 0.78rem;
            font-weight: 600;
            color: #cbd5e1;
            text-transform: uppercase;
        }
        .empty {
            text-align: center;
            padding: 2rem;
            color: #9aa5b1;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <main class="overlay-frame" id="veto-app">
        <header class="header">
            <div class="team-block">
                <span class="team-tag" id="team-a-tag">{{ $matchData['team_a']['short_name'] }}</span>
                <span class="team-name" id="team-a-name">{{ $matchData['team_a']['name'] }}</span>
            </div>

            <div class="center-meta">
                <div class="tournament-name" id="tournament-name">{{ $matchData['tournament']['name'] }}</div>
                <div class="match-title" id="match-title">
                    MAP VETO • BO{{ $matchData['best_of'] }}
                    @if ($matchData['round_label']) • {{ $matchData['round_label'] }} @endif
                </div>
            </div>

            <div class="team-block">
                <span class="team-name" id="team-b-name">{{ $matchData['team_b']['name'] }}</span>
                <span class="team-tag" id="team-b-tag">{{ $matchData['team_b']['short_name'] }}</span>
            </div>
        </header>

        <section class="veto-grid" id="veto-grid"></section>
    </main>

    <script>
        const initialMatch = @json($matchData);
        const reverb = @json($reverb);

        function renderVeto(match) {
            const grid = document.getElementById('veto-grid');
            if (!match.vetos || match.vetos.length === 0) {
                grid.innerHTML = '<div class="empty">Waiting for map veto steps...</div>';
                return;
            }

            grid.innerHTML = match.vetos.map((veto) => {
                const action = (veto.action || 'ban').toLowerCase();
                const teamLabel = veto.team ? veto.team.short_name : 'DECIDER';
                const sideLabel = veto.side ? `Start: ${veto.side}` : '';
                return `
                    <article class="veto-card veto-card--${action}">
                        <div class="veto-step">
                            <span>${teamLabel}</span>
                            <span class="veto-badge badge-${action}">${action}</span>
                        </div>
                        <div class="veto-map">${veto.map_name}</div>
                        ${sideLabel ? `<div class="veto-side">${sideLabel}</div>` : ''}
                    </article>
                `;
            }).join('');
        }

        function connectReverb(matchId) {
            if (!reverb.key) return;
            const protocol = reverb.scheme === 'https' ? 'wss' : 'ws';
            const ws = new WebSocket(`${protocol}://${reverb.host}:${reverb.port}/app/${reverb.key}?protocol=7&client=js&version=8.4.0`);

            ws.onmessage = (event) => {
                const msg = JSON.parse(event.data);
                if (msg.event === 'pusher:connection_established') {
                    ws.send(JSON.stringify({
                        event: 'pusher:subscribe',
                        data: { channel: `matches.${matchId}` }
                    }));
                }
                if (msg.event === 'veto.updated' || msg.event === 'match.updated') {
                    const payload = typeof msg.data === 'string' ? JSON.parse(msg.data) : msg.data;
                    if (payload && payload.match) {
                        renderVeto(payload.match);
                    }
                }
            };

            ws.onclose = () => setTimeout(() => connectReverb(matchId), 3000);
        }

        renderVeto(initialMatch);
        connectReverb(initialMatch.id);
    </script>
</body>
</html>
