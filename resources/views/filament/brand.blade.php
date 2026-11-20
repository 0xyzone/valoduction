<div style="display: inline-flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
    <span style="font-size: 1.125rem; font-weight: 700; letter-spacing: -0.025em; line-height: 1.25;">
        {{ $brandName }}
    </span>

    <button
        type="button"
        title="View Changelog"
        onclick="event.preventDefault(); event.stopPropagation(); window.open('{{ route('changelog') }}', '_blank', 'noopener,noreferrer');"
        style="
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.25;
            letter-spacing: 0.01em;
            background-color: rgba(245, 158, 11, 0.14);
            color: rgb(217, 119, 6);
            border: 1px solid rgba(245, 158, 11, 0.35);
            cursor: pointer;
            transition: background-color 150ms ease, border-color 150ms ease;
        "
        onmouseover="this.style.backgroundColor='rgba(245, 158, 11, 0.24)'; this.style.borderColor='rgba(245, 158, 11, 0.55)';"
        onmouseout="this.style.backgroundColor='rgba(245, 158, 11, 0.14)'; this.style.borderColor='rgba(245, 158, 11, 0.35)';"
    >
        {{ $version }}
    </button>
</div>
