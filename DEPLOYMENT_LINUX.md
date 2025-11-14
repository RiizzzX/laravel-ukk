# Deployment Guide untuk Linux (Case-Sensitive Filesystem)

## Prerequisites
- PHP 8.1+
- MySQL/MariaDB
- Nginx atau Apache
- Composer
- Git

## Persiapan Sebelum Deploy

### 1. Clone Repository
```bash
git clone https://github.com/RiizzzX/laravel-ukk.git
cd laravel-ukk
```

### 2. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

**Catatan:** Tidak perlu `npm install` karena project sudah menggunakan Tailwind CDN.

### 3. Setup Environment File
```bash
cp .env.example .env
nano .env
```

Update konfigurasi sesuai environment production:
```dotenv
APP_NAME="NGASAR"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sarpras
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Setup Database
```bash
# Import database
mysql -u your_db_user -p sarpras < database/schema/mysql-schema.sql

# Atau jalankan migrations
php artisan migrate --force

# Seed data (optional)
php artisan db:seed --class=ItemListLokasiSeeder
```

### 6. Setup Storage & Permissions
```bash
# Create symbolic link untuk storage
php artisan storage:link

# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 7. Optimize untuk Production
```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

## Konfigurasi Web Server

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/laravel-ukk/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Apache Configuration (.htaccess sudah include)
File `.htaccess` di folder `public/` sudah dikonfigurasi dengan benar.

## Case Sensitivity Checklist

✅ **Files yang sudah diperbaiki:**
- `app/Models/ListLokasi.php` (renamed dari `Listlokasi.php`)
- Semua import statements menggunakan `ListLokasi` dengan benar
- View files menggunakan lowercase yang konsisten

✅ **Import statements yang perlu diperhatikan:**
```php
use App\Models\ListLokasi;  // ✓ Correct
use App\Models\Listlokasi;  // ✗ Wrong (akan error di Linux)
```

## Testing di Linux

### 1. Check Filesystem Case
```bash
# Test case sensitivity
cd /var/www/laravel-ukk/app/Models
ls -la | grep -i "listlokasi"
# Harus muncul: ListLokasi.php (dengan huruf kapital L di awal dan tengah)
```

### 2. Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 3. Test Autoloader
```bash
composer dump-autoload
php artisan tinker
>>> App\Models\ListLokasi::first();
# Harus bisa load model tanpa error
```

## Troubleshooting

### Error: "Class 'App\Models\Listlokasi' not found"
**Solusi:**
```bash
# Pastikan nama file exact dengan classname
cd app/Models
mv Listlokasi.php ListLokasi.php

# Clear cache
php artisan cache:clear
composer dump-autoload
```

### Error: Permission denied untuk storage
**Solusi:**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Error: 500 Internal Server Error
**Solusi:**
1. Check error log: `tail -f storage/logs/laravel.log`
2. Pastikan `.env` sudah di-setup dengan benar
3. Pastikan `APP_KEY` sudah di-generate
4. Clear semua cache

## Security Checklist untuk Production

- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Generate strong `APP_KEY`
- [ ] Update database credentials
- [ ] Setup SSL certificate (Let's Encrypt)
- [ ] Disable directory listing
- [ ] Setup firewall (UFW)
- [ ] Configure rate limiting
- [ ] Setup backups (database + files)
- [ ] Monitor logs regularly

## Maintenance Commands

```bash
# Update application
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Backup database
mysqldump -u user -p sarpras > backup_$(date +%Y%m%d).sql

# Monitor logs
tail -f storage/logs/laravel.log
```

## CDN Notice

Project ini menggunakan **Tailwind CSS dan Alpine.js via CDN**, sehingga:
- ✅ Tidak perlu `npm install`
- ✅ Tidak perlu `npm run build`
- ✅ Tidak perlu Node.js di server
- ✅ Deploy lebih cepat

File yang bisa dihapus (optional):
- `package.json`
- `vite.config.js`
- `postcss.config.js`
- `tailwind.config.js`
- Folder `node_modules/`

## Support

Untuk issue atau pertanyaan, silakan buka issue di:
https://github.com/RiizzzX/laravel-ukk/issues
