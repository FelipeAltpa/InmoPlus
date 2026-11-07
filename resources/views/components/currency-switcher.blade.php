@props(['id' => 'display-currency', 'dark' => false])

<div data-currency-switcher data-rates-url="{{ url('/api/exchange-rates') }}" class="flex flex-wrap items-center gap-2">
    <label for="{{ $id }}" class="text-sm font-medium {{ $dark ? 'text-white' : 'text-gray-600' }}">Mostrar precios en</label>
    <select id="{{ $id }}" data-currency-select class="rounded-lg border-gray-300 py-2 pl-3 pr-8 text-sm font-semibold text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="COP" selected>Pesos colombianos (COP)</option>
        <option value="USD">Dólares (USD)</option>
        <option value="EUR">Euros (EUR)</option>
    </select>
    <span data-currency-status class="text-xs {{ $dark ? 'text-white/80' : 'text-gray-500' }}" role="status" aria-live="polite">Tasa de referencia diaria · Frankfurter</span>
</div>
