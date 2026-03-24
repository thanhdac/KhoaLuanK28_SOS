<?php

namespace App\Http\Controllers;

use App\Models\ThanhVienDoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ThanhVienDoiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required',
        ]);

        $user = ThanhVienDoi::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->mat_khau, $user->mat_khau)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => $user->load('doiCuuHo'),
        ]);
    }

    public function index()
    {
        $thanhVien = ThanhVienDoi::with('doiCuuHo')->get();
        return response()->json($thanhVien);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required',
            'email' => 'required|email|unique:thanh_vien_dois,email',
            'so_dien_thoai' => 'required',
            'vai_tro_trong_doi' => 'required',
            'mat_khau' => 'required',
            'id_doi_cuu_ho' => 'nullable|exists:doi_cuu_hos,id_doi_cuu_ho',
        ]);

        $data = $request->all();
        $data['mat_khau'] = Hash::make($data['mat_khau']);
        $data['trang_thai'] = 1;

        $thanhVien = ThanhVienDoi::create($data);
        return response()->json([
            'status' => true,
            'message' => 'Thêm thành viên thành công',
            'data' => $thanhVien
        ]);
    }

    public function update(Request $request, $id)
    {
        $thanhVien = ThanhVienDoi::findOrFail($id);

        $data = $request->only(['ho_ten', 'email', 'so_dien_thoai', 'vai_tro_trong_doi', 'id_doi_cuu_ho']);
        if ($request->has('mat_khau') && !empty($request->mat_khau)) {
            $data['mat_khau'] = Hash::make($request->mat_khau);
        }

        $thanhVien->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thành công',
            'data' => $thanhVien
        ]);
    }

    public function destroy($id)
    {
        $thanhVien = ThanhVienDoi::findOrFail($id);
        $thanhVien->delete();
        return response()->json([
            'status' => true,
            'message' => 'Xóa thành công'
        ]);
    }

    public function updateStatus($id)
    {
        $thanhVien = ThanhVienDoi::findOrFail($id);
        $thanhVien->trang_thai = $thanhVien->trang_thai == 1 ? 0 : 1;
        $thanhVien->save();
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'data' => $thanhVien
        ]);
    }
}
