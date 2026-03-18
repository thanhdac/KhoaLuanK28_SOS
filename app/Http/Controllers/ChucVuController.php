<?php

namespace App\Http\Controllers;

use App\Models\{ChucVu};
use Illuminate\Http\Request;

class ChucVuController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $items = ChucVu::paginate($perPage);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_chuc_vu' => 'required|string|max:255',
            'slug_chuc_vu' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string',
            'tinh_trang' => 'nullable|integer|in:0,1'
        ]);
        $item = ChucVu::create($validated);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = ChucVu::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = ChucVu::findOrFail($id);
        $validated = $request->validate([
            'ten_chuc_vu' => 'sometimes|string|max:255',
            'slug_chuc_vu' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string',
            'tinh_trang' => 'nullable|integer|in:0,1'
        ]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = ChucVu::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}