<?php

namespace App\Http\Controllers;

use App\Models\{DanhGiaCuuHo};
use Illuminate\Http\Request;

class DanhGiaCuuHoController extends Controller
{
    public function index()
    {
        $items = DanhGiaCuuHo::paginate(15);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([]);
        $item = DanhGiaCuuHo::create($validated);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = DanhGiaCuuHo::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = DanhGiaCuuHo::findOrFail($id);
        $validated = $request->validate([]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = DanhGiaCuuHo::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}