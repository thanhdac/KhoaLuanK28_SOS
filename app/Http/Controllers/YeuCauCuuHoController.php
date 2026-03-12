<?php

namespace App\Http\Controllers;

use App\Models\{YeuCauCuuHo, HangDoiXuLy, PhanLoaiAis, DuLieuHeatmap, PhanCongCuuHo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class YeuCauCuuHoController extends Controller
{
    /**
     * Display paginated list of all rescue requests
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $sortBy = $request->get('sort_by', 'id_yeu_cau');
            $sortOrder = $request->get('sort_order', 'desc');

            $items = YeuCauCuuHo::with([
                'nguoiDung',
                'loaiSuCo',
                'hangDoiXuLy',
                'phanLoaiAis',
                'phanCongs',
                'danhGias'
            ])
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => 'Danh sách yêu cầu cứu hộ',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created rescue request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_nguoi_dung' => 'required|integer|exists:nguoi_dung,id_nguoi_dung',
                'id_loai_su_co' => 'required|integer|exists:loai_su_co,id_loai_su_co',
                'vi_tri_lat' => 'required|numeric',
                'vi_tri_lng' => 'required|numeric',
                'vi_tri_dia_chi' => 'required|string|max:255',
                'chi_tiet' => 'required|string',
                'mo_ta' => 'nullable|string',
                'hinh_anh' => 'nullable|string',
                'so_nguoi_bi_anh_huong' => 'nullable|integer|min:0',
                'muc_do_khan_cap' => 'nullable|integer|between:1,5',
                'diem_uu_tien' => 'nullable|numeric',
                'trang_thai' => 'nullable|string|in:moi,dang_xu_ly,da_hoan_thanh,da_huy'
            ]);

            $item = YeuCauCuuHo::create($validated);

            // Create processing queue entry
            HangDoiXuLy::create([
                'id_yeu_cau' => $item->id_yeu_cau,
                'diem_uu_tien' => $validated['diem_uu_tien'] ?? 0,
                'muc_khan_cap' => $validated['muc_do_khan_cap'] ?? 1,
                'trang_thai' => 'doi_xu_ly'
            ]);

            $item->load('nguoiDung', 'loaiSuCo', 'hangDoiXuLy');

            return Response::json([
                'success' => true,
                'message' => 'Tạo yêu cầu cứu hộ thành công',
                'data' => $item
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi tạo yêu cầu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified rescue request
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $item = YeuCauCuuHo::with([
                'nguoiDung',
                'loaiSuCo',
                'hangDoiXuLy',
                'phanLoaiAis',
                'phanCongs.doiCuuHo',
                'phanCongs.ketQua',
                'danhGias'
            ])->findOrFail($id);

            return Response::json([
                'success' => true,
                'message' => 'Chi tiết yêu cầu cứu hộ',
                'data' => $item
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Yêu cầu cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy chi tiết: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified rescue request
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $item = YeuCauCuuHo::findOrFail($id);

            $validated = $request->validate([
                'id_nguoi_dung' => 'nullable|integer|exists:nguoi_dung,id_nguoi_dung',
                'id_loai_su_co' => 'nullable|integer|exists:loai_su_co,id_loai_su_co',
                'vi_tri_lat' => 'nullable|numeric',
                'vi_tri_lng' => 'nullable|numeric',
                'vi_tri_dia_chi' => 'nullable|string|max:255',
                'chi_tiet' => 'nullable|string',
                'mo_ta' => 'nullable|string',
                'hinh_anh' => 'nullable|string',
                'so_nguoi_bi_anh_huong' => 'nullable|integer|min:0',
                'muc_do_khan_cap' => 'nullable|integer|between:1,5',
                'diem_uu_tien' => 'nullable|numeric',
                'trang_thai' => 'nullable|string|in:moi,dang_xu_ly,da_hoan_thanh,da_huy'
            ]);

            $item->update($validated);

            // Update processing queue if priority or urgency changed
            if (isset($validated['diem_uu_tien']) || isset($validated['muc_do_khan_cap'])) {
                $item->hangDoiXuLy()->update([
                    'diem_uu_tien' => $validated['diem_uu_tien'] ?? $item->hangDoiXuLy->diem_uu_tien,
                    'muc_khan_cap' => $validated['muc_do_khan_cap'] ?? $item->hangDoiXuLy->muc_khan_cap
                ]);
            }

            $item->load('nguoiDung', 'loaiSuCo', 'hangDoiXuLy');

            return Response::json([
                'success' => true,
                'message' => 'Cập nhật yêu cầu cứu hộ thành công',
                'data' => $item
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Yêu cầu cứu hộ không tồn tại'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified rescue request
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $item = YeuCauCuuHo::findOrFail($id);

            // Delete related processing queue entries
            HangDoiXuLy::where('id_yeu_cau', $id)->delete();

            // Delete AI classifications
            PhanLoaiAis::where('id_yeu_cau', $id)->delete();

            $item->delete();

            return Response::json([
                'success' => true,
                'message' => 'Xóa yêu cầu cứu hộ thành công'
            ], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Yêu cầu cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi xóa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rescue requests by status
     *
     * @param string $status
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByStatus(Request $request, $status)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $validStatuses = ['moi', 'dang_xu_ly', 'da_hoan_thanh', 'da_huy'];

            if (!in_array($status, $validStatuses)) {
                return Response::json([
                    'success' => false,
                    'message' => 'Trạng thái không hợp lệ'
                ], 422);
            }

            $items = YeuCauCuuHo::with([
                'nguoiDung',
                'loaiSuCo',
                'hangDoiXuLy'
            ])
            ->where('trang_thai', $status)
            ->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => "Danh sách yêu cầu với trạng thái: {$status}",
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the status of a rescue request
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $item = YeuCauCuuHo::findOrFail($id);

            $validated = $request->validate([
                'trang_thai' => 'required|string|in:moi,dang_xu_ly,da_hoan_thanh,da_huy'
            ]);

            $item->update(['trang_thai' => $validated['trang_thai']]);

            // Update processing queue status if applicable
            if ($item->hangDoiXuLy) {
                $item->hangDoiXuLy->update(['trang_thai' => $validated['trang_thai']]);
            }

            $item->load('nguoiDung', 'loaiSuCo', 'hangDoiXuLy');

            return Response::json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'data' => $item
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Yêu cầu cứu hộ không tồn tại'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rescue requests by urgency level
     *
     * @param Request $request
     * @param int $muc_do
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByUrgency(Request $request, $muc_do)
    {
        try {
            $perPage = $request->get('per_page', 15);

            if ($muc_do < 1 || $muc_do > 5) {
                return Response::json([
                    'success' => false,
                    'message' => 'Mức độ khan cấp phải từ 1 đến 5'
                ], 422);
            }

            $items = YeuCauCuuHo::with([
                'nguoiDung',
                'loaiSuCo',
                'hangDoiXuLy'
            ])
            ->where('muc_do_khan_cap', $muc_do)
            ->orderBy('diem_uu_tien', 'desc')
            ->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => "Danh sách yêu cầu mức độ khan cấp: {$muc_do}",
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get processing queue with pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHangDoiXuLy(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $sortBy = $request->get('sort_by', 'diem_uu_tien');
            $sortOrder = $request->get('sort_order', 'desc');

            $queue = HangDoiXuLy::with([
                'yeuCau.nguoiDung',
                'yeuCau.loaiSuCo'
            ])
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => 'Hàng đợi xử lý',
                'data' => $queue
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy hàng đợi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get processing queue by status
     *
     * @param Request $request
     * @param string $trang_thai
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHangDoiByStatus(Request $request, $trang_thai)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $validStatuses = ['doi_xu_ly', 'dang_xu_ly', 'hoan_thanh', 'huy'];

            if (!in_array($trang_thai, $validStatuses)) {
                return Response::json([
                    'success' => false,
                    'message' => 'Trạng thái không hợp lệ'
                ], 422);
            }

            $queue = HangDoiXuLy::with([
                'yeuCau.nguoiDung',
                'yeuCau.loaiSuCo'
            ])
            ->where('trang_thai', $trang_thai)
            ->orderBy('diem_uu_tien', 'desc')
            ->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => "Hàng đợi với trạng thái: {$trang_thai}",
                'data' => $queue
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy hàng đợi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get AI classifications for a rescue request
     *
     * @param int $id_yeu_cau
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPhanLoaiAis($id_yeu_cau)
    {
        try {
            $yeuCau = YeuCauCuuHo::findOrFail($id_yeu_cau);

            $classifications = $yeuCau->phanLoaiAis()
                ->orderBy('do_tin_cay', 'desc')
                ->get();

            return Response::json([
                'success' => true,
                'message' => 'Danh sách phân loại AI',
                'data' => $classifications
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Yêu cầu cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create AI classification for a rescue request
     *
     * @param Request $request
     * @param int $id_yeu_cau
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPhanLoaiAis(Request $request, $id_yeu_cau)
    {
        try {
            $yeuCau = YeuCauCuuHo::findOrFail($id_yeu_cau);

            $validated = $request->validate([
                'so_nguoi' => 'nullable|integer|min:0',
                'ten_loai_su_co' => 'required|string|max:255',
                'muc_tu_bao_cao' => 'nullable|numeric',
                'thoi_gian_cho' => 'nullable|numeric',
                'diem_uu_tien' => 'nullable|numeric',
                'muc_khan_cap' => 'nullable|integer|between:1,5',
                'do_tin_cay' => 'required|numeric|between:0,100',
                'ly_do' => 'nullable|string',
                'model_version' => 'nullable|string'
            ]);

            $validated['id_yeu_cau'] = $id_yeu_cau;
            $classification = PhanLoaiAis::create($validated);

            return Response::json([
                'success' => true,
                'message' => 'Tạo phân loại AI thành công',
                'data' => $classification
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Yêu cầu cứu hộ không tồn tại'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi xác thực dữ liệu',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi tạo phân loại: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific AI classification
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPhanLoai($id)
    {
        try {
            $classification = PhanLoaiAis::with('yeuCau.loaiSuCo', 'yeuCau.nguoiDung')
                ->findOrFail($id);

            return Response::json([
                'success' => true,
                'message' => 'Chi tiết phân loại AI',
                'data' => $classification
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Phân loại AI không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get heatmap data for visualization
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHeatmapData(Request $request)
    {
        try {
            $minLat = $request->get('min_lat');
            $maxLat = $request->get('max_lat');
            $minLng = $request->get('min_lng');
            $maxLng = $request->get('max_lng');

            $query = YeuCauCuuHo::query();

            if ($minLat && $maxLat) {
                $query->whereBetween('vi_tri_lat', [$minLat, $maxLat]);
            }
            if ($minLng && $maxLng) {
                $query->whereBetween('vi_tri_lng', [$minLng, $maxLng]);
            }

            $heatmapData = $query->select([
                'id_yeu_cau',
                'vi_tri_lat',
                'vi_tri_lng',
                'muc_do_khan_cap',
                'diem_uu_tien',
                'trang_thai'
            ])->get();

            // Generate density data
            $densityData = $heatmapData->map(function ($item) {
                return [
                    'lat' => floatval($item->vi_tri_lat),
                    'lng' => floatval($item->vi_tri_lng),
                    'intensity' => $item->muc_do_khan_cap + $item->diem_uu_tien / 100,
                    'status' => $item->trang_thai
                ];
            });

            return Response::json([
                'success' => true,
                'message' => 'Dữ liệu heatmap',
                'data' => $densityData
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu heatmap: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search rescue requests with multiple filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = YeuCauCuuHo::with([
                'nguoiDung',
                'loaiSuCo',
                'hangDoiXuLy'
            ]);

            // Search by keyword in chi_tiet, mo_ta, vi_tri_dia_chi
            if ($request->has('keyword')) {
                $keyword = $request->get('keyword');
                $query->where(function ($q) use ($keyword) {
                    $q->where('chi_tiet', 'like', "%{$keyword}%")
                        ->orWhere('mo_ta', 'like', "%{$keyword}%")
                        ->orWhere('vi_tri_dia_chi', 'like', "%{$keyword}%");
                });
            }

            // Filter by status
            if ($request->has('trang_thai')) {
                $query->where('trang_thai', $request->get('trang_thai'));
            }

            // Filter by urgency
            if ($request->has('muc_do_khan_cap')) {
                $query->where('muc_do_khan_cap', $request->get('muc_do_khan_cap'));
            }

            // Filter by incident type
            if ($request->has('id_loai_su_co')) {
                $query->where('id_loai_su_co', $request->get('id_loai_su_co'));
            }

            // Filter by user
            if ($request->has('id_nguoi_dung')) {
                $query->where('id_nguoi_dung', $request->get('id_nguoi_dung'));
            }

            // Filter by date range
            if ($request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('created_at', [
                    $request->get('from_date'),
                    $request->get('to_date')
                ]);
            }

            // Filter by priority range
            if ($request->has('min_priority') && $request->has('max_priority')) {
                $query->whereBetween('diem_uu_tien', [
                    $request->get('min_priority'),
                    $request->get('max_priority')
                ]);
            }

            // Filter by affected people
            if ($request->has('min_people') && $request->has('max_people')) {
                $query->whereBetween('so_nguoi_bi_anh_huong', [
                    $request->get('min_people'),
                    $request->get('max_people')
                ]);
            }

            $perPage = $request->get('per_page', 15);
            $sortBy = $request->get('sort_by', 'id_yeu_cau');
            $sortOrder = $request->get('sort_order', 'desc');

            $results = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => 'Kết quả tìm kiếm',
                'data' => $results
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi tìm kiếm: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total number of rescue requests
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTotalRequests(Request $request)
    {
        try {
            $query = YeuCauCuuHo::query();

            // Filter by date range if provided
            if ($request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('created_at', [
                    $request->get('from_date'),
                    $request->get('to_date')
                ]);
            }

            $total = $query->count();
            $byStatus = $query->groupBy('trang_thai')->selectRaw('trang_thai, COUNT(*) as count')->get();

            return Response::json([
                'success' => true,
                'message' => 'Tổng số yêu cầu cứu hộ',
                'data' => [
                    'total' => $total,
                    'by_status' => $byStatus
                ]
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get requests count by incident type
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequestsByType(Request $request)
    {
        try {
            $query = YeuCauCuuHo::query();

            if ($request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('created_at', [
                    $request->get('from_date'),
                    $request->get('to_date')
                ]);
            }

            $requestsByType = $query->with('loaiSuCo')
                ->groupBy('id_loai_su_co')
                ->selectRaw('id_loai_su_co, COUNT(*) as count')
                ->get()
                ->map(function ($item) {
                    return [
                        'id_loai_su_co' => $item->id_loai_su_co,
                        'type_name' => $item->loaiSuCo->ten_danh_muc ?? 'Unknown',
                        'count' => $item->count
                    ];
                });

            return Response::json([
                'success' => true,
                'message' => 'Số lượng yêu cầu theo loại sự cố',
                'data' => $requestsByType
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get requests count by urgency level
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequestsByUrgency(Request $request)
    {
        try {
            $query = YeuCauCuuHo::query();

            if ($request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('created_at', [
                    $request->get('from_date'),
                    $request->get('to_date')
                ]);
            }

            $requestsByUrgency = $query->groupBy('muc_do_khan_cap')
                ->selectRaw('muc_do_khan_cap, COUNT(*) as count')
                ->orderBy('muc_do_khan_cap')
                ->get();

            return Response::json([
                'success' => true,
                'message' => 'Số lượng yêu cầu theo mức độ khan cấp',
                'data' => $requestsByUrgency
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get processing status statistics
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProcessingStatus(Request $request)
    {
        try {
            $query = YeuCauCuuHo::query();

            if ($request->has('from_date') && $request->has('to_date')) {
                $query->whereBetween('created_at', [
                    $request->get('from_date'),
                    $request->get('to_date')
                ]);
            }

            $total = $query->count();
            $new = $query->where('trang_thai', 'moi')->count();
            $processing = $query->where('trang_thai', 'dang_xu_ly')->count();
            $completed = $query->where('trang_thai', 'da_hoan_thanh')->count();
            $cancelled = $query->where('trang_thai', 'da_huy')->count();

            $avgUrgency = $query->avg('muc_do_khan_cap') ?? 0;
            $avgAffectedPeople = $query->avg('so_nguoi_bi_anh_huong') ?? 0;
            $totalAffectedPeople = $query->sum('so_nguoi_bi_anh_huong') ?? 0;

            return Response::json([
                'success' => true,
                'message' => 'Thống kê trạng thái xử lý',
                'data' => [
                    'total' => $total,
                    'new' => $new,
                    'processing' => $processing,
                    'completed' => $completed,
                    'cancelled' => $cancelled,
                    'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
                    'avg_urgency' => round($avgUrgency, 2),
                    'avg_affected_people' => round($avgAffectedPeople, 2),
                    'total_affected_people' => $totalAffectedPeople
                ]
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }
}
