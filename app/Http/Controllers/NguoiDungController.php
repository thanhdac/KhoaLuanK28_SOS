<?php

namespace App\Http\Controllers;

use App\Models\{NguoiDung};
use Illuminate\Http\Request;

class NguoiDungController extends Controller
{
    public function index()
    {
        $items = NguoiDung::paginate(15);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([]);
        $item = NguoiDung::create($validated);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = NguoiDung::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = NguoiDung::findOrFail($id);
        $validated = $request->validate([]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = NguoiDung::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}