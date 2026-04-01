<?php

namespace App\Http\Controllers;

use App\Http\Requests\YeuCauCuuHoRequest;
use App\Models\{YeuCauCuuHo, HangDoiXuLy, PhanLoaiAis, DuLieuHeatmap, PhanCongCuuHo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class YeuCauCuuHoController extends Controller
{
    private function urgencyToNumber($value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $v = strtoupper(trim((string) $value));
        return match ($v) {
            'LOW' => 1,
            'MEDIUM' => 2,
            'HIGH' => 3,
            'CRITICAL' => 4,
            default => 0,
        };
    }

    private function normalizeTrangThaiYeuCau(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $v = strtoupper(trim($value));

        // Backward-compatible mapping (old lowercase API)
        $map = [
            'MOI' => 'CHO_XU_LY',
            'DANG_XU_LY' => 'DANG_XU_LY',
            'DA_HOAN_THANH' => 'HOAN_THANH',
            'DA_HUY' => 'HUY_BO',
            'HUY' => 'HUY_BO',

            // Already-canonical
            'CHO_XU_LY' => 'CHO_XU_LY',
            'HOAN_THANH' => 'HOAN_THANH',
            'HUY_BO' => 'HUY_BO',
        ];

        return $map[$v] ?? $v;
    }

    private function normalizeMucDoKhanCap($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Accept old numeric level (1-5) and map to canonical string
        if (is_numeric($value)) {
            $n = (int) $value;
            if ($n <= 1) {
                return 'LOW';
            }
            if ($n == 2) {
                return 'MEDIUM';
            }
            if ($n == 3) {
                return 'HIGH';
            }
            return 'CRITICAL';
        }

        $v = strtoupper(trim((string) $value));
        $allowed = ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'];
        return in_array($v, $allowed, true) ? $v : $v;
    }

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
    public function store(YeuCauCuuHoRequest $request)
    {
        try {
            if (is_array($request->input('chi_tiet'))) {
                $request->merge(['chi_tiet' => implode(', ', $request->input('chi_tiet'))]);
            }

            $validated = $request->validated();

            if (array_key_exists('trang_thai', $validated)) {
                $validated['trang_thai'] = $this->normalizeTrangThaiYeuCau($validated['trang_thai']);
            }
            if (array_key_exists('muc_do_khan_cap', $validated)) {
                $validated['muc_do_khan_cap'] = $this->normalizeMucDoKhanCap($validated['muc_do_khan_cap']);
            }

            if (!array_key_exists('diem_uu_tien', $validated) || $validated['diem_uu_tien'] === null) {
                $validated['diem_uu_tien'] = 0;
            }
            if (!array_key_exists('so_nguoi_bi_anh_huong', $validated) || $validated['so_nguoi_bi_anh_huong'] === null) {
                $validated['so_nguoi_bi_anh_huong'] = 1;
            }
            if (!array_key_exists('muc_do_khan_cap', $validated) || $validated['muc_do_khan_cap'] === null) {
                $validated['muc_do_khan_cap'] = 'MEDIUM';
            }
            if (!array_key_exists('trang_thai', $validated) || $validated['trang_thai'] === null) {
                $validated['trang_thai'] = 'CHO_XU_LY';
            }

            $item = YeuCauCuuHo::create($validated);

            // Create processing queue entry
            HangDoiXuLy::create([
                'id_yeu_cau' => $item->id_yeu_cau,
                'diem_uu_tien' => $validated['diem_uu_tien'] ?? 0,
                'muc_khan_cap' => $validated['muc_do_khan_cap'] ?? 'MEDIUM',
                'trang_thai' => 'WAITING'
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
                'vi_tri_dia_chi' => 'nullable|string|max:500',
                'chi_tiet' => 'nullable|string',
                'mo_ta' => 'nullable|string',
                'hinh_anh' => 'nullable|string',
                'so_nguoi_bi_anh_huong' => 'nullable|integer|min:0',
                'muc_do_khan_cap' => 'nullable',
                'diem_uu_tien' => 'nullable|numeric',
                'trang_thai' => 'nullable|string'
            ]);

            if (array_key_exists('trang_thai', $validated)) {
                $validated['trang_thai'] = $this->normalizeTrangThaiYeuCau($validated['trang_thai']);
            }
            if (array_key_exists('muc_do_khan_cap', $validated)) {
                $validated['muc_do_khan_cap'] = $this->normalizeMucDoKhanCap($validated['muc_do_khan_cap']);
            }

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
            $normalized = $this->normalizeTrangThaiYeuCau($status);
            $validStatuses = ['CHO_XU_LY', 'DANG_XU_LY', 'HOAN_THANH', 'HUY_BO'];

            if (!in_array($normalized, $validStatuses, true)) {
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
            ->where('trang_thai', $normalized)
            ->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => "Danh sách yêu cầu với trạng thái: {$normalized}",
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
                'trang_thai' => 'required|string'
            ]);

            $normalized = $this->normalizeTrangThaiYeuCau($validated['trang_thai']);
            $allowed = ['CHO_XU_LY', 'DANG_XU_LY', 'HOAN_THANH', 'HUY_BO'];
            if (!in_array($normalized, $allowed, true)) {
                return Response::json([
                    'success' => false,
                    'message' => 'Trạng thái không hợp lệ'
                ], 422);
            }

            $item->update(['trang_thai' => $normalized]);

            // Update processing queue status if applicable
            if ($item->hangDoiXuLy) {
                $queueStatus = $normalized === 'CHO_XU_LY' ? 'WAITING' : ($normalized === 'DANG_XU_LY' ? 'PROCESSING' : 'DONE');
                $item->hangDoiXuLy->update(['trang_thai' => $queueStatus]);
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
            $normalized = $this->normalizeMucDoKhanCap($muc_do);
            $valid = ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'];

            if (!in_array($normalized, $valid, true)) {
                return Response::json([
                    'success' => false,
                    'message' => 'Mức độ khẩn cấp không hợp lệ'
                ], 422);
            }

            $items = YeuCauCuuHo::with([
                'nguoiDung',
                'loaiSuCo',
                'hangDoiXuLy'
            ])
            ->where('muc_do_khan_cap', $normalized)
            ->orderBy('diem_uu_tien', 'desc')
            ->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => "Danh sách yêu cầu mức độ khẩn cấp: {$normalized}",
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
            $normalized = strtoupper(trim((string) $trang_thai));
            $validStatuses = ['WAITING', 'PROCESSING', 'DONE'];

            if (!in_array($normalized, $validStatuses, true)) {
                return Response::json([
                    'success' => false,
                    'message' => 'Trạng thái không hợp lệ'
                ], 422);
            }

            $queue = HangDoiXuLy::with([
                'yeuCau.nguoiDung',
                'yeuCau.loaiSuCo'
            ])
            ->where('trang_thai', $normalized)
            ->orderBy('diem_uu_tien', 'desc')
            ->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => "Hàng đợi với trạng thái: {$normalized}",
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
     * Get processing queue info for a specific rescue request
     *
     * Route: GET yeu-cau-cuu-ho/{id}/hang-doi
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHangDoi($id)
    {
        try {
            $yeuCau = YeuCauCuuHo::with(['hangDoiXuLy'])->findOrFail($id);
            $queue = $yeuCau->hangDoiXuLy;

            if (!$queue) {
                return Response::json([
                    'success' => false,
                    'message' => 'Yêu cầu chưa có trong hàng đợi xử lý'
                ], 404);
            }

            $queue->load(['yeuCau.nguoiDung', 'yeuCau.loaiSuCo']);

            return Response::json([
                'success' => true,
                'message' => 'Thông tin hàng đợi xử lý',
                'data' => $queue
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Yêu cầu cứu hộ không tồn tại'
            ], 404);
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
                $urgency = $this->urgencyToNumber($item->muc_do_khan_cap);
                $priority = is_numeric($item->diem_uu_tien) ? (float) $item->diem_uu_tien : 0;
                return [
                    'lat' => floatval($item->vi_tri_lat),
                    'lng' => floatval($item->vi_tri_lng),
                    'intensity' => $urgency + $priority / 100,
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
            $baseQuery = YeuCauCuuHo::query();

            if ($request->has('from_date') && $request->has('to_date')) {
                $baseQuery->whereBetween('created_at', [
                    $request->get('from_date'),
                    $request->get('to_date')
                ]);
            }

            $total = (clone $baseQuery)->count();
            $new = (clone $baseQuery)->where('trang_thai', 'CHO_XU_LY')->count();
            $processing = (clone $baseQuery)->where('trang_thai', 'DANG_XU_LY')->count();
            $completed = (clone $baseQuery)->where('trang_thai', 'HOAN_THANH')->count();
            $cancelled = (clone $baseQuery)->where('trang_thai', 'HUY_BO')->count();

            $urgencies = (clone $baseQuery)->pluck('muc_do_khan_cap');
            $urgencyAvg = $urgencies->count() > 0
                ? $urgencies->map(fn ($v) => $this->urgencyToNumber($v))->avg()
                : 0;
            $avgAffectedPeople = (clone $baseQuery)->avg('so_nguoi_bi_anh_huong') ?? 0;
            $totalAffectedPeople = (clone $baseQuery)->sum('so_nguoi_bi_anh_huong') ?? 0;

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
                    'avg_urgency' => round($urgencyAvg, 2),
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
