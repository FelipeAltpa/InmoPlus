@props(['amount', 'unit' => ''])

<span data-cop-price="{{ $amount }}" data-unit="{{ $unit }}" {{ $attributes->class('font-semibold tabular-nums text-slate-900') }}>
    COP ${{ number_format((float) $amount, 0, ',', '.') }}{{ $unit }}
</span>
