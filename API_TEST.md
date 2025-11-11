# Test API Laravel - Sarpras

## Test dengan Postman/Thunder Client/Insomnia

### 1. Register User Baru
```http
POST http://127.0.0.1:8000/api/register
Content-Type: application/json

{
  "username": "testuser",
  "email": "testuser@example.com",
  "password": "123456",
  "password_confirmation": "123456"
}
```

**Expected Response (201):**
```json
{
  "message": "Register berhasil",
  "user": {
    "id": 1,
    "username": "testuser",
    "email": "testuser@example.com",
    "role": "pengguna"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz..."
}
```

---

### 2. Login User
```http
POST http://127.0.0.1:8000/api/login
Content-Type: application/json

{
  "username": "testuser",
  "password": "123456"
}
```

**Expected Response (200):**
```json
{
  "message": "Login berhasil",
  "user": {
    "id": 1,
    "username": "testuser",
    "email": "testuser@example.com",
    "role": "pengguna"
  },
  "token": "2|zyxwvutsrqponmlkjihgfedcba..."
}
```

---

### 3. Get Current User (Protected)
```http
GET http://127.0.0.1:8000/api/auth/me
Authorization: Bearer YOUR_TOKEN_HERE
```

**Expected Response (200):**
```json
{
  "id": 1,
  "username": "testuser",
  "email": "testuser@example.com",
  "role": "pengguna"
}
```

---

### 4. Logout (Protected)
```http
POST http://127.0.0.1:8000/api/auth/logout
Authorization: Bearer YOUR_TOKEN_HERE
```

**Expected Response (200):**
```json
{
  "message": "Logout berhasil"
}
```

---

## Test dari Command Line (Windows)

### Test Register:
```bash
curl -X POST http://127.0.0.1:8000/api/register ^
  -H "Content-Type: application/json" ^
  -d "{\"username\":\"testuser\",\"email\":\"testuser@example.com\",\"password\":\"123456\",\"password_confirmation\":\"123456\"}"
```

### Test Login:
```bash
curl -X POST http://127.0.0.1:8000/api/login ^
  -H "Content-Type: application/json" ^
  -d "{\"username\":\"testuser\",\"password\":\"123456\"}"
```

---

## Error Responses

### Validation Error (422):
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "username": ["The username field is required."],
    "password": ["The password must be at least 6 characters."]
  }
}
```

### Authentication Error (401):
```json
{
  "message": "Unauthenticated."
}
```

### Not Found (404):
```json
{
  "message": "Not Found"
}
```

---

## Quick Test Script

Buat file `test_api.ps1`:
```powershell
# Test Register
$register = @{
    username = "testuser$(Get-Random)"
    email = "test$(Get-Random)@example.com"
    password = "123456"
    password_confirmation = "123456"
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/register" `
    -Method Post -Body $register -ContentType "application/json"

Write-Host "Register Success!"
Write-Host "Token: $($response.token)"
Write-Host "Username: $($response.user.username)"

# Test Login
$login = @{
    username = $response.user.username
    password = "123456"
} | ConvertTo-Json

$loginResponse = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/login" `
    -Method Post -Body $login -ContentType "application/json"

Write-Host "`nLogin Success!"
Write-Host "Token: $($loginResponse.token)"
```

Jalankan:
```bash
powershell -ExecutionPolicy Bypass -File test_api.ps1
```

---

## Testing Checklist

- [ ] Laravel server running (`php artisan serve`)
- [ ] Database connected
- [ ] Register endpoint works (201 response)
- [ ] Login endpoint works (200 response)
- [ ] Token generated successfully
- [ ] Protected routes require token
- [ ] Logout works
- [ ] Validation errors shown correctly

---

**Happy Testing! 🧪**
