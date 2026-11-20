<div wire:poll.15s="refreshStatus" style="display: inline-flex; align-items: center;">
    <button
        type="button"
        wire:click="refreshStatus"
        title="{{ $isRunning ? "Reverb WebSocket active ({$endpoint}) — click to recheck" : "Reverb WebSocket offline ({$endpoint}) — click to recheck" }}"
        style="
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.32rem 0.72rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            cursor: pointer;
            border: 1px solid {{ $isRunning ? 'rgba(16, 185, 129, 0.35)' : 'rgba(255, 70, 85, 0.38)' }};
            background-color: {{ $isRunning ? 'rgba(16, 185, 129, 0.12)' : 'rgba(255, 70, 85, 0.12)' }};
            color: {{ $isRunning ? '#10B981' : '#FF4655' }};
            transition: background-color 150ms ease, border-color 150ms ease, transform 120ms ease;
        "
    >
        <span style="
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 9999px;
            background-color: {{ $isRunning ? '#10B981' : '#FF4655' }};
            box-shadow: 0 0 0 3px {{ $isRunning ? 'rgba(16, 185, 129, 0.22)' : 'rgba(255, 70, 85, 0.22)' }};
        "></span>
        <span>{{ $isRunning ? 'Reverb Live' : 'Reverb Offline' }}</span>
    </button>
</div>
