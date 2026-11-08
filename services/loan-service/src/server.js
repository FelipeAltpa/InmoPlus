import http from 'node:http';
import { timingSafeEqual } from 'node:crypto';

const port = Number(process.env.PORT || 8092);
const secret = process.env.LOAN_SERVICE_TOKEN || '';
const maxBodyBytes = 16 * 1024;

function respond(response, status, data) {
  response.writeHead(status, {
    'Content-Type': 'application/json; charset=utf-8',
    'Cache-Control': 'no-store',
    'X-Content-Type-Options': 'nosniff',
  });
  response.end(JSON.stringify(data));
}

function hasValidToken(candidate) {
  const expected = Buffer.from(secret);
  const supplied = Buffer.from(candidate);
  return expected.length > 0 && expected.length === supplied.length && timingSafeEqual(expected, supplied);
}

const server = http.createServer(async (request, response) => {
  const url = new URL(request.url || '/', `http://${request.headers.host || 'localhost'}`);

  if (request.method === 'GET' && url.pathname === '/health') {
    return respond(response, 200, { status: 'ok', service: 'inmoplus-loan' });
  }

  if (request.method !== 'POST' || url.pathname !== '/api/simulations') {
    return respond(response, 404, { message: 'Ruta no encontrada.' });
  }

  if (!hasValidToken(String(request.headers['x-service-token'] || ''))) {
    return respond(response, 401, { message: 'Servicio no autorizado.' });
  }

  const chunks = [];
  let bodyBytes = 0;
  for await (const chunk of request) {
    bodyBytes += chunk.length;
    if (bodyBytes > maxBodyBytes) {
      request.resume();
      return respond(response, 413, { message: 'Solicitud demasiado grande.' });
    }
    chunks.push(chunk);
  }

  let input;
  try {
    input = JSON.parse(Buffer.concat(chunks).toString('utf8'));
  } catch {
    return respond(response, 400, { message: 'JSON inválido.' });
  }

  const principal = Number(input.principal);
  const annualRate = Number(input.annual_rate);
  const months = Number(input.months);
  if (!Number.isFinite(principal) || principal < 1_000_000 || principal > 10_000_000_000
      || !Number.isFinite(annualRate) || annualRate < 0 || annualRate > 100
      || !Number.isInteger(months) || months < 1 || months > 360) {
    return respond(response, 422, {
      message: 'Datos fuera de rango.',
      errors: {
        principal: '1.000.000–10.000.000.000 COP',
        annual_rate: '0–100 %',
        months: '1–360',
      },
    });
  }

  const monthlyRate = Math.pow(1 + annualRate / 100, 1 / 12) - 1;
  const installment = monthlyRate === 0
    ? principal / months
    : principal * monthlyRate / (1 - Math.pow(1 + monthlyRate, -months));

  return respond(response, 200, {
    currency: 'COP',
    principal,
    annual_rate: annualRate,
    months,
    monthly_installment: Number(installment.toFixed(2)),
    total_paid: Number((installment * months).toFixed(2)),
    total_interest: Number((installment * months - principal).toFixed(2)),
    disclaimer: 'Simulación educativa, no constituye una oferta de crédito.',
  });
});

server.listen(port, '0.0.0.0', () => console.log(`InmoPlus Loan Service activo en :${port}`));
