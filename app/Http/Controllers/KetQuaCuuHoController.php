<?php

namespace App\Http\Controllers;

use App\Models\{KetQuaCuuHo};
use Illuminate\Http\Request;

class KetQuaCuuHoController extends Controller
{
    public function index()
    {
        $items = KetQuaCuuHo::paginate(15);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([]);
        $item = KetQuaCuuHo::create($validated);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = KetQuaCuuHo::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = KetQuaCuuHo::findOrFail($id);
        $validated = $request->validate([]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = KetQuaCuuHo::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}