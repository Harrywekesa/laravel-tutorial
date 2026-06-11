# Module 16: Deployment & Hosting

**Duration:** 5–7 days | **Level:** Advanced

---

## Hosting Options

| Provider | Best For | Price |
|----------|----------|-------|
| **Laravel Forge** | Managed VPS deployment | $12+/mo |
| **Laravel Vapor** | Serverless (AWS Lambda) | Usage-based |
| **DigitalOcean** | VPS (Droplets) | $6+/mo |
| **AWS EC2 / Lightsail** | Full control | Varies |
| **Shared hosting** | Simple apps (cPanel) | $3+/mo |
| **Railway / Render** | Quick deploy from Git | Free tier |

---

## Server Requirements

- PHP 8.2+ with extensions (mbstring, openssl, pdo, tokenizer, xml, ctype, json, bcmath)
- Composer 2.x
- Nginx or Apache
- MySQL 8+ / PostgreSQL 15+ / SQLite (dev only)
- Redis (recommended for cache/queue)
- Node.js (build step only)

---

## Deployment Checklist

### 1. Prepare Application

```bash
composer install --optimize-autoloader --no-dev
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 2. Environment

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://taskforge.example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=taskforge
DB_USERNAME=taskforge
DB_PASSWORD=strong-password

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
```

### 3. Nginx Configuration

See `taskforge/deploy/nginx.conf`:

```nginx
server {
    listen 80;
    server_name taskforge.example.com;
    root /var/www/taskforge/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 4. File Permissions

```bash
chown -R www-data:www-data /var/www/taskforge
chmod -R 755 /var/www/taskforge
chmod -R 775 storage bootstrap/cache
```

### 5. Run Migrations

```bash
php artisan migrate --force
php artisan storage:link
```

### 6. Process Managers

**Queue worker (Supervisor):**

```ini
[program:taskforge-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/taskforge/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
```

**Scheduler cron:**

```cron
* * * * * www-data cd /var/www/taskforge && php artisan schedule:run >> /dev/null 2>&1
```

---

## SSL with Let's Encrypt

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d taskforge.example.com
```

---

## Zero-Downtime Deployment

Use **Forge**, **Envoyer**, or **GitHub Actions** with:

1. Clone to new release directory
2. `composer install --no-dev`
3. `php artisan migrate --force`
4. Symlink `current` → new release
5. Reload PHP-FPM
6. Restart queue workers: `php artisan queue:restart`

---

## Docker / Laravel Sail

```bash
composer require laravel/sail --dev
php artisan sail:install
./vendor/bin/sail up
```

---

## CI/CD with GitHub Actions

See `taskforge/deploy/github-actions.yml` for automated test + deploy pipeline.

---

## Exercises

1. Deploy TaskForge to a free Render.com or Railway instance.
2. Set up SSL and verify `https://` works.
3. Configure Supervisor for queue workers.

---

## Next Module

→ [Module 17: Maintenance & Monitoring](17-maintenance-monitoring.md)
