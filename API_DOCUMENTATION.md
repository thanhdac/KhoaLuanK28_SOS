# API Documentation - Hệ Thống Quản Lý Cứu Hộ Khẩn Cấp

## Base URL
```
http://localhost:8000/api
```

## ============================================
## PHÂN QUYỀN & QUẢN TRỊ (ADMIN & PERMISSIONS)
## ============================================

### 1. Chức Năng (Functions/Features)
```
GET    /api/chuc-nang              - Danh sách tất cả chức năng
POST   /api/chuc-nang              - Tạo chức năng mới
GET    /api/chuc-nang/{id}         - Chi tiết chức năng
PUT    /api/chuc-nang/{id}         - Cập nhật chức năng
DELETE /api/chuc-nang/{id}         - Xóa chức năng
```

### 2. Chức Vụ (Positions/Roles)
```
GET    /api/chuc-vu                - Danh sách tất cả chức vụ
POST   /api/chuc-vu                - Tạo chức vụ mới
GET    /api/chuc-vu/{id}           - Chi tiết chức vụ
PUT    /api/chuc-vu/{id}           - Cập nhật chức vụ
DELETE /api/chuc-vu/{id}           - Xóa chức vụ
```

### 3. Admin Management
```
GET    /api/admin                  - Danh sách admin
POST   /api/admin                  - Tạo admin mới
GET    /api/admin/{id}             - Chi tiết admin
PUT    /api/admin/{id}             - Cập nhật admin
DELETE /api/admin/{id}             - Xóa admin
```

## ============================================
## NGƯỜI DÙNG (USER MANAGEMENT)
## ============================================

### User Registration & Authentication
```
POST   /api/nguoi-dung/register    - Đăng ký người dùng mới
POST   /api/nguoi-dung/login       - Đăng nhập
POST   /api/nguoi-dung/logout      - Đăng xuất (Require Auth)
```

### User CRUD
```
GET    /api/nguoi-dung             - Danh sách người dùng
POST   /api/nguoi-dung             - Tạo người dùng
GET    /api/nguoi-dung/{id}        - Chi tiết người dùng
PUT    /api/nguoi-dung/{id}        - Cập nhật thông tin người dùng
DELETE /api/nguoi-dung/{id}        - Xóa người dùng
```

## ============================================
## PHÂN LOẠI SỰ CỐ (INCIDENT TYPES)
## ============================================

### Loại Sự Cố
```
GET    /api/loai-su-co             - Danh sách loại sự cố
POST   /api/loai-su-co             - Tạo loại sự cố mới
GET    /api/loai-su-co/{id}        - Chi tiết loại sự cố
PUT    /api/loai-su-co/{id}        - Cập nhật loại sự cố
DELETE /api/loai-su-co/{id}        - Xóa loại sự cố
GET    /api/loai-su-co/{id}/chi-tiet - Danh sách chi tiết của loại sự cố
```

## ============================================
## YÊU CẦU CỨU HỘ (HELP REQUESTS)
## ============================================

### CRUD Operations
```
GET    /api/yeu-cau-cuu-ho              - Danh sách tất cả yêu cầu
POST   /api/yeu-cau-cuu-ho              - Tạo yêu cầu cứu hộ mới
GET    /api/yeu-cau-cuu-ho/{id}         - Chi tiết yêu cầu
PUT    /api/yeu-cau-cuu-ho/{id}         - Cập nhật yêu cầu
DELETE /api/yeu-cau-cuu-ho/{id}         - Xóa yêu cầu
```

### Advanced Operations
```
PUT    /api/yeu-cau-cuu-ho/{id}/trang-thai        - Cập nhật trạng thái xử lý
GET    /api/yeu-cau-cuu-ho/theo-trang-thai/{status} - Lọc theo trạng thái
GET    /api/yeu-cau-cuu-ho/theo-muc-do-khan-cap/{urgency} - Lọc theo mức cấp
GET    /api/yeu-cau-cuu-ho/{id}/phan-loai        - Lấy kết quả phân loại AI
GET    /api/yeu-cau-cuu-ho/{id}/hang-doi         - Lấy vị trí trong hàng đợi
POST   /api/phan-loai-ais/{id_yeu_cau}/tao-phan-loai - Tạo phân loại AI
GET    /api/phan-loai-ais/{id_yeu_cau}           - Lấy phân loại AI
```

