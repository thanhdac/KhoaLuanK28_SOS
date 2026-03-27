<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\ThanhVienDoiController;

// =========================================
// PHÂN QUYỀN & QUẢN TRỊ
// =========================================

// Chức năng (Functions/Features)
Route::apiResource('chuc-nang', ChucNangController::class);

// Chức vụ (Positions/Roles)
Route::apiResource('chuc-vu', ChucVuController::class);

// =========================================
// ADMIN MANAGEMENT
// =========================================
Route::post('admin/login', [AdminController::class, 'login']);
Route::get('/admin/check-token', [AdminController::class, 'checkAdmin']);  // No middleware - handle in controller
Route::get('/debug/token', function (Request $request) {
    return response()->json([
        'token' => $request->bearerToken(),
        'headers' => $request->headers->all(),
        'user' => Auth::guard('sanctum')->user(),
    ]);
});

Route::middleware(['auth:sanctum', 'check.admin'])->group(function () {
    Route::get('admin/profile', [AdminController::class, 'getProfile']);
    Route::post('admin/logout', [AdminController::class, 'logout']);
    Route::get('admin/list', [AdminController::class, 'index']);
    Route::get('admin/chi-tiet/{id}', [AdminController::class, 'show']);
    Route::post('admin/create', [AdminController::class, 'store']);
    Route::put('admin/update/{id}', [AdminController::class, 'update']);
    Route::delete('admin/delete/{id}', [AdminController::class, 'destroy']);
    Route::get('admin/search', [AdminController::class, 'search']);
    Route::put('admin/change-status/{id}', [AdminController::class, 'changeStatus']);
    Route::put('admin/active/{id}', [AdminController::class, 'active']);
});

// =========================================
// NGƯỜI DÙNG (USERS)
// =========================================
Route::post('nguoi-dung/login', [NguoiDungController::class, 'login']);
Route::post('nguoi-dung/register', [NguoiDungController::class, 'register']);
Route::get('/nguoi-dung/check-client', [NguoiDungController::class, 'checkClient']);


Route::middleware(['auth:sanctum', 'check.admin'])->group(function () {

    Route::get('/client/profile/data', [NguoiDungController::class, 'getProfile']);
    Route::post('/client/profile/update', [NguoiDungController::class, 'updateProfile']);

    Route::get('nguoi-dung/list', [NguoiDungController::class, 'index']);
    Route::get('nguoi-dung/chi-tiet/{id}', [NguoiDungController::class, 'show']);
    Route::post('nguoi-dung/create', [NguoiDungController::class, 'store']);
    Route::put('nguoi-dung/update/{id}', [NguoiDungController::class, 'update']);
    Route::delete('nguoi-dung/delete/{id}', [NguoiDungController::class, 'destroy']);
    Route::get('nguoi-dung/search', [NguoiDungController::class, 'search']);
    Route::put('nguoi-dung/change-status/{id}', [NguoiDungController::class, 'changeStatus']);
});

// =========================================
// THANH VIÊN ĐỘI CỨU HỘ (RESCUE TEAM MEMBERS)
// =========================================
Route::post('thanh-vien-doi/login', [ThanhVienDoiController::class, 'login']);

Route::middleware(['auth:sanctum', 'check.admin'])->group(function () {
    Route::get('thanh-vien-doi/list', [ThanhVienDoiController::class, 'index']);
    Route::post('thanh-vien-doi/create', [ThanhVienDoiController::class, 'store']);
    Route::put('thanh-vien-doi/update/{id}', [ThanhVienDoiController::class, 'update']);
    Route::delete('thanh-vien-doi/delete/{id}', [ThanhVienDoiController::class, 'destroy']);
    Route::put('thanh-vien-doi/change-status/{id}', [ThanhVienDoiController::class, 'updateStatus']);
});

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
Route::post('doi-cuu-ho/login', [DoiCuuHoController::class, 'login']);
Route::get('/doi-cuu-ho/check-token', [DoiCuuHoController::class, 'checkThanhVien']);
Route::get('get-doi-cuu-ho/{id}/thanh-vien', [DoiCuuHoController::class, 'getThanhVien']);
Route::post('post-doi-cuu-ho/{id}/thanh-vien', [DoiCuuHoController::class, 'addThanhVien']);
Route::delete('delete-doi-cuu-ho/{id}/thanh-vien/{id_thanh_vien}', [DoiCuuHoController::class, 'removeThanhVien']);

Route::get('get-doi-cuu-ho/{id}/tai-nguyen', [DoiCuuHoController::class, 'getTaiNguyen']);
Route::post('post-doi-cuu-ho/{id}/tai-nguyen', [DoiCuuHoController::class, 'addTaiNguyen']);
Route::put('put-doi-cuu-ho/{id}/tai-nguyen/{id_tai_nguyen}', [DoiCuuHoController::class, 'updateTaiNguyen']);

Route::get('get-doi-cuu-ho/{id}/vi-tri', [DoiCuuHoController::class, 'getViTri']);
Route::post('post-doi-cuu-ho/{id}/vi-tri', [DoiCuuHoController::class, 'addViTri']);

Route::get('get-doi-cuu-ho/{id}/nang-luc', [DoiCuuHoController::class, 'getNangLuc']);
Route::put('put-doi-cuu-ho/{id}/nang-luc', [DoiCuuHoController::class, 'updateNangLuc']);

Route::get('get-doi-cuu-ho/{id}/loai-su-co-dung-xu-ly', [DoiCuuHoController::class, 'getLoaiSuCoDungXuLy']);
Route::post('post-doi-cuu-ho/{id}/loai-su-co-dung-xu-ly', [DoiCuuHoController::class, 'addLoaiSuCoDungXuLy']);

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
Route::post('post-ket-qua-cuu-ho/phan-cong/{id_phan_cong}', [KetQuaCuuHoController::class, 'createForPhanCong']);
Route::get('get-ket-qua-cuu-ho/phan-cong/{id_phan_cong}', [KetQuaCuuHoController::class, 'getByPhanCong']);

// =========================================
// ĐÁNH GIÁ CỨU HỘ (RATINGS/REVIEWS)
// =========================================
Route::apiResource('danh-gia-cuu-ho', DanhGiaCuuHoController::class, ['except' => ['update', 'destroy']]);
Route::get('get-danh-gia-cuu-ho/yeu-cau/{id_yeu_cau}', [DanhGiaCuuHoController::class, 'getByYeuCau']);
Route::post('post-danh-gia-cuu-ho/yeu-cau/{id_yeu_cau}', [DanhGiaCuuHoController::class, 'createForYeuCau']);

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
