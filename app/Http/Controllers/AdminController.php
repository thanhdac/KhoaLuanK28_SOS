<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'nullable|string',
            'password' => 'nullable|string',
        ]);

        $password = $request->input('mat_khau', $request->input('password'));
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !$password) {
            return response()->json([
                'status' => false,
                'message' => 'Tai khoan sai email hoac password',
            ], 401);
        }

        if ($admin->mat_khau === $password) {
            $admin->mat_khau = Hash::make($password);
            $admin->save();
        }

        if (!Hash::check($password, $admin->mat_khau)) {
            return response()->json([
                'status' => false,
                'message' => 'Tai khoan sai email hoac password',
            ], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Dang nhap thanh cong',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => $admin->load('chucVu')->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function checkAdmin()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user || !($user instanceof Admin)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Token valid',
            'data' => $user->load('chucVu')->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function getProfile()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user || !($user instanceof Admin)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'data' => $user->load('chucVu')->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user('sanctum');

        if (!$user || !($user instanceof Admin)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $token = $user->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Dang xuat thanh cong',
        ]);
    }

    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Admin::with('chucVu')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $admin = Admin::create([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'mat_khau' => Hash::make($request->mat_khau ?? '123456'),
            'so_dien_thoai' => $request->so_dien_thoai,
            'id_chuc_vu' => $request->id_chuc_vu,
            'trang_thai' => $request->trang_thai ?? 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Them admin thanh cong',
            'data' => $admin->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function show($id)
    {
        $admin = Admin::with('chucVu')->where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin khong ton tai',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $admin->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin khong ton tai',
            ], 404);
        }

        $updateData = [
            'ho_ten' => $request->ho_ten ?? $admin->ho_ten,
            'email' => $request->email ?? $admin->email,
            'so_dien_thoai' => $request->so_dien_thoai ?? $admin->so_dien_thoai,
            'id_chuc_vu' => $request->id_chuc_vu ?? $admin->id_chuc_vu,
            'trang_thai' => $request->trang_thai ?? $admin->trang_thai,
        ];

        if ($request->filled('mat_khau')) {
            $updateData['mat_khau'] = Hash::make($request->mat_khau);
        }

        $admin->update($updateData);

        return response()->json([
            'status' => true,
            'message' => 'Cap nhat admin thanh cong',
            'data' => $admin->fresh()->load('chucVu')->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function destroy($id)
    {
        $admin = Admin::where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin khong ton tai',
            ], 404);
        }

        $admin->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xoa admin thanh cong',
        ]);
    }

    public function search(Request $request)
    {
        $keyword = '%' . $request->noi_dung_tim . '%';
        $data = Admin::with('chucVu')
            ->where('ho_ten', 'like', $keyword)
            ->orWhere('email', 'like', $keyword)
            ->orWhere('so_dien_thoai', 'like', $keyword)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function changeStatus($id)
    {
        $admin = Admin::where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin khong ton tai',
            ], 404);
        }

        $admin->trang_thai = $admin->trang_thai == 1 ? 0 : 1;
        $admin->save();

        return response()->json([
            'status' => true,
            'message' => 'Cap nhat trang thai thanh cong',
            'data' => $admin->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function active($id)
    {
        $admin = Admin::where('id_admin', $id)->first();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Admin khong ton tai',
            ], 404);
        }

        if ($admin->trang_thai == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Tai khoan da duoc kich hoat',
            ], 400);
        }

        $admin->trang_thai = 1;
        $admin->save();

        return response()->json([
            'status' => true,
            'message' => 'Kich hoat tai khoan thanh cong',
            'data' => $admin->makeHidden(['mat_khau', 'api_token']),
        ]);
    }
}
