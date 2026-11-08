# InmoPlus Loan Service

Microservicio Node.js que calcula cuotas hipotecarias. Solo Laravel lo consume; las credenciales internas nunca llegan al navegador.

1. Copia `.env.example` a `.env` y define `LOAN_SERVICE_TOKEN` con una cadena aleatoria larga.
2. Usa exactamente el mismo valor en `LOAN_SERVICE_TOKEN` del `.env` de Laravel.
3. Inicia el servicio desde esta carpeta con `docker compose up -d --build`.
4. Comprueba `http://127.0.0.1:8092/health`; la app publica la fachada en `POST /api/loan-simulations`.

Para desarrollo sin Docker, usa Node.js 20 o posterior y ejecuta `npm start` con `PORT=8092` y `LOAN_SERVICE_TOKEN` configurados.
