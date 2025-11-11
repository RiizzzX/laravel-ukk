# API Documentation - NGASAR Mobile App

API untuk aplikasi mobile sistem pengaduan sarana prasarana sekolah.

## Base URL
```
http://localhost/api
```

## Authentication
API menggunakan Laravel Sanctum (Bearer Token). Setelah login, gunakan token di header:
```
Authorization: Bearer {your-token}
```

---

## 📱 Endpoints

### 🔐 Authentication

#### 1. Register
**POST** `/auth/register`

**Request Body:**
```json
{
  "username": "john_doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "message": "Register berhasil",
  "user": {
    "id_user": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "role": "pengguna"
  },
  "token": "1|abc123..."
}
```

---

#### 2. Login
**POST** `/auth/login`

**Request Body:**
```json
{
  "username": "john_doe",
  "password": "password123"
}
```

**Response:**
```json
{
  "message": "Login berhasil",
  "user": {
    "id_user": 1,
    "username": "john_doe",
    "role": "pengguna"
  },
  "token": "2|xyz789..."
}
```

---

#### 3. Get User Info
**GET** `/auth/me`

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "id_user": 1,
  "username": "john_doe",
  "email": "john@example.com",
  "role": "pengguna"
}
```

---

#### 4. Update Profile
**PUT** `/auth/profile`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "username": "new_username",
  "email": "newemail@example.com",
  "password": "newpassword",
  "password_confirmation": "newpassword"
}
```

---

#### 5. Logout
**POST** `/auth/logout`

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "message": "Logout berhasil"
}
```

---

### 📍 Master Data (Public - No Auth)

#### 6. Get All Lokasi
**GET** `/master/lokasi`

**Response:**
```json
{
  "success": true,
  "message": "Data lokasi berhasil diambil",
  "data": [
    {
      "id_lokasi": 1,
      "nama_lokasi": "Ruang Kelas",
      "gedung": "Gedung A"
    },
    {
      "id_lokasi": 2,
      "nama_lokasi": "Lab Komputer",
      "gedung": "Gedung B"
    }
  ]
}
```

---

#### 7. Get Lokasi Detail
**GET** `/master/lokasi/{id}`

**Response:**
```json
{
  "success": true,
  "message": "Detail lokasi",
  "data": {
    "id_lokasi": 1,
    "nama_lokasi": "Ruang Kelas",
    "gedung": "Gedung A",
    "items": [
      {
        "id_item": 1,
        "nama_item": "Meja Siswa",
        "lokasi": 1
      }
    ]
  }
}
```

---

#### 8. Get All Items
**GET** `/master/items`

**Response:**
```json
{
  "success": true,
  "message": "Data item berhasil diambil",
  "data": [
    {
      "id_item": 1,
      "nama_item": "Meja Siswa",
      "lokasi": 1,
      "lokasi_relation": {
        "id_lokasi": 1,
        "nama_lokasi": "Ruang Kelas"
      }
    }
  ]
}
```

---

#### 9. Get Items by Lokasi
**GET** `/master/items/by-lokasi/{id_lokasi}`

**Response:**
```json
{
  "success": true,
  "message": "Data item berdasarkan lokasi",
  "data": [
    {
      "id_item": 1,
      "nama_item": "Meja Siswa",
      "lokasi": 1
    },
    {
      "id_item": 2,
      "nama_item": "Kursi Siswa",
      "lokasi": 1
    }
  ]
}
```

---

### 📝 Pengaduan Management (Auth Required)

#### 10. Get All Pengaduan
**GET** `/pengaduan`

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "message": "Data pengaduan berhasil diambil",
  "data": [
    {
      "id_pengaduan": 1,
      "id_user": 1,
      "id_item": 1,
      "lokasi": 1,
      "deskripsi": "Kursi rusak di kelas",
      "foto": "pengaduan/abc.jpg",
      "status": "pending",
      "tgl_pengajuan": "2025-01-20",
      "created_at": "2025-01-20T10:00:00",
      "item": {
        "id_item": 1,
        "nama_item": "Kursi Siswa"
      },
      "lokasi_relation": {
        "id_lokasi": 1,
        "nama_lokasi": "Ruang Kelas"
      }
    }
  ]
}
```

---

#### 11. Create Pengaduan
**POST** `/pengaduan`

