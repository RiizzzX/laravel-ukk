# Alur Sistem Pengaduan - Normal vs Temporary Item

## 📋 Overview
Sistem pengaduan sekarang dibedakan menjadi 2 tipe:
1. **Pengaduan Normal** - Langsung masuk ke tabel `pengaduan`
2. **Pengaduan Temporary Item** - Masuk ke tabel `temporary_item`, perlu approval admin

---

## 🔄 Alur Pengaduan Normal

### 1. User Submit Pengaduan Normal
- User memilih tipe: **Normal**
- User memilih item dan lokasi yang sudah ada
- Upload foto (wajib)
- Isi deskripsi

### 2. Data Disimpan
- Masuk ke tabel `pengaduan`
- Status: `pending`
- Field `tipe_pengaduan`: `normal`

### 3. Admin Review
- Admin melihat di halaman **Pengaduan Pending**
- Admin bisa **Terima** atau **Tolak**

### 4. Jika Diterima
- Status berubah: `diterima`
- Tersedia untuk semua petugas

### 5. Jika Ditolak
- Status berubah: `ditolak`
- Masuk ke riwayat

---

## 🆕 Alur Pengaduan Temporary Item

### 1. User Submit Temporary Item
- User memilih tipe: **Temporary**
- User centang: Item Baru / Lokasi Baru (atau keduanya)
- Isi nama item baru / lokasi baru
- Upload foto (opsional)
- Isi deskripsi

### 2. Data Disimpan
- **LANGSUNG** masuk ke tabel `temporary_item`
- Status: `pending`
- Field yang disimpan:
  - `id_user`
  - `nama_barang_baru`
  - `lokasi_barang_baru`
  - `deskripsi`
  - `foto`
  - `status` = 'pending'

### 3. Admin Review Temporary Item
- Admin melihat di halaman **Temporary Items**
- Admin bisa **Approve** atau **Reject**

### 4. Jika APPROVED ✅
Admin mengklik tombol **Approve**, maka sistem akan:

1. **Buat Item Baru** (jika `nama_barang_baru` ada)
   - Insert ke tabel `items`
   - Simpan `id_item` ke `temporary_item.id_item`

2. **Buat Lokasi Baru** (jika `lokasi_barang_baru` ada)
   - Insert ke tabel `lokasi`

3. **Link Item-Lokasi** (jika keduanya ada)
   - Insert ke tabel `list_lokasi` (item_lokasi)
   - Format: `(id_item, id_lokasi)`

4. **Update Status Temporary Item**
   - `temporary_item.status` = 'approved'

5. **Buat Entry Pengaduan di Riwayat**
   - Insert ke tabel `pengaduan`
   - Status: `selesai`
   - `tipe_pengaduan`: 'temporary'
   - `temporary_item_id`: link ke temporary_item
   - Langsung masuk **RIWAYAT**

6. **Kirim Notifikasi ke User**
   - "✅ Item/Lokasi Baru Disetujui"
   - "Item X dan Lokasi Y telah ditambahkan"

### 5. Jika REJECTED ❌
Admin mengklik tombol **Reject**, maka sistem akan:

1. **Update Status Temporary Item**
   - `temporary_item.status` = 'rejected'
   - `temporary_item.alasan_penolakan` = (isi dari admin)

2. **Tetap di Tabel Temporary**
   - Data **TIDAK DIHAPUS**
   - Tetap ada di `temporary_item` dengan status 'rejected'

3. **Kirim Notifikasi ke User**
   - "❌ Item/Lokasi Ditolak"
   - "Alasan: [alasan dari admin]"

---

## 📊 Tabel Database

### Tabel: `pengaduan`
```sql
- id_pengaduan (PK)
- id_user (FK)
- id_item (FK) - NULL jika tipe = temporary
- lokasi (FK ke id_lokasi) - NULL jika tipe = temporary
- deskripsi
- foto
- status: pending, diterima, ditolak, diproses, selesai
- tipe_pengaduan: 'normal', 'temporary' (NEW)
- temporary_item_id (FK) - Link ke temporary_item
- timestamps
```

### Tabel: `temporary_item`
```sql
- id_temporary (PK)
- id_user (FK) - NEW: Langsung link ke user
- id_item (FK) - NULL, diisi setelah approved
- id_pengaduan (FK) - DEPRECATED (tidak dipakai lagi)
- nama_barang_baru
- lokasi_barang_baru
- deskripsi (NEW) - Deskripsi pengaduan
- foto (NEW) - Foto bukti
- status: 'pending', 'approved', 'rejected' (NEW)
- alasan_penolakan
- timestamps
```

### Tabel: `list_lokasi` (item_lokasi)
```sql
- id (PK)
- id_item (FK)
- id_lokasi (FK)
- timestamps
```

---

## 🎯 Status Flow

### Pengaduan Normal
```
USER SUBMIT → pending → (ADMIN REVIEW) → diterima/ditolak
                                        ↓
                                   (PETUGAS) → diproses → selesai
```

### Temporary Item
```
USER SUBMIT → temporary_item (status: pending)
              ↓
       (ADMIN REVIEW)
       ↓            ↓
   APPROVE      REJECT
       ↓            ↓
   1. Buat item/lokasi    temporary_item.status = 'rejected'
   2. Link di item_lokasi  (tetap di tabel)
   3. temporary_item.status = 'approved'
   4. Buat pengaduan (status: selesai)
   5. Masuk RIWAYAT
```

---

## 🔑 Key Points

1. **Temporary item TIDAK masuk tabel pengaduan** saat submit
2. **Hanya setelah APPROVED** baru masuk ke tabel pengaduan (langsung selesai)
3. **Jika REJECTED**, tetap di tabel temporary_item dengan status 'rejected'
4. **Item dan Lokasi baru** otomatis dibuat saat approve
5. **Link item-lokasi** otomatis dibuat di tabel `list_lokasi`

---

## 📝 Perubahan File

### Migration
- `2025_11_13_010742_add_tipe_pengaduan_to_pengaduan_table.php`
- `2025_11_13_010911_add_fields_to_temporary_item_table.php`

### Models
- `app/Models/Pengaduan.php` - Tambah field `tipe_pengaduan`
- `app/Models/TemporaryItem.php` - Tambah field `id_user`, `deskripsi`, `foto`, `status`

### Controllers
- `app/Http/Controllers/PengaduanController.php` - Pisahkan logika store
- `app/Http/Controllers/AdminController.php` - Update approve/reject logic

---

## ✅ Testing Checklist

- [ ] User bisa submit pengaduan normal
- [ ] User bisa submit temporary item (item baru)
- [ ] User bisa submit temporary item (lokasi baru)
- [ ] User bisa submit temporary item (item + lokasi baru)
- [ ] Admin bisa approve temporary item
- [ ] Admin bisa reject temporary item
- [ ] Item baru masuk ke tabel items
- [ ] Lokasi baru masuk ke tabel lokasi
- [ ] Link item-lokasi masuk ke list_lokasi
- [ ] Pengaduan temporary approved masuk riwayat
- [ ] Temporary rejected tetap di tabel temporary
- [ ] Notifikasi terkirim ke user

---

**Dibuat:** 13 November 2025
**Status:** ✅ Implemented
