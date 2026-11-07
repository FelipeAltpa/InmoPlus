import http from 'node:http';
import { timingSafeEqual } from 'node:crypto';

const port = Number(process.env.PORT || 8091);
const serviceToken = process.env.RATES_SERVICE_TOKEN || '';
const upstream = (process.env.FRANKFURTER_BASE_URL || 'https://api.frankfurter.dev/v2').replace(/\/$/, '');
const cacheTtlMs = 12 * 60 * 60 * 1000;
let cache = null;

const validServiceToken = (candidate = '') => {
    const expected = Buffer.from(serviceToken);
    const received = Buffer.from(candidate);
    return expected.length > 0 && expected.length === received.length && timingSafeEqual(expected, received);
};

const send = (res, status, payload) => {
    res.writeHead(status, { 'Content-Type': 'application/json; charset=utf-8', 'Cache-Control': 'no-store' });
    res.end(JSON.stringify(payload));
};

const server = http.createServer(async (req, res) => {
    const url = new URL(req.url || '/', `http://${req.headers.host || 'localhost'}`);

    if (url.pathname === '/health' && req.method === 'GET') {
        return send(res, 200, { status: 'ok', service: 'inmoplus-rates' });
    }
    if (url.pathname !== '/api/rates' || req.method !== 'GET') {
        return send(res, 404, { message: 'Ruta no encontrada.' });
    }
    if (!validServiceToken(String(req.headers['x-service-token'] || ''))) {
        return send(res, 401, { message: 'Servicio no autorizado.' });
    }

    try {
        if (!cache || cache.expiresAt <= Date.now()) {
            const response = await fetch(`${upstream}/rates?base=COP&quotes=USD,EUR`, {
                headers: { Accept: 'application/json' },
                signal: AbortSignal.timeout(8000),
            });
            if (!response.ok) throw new Error(`Frankfurter respondió HTTP ${response.status}.`);
            const rows = await response.json();
            const rates = {};
            let date = null;
            for (const row of rows) {
                if (['USD', 'EUR'].includes(row?.quote) && Number.isFinite(Number(row.rate)) && Number(row.rate) > 0) {
                    rates[row.quote] = Number(row.rate);
                    date ||= row.date;
                }
            }
            if (!date || !rates.USD || !rates.EUR) throw new Error('Respuesta incompleta de Frankfurter.');
            cache = { date, rates, expiresAt: Date.now() + cacheTtlMs };
        }
        return send(res, 200, { date: cache.date, base: 'COP', rates: cache.rates, source: 'Frankfurter', stale: false });
    } catch (error) {
        console.error('Fallo consultando Frankfurter:', error.message);
        if (cache) {
            return send(res, 200, { date: cache.date, base: 'COP', rates: cache.rates, source: 'Frankfurter', stale: true });
        }
        return send(res, 503, { message: 'No se pudo obtener la tasa de cambio.' });
    }
});

server.listen(port, '0.0.0.0', () => console.log(`InmoPlus Rates Service activo en :${port}`));
