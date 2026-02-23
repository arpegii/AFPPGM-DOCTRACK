# AFPPGMC Document Tracking - Local Network Deployment

This guide is for deploying on a local network (LAN) and serving users from a host machine.

## 1) Server Requirements

- PHP `8.2+`
- Composer `2+`
- Node.js `18+` and npm
- MySQL/MariaDB (recommended for production)
- Web server access to project folder

## 2) First-Time Setup

From project root:

```powershell
composer install --no-dev --optimize-autoloader
npm install
copy .env.example .env
php artisan key:generate
```

Configure `.env`:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=http://<SERVER_IP>:8000` (or your domain/internal hostname)
- Set DB credentials (`DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
- `QUEUE_CONNECTION=sync` (default in this project for simpler LAN deployment)
- Configure mail settings if email notifications are required

Run database and assets:

```powershell
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
npm run build
php artisan optimize
```

## 3) Run the App (LAN Accessible)

```powershell
php artisan serve --host=0.0.0.0 --port=8000
```

Users connect using:

- `http://<SERVER_IP>:8000`

## 4) Required Background Process

This app uses scheduled overdue notifications. Keep scheduler running:

```powershell
php artisan schedule:work
```

If you later change `QUEUE_CONNECTION` from `sync` to `database`, also run:

```powershell
php artisan queue:work --tries=3 --timeout=120
```

## 5) Update Deployment

After pulling new code:

```powershell
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

Restart running app/scheduler processes.

## 6) Pre-Go-Live Checklist

- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` points to actual LAN address/hostname
- [ ] `php artisan test` passes
- [ ] `php artisan route:list` works
- [ ] `storage:link` exists
- [ ] Scheduler is running (`schedule:work`)
- [ ] Mail server settings verified (if using email notifications)
- [ ] Backup plan for MySQL database is in place

