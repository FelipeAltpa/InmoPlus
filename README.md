# InmoPlus

Sistema inmobiliario académico construido con Laravel 12, PHP 8.2 o superior, MySQL, Blade, Tailwind CSS, Alpine.js, DataTables, Chart.js, JWT y DomPDF. Incluye catálogo público, cuentas con roles, panel administrativo, CRUD de propiedades, tipos y clientes, API REST, conversión de precios COP/USD/EUR con Frankfurter, imágenes y reportes PDF.

**Producción:** <https://inmoplus.northcentralus.cloudapp.azure.com> (Swagger en [`/docs/api`](https://inmoplus.northcentralus.cloudapp.azure.com/docs/api)).

## Requisitos

- Laragon con PHP 8.2+ y MySQL 8+ (en este equipo: PHP 8.3 y MySQL 8.4).
- Composer 2 y Node.js 20.6 o superior/npm.
- Extensiones PHP: `pdo_mysql`, `fileinfo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype` y `json`.

## Instalación local con Laragon

1. Inicia **MySQL** desde Laragon y crea una base vacía llamada `inmoplus` en HeidiSQL o ejecuta `CREATE DATABASE inmoplus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`.
2. Abre una terminal en la raíz del proyecto y ejecuta:

   ```bash
   composer install
   copy .env.example .env
   php artisan key:generate
   php artisan jwt:secret
   ```

