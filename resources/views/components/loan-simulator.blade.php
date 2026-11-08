<section class="rounded-xl bg-white p-6 shadow" data-loan-simulator>
    <h3 class="text-lg font-semibold text-gray-900">Simulador hipotecario</h3><p class="mt-1 text-sm text-gray-600">Calcula una cuota estimada para esta propiedad (COP).</p>
    <form class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3" data-loan-form>
        <label class="text-sm">Monto del crédito<input name="principal" type="number" min="1000000" max="10000000000" required value="{{ (int) $property->price }}" class="mt-1 w-full rounded-md border-gray-300"></label>
        <label class="text-sm">Tasa efectiva anual (%)<input name="annual_rate" type="number" min="0" max="100" step="0.1" required value="12" class="mt-1 w-full rounded-md border-gray-300"></label>
        <label class="text-sm">Plazo (meses)<input name="months" type="number" min="1" max="360" required value="240" class="mt-1 w-full rounded-md border-gray-300"></label>
        <button class="sm:col-span-3 rounded-md bg-indigo-600 px-4 py-2 text-white">Calcular cuota</button>
    </form><p class="mt-3 text-sm" data-loan-result aria-live="polite"></p>
</section>
@once
<script>
document.addEventListener('submit', async (event) => {
  const form = event.target.closest('[data-loan-form]'); if (!form) return;
  event.preventDefault(); const box=form.closest('[data-loan-simulator]'); const output=box.querySelector('[data-loan-result]'); output.textContent='Calculando…';
  try { const data=Object.fromEntries(new FormData(form)); const response=await fetch('/api/loan-simulations',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(data)}); const result=await response.json(); if(!response.ok) throw new Error(result.message||'No fue posible calcular.'); output.textContent=`Cuota estimada: ${new Intl.NumberFormat('es-CO',{style:'currency',currency:'COP',maximumFractionDigits:0}).format(result.monthly_installment)} · Total intereses: ${new Intl.NumberFormat('es-CO',{style:'currency',currency:'COP',maximumFractionDigits:0}).format(result.total_interest)}. ${result.disclaimer}`; } catch(error) { output.textContent=error.message; }
});
</script>
@endonce
