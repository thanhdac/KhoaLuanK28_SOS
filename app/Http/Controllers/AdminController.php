<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use App\Models\PhanQuyen;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ===== ADMIN AUTHENTICATION =====

    public function login(Request $request)
    {
        $check = Admin::where('email', $request->email)
            ->where('mat_khau', $request->mat_khau)->first();

        if ($check) {
            return response()->json([
                'status' => true,
                'message' => 'Đăng nhập thành công',
                'data' => $check,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tài khoản sai email hoặc password',
            ]);
        }
    }

    // ===== ADMIN CRUD =====

    public function index()
    {
        $data = Admin::get();
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
            'mat_khau'      => $request->mat_khau,
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
            'mat_khau'      => $request->mat_khau ?? $admin->mat_khau,
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
