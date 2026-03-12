# QUICK START GUIDE - Hệ Thống Quản Lý Cứu Hộ Khẩn Cấp

## 🚀 CẤU HÌNH BAN ĐẦU

### 1. Kiểm tra cấu hình Database
```bash
# File: .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=k28_be
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Chạy Migrations (Tạo bảng database)
```bash
cd /d/Khoa_Luan_K28/K28_BE
php artisan migrate --force
```

### 3. Khởi động API Server
```bash
php artisan serve
# API chạy tại: http://localhost:8000
```

### 4. Kiểm tra Health
```bash
curl http://localhost:8000/api/health
```

## 📚 CÁC ENDPOINT CHÍNH

### A. YÊU CẦU CỨU HỘ (Help Requests) - Core Feature

#### 1. Tạo yêu cầu cứu hộ mới
```bash
POST http://localhost:8000/api/yeu-cau-cuu-ho
Content-Type: application/json

{
  "id_nguoi_dung": 1,
  "id_loai_su_co": 1,
  "vi_tri_lat": 10.7769,
  "vi_tri_lng": 106.7009,
  "vi_tri_dia_chi": "123 Đường Lê Lợi, Q1, TPHCM",
  "chi_tiet": "Cháy nhà tầng 3",
  "mo_ta": "Chi tiết sự cố",
  "muc_do_khan_cap": "5"
}

✅ Response (201 Created):
{
  "success": true,
  "data": {
    "id_yeu_cau": 1,
    "id_nguoi_dung": 1,
    "id_loai_su_co": 1,
    "trang_thai": "CHO_XU_LY",
    "created_at": "2026-03-12T10:00:00Z"
  }
}
```

#### 2. Lấy danh sách yêu cầu
```bash
GET http://localhost:8000/api/yeu-cau-cuu-ho?page=1&per_page=15

✅ Response (200 OK):
{
  "success": true,
  "data": [
    {
      "id_yeu_cau": 1,
      "ho_ten_nguoi_dung": "Nguyễn Văn A",
      "ten_loai_su_co": "Cháy",
      "muc_do_khan_cap": 5,
      "trang_thai": "CHO_XU_LY",
      "created_at": "2026-03-12T10:00:00Z"
    }
  ],
  "meta": {
    "total": 100,
    "current_page": 1,
    "per_page": 15,
    "last_page": 7
  }
}
```

#### 3. Lọc yêu cầu theo mức độ cấp bách
```bash
GET http://localhost:8000/api/yeu-cau-cuu-ho/theo-muc-do-khan-cap/5

# Hoặc tất cả yêu cầu có mức độ cấp bách = 5 (HIGH)
```

#### 4. Lọc yêu cầu theo trạng thái
```bash
GET http://localhost:8000/api/yeu-cau-cuu-ho/theo-trang-thai/DANG_XU_LY

# Trạng thái có thể: CHO_XU_LY, DANG_XU_LY, HOAN_THANH, HUY_BO
```

#### 5. Cập nhật trạng thái yêu cầu
```bash
PUT http://localhost:8000/api/yeu-cau-cuu-ho/1/trang-thai
Content-Type: application/json

{
  "trang_thai": "DANG_XU_LY"
}
```

#### 6. Xem hàng đợi xử lý
```bash
GET http://localhost:8000/api/hang-doi-xu-ly

# Trả về danh sách yêu cầu sắp xếp theo ưu tiên
```

### B. ĐỘI CỨU HỘ (Rescue Teams)

#### 1. Tạo đội cứu hộ mới
```bash
POST http://localhost:8000/api/doi-cuu-ho
Content-Type: application/json

{
  "ten_co": "Đội Cứu Hộ 1",
  "khu_vuc_quan_ly": "Q1",
  "so_dien_thoai_hotline": "0123456789",
  "vi_tri_lat": 10.7769,
  "vi_tri_lng": 106.7009,
  "mo_ta": "Đội cứu hộ Q1"
}
```

#### 2. Lấy danh sách đội
```bash
GET http://localhost:8000/api/doi-cuu-ho
```

#### 3. Thêm thành viên vào đội
```bash
POST http://localhost:8000/api/doi-cuu-ho/1/thanh-vien
Content-Type: application/json

{
  "ho_ten": "Nguyễn Văn B",
  "so_dien_thoai": "0987654321",
  "email": "b@example.com",
  "vai_tro_trong_doi": "Team Leader"
}
```

#### 4. Thêm tài nguyên cho đội
```bash
POST http://localhost:8000/api/doi-cuu-ho/1/tai-nguyen
Content-Type: application/json

{
  "ten_tai_nguyen": "Xe cứu hộ",
  "loai_tai_nguyen": "Vehicle",
  "so_luong": 2
}
```

#### 5. Lọc đội có sẵn
```bash
GET http://localhost:8000/api/doi-cuu-ho/theo-trang-thai/SAN_SANG
```

#### 6. Lọc đội theo khu vực
```bash
GET http://localhost:8000/api/doi-cuu-ho/theo-khu-vuc/Q1
```

### C. PHÂN CÔNG & PHÂN LOẠI

#### 1. Phân công nhiệm vụ
```bash
POST http://localhost:8000/api/phan-cong-cuu-ho
Content-Type: application/json

{
  "id_yeu_cau": 1,
  "id_doi_cuu_ho": 2,
  "mo_ta": "Phân công xử lý sự cố"
}
```

#### 2. Cập nhật trạng thái phân công
```bash
PUT http://localhost:8000/api/phan-cong-cuu-ho/1/trang-thai
Content-Type: application/json

