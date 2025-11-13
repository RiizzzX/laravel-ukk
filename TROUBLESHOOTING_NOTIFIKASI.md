# 🔧 Troubleshooting Notifikasi Dropdown

Dokumen ini berisi daftar error yang mungkin terjadi pada sistem notifikasi dropdown dan solusinya.

---

## ❌ ERROR 1: Badge Notifikasi Muncul Lagi Setelah Refresh

### Gejala:
```
1. User klik bell icon → badge hilang
2. User refresh halaman (F5)
3. Badge muncul lagi dengan angka yang sama
```

### Penyebab:
- Notifikasi tidak benar-benar di-mark as read di database
- Fungsi `markAllNotificationsAsRead()` gagal dipanggil atau error
- CSRF token tidak valid

### Solusi 1: Check Console Browser
```javascript
// Buka Console (F12) → Tab Console
// Setelah klik bell icon, harus muncul:
"All notifications marked as read"

// Jika TIDAK muncul, berarti fungsi mark as read gagal
```

### Solusi 2: Check Database
```sql
-- Buka phpMyAdmin → database ukk_sarpras → tabel notifikasi
-- Check kolom is_read untuk user Anda
SELECT * FROM notifikasi WHERE id_user = 1 ORDER BY created_at DESC;

-- Setelah buka dropdown, semua is_read harus = 1
-- Jika masih 0, berarti mark as read gagal
```

### Solusi 3: Check Route & Controller
```bash
# Test route manual
# Buka Postman atau browser console:
fetch('/notifikasi/mark-read', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({})
})
.then(r => r.json())
.then(d => console.log(d))
```

### Solusi 4: Clear Cache Laravel
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## ❌ ERROR 2: Badge Tidak Muncul Sama Sekali

### Gejala:
```
1. Ada notifikasi baru di database
2. Badge tidak muncul di sidebar
3. Dropdown kosong
```

### Penyebab:
- JavaScript tidak berjalan
- Element ID tidak ditemukan
- Route API error

### Solusi 1: Check Console Error
```javascript
// Buka Console (F12) → Tab Console
// Cari error merah, contoh:
"Uncaught ReferenceError: notificationBadgeSidebar is not defined"
"Failed to fetch"
"404 Not Found"
```

### Solusi 2: Check Element ID
```html
<!-- Buka Inspect Element (F12) → Tab Elements -->
<!-- Search (Ctrl+F) untuk ID berikut: -->
- notificationBellSidebar
- notificationBadgeSidebar
- notificationDropdownSidebar
- notificationListSidebar

<!-- Jika tidak ketemu, berarti HTML belum ter-load -->
```

### Solusi 3: Test API Endpoint
```bash
# Test di browser atau Postman
GET http://localhost/notifikasi/unread

# Response harus JSON:
{
    "notifikasi": [...],
    "totalUnread": 3
}
```

### Solusi 4: Check Blade Template
```bash
# Pastikan file ini ada:
resources/views/partials/sidebar.blade.php
resources/views/layouts/app.blade.php

# Dan sudah di-include di semua halaman
```

---

## ❌ ERROR 3: Dropdown Tidak Muncul Saat Klik Bell Icon

### Gejala:
```
1. Klik bell icon
2. Tidak ada yang terjadi
3. Dropdown tidak expand
```

### Penyebab:
- JavaScript event listener tidak aktif
- Element hidden class tidak di-remove
- Z-index issue

### Solusi 1: Check JavaScript Load
```javascript
// Buka Console → ketik:
typeof notificationBellSidebar

// Harus return: "object"
// Jika "undefined", berarti script belum load
```

### Solusi 2: Manual Toggle
```javascript
// Buka Console → ketik:
document.getElementById('notificationDropdownSidebar').classList.remove('hidden')

// Jika dropdown muncul, berarti CSS OK, masalah di JS event
```

### Solusi 3: Check Event Listener
```javascript
// Tambahkan console.log di event listener:
notificationBellSidebar.addEventListener('click', function(e) {
    console.log('Bell clicked!'); // Harus muncul saat klik
    // ...
});
```

---

## ❌ ERROR 4: Loading Spinner Tidak Hilang

### Gejala:
```
1. Klik bell icon
2. Loading spinner muncul
3. Tidak hilang-hilang (stuck)
```

### Penyebab:
- API endpoint error (500, 404)
- Promise tidak resolve
- Network error

### Solusi 1: Check Network Tab
```javascript
// Buka F12 → Tab Network
// Klik bell icon
// Cari request: "unread"
// Check status code:
- 200 OK ✅
- 500 Internal Server Error ❌
- 404 Not Found ❌
- 419 CSRF Token Mismatch ❌
```

### Solusi 2: Check Laravel Log
```bash
# Buka file log
storage/logs/laravel.log

# Cari error terbaru
# Biasanya ada stack trace
```

### Solusi 3: Test API Manual
```bash
# Buka browser baru
# Login dulu
# Buka URL:
http://localhost/notifikasi/unread

# Harus muncul JSON, bukan error page
```

---

## ❌ ERROR 5: Notifikasi Kosong Padahal Ada di Database

### Gejala:
```
1. Database ada notifikasi (is_read = 0)
2. Dropdown menampilkan "Tidak Ada Notifikasi"
```

### Penyebab:
- Query filter salah (by user)
- is_read sudah berubah jadi 1
- Data tidak ter-serialize dengan benar

