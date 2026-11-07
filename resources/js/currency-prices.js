const currencySelector = document.querySelector('[data-currency-select]');

if (currencySelector) {
    const currencyControl = currencySelector.closest('[data-currency-switcher]');
    const status = currencyControl.querySelector('[data-currency-status]');
    const originalCurrency = 'COP';
    const rateCache = new Map();
    let displayedCurrency = originalCurrency;

    const formatMoney = (amount, currency) => new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency,
        currencyDisplay: 'code',
        maximumFractionDigits: currency === 'COP' ? 0 : 2,
    }).format(amount);

    const renderPrices = (currency, rate = 1) => {
        document.querySelectorAll('[data-cop-price]').forEach((element) => {
            const copAmount = Number(element.dataset.copPrice);
            if (!Number.isFinite(copAmount)) return;

            const amount = copAmount * rate;
            element.textContent = `${formatMoney(amount, currency)}${element.dataset.unit || ''}`;
        });
    };

    const getRate = async (quote) => {
        if (rateCache.has(quote)) return rateCache.get(quote);

        const url = new URL(currencyControl.dataset.ratesUrl, window.location.origin);
        url.searchParams.set('quote', quote);

        const response = await fetch(url, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });
        const data = await response.json().catch(() => null);

        if (!response.ok || !data || data.base !== originalCurrency || data.quote !== quote || !Number.isFinite(Number(data.rate))) {
            throw new Error(data?.message || 'No se pudo obtener la tasa de cambio.');
        }

        const rate = { value: Number(data.rate), date: data.date };
        rateCache.set(quote, rate);
        return rate;
    };

    currencySelector.addEventListener('change', async () => {
        const targetCurrency = currencySelector.value;
        if (targetCurrency === originalCurrency) {
            displayedCurrency = originalCurrency;
            renderPrices(originalCurrency);
            status.textContent = 'Precios originales en COP.';
            status.classList.remove('text-red-600');
            return;
        }

        currencySelector.disabled = true;
        status.textContent = `Consultando tasa COP → ${targetCurrency}…`;
        status.classList.remove('text-red-600');

        try {
            const rate = await getRate(targetCurrency);
            displayedCurrency = targetCurrency;
            renderPrices(targetCurrency, rate.value);
            status.innerHTML = '';
            status.append(document.createTextNode(`Tasa de referencia del ${rate.date} · `));
            const source = document.createElement('a');
            source.href = 'https://frankfurter.dev/';
            source.target = '_blank';
            source.rel = 'noopener noreferrer';
            source.className = 'underline underline-offset-2';
            source.textContent = 'Frankfurter';
            status.append(source);
        } catch (error) {
            currencySelector.value = displayedCurrency;
            status.textContent = `${error.message} Se mantienen los precios en ${displayedCurrency}.`;
            status.classList.add('text-red-600');
        } finally {
            currencySelector.disabled = false;
        }
    });

    renderPrices(originalCurrency);
}
