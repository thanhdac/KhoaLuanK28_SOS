<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;

class NguoiDungController extends Controller
{
    // ===== USER AUTHENTICATION =====

    public function login(Request $request)
    {
        $check = NguoiDung::where('email', $request->email)
            ->where('mat_khau', $request->mat_khau)->first();

        if ($check) {
            return response()->json([
                'status'    => true,
                'message'   => 'Đăng nhập thành công',
                'data'      => $check,
            ]);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => 'Tài khoản sai email hoặc password',
            ]);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'so_dien_thoai' => 'required|string|regex:/^0\d{9,10}$/',
            'email'         => 'required|email|unique:nguoi_dung,email',
        ], [
            'so_dien_thoai.required'  => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.regex'     => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng (bắt đầu bằng 0, có 10 hoặc 11 số).',
            'email.required'          => 'Vui lòng nhập email.',
            'email.email'             => 'Email không đúng định dạng.',
            'email.unique'            => 'Email đã tồn tại trong hệ thống.',
        ]);

        $nguoiDung = new NguoiDung();
        $nguoiDung->ho_ten = $request->ho_ten;
        $nguoiDung->email = $request->email;
        $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
        $nguoiDung->mat_khau = $request->mat_khau;
        $nguoiDung->trang_thai = 1;
        $nguoiDung->save();

        return response()->json([
            'status'    => true,
            'message'   => 'Đăng ký thành công',
            'data'      => $nguoiDung,
        ]);
    }

    // ===== USERS CRUD =====

    public function index()
    {
        $data = NguoiDung::get();
        return response()->json([
            'status'    => true,
            'data'      => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ho_ten'        => 'required|string|max:255',
            'email'         => 'required|email|unique:nguoi_dung,email',
            'so_dien_thoai' => 'required|string',
        ]);

        $user = NguoiDung::create([
            'ho_ten'        => $request->ho_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'mat_khau'      => $request->mat_khau ?? '123456',
            'trang_thai'    => $request->trang_thai ?? 1,
        ]);

        return response()->json([
            'status'    => true,
            'message'   => 'Thêm người dùng ' . $request->ho_ten . ' thành công',
            'data'      => $user,
        ]);
    }

    public function show($id)
    {
        $user = NguoiDung::where('id_nguoi_dung', $id)->first();

        if (!$user) {
            return response()->json([
                'status'    => false,
                'message'   => 'Người dùng không tồn tại!',
            ]);
        }

        return response()->json([
            'status'    => true,
            'data'      => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = NguoiDung::where('id_nguoi_dung', $id)->first();

        if (!$user) {
            return response()->json([
                'status'    => false,
                'message'   => 'Người dùng không tồn tại!',
            ]);
        }

        $updateData = [
            'ho_ten'        => $request->ho_ten ?? $user->ho_ten,
            'email'         => $request->email ?? $user->email,
            'mat_khau'      => $request->mat_khau ?? $user->mat_khau,
            'so_dien_thoai' => $request->so_dien_thoai ?? $user->so_dien_thoai,
            'trang_thai'    => $request->trang_thai ?? $user->trang_thai,
        ];

        NguoiDung::where('id_nguoi_dung', $id)->update($updateData);

        return response()->json([
            'status'    => true,
            'message'   => 'Cập nhật người dùng ' . $user->ho_ten . ' thành công',
            'data'      => NguoiDung::find($id),
        ]);
    }

    public function destroy($id)
    {
        $user = NguoiDung::where('id_nguoi_dung', $id)->first();

        if (!$user) {
            return response()->json([
                'status'    => false,
                'message'   => 'Người dùng không tồn tại!',
            ]);
        }

        $ho_ten = $user->ho_ten;
        NguoiDung::where('id_nguoi_dung', $id)->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Xóa người dùng ' . $ho_ten . ' thành công',
        ]);
    }

    // ===== UTILITIES =====

    public function search(Request $request)
    {
        $noi_dung_tim = '%' . $request->noi_dung_tim . '%';
        $data = NguoiDung::where('ho_ten', 'like', $noi_dung_tim)
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
        $user = NguoiDung::where('id_nguoi_dung', $id)->first();

        if (!$user) {
            return response()->json([
                'status'    => false,
                'message'   => 'Người dùng không tồn tại!',
            ]);
        }

        $user->trang_thai = $user->trang_thai == 1 ? 0 : 1;
        $user->save();

        return response()->json([
            'status'    => true,
            'message'   => 'Cập nhật trạng thái ' . $user->ho_ten . ' thành công',
            'data'      => $user,
        ]);
    }
}