### Solusi 1: Check Query Controller
```php
// File: app/Http/Controllers/PengaduanController.php
// Method: getUnreadNotifications()

public function getUnreadNotifications()
{
    $user = Auth::user();
    
    // PASTIKAN filter by user ID
    $notifikasi = \App\Models\Notifikasi::where('id_user', $user->id_user)
        ->where('is_read', false)
        ->latest('created_at')
        ->take(10)
        ->get();
    
    // Debug: uncomment ini
    // dd($notifikasi); // Harus ada data
    
    return response()->json([...]);
}
```

### Solusi 2: Check Database Manual
```sql
-- Login sebagai user dengan id = 1 (contoh)
SELECT * FROM notifikasi 
WHERE id_user = 1 
AND is_read = 0 
ORDER BY created_at DESC;

-- Jika ada data, berarti backend issue
-- Jika tidak ada, berarti memang tidak ada notif unread
```

---

## ❌ ERROR 6: CSRF Token Mismatch (419)

### Gejala:
```
Network Tab menunjukkan:
POST /notifikasi/mark-read
Status: 419 (unknown status)
```

### Penyebab:
- CSRF token tidak valid
- Session expired
- Meta tag CSRF tidak ada

### Solusi 1: Check Meta Tag
```html
<!-- Buka Inspect Element → <head> -->
<!-- Harus ada: -->
<meta name="csrf-token" content="...long-token...">

<!-- Jika tidak ada, tambahkan di layouts/app.blade.php: -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### Solusi 2: Refresh Session
```bash
# Clear session
php artisan session:table
php artisan migrate:refresh

# Atau hapus manual:
# Logout → Clear browser cache → Login lagi
```

### Solusi 3: Update JavaScript
```javascript
// Pastikan CSRF token dikirim:
fetch('...', {
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        // ATAU
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
    }
})
```

---

## ❌ ERROR 7: Badge Tidak Update Real-time

### Gejala:
```
1. Ada notifikasi baru masuk
2. User harus refresh manual untuk lihat badge
```

### Penyebab:
- Auto-refresh interval terlalu lama
- setInterval tidak jalan
- JavaScript error

### Solusi 1: Check Interval
```javascript
// Buka Console → ketik:
setInterval(() => console.log('Checking...'), 5000)

// Harus print "Checking..." setiap 5 detik
// Jika tidak, berarti setInterval tidak jalan
```

### Solusi 2: Kurangi Interval
```javascript
// Di layouts/app.blade.php
// BEFORE:
setInterval(checkUnreadNotifications, 30000); // 30 detik

// AFTER:
setInterval(checkUnreadNotifications, 10000); // 10 detik
```

### Solusi 3: Manual Trigger
```javascript
// Buka Console → ketik:
checkUnreadNotifications()

// Badge harus update
// Jika iya, berarti interval yang masalah
```

---

## ❌ ERROR 8: Dropdown Terpotong / Tidak Terlihat Penuh

### Gejala:
```
1. Dropdown expand ke atas
2. Sebagian terpotong oleh header/footer
3. Scroll tidak muncul
```

### Penyebab:
- Z-index tidak cukup tinggi
- Overflow hidden di parent
- Max-height terlalu kecil

### Solusi 1: Fix Z-index
```css
/* Di sidebar.blade.php atau CSS */
#notificationDropdownSidebar {
    z-index: 9999 !important;
    position: relative;
}
```

### Solusi 2: Fix Max-height
```html
<!-- Ubah max-h-96 jadi lebih kecil -->
<div class="max-h-80 overflow-y-auto">
    <!-- Atau -->
<div class="max-h-64 overflow-y-auto">
```

### Solusi 3: Check Parent Overflow
```css
/* Pastikan parent tidak overflow: hidden */
#sidebar {
    overflow-y: auto; /* Bukan hidden */
}
```

---

## 📋 Checklist Debug Umum

Gunakan checklist ini jika notifikasi tidak berfungsi:

```
✅ 1. Check Console untuk error JavaScript
✅ 2. Check Network Tab untuk API call
✅ 3. Check database apakah ada notifikasi
✅ 4. Test API endpoint manual (Postman/browser)
✅ 5. Check Laravel log (storage/logs/laravel.log)
✅ 6. Check CSRF token di meta tag
✅ 7. Clear cache Laravel (artisan cache:clear)
✅ 8. Hard refresh browser (Ctrl+Shift+R)
✅ 9. Test di browser lain (Chrome/Firefox)
✅ 10. Check permission database & storage
```

---

## 🔍 Tools untuk Debug

### 1. Browser DevTools (F12)
```
- Console: Lihat error JavaScript
- Network: Lihat API request/response
- Elements: Inspect HTML structure
- Application: Check cookies & storage
```

### 2. Laravel Telescope (Optional)
```bash
composer require laravel/telescope
php artisan telescope:install
php artisan migrate

# Akses: http://localhost/telescope
# Lihat semua request, query, exception
```

### 3. Laravel Debugbar (Optional)
```bash
composer require barryvdh/laravel-debugbar --dev

# Muncul toolbar di bawah halaman
# Lihat queries, routes, views, dll
```

---

## 💡 Tips Pencegahan

1. **Selalu check Console** sebelum report bug
2. **Test di incognito mode** untuk rule out cache issue
3. **Backup database** sebelum migrate atau fix
4. **Gunakan Git** untuk track perubahan
5. **Test di berbagai browser** (Chrome, Firefox, Edge)
6. **Dokumentasikan** setiap perubahan yang dilakukan

---

## 📞 Dukungan

Jika masih ada masalah setelah mencoba semua solusi di atas:

1. Screenshot error message
2. Copy full error dari Console
3. Copy Laravel log jika ada
4. Jelaskan langkah reproduksi error
5. Sebutkan browser & versi yang digunakan

---

**Terakhir diupdate:** {{ date('Y-m-d') }}
**Versi:** 1.0
