<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class NguoiDungController extends Controller
{
    // ===== USER AUTHENTICATION =====

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required',
        ]);

        $user = NguoiDung::where('email', $request->email)->first();

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
            'data' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|digits:10|regex:/^0\d+$/',

            'email' => 'required|email|unique:nguoi_dung,email',
            'mat_khau' => 'required|string|min:6',
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ và tên.',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng (bắt đầu bằng 0, có 10 số).',
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 10 ký tự.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min' => 'Mật khẩu tối thiểu 6 ký tự.',
        ]);

        $nguoiDung = new NguoiDung();
        $nguoiDung->ho_ten = $request->ho_ten;
        $nguoiDung->email = $request->email;
        $nguoiDung->so_dien_thoai = $request->so_dien_thoai;
        $nguoiDung->mat_khau = Hash::make($request->mat_khau);
        $nguoiDung->trang_thai = 1;

        $nguoiDung->save();

        return response()->json([
            'status' => true,
            'message' => 'Đăng ký thành công',
            'data' => $nguoiDung,
        ], 201);
    }

    public function checkClient()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            return response()->json([
                'status' => true,
                'ho_ten' => $user->ho_ten,
                'email' => $user->email
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Vui lòng đăng nhập để sử dụng.',
            ]);
        }
    }

    public function getProfile()
    {
        $client = Auth::guard('sanctum')->user(); // lấy user từ token
        return response()->json([
            'status' => true,
            'data'   => $client
        ]);
    }

    public function updateProfile(Request $request)
    {
        $client = Auth::guard('sanctum')->user();

        if ($client) {
            $client->update([
                'ho_ten'     => $request->ho_ten,
                'email'         => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Cập nhật thông tin thành công',
                'data'    => $client
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Không thể cập nhật thông tin'
        ], 400);
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
            'mat_khau'      => Hash::make($request->mat_khau ?? '123456'),
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
            'mat_khau'      => $request->has('mat_khau') ? Hash::make($request->mat_khau) : $user->mat_khau,
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
