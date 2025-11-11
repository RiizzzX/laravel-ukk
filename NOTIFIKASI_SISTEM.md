# 📱 Sistem Notifikasi Laporan/Pengaduan

## 🎯 Cara Kerja Sistem Notifikasi

Sistem notifikasi telah diimplementasikan untuk memberitahu **user (pelapor)** ketika status pengaduan mereka berubah.

---

## 🔄 Alur Notifikasi

### 1. **User Membuat Pengaduan Baru**
```
Status: PENDING
is_read: false (default)
```
- User membuat laporan kehilangan barang
- Status awal: `pending`
- Kolom `is_read` otomatis `false`

---

### 2. **Admin Menerima/Menolak Pengaduan**
```
Status: PENDING → DITERIMA/DITOLAK
is_read: false ✅ (notifikasi aktif)
```
- Admin review pengaduan
- Ubah status menjadi `diterima` atau `ditolak`
- Sistem **otomatis set `is_read = false`**
- User mendapat **notifikasi baru** di dashboard

---

### 3. **Petugas Mengambil Pengaduan (Claim)**
```
Status: DITERIMA → DIPROSES
is_read: false ✅ (notifikasi aktif)
```
- Petugas claim pengaduan
- Status berubah ke `diproses`
- `id_petugas` diisi dengan petugas yang mengambil
- User mendapat **notifikasi bahwa pengaduan sedang dikerjakan**

---

### 4. **Petugas Menyelesaikan Pengaduan**
```
Status: DIPROSES → SELESAI
is_read: false ✅ (notifikasi aktif)
```
- Petugas selesaikan pengaduan + upload foto bukti
- Status berubah ke `selesai`
- User mendapat **notifikasi pengaduan selesai**

---

### 5. **User Membuka Dashboard**
```
is_read: false → true ✅ (notifikasi dibaca)
```
- User login dan buka dashboard
- Melihat notifikasi status pengaduan
- Sistem **otomatis mark `is_read = true`**
- Badge notifikasi hilang

---

## 💻 Implementasi Teknis

### **Database**
```sql
ALTER TABLE pengaduan ADD COLUMN is_read BOOLEAN DEFAULT FALSE;
```

### **Model: Pengaduan.php**
```php
protected $fillable = [
    // ... existing fields
    'is_read', // Kolom notifikasi
];
```

### **Controller: PengaduanController.php**

#### Dashboard dengan Notifikasi
```php
public function dashboard()
{
    $user = Auth::user();

    // Hitung jumlah notifikasi belum dibaca
    $notifikasiCount = Pengaduan::where('id_user', $user->id_user)
        ->whereIn('status', ['diterima', 'diproses', 'selesai', 'ditolak'])
        ->where('is_read', false)
        ->count();

    // Ambil detail notifikasi baru (max 5)
    $notifikasiBaru = Pengaduan::with(['item', 'lokasiRelation', 'petugas'])
        ->where('id_user', $user->id_user)
        ->whereIn('status', ['diterima', 'diproses', 'selesai', 'ditolak'])
        ->where('is_read', false)
        ->latest('updated_at')
        ->take(5)
        ->get();

    return view('dashboard', [
        // ... existing data
        'notifikasiCount' => $notifikasiCount,  // Badge count
        'notifikasiBaru'  => $notifikasiBaru,   // Detail notifikasi
    ]);
}
```

#### Mark Notifikasi as Read
```php
public function markNotificationRead()
{
    $user = Auth::user();
    
    Pengaduan::where('id_user', $user->id_user)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json(['success' => true]);
}
```

### **Admin/Petugas Controller**

Setiap kali status berubah, reset `is_read`:

```php
// AdminController - Update Status
$pengaduan->status = $request->status;
$pengaduan->is_read = false; // Reset notifikasi
$pengaduan->save();

// PetugasController - Update Status
$pengaduan->status = $request->status;
$pengaduan->is_read = false; // Reset notifikasi
$pengaduan->save();
```

---

## 🎨 Tampilan di Frontend

### **1. Badge Notifikasi di Navbar**
```blade
@if(isset($notifikasiCount) && $notifikasiCount > 0)
  <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs 
               rounded-full px-2 py-0.5 font-bold">
    {{ $notifikasiCount }}
  </span>
@endif
```

### **2. Card Notifikasi di Dashboard**
```blade
@if(isset($notifikasiBaru) && $notifikasiBaru->count() > 0)
  <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
    <h3 class="font-bold">🔔 Notifikasi Baru</h3>
    @foreach($notifikasiBaru as $notif)
      <div class="p-3 bg-white rounded mt-2">
        <p><strong>{{ $notif->item->nama_item }}</strong></p>
        <p>Status: <span class="badge">{{ $notif->status }}</span></p>
        <p class="text-xs text-gray-500">
          {{ $notif->updated_at->diffForHumans() }}
        </p>
      </div>
    @endforeach
  </div>
@endif
```

