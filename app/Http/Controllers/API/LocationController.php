<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;
use App\Models\Item;
use App\Models\StorageBox;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boat = Auth::user();

        $locations = Location::where('boat_id', $boat->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $locations
        ]);
    }

    public function getItems($id)
{
    // 1. Verifica que la ubicación exista
    $location = Location::findOrFail($id);

    // 2. Ítems directamente en la ubicación
    $directItems = Item::where('location_id', $id)
                        ->whereNull('storage_box_id')
                        ->get();

    // 3. Cajas en esa ubicación
    $boxes = StorageBox::where('location_id', $id)->get();

    // 4. Para cada caja, buscar sus ítems
    $boxesWithItems = $boxes->map(function ($box) {
        $items = Item::where('storage_box_id', $box->id)->get();
        return [
            'box' => $box,
            'items' => $items
        ];
    });

    // 5. Devolver respuesta
    return response()->json([
        'location' => $location,
        'direct_items' => $directItems,
        'storage_boxes' => $boxesWithItems
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $boat = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $location = Location::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $location,
            'message' => 'Ubicación creada correctamente.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $boat = Auth::user();
        $location = Location::find($id);
        if ($location->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $location->update($validated);
        
        return response()->json([
            'status' => 'success',
            'data' => $location,
            'message' => 'Ubicación actualizada correctamente.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $boat = Auth::user();
        $location = Location::find($id);
        if ($location->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }
        
        $location->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ubicación eliminada correctamente.'
        ]);
    }
}
