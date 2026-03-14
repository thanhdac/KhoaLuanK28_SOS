<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChucNangController;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\LoaiSuCoController;
use App\Http\Controllers\YeuCauCuuHoController;
use App\Http\Controllers\DoiCuuHoController;
use App\Http\Controllers\PhanCongCuuHoController;
use App\Http\Controllers\KetQuaCuuHoController;
use App\Http\Controllers\DanhGiaCuuHoController;

// =========================================
// PHÂN QUYỀN & QUẢN TRỊ
// =========================================

// Chức năng (Functions/Features)
Route::apiResource('chuc-nang', ChucNangController::class);

// Chức vụ (Positions/Roles)
Route::apiResource('chuc-vu', ChucVuController::class);

// Admin Management
Route::apiResource('admin', AdminController::class);

// =========================================
// NGƯỜI DÙNG
// =========================================
Route::post('/client/dang-nhap', [NguoiDungController::class, 'dangNhap']);
Route::post('/client/dang-ky', [NguoiDungController::class, 'dangKy']);
Route::post('/client/quen-mat-khau', [NguoiDungController::class, 'quenMatKhau']);
Route::post('/client/dat-lai-mat-khau', [NguoiDungController::class, 'datLaiMatKhau']);
Route::post('/client/kich-hoat', [NguoiDungController::class, 'kichHoat']);
Route::get('/client/check-token', [NguoiDungController::class, 'checkClient']);
Route::get('/client/thong-tin', [NguoiDungController::class, 'thongTinNguoiDung']);
Route::post('/client/cap-nhat-thong-tin', [NguoiDungController::class, 'capNhatThongTin']);

Route::post('/client/doi-mat-khau', [NguoiDungController::class, 'doiMatKhau']);

// =========================================
// PHÂN LOẠI SỰ CỐ
// =========================================

// Loại sự cố (Incident Types)
Route::apiResource('loai-su-co', LoaiSuCoController::class);

// Loại sự cố - Advanced operations
Route::get('loai-su-co/{id}/chi-tiet', [LoaiSuCoController::class, 'getChiTiet']);
Route::get('loai-su-co/theo-trang-thai/{trang_thai}', [LoaiSuCoController::class, 'getByStatus']);
Route::get('loai-su-co/{id}/yeu-cau-cuu-ho', [LoaiSuCoController::class, 'getYeuCauCuuHo']);
Route::get('loai-su-co/{id}/doi-cuu-ho', [LoaiSuCoController::class, 'getDoiCuuHo']);
Route::get('tim-kiem/loai-su-co', [LoaiSuCoController::class, 'search']);
Route::put('loai-su-co/{id}/trang-thai', [LoaiSuCoController::class, 'updateStatus']);

// =========================================
// YÊU CẦU CỨU HỘ (HELP REQUESTS)
// =========================================
Route::apiResource('yeu-cau-cuu-ho', YeuCauCuuHoController::class);

// Yêu cầu cứu hộ - Advanced operations
Route::get('yeu-cau-cuu-ho/{id}/phan-loai', [YeuCauCuuHoController::class, 'getPhanLoai']);
Route::get('yeu-cau-cuu-ho/{id}/hang-doi', [YeuCauCuuHoController::class, 'getHangDoi']);
Route::put('yeu-cau-cuu-ho/{id}/trang-thai', [YeuCauCuuHoController::class, 'updateStatus']);
Route::get('yeu-cau-cuu-ho/theo-trang-thai/{trang_thai}', [YeuCauCuuHoController::class, 'getByStatus']);
Route::get('yeu-cau-cuu-ho/theo-muc-do-khan-cap/{muc_do}', [YeuCauCuuHoController::class, 'getByUrgency']);

// Hàng đợi xử lý (Processing Queue)
Route::get('hang-doi-xu-ly', [YeuCauCuuHoController::class, 'getHangDoiXuLy']);
Route::get('hang-doi-xu-ly/theo-trang-thai/{trang_thai}', [YeuCauCuuHoController::class, 'getHangDoiByStatus']);

// AI Phân loại (AI Classification)
Route::get('phan-loai-ais/{id_yeu_cau}', [YeuCauCuuHoController::class, 'getPhanLoaiAis']);
Route::post('phan-loai-ais/{id_yeu_cau}/tao-phan-loai', [YeuCauCuuHoController::class, 'createPhanLoaiAis']);

// =========================================
// ĐỘI CỨU HỘ (RESCUE TEAMS)
// =========================================
Route::apiResource('doi-cuu-ho', DoiCuuHoController::class);

// Đội cứu hộ - Advanced operations
Route::get('doi-cuu-ho/{id}/thanh-vien', [DoiCuuHoController::class, 'getThanhVien']);
Route::post('doi-cuu-ho/{id}/thanh-vien', [DoiCuuHoController::class, 'addThanhVien']);
Route::delete('doi-cuu-ho/{id}/thanh-vien/{id_thanh_vien}', [DoiCuuHoController::class, 'removeThanhVien']);

