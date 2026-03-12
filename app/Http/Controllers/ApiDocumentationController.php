<?php

namespace App\Http\Controllers;

class ApiDocumentationController extends Controller
{
    /**
     * Display API documentation
     */
    public function index()
    {
        $docs = $this->getApiDocumentation();
        return view('api-documentation', ['docs' => $docs]);
    }

    /**
     * Get all API documentation
     */
    private function getApiDocumentation()
    {
        return [
            [
                'section' => 'PHÂN QUYỀN & QUẢN TRỊ',
                'description' => 'Quản lý chức năng, chức vụ và quyền hạn của hệ thống',
                'endpoints' => [
                    [
                        'name' => 'Chức Năng',
                        'baseUrl' => '/api/chuc-nang',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách tất cả các chức năng', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Tạo chức năng mới', 'params' => ['ten_chuc_nang' => 'Tên chức năng (bắt buộc)', 'mo_ta' => 'Mô tả chức năng']],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết một chức năng', 'params' => ['id' => 'ID chức năng (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '/{id}', 'description' => 'Cập nhật chức năng', 'params' => ['id' => 'ID chức năng (bắt buộc)', 'ten_chuc_nang' => 'Tên chức năng', 'mo_ta' => 'Mô tả']],
                            ['method' => 'DELETE', 'path' => '/{id}', 'description' => 'Xóa chức năng', 'params' => ['id' => 'ID chức năng (bắt buộc)']],
                        ]
                    ],
                    [
                        'name' => 'Chức Vụ',
                        'baseUrl' => '/api/chuc-vu',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách tất cả chức vụ', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Tạo chức vụ mới', 'params' => ['ten_chuc_vu' => 'Tên chức vụ (bắt buộc)', 'mo_ta' => 'Mô tả']],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết một chức vụ', 'params' => ['id' => 'ID chức vụ (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '/{id}', 'description' => 'Cập nhật chức vụ', 'params' => ['id' => 'ID chức vụ (bắt buộc)']],
                            ['method' => 'DELETE', 'path' => '/{id}', 'description' => 'Xóa chức vụ', 'params' => ['id' => 'ID chức vụ (bắt buộc)']],
                        ]
                    ],
                    [
                        'name' => 'Admin',
                        'baseUrl' => '/api/admin',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách tất cả adminlist', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Tạo admin mới', 'params' => ['id_nguoi_dung' => 'ID người dùng', 'id_chuc_vu' => 'ID chức vụ']],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết admin', 'params' => ['id' => 'ID admin (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '/{id}', 'description' => 'Cập nhật admin', 'params' => ['id' => 'ID admin (bắt buộc)']],
                            ['method' => 'DELETE', 'path' => '/{id}', 'description' => 'Xóa admin', 'params' => ['id' => 'ID admin (bắt buộc)']],
                        ]
                    ]
                ]
            ],
            [
                'section' => 'NGƯỜI DÙNG',
                'description' => 'Quản lý thông tin người dùng và xác thực',
                'endpoints' => [
                    [
                        'name' => 'Người Dùng',
                        'baseUrl' => '/api/nguoi-dung',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách tất cả người dùng', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang']],
                            ['method' => 'POST', 'path' => '/register', 'description' => 'Đăng ký người dùng mới', 'params' => ['ten_nguoi_dung' => 'Tên người dùng', 'email' => 'Email (bắt buộc)', 'so_dien_thoai' => 'Số điện thoại', 'mat_khau' => 'Mật khẩu (bắt buộc)', 'dia_chi' => 'Địa chỉ']],
                            ['method' => 'POST', 'path' => '/login', 'description' => 'Đăng nhập người dùng', 'params' => ['email' => 'Email (bắt buộc)', 'mat_khau' => 'Mật khẩu (bắt buộc)']],
                            ['method' => 'POST', 'path' => '/logout', 'description' => 'Đăng xuất người dùng (yêu cầu auth)', 'params' => []],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết người dùng', 'params' => ['id' => 'ID người dùng (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '/{id}', 'description' => 'Cập nhật thông tin người dùng', 'params' => ['id' => 'ID người dùng (bắt buộc)', 'ten_nguoi_dung' => 'Tên', 'so_dien_thoai' => 'Số điện thoại', 'dia_chi' => 'Địa chỉ']],
                            ['method' => 'DELETE', 'path' => '/{id}', 'description' => 'Xóa người dùng', 'params' => ['id' => 'ID người dùng (bắt buộc)']],
                        ]
                    ]
                ]
            ],
            [
                'section' => 'PHÂN LOẠI SỰ CỐ',
                'description' => 'Quản lý các loại sự cố và phân loại của hệ thống',
                'endpoints' => [
                    [
                        'name' => 'Loại Sự Cố',
                        'baseUrl' => '/api/loai-su-co',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách tất cả loại sự cố', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Tạo loại sự cố mới', 'params' => ['ten_loai_su_co' => 'Tên loại (bắt buộc)', 'mo_ta' => 'Mô tả', 'trang_thai' => 'hoat_dong / khong_hoat_dong']],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết loại sự cố', 'params' => ['id' => 'ID loại sự cố (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/{id}/chi-tiet', 'description' => 'Lấy chi tiết chi tiết loại sự cố', 'params' => ['id' => 'ID loại sự cố (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/theo-trang-thai/{trang_thai}', 'description' => 'Lọc loại sự cố theo trạng thái', 'params' => ['trang_thai' => 'hoat_dong / khong_hoat_dong (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/{id}/yeu-cau-cuu-ho', 'description' => 'Lấy danh sách yêu cầu cứu hộ theo loại sự cố', 'params' => ['id' => 'ID loại sự cố (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/{id}/doi-cuu-ho', 'description' => 'Lấy danh sách đội cứu hộ phù hợp với loại sự cố', 'params' => ['id' => 'ID loại sự cố (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/tim-kiem', 'description' => 'Tìm kiếm loại sự cố', 'params' => ['q' => 'Từ khóa tìm kiếm', 'per_page' => 'Số items mỗi trang']],
                            ['method' => 'PUT', 'path' => '/{id}', 'description' => 'Cập nhật loại sự cố', 'params' => ['id' => 'ID loại sự cố (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '/{id}/trang-thai', 'description' => 'Cập nhật trạng thái loại sự cố', 'params' => ['id' => 'ID loại sự cố (bắt buộc)', 'trang_thai' => 'Trạng thái mới']],
                            ['method' => 'DELETE', 'path' => '/{id}', 'description' => 'Xóa loại sự cố', 'params' => ['id' => 'ID loại sự cố (bắt buộc)']],
                        ]
                    ]
                ]
            ],
            [
                'section' => 'YÊU CẦU CỨU HỘ',
                'description' => 'Quản lý các yêu cầu cứu hộ, phân loại và xếp hàng xử lý',
                'endpoints' => [
                    [
                        'name' => 'Yêu Cầu Cứu Hộ',
                        'baseUrl' => '/api/yeu-cau-cuu-ho',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách tất cả yêu cầu cứu hộ', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang', 'sort_by' => 'Trường cần sắp xếp', 'sort_order' => 'asc / desc']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Tạo yêu cầu cứu hộ mới', 'params' => ['id_nguoi_dung' => 'ID người dùng (bắt buộc)', 'id_loai_su_co' => 'ID loại sự cố (bắt buộc)', 'vi_tri_lat' => 'Latitude (bắt buộc)', 'vi_tri_lng' => 'Longitude (bắt buộc)', 'vi_tri_dia_chi' => 'Địa chỉ (bắt buộc)', 'chi_tiet' => 'Chi tiết sự cố (bắt buộc)', 'mo_ta' => 'Mô tả', 'hinh_anh' => 'Đường dẫn hình ảnh', 'so_nguoi_bi_anh_huong' => 'Số người bị ảnh hưởng', 'muc_do_khan_cap' => 'Mức độ khẩn cấp (1-5)', 'diem_uu_tien' => 'Điểm ưu tiên']],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết yêu cầu cứu hộ', 'params' => ['id' => 'ID yêu cầu (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/{id}/phan-loai', 'description' => 'Lấy phân loại của yêu cầu', 'params' => ['id' => 'ID yêu cầu (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/theo-trang-thai/{trang_thai}', 'description' => 'Lọc yêu cầu theo trạng thái', 'params' => ['trang_thai' => 'moi / dang_xu_ly / da_hoan_thanh / da_huy']],
                            ['method' => 'GET', 'path' => '/theo-muc-do-khan-cap/{muc_do}', 'description' => 'Lọc yêu cầu theo mức độ khẩn cấp', 'params' => ['muc_do' => 'Mức độ (1-5)']],
                            ['method' => 'PUT', 'path' => '/{id}', 'description' => 'Cập nhật yêu cầu cứu hộ', 'params' => ['id' => 'ID yêu cầu (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '/{id}/trang-thai', 'description' => 'Cập nhật trạng thái yêu cầu', 'params' => ['id' => 'ID yêu cầu (bắt buộc)', 'trang_thai' => 'Trạng thái mới']],
                            ['method' => 'GET', 'path' => '/tim-kiem', 'description' => 'Tìm kiếm yêu cầu cứu hộ', 'params' => ['q' => 'Từ khóa tìm kiếm', 'per_page' => 'Số items mỗi trang']],
                            ['method' => 'DELETE', 'path' => '/{id}', 'description' => 'Xóa yêu cầu cứu hộ', 'params' => ['id' => 'ID yêu cầu (bắt buộc)']],
                        ]
                    ],
                    [
                        'name' => 'Hàng Đợi Xử Lý',
                        'baseUrl' => '/api/hang-doi-xu-ly',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy hàng đợi xử lý hiện tại', 'params' => []],
                            ['method' => 'GET', 'path' => '/theo-trang-thai/{trang_thai}', 'description' => 'Lấy hàng đợi theo trạng thái', 'params' => ['trang_thai' => 'doi_xu_ly / dang_xu_ly / da_hoan_thanh']],
                        ]
                    ],
                    [
                        'name' => 'Phân Loại AI',
                        'baseUrl' => '/api/phan-loai-ais',
                        'methods' => [
                            ['method' => 'GET', 'path' => '/{id_yeu_cau}', 'description' => 'Lấy phân loại AI cho yêu cầu', 'params' => ['id_yeu_cau' => 'ID yêu cầu (bắt buộc)']],
                            ['method' => 'POST', 'path' => '/{id_yeu_cau}/tao-phan-loai', 'description' => 'Tạo phân loại AI cho yêu cầu', 'params' => ['id_yeu_cau' => 'ID yêu cầu (bắt buộc)']],
                        ]
                    ]
                ]
            ],
            [
                'section' => 'ĐỘI CỨU HỘ',
                'description' => 'Quản lý các đội cứu hộ, thành viên, tài nguyên và năng lực',
                'endpoints' => [
                    [
                        'name' => 'Đội Cứu Hộ',
                        'baseUrl' => '/api/doi-cuu-ho',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách tất cả đội cứu hộ', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Tạo đội cứu hộ mới', 'params' => ['ten_doi' => 'Tên đội (bắt buộc)', 'khu_vuc' => 'Khu vực hoạt động', 'mo_ta' => 'Mô tả', 'trang_thai' => 'co_san / khong_co_san']],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết đội cứu hộ', 'params' => ['id' => 'ID đội (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/theo-trang-thai/{trang_thai}', 'description' => 'Lọc đội theo trạng thái', 'params' => ['trang_thai' => 'co_san / khong_co_san']],
                            ['method' => 'GET', 'path' => '/theo-khu-vuc/{khu_vuc}', 'description' => 'Lọc đội theo khu vực', 'params' => ['khu_vuc' => 'Tên khu vực (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/tim-kiem', 'description' => 'Tìm kiếm đội cứu hộ', 'params' => ['q' => 'Từ khóa tìm kiếm']],
                            ['method' => 'PUT', 'path' => '/{id}', 'description' => 'Cập nhật đội cứu hộ', 'params' => ['id' => 'ID đội (bắt buộc)']],
                            ['method' => 'DELETE', 'path' => '/{id}', 'description' => 'Xóa đội cứu hộ', 'params' => ['id' => 'ID đội (bắt buộc)']],
                        ]
                    ],
                    [
                        'name' => 'Thành Viên Đội',
                        'baseUrl' => '/api/doi-cuu-ho/{id}/thanh-vien',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách thành viên của đội', 'params' => ['id' => 'ID đội (bắt buộc)']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Thêm thành viên vào đội', 'params' => ['id' => 'ID đội (bắt buộc)', 'id_thanh_vien' => 'ID thành viên (bắt buộc)']],
                            ['method' => 'DELETE', 'path' => '/{id_thanh_vien}', 'description' => 'Xóa thành viên khỏi đội', 'params' => ['id' => 'ID đội (bắt buộc)', 'id_thanh_vien' => 'ID thành viên (bắt buộc)']],
                        ]
                    ],
                    [
                        'name' => 'Tài Nguyên Đội',
                        'baseUrl' => '/api/doi-cuu-ho/{id}/tai-nguyen',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách tài nguyên của đội', 'params' => ['id' => 'ID đội (bắt buộc)']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Thêm tài nguyên cho đội', 'params' => ['id' => 'ID đội (bắt buộc)', 'ten_tai_nguyen' => 'Tên tài nguyên', 'loai' => 'Loại tài nguyên']],
                            ['method' => 'PUT', 'path' => '/{id_tai_nguyen}', 'description' => 'Cập nhật tài nguyên', 'params' => ['id' => 'ID đội (bắt buộc)', 'id_tai_nguyen' => 'ID tài nguyên (bắt buộc)']],
                        ]
                    ],
                    [
                        'name' => 'Vị Trí Đội',
                        'baseUrl' => '/api/doi-cuu-ho/{id}/vi-tri',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy vị trí hiện tại của đội', 'params' => ['id' => 'ID đội (bắt buộc)']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Cập nhật vị trí đội', 'params' => ['id' => 'ID đội (bắt buộc)', 'lat' => 'Latitude', 'lng' => 'Longitude']],
                        ]
                    ],
                    [
                        'name' => 'Năng Lực Đội',
                        'baseUrl' => '/api/doi-cuu-ho/{id}/nang-luc',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy năng lực của đội', 'params' => ['id' => 'ID đội (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '', 'description' => 'Cập nhật năng lực của đội', 'params' => ['id' => 'ID đội (bắt buộc)']],
                        ]
                    ],
                    [
                        'name' => 'Loại Sự Cố Xử Lý',
                        'baseUrl' => '/api/doi-cuu-ho/{id}/loai-su-co-dung-xu-ly',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy loại sự cố đội có thể xử lý', 'params' => ['id' => 'ID đội (bắt buộc)']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Thêm loại sự cố mà đội có thể xử lý', 'params' => ['id' => 'ID đội (bắt buộc)', 'id_loai_su_co' => 'ID loại sự cố (bắt buộc)']],
                        ]
                    ]
                ]
            ],
            [
                'section' => 'PHÂN CÔNG CỨU HỘ',
                'description' => 'Quản lý phân công cứu hộ và kết quả xử lý',
                'endpoints' => [
                    [
                        'name' => 'Phân Công',
                        'baseUrl' => '/api/phan-cong-cuu-ho',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách phân công', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Tạo phân công mới', 'params' => ['id_yeu_cau' => 'ID yêu cầu (bắt buộc)', 'id_doi_cuu_ho' => 'ID đội (bắt buộc)', 'trang_thai' => 'chua_phan_cong / da_phan_cong / dang_xu_ly / da_hoan_thanh / khong_thanh_cong']],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết phân công', 'params' => ['id' => 'ID phân công (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/theo-yeu-cau/{id_yeu_cau}', 'description' => 'Lấy phân công theo yêu cầu', 'params' => ['id_yeu_cau' => 'ID yêu cầu (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/theo-doi/{id_doi_cuu_ho}', 'description' => 'Lấy phân công theo đội', 'params' => ['id_doi_cuu_ho' => 'ID đội (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/theo-trang-thai/{trang_thai}', 'description' => 'Lấy phân công theo trạng thái', 'params' => ['trang_thai' => 'Trạng thái (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '/{id}', 'description' => 'Cập nhật phân công', 'params' => ['id' => 'ID phân công (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '/{id}/trang-thai', 'description' => 'Cập nhật trạng thái phân công', 'params' => ['id' => 'ID phân công (bắt buộc)', 'trang_thai' => 'Trạng thái mới']],
                            ['method' => 'DELETE', 'path' => '/{id}', 'description' => 'Xóa phân công', 'params' => ['id' => 'ID phân công (bắt buộc)']],
                        ]
                    ],
                    [
                        'name' => 'Kết Quả Cứu Hộ',
                        'baseUrl' => '/api/ket-qua-cuu-ho',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách kết quả cứu hộ', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang']],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết kết quả', 'params' => ['id' => 'ID kết quả (bắt buộc)']],
                            ['method' => 'POST', 'path' => '/phan-cong/{id_phan_cong}', 'description' => 'Tạo kết quả cho phân công', 'params' => ['id_phan_cong' => 'ID phân công (bắt buộc)', 'trang_thai' => 'Trạng thái (bắt buộc)', 'mo_ta' => 'Mô tả chi tiết']],
                            ['method' => 'GET', 'path' => '/phan-cong/{id_phan_cong}', 'description' => 'Lấy kết quả theo phân công', 'params' => ['id_phan_cong' => 'ID phân công (bắt buộc)']],
                            ['method' => 'PUT', 'path' => '/{id}', 'description' => 'Cập nhật kết quả', 'params' => ['id' => 'ID kết quả (bắt buộc)']],
                        ]
                    ]
                ]
            ],
            [
                'section' => 'ĐÁNH GIÁ CỨU HỘ',
                'description' => 'Quản lý đánh giá và đánh giá chất lượng dịch vụ',
                'endpoints' => [
                    [
                        'name' => 'Đánh Giá',
                        'baseUrl' => '/api/danh-gia-cuu-ho',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Lấy danh sách đánh giá', 'params' => ['per_page' => 'Số items mỗi trang', 'page' => 'Số trang']],
                            ['method' => 'POST', 'path' => '', 'description' => 'Tạo đánh giá mới', 'params' => ['id_yeu_cau' => 'ID yêu cầu (bắt buộc)', 'id_doi_cuu_ho' => 'ID đội (bắt buộc)', 'diem_danh_gia' => 'Điểm đánh giá (1-5)', 'nhan_xet' => 'Nhận xét']],
                            ['method' => 'GET', 'path' => '/{id}', 'description' => 'Lấy chi tiết đánh giá', 'params' => ['id' => 'ID đánh giá (bắt buộc)']],
                            ['method' => 'GET', 'path' => '/yeu-cau/{id_yeu_cau}', 'description' => 'Lấy đánh giá theo yêu cầu', 'params' => ['id_yeu_cau' => 'ID yêu cầu (bắt buộc)']],
                            ['method' => 'POST', 'path' => '/yeu-cau/{id_yeu_cau}', 'description' => 'Tạo đánh giá cho yêu cầu', 'params' => ['id_yeu_cau' => 'ID yêu cầu (bắt buộc)']],
                        ]
                    ]
                ]
            ],
            [
                'section' => 'THỐNG KÊ & ANALYTICS',
                'description' => 'Lấy data thống kê và phân tích dữ liệu',
                'endpoints' => [
                    [
                        'name' => 'Thống Kê Chung',
                        'baseUrl' => '/api/thong-ke',
                        'methods' => [
                            ['method' => 'GET', 'path' => '/tong-so-yeu-cau', 'description' => 'Lấy tổng số yêu cầu cứu hộ', 'params' => []],
                            ['method' => 'GET', 'path' => '/yeu-cau-theo-loai', 'description' => 'Thống kê yêu cầu theo loại sự cố', 'params' => []],
                            ['method' => 'GET', 'path' => '/yeu-cau-theo-muc-do-khan-cap', 'description' => 'Thống kê yêu cầu theo mức độ khẩn cấp', 'params' => []],
                            ['method' => 'GET', 'path' => '/trang-thai-xu-ly', 'description' => 'Thống kê trạng thái xử lý', 'params' => []],
                            ['method' => 'GET', 'path' => '/hieu-suat-doi-cuu-ho', 'description' => 'Thống kê hiệu suất đội cứu hộ', 'params' => []],
                            ['method' => 'GET', 'path' => '/danh-sach-doi-co-san', 'description' => 'Lấy danh sách đội có sẵn', 'params' => []],
                            ['method' => 'GET', 'path' => '/heatmap', 'description' => 'Lấy dữ liệu heatmap vị trí sự cố', 'params' => []],
                        ]
                    ]
                ]
            ],
            [
                'section' => 'TÌM KIẾM',
                'description' => 'Tìm kiếm toàn cục trong hệ thống',
                'endpoints' => [
                    [
                        'name' => 'Tìm Kiếm Chung',
                        'baseUrl' => '/api/tim-kiem',
                        'methods' => [
                            ['method' => 'GET', 'path' => '/yeu-cau', 'description' => 'Tìm kiếm yêu cầu cứu hộ', 'params' => ['q' => 'Từ khóa tìm kiếm (bắt buộc)', 'per_page' => 'Số items mỗi trang']],
                            ['method' => 'GET', 'path' => '/doi-cuu-ho', 'description' => 'Tìm kiếm đội cứu hộ', 'params' => ['q' => 'Từ khóa tìm kiếm (bắt buộc)', 'per_page' => 'Số items mỗi trang']],
                            ['method' => 'GET', 'path' => '/loai-su-co', 'description' => 'Tìm kiếm loại sự cố', 'params' => ['q' => 'Từ khóa tìm kiếm (bắt buộc)', 'per_page' => 'Số items mỗi trang']],
                        ]
                    ]
                ]
            ],
            [
                'section' => 'SYSTEM',
                'description' => 'Health check và status của hệ thống',
                'endpoints' => [
                    [
                        'name' => 'Health Check',
                        'baseUrl' => '/api/health',
                        'methods' => [
                            ['method' => 'GET', 'path' => '', 'description' => 'Kiểm tra trạng thái API', 'params' => []],
                        ]
                    ]
                ]
            ]
        ];
    }
}
