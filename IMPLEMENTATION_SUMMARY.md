# 📚 Implementation Complete - 3 Sanctum Guards for Role-Based Access

## 🎯 What Was Done

### 1. ✅ Configuration Setup (config/auth.php)

Added 3 Sanctum guards for different user types:

```php
Guards:
├── admin (Admin model)
├── nguoi-dung (NguoiDung model)
└── thanh-vien-doi (ThanhVienDoi model)

Providers:
├── admin → Admin model
├── nguoi-dung → NguoiDung model
└── thanh-vien-doi → ThanhVienDoi model
```

**Benefits:**
- Separate authentication for each user type
- Easy permission management
- Type-safe user handling
- Cleaner middleware checks

### 2. ✅ Database & Seeders

**Updated seeders to use Hash::make() (secure):**
- `AdminSeeder.php` - 5 admin accounts with roles
- `NguoiDungSeeder.php` - 14 regular users
- `ThanhVienDoiSeeder.php` - 12 team members with roles

**Sample Data:**
```
Admin Accounts: admin@example.com (admin123)
User Accounts: huong1@example.com (user123)
Team Members: member1@Q1.example.com (member123)
```

### 3. ✅ Middleware Stack

New middleware for role-based access:

```
CheckRole.php (check.role)
└── Validates user role and grants permission

CheckAdmin.php (check.admin) 
└── Ensures only Admin model can access

AuthGuard.php (auth.guard)
└── Flexible guard checking
```

### 4. ✅ Helper Class (AuthHelper.php)

Utility methods for easy user checks in controllers:

```php
AuthHelper::user()           // Get current user
AuthHelper::getUserType()    // Get type (admin/nguoi-dung/thanh-vien-doi)
AuthHelper::getUserRole()    // Get role (chức vụ/vai trò)
AuthHelper::isAdmin()        // Check if admin
AuthHelper::isUser()         // Check if regular user
AuthHelper::isTeamMember()   // Check if team member
AuthHelper::isTeamLeader()   // Check if team leader
AuthHelper::getId()          // Get user ID (universal)
AuthHelper::getName()        // Get user name
AuthHelper::getEmail()       // Get email
AuthHelper::isActive()       // Check if account active
```

### 5. ✅ Middleware Registration

Registered in `bootstrap/app.php`:
```php
'check.admin' => CheckAdmin::class
'check.role' => CheckRole::class
'auth.guard' => AuthGuard::class
```

---

## 📖 Documentation Created

1. **SANCTUM_GUARDS_SETUP.md** (Complete reference guide)
   - Configuration details
   - Usage examples
   - Route examples
   - Testing procedures

2. **TESTING_3_GUARDS.md** (Testing guide)
   - Step-by-step test cases
   - curl commands
   - Example controllers
   - Troubleshooting

---

## 🚀 How to Use

### In Routes (routes/api.php)

```php
// Public - No auth needed
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/nguoi-dung/login', [NguoiDungController::class, 'login']);

// Admin-only
Route::middleware(['auth:sanctum', 'check.role:admin'])->group(function () {
    Route::get('/admin/profile', [AdminController::class, 'profile']);
    Route::get('/admin/list', [AdminController::class, 'index']);
});

// User-only
Route::middleware(['auth:sanctum', 'check.role:nguoi-dung'])->group(function () {
    Route::post('/yeu-cau-cuu-ho', [YeuCauCuuHoController::class, 'store']);
    Route::get('/yeu-cau-cuu-ho', [YeuCauCuuHoController::class, 'index']);
});

// Team member-only
Route::middleware(['auth:sanctum', 'check.role:thanh-vien-doi'])->group(function () {
    Route::get('/phan-cong', [PhanCongCuuHoController::class, 'getAssignments']);
    Route::put('/phan-cong/{id}/status', [PhanCongCuuHoController::class, 'updateStatus']);
});
```

### In Controllers

**Method 1: Using Request attributes (from middleware)**
```php
public function store(Request $request)
{
    $userType = $request->attributes->get('user_type');  // 'admin'
    $userRole = $request->attributes->get('user_role');  // 'Quản trị viên'
    
    // Logic based on user type/role
}
```

**Method 2: Using AuthHelper (recommended)**
```php
use App\Support\AuthHelper;

public function store(Request $request)
{
    if (!AuthHelper::isUser()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $userId = AuthHelper::getId();
    $userName = AuthHelper::getName();
    
    // Create resource...
}
```

