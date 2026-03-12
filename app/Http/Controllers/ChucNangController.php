<?php

namespace App\Http\Controllers;

use App\Models\{ChucNang};
use Illuminate\Http\Request;

class ChucNangController extends Controller
{
    public function index()
    {
        $items = ChucNang::paginate(15);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([]);
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
        $validated = $request->validate([]);
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