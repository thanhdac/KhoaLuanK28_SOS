<?php

namespace App\Http\Controllers;

use App\Models\{ChucNang};
use Illuminate\Http\Request;

class ChucNangController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $items = ChucNang::paginate($perPage);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_chuc_nang' => 'required|string|max:255'
        ]);
        $item = ChucNang::create($validated);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = ChucNang::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = ChucNang::findOrFail($id);
        $validated = $request->validate([
            'ten_chuc_nang' => 'sometimes|string|max:255'
        ]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = ChucNang::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}