<?php

namespace App\Http\Controllers;

use App\Models\{Admin};
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $items = Admin::paginate(15);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([]);
        $item = Admin::create($validated);
        return response()->json($item, 201);
    }

    public function show($id)
    {
        $item = Admin::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = Admin::findOrFail($id);
        $validated = $request->validate([]);
        $item->update($validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = Admin::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }
}