**Headers:** `Authorization: Bearer {token}`, `Content-Type: multipart/form-data`

**Request Body (form-data):**
- `id_item` (required): ID item yang rusak
- `lokasi` (required): ID lokasi
- `deskripsi` (required): Deskripsi pengaduan
- `foto` (optional): File gambar (jpg, jpeg, png, max 5MB)

**Response:**
```json
{
  "success": true,
  "message": "Pengaduan berhasil dibuat",
  "data": {
    "id_pengaduan": 1,
    "status": "pending",
    ...
  }
}
```

---

#### 12. Get Pengaduan Detail
**GET** `/pengaduan/{id}`

**Headers:** `Authorization: Bearer {token}`

---

#### 13. Update Pengaduan
**PUT** `/pengaduan/{id}`

**Headers:** `Authorization: Bearer {token}`

**Note:** Hanya bisa update jika status masih `pending`

**Request Body:**
```json
{
  "id_item": 2,
  "lokasi": 1,
  "deskripsi": "Deskripsi updated"
}
```

---

#### 14. Delete Pengaduan
**DELETE** `/pengaduan/{id}`

**Headers:** `Authorization: Bearer {token}`

**Note:** Hanya bisa delete jika status masih `pending`

---

### 🔍 Filter by Status

#### 15. Get Pending Pengaduan
**GET** `/pengaduan/status/pending`

#### 16. Get Diproses Pengaduan
**GET** `/pengaduan/status/diproses`

#### 17. Get Selesai Pengaduan
**GET** `/pengaduan/status/selesai`

#### 18. Get Ditolak Pengaduan
**GET** `/pengaduan/status/ditolak`

#### 19. Get Riwayat (Selesai + Ditolak)
**GET** `/pengaduan/riwayat/all`

---

### 💡 Saran Item

#### 20. Submit Saran Item Baru
**POST** `/pengaduan/saran`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "nama_item": "Proyektor Baru",
  "lokasi": 1,
  "alasan": "Proyektor lama sudah rusak dan perlu diganti"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Saran item berhasil dikirim",
  "data": {
    "id_pengaduan": 5,
    "deskripsi": "SARAN ITEM: Proyektor Baru\n\nAlasan: Proyektor lama sudah rusak...",
    "status": "pending"
  }
}
```

---

### 📊 Statistics

#### 21. Get User Statistics
**GET** `/statistics`

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "message": "Statistik pengaduan",
  "data": {
    "total": 10,
    "pending": 3,
    "diproses": 2,
    "selesai": 4,
    "ditolak": 1
  }
}
```

---

## 🧪 Testing dengan Postman

### Setup
1. Buat collection baru "NGASAR API"
2. Set base URL variable: `{{base_url}}` = `http://localhost/api`
3. Set token variable: `{{token}}` untuk menyimpan token setelah login

### Flow Testing
1. **Register/Login** → Simpan token dari response
2. **Get Lokasi** → Untuk dropdown lokasi
3. **Get Items by Lokasi** → Untuk dropdown item
4. **Create Pengaduan** → Buat pengaduan dengan foto
5. **Get All Pengaduan** → Lihat list pengaduan
6. **Get Statistics** → Lihat statistik dashboard

---

## 📝 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Pesan sukses",
  "data": {...}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Pesan error",
  "errors": {
    "field": ["Error message"]
  }
}
```

---

## 🔒 Status Codes
- `200` OK
- `201` Created
- `401` Unauthorized
- `403` Forbidden
- `404` Not Found
- `422` Validation Error
- `500` Server Error

---

## 📱 Mobile App Features yang Didukung
1. ✅ Authentication (Register, Login, Logout)
2. ✅ Profile Management
3. ✅ Lihat Master Data (Lokasi & Item)
4. ✅ Buat Pengaduan dengan Upload Foto
5. ✅ Lihat Riwayat Pengaduan
6. ✅ Filter Pengaduan by Status
7. ✅ Edit/Hapus Pengaduan (jika pending)
8. ✅ Submit Saran Item Baru
9. ✅ Dashboard Statistics

---

## 🛠️ Development Notes
- Base URL production: Ganti dengan domain production
- Token disimpan di local storage mobile app
- Foto disimpan di `storage/app/public/pengaduan`
- Pastikan `php artisan storage:link` sudah dijalankan
