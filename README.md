# NGASAR - Sistem Pengaduan Sarana Prasarana

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Aplikasi web untuk mengelola pengaduan dan pemeliharaan sarana prasarana sekolah.

## 🚀 Features

- **Multi-Role System**: Admin, Petugas, dan User
- **Pengaduan Management**: Create, track, dan update status pengaduan
- **Item & Lokasi Management**: Many-to-many relationship untuk item di multiple lokasi
- **Real-time Notifications**: Sistem notifikasi untuk update status
- **Responsive Design**: Mobile-friendly dengan Tailwind CSS
- **API Ready**: REST API untuk integrasi mobile app
- **CDN-based**: Tidak perlu npm build process

## 📋 Requirements

- PHP 8.1 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.3+
- Composer
- Web Server (Apache/Nginx)

**Tidak perlu Node.js/npm** - Project menggunakan Tailwind CSS dan Alpine.js via CDN

## 🔧 Installation (Development)

### Windows (Laragon)

### Windows (Laragon)

```bash
# Clone repository
git clone https://github.com/RiizzzX/laravel-ukk.git
cd laravel-ukk

# Install dependencies
composer install

# Setup environment
cp .env.example .env
# Edit .env dengan database credentials

# Generate application key
php artisan key:generate

# Run migrations dan seeder
php artisan migrate
php artisan db:seed --class=ItemListLokasiSeeder

# Create storage link
php artisan storage:link

# Start server
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

### Linux (Production)

Lihat panduan lengkap di [DEPLOYMENT_LINUX.md](DEPLOYMENT_LINUX.md)

```bash
# Quick deployment
bash deploy.sh
```

## 🔐 Default Login Credentials

### Admin
- Username: `admin`
- Password: `admin123`

### Petugas
- Username: `petugas1`
- Password: `petugas123`

### User
- Username: `user1`
- Password: `user123`

## 📱 API Documentation

API documentation tersedia di [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

Base URL: `http://localhost:8000/api`

### Authentication
```bash
POST /api/login
POST /api/register
POST /api/logout
```

### Pengaduan
```bash
GET    /api/pengaduan
POST   /api/pengaduan
GET    /api/pengaduan/{id}
PUT    /api/pengaduan/{id}
DELETE /api/pengaduan/{id}
```

## 🗂️ Project Structure

```
ukk-laravel-main/
├── app/
│   ├── Http/Controllers/     # Controllers
│   │   ├── Api/              # API Controllers
│   │   ├── AdminController.php
│   │   ├── PetugasController.php
│   │   └── PengaduanController.php
│   ├── Models/               # Eloquent Models
│   │   ├── Item.php
│   │   ├── Lokasi.php
│   │   ├── ListLokasi.php    # Pivot table model
│   │   ├── Pengaduan.php
│   │   └── User.php
│   └── Observers/            # Model Observers
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   └── views/                # Blade templates
│       ├── admin/            # Admin views
│       ├── petugas/          # Petugas views
│       ├── pengaduan/        # User pengaduan views
│       └── layouts/          # Layout templates
├── routes/
│   ├── web.php               # Web routes
│   ├── api.php               # API routes
│   └── auth.php              # Auth routes
├── public/
│   └── storage/              # Symbolic link to storage
└── storage/
    └── app/public/           # User uploaded files
```

## 🛠️ Technology Stack

- **Backend**: Laravel 10.x
- **Database**: MySQL 8.0
- **Frontend**: Tailwind CSS 3.x (via CDN)
- **JavaScript**: Alpine.js 3.x (via CDN)
- **Authentication**: Laravel Sanctum
- **Icons**: Heroicons (SVG inline)

## 📝 Database Schema

### Many-to-Many Relationship
Project menggunakan **many-to-many relationship** untuk item dan lokasi:

- `items` - Daftar item unik (1 Kursi, 1 AC, dst)
- `lokasi` - Daftar lokasi/ruangan
- `list_lokasi` - Pivot table (1 item bisa di multiple lokasi)

Contoh:
```
Item "Kursi" (id=1) → tersedia di:
  - Ruang Kelas 10-1
  - Lab Komputer
  - Perpustakaan
```

## 🧪 Testing

```bash
# Run tests
php artisan test

# Verify case sensitivity (before Linux deployment)
bash verify-case-sensitivity.sh
```

## 🚀 Deployment

### Production Checklist

- [ ] Update `.env` dengan production config
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Generate `APP_KEY`
- [ ] Setup database credentials
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Set proper file permissions
- [ ] Setup SSL certificate
- [ ] Configure backup system

Lihat panduan lengkap: [DEPLOYMENT_LINUX.md](DEPLOYMENT_LINUX.md)

## 🐛 Troubleshooting

### Case Sensitivity Issues (Linux)
```bash
# Verify model files
ls -la app/Models/ | grep -i "listlokasi"
# Should show: ListLokasi.php (not Listlokasi.php)

# Clear cache
php artisan cache:clear
composer dump-autoload
```

### Permission Issues
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Database Connection Error
- Check `.env` database credentials
- Verify database exists: `mysql -u user -p -e "SHOW DATABASES;"`
- Test connection: `php artisan tinker` → `DB::connection()->getPdo();`

## 📄 License

This project is licensed under the MIT License.

## 👥 Contributors

- [RiizzzX](https://github.com/RiizzzX)

## 🙏 Acknowledgments

Built with Laravel, Tailwind CSS, and Alpine.js.