{
  "trang_thai_nhiem_vu": "DANG_XU_LY"
}
```

#### 3. Tạo kết quả xử lý
```bash
POST http://localhost:8000/api/ket-qua-cuu-ho/phan-cong/1
Content-Type: application/json

{
  "bao_cao_hien_truong": "Tiếp cận khu vực bị cố.Kiểm soát tình hình...",
  "trang_thai_ket_qua": "HOAN_THANH"
}
```

### D. THỐNG KÊ & PHÂN TÍCH

#### 1. Tổng số yêu cầu
```bash
GET http://localhost:8000/api/thong-ke/tong-so-yeu-cau

✅ Response:
{
  "total": 100,
  "new": 30,
  "processing": 40,
  "completed": 25,
  "cancelled": 5
}
```

#### 2. Phân bổ theo loại sự cố
```bash
GET http://localhost:8000/api/thong-ke/yeu-cau-theo-loai

✅ Response:
{
  "data": [
    {"type": "Cháy", "count": 25},
    {"type": "Đuối nước", "count": 15},
    {"type": "Tai nạn giao thông", "count": 20}
  ]
}
```

#### 3. Phân bổ theo mức độ cấp bách
```bash
GET http://localhost:8000/api/thong-ke/yeu-cau-theo-muc-do-khan-cap

✅ Response:
{
  "1": 5,
  "2": 10,
  "3": 15,
  "4": 30,
  "5": 40
}
```

#### 4. Trạng thái xử lý
```bash
GET http://localhost:8000/api/thong-ke/trang-thai-xu-ly

✅ Response:
{
  "total": 100,
  "new": 30,
  "processing": 40,
  "completed": 25,
  "cancelled": 5,
  "completion_rate": 0.25,
  "avg_urgency": 3.8,
  "avg_affected_people": 2.5
}
```

#### 5. Hiệu suất đội cứu hộ
```bash
GET http://localhost:8000/api/thong-ke/hieu-suat-doi-cuu-ho

✅ Response:
{
  "data": [
    {
      "team_id": 1,
      "team_name": "Đội 1",
      "completed_tasks": 50,
      "in_progress": 10,
      "efficiency_rate": 0.83
    }
  ]
}
```

#### 6. Dữ liệu Heatmap
```bash
GET http://localhost:8000/api/thong-ke/heatmap

✅ Response:
{
  "data": [
    {
      "lat": 10.7769,
      "lng": 106.7009,
      "density": 10,
      "risk_level": 8.5
    }
  ]
}
```

### E. TÌM KIẾM

#### 1. Tìm yêu cầu nâng cao
```bash
GET "http://localhost:8000/api/tim-kiem/yeu-cau?q=fire&status=DANG_XU_LY&priority_from=3&priority_to=5"

Tham số:
- q: Từ khóa tìm kiếm
- status: Trạng thái
- urgency: Mức độ cấp bách
- type: Loại sự cố
- priority_from: Mức ưu tiên từ
- priority_to: Mức ưu tiên đến
- affected_from: Số người ảnh hưởng từ
- affected_to: Số người ảnh hưởng đến
- date_from: Ngày bắt đầu
-date_to: Ngày kết thúc
```

#### 2. Tìm đội
```bash
GET "http://localhost:8000/api/tim-kiem/doi-cuu-ho?q=Q1&status=available"
```

## 🔑 KEY FEATURES

### ✅ YeuCauCuuHoController Implementation
- Full CRUD operations
- Status management
- Urgency filtering
- AI Classification
- Processing queue
- Heatmap data
- Advanced search
- Statistics
- Pagination & sorting

### ✅ Models with Relationships
- All 22 models properly configured
- Eager loading support
- Foreign key constraints
- Relationship management

### ✅ API Routes (50+ endpoints)
- RESTful design
- Proper HTTP methods
- Consistent URL structure
- Pagination support

## 🎯 TESTING WORKFLOW

### Step 1: Create test data
```bash
# Create user
POST /api/nguoi-dung/register

# Create incident type
POST /api/loai-su-co

# Create rescue team
POST /api/doi-cuu-ho

# Create help request
POST /api/yeu-cau-cuu-ho
```

### Step 2: View data
```bash
# Get all data
GET /api/yeu-cau-cuu-ho
GET /api/doi-cuu-ho
```

### Step 3: Assign and process
```bash
# Assign task
POST /api/phan-cong-cuu-ho

# Update status
PUT /api/phan-cong-cuu-ho/{id}/trang-thai

# Add result
POST /api/ket-qua-cuu-ho/phan-cong/{id}
```

### Step 4: View statistics
```bash
# Get stats
GET /api/thong-ke/*
```

## 📖 DOCUMENTATION FILES

1. **API_DOCUMENTATION.md** - Tài liệu API đầy đủ
2. **PROJECT_SUMMARY.md** - Tóm tắt dự án
3. **QUICK_START_GUIDE.md** - Hướng dẫn này

## 🔗 RESOURCES

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [API Standards](https://restfulapi.net/)
- [Database Schema](./schema.md)

## 💡 TIPS

1. Sử dụng Postman hoặc Insomnia để test API
2. Kiểm tra .env file để đảm bảo cấu hình database đúng
3. Xem logs nếu có lỗi: `tail -f storage/logs/laravel.log`
4. Xóa cache nếu cần: `php artisan cache:clear`
5. Generate API docs: `php artisan scribe:generate`

## 🆘 TROUBLESHOOTING

### Database Connection Error
```
Solution: Check .env DB_* settings and MySQL server running
```

### CORS Error
```
Solution: Configure cors in config/cors.php
```

### Route Not Found
```
Solution: Run `php artisan route:list` to verify routes
```

### Model Error
```
Solution: Check model namespace in controller
```

---

**Ready to go! Start building your API now!** 🚀