Route::get('doi-cuu-ho/{id}/tai-nguyen', [DoiCuuHoController::class, 'getTaiNguyen']);
Route::post('doi-cuu-ho/{id}/tai-nguyen', [DoiCuuHoController::class, 'addTaiNguyen']);
Route::put('doi-cuu-ho/{id}/tai-nguyen/{id_tai_nguyen}', [DoiCuuHoController::class, 'updateTaiNguyen']);

Route::get('doi-cuu-ho/{id}/vi-tri', [DoiCuuHoController::class, 'getViTri']);
Route::post('doi-cuu-ho/{id}/vi-tri', [DoiCuuHoController::class, 'addViTri']);

Route::get('doi-cuu-ho/{id}/nang-luc', [DoiCuuHoController::class, 'getNangLuc']);
Route::put('doi-cuu-ho/{id}/nang-luc', [DoiCuuHoController::class, 'updateNangLuc']);

Route::get('doi-cuu-ho/{id}/loai-su-co-dung-xu-ly', [DoiCuuHoController::class, 'getLoaiSuCoDungXuLy']);
Route::post('doi-cuu-ho/{id}/loai-su-co-dung-xu-ly', [DoiCuuHoController::class, 'addLoaiSuCoDungXuLy']);

Route::get('doi-cuu-ho/theo-trang-thai/{trang_thai}', [DoiCuuHoController::class, 'getByStatus']);
Route::get('doi-cuu-ho/theo-khu-vuc/{khu_vuc}', [DoiCuuHoController::class, 'getByKhuVuc']);

// =========================================
// PHÂN CÔNG CỨU HỘ (TASK ASSIGNMENT)
// =========================================
Route::apiResource('phan-cong-cuu-ho', PhanCongCuuHoController::class);

// Phân công - Advanced operations
Route::put('phan-cong-cuu-ho/{id}/trang-thai', [PhanCongCuuHoController::class, 'updateStatus']);
Route::get('phan-cong-cuu-ho/theo-yeu-cau/{id_yeu_cau}', [PhanCongCuuHoController::class, 'getByYeuCau']);
Route::get('phan-cong-cuu-ho/theo-doi/{id_doi_cuu_ho}', [PhanCongCuuHoController::class, 'getByDoi']);
Route::get('phan-cong-cuu-ho/theo-trang-thai/{trang_thai}', [PhanCongCuuHoController::class, 'getByStatus']);

// Kết quả cứu hộ (Response Results)
Route::apiResource('ket-qua-cuu-ho', KetQuaCuuHoController::class, ['except' => ['store', 'destroy']]);
Route::post('ket-qua-cuu-ho/phan-cong/{id_phan_cong}', [KetQuaCuuHoController::class, 'createForPhanCong']);
Route::get('ket-qua-cuu-ho/phan-cong/{id_phan_cong}', [KetQuaCuuHoController::class, 'getByPhanCong']);

// =========================================
// ĐÁNH GIÁ CỨU HỘ (RATINGS/REVIEWS)
// =========================================
Route::apiResource('danh-gia-cuu-ho', DanhGiaCuuHoController::class, ['except' => ['update', 'destroy']]);
Route::get('danh-gia-cuu-ho/yeu-cau/{id_yeu_cau}', [DanhGiaCuuHoController::class, 'getByYeuCau']);
Route::post('danh-gia-cuu-ho/yeu-cau/{id_yeu_cau}', [DanhGiaCuuHoController::class, 'createForYeuCau']);

// =========================================
// STATISTICS & ANALYTICS
// =========================================
Route::get('thong-ke/tong-so-yeu-cau', [YeuCauCuuHoController::class, 'getTotalRequests']);
Route::get('thong-ke/yeu-cau-theo-loai', [YeuCauCuuHoController::class, 'getRequestsByType']);
Route::get('thong-ke/yeu-cau-theo-muc-do-khan-cap', [YeuCauCuuHoController::class, 'getRequestsByUrgency']);
Route::get('thong-ke/trang-thai-xu-ly', [YeuCauCuuHoController::class, 'getProcessingStatus']);
Route::get('thong-ke/hieu-suat-doi-cuu-ho', [DoiCuuHoController::class, 'getTeamEfficiency']);
Route::get('thong-ke/danh-sach-doi-co-san', [DoiCuuHoController::class, 'getAvailableTeams']);
Route::get('thong-ke/heatmap', [YeuCauCuuHoController::class, 'getHeatmapData']);

// =========================================
// SEARCH & FILTER
// =========================================
Route::get('tim-kiem/yeu-cau', [YeuCauCuuHoController::class, 'search']);
Route::get('tim-kiem/doi-cuu-ho', [DoiCuuHoController::class, 'search']);

// Default
Route::get('/health', function () {
    return response()->json(['status' => 'OK', 'message' => 'API is running']);
});
