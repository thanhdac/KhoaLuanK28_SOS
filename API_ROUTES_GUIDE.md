# API Routes Documentation

## 🎯 Admin Management

### Login
```
POST /api/admin/login
```
**Body:**
```json
{
  "email": "admin@example.com",
  "mat_khau": "password123"
}
```

### Get List
```
GET /api/admin
```

### Get Detail
```
GET /api/admin/{id}
```

### Create
```
POST /api/admin
```
**Body:**
```json
{
  "ho_ten": "Tên Admin",
  "email": "email@example.com",
  "mat_khau": "password",
  "so_dien_thoai": "0901234567",
  "id_chuc_vu": 1,
  "trang_thai": 1
}
```

### Update
```
PUT /api/admin/{id}
```
**Body:**
```json
{
  "ho_ten": "Tên mới",
  "email": "email@example.com",
  "mat_khau": "password",
  "so_dien_thoai": "0987654321",
  "id_chuc_vu": 1,
  "trang_thai": 1
}
```

### Delete
```
DELETE /api/admin/{id}
```

### Search
```
GET /api/tim-kiem/admin?noi_dung_tim=keyword
```

### Change Status
```
PUT /api/admin/{id}/change-status
```

### Active Account
```
PUT /api/admin/{id}/active
```

---

## 👥 User Management

### Login
```
POST /api/nguoi-dung/login
```
**Body:**
```json
{
  "email": "user@example.com",
  "mat_khau": "password123"
}
```

### Register
```
POST /api/nguoi-dung/register
```
**Body:**
```json
{
  "ho_ten": "Tên Người Dùng",
  "email": "email@example.com",
  "mat_khau": "password",
  "so_dien_thoai": "0901234567"
}
```

### Get List
```
GET /api/nguoi-dung
```

### Get Detail
```
GET /api/nguoi-dung/{id}
```

### Create
```
POST /api/nguoi-dung
```
**Body:**
```json
{
  "ho_ten": "Tên Người Dùng",
  "email": "email@example.com",
  "mat_khau": "password",
  "so_dien_thoai": "0901234567",
  "trang_thai": 1
}
```

### Update
```
PUT /api/nguoi-dung/{id}
```
**Body:**
```json
{
  "ho_ten": "Tên mới",
  "email": "email@example.com",
  "mat_khau": "password",
  "so_dien_thoai": "0987654321",
  "trang_thai": 1
}
```

### Delete
```
DELETE /api/nguoi-dung/{id}
```

### Search
```
GET /api/tim-kiem/nguoi-dung?noi_dung_tim=keyword
```

### Change Status
```
PUT /api/nguoi-dung/{id}/change-status
```

---

## 📋 Usage Examples

### JavaScript/Axios

**Login Admin:**
```javascript
const response = await axios.post('http://localhost:8000/api/admin/login', {
  email: 'admin@example.com',
  mat_khau: 'password123'
});
```

**Get Admin List:**
```javascript
const admins = await axios.get('http://localhost:8000/api/admin');
console.log(admins.data.data);
```

**Create Admin:**
```javascript
const newAdmin = await axios.post('http://localhost:8000/api/admin', {
  ho_ten: 'Admin Mới',
  email: 'newemail@example.com',
  mat_khau: 'password',
  so_dien_thoai: '0901234567',
  id_chuc_vu: 1,
  trang_thai: 1
});
```

**Update Admin:**
```javascript
const updated = await axios.put('http://localhost:8000/api/admin/1', {
  ho_ten: 'Admin Updated',
  so_dien_thoai: '0987654321'
});
```

**Delete Admin:**
```javascript
await axios.delete('http://localhost:8000/api/admin/1');
```

**Search:**
```javascript
const results = await axios.get('http://localhost:8000/api/tim-kiem/admin?noi_dung_tim=admin');
```

---

## 📝 Base URL
```
http://localhost:8000/api
```

---

## ✅ Response Format

### Success Response
```json
{
  "status": true,
  "message": "Đăng nhập thành công",
  "data": {
    "id_admin": 1,
    "ho_ten": "Admin",
    "email": "admin@example.com",
    "so_dien_thoai": "0901234567",
    "id_chuc_vu": 1,
    "trang_thai": 1
  }
}
```

### Error Response
```json
{
  "status": false,
  "message": "Tài khoản sai email hoặc password"
}
```

### List Response
```json
{
  "status": true,
  "data": [
    { "id_admin": 1, "ho_ten": "Admin 1", ... },
    { "id_admin": 2, "ho_ten": "Admin 2", ... }
  ]
}
```