### Status Constants
```
CHO_XU_LY       - Chờ xử lý
DANG_XU_LY      - Đang xử lý
HOAN_THANH      - Hoàn thành
HUY_BO          - Hủy bỏ
```

### Processing Queue
```
GET    /api/hang-doi-xu-ly         - Danh sách hàng đợi
GET    /api/hang-doi-xu-ly/theo-trang-thai/{status} - Lọc hàng đợi theo trạng thái
```

## ============================================
## ĐỘI CỨU HỘ (RESCUE TEAMS)
## ============================================

### CRUD Operations
```
GET    /api/doi-cuu-ho              - Danh sách đội cứu hộ
POST   /api/doi-cuu-ho              - Tạo đội cứu hộ mới
GET    /api/doi-cuu-ho/{id}         - Chi tiết đội
PUT    /api/doi-cuu-ho/{id}         - Cập nhật đội
DELETE /api/doi-cuu-ho/{id}         - Xóa đội
```

### Team Members
```
GET    /api/doi-cuu-ho/{id}/thanh-vien          - Danh sách thành viên
POST   /api/doi-cuu-ho/{id}/thanh-vien          - Thêm thành viên vào đội
DELETE /api/doi-cuu-ho/{id}/thanh-vien/{id_member} - Xóa thành viên khỏi đội
```

### Team Resources
```
GET    /api/doi-cuu-ho/{id}/tai-nguyen          - Danh sách tài nguyên
POST   /api/doi-cuu-ho/{id}/tai-nguyen          - Thêm tài nguyên
PUT    /api/doi-cuu-ho/{id}/tai-nguyen/{id_resource} - Cập nhật tài nguyên
```

### Base Locations
```
GET    /api/doi-cuu-ho/{id}/vi-tri             - Danh sách vị trí cứu hộ
POST   /api/doi-cuu-ho/{id}/vi-tri             - Thêm vị trí cứu hộ
```

### Capacity Management
```
GET    /api/doi-cuu-ho/{id}/nang-luc           - Lấy thông tin năng lực đội
PUT    /api/doi-cuu-ho/{id}/nang-luc           - Cập nhật năng lực đội
```

### Incident Type Handling
```
GET    /api/doi-cuu-ho/{id}/loai-su-co-dung-xu-ly  - Loại sự cố đội có thể xử lý
POST   /api/doi-cuu-ho/{id}/loai-su-co-dung-xu-ly  - Thêm loại sự cố
```

### Filters
```
GET    /api/doi-cuu-ho/theo-trang-thai/{status} - Lọc theo trạng thái (SAN_SANG, DANG_LT, etc)
GET    /api/doi-cuu-ho/theo-khu-vuc/{region}    - Lọc theo khu vực quản lý
```

## ============================================
## PHÂN CÔNG CỨU HỘ (TASK ASSIGNMENT)
## ============================================

### CRUD Operations
```
GET    /api/phan-cong-cuu-ho            - Danh sách phân công
POST   /api/phan-cong-cuu-ho            - Phân công nhiệm vụ mới
GET    /api/phan-cong-cuu-ho/{id}       - Chi tiết phân công
PUT    /api/phan-cong-cuu-ho/{id}       - Cập nhật phân công
DELETE /api/phan-cong-cuu-ho/{id}       - Hủy phân công
```

### Advanced Operations
```
PUT    /api/phan-cong-cuu-ho/{id}/trang-thai - Cập nhật trạng thái nhiệm vụ
GET    /api/phan-cong-cuu-ho/theo-yeu-cau/{id_yeu_cau} - Danh sách phân công cho yêu cầu
GET    /api/phan-cong-cuu-ho/theo-doi/{id_doi}         - Danh sách phân công cho đội
GET    /api/phan-cong-cuu-ho/theo-trang-thai/{status}  - Lọc theo trạng thái
```

### Response Results
```
POST   /api/ket-qua-cuu-ho/phan-cong/{id_phan_cong}  - Tạo kết quả cho phân công
GET    /api/ket-qua-cuu-ho/phan-cong/{id_phan_cong}  - Lấy kết quả của phân công
GET    /api/ket-qua-cuu-ho/{id}                      - Xem kết quả
PUT    /api/ket-qua-cuu-ho/{id}                      - Cập nhật kết quả
```

