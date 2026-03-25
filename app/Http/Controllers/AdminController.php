<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use App\Models\PhanQuyen;
use App\Support\AuthHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    // ===== ADMIN AUTHENTICATION =====

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required',
        ]);

        $user = Admin::where('email', $request->email)->first();

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
            'data' => $user->load('chucVu'),
        ]);
    }

    public function checkAdmin(Request $request)
    {
        // Lấy token từ header
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token not provided',
            ], 401);
        }

        // Kiểm tra user từ token
        $user = Auth::guard('sanctum')->user();
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token',
            ], 401);
        }

        // Kiểm tra nếu không phải admin
        if (!($user instanceof Admin)) {
            return response()->json([
                'status' => false,
                'message' => 'Not an admin user',
            ], 403);
        }

        // Kiểm tra account có active không
        if ($user->trang_thai != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Account is inactive',
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Token valid',
            'data' => [
                'id_admin' => $user->id_admin,
                'ho_ten' => $user->ho_ten,
                'email' => $user->email,
                'so_dien_thoai' => $user->so_dien_thoai,
                'chuc_vu' => $user->chucVu?->ten_chuc_vu,
                'trang_thai' => $user->trang_thai,
            ],
        ]);
    }

    public function getProfile()
    {
        if (!AuthHelper::isAdmin()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $admin = AuthHelper::user()->load('chucVu');

        return response()->json([
            'status' => true,
            'data' => $admin,
        ]);
    }

    public function logout(Request $request)
    {
        if (!AuthHelper::isAdmin()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $request->user('sanctum')->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Đăng xuất thành công',
        ]);
    }



    // ===== ADMIN CRUD =====

    public function index()
    {
        $data = Admin::with('chucVu')->get();
        return response()->json([
            'status'    => true,
            'data'      => $data,
        ]);
    }

    public function store(Request $request)
    {
        $admin = Admin::create([
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'mat_khau'      => Hash::make($request->mat_khau),
            'so_dien_thoai' => $request->so_dien_thoai,
            'id_chuc_vu'    => $request->id_chuc_vu,
            'trang_thai'    => $request->trang_thai ?? 1,
        ]);

        return response()->json([
            'status'    => true,
            'message'   => 'Thêm admin ' . $request->ho_ten . ' thành công',
            'data'      => $admin,
        ]);
    }

    public function show($id)
    {
        $admin = Admin::where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status'    => false,
                'message'   => 'Admin không tồn tại!',
            ]);
        }

        return response()->json([
            'status'    => true,
            'data'      => $admin,
        ]);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status'    => false,
                'message'   => 'Admin không tồn tại!',
            ]);
        }

        $updateData = [
            'ho_ten'        => $request->ho_ten ?? $admin->ho_ten,
            'email'         => $request->email ?? $admin->email,
            'mat_khau'      => $request->has('mat_khau') ? Hash::make($request->mat_khau) : $admin->mat_khau,
            'so_dien_thoai' => $request->so_dien_thoai ?? $admin->so_dien_thoai,
            'id_chuc_vu'    => $request->id_chuc_vu ?? $admin->id_chuc_vu,
            'trang_thai'    => $request->trang_thai ?? $admin->trang_thai,
        ];

        Admin::where('id_admin', $id)->update($updateData);

        return response()->json([
            'status'    => true,
            'message'   => 'Cập nhật admin ' . $admin->ho_ten . ' thành công',
            'data'      => Admin::find($id),
        ]);
    }

    public function destroy($id)
    {
        $admin = Admin::where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status'    => false,
                'message'   => 'Admin không tồn tại!',
            ]);
        }

        $ho_ten = $admin->ho_ten;
        Admin::where('id_admin', $id)->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Xóa admin ' . $ho_ten . ' thành công',
        ]);
    }

    // ===== ADMIN UTILITIES =====

    public function search(Request $request)
    {
        $noi_dung_tim = '%' . $request->noi_dung_tim . '%';
        $data = Admin::where('ho_ten', 'like', $noi_dung_tim)
            ->orWhere('email', 'like', $noi_dung_tim)
            ->orWhere('so_dien_thoai', 'like', $noi_dung_tim)
            ->get();

        return response()->json([
            'status'    => true,
            'data'      => $data,
        ]);
    }

    public function changeStatus($id)
    {
        $admin = Admin::where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status'    => false,
                'message'   => 'Admin không tồn tại!',
            ]);
        }

        $admin->trang_thai = $admin->trang_thai == 1 ? 0 : 1;
        $admin->save();

        return response()->json([
            'status'    => true,
            'message'   => 'Cập nhật trạng thái ' . $admin->ho_ten . ' thành công',
            'data'      => $admin,
        ]);
    }

    public function active($id)
    {
        $admin = Admin::where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status'    => false,
                'message'   => 'Admin không tồn tại!',
            ]);
        }

        if ($admin->trang_thai == 1) {
            return response()->json([
                'status'    => false,
                'message'   => 'Tài khoản đã được kích hoạt trước đó',
            ]);
        }

        $admin->trang_thai = 1;
        $admin->save();

        return response()->json([
            'status'    => true,
            'message'   => 'Kích hoạt tài khoản thành công',
            'data'      => $admin,
        ]);
    }
}
