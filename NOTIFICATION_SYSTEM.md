# Sistem Notifikasi Pengaduan - Dokumentasi

## Ringkasan Fitur

Sistem notifikasi real-time telah diimplementasikan untuk semua tiga role pengguna: `pengguna`, `petugas`, dan `admin`.

---

## 1. Notifikasi untuk PENGGUNA (User)

### Flow Notifikasi Pengaduan:
```
User membuat pengaduan 
    ↓
Admin menerima notifikasi "🔔 Pengaduan Baru Masuk"
    ↓
Admin menerima/menolak pengaduan
    ↓
User menerima notifikasi "✅ Pengaduan Diterima" atau "❌ Pengaduan Ditolak"
    ↓
Petugas mengambil pengaduan (ubah status ke "Diproses")
    ↓
User menerima notifikasi "🔧 Pengaduan Sedang Diproses" + nama petugas
    ↓
Petugas menyelesaikan pengaduan (ubah status ke "Selesai")
    ↓
User menerima notifikasi "✅ Pengaduan Selesai" + catatan petugas
```

### Notifikasi yang diterima:
- **Pending → Diterima**: `✅ Pengaduan Diterima`
- **Pending → Ditolak**: `❌ Pengaduan Ditolak`
- **Diterima → Diproses**: `🔧 Pengaduan Sedang Diproses` (dari Petugas)
- **Diproses → Selesai**: `✅ Pengaduan Selesai` (dari Petugas)

### Badge/Indicator:
- Badge menampilkan jumlah notifikasi yang **belum dibaca**
- Badge hilang setelah user membuka dropdown notifikasi untuk pertama kalinya
- Badge reappear setelah page refresh jika ada notifikasi baru yang belum dibaca

---

## 2. Notifikasi untuk ADMIN

### Notifikasi yang diterima:
1. **📋 Pengaduan Baru Masuk** - Ketika user membuat pengaduan baru
   - Informasi: Nama user, preview deskripsi
   - Link: `/admin/pengaduan`

2. **📝 Pengajuan Item/Lokasi Baru** - Ketika user mengajukan item/lokasi baru
   - Informasi: Nama user, tipe pengajuan
   - Link: `/admin/temporary-items`

3. **👤 Petugas Mengambil Pengaduan** - Ketika petugas mengambil (claim) pengaduan
   - Informasi: Nama petugas, judul pengaduan
   - Link: `/admin/pengaduan/riwayat`

4. **✅ Pengaduan Selesai** - Ketika petugas menyelesaikan pengaduan
   - Informasi: Nama petugas, judul pengaduan
   - Link: `/admin/pengaduan/riwayat`

### Manfaat untuk Admin:
- Monitor semua pengaduan masuk dalam real-time
- Tahu siapa saja petugas yang telah mengambil pengaduan
- Tracking pengaduan yang sudah selesai
- Manajemen temporary item dari pengguna

---

## 3. Notifikasi untuk PETUGAS

### Notifikasi yang diterima:
1. **📋 Pengaduan Tersedia** - Ketika admin menerima pengaduan baru (status = "diterima")
   - Informasi: Judul pengaduan, nama user yang membuat
   - Link: `/petugas/pengaduan`

### Manfaat untuk Petugas:
- Tahu ada pengaduan baru yang tersedia untuk diambil
- Dapat melihat siapa yang membuat pengaduan
- Real-time notification system memastikan petugas tidak ketinggalan pekerjaan

---

## Implementasi Teknis

### File-File yang Dimodifikasi:

1. **app/Http/Controllers/PengaduanController.php**
   - Mengirim notifikasi ke admin saat pengaduan baru dibuat
   - Mengirim notifikasi ke admin saat temporary item diajukan

2. **app/Http/Controllers/AdminController.php**
   - Mengirim notifikasi ke user saat pengaduan diterima/ditolak
   - Mengirim notifikasi ke semua petugas saat pengaduan diterima (status="diterima")

3. **app/Http/Controllers/PetugasController.php**
   - Mengirim notifikasi ke user saat pengaduan diproses (status="diproses")
   - Mengirim notifikasi ke user saat pengaduan selesai (status="selesai")
   - Mengirim notifikasi ke admin saat petugas mengambil pengaduan
   - Mengirim notifikasi ke admin saat pengaduan selesai

4. **app/Models/Notifikasi.php**
   - Model notifikasi dengan method helper `createNotification()`, `notifyAllAdmins()`, `notifyAllPetugas()`

