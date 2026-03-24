<?php

namespace App\Http\Controllers;

use App\Models\{DoiCuuHo, ThanhVienDoi, TaiNguyenCuuHo, ViTriDoiCuuHo, NangLucDoi, DoiCuuHoLoaiSuCo, LoaiSuCo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DoiCuuHoController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required',
        ]);

        $user = DoiCuuHo::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->mat_khau, $user->mat_khau)) {
            return response()->json([
                'status' => false,
                'message' => 'Tài khoản sai email hoặc password',
            ], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => $user->load(['thanhViens', 'taiNguyens', 'viTris', 'nangLuc', 'loaiSuCos']),
        ]);
    }

    public function checkThanhVien()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            return response()->json([
                'status' => true,
                'ho_ten' => $user->ho_va_ten,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền truy cập.',
            ]);
        }
    }

    /**
     * Display paginated list of all rescue teams
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $sortBy = $request->get('sort_by', 'id_doi_cuu_ho');
            $sortOrder = $request->get('sort_order', 'desc');

            $items = DoiCuuHo::with(['thanhViens', 'taiNguyens', 'viTris', 'nangLuc', 'loaiSuCos', 'phanCongs'])
                ->orderBy($sortBy, $sortOrder)
                ->paginate($perPage);

            return Response::json([
                'success' => true,
                'message' => 'Danh sách đội cứu hộ',
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
     * Store a newly created rescue team
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ten_co' => 'required|string|max:255',
                'khu_vuc_quan_ly' => 'required|string|max:255',
                'so_dien_thoai_hotline' => 'nullable|string|max:20',
                'vi_tri_lat' => 'nullable|numeric',
                'vi_tri_lng' => 'nullable|numeric',
                'trang_thai' => 'nullable|string|max:30',
                'mo_ta' => 'nullable|string'
            ]);

            $item = DoiCuuHo::create($validated);
            $item->load('thanhViens', 'taiNguyens', 'viTris', 'nangLuc', 'loaiSuCos');

            return Response::json([
                'success' => true,
                'message' => 'Tạo đội cứu hộ thành công',
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
                'message' => 'Lỗi khi tạo đội: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified rescue team
     */
    public function show($id)
    {
        try {
            $item = DoiCuuHo::with(['thanhViens', 'taiNguyens', 'viTris', 'nangLuc', 'loaiSuCos', 'phanCongs'])
                ->findOrFail($id);

            return Response::json([
                'success' => true,
                'message' => 'Chi tiết đội cứu hộ',
                'data' => $item
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified rescue team
     */
    public function update(Request $request, $id)
    {
        try {
            $item = DoiCuuHo::findOrFail($id);

            $validated = $request->validate([
                'ten_co' => 'sometimes|string|max:255',
                'khu_vuc_quan_ly' => 'sometimes|string|max:255',
                'so_dien_thoai_hotline' => 'nullable|string|max:20',
                'vi_tri_lat' => 'nullable|numeric',
                'vi_tri_lng' => 'nullable|numeric',
                'trang_thai' => 'nullable|string|max:30',
                'mo_ta' => 'nullable|string'
            ]);

            $item->update($validated);
            $item->load('thanhViens', 'taiNguyens', 'viTris', 'nangLuc', 'loaiSuCos');

            return Response::json([
                'success' => true,
                'message' => 'Cập nhật đội cứu hộ thành công',
                'data' => $item
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
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
     * Delete the specified rescue team
     */
    public function destroy($id)
    {
        try {
            $item = DoiCuuHo::findOrFail($id);
            $item->delete();

            return Response::json([
                'success' => true,
                'message' => 'Xóa đội cứu hộ thành công'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi khi xóa: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== THÀNH VIÊN ĐỘI ==========

    /**
     * Get team members
     */
    public function getThanhVien($id)
    {
        try {
            DoiCuuHo::findOrFail($id);
            $items = ThanhVienDoi::where('id_doi_cuu_ho', $id)->paginate(15);

            return Response::json([
                'success' => true,
                'message' => 'Danh sách thành viên đội',
                'data' => $items
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add team member
     */
    public function addThanhVien(Request $request, $id)
    {
        try {
            DoiCuuHo::findOrFail($id);

            $validated = $request->validate([
                'ho_ten' => 'required|string|max:255',
                'so_dien_thoai' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'mat_khau' => 'nullable|string|min:6',
                'vai_tro_trong_doi' => 'required|string|max:255',
                'trang_thai' => 'nullable|integer|in:0,1'
            ]);

            $validated['id_doi_cuu_ho'] = $id;
            $item = ThanhVienDoi::create($validated);

            return Response::json([
                'success' => true,
                'message' => 'Thêm thành viên thành công',
                'data' => $item
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
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
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove team member
     */
    public function removeThanhVien($id, $id_thanh_vien)
    {
        try {
            DoiCuuHo::findOrFail($id);
            $item = ThanhVienDoi::where('id_thanh_vien_doi', $id_thanh_vien)
                ->where('id_doi_cuu_ho', $id)
                ->firstOrFail();

            $item->delete();

            return Response::json([
                'success' => true,
                'message' => 'Xóa thành viên thành công'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Thành viên hoặc đội không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== TÀI NGUYÊN ĐỘI ==========

    /**
     * Get team resources
     */
    public function getTaiNguyen($id)
    {
        try {
            DoiCuuHo::findOrFail($id);
            $items = TaiNguyenCuuHo::where('id_doi_cuu_ho', $id)->paginate(15);

            return Response::json([
                'success' => true,
                'message' => 'Danh sách tài nguyên đội',
                'data' => $items
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add team resource
     */
    public function addTaiNguyen(Request $request, $id)
    {
        try {
            DoiCuuHo::findOrFail($id);

            $validated = $request->validate([
                'ten_tai_nguyen' => 'required|string|max:255',
                'loai_tai_nguyen' => 'required|string|max:100',
                'so_luong' => 'required|integer|min:1',
                'trang_thai' => 'nullable|integer|in:0,1'
            ]);

            $validated['id_doi_cuu_ho'] = $id;
            $item = TaiNguyenCuuHo::create($validated);

            return Response::json([
                'success' => true,
                'message' => 'Thêm tài nguyên thành công',
                'data' => $item
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
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
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update team resource
     */
    public function updateTaiNguyen(Request $request, $id, $id_tai_nguyen)
    {
        try {
            DoiCuuHo::findOrFail($id);
            $item = TaiNguyenCuuHo::where('id_tai_nguyen', $id_tai_nguyen)
                ->where('id_doi_cuu_ho', $id)
                ->firstOrFail();

            $validated = $request->validate([
                'ten_tai_nguyen' => 'sometimes|string|max:255',
                'loai_tai_nguyen' => 'sometimes|string|max:100',
                'so_luong' => 'sometimes|integer|min:1',
                'trang_thai' => 'nullable|integer|in:0,1'
            ]);

            $item->update($validated);

            return Response::json([
                'success' => true,
                'message' => 'Cập nhật tài nguyên thành công',
                'data' => $item
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Tài nguyên hoặc đội không tồn tại'
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
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== VỊ TRÍ ĐỘI ==========

    /**
     * Get team locations
     */
    public function getViTri($id)
    {
        try {
            DoiCuuHo::findOrFail($id);
            $items = ViTriDoiCuuHo::where('id_doi_cuu_ho', $id)->paginate(15);

            return Response::json([
                'success' => true,
                'message' => 'Danh sách vị trí đội',
                'data' => $items
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add team location
     */
    public function addViTri(Request $request, $id)
    {
        try {
            DoiCuuHo::findOrFail($id);

            $validated = $request->validate([
                'vi_tri_lat' => 'required|numeric',
                'vi_tri_lng' => 'required|numeric'
            ]);

            $validated['id_doi_cuu_ho'] = $id;
            $item = ViTriDoiCuuHo::create($validated);

            return Response::json([
                'success' => true,
                'message' => 'Thêm vị trí thành công',
                'data' => $item
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
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
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== NĂNG LỰC ĐỘI ==========

    /**
     * Get team capabilities
     */
    public function getNangLuc($id)
    {
        try {
            DoiCuuHo::findOrFail($id);
            $item = NangLucDoi::where('id_doi_cuu_ho', $id)->first();

            if (!$item) {
                return Response::json([
                    'success' => false,
                    'message' => 'Năng lực đội chưa được thiết lập'
                ], 404);
            }

            return Response::json([
                'success' => true,
                'message' => 'Thông tin năng lực đội',
                'data' => $item
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update team capabilities
     */
    public function updateNangLuc(Request $request, $id)
    {
        try {
            DoiCuuHo::findOrFail($id);

            $validated = $request->validate([
                'so_viec_dang_xu_ly' => 'sometimes|integer|min:0',
                'so_viec_toi_da' => 'sometimes|integer|min:0',
                'ty_le_hoan_thanh' => 'sometimes|numeric|between:0,100',
                'thoi_gian_xu_ly_tb' => 'sometimes|numeric|min:0'
            ]);

            $item = NangLucDoi::firstOrCreate(
                ['id_doi_cuu_ho' => $id],
                $validated
            );

            $item->update($validated);

            return Response::json([
                'success' => true,
                'message' => 'Cập nhật năng lực thành công',
                'data' => $item
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
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
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== LOẠI SỰ CỐ ĐỘI XỬ LÝ ==========

    /**
     * Get incident types handled by team
     */
    public function getLoaiSuCoDungXuLy($id)
    {
        try {
            DoiCuuHo::findOrFail($id);
            $items = LoaiSuCo::whereHas('doiCuuHos', function ($query) use ($id) {
                $query->where('id_doi_cuu_ho', $id);
            })->paginate(15);

            return Response::json([
                'success' => true,
                'message' => 'Danh sách loại sự cố xử lý',
                'data' => $items
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ không tồn tại'
            ], 404);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add incident type capability to team
     */
    public function addLoaiSuCoDungXuLy(Request $request, $id)
    {
        try {
            DoiCuuHo::findOrFail($id);

            $validated = $request->validate([
                'id_loai_su_co' => 'required|integer|exists:loai_su_co,id_loai_su_co'
            ]);

            // Check if already exists
            $existing = DoiCuuHoLoaiSuCo::where('id_doi_cuu_ho', $id)
                ->where('id_loai_su_co', $validated['id_loai_su_co'])
                ->first();

            if ($existing) {
                return Response::json([
                    'success' => false,
                    'message' => 'Loại sự cố này đã có trong danh sách'
                ], 409);
            }

            $item = DoiCuuHoLoaiSuCo::create([
                'id_doi_cuu_ho' => $id,
                'id_loai_su_co' => $validated['id_loai_su_co']
            ]);

            $item->load('loaiSuCo');

            return Response::json([
                'success' => true,
                'message' => 'Thêm loại sự cố thành công',
                'data' => $item
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Response::json([
                'success' => false,
                'message' => 'Đội cứu hộ hoặc loại sự cố không tồn tại'
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
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== FILTER & SEARCH ==========

    /**
     * Get teams by status
     */
    public function getByStatus($trang_thai)
    {
        try {
            $items = DoiCuuHo::where('trang_thai', $trang_thai)
                ->with(['thanhViens', 'taiNguyens', 'nangLuc'])
                ->paginate(15);

            return Response::json([
                'success' => true,
                'message' => 'Danh sách đội theo trạng thái',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teams by region
     */
    public function getByKhuVuc($khu_vuc)
    {
        try {
            $items = DoiCuuHo::where('khu_vuc_quan_ly', 'like', '%' . $khu_vuc . '%')
                ->with(['thanhViens', 'taiNguyens', 'nangLuc'])
                ->paginate(15);

            return Response::json([
                'success' => true,
                'message' => 'Danh sách đội theo khu vực',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get team efficiency statistics
     */
    public function getTeamEfficiency(Request $request)
    {
        try {
            $data = DoiCuuHo::with('nangLuc', 'phanCongs')
                ->get()
                ->map(function ($team) {
                    return [
                        'id_doi_cuu_ho' => $team->id_doi_cuu_ho,
                        'ten_co' => $team->ten_co,
                        'so_nhan_su' => $team->thanhViens()->count(),
                        'so_tai_nguyen' => $team->taiNguyens()->count(),
                        'so_nhiem_vu_dang_xy_ly' => $team->phanCongs()
                            ->where('trang_thai_nhiem_vu', 'DANG_XU_LY')->count(),
                        'ty_le_hoan_thanh' => $team->nangLuc?->ty_le_hoan_thanh ?? 0,
                        'thoi_gian_xu_ly_tb' => $team->nangLuc?->thoi_gian_xu_ly_tb ?? 0
                    ];
                });

            return Response::json([
                'success' => true,
                'message' => 'Thống kê hiệu suất đội cứu hộ',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available teams
     */
    public function getAvailableTeams(Request $request)
    {
        try {
            $items = DoiCuuHo::where('trang_thai', 'SAN_SANG')
                ->with(['thanhViens', 'taiNguyens', 'nangLuc', 'phanCongs'])
                ->get()
                ->filter(function ($team) {
                    $currentAssignments = $team->phanCongs()
                        ->where('trang_thai_nhiem_vu', 'DANG_XU_LY')->count();
                    $capacity = $team->nangLuc?->so_viec_toi_da ?? 999;
                    return $currentAssignments < $capacity;
                });

            return Response::json([
                'success' => true,
                'message' => 'Danh sách đội có sẵn',
                'data' => $items->values()
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search teams
     */
    public function search(Request $request)
    {
        try {
            $keyword = $request->get('noi_dung_tim', '');

            $items = DoiCuuHo::where('ten_co', 'like', '%' . $keyword . '%')
                ->orWhere('khu_vuc_quan_ly', 'like', '%' . $keyword . '%')
                ->with(['thanhViens', 'taiNguyens', 'nangLuc'])
                ->paginate(15);

            return Response::json([
                'success' => true,
                'message' => 'Kết quả tìm kiếm',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
