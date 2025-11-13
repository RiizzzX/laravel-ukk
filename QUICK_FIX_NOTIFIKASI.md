# 🚀 Quick Fix Notifikasi - Cheat Sheet

## 1️⃣ Badge Muncul Terus Setelah Refresh

```javascript
// Console (F12) → Ketik:
fetch('/notifikasi/mark-read', {method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json'}, body: '{}'}).then(r=>r.json()).then(d=>console.log(d))

// Refresh halaman → Badge harus hilang
```

**Atau check database:**
```sql
UPDATE notifikasi SET is_read = 1 WHERE id_user = YOUR_USER_ID;
```

---

## 2️⃣ Badge Tidak Muncul

```javascript
// Console → Ketik:
fetch('/notifikasi/unread').then(r=>r.json()).then(d=>console.log(d))

// Harus return: {notifikasi: [...], totalUnread: N}
```

---

## 3️⃣ Dropdown Tidak Expand

```javascript
// Console → Ketik:
document.getElementById('notificationDropdownSidebar').classList.remove('hidden')

// Jika muncul = CSS OK, masalah di JS
// Jika tidak = Check HTML structure
```

---

## 4️⃣ Loading Stuck

**Check Network Tab (F12):**
- Status: 200 ✅ OK
- Status: 500 ❌ Check `storage/logs/laravel.log`
- Status: 419 ❌ CSRF issue, refresh session

---

## 5️⃣ Error 419 CSRF

```html
<!-- Check ada di <head>: -->
<meta name="csrf-token" content="...">

<!-- Jika tidak ada, tambahkan -->
```

---

## 6️⃣ Clear Cache Laravel

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 7️⃣ Force Update Badge

```javascript
// Console → Ketik:
document.getElementById('notificationBadgeSidebar').textContent = '0'
document.getElementById('notificationBadgeSidebar').classList.add('hidden')
```

---

## 8️⃣ Test Mark as Read Manual

```sql
-- phpMyAdmin → Run query:
SELECT * FROM notifikasi WHERE id_user = 1 AND is_read = 0;

-- Jika ada hasil, mark manual:
UPDATE notifikasi SET is_read = 1 WHERE id_user = 1;
```

---

## 9️⃣ Debug Mode

```javascript
// Tambahkan di awal function loadNotifications():
console.log('Loading notifications...');

// Tambahkan di markAllNotificationsAsRead():
console.log('Marking all as read...');

// Buka Console → Harus muncul log saat klik bell
```

---

## 🔟 Nuclear Option (Reset Semua)

```bash
# 1. Clear cache
php artisan cache:clear

# 2. Clear browser cache (Ctrl+Shift+Del)

# 3. Hard refresh (Ctrl+Shift+R)

# 4. Mark all as read di database
UPDATE notifikasi SET is_read = 1;

# 5. Restart server
php artisan serve
```

---

## 📱 One-Liner Fixes

```javascript
// Fix badge tidak update:
checkUnreadNotifications()

// Fix dropdown stuck:
document.getElementById('notificationDropdownSidebar').classList.add('hidden')

// Fix loading stuck:
document.getElementById('loadingNotificationsSidebar').classList.add('hidden')

// Force badge hilang:
document.getElementById('notificationBadgeSidebar').classList.add('hidden')
```

---

## 🎯 Instant Debug

Buka Console (F12) → Paste kode ini:

```javascript
// Debug notifikasi lengkap
console.log('=== NOTIFIKASI DEBUG ===');
console.log('Bell element:', document.getElementById('notificationBellSidebar'));
console.log('Badge element:', document.getElementById('notificationBadgeSidebar'));
console.log('Dropdown element:', document.getElementById('notificationDropdownSidebar'));
fetch('/notifikasi/unread')
  .then(r => r.json())
  .then(d => console.log('API Response:', d))
  .catch(e => console.error('API Error:', e));
console.log('=== END DEBUG ===');
```

---

## ⚡ Troubleshoot dalam 30 Detik

1. **F12** → Console → Ada error? ❌
2. **F12** → Network → Status 200? ✅
3. **Database** → Ada notif is_read=0? ✅
4. **Console** → `checkUnreadNotifications()` → Badge muncul? ✅

**Jika semua ✅ tapi masih error:**
- Clear cache Laravel & browser
- Hard refresh (Ctrl+Shift+R)
- Test di incognito mode

---

**Lihat troubleshooting lengkap:** `TROUBLESHOOTING_NOTIFIKASI.md`
