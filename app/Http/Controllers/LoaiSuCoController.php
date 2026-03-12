<?php

namespace App\Http\Controllers;

use App\Models\LoaiSuCo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LoaiSuCoController extends Controller
{
    /**
     * Get all incident types with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $per_page = $request->input('per_page', 15);
        $items = LoaiSuCo::paginate($per_page);
        return response()->json($items);
    }

    /**
     * Create a new incident type
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ten_danh_muc' => 'required|string|max:255',
            'slug_danh_muc' => 'required|string|unique:loai_su_co,slug_danh_muc|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'sometimes|boolean'
        ]);

        $validated['trang_thai'] = $validated['trang_thai'] ?? 1;

        try {
            $item = LoaiSuCo::create($validated);
            return response()->json($item, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating incident type', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get a specific incident type
     */
    public function show($id): JsonResponse
    {
        try {
            $item = LoaiSuCo::with(['chiTiets', 'yeuCauCuuHos', 'doiCuuHos'])->findOrFail($id);
            return response()->json($item);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Incident type not found'], 404);
        }
    }

    /**
     * Update an incident type
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $item = LoaiSuCo::findOrFail($id);

            $validated = $request->validate([
                'ten_danh_muc' => 'sometimes|string|max:255',
                'slug_danh_muc' => 'sometimes|string|max:255|unique:loai_su_co,slug_danh_muc,' . $id . ',id_loai_su_co',
                'mo_ta' => 'nullable|string',
                'trang_thai' => 'sometimes|boolean'
            ]);

            $item->update($validated);
            return response()->json($item);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating incident type', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete an incident type
     */
    public function destroy($id): JsonResponse
    {
        try {
            $item = LoaiSuCo::findOrFail($id);
            $item->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting incident type', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get detailed information of an incident type
     */
    public function getChiTiet($id): JsonResponse
    {
        try {
            $item = LoaiSuCo::with('chiTiets')->findOrFail($id);
            return response()->json($item);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Incident type not found'], 404);
        }
    }

    /**
     * Get incident types by status
     */
    public function getByStatus($trang_thai = 1): JsonResponse
    {
        try {
            $items = LoaiSuCo::where('trang_thai', $trang_thai)
                ->paginate(15);
            return response()->json($items);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving incident types', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get all rescue requests for an incident type
     */
    public function getYeuCauCuuHo($id): JsonResponse
    {
        try {
            $item = LoaiSuCo::findOrFail($id);
            $yeuCaus = $item->yeuCauCuuHos()->paginate(15);
            return response()->json($yeuCaus);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving rescue requests', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get all rescue teams capable of handling this incident type
     */
    public function getDoiCuuHo($id): JsonResponse
    {
        try {
            $item = LoaiSuCo::findOrFail($id);
            $dois = $item->doiCuuHos()->paginate(15);
            return response()->json($dois);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving rescue teams', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Update incident type status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $item = LoaiSuCo::findOrFail($id);

            $validated = $request->validate([
                'trang_thai' => 'required|boolean'
            ]);

            $item->update($validated);
            return response()->json($item);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating status', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Search incident types by name or slug
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->input('q', '');

            if (empty($query)) {
                return response()->json(['message' => 'Search query is required'], 400);
            }

            $items = LoaiSuCo::where('ten_danh_muc', 'LIKE', "%{$query}%")
                ->orWhere('slug_danh_muc', 'LIKE', "%{$query}%")
                ->orWhere('mo_ta', 'LIKE', "%{$query}%")
                ->paginate(15);

            return response()->json($items);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error searching incident types', 'error' => $e->getMessage()], 400);
        }
    }
}
