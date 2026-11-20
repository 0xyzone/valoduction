@php
    $swatches ??= [
        '#FF4655',
        '#BD3944',
        '#C89B3C',
        '#F59E0B',
        '#10B981',
        '#06B6D4',
        '#3B82F6',
        '#6366F1',
        '#A855F7',
        '#0F1923',
        '#ECE8E1',
        '#64748B',
    ];
@endphp

<div
    x-data="{
        selected: $wire.$entangle('{{ $statePath }}'),
        pick(hex) {
            this.selected = hex;
        }
    }"
    style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.45rem; margin-top: 0.55rem;"
>
    @foreach ($swatches as $hex)
        <button
            type="button"
            title="{{ $hex }}"
            aria-label="Select color {{ $hex }}"
            x-on:click="pick('{{ $hex }}')"
            x-bind:style="{
                backgroundColor: '{{ $hex }}',
                transform: (selected || '').toUpperCase() === '{{ strtoupper($hex) }}' ? 'scale(1.15)' : 'scale(1)',
                boxShadow: (selected || '').toUpperCase() === '{{ strtoupper($hex) }}'
                    ? '0 0 0 2px rgba(255, 70, 85, 0.9), 0 4px 10px -2px rgba(0, 0, 0, 0.35)'
                    : 'inset 0 0 0 1px rgba(255, 255, 255, 0.22)'
            }"
            style="
                width: 1.4rem;
                height: 1.4rem;
                border-radius: 9999px;
                border: none;
                cursor: pointer;
                transition: transform 120ms ease, box-shadow 120ms ease;
            "
        ></button>
    @endforeach
</div>
