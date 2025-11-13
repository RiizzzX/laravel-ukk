# 📱 API Documentation untuk Flutter Mobile App

Base URL: `http://localhost/api/` atau `http://your-domain.com/api/`

## 🔐 Authentication

Semua endpoint yang memerlukan autentikasi menggunakan **Bearer Token** (Laravel Sanctum).

Header yang diperlukan:
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json (atau multipart/form-data untuk upload file)
```

---

## 📋 Daftar Endpoint

### 1. Authentication

#### **POST** `/register`
Registrasi user baru

**Request Body:**
```json
{
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "message": "Register berhasil",
  "user": {
    "id_user": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "name": "john@example.com",
    "role": "pengguna"
  },
  "token": "1|abc123def456..."
}
```

---

#### **POST** `/login`
Login user (support username atau email)

**Request Body:**
```json
{
  "login": "johndoe",  // bisa username atau email
  "password": "password123"
}
```
atau
```json
{
  "username": "johndoe",
  "password": "password123"
}
```
atau
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "message": "Login berhasil",
  "user": {
    "id_user": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "name": "john@example.com",
    "role": "pengguna"
  },
  "token": "2|xyz789abc456..."
}
```

---

#### **POST** `/auth/logout` 🔒
Logout dan hapus token

**Response (200):**
```json
{
  "message": "Logout berhasil"
}
```

---

#### **GET** `/auth/me` 🔒
Get data user yang sedang login

**Response (200):**
```json
{
  "id_user": 1,
  "username": "johndoe",
  "email": "john@example.com",
  "name": "john@example.com",
  "role": "pengguna"
}
```

---

#### **GET** `/profile` 🔒
Get profil lengkap user

**Response (200):**
```json
{
  "message": "Profile berhasil dimuat",
  "data": {
    "id_user": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "name": "john@example.com",
    "role": "pengguna"
  }
}
```

---

#### **PUT** `/auth/profile` 🔒
Update profil user

**Request Body:**
```json
{
  "username": "johndoe_new",
  "email": "newemail@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

---

#### **PUT** `/profile/update-password` 🔒
Update password saja

**Request Body:**
```json
{
  "old_password": "password123",
  "new_password": "newpassword456",
  "new_password_confirmation": "newpassword456"
}
```

---

### 2. Master Data (Public)

#### **GET** `/lokasi`
Get semua lokasi

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id_lokasi": 1,
      "nama_lokasi": "Ruang Kelas 1A",
      "created_at": "2025-11-13T10:00:00.000000Z"
    }
  ]
}
```

---

#### **GET** `/lokasi/{id}`
Get detail lokasi

---