5. **resources/views/layouts/app.blade.php**
   - JavaScript polling untuk memuat notifikasi setiap 5 detik (max 30 detik)
   - Auto-mark notifikasi saat dropdown dibuka
   - One-time badge reminder

6. **resources/views/partials/sidebar.blade.php**
   - Sidebar bersih tanpa duplicate HTML
   - Notification bell untuk semua role

### Database:
- Tabel `notifikasi` dengan field:
  - `id_notifikasi` (PK)
  - `id_user` (FK ke users)
  - `tipe` (pengaduan_baru, temporary-item, petugas_claim, pengaduan_selesai, status_update, etc)
  - `judul` (Judul notifikasi)
  - `isi` (Isi/deskripsi notifikasi)
  - `link` (URL link untuk notifikasi)
  - `ref_id` (Reference ID ke table lain, misal id_pengaduan)
  - `is_read` (Boolean: sudah dibaca atau belum)
  - `created_at`, `updated_at`

---

## Flow Diagram Lengkap

```
┌─────────────────────────────────────────────────────────────────┐
│                    SISTEM NOTIFIKASI PENGADUAN                  │
└─────────────────────────────────────────────────────────────────┘

USER (Pengguna)
├─ Buat Pengaduan
│  ├─ Admin: 🔔 Pengaduan Baru Masuk
│  └─ Petugas: 📋 Pengaduan Tersedia (saat admin terima)
│
├─ Status Diterima
│  └─ User: ✅ Pengaduan Diterima
│
├─ Status Ditolak
│  └─ User: ❌ Pengaduan Ditolak
│
└─ Petugas Ambil → Selesai
   ├─ User: 🔧 Pengaduan Sedang Diproses
   ├─ Admin: 👤 Petugas Mengambil Pengaduan
   ├─ User: ✅ Pengaduan Selesai
   └─ Admin: ✅ Pengaduan Selesai

ADMIN (Administrator)
├─ Terima Notifikasi Baru:
│  ├─ 🔔 Pengaduan Baru Masuk (dari user)
│  ├─ 📝 Pengajuan Item/Lokasi Baru (dari user)
│  ├─ 👤 Petugas Mengambil Pengaduan (dari petugas)
│  └─ ✅ Pengaduan Selesai (dari petugas)
│
└─ Aksi:
   ├─ Review pengaduan
   ├─ Terima/Tolak pengaduan
   ├─ Monitor progress petugas
   └─ Review temporary items

PETUGAS (Staff)
├─ Terima Notifikasi:
│  └─ 📋 Pengaduan Tersedia (dari admin saat terima pengaduan)
│
├─ Ambil Pengaduan (status: diterima → diproses)
│  ├─ User: 🔧 Pengaduan Sedang Diproses
│  └─ Admin: 👤 Petugas Mengambil Pengaduan
│
└─ Selesaikan Pengaduan (status: diproses → selesai)
   ├─ User: ✅ Pengaduan Selesai
   └─ Admin: ✅ Pengaduan Selesai
```

---

## Testing Checklist

- [ ] **User**: Buat pengaduan → cek notifikasi ke admin
- [ ] **User**: Tunggu admin terima → cek notifikasi "Pengaduan Diterima"
- [ ] **User**: Tunggu petugas ambil → cek notifikasi "Pengaduan Sedang Diproses"
- [ ] **User**: Tunggu petugas selesai → cek notifikasi "Pengaduan Selesai"
- [ ] **Admin**: Buka admin.pengaduan → lihat notifikasi baru
- [ ] **Admin**: Terima pengaduan → cek notifikasi ke petugas
- [ ] **Petugas**: Login → lihat notifikasi pengaduan tersedia
- [ ] **Petugas**: Ambil pengaduan → cek notifikasi ke user & admin
- [ ] **Petugas**: Selesaikan pengaduan → cek notifikasi ke user & admin
- [ ] **Badge**: Muncul saat ada notifikasi baru
- [ ] **Badge**: Hilang setelah dropdown dibuka
- [ ] **Badge**: Reappear saat page refresh jika ada notifikasi baru

---

## Future Improvements

1. **Email Notifications**: Kirim email summary ke admin saat ada pengaduan masuk
2. **SMS/Push**: Implementasi push notification untuk mobile app
3. **Notification Preferences**: User dapat customize jenis notifikasi yang diterima
4. **Notification History**: Simpan history notifikasi, bukan hanya unread
5. **Real-time WebSocket**: Upgrade dari polling ke WebSocket untuk instant updates
6. **Notification Channels**: Multi-channel support (email, SMS, in-app, etc)

---

**Status**: ✅ Implementasi Selesai | 📅 Tanggal: November 12, 2025
