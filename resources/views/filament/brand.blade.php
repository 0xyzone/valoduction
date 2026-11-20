<div style="display: inline-flex; align-items: center; gap: 0.55rem; white-space: nowrap;">
    <span style="
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 0.55rem;
        background: linear-gradient(135deg, #FF4655, #D93442);
        color: #ECE8E1;
        font-size: 0.875rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        box-shadow: 0 6px 14px -4px rgba(255, 70, 85, 0.55);
    ">V</span>

    <span style="font-size: 1.125rem; font-weight: 800; letter-spacing: -0.025em; line-height: 1.25;">
        {{ $brandName }}
    </span>

    <button
        type="button"
        title="View Changelog"
        onclick="event.preventDefault(); event.stopPropagation(); window.open('{{ route('changelog') }}', '_blank', 'noopener,noreferrer');"
        style="
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.55rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: 0.02em;
            background-color: rgba(255, 70, 85, 0.12);
            color: #FF4655;
            border: 1px solid rgba(255, 70, 85, 0.35);
            cursor: pointer;
            transition: background-color 150ms ease, border-color 150ms ease, transform 120ms ease;
        "
        onmouseover="this.style.backgroundColor='rgba(255, 70, 85, 0.22)'; this.style.borderColor='rgba(255, 70, 85, 0.6)';"
        onmouseout="this.style.backgroundColor='rgba(255, 70, 85, 0.12)'; this.style.borderColor='rgba(255, 70, 85, 0.35)';"
    >
        {{ $version }}
    </button>
</div>
