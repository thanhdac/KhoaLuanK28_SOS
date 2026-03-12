<?php

namespace App\Http\Controllers;

use App\Models\{PhanCongCuuHo};
use Illuminate\Http\Request;

class PhanCongCuuHoController extends Controller
{
    public function index()
    {
        $items = PhanCongCuuHo::paginate(15);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([]);
        $item = PhanCongCuuHo::create($validated);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = PhanCongCuuHo::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = PhanCongCuuHo::findOrFail($id);
        $validated = $request->validate([]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = PhanCongCuuHo::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}