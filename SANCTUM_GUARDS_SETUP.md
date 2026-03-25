# 🔐 Sanctum Guards Setup - Admin, NguoiDung, ThanhVienDoi

## 📋 Overview

Hệ thống đã được cấu hình với **3 Sanctum Guards** để dễ dàng phân quyền cho 3 loại user:

| Guard | Model | Table | Vai Trò |
|-------|-------|-------|---------|
| `admin` | `App\Models\Admin` | `admin` | Quản trị viên (có id_chuc_vu) |
| `nguoi-dung` | `App\Models\NguoiDung` | `nguoi_dung` | Người dùng thường (gọi cứu hộ) |
| `thanh-vien-doi` | `App\Models\ThanhVienDoi` | `thanh_vien_doi` | Thành viên đội cứu hộ |

---

## 🔧 Configuration (config/auth.php)

### Guards Setup
```php
'guards' => [
    'admin' => [
        'driver' => 'sanctum',
        'provider' => 'admin',
    ],
    'nguoi-dung' => [
        'driver' => 'sanctum',
        'provider' => 'nguoi-dung',
    ],
    'thanh-vien-doi' => [
        'driver' => 'sanctum',
        'provider' => 'thanh-vien-doi',
    ],
],
```

### Providers Setup
```php
'providers' => [
    'admin' => [
        'driver' => 'eloquent',
        'model' => Admin::class,
    ],
    'nguoi-dung' => [
        'driver' => 'eloquent',
        'model' => NguoiDung::class,
    ],
    'thanh-vien-doi' => [
        'driver' => 'eloquent',
        'model' => ThanhVienDoi::class,
    ],
],
```

---

## 🛡️ Middleware Usage

### 1. **Check Role Middleware** (`check.role`)

Kiểm tra xem user có đúng role (vai trò) không.

#### Cú pháp:
```php
Route::middleware(['auth:sanctum', 'check.role:admin'])->group(function () {
    // Chỉ admin mới vào được
});

Route::middleware(['auth:sanctum', 'check.role:thanh-vien-doi,team-leader'])->group(function () {
    // Chỉ thành viên đội có vai trò là Team Leader
});
```

#### Trong Controller:
```php
public function adminOnly()
{
    $userType = request()->attributes->get('user_type');  // 'admin'
    $userRole = request()->attributes->get('user_role');  // vai trò cụ thể
    
    // Logic code...
}
```

---

### 2. **Auth Guard Middleware** (`auth.guard`)

Kiểm tra authentication với guard cụ thể.

#### Cú pháp:
```php
Route::middleware(['auth.guard:admin'])->group(function () {
    // Yêu cầu đăng nhập là admin
});

Route::middleware(['auth.guard:nguoi-dung'])->group(function () {
    // Yêu cầu đăng nhập là người dùng thường
});
```

---

## 📡 API Routes Example

```php
// ===== ADMIN LOGIN =====
Route::post('/admin/login', [AdminController::class, 'login']);

// ===== ADMIN PROTECTED ROUTES =====
Route::middleware(['auth:sanctum', 'check.role:admin'])->group(function () {
    Route::get('/admin/profile', [AdminController::class, 'getProfile']);
    Route::post('/admin/logout', [AdminController::class, 'logout']);
});

// ===== NGUOI DUNG LOGIN =====
Route::post('/nguoi-dung/login', [NguoiDungController::class, 'login']);
Route::post('/nguoi-dung/register', [NguoiDungController::class, 'register']);

// ===== NGUOI DUNG PROTECTED ROUTES =====
Route::middleware(['auth:sanctum', 'check.role:nguoi-dung'])->group(function () {
    Route::get('/nguoi-dung/profile', [NguoiDungController::class, 'getProfile']);
    Route::get('/yeu-cau-cuu-ho', [YeuCauCuuHoController::class, 'index']);
});

// ===== THANH VIEN DOI LOGIN =====
Route::post('/thanh-vien-doi/login', [ThanhVienDoiController::class, 'login']);

// ===== THANH VIEN DOI PROTECTED ROUTES =====
Route::middleware(['auth:sanctum', 'check.role:thanh-vien-doi'])->group(function () {
    Route::get('/thanh-vien-doi/profile', [ThanhVienDoiController::class, 'getProfile']);
    Route::get('/phan-cong-cuu-ho', [PhanCongCuuHoController::class, 'getAssignments']);
});
```

---

## 🧪 Testing the Guards

### 1. **Login as Admin**

