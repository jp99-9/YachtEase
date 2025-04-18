<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Boat;
use App\Models\Location;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * Vamos a probar a filtrar desde aqui en vez de el frontend para mejorar la eficiencia y escalabilidad.
     */
    public function index(Request $request)
    {
        $boat = Auth::user();
        $query = Item::with(['type', 'location', 'storageBox'])
        ->whereHas('location', function ($q) use ($boat) {
        $q->where('boat_id', $boat->id);
    });
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtros específicos
        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('box_id')) {
            $query->where('storage_box_id', $request->box_id);
        }

        $items = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $items->items(),
                'pagination' => [
                    'current_page' => $items->currentPage(),
                    'last_page' => $items->lastPage(),
                    'per_page' => $items->perPage(),
                    'total' => $items->total(),
                ]
            ]
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
            'quantity' => 'required|integer|min:1',
            'type_id' => 'required|exists:types,id',
            'location_id' => 'required|exists:locations,id',
            'storage_box_id' => 'nullable|exists:storage_boxes,id',
        ]);

        $location = $boat->locations()->where('id', $validated['location_id'])->first();

        if (!$location) {
            return response()->json(['error' => 'Ubicación no válida para este barco'], 403);
        }
        
        $item = $location->items()->create($validated);
        
        //ejemplo para thunderclient
        // {
        //     "name":"Bengala",
        //     "description":"Bengala de luz color rojo de emergencia",
        //      "quantity":"5",
        //       "type_id":"2",
        //       "location_id":"1",
        //        "storage_box_id":"5"
            
        //   }

        return response()->json([
            'status' => 'success',
            'data' => $item,
            'message' => 'Item creado correctamente.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $boat = Auth::user();
        if ($item->location->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }
        return response()->json([
            'status' => 'success',
            'data' => $item->load('type', 'storageBox')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $boat = Auth::user();
        if ($item->location->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'type_id' => 'required|exists:types,id',
            'location_id' => 'required|exists:locations,id',
            'storage_box_id' => 'nullable|exists:storage_boxes,id',
        ]);

        $item->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $item,
            'message' => 'Item actualizado correctamente.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $boat = Auth::user();
        if ($item->location->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }
        

        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item eliminado correctamente.'
        ]);
    }
}