3. En `.env`, configura `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=inmoplus`, `DB_USERNAME=root` y la contraseña que configuraste en Laragon. Para enviar los correos de verificación y de recuperación de contraseña configura Gmail: activa la verificación en dos pasos de la cuenta, crea una [contraseña de aplicación](https://myaccount.google.com/apppasswords) y usa `MAIL_MAILER=smtp`, `MAIL_HOST=smtp.gmail.com`, `MAIL_PORT=587`, `MAIL_USERNAME` y `MAIL_FROM_ADDRESS` con la cuenta y `MAIL_PASSWORD` con la contraseña de aplicación (el bloque comentado está en `.env.example`). Luego ejecuta `php artisan config:clear`. Con el transporte `log` los enlaces solo aparecen en `storage/logs/laravel.log`.
4. Instala frontend, aplica las migraciones y prepara archivos públicos:

   ```bash
   npm install
   php artisan migrate --seed
   php artisan storage:link
   npm run build
   ```

5. Inicia la aplicación, Vite y el servicio de tasas con un solo comando:

   ```bash
   composer dev
   ```

   Verifica que `RATES_SERVICE_TOKEN` tenga un valor en `.env`; si está vacío, genera uno con `php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"`. El servicio de tasas reutiliza ese valor. No ejecutes al mismo tiempo el contenedor Docker de `rates-service`, porque ambos usan el puerto 8091. El simulador hipotecario es opcional: solo funciona si inicias también `services/loan-service` con Docker Compose y configuras `LOAN_SERVICE_TOKEN`; el resto del sistema no lo necesita.

   Visita <http://127.0.0.1:8000>. Swagger está en `/docs/api` (pública también en producción). Para ver el cambio de moneda, selecciona USD o EUR; COP es la moneda original.

Para reiniciar la base de datos: `php artisan migrate:fresh --seed`. **Esto borra las tablas y datos de esa base**, úsalo solo en desarrollo.

## Cuentas iniciales

El seeder crea cuentas de administrador, agentes y clientes. Contraseña de todas las cuentas sembradas: `password123`, o el valor de `SEED_PASSWORD` si lo defines en `.env` antes de ejecutar `php artisan migrate --seed`.

Las cuentas creadas desde el panel no reciben una contraseña compartida: se genera una clave aleatoria y, si tienen correo real, se envía un enlace de restablecimiento. El registro público crea cuentas de cliente; solo el administrador debe habilitar cuentas de agente.

- Administrador: `admin@inmoplus.com`
- Agente: `agente1@inmoplus.com`
- Agente: `agente2@inmoplus.com`
- Clientes de prueba: `maria.lopez@example.com` y `jose.ramirez@example.com` (el seeder crea 12 en total)

Cambia o elimina estas cuentas (o define `SEED_PASSWORD`) antes de publicar el sistema.

## Funciones incluidas

- Landing y catálogo público de inmuebles.
- Registro con verificación de correo obligatoria, login y recuperación de contraseña por correo. Las cuentas del seeder y las que crea el administrador ya quedan verificadas.
- Dashboard con estadísticas, gráfico Chart.js y listados DataTables.
- CRUD de propiedades, tipos de inmueble y clientes, con permisos por rol.
- Edición del perfil y carga de imágenes en el disco público.
- API documentada con Swagger/OpenAPI en `/docs/api` y `/docs/api.json`; colección Postman en `docs/InmoPlus.postman_collection.json`.
- Documentación interactiva de la API en **`/docs/api`** (enlace «Documentación API (Swagger)» en el menú lateral y en el pie del sitio): permite probar GET, POST, PUT y DELETE con cabeceras JSON y token JWT Bearer (botón *Authorization*).
- Selector público para presentar los precios inmobiliarios en COP, USD o EUR con tasas de referencia diarias de Frankfurter; la base de datos conserva COP.
- Reporte PDF descargable desde el panel.
- Administración de cuentas desde Panel → Usuarios: alta de agente/cliente, cambio de rol y activación/desactivación; al desactivar o cambiar rol se revocan los tokens API.
- Solicitudes de visita: el agente confirma la hora pedida o propone otra para que el cliente la acepte/decline; cliente, agente o administrador pueden cancelar según el estado.
- Búsqueda avanzada por texto, ciudad, barrio, operación, tipo, precio, habitaciones, baños y área, con orden por fecha, precio o área en web y API.
- Simulador de crédito hipotecario, publicado como servicio Node separado y consumido por Laravel mediante un token interno.

## Despliegue

Producción: <https://inmoplus.northcentralus.cloudapp.azure.com>

Corre en una máquina virtual de Azure (Ubuntu 24.04, 1 GB de RAM + 2 GB de swap) con Docker Compose (`compose.prod.yaml`). Contenedores:

- `app`: Laravel servido por FrankenPHP, con HTTPS automático de Let's Encrypt.
- `mysql`: MySQL 8.4.
- `rates-service` y `loan-service`.

Al arrancar, `app` ejecuta `php artisan migrate --force` y `php artisan optimize`. La base de datos, las imágenes subidas y los certificados viven en volúmenes Docker, así que se conservan entre actualizaciones.

Primera instalación en un servidor con Docker:

```bash
cp .env.example .env    # completar con los valores de producción (ver abajo)
docker compose -f compose.prod.yaml up -d --build
docker compose -f compose.prod.yaml exec app php artisan db:seed --force
```

El `.env` del servidor nunca se sube al repositorio y necesita:

- `APP_ENV=production` y `APP_URL=https://<dominio>`.
- `SERVER_NAME=<dominio>`: el dominio para el que se pide el certificado.
- `APP_KEY` y `JWT_SECRET`.
- `DB_USERNAME` y `DB_PASSWORD`: Compose crea ese usuario en MySQL la primera vez.
- `RATES_SERVICE_TOKEN` y `LOAN_SERVICE_TOKEN`.
- El bloque `MAIL_*` de Gmail.
- `SEED_PASSWORD`: en producción las cuentas sembradas usan este valor y no `password123`.

Compose fija por su cuenta `DB_HOST=mysql`, `APP_DEBUG=false` y las URL internas de los microservicios.

Para actualizar producción con el último commit, desde la raíz del repositorio y con la clave SSH de la VM:

```bash
git archive --format=tar HEAD | ssh -i ~/.ssh/inmoplus_key.pem azureuser@inmoplus.northcentralus.cloudapp.azure.com "tar -x -C ~/inmoplus"
ssh -i ~/.ssh/inmoplus_key.pem azureuser@inmoplus.northcentralus.cloudapp.azure.com "cd ~/inmoplus && docker compose -f compose.prod.yaml up -d --build"
```

## ZIP de entrega académica

Incluye el código fuente, `composer.json`, `composer.lock` (si se generó), `package.json`, `package-lock.json` (si se generó), migraciones, seeders, documentación y un dump SQL exportado desde MySQL. Excluye `.env`, `vendor/`, `node_modules/`, cachés, logs y credenciales. Conserva `.env.example`. Incluye instrucciones para importar el dump y ejecutar la instalación. No empaquetes el `.bak` de SQL Server: no es el formato de la base elegida para la entrega.

## Comandos útiles

```bash
php artisan route:list
php artisan storage:link
npm run build
```







