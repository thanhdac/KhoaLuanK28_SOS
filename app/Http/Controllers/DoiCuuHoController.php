<?php

namespace App\Http\Controllers;

use App\Models\{DoiCuuHo};
use Illuminate\Http\Request;

class DoiCuuHoController extends Controller
{
    public function index()
    {
        $items = DoiCuuHo::paginate(15);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([]);
        $item = DoiCuuHo::create($validated);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = DoiCuuHo::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = DoiCuuHo::findOrFail($id);
        $validated = $request->validate([]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = DoiCuuHo::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}