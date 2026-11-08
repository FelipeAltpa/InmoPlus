# Guion de la presentación (15 min, 3 integrantes)

Cada requisito del profesor aparece una vez, con la ruta donde se muestra.

## Antes de empezar (la noche anterior y 10 min antes)

- [ ] MySQL arriba y base limpia: `php artisan migrate:fresh --seed` (borra datos; solo en local).
- [ ] `php artisan storage:link` y `npm run build` ejecutados.
- [ ] `.env` con Gmail (contraseña de aplicación) para la verificación y la recuperación de contraseña. Prueba un envío antes y ten abierta la bandeja del correo con el que vas a registrarte.
- [ ] `composer dev` corriendo en una terminal. Abre <http://127.0.0.1:8000>.
- [ ] Postman con la colección `docs/InmoPlus.postman_collection.json` importada.
- [ ] Tres ventanas listas: una normal (admin), una de incógnito (agente), una con Postman.
- [ ] Una imagen `.jpg`/`.png` de más de 100x100 px en el escritorio para la subida.
- [ ] Plan B: capturas del flujo por si falla internet o el SMTP.

Cuentas (clave `password123` o la de `SEED_PASSWORD`):
`admin@inmoplus.com`, `agente1@inmoplus.com`, `maria.lopez@example.com` (cliente).

## Reparto y tiempos

| Min | Quién | Qué se muestra | Ruta / requisito |
|---|---|---|---|
| 0:00–1:30 | A | Presentación del proyecto, stack (Laravel 12, MySQL, Blade, Tailwind, JWT). Mostrar carpeta de migraciones. | Req. 1 |
| 1:30–3:00 | A | Landing con propiedades, catálogo con filtros y cambio de moneda. | `/`, `/properties` — Req. 2 |
| 3:00–4:30 | A | Registro de un cliente nuevo: llega el correo de verificación, se abre el enlace y entra. Mostrar que se crea siempre con rol cliente y que sin verificar no accede. | `/register`, `/verify-email`, `/login` — Req. 2 |
| 4:30–6:00 | A | Recuperación de contraseña: pedir enlace, abrir el correo en Gmail, restablecer, entrar. | `/forgot-password` — Req. 4 |
| 6:00–8:00 | B | Entrar como admin. Dashboard con usuario activo, menú, gráfico por tipo. Cerrar sesión desde el menú del avatar. | `/dashboard` — Req. 5, 6 |
| 8:00–9:30 | B | CRUD de propiedades: crear con imagen principal y galería, editar, eliminar. Tabla DataTables (buscar, ordenar). | `/dashboard/properties` — Req. 5, 7, 10 |
| 9:30–10:30 | B | Segunda tabla DataTables y CRUD: tipos de propiedad y clientes. | `/dashboard/property-types`, `/dashboard/clients` — Req. 5, 7 |
| 10:30–11:30 | B | Editar perfil propio con foto. | `/profile` — Req. 5, 10 |
| 11:30–12:30 | C | Reporte PDF descargable. | `/dashboard/reports` — Req. 9 |
| 12:30–13:30 | C | Seguridad por rol: agente (incógnito) no ve usuarios ni clientes; URL directa da 403; un agente no edita propiedades ajenas. | Req. 3 |
| 13:30–15:00 | C | API: en Postman, login (token JWT), GET listado, POST, PUT, DELETE con `Authorization: Bearer`; 401 sin token y 403 con cliente. Mostrar también Swagger en `/docs/api` (botón *Authorization* con el token y *Send API Request*). | `/api/*` — Req. 8, 11, 12 |

Cierre (los 3, 30 s): layout maestro compartido (`x-app-layout`, `x-guest-layout`, `x-public-layout`), README, tests con `composer test`.

## Preguntas probables y respuesta corta

- **¿Por qué JWT y no Sanctum?** Token con vencimiento (60 min), abilities por rol y revocación con `token_version`.
- **¿Qué impide que alguien se registre como admin?** El controlador fija `role = client` (`RegisteredUserController`).
- **¿Qué pasa si un agente intenta editar una propiedad ajena?** `PropertyPolicy` responde 403 en web y API.
- **¿Cómo se revocan los tokens?** Al desactivar un usuario, cambiarle el rol o su contraseña se incrementa `token_version`.
- **¿Dónde está el modelo de datos?** Solo en `database/migrations/`.

## Si algo falla

- Sin correo: cambiar a `MAIL_MAILER=log`, ejecutar `php artisan config:clear` y copiar el enlace de verificación o de reset desde `storage/logs/laravel.log`.
- Sin servicio de tasas: el selector de moneda no cambia precios; el resto funciona. No es parte del checklist.
- Error 500 inesperado: `APP_DEBUG=true` en local muestra la causa; durante la presentación no lo cambies.
