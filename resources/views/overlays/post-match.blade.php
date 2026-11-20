<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Post-Match Overlay</title>
    <style>
        :root {
            --accent: {{ $accent }};
            --cream: #ece8e1;
            --obsidian: #0f1923;
            --glass: rgba(15, 25, 35, 0.85);
            --border: rgba(236, 232, 225, 0.14);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 1.75rem;
            min-height: 100vh;
            font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, -apple-system, sans-serif;
            background: {{ $previewBg === 'dark' ? '#0F1923' : 'transparent' }};
            color: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .board {
            width: 100%;
            max-width: 1320px;
            background: var(--glass);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            padding: 1.75rem 2rem;
            box-shadow: 0 24px 60px -16px rgba(0, 0, 0, 0.7);
        }
        .scoreboard-header {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--border);
        }
        .team-side {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }
        .team-side--right { justify-content: flex-end; text-align: right; }
        .team-tag {
            padding: 0.35rem 0.75rem;
            border-radius: 0.75rem;
            font-weight: 800;
            background: rgba(255, 70, 85, 0.16);
            border: 1px solid rgba(255, 70, 85, 0.4);
        }
        .team-name { font-size: 1.4rem; font-weight: 800; }
        .series-score {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 2.25rem;
            font-weight: 900;
            padding: 0.4rem 1.25rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
        }
        .series-dash { color: var(--accent); font-size: 1.5rem; }
        .map-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin: 1.25rem 0;
        }
        .map-pill {
            padding: 0.45rem 0.9rem;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 700;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.04);
            color: var(--cream);
            cursor: pointer;
        }
        .map-pill--active {
            background: var(--accent);
            border-color: transparent;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }
        th {
            text-align: left;
            padding: 0.65rem 0.75rem;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9aa5b1;
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 0.65rem 0.75rem;
            border-bottom: 1px solid rgba(236, 232, 225, 0.06);
            font-weight: 600;
        }
        .agent-pill {
            padding: 0.15rem 0.55rem;
            border-radius: 9999px;
            font-size: 0.74rem;
            background: rgba(236, 232, 225, 0.1);
        }
        .empty {
            text-align: center;
            padding: 2rem;
            color: #9aa5b1;
        }
    </style>
</head>
<body>
    <main class="board">
        <header class="scoreboard-header">
            <div class="team-side">
                <span class="team-tag" id="pm-a-tag">{{ $matchData['team_a']['short_name'] }}</span>
                <span class="team-name" id="pm-a-name">{{ $matchData['team_a']['name'] }}</span>
            </div>

            <div class="series-score">
                <span id="pm-a-score">{{ $matchData['team_a_score'] }}</span>
                <span class="series-dash">:</span>
                <span id="pm-b-score">{{ $matchData['team_b_score'] }}</span>
            </div>

            <div class="team-side team-side--right">
                <span class="team-name" id="pm-b-name">{{ $matchData['team_b']['name'] }}</span>
                <span class="team-tag" id="pm-b-tag">{{ $matchData['team_b']['short_name'] }}</span>
            </div>
        </header>

        <div class="map-tabs" id="map-tabs"></div>
        <div id="stats-container"></div>
    </main>

    <script>
        let currentMatch = @json($matchData);
        let activeMapIndex = 0;
        const reverb = @json($reverb);

        function renderPostMatch(match) {
            currentMatch = match;
            document.getElementById('pm-a-score').textContent = match.team_a_score;
            document.getElementById('pm-b-score').textContent = match.team_b_score;

            const tabsEl = document.getElementById('map-tabs');
            const statsEl = document.getElementById('stats-container');

            if (!match.maps || match.maps.length === 0) {
                tabsEl.innerHTML = '';
                statsEl.innerHTML = '<div class="empty">No map results recorded yet.</div>';
                return;
            }

            if (activeMapIndex >= match.maps.length) activeMapIndex = 0;

            tabsEl.innerHTML = match.maps.map((m, idx) => `
                <button type="button" class="map-pill ${idx === activeMapIndex ? 'map-pill--active' : ''}" onclick="selectMap(${idx})">
                    Map ${m.map_number}: ${m.map_name} (${m.team_a_score} - ${m.team_b_score})
                </button>
            `).join('');

            const activeMap = match.maps[activeMapIndex];
            if (!activeMap.stats || activeMap.stats.length === 0) {
                statsEl.innerHTML = '<div class="empty">No player stats recorded for this map yet.</div>';
                return;
            }

            statsEl.innerHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>Player</th>
                            <th>Agent</th>
                            <th>ACS</th>
                            <th>K / D / A</th>
                            <th>ADR</th>
                            <th>HS%</th>
                            <th>FK / FD</th>
                            <th>Credits</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${activeMap.stats.map((s) => `
                            <tr>
                                <td>${s.player_name} <span style="opacity:0.6;font-size:0.78em;">(${s.riot_id})</span></td>
                                <td><span class="agent-pill">${s.agent || '—'}</span></td>
                                <td>${s.acs}</td>
                                <td>${s.kills} / ${s.deaths} / ${s.assists}</td>
                                <td>${Number(s.adr).toFixed(1)}</td>
                                <td>${Number(s.hs_percentage).toFixed(1)}%</td>
                                <td>${s.first_kills} / ${s.first_deaths}</td>
                                <td>${Number(s.credits_spent).toLocaleString()}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        function selectMap(index) {
            activeMapIndex = index;
            renderPostMatch(currentMatch);
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
                if (msg.event === 'match.updated') {
                    const payload = typeof msg.data === 'string' ? JSON.parse(msg.data) : msg.data;
                    if (payload && payload.match) {
                        renderPostMatch(payload.match);
                    }
                }
            };

            ws.onclose = () => setTimeout(() => connectReverb(matchId), 3000);
        }

        renderPostMatch(currentMatch);
        connectReverb(currentMatch.id);
    </script>
</body>
</html>