### **3. Alert Toast Notification**
```blade
@if(isset($notifikasiBaru) && $notifikasiBaru->count() > 0)
  <div id="toast" class="fixed top-4 right-4 bg-green-500 text-white 
       px-6 py-3 rounded-lg shadow-lg z-50">
    ✅ Ada {{ $notifikasiCount }} update pengaduan!
  </div>
@endif
```

---

## 🔗 Routes

```php
// User routes
Route::middleware(['auth', 'role:pengguna'])->group(function () {
    // Dashboard dengan notifikasi
    Route::get('/user/dashboard', [PengaduanController::class, 'dashboard'])
        ->name('user.dashboard');
    
    // Mark notifikasi as read (AJAX)
    Route::post('/notifikasi/mark-read', [PengaduanController::class, 'markNotificationRead'])
        ->name('notifikasi.markRead');
});
```

---

## ⚡ JavaScript untuk Auto Mark Read

```javascript
// Auto mark notifikasi as read saat user buka dashboard
document.addEventListener('DOMContentLoaded', function() {
    const notifCount = {{ $notifikasiCount ?? 0 }};
    
    if (notifCount > 0) {
        // Tunggu 3 detik, lalu mark as read
        setTimeout(() => {
            fetch("{{ route('notifikasi.markRead') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hapus badge notifikasi
                    document.querySelector('.notification-badge')?.remove();
                    
                    // Fade out notifikasi card
                    document.querySelector('.notification-card')?.classList.add('fade-out');
                }
            });
        }, 3000); // 3 detik
    }
});
```

---

## 📊 Status Flow & Notifikasi

```
USER          ADMIN         PETUGAS       NOTIFIKASI USER
┌─────────┐   ┌─────────┐   ┌─────────┐   ┌──────────────┐
│ PENDING │──▶│ DITERIMA│──▶│ DIPROSES│──▶│   SELESAI    │
└─────────┘   └─────────┘   └─────────┘   └──────────────┘
   🔔            🔔            🔔              🔔
  false        false         false          false
                                          (is_read = false)

USER BUKA DASHBOARD → is_read = true → Badge hilang ✅
```

---

## ✅ Keuntungan Sistem Ini

1. **Real-time Update**: User langsung tahu status pengaduan mereka
2. **Badge Count**: Visual indicator jumlah notifikasi belum dibaca
3. **Detail Notifikasi**: Tampilkan info lengkap (item, status, waktu)
4. **Auto Mark Read**: Otomatis tandai sudah dibaca setelah user lihat
5. **Non-Intrusive**: Tidak mengganggu, user bisa baca kapan saja
6. **Database Efficient**: Hanya 1 kolom boolean tambahan

---

## 🚀 Cara Testing

1. **Login sebagai User** → Buat pengaduan baru
2. **Login sebagai Admin** → Terima pengaduan
3. **Login sebagai User lagi** → Lihat badge notifikasi di navbar ✅
4. **Klik Dashboard** → Lihat card notifikasi detail ✅
5. **Tunggu 3 detik** → Badge hilang otomatis (marked as read) ✅

---

## 📝 Next Steps (Optional)

### **Level 1: Basic** ✅ (DONE)
- [x] Badge count notifikasi
- [x] Auto mark as read
- [x] Reset is_read saat status berubah

### **Level 2: Advanced**
- [ ] Notifikasi sidebar dropdown (klik badge → dropdown list)
- [ ] Button "Mark all as read"
- [ ] Filter notifikasi by status (diterima, diproses, selesai)

### **Level 3: Pro**
- [ ] Real-time notification (Pusher/Laravel Echo)
- [ ] Sound notification
- [ ] Email notification
- [ ] Push notification (PWA)
- [ ] Notification history page

---

## 🎯 Kesimpulan

Sistem notifikasi sudah **siap digunakan**! User akan otomatis menerima notifikasi setiap kali:
- ✅ Admin terima/tolak pengaduan
- ✅ Petugas ambil pengaduan
- ✅ Petugas selesaikan pengaduan

Badge dan card notifikasi akan muncul di dashboard, dan otomatis hilang setelah user membuka dashboard (marked as read).

---

**Dibuat:** 11 November 2025  
**Framework:** Laravel 10.x  
**Database:** MySQL  
**Frontend:** Blade + TailwindCSS