**Method 3: Using Auth guard directly**
```php
use Illuminate\Support\Facades\Auth;

public function store(Request $request)
{
    $user = Auth::guard('sanctum')->user();
    
    if ($user instanceof \App\Models\Admin) {
        // Admin logic
    } elseif ($user instanceof \App\Models\NguoiDung) {
        // User logic
    }
}
```

---

## 🔄 Existing checkAdmin vs CheckRole

### Current Route (api.php)
```php
Route::get('/admin/check-token', [AdminController::class, 'checkAdmin']);
```

### Current Method (AdminController.php)
```php
public function checkAdmin()
{
    $user = Auth::guard('sanctum')->user();
    if ($user) {
        return response()->json(['status' => true, 'email'=> $user->email]);
    }
    return response()->json(['status' => false, 'message' => '...']);
}
```

### To Enhance with Role Check:
```php
Route::get('/admin/check-token', [AdminController::class, 'checkAdmin'])
     ->middleware('auth:sanctum', 'check.role:admin');

// Now checkAdmin() will only be called if user is Admin
```

---

## 📊 Architecture Overview

```
User Request
    ↓
Authentication (auth:sanctum)
    ├─ Checks if valid token
    └─ Creates user object
        ↓
    Role/Type Check (check.role:admin)
        ├─ Determines user model type
        └─ Validates role permissions
            ↓
        Controller Method
            ↓
        Response
```

---

## 🧪 Quick Test

### 1. Start the server
```bash
php artisan serve
```

### 2. Test admin login
```bash
curl -X POST http://localhost:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","mat_khau":"admin123"}'
```

### 3. Use token to access protected route
```bash
# Replace TOKEN_HERE with actual token
curl -X GET http://localhost:8000/api/admin/check-token \
  -H "Authorization: Bearer TOKEN_HERE"
```

---

## 🔐 Security Features

✅ **Password Hashing**: All passwords use `Hash::make()`  
✅ **Token-based Auth**: Laravel Sanctum tokens  
✅ **Role Separation**: Distinct models for each user type  
✅ **Middleware Protection**: Each guard validates permissions  
✅ **Hidden Fields**: Passwords hidden in JSON responses  
✅ **Type Safety**: Model-based user validation  

---

## ⚠️ Important Notes

1. **Guards vs Providers**
   - Guard = How authentication works (session, sanctum)
   - Provider = Which model to use (Admin, NguoiDung, etc)

2. **Middleware Order**
   - `auth:sanctum` first (validates token)
   - `check.role` second (validates role)

3. **Token Expiration**
   - Sanctum tokens don't expire by default
   - Can be configured in `config/sanctum.php`

4. **Multiple Guards**
   - Only one guard active at a time
   - Use `Auth::guard('admin')->check()` to check specific guard

---

## 📝 Files Modified/Created

```
✅ config/auth.php               - Guards & providers added
✅ database/seeders/AdminSeeder.php       - Hash::make() update
✅ database/seeders/NguoiDungSeeder.php   - Hash::make() update
✅ database/seeders/ThanhVienDoiSeeder.php - Hash::make() update
✅ app/Http/Middleware/CheckRole.php      - NEW (role validation)
✅ app/Http/Middleware/AuthGuard.php      - NEW (flexible guards)
✅ app/Support/AuthHelper.php             - NEW (utility methods)
✅ bootstrap/app.php                      - Middleware registered
✅ check_db_structure.php                 - Verification script
✅ SANCTUM_GUARDS_SETUP.md                - Complete guide
✅ TESTING_3_GUARDS.md                    - Testing guide
```

---

## 🎓 Next Steps

1. **Update Controllers** with login methods
2. **Protect routes** with middleware
3. **Implement role checks** in controllers
4. **Test with Postman** or curl
5. **Add frontend** integration
6. **Monitor logs** for issues
7. **Add rate limiting** if needed
8. **Implement logout** functionality

---

## 📞 Support

**Reviewed Files:**
- config/auth.php ✅
- Database structure ✅
- Middleware stack ✅
- SeederS ✅

**Test Database:**
- Admin: 5 records ✅
- NguoiDung: 14 records ✅
- ThanhVienDoi: 12 records ✅

All systems ready for authorization implementation! 🚀