## ============================================
## ĐÁNH GIÁ CỨU HỘ (RATINGS/REVIEWS)
## ============================================

```
GET    /api/danh-gia-cuu-ho                     - Danh sách đánh giá
POST   /api/danh-gia-cuu-ho                     - Đánh giá yêu cầu
GET    /api/danh-gia-cuu-ho/{id}                - Chi tiết đánh giá
GET    /api/danh-gia-cuu-ho/yeu-cau/{id}        - Danh sách đánh giá cho yêu cầu
POST   /api/danh-gia-cuu-ho/yeu-cau/{id}        - Đánh giá cho yêu cầu cụ thể
```

## ============================================
## THỐNG KÊ & PHÂN TÍCH (STATISTICS & ANALYTICS)
## ============================================

```
GET    /api/thong-ke/tong-so-yeu-cau              - Tổng số yêu cầu
GET    /api/thong-ke/yeu-cau-theo-loai            - Phân bổ theo loại sự cố
GET    /api/thong-ke/yeu-cau-theo-muc-do-khan-cap - Phân bổ theo mức độ cấp bách
GET    /api/thong-ke/trang-thai-xu-ly             - Thống kê trạng thái xử lý
GET    /api/thong-ke/hieu-suat-doi-cuu-ho         - Hiệu suất từng đội
GET    /api/thong-ke/danh-sach-doi-co-san         - Danh sách đội có sẵn
GET    /api/thong-ke/heatmap                      - Dữ liệu heatmap vùng nguy hiểm
```

## ============================================
## TÌM KIẾM (SEARCH)
## ============================================

```
GET    /api/tim-kiem/yeu-cau?q=search_term    - Tìm kiếm yêu cầu
GET    /api/tim-kiem/doi-cuu-ho?q=search_term - Tìm kiếm đội cứu hộ
```

## ============================================
## HEALTH CHECK
## ============================================

```
GET    /api/health    - Kiểm tra trạng thái API
```

## Response Format

### Success Response (200 OK)
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "..."
  },
  "message": "Operation successful"
}
```

### List Response (200 OK)
```json
{
  "success": true,
  "data": [
    { "id": 1, "name": "..." },
    { "id": 2, "name": "..." }
  ],
  "meta": {
    "total": 100,
    "current_page": 1,
    "per_page": 15,
    "last_page": 7
  }
}
```

### Error Response (400/422/500)
```json
{
  "success": false,
  "errors": {
    "field_name": ["Error message"]
  },
  "message": "Validation failed"
}
```

## Pagination
All list endpoints support pagination:
```
GET /api/endpoint?page=1&per_page=15
```

## Filtering & Sorting
All list endpoints support filtering and sorting:
```
GET /api/endpoint?sort=created_at&order=desc&status=active
```

## Request Headers
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}  (if authenticated)
```

## Status Codes
- `200 OK` - Request successful
- `201 Created` - Resource created
- `204 No Content` - Resource deleted
- `400 Bad Request` - Invalid request
- `401 Unauthorized` - Authentication required
- `403 Forbidden` - Permission denied
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation error
- `500 Internal Server Error` - Server error

## Example Requests

### Create Help Request
```bash
POST /api/yeu-cau-cuu-ho
Content-Type: application/json

{
  "id_nguoi_dung": 1,
  "id_loai_su_co": 1,
  "vi_tri_lat": 10.7769,
  "vi_tri_lng": 106.7009,
  "vi_tri_dia_chi": "123 Đường Lê Lợi, Q1, TPHCM",
  "chi_tiet": "Chi tiết sự cố",
  "muc_do_khan_cap": "HIGH"
}
```

### Assign Task to Team
```bash
POST /api/phan-cong-cuu-ho
Content-Type: application/json

{
  "id_yeu_cau": 1,
  "id_doi_cuu_ho": 2,
  "id_chi_tiet_su_co": 3,
  "mo_ta": "Mô tả nhiệm vụ"
}
```

### Create Team
```bash
POST /api/doi-cuu-ho
Content-Type: application/json

{
  "ten_co": "Đội Cứu Hộ 1",
  "khu_vuc_quan_ly": "Q1",
  "so_dien_thoai_hotline": "0123456789",
  "vi_tri_lat": 10.7769,
  "vi_tri_lng": 106.7009,
  "mo_ta": "Đội cứu hộ chuyên trách..."
}
```
