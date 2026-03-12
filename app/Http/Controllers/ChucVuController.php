<?php

namespace App\Http\Controllers;

use App\Models\{ChucVu};
use Illuminate\Http\Request;

class ChucVuController extends Controller
{
    public function index()
    {
        $items = ChucVu::paginate(15);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([]);
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
        $validated = $request->validate([]);
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