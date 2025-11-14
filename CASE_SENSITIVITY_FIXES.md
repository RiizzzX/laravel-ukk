# Case Sensitivity Fixes - Summary

Project ini telah diperbaiki untuk **case-sensitive filesystem** (Linux deployment).

## ✅ Files yang Sudah Diperbaiki

### 1. Model Files
- ✅ `app/Models/Listlokasi.php` → **RENAMED** → `app/Models/ListLokasi.php`
- ✅ Class name: `ListLokasi` (dengan capital L di awal dan tengah)
- ✅ All imports menggunakan: `use App\Models\ListLokasi;`

### 2. View Files
- ✅ Semua view files menggunakan **lowercase** yang konsisten
- ✅ Folder structure: `admin/`, `petugas/`, `pengaduan/`, `auth/`
- ✅ No mixed case in filenames

### 3. Controller References
- ✅ AdminController - menggunakan `Item::with('listLokasi.lokasi')`
- ✅ ItemController - menggunakan `Item::with('listLokasi.lokasi')`
- ✅ PengaduanController - menggunakan `Item::with('listLokasi.lokasi')`
- ✅ Api\ItemController - references sudah benar

### 4. Seeders
- ✅ ItemListLokasiSeeder - import `use App\Models\ListLokasi;`
- ✅ ItemLokasiSeederComplete - import sudah benar
- ✅ All seeder references consistent

## 🔍 Verification Commands

### Check Model File Name (Linux)
```bash
ls -la app/Models/ | grep -i "listlokasi"
# Expected: ListLokasi.php
```

### Check Imports
```bash
grep -r "use App\\\\Models\\\\Listlokasi" app/ database/ --include="*.php"
# Expected: No results (all should be ListLokasi)
```

### Test Autoloader
```bash
composer dump-autoload
php artisan tinker
>>> App\Models\ListLokasi::count();
# Should work without errors
```

## 📝 Important Notes

### Nama File vs Class Name
**HARUS SAMA PERSIS** di Linux:
```php
// File: app/Models/ListLokasi.php
class ListLokasi extends Model { }  // ✓ Correct

// File: app/Models/Listlokasi.php
class ListLokasi extends Model { }  // ✗ Error di Linux!
```

### Import Statements
```php
use App\Models\ListLokasi;  // ✓ Correct
use App\Models\Listlokasi;  // ✗ Error di Linux!
use App\Models\listLokasi;  // ✗ Error di Linux!
```

### Route Names
Tetap gunakan **lowercase** untuk konsistensi:
```php
Route::get('/admin/items', [AdminController::class, 'listItems'])
    ->name('admin.items.index');  // ✓ lowercase
```

## 🚀 Pre-Deployment Checklist

- [x] Rename `Listlokasi.php` to `ListLokasi.php`
- [x] Update all import statements
- [x] Verify controller references
- [x] Test autoloader
- [x] Create verification script
- [x] Update documentation
- [x] Test on Linux (if available)

## 🛠️ Testing Before Deploy

### Run Verification Script
```bash
bash verify-case-sensitivity.sh
```

### Manual Testing
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Regenerate autoload
composer dump-autoload

# Test in tinker
php artisan tinker
>>> App\Models\ListLokasi::first();
>>> App\Models\Item::with('listLokasi')->first();
```

## 📦 Deploy to Linux

```bash
# Method 1: Using deploy script
bash deploy.sh

# Method 2: Manual steps
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## ⚠️ Common Issues & Solutions

### Issue: "Class 'App\Models\Listlokasi' not found"
**Cause:** File name tidak match dengan class name (case-sensitive)  
**Solution:**
```bash
cd app/Models
mv Listlokasi.php ListLokasi.php
composer dump-autoload
php artisan cache:clear
```

### Issue: "Target class [Listlokasi] does not exist"
**Cause:** Import statement menggunakan wrong case  
**Solution:** Update import di file yang error:
```php
use App\Models\ListLokasi;  // Fix the import
```

### Issue: Relationship not working
**Cause:** Method name vs model name mismatch  
**Solution:** Verify relationship methods:
```php
// In Item model
public function listLokasi() {
    return $this->hasMany(ListLokasi::class, 'id_item', 'id_item');
}
```

## 📚 Reference Files

- **Deployment Guide:** `DEPLOYMENT_LINUX.md`
- **API Documentation:** `API_DOCUMENTATION.md`
- **Verification Script:** `verify-case-sensitivity.sh`
- **Deploy Script:** `deploy.sh`
- **README:** `README.md`

## ✅ Status

**PROJECT IS READY FOR LINUX DEPLOYMENT** ✓

All case sensitivity issues have been resolved. The project can now be safely deployed on Linux servers with case-sensitive filesystems.

Last updated: November 14, 2025
