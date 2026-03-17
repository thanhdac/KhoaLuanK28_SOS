<?php

namespace App\Http\Controllers;

use App\Models\{DanhGiaCuuHo, YeuCauCuuHo};
use Illuminate\Http\Request;

class DanhGiaCuuHoController extends Controller
{
    public function index()
    {
        $items = DanhGiaCuuHo::with(['yeuCau', 'nguoiDung'])->paginate(15);
        return response()->json([
            'status' => true,
            'data' => $items->items(),
            'pagination' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_yeu_cau' => 'required|numeric',
            'id_nguoi_dung' => 'required|numeric',
            'diem_danh_gia' => 'required|numeric|between:1,5',
            'noi_dung_danh_gia' => 'nullable|string',
        ]);

        $item = DanhGiaCuuHo::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Tạo đánh giá thành công',
            'data' => $item
        ], 201);
    }

    public function show($id)
    {
        $item = DanhGiaCuuHo::with(['yeuCau', 'nguoiDung'])->findOrFail($id);
        return response()->json([
            'status' => true,
            'data' => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = DanhGiaCuuHo::findOrFail($id);
        $validated = $request->validate([
            'diem_danh_gia' => 'sometimes|numeric|between:1,5',
            'noi_dung_danh_gia' => 'nullable|string',
        ]);
        $item->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật đánh giá thành công',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = DanhGiaCuuHo::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa đánh giá thành công'
        ], 200);
    }

    /**
     * Get ratings for a help request
     */
    public function getByYeuCau($id_yeu_cau)
    {
        $yeuCau = YeuCauCuuHo::findOrFail($id_yeu_cau);
        $items = DanhGiaCuuHo::where('id_yeu_cau', $id_yeu_cau)
            ->with(['yeuCau', 'nguoiDung'])
            ->get();

        return response()->json([
            'status' => true,
            'data' => $items,
            'count' => $items->count()
        ]);
    }

    /**
     * Create rating for a help request
     */
    public function createForYeuCau(Request $request, $id_yeu_cau)
    {
        $yeuCau = YeuCauCuuHo::findOrFail($id_yeu_cau);

        $validated = $request->validate([
            'id_nguoi_dung' => 'required|numeric',
            'diem_danh_gia' => 'required|numeric|between:1,5',
            'noi_dung_danh_gia' => 'nullable|string',
        ]);

        $validated['id_yeu_cau'] = $id_yeu_cau;
        $item = DanhGiaCuuHo::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Tạo đánh giá cho yêu cầu thành công',
            'data' => $item
        ], 201);
    }
}
