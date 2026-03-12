# HỆ THỐNG QUẢN LÝ CỨU HỘ KHẨN CẤP - TÓMALTHOUGHM DỰ ÁN

## 📊 TÓMALTHOUGHM HOÀN VỀ PHÁT TRIỂN

### 1. DATABASE SETUP ✅
- **20 Migration Files** - Tất cả các bảng database đã được tạo
- **Relationships** - Tất cả foreign keys và constraints đã được cấu hình
- **Status**: Migrations chạy thành công, database k28_be đã được khởi tạo

### 2. MODELS ✅
- **22 Eloquent Models** - Tất cả các model đã được tạo
- **Relationships** - Tất cả relationships đã được cấu hình:
  - belongsTo, hasMany, belongsToMany relationships
  - Eager loading support
  - Model relationships: ChucNang, ChucVu, Admin, NguoiDung, LoaiSuCo, ChiTietLoaiSuCo, YeuCauCuuHo, PhanLoaiAis, TrongSoPhanLoai, HangDoiXuLy, DoiCuuHo, NangLucDoi, ThanhVienDoi, TaiNguyenCuuHo, ViTriDoiCuuHo, PhanCongCuuHo, KetQuaCuuHo, DanhGiaCuuHo, DuLieuHeatmap

### 3. API ROUTES ✅
- **50+ API Endpoints** - Tất cả các endpoint đã được định nghĩa
- **Structured** - Routes được tổ chức theo module:
  - /api/chuc-nang - Quản lý chức năng
  - /api/chuc-vu - Quản lý chức vụ
  - /api/admin - Quản lý admin
  - /api/nguoi-dung - Quản lý người dùng + auth
  - /api/loai-su-co - Phân loại sự cố
  - /api/yeu-cau-cuu-ho - Yêu cầu cứu hộ (chính)
  - /api/doi-cuu-ho - Đội cứu hộ
  - /api/phan-cong-cuu-ho - Phân công nhiệm vụ
  - /api/ket-qua-cuu-ho - Kết quả xử lý
  - /api/danh-gia-cuu-ho - Đánh giá
  - /api/thong-ke/* - Thống kê & phân tích
  - /api/tim-kiem/* - Tìm kiếm

### 4. CONTROLLERS ✅
- **10 Controllers** - Tất cả các controller đã được tạo
- **YeuCauCuuHoController** - HOÀN TOÀN ĐƯỢC IMPLEMENT (869 lines):
  - ✅ CRUD: index, store, show, update, destroy
  - ✅ Status management: getByStatus, updateStatus
  - ✅ Urgency filtering: getByUrgency
  - ✅ Processing queue: getHangDoiXuLy, getHangDoiByStatus
  - ✅ AI Classification: getPhanLoaiAis, createPhanLoaiAis, getPhanLoai
  - ✅ Heatmap data: getHeatmapData
  - ✅ Search: search (advanced filtering)
  - ✅ Statistics: getTotalRequests, getRequestsByType, getRequestsByUrgency, getProcessingStatus
  - ✅ Validation & Error Handling
  - ✅ Eager Loading & Performance Optimization

### 5. DOCUMENTATION ✅
- **API_DOCUMENTATION.md** - Tài liệu API với ví dụ đầy đủ
- **Endpoints** - Tất cả 50+ endpoints được tài liệu hóa
- **Request/Response** - Format rõ ràng với ví dụ
- **Status Codes** - Mô tả HTTP status codes
- **Filter & Pagination** - Làm rõ cách sử dụng

## 🏗️ KIẾN TRÚC DATABASE

### Core Tables (Bảng Chính):
1. **chuc_nang** - Chức năng (Functions)
2. **chuc_vu** - Chức vụ (Positions)
3. **admin** - Quản trị viên
4. **phan_quyen** - Phân quyền

### User & Request Tables:
5. **nguoi_dung** - Người dùng
6. **loai_su_co** - Loại sự cố
7. **chi_tiet_loai_su_co** - Chi tiết loại sự cố
8. **yeu_cau_cuu_ho** - Yêu cầu cứu hộ (chính)

### Processing Tables:
9. **hang_doi_xu_ly** - Hàng đợi xử lý
10. **phan_loai_ais** - Phân loại AI
11. **trong_so_phan_loai** - Trọng số phân loại

### Rescue Team Tables:
12. **doi_cuu_ho** - Đội cứu hộ
13. **thanh_vien_doi** - Thành viên đội
14. **nang_luc_doi** - Năng lực đội
15. **tai_nguyen_cuu_ho** - Tài nguyên cứu hộ
16. **vi_tri_doi_cuu_ho** - Vị trí đội
17. **doi_cuu_ho_loai_su_co** - Loại sự cố mà đội xử lý

### Assignment & Results Tables:
18. **phan_cong_cuu_ho** - Phân công nhiệm vụ
19. **ket_qua_cuu_ho** - Kết quả xử lý
20. **danh_gia_cuu_ho** - Đánh giá

### Analytics:
21. **du_lieu_heatmap** - Dữ liệu heatmap

## 🔌 API ENDPOINTS

### Phân Quyền & Quản Trị
```
GET    /api/chuc-nang           - Danh sách chức năng
POST   /api/chuc-nang           - Tạo chức năng
PUT    /api/chuc-nang/{id}      - Cập nhật chức năng
DELETE /api/chuc-nang/{id}      - Xóa chức năng

GET    /api/chuc-vu             - Danh sách chức vụ
POST   /api/chuc-vu             - Tạo chức vụ
PUT    /api/chuc-vu/{id}        - Cập nhật chức vụ
DELETE /api/chuc-vu/{id}        - Xóa chức vụ
```

### Người Dùng
```
POST   /api/nguoi-dung/register - Đăng ký
POST   /api/nguoi-dung/login    - Đăng nhập
POST   /api/nguoi-dung/logout   - Đăng xuất
GET    /api/nguoi-dung          - Danh sách người dùng
POST   /api/nguoi-dung          - Tạo người dùng
PUT    /api/nguoi-dung/{id}     - Cập nhật người dùng
DELETE /api/nguoi-dung/{id}     - Xóa người dùng
```

### Yêu Cầu Cứu Hộ (Main)
```
GET    /api/yeu-cau-cuu-ho                                    - Danh sách
POST   /api/yeu-cau-cuu-ho                                    - Tạo yêu cầu
GET    /api/yeu-cau-cuu-ho/{id}                               - Chi tiết
PUT    /api/yeu-cau-cuu-ho/{id}                               - Cập nhật
DELETE /api/yeu-cau-cuu-ho/{id}                               - Xóa

PUT    /api/yeu-cau-cuu-ho/{id}/trang-thai                    - Cập nhật trạng thái
GET    /api/yeu-cau-cuu-ho/theo-trang-thai/{status}           - Lọc theo trạng thái
GET    /api/yeu-cau-cuu-ho/theo-muc-do-khan-cap/{urgency}     - Lọc theo mức cấp
GET    /api/yeu-cau-cuu-ho/{id}/phan-loai                     - Lấy phân loại AI
GET    /api/yeu-cau-cuu-ho/{id}/hang-doi                      - Vị trí hàng đợi
POST   /api/phan-loai-ais/{id}/tao-phan-loai                  - Tạo phân loại AI
GET    /api/hang-doi-xu-ly                                    - Hàng đợi
GET    /api/hang-doi-xu-ly/theo-trang-thai/{status}           - Hàng đợi theo trạng thái
```

### Đội Cứu Hộ
```
GET    /api/doi-cuu-ho                                          - Danh sách đội
POST   /api/doi-cuu-ho                                          - Tạo đội
GET    /api/doi-cuu-ho/{id}                                     - Chi tiết đội
PUT    /api/doi-cuu-ho/{id}                                     - Cập nhật đội
DELETE /api/doi-cuu-ho/{id}                                     - Xóa đội

GET    /api/doi-cuu-ho/{id}/thanh-vien                         - Thành viên
POST   /api/doi-cuu-ho/{id}/thanh-vien                         - Thêm thành viên
DELETE /api/doi-cuu-ho/{id}/thanh-vien/{id_member}             - Xóa thành viên

GET    /api/doi-cuu-ho/{id}/tai-nguyen                         - Tài nguyên
POST   /api/doi-cuu-ho/{id}/tai-nguyen                         - Thêm tài nguyên
PUT    /api/doi-cuu-ho/{id}/tai-nguyen/{id_resource}           - Cập nhật tài nguyên

GET    /api/doi-cuu-ho/{id}/vi-tri                             - Vị trí
POST   /api/doi-cuu-ho/{id}/vi-tri                             - Thêm vị trí

GET    /api/doi-cuu-ho/{id}/nang-luc                           - Năng lực
PUT    /api/doi-cuu-ho/{id}/nang-luc                           - Cập nhật năng lực

GET    /api/doi-cuu-ho/theo-trang-thai/{status}                - Lọc theo trạng thái
GET    /api/doi-cuu-ho/theo-khu-vuc/{region}                   - Lọc theo khu vực
```

### Phân Công & Kết Quả
```
GET    /api/phan-cong-cuu-ho                                  - Danh sách phân công
POST   /api/phan-cong-cuu-ho                                  - Phân công mới
GET    /api/phan-cong-cuu-ho/{id}                             - Chi tiết
PUT    /api/phan-cong-cuu-ho/{id}                             - Cập nhật
DELETE /api/phan-cong-cuu-ho/{id}                             - Hủy phân công

PUT    /api/phan-cong-cuu-ho/{id}/trang-thai                  - Cập nhật trạng thái
GET    /api/phan-cong-cuu-ho/theo-yeu-cau/{id}                - Lọc theo yêu cầu
GET    /api/phan-cong-cuu-ho/theo-doi/{id}                    - Lọc theo đội

POST   /api/ket-qua-cuu-ho/phan-cong/{id}                     - Tạo kết quả
GET    /api/ket-qua-cuu-ho/phan-cong/{id}                     - Lấy kết quả
GET    /api/ket-qua-cuu-ho/{id}                               - Chi tiết
PUT    /api/ket-qua-cuu-ho/{id}                               - Cập nhật
```

### Thống Kê
```
GET    /api/thong-ke/tong-so-yeu-cau                          - Tổng số yêu cầu
GET    /api/thong-ke/yeu-cau-theo-loai                        - Theo loại sự cố
GET    /api/thong-ke/yeu-cau-theo-muc-do-khan-cap             - Theo mức cấp
GET    /api/thong-ke/trang-thai-xu-ly                         - Trạng thái xử lý
GET    /api/thong-ke/hieu-suat-doi-cuu-ho                     - Hiệu suất đội
GET    /api/thong-ke/danh-sach-doi-co-san                     - Đội có sẵn
GET    /api/thong-ke/heatmap                                  - Heatmap dữ liệu
```

### Tìm Kiếm
```
GET    /api/tim-kiem/yeu-cau?q=search_term                    - Tìm yêu cầu
GET    /api/tim-kiem/doi-cuu-ho?q=search_term                 - Tìm đội
```

## 🚀 CÁC FEATURES ĐÃ IMPLEMENT

### ✅ YeuCauCuuHoController (869 lines)
- [x] CRUD Operations
- [x] Status Management
- [x] Urgency Filtering
- [x] AI Classification
- [x] Processing Queue Management
- [x] Heatmap Data Generation
- [x] Advanced Search
- [x] Statistics & Reporting
- [x] Pagination & Sorting
- [x] Validation & Error Handling
- [x] Eager Loading Optimization

### ⏳ Remaining Controllers (Backend Logic Ready)
- [ ] ChucNangController - Ready to implement
- [ ] ChucVuController - Ready to implement
- [ ] AdminController - Ready to implement
- [ ] NguoiDungController - Ready to implement
- [ ] LoaiSuCoController - Ready to implement
- [ ] DoiCuuHoController - Ready to implement
- [ ] PhanCongCuuHoController - Ready to implement
- [ ] KetQuaCuuHoController - Ready to implement
- [ ] DanhGiaCuuHoController - Ready to implement

## 📝 CÁCH SỬ DỤNG

### 1. Chạy Migrations
```bash
php artisan migrate
```

### 2. Khởi động Server
```bash
php artisan serve
```

### 3. Test API
```bash
# Create a help request
curl -X POST http://localhost:8000/api/yeu-cau-cuu-ho \
  -H "Content-Type: application/json" \
  -d '{
    "id_nguoi_dung": 1,
    "id_loai_su_co": 1,
    "vi_tri_lat": 10.7769,
    "vi_tri_lng": 106.7009,
    "vi_tri_dia_chi": "123 Đường Lê Lợi, Q1, TPHCM",
    "chi_tiet": "Chi tiết sự cố",
    "muc_do_khan_cap": "HIGH"
  }'

# Get all help requests
curl -X GET http://localhost:8000/api/yeu-cau-cuu-ho

# Get statistics
curl -X GET http://localhost:8000/api/thong-ke/tong-so-yeu-cau
```

### 4. Pagination Example
```bash
# Get page 2 with 20 items per page
curl -X GET "http://localhost:8000/api/yeu-cau-cuu-ho?page=2&per_page=20"
```

### 5. Filtering Example
```bash
# Get high urgency requests
curl -X GET "http://localhost:8000/api/yeu-cau-cuu-ho/theo-muc-do-khan-cap/5"

# Get processing requests
curl -X GET "http://localhost:8000/api/yeu-cau-cuu-ho/theo-trang-thai/DANG_XU_LY"
```

## 📦 FILES CREATED/MODIFIED

### Migrations (20 files)
- ✅ database/migrations/2026_03_12_*_create_*.php

### Models (22 files)
- ✅ app/Models/ChucNang.php
- ✅ app/Models/ChucVu.php
- ✅ app/Models/Admin.php
- ✅ app/Models/PhanQuyen.php
- ✅ app/Models/NguoiDung.php
- ✅ app/Models/LoaiSuCo.php
- ✅ app/Models/ChiTietLoaiSuCo.php
- ✅ app/Models/YeuCauCuuHo.php
- ✅ app/Models/PhanLoaiAis.php
- ✅ app/Models/TrongSoPhanLoai.php
- ✅ app/Models/HangDoiXuLy.php
- ✅ app/Models/DoiCuuHo.php
- ✅ app/Models/DoiCuuHoLoaiSuCo.php
- ✅ app/Models/NangLucDoi.php
- ✅ app/Models/ThanhVienDoi.php
- ✅ app/Models/TaiNguyenCuuHo.php
- ✅ app/Models/ViTriDoiCuuHo.php
- ✅ app/Models/PhanCongCuuHo.php
- ✅ app/Models/KetQuaCuuHo.php
- ✅ app/Models/DanhGiaCuuHo.php
- ✅ app/Models/DuLieuHeatmap.php

### Routes
- ✅ routes/api.php (50+ endpoints)
- ✅ bootstrap/app.php (API routing configuration)

### Controllers (10 files)
- ✅ app/Http/Controllers/ChucNangController.php
- ✅ app/Http/Controllers/ChucVuController.php
- ✅ app/Http/Controllers/AdminController.php
- ✅ app/Http/Controllers/NguoiDungController.php
- ✅ app/Http/Controllers/LoaiSuCoController.php
- ✅ app/Http/Controllers/YeuCauCuuHoController.php (FULLY IMPLEMENTED - 869 lines)
- ✅ app/Http/Controllers/DoiCuuHoController.php
- ✅ app/Http/Controllers/PhanCongCuuHoController.php
- ✅ app/Http/Controllers/KetQuaCuuHoController.php
- ✅ app/Http/Controllers/DanhGiaCuuHoController.php

### Documentation
- ✅ API_DOCUMENTATION.md

## ⚙️ NEXT STEPS

1. **Implement Remaining Controllers**
   - Follow the pattern from YeuCauCuuHoController
   - Use same validation and error handling approach
   - Add relationships and eager loading

2. **Add Authentication**
   - Implement Laravel Sanctum
   - Add middleware for protected routes
   - Role-based access control (RBAC)

3. **Add Middleware**
   - Request validation middleware
   - Rate limiting
   - CORS configuration

4. **Testing**
   - Write unit tests
   - Write feature tests
   - Create test database seeder

5. **Deployment**
   - Setup production environment
   - Configure database
   - Setup monitoring and logging

## 📞 API SUPPORT

All endpoints return consistent JSON responses:
- Success: `{"success": true, "data": {...}, "message": "..."}`
- Error: `{"success": false, "errors": {...}, "message": "..."}`
- List: `{"success": true, "data": [...], "meta": {...}}`

Status Codes:
- 200: Success
- 201: Created
- 204: No Content
- 400: Bad Request
- 404: Not Found
- 422: Validation Error
- 500: Server Error