```bash
curl -X POST http://localhost:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "mat_khau": "admin123"
  }'
```

**Response:**
```json
{
  "status": true,
  "message": "Đăng nhập thành công",
  "token": "25|8NkGp...",
  "token_type": "Bearer",
  "data": {
    "id_admin": 1,
    "ho_ten": "Nguyễn Văn A",
    "email": "admin@example.com",
    "chuc_vu": { ... }
  }
}
```

### 2. **Use Token in Request**

```bash
curl -X GET http://localhost:8000/api/admin/profile \
  -H "Authorization: Bearer 25|8NkGp..." \
  -H "Content-Type: application/json"
```

### 3. **Login as NguoiDung**

```bash
curl -X POST http://localhost:8000/api/nguoi-dung/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "huong1@example.com",
    "mat_khau": "user123"
  }'
```

### 4. **Login as ThanhVienDoi**

```bash
# Cần có controller cho ThanhVienDoi trước
curl -X POST http://localhost:8000/api/thanh-vien-doi/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "member1@doi1.example.com",
    "mat_khau": "member123"
  }'
```

---

## 📊 Test Data

Sau khi chạy `php artisan db:seed`, có sẵn dữ liệu test:

### Admin Accounts
| Email | Password | Chức Vụ | 
|-------|----------|--------|
| admin@example.com | admin123 | (ChucVu[0]) |
| operator1@example.com | op123 | (ChucVu[1]) |
| operator2@example.com | op123 | (ChucVu[1]) |
| support@example.com | sup123 | (ChucVu[1]) |
| manager@example.com | mgr123 | (ChucVu[0]) |

### NguoiDung Accounts
| Email | Password |
|-------|----------|
| huong1@example.com | user123 |
| hung1@example.com | user123 |
| hoa1@example.com | user123 |
| ... (10 tổng cộng) | user123 |

### ThanhVienDoi Accounts (Team Members)
| Email | Password | Vai Trò |
|-------|----------|---------|
| member1@{doi}.example.com | member123 | Team Leader |
| member2@{doi}.example.com | member123 | Member |
| ... | member123 | ... |

---

## 🚀 Controller Implementation Example

### AdminController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->mat_khau, $admin->mat_khau)) {
            return response()->json([
                'status' => false,
                'message' => 'Email hoặc mật khẩu sai',
            ], 401);
        }

        $token = $admin->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => $admin->load('chucVu'),
        ]);
    }

    public function getProfile()
    {
        $admin = Auth::guard('sanctum')->user();
        
        return response()->json([
            'status' => true,
            'data' => $admin->load('chucVu'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user('sanctum')->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Đăng xuất thành công',
        ]);
    }
}
```

---

## 🔄 Getting User in Routes/Controllers

### Via Request
```php
// Lấy user hiện tại
$user = Auth::guard('sanctum')->user();

// Lấy user type từ middleware
$userType = request()->attributes->get('user_type');  // 'admin', 'nguoi-dung', 'thanh-vien-doi'
$userRole = request()->attributes->get('user_role');  // Vai trò cụ thể
```

### Checking User Type
```php
use App\Models\Admin;
use App\Models\NguoiDung;
use App\Models\ThanhVienDoi;

$user = Auth::guard('sanctum')->user();

if ($user instanceof Admin) {
    // Admin logic
}

if ($user instanceof NguoiDung) {
    // User logic
}

if ($user instanceof ThanhVienDoi) {
    // Team member logic
}
```

---

## ✅ Checklist để Sử Dụng

- [x] Config auth.php với 3 guards
- [x] Update seeders để sử dụng Hash::make()
- [x] Register middleware trong bootstrap/app.php
- [x] Tạo middleware CheckRole
- [x] Seed database với: `php artisan migrate:fresh --seed`
- [ ] Update routes/api.php để sử dụng middleware
- [ ] Implement login methods trong Controllers
- [ ] Test từng endpoint với Postman/curl

---

## 📝 Chú Ý

1. **Default Guard**: Khi không chỉ định guard, Laravel sẽ dùng guard mặc định (web)
2. **Sanctum Check**: Sử dụng `auth:sanctum` để chỉ chấp nhận token bearer
3. **Role Check**: Sử dụng `check.role:admin` để chỉ chấp nhận role cụ thể
4. **Hidden Fields**: Passwords được ẩn tự động via $hidden trong Models
5. **Token Expiration**: Sanctum tokens không hết hạn mặc định (có thể set trong config)

