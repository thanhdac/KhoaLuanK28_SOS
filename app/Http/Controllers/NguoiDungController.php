<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NguoiDungController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'nullable|string',
            'password' => 'nullable|string',
        ]);

        $password = $request->input('mat_khau', $request->input('password'));
        $user = NguoiDung::where('email', $request->email)->first();

        if (!$user || !$password) {
            return response()->json([
                'status' => false,
                'message' => 'Tai khoan sai email hoac password',
            ], 401);
        }

        if ($user->mat_khau === $password) {
            $user->mat_khau = Hash::make($password);
            $user->save();
        }

        if (!Hash::check($password, $user->mat_khau)) {
            return response()->json([
                'status' => false,
                'message' => 'Tai khoan sai email hoac password',
            ], 401);
        }

        $token = $user->createToken('nguoi-dung-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Dang nhap thanh cong',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => $user->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|regex:/^0\d{9,10}$/',
            'email' => 'required|email|unique:nguoi_dung,email',
            'mat_khau' => 'required|string|min:6',
        ]);

        $user = new NguoiDung();
        $user->ho_ten = $request->ho_ten;
        $user->email = $request->email;
        $user->so_dien_thoai = $request->so_dien_thoai;
        $user->mat_khau = Hash::make($request->mat_khau);
        $user->trang_thai = 1;
        $user->save();

        $token = $user->createToken('nguoi-dung-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Dang ky thanh cong',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => $user->makeHidden(['mat_khau', 'api_token']),
        ], 201);
    }

    public function checkClient()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user || !($user instanceof NguoiDung)) {
            return response()->json([
                'status' => false,
                'message' => 'Vui long dang nhap de su dung',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'data' => $user->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function getProfile()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user || !($user instanceof NguoiDung)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'data' => $user->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user || !($user instanceof NguoiDung)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $user->update([
            'ho_ten' => $request->ho_ten ?? $user->ho_ten,
            'email' => $request->email ?? $user->email,
            'so_dien_thoai' => $request->so_dien_thoai ?? $user->so_dien_thoai,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cap nhat thong tin thanh cong',
            'data' => $user->fresh()->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => NguoiDung::get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nguoi_dung,email',
            'so_dien_thoai' => 'required|string',
        ]);

        $user = NguoiDung::create([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'mat_khau' => Hash::make($request->mat_khau ?? '123456'),
            'trang_thai' => $request->trang_thai ?? 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Them nguoi dung thanh cong',
            'data' => $user->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function show($id)
    {
        $user = NguoiDung::where('id_nguoi_dung', $id)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Nguoi dung khong ton tai',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = NguoiDung::where('id_nguoi_dung', $id)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Nguoi dung khong ton tai',
            ], 404);
        }

        $updateData = [
            'ho_ten' => $request->ho_ten ?? $user->ho_ten,
            'email' => $request->email ?? $user->email,
            'so_dien_thoai' => $request->so_dien_thoai ?? $user->so_dien_thoai,
            'trang_thai' => $request->trang_thai ?? $user->trang_thai,
        ];

        if ($request->filled('mat_khau')) {
            $updateData['mat_khau'] = Hash::make($request->mat_khau);
        }

        $user->update($updateData);

        return response()->json([
            'status' => true,
            'message' => 'Cap nhat nguoi dung thanh cong',
            'data' => $user->fresh()->makeHidden(['mat_khau', 'api_token']),
        ]);
    }

    public function destroy($id)
    {
        $user = NguoiDung::where('id_nguoi_dung', $id)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Nguoi dung khong ton tai',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xoa nguoi dung thanh cong',
        ]);
    }

    public function search(Request $request)
    {
        $keyword = '%' . $request->noi_dung_tim . '%';
        $data = NguoiDung::where('ho_ten', 'like', $keyword)
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
        $user = NguoiDung::where('id_nguoi_dung', $id)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Nguoi dung khong ton tai',
            ], 404);
        }

        $user->trang_thai = $user->trang_thai == 1 ? 0 : 1;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Cap nhat trang thai thanh cong',
            'data' => $user->makeHidden(['mat_khau', 'api_token']),
        ]);
    }
}
