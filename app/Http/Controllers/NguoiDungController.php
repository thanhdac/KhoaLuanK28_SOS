<?php

namespace App\Http\Controllers;

use App\Http\Requests\NguoiDungRequest;
use App\Models\{NguoiDung};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NguoiDungController extends Controller
{
    public function getData()
    {
        $data = NguoiDung::all();
        return response()->json([
            'data' => $data
        ]);
    }

    public function addData(Request $request)
    {
        NguoiDung::create([
            'ho_va_ten'     => $request->ho_va_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'password'      => '123456',
            'cccd'          => $request->cccd,
            'ngay_sinh'     => $request->ngay_sinh,
            'is_block'      => 0,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Thêm khách hàng ' . $request->ho_va_ten . ' thành công',
        ]);
    }

    public function update(Request $request)
    {
        NguoiDung::where('id', $request->id)->update([
            'ho_va_ten'     => $request->ho_va_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'password'      => $request->password,
            'cccd'          => $request->cccd,
            'ngay_sinh'     => $request->ngay_sinh,
            'is_block'      => $request->is_block,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật khách hàng ' . $request->ho_va_ten . ' thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        NguoiDung::where('id', $request->id)->delete();
        return response()->json([
            'status' => true,
            'message' => 'Xóa khách hàng thành công',
        ]);
    }

    public function changeStatus(Request $request)
    {
        $nhan_vien = NguoiDung::where('id', $request->id)->first();
        $nhan_vien->is_block = !$nhan_vien->is_block;
        $nhan_vien->save();

        return response()->json([
            'status' => true,
            'message' => 'Thay đổi trạng thái thành công',
        ]);
    }

    public function dangNhap(Request $request)
    {
        $check = NguoiDung::where('email', $request->email)
            ->where('password', $request->password)->first();
        if ($check) {
            if ($check->is_active == 0) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Tài khoản chưa được kích hoạt',
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Đăng nhập thành công',
                    'token' => $check->createToken('key_client')->plainTextToken,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tài khoản sai email hoặc password',
            ]);
        }
    }

    public function checkClient()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            return response()->json([
                'status' => true,
                'ho_ten' => $user->ho_va_ten,
                'email' => $user->email
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền truy cập.',
            ]);
        }
    }

    public function kichHoat(Request $request)
    {
        $user = NguoiDung::where('hash_active', $request->hash_active)->update([
            'is_active' => 1
        ]);
        return response()->json([
            'status'    =>  1,
            'message'   =>  'Đã kích hoạt tài khoản thành công'
        ]);
    }

    public function dangKy(NguoiDungLoginRequest $request)
    {
        $key = Str::uuid();
        $khachHang = NguoiDung::create([
            'ho_va_ten'     => $request->ho_va_ten,
            'email'         => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'password'      => $request->password,
            'cccd'          => $request->cccd,
            'ngay_sinh'     => $request->ngay_sinh,
            'is_block'      => 0,
            'is_active'     => 0,
            'hash_active'   => $key,
        ]);

        $tieu_de = "Kích hoạt tài khoản";
        $view = "kichHoatTK";
        $noi_dung['ho_va_ten'] = $khachHang->ho_va_ten;
        $noi_dung['link'] = "http://localhost:5173/client/kich-hoat/" . $key;
        // Mail::to($request->email)->send(new MasterMail($tieu_de, $view, $noi_dung));

        return response()->json([
            'status' => true,
            'message' => 'Đã đăng ký tài khoản thành công. ',
        ]);
    }

    public function quenMatKhau(Request $request)
    {
        $user = NguoiDung::where('email', $request->email)->first();

        if ($user) {
            $key = Str::uuid();
            $tieu_de = "Quên mật khẩu";
            $view = "quenMatKhau";
            $noi_dung['ho_va_ten'] = $user->ho_va_ten;
            $noi_dung['link'] = "http://localhost:5173/client/dat-lai-mat-khau/" . $key;

            $user->hash_reset = $key;
            $user->save();

            // Mail::to($user->email)->send(new MasterMail($tieu_de, $view, $noi_dung));

        //     return response()->json([
        //         'status' => true,
        //         'message' => 'Vui lòng kiểm tra email để đổi mật khẩu.',
        //     ]);
        // } else {
            return response()->json([
                'status' => false,
                'message' => 'Tài khoản chưa được đăng ký.',
            ]);
        }
    }

    public function datLaiMatKhau(Request $request)
    {
        $user = NguoiDung::where('hash_reset', $request->hash_reset)->first();

        if ($request->password != $request->re_password) {
            return response()->json([
                'status' => false,
                'message' => 'Mật khẩu và xác nhận mật khẩu không khớp.',
            ]);
        }

        $user->update([
            'password'      =>  $request->password,
            'hash_reset'    =>  null
        ]);

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Đổi mật khẩu thành công.',
        ]);
    }

    public function thongTinNguoiDung()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\NguoiDung) {
            return response()->json([
                'data'  => $user,
            ]);
        }
    }

    public function doiMatKhau(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\NguoiDung) {

            if ($user->password != $request->password) {
                return response()->json([
                    'status'    =>  0,
                    'message'   =>  'Mật khẩu hiện tại không đúng. Vui lòng nhập lại.'
                ]);
            } else {
                if ($request->new_password != $request->confirm_password) {
                    return response()->json([
                        'status'    =>  0,
                        'message'   =>  'Mật khẩu hiện mới và xác nhận không khớp. Vui lòng nhập lại.'
                    ]);
                } else {
                    $user->password = $request->new_password;
                    $user->save();

                    return response()->json([
                        'status'    =>  1,
                        'message'   =>  'Đổi mật khẩu thành công.'
                    ]);
                }
            }
        }
    }
    // public function xuatExcel()
    // {
    //     $data = NguoiDung::all();


    //     return Excel::download(new KhachHangExport($data), "khach_hang.xlsx");
    // }
    public function search(Request $request)
    {
        $noi_dung = '%' . $request->noi_dung . '%';
        $data = NguoiDung::where('ho_va_ten', 'like', $noi_dung)
            ->orWhere('email', 'like', $noi_dung)
            ->orWhere('so_dien_thoai', 'like', $noi_dung)
            ->orWhere('cccd', 'like', $noi_dung)
            ->get();
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function capNhatThongTin(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền.'
            ]);
        }

        $user->update([
            'ho_va_ten'     => $request->ho_va_ten,
            'so_dien_thoai' => $request->so_dien_thoai,

        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thông tin thành công.',
            'data' => $user
        ]);
    }
}
