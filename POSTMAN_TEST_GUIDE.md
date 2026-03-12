# Rescue Management API - Postman Test Cases

## 📋 Table of Contents
1. [Setup Instructions](#setup-instructions)
2. [Authentication Tests](#authentication-tests)
3. [Help Requests API Tests](#help-requests-api-tests)
4. [Rescue Teams Tests](#rescue-teams-tests)
5. [Task Assignment Workflow](#task-assignment-workflow)
6. [Statistics & Analytics](#statistics--analytics)
7. [Error Cases](#error-cases)

---

## Setup Instructions

### Prerequisites
- Postman installed (latest version)
- API server running on `http://localhost:8000`
- Database seeded with sample data

### Import Collection
1. Open Postman
2. Click "Import" → Select `postman_collection.json`
3. All test cases will be imported with environment variables

### Environment Variables
The collection uses these variables (auto-populated during tests):
- `adminToken` - Admin authentication token
- `userToken` - User authentication token
- `requestId` - Current help request ID
- `newRequestId` - Newly created request ID
- `teamId` - Current team ID
- `assignmentId` - Current assignment ID

---

## Authentication Tests

### 1.1 Admin Login
**Endpoint:** `POST /api/admin/login`

**Request:**
```json
{
  "email": "admin@example.com",
  "password": "admin123"
}
```

**Expected Response (200):**
```json
{
  "success": true,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "Bearer",
  "admin": {
    "id_admin": 1,
    "email": "admin@example.com",
    "ho_ten": "Nguyễn Văn A"
  }
}
```

**Test Assertions:**
- Status code = 200
- Response has `access_token` property
- Token stored in `adminToken` variable

---

### 1.2 User Login
**Endpoint:** `POST /api/nguoi-dung/login`

**Request:**
```json
{
  "email": "huong1@example.com",
  "password": "user123"
}
```

**Expected Response (200):**
```json
{
  "success": true,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "Bearer",
  "user": {
    "id_nguoi_dung": 5,
    "email": "huong1@example.com",
    "ho_ten": "Nguyễn Thị Hương"
  }
}
```

**Test Assertions:**
- Status code = 200
- Response has `access_token` property
- Token stored in `userToken` variable

---

## Help Requests API Tests

### 2.1 Get All Help Requests
**Endpoint:** `GET /api/yeu-cau-cuu-ho`

**Headers:**
```
Authorization: Bearer {{adminToken}}
```

**Expected Response (200):**
```json
[
  {
    "id_yeu_cau": 1,
    "id_nguoi_dung": 5,
    "id_loai_su_co": 1,
    "vi_tri_lat": 10.7769,
    "vi_tri_lng": 106.7009,
    "vi_tri_dia_chi": "123 Đường Lê Lợi, Q1",
    "chi_tiet": "Cháy tầng 3 toà nhà",
    "mo_ta": "Nhà số 123 bốc cháy từ tầng 3",
    "hinh_anh": "image_1.jpg",
    "so_nguoi_bi_anh_huong": 5,
    "muc_do_khan_cap": "CRITICAL",
    "diem_uu_tien": 10,
    "trang_thai": "CHO_XU_LY",
    "created_at": "2026-03-12T03:00:00Z"
  },
  {
    "id_yeu_cau": 2,
    "id_nguoi_dung": 6,
    "id_loai_su_co": 2,
    "vi_tri_lat": 10.8000,
    "vi_tri_lng": 106.7100,
    "vi_tri_dia_chi": "Bể bơi Tao Đàn, Q3",
    "chi_tiet": "Trẻ em bị đuối nước",
    "trang_thai": "DANG_XU_LY",
    "muc_do_khan_cap": "CRITICAL",
    "diem_uu_tien": 10
  }
]
```

**Test Assertions:**
- Status code = 200
- Response is an array
- Array length > 0
- First request ID saved to `requestId` variable

---

### 2.2 Get Request by ID
**Endpoint:** `GET /api/yeu-cau-cuu-ho/{{requestId}}`

**Expected Response (200):**
```json
{
  "id_yeu_cau": 1,
  "id_nguoi_dung": 5,
  "id_loai_su_co": 1,
  "vi_tri_lat": 10.7769,
  "vi_tri_lng": 106.7009,
  "vi_tri_dia_chi": "123 Đường Lê Lợi, Q1",
  "trang_thai": "CHO_XU_LY",
  "muc_do_khan_cap": "CRITICAL",
  "diem_uu_tien": 10,
  "nguoiDung": {
    "id_nguoi_dung": 5,
    "ho_ten": "Nguyễn Thị Hương",
    "email": "huong1@example.com"
  },
  "loaiSuCo": {
    "id_loai_su_co": 1,
    "ten_danh_muc": "Cháy",
    "slug_danh_muc": "chay"
  }
}
```

**Test Assertions:**
- Status code = 200
- Request ID matches `{{requestId}}`
- Has user and incident type details

---

### 2.3 Create New Help Request (User)
**Endpoint:** `POST /api/yeu-cau-cuu-ho`

**Headers:**
```
Authorization: Bearer {{userToken}}
Content-Type: application/json
```

**Request:**
```json
{
  "id_nguoi_dung": 5,
  "id_loai_su_co": 1,
  "vi_tri_lat": 10.7769,
  "vi_tri_lng": 106.7009,
  "vi_tri_dia_chi": "Tòa nhà 128 Lê Lợi, Q1, TPHCM",
  "chi_tiet": "Cháy tầng thứ tư",
  "mo_ta": "Cần cứu hộ ngay - Khói dày đặc",
  "hinh_anh": "fire_incident.jpg",
  "so_nguoi_bi_anh_huong": 8,
  "muc_do_khan_cap": "CRITICAL",
  "diem_uu_tien": 10
}
```

**Expected Response (201):**
```json
{
  "success": true,
  "message": "Yêu cầu cứu hộ được tạo thành công",
  "id_yeu_cau": 16,
  "trang_thai": "CHO_XU_LY"
}
```

**Test Assertions:**
- Status code = 201
- Response has `id_yeu_cau` property
- New request ID saved to `newRequestId` variable
- Default status = "CHO_XU_LY"

---

### 2.4 Update Help Request Status
**Endpoint:** `PUT /api/yeu-cau-cuu-ho/{{newRequestId}}`

**Headers:**
```
Authorization: Bearer {{adminToken}}
Content-Type: application/json
```

**Request:**
```json
{
  "trang_thai": "DANG_XU_LY"
}
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Cập nhật thành công",
  "id_yeu_cau": 16,
  "trang_thai": "DANG_XU_LY"
}
```

**Test Assertions:**
- Status code = 200
- Status updated to "DANG_XU_LY"

---

### 2.5 Filter by Status
**Endpoint:** `GET /api/yeu-cau-cuu-ho/theo-trang-thai/CHO_XU_LY`

**Expected Response (200):**
```json
[
  {
    "id_yeu_cau": 4,
    "trang_thai": "CHO_XU_LY",
    "muc_do_khan_cap": "HIGH"
  },
  {
    "id_yeu_cau": 7,
    "trang_thai": "CHO_XU_LY",
    "muc_do_khan_cap": "CRITICAL"
  }
]
```

**Test Assertions:**
- Status code = 200
- All requests have status "CHO_XU_LY"

---

## Rescue Teams Tests

### 3.1 Get All Teams
**Endpoint:** `GET /api/doi-cuu-ho`

**Expected Response (200):**
```json
[
  {
    "id_doi_cuu_ho": 1,
    "ten_co": "Đội Cứu Hộ Q1",
    "khu_vuc_quan_ly": "Q1",
    "so_dien_thoai_hotline": "0801111111",
    "vi_tri_lat": 10.7800,
    "vi_tri_lng": 106.7050,
    "trang_thai": "SAN_SANG",
    "mo_ta": "Đội cứu hộ khu vực Q1"
  },
  {
    "id_doi_cuu_ho": 2,
    "ten_co": "Đội Cứu Hộ Q3",
    "khu_vuc_quan_ly": "Q3",
    "so_dien_thoai_hotline": "0802222222",
    "trang_thai": "SAN_SANG"
  }
]
```

**Test Assertions:**
- Status code = 200
- Response is an array
- First team ID saved to `teamId` variable

---

### 3.2 Get Team With Details
**Endpoint:** `GET /api/doi-cuu-ho/{{teamId}}`

**Expected Response (200):**
```json
{
  "id_doi_cuu_ho": 1,
  "ten_co": "Đội Cứu Hộ Q1",
  "khu_vuc_quan_ly": "Q1",
  "so_dien_thoai_hotline": "0801111111",
  "vi_tri_lat": 10.7800,
  "vi_tri_lng": 106.7050,
  "trang_thai": "SAN_SANG",
  "thanhVienDoi": [
    {
      "id_thanh_vien": 1,
      "ho_ten": "Thành viên 1 - Q1",
      "so_dien_thoai": "0911234567",
      "vai_tro_trong_doi": "Team Leader",
      "trang_thai": 1
    },
    {
      "id_thanh_vien": 2,
      "ho_ten": "Thành viên 2 - Q1",
      "vai_tro_trong_doi": "Member"
    }
  ],
  "taiNguyenCuuHo": [
    {
      "id_tai_nguyen": 1,
      "ten_tai_nguyen": "Xe cứu hộ",
      "loai_tai_nguyen": "Vehicle",
      "so_luong": 2,
      "trang_thai": 1
    },
    {
      "id_tai_nguyen": 2,
      "ten_tai_nguyen": "Thiết bị chữa cháy",
      "loai_tai_nguyen": "Equipment",
      "so_luong": 5
    }
  ],
  "nangLucDoi": {
    "id_nang_luc": 1,
    "so_viec_dang_xu_ly": 1,
    "so_viec_toi_da": 3,
    "ty_le_hoan_thanh": 0.95,
    "thoi_gian_xu_ly_tb": 45
  }
}
```

**Test Assertions:**
- Status code = 200
- Has `thanhVienDoi` array (team members)
- Has `taiNguyenCuuHo` array (resources)
- Has `nangLucDoi` object (team capacity)

---

## Task Assignment Workflow

### 4.1 Create Assignment
**Endpoint:** `POST /api/phan-cong-cuu-ho`

**Request:**
```json
{
  "id_yeu_cau": 16,
  "id_doi_cuu_ho": 1,
  "mo_ta": "Phân công xử lý sự cố cháy tầng 4",
  "thoi_gian_phan_cong": "2026-03-12T10:00:00Z",
  "trang_thai_nhiem_vu": "DANG_XU_LY"
}
```

**Expected Response (201):**
```json
{
  "success": true,
  "message": "Phân công được tạo thành công",
  "id_phan_cong": 12,
  "id_yeu_cau": 16,
  "id_doi_cuu_ho": 1,
  "trang_thai_nhiem_vu": "DANG_XU_LY"
}
```

**Test Assertions:**
- Status code = 201
- Assignment ID saved to `assignmentId` variable

---

### 4.2 Update Assignment Status
**Endpoint:** `PUT /api/phan-cong-cuu-ho/{{assignmentId}}`

**Request:**
```json
{
  "trang_thai_nhiem_vu": "HOAN_THANH"
}
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Cập nhật thành công",
  "id_phan_cong": 12,
  "trang_thai_nhiem_vu": "HOAN_THANH"
}
```

**Test Assertions:**
- Status code = 200
- Status updated to "HOAN_THANH"

---

### 4.3 Create Rescue Result
**Endpoint:** `POST /api/ket-qua-cuu-ho`

**Request:**
```json
{
  "id_phan_cong": 12,
  "bao_cao_hien_truong": "Báo cáo hiện trường: Đã kiểm soát tình hình cháy. Sơ tán 8 người an toàn. Dập lửa thành công với ít thiệt hại.",
  "trang_thai_ket_qua": "HOAN_THANH",
  "hinh_anh_minh_chung": "rescue_result_photo.jpg",
  "thoi_gian_ket_thuc": "2026-03-12T10:45:00Z"
}
```

**Expected Response (201):**
```json
{
  "success": true,
  "message": "Kết quả cứu hộ được tạo thành công",
  "id_ket_qua": 5,
  "trang_thai_ket_qua": "HOAN_THANH"
}
```

**Test Assertions:**
- Status code = 201
- Has `id_ket_qua` property

---

### 4.4 Create Rating
**Endpoint:** `POST /api/danh-gia-cuu-ho`

**Request:**
```json
{
  "id_yeu_cau": 16,
  "id_nguoi_dung": 5,
  "diem_danh_gia": 5,
  "noi_dung_danh_gia": "Cảm ơn đội cứu hộ đã xử lý nhanh chóng và chuyên nghiệp!"
}
```

**Expected Response (201):**
```json
{
  "success": true,
  "message": "Đánh giá được tạo thành công",
  "id_danh_gia": 8,
  "diem_danh_gia": 5
}
```

**Test Assertions:**
- Status code = 201
- Rating 1-5 stars
- Has comment

---

## Statistics & Analytics

### 7.1 Total Requests Count
**Endpoint:** `GET /api/thong-ke/tong-so-yeu-cau`

**Expected Response (200):**
```json
{
  "total": 30
}
```

**Test Assertions:**
- Status code = 200
- Total is number > 0

---

### 7.2 Requests by Status
**Endpoint:** `GET /api/thong-ke/yeu-cau-theo-trang-thai`

**Expected Response (200):**
```json
{
  "CHO_XU_LY": 8,
  "DANG_XU_LY": 12,
  "HOAN_THANH": 9,
  "HUY_BO": 1
}
```

**Test Assertions:**
- Status code = 200
- Has CHO_XU_LY count
- Has DANG_XU_LY count

---

### 7.3 Requests by Urgency
**Endpoint:** `GET /api/thong-ke/yeu-cau-theo-do-khan-cap`

**Expected Response (200):**
```json
{
  "LOW": 5,
  "MEDIUM": 8,
  "HIGH": 12,
  "CRITICAL": 5
}
```

**Test Assertions:**
- Status code = 200
- All urgency levels present

---

### 7.4 Processing Queue Status
**Endpoint:** `GET /api/thong-ke/trang-thai-xu-ly`

**Expected Response (200):**
```json
{
  "in_queue": 8,
  "processing": 12,
  "completed": 9,
  "cancelled": 1
}
```

**Test Assertions:**
- Status code = 200
- All metrics non-negative

---

## Error Cases

### 9.1 Invalid Request ID (404)
**Endpoint:** `GET /api/yeu-cau-cuu-ho/99999`

**Expected Response (404):**
```json
{
  "success": false,
  "message": "Yêu cầu cứu hộ không tìm thấy"
}
```

**Test Assertions:**
- Status code = 404
- Has error message

---

### 9.2 Missing Required Fields (422)
**Endpoint:** `POST /api/yeu-cau-cuu-ho`

**Request (Missing required fields):**
```json
{
  "vi_tri_lat": 10.7769,
  "vi_tri_lng": 106.7009
}
```

**Expected Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "id_nguoi_dung": ["The id_nguoi_dung field is required."],
    "id_loai_su_co": ["The id_loai_su_co field is required."]
  }
}
```

**Test Assertions:**
- Status code = 422
- Has errors property with field-specific messages

---

### 9.3 Unauthorized (401)
**Endpoint:** `GET /api/yeu-cau-cuu-ho` (without Authorization header)

**Expected Response (401):**
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

**Test Assertions:**
- Status code = 401

---

## Running Tests

1. **Import Collection** into Postman
2. **Run Authentication** tests first (to populate tokens)
3. **Run CRUD tests** for each entity
4. **Run Workflow tests** (create → assign → complete → rate)
5. **Run Statistics** to verify metrics
6. **Run Error cases** to test validation

### Run All Tests
In Postman, use the Collection Runner:
1. Click "Run" on the collection
2. Select "Rescue Management API - Test Cases"
3. Click "Run Rescue Management API"
4. View test results

---

## Key Test Data

### Admin Accounts
| Email | Password | Role |
|-------|----------|------|
| admin@example.com | admin123 | Admin |
| operator1@example.com | op123 | Operator |

### Users
| Email | Password |
|-------|----------|
| huong1@example.com | user123 |
| hung1@example.com | user123 |
| hoa1@example.com | user123 |

### Incident Types
- 1: Cháy (Fire)
- 2: Đuối nước (Drowning)
- 3: Tai nạn giao thông (Traffic accident)
- 4: Sập nhà (Building collapse)

### Teams
- Q1: Đội Cứu Hộ Q1
- Q3: Đội Cứu Hộ Q3
- Q5: Đội Cứu Hộ Q5
- Q7: Đội Cứu Hộ Q7

---

## Notes
- All timestamps should be in ISO 8601 format
- Latitude/Longitude are in HCMC area (around 10.77°N, 106.70°E)
- Status values: CHO_XU_LY, DANG_XU_LY, HOAN_THANH, HUY_BO
- Urgency levels: LOW, MEDIUM, HIGH, CRITICAL
- Ratings: 1-5 stars