#### **GET** `/items`
Get semua items

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id_item": 1,
      "nama_item": "Kursi",
      "created_at": "2025-11-13T10:00:00.000000Z"
    }
  ]
}
```

---

#### **GET** `/items/by-lokasi/{id_lokasi}`
Get items berdasarkan lokasi

---

### 3. Pengaduan Normal 🔒

#### **GET** `/pengaduan`
List semua pengaduan normal user

**Response (200):**
```json
{
  "success": true,
  "message": "Data pengaduan normal berhasil diambil",
  "data": [
    {
      "id_pengaduan": 1,
      "id_user": 1,
      "id_item": 2,
      "lokasi": 1,
      "deskripsi": "Kursi rusak",
      "foto": "123456_foto.jpg",
      "status": "pending",
      "tipe_pengaduan": "normal",
      "created_at": "2025-11-13T10:00:00.000000Z",
      "item": {
        "id_item": 2,
        "nama_item": "Kursi"
      },
      "lokasi_relation": {
        "id_lokasi": 1,
        "nama_lokasi": "Ruang Kelas 1A"
      }
    }
  ]
}
```

---

#### **POST** `/pengaduan`
Buat pengaduan normal baru

**Request (multipart/form-data):**
```
id_item: 2
lokasi: 1
deskripsi: "Kursi rusak di pojok kiri"
foto: [file image]
```

**Response (201):**
```json
{
  "success": true,
  "message": "Pengaduan normal berhasil dibuat",
  "data": {
    "id_pengaduan": 1,
    "id_user": 1,
    "id_item": 2,
    "lokasi": 1,
    "deskripsi": "Kursi rusak di pojok kiri",
    "foto": "123456_foto.jpg",
    "status": "pending",
    "tipe_pengaduan": "normal"
  }
}
```

---

#### **GET** `/pengaduan/{id}`
Detail pengaduan

---

#### **PUT** `/pengaduan/{id}`
Update pengaduan (hanya jika status pending)

---

#### **DELETE** `/pengaduan/{id}`
Hapus pengaduan (hanya jika status pending)

---

#### **GET** `/pengaduan/status/pending`
Filter pengaduan normal status pending

---

#### **GET** `/pengaduan/status/diproses`
Filter pengaduan normal status diproses

---

#### **GET** `/pengaduan/status/selesai`
Filter pengaduan normal status selesai

---

#### **GET** `/pengaduan/status/ditolak`
Filter pengaduan normal status ditolak

---

#### **GET** `/pengaduan/riwayat/all`
Riwayat pengaduan normal (selesai + ditolak)

---

### 4. Temporary Items (Item/Lokasi Baru) 🔒

#### **GET** `/temporary-items`
List semua temporary items user

**Response (200):**
```json
{
  "success": true,
  "message": "Data temporary items berhasil diambil",
  "data": [
    {
      "id_temporary": 1,
      "id_user": 1,
      "nama_barang_baru": "Proyektor",
      "lokasi_barang_baru": "Ruang Lab Baru",
      "deskripsi": "Perlu proyektor di lab baru",
      "foto": "123456_temp.jpg",
      "status": "pending",
      "alasan_penolakan": null,
      "created_at": "2025-11-13T10:00:00.000000Z",
      "user": {
        "id_user": 1,
        "username": "johndoe",
        "name": "john@example.com"
      }
    }
  ]
}
```

---

#### **POST** `/temporary-items`
Submit temporary item baru (item/lokasi baru)

**Request (multipart/form-data):**
```
nama_barang_baru: "Proyektor" (opsional jika lokasi_barang_baru diisi)
lokasi_barang_baru: "Ruang Lab Baru" (opsional jika nama_barang_baru diisi)
deskripsi: "Perlu proyektor untuk presentasi di lab baru"
foto: [file image] (opsional)
```

**Note:** Minimal salah satu dari `nama_barang_baru` atau `lokasi_barang_baru` harus diisi!

**Response (201):**
```json
{
  "success": true,
  "message": "Pengajuan item/lokasi baru berhasil dikirim! Tunggu persetujuan admin.",
  "data": {
    "id_temporary": 1,
    "id_user": 1,
    "nama_barang_baru": "Proyektor",
    "lokasi_barang_baru": "Ruang Lab Baru",
    "deskripsi": "Perlu proyektor untuk presentasi di lab baru",
    "foto": "123456_temp.jpg",
    "status": "pending"
  }
}
```

---

#### **GET** `/temporary-items/{id}`
Detail temporary item

**Response (200):**
```json
{
  "success": true,
  "message": "Detail temporary item",
  "data": {
    "id_temporary": 1,
    "id_user": 1,
    "nama_barang_baru": "Proyektor",
    "lokasi_barang_baru": "Ruang Lab Baru",
    "deskripsi": "Perlu proyektor untuk presentasi di lab baru",
    "foto": "123456_temp.jpg",
    "status": "approved",
    "alasan_penolakan": null,
    "created_at": "2025-11-13T10:00:00.000000Z"
  }
}
```

---

### 5. Statistics 🔒

#### **GET** `/statistics`
Statistik pengaduan untuk dashboard

**Response (200):**
```json
{
  "success": true,
  "message": "Statistik pengaduan",
  "data": {
    "normal": {
      "total": 10,
      "pending": 3,
      "diterima": 2,
      "diproses": 2,
      "selesai": 2,
      "ditolak": 1
    },
    "temporary": {
      "total": 5,
      "pending": 2,
      "approved": 2,
      "rejected": 1
    }
  }
}
```

---

## 🔄 Status Flow

### Pengaduan Normal:
1. **pending** → User submit, tunggu admin review
2. **diterima** → Admin terima, tersedia untuk petugas
3. **diproses** → Petugas mengerjakan
4. **selesai** → Petugas selesai, masuk riwayat ✅
5. **ditolak** → Admin tolak, masuk riwayat ❌

### Temporary Item:
1. **pending** → User submit, tunggu admin review
2. **approved** → Admin setujui, item/lokasi ditambahkan, masuk riwayat sebagai pengaduan selesai ✅
3. **rejected** → Admin tolak, tetap di tabel temporary dengan status rejected ❌

---

## 📁 Upload File

Base folder untuk foto:
- Pengaduan normal: `public/uploads/pengaduan/`
- Temporary items: `public/uploads/temporary/`

URL akses foto:
```
http://your-domain.com/uploads/pengaduan/{filename}
http://your-domain.com/uploads/temporary/{filename}
```

---

## ⚠️ Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "Aplikasi mobile hanya untuk pengguna biasa."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### 500 Server Error
```json
{
  "message": "Internal server error"
}
```

---

## 🎯 Perbedaan Fitur Web vs Mobile

| Fitur | Web | Mobile API |
|-------|-----|------------|
| **Register** | ✅ | ✅ |
| **Login** | ✅ | ✅ (Username/Email) |
| **Pengaduan Normal** | ✅ | ✅ |
| **Temporary Item** | ✅ | ✅ |
| **Notifikasi** | ✅ Badge di Sidebar | ❌ Tidak ada (Update status melalui polling) |
| **Role Petugas** | ✅ | ❌ (Web only) |
| **Role Admin** | ✅ | ❌ (Web only) |

### 📌 Catatan Notifikasi untuk Mobile:
- Mobile app **TIDAK** memiliki sistem notifikasi push
- Untuk mengetahui update status pengaduan, gunakan **polling** dengan memanggil:
  - `GET /pengaduan` - untuk list terbaru
  - `GET /temporary-items` - untuk status temporary items
  - `GET /statistics` - untuk count terbaru
- Atau implementasikan **pull-to-refresh** di Flutter untuk update manual

---

## 🧪 Testing dengan Postman/Thunder Client

### 1. Login
```
POST http://localhost/api/login
Body (JSON):
{
  "login": "user1",
  "password": "password"
}
```

### 2. Simpan Token dari Response

### 3. Buat Pengaduan Normal
```
POST http://localhost/api/pengaduan
Headers:
  Authorization: Bearer {token}
Body (form-data):
  id_item: 1
  lokasi: 1
  deskripsi: Test pengaduan
  foto: [upload file]
```

### 4. Buat Temporary Item
```
POST http://localhost/api/temporary-items
Headers:
  Authorization: Bearer {token}
Body (form-data):
  nama_barang_baru: Proyektor LCD
  lokasi_barang_baru: Ruang Meeting Baru
  deskripsi: Butuh proyektor untuk meeting
  foto: [upload file]
```

---

## ✅ Fitur Sudah Siap untuk Flutter!

Semua endpoint sudah mendukung:
- ✅ Authentication (Register, Login, Logout)
- ✅ Profile Management
- ✅ Pengaduan Normal (CRUD)
- ✅ Temporary Items (Item/Lokasi Baru)
- ✅ Filter by Status
- ✅ Riwayat
- ✅ Statistics untuk Dashboard
- ✅ Upload Foto
- ✅ Master Data (Items, Lokasi)

🎉 **Siap digunakan untuk Flutter Mobile App!**
