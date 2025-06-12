<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Boat;
use App\Models\Location;
use App\Models\Type;

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
            'image' => 'nullable|string|max:255',
            'type_id' => 'required|exists:types,id',
            'brand' => 'nullable|string|max:255',
            'minimum_recommended' => 'nullable|integer|min:1',
            'qr_code' => 'nullable|string|max:255',
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
        //       "brand":"Bosch",
        //       "minimum_recommended":"2",
        //       "qr_code":"1234567890"
            
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
            'data' => $item->load('type', 'storageBox'),
            'message' => 'Item encontrado correctamente.'
            
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
            'brand' => 'nullable|string|max:255',
            'minimum_recommended' => 'nullable|integer|min:1',
            'qr_code' => 'nullable|string|max:255',
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

    /**
     * List items that are at risk of being empty based on quantity and minimum_recommended
     */
    public function lowStock(Request $request)
    {
        $boat = Auth::user();
        
        $query = Item::with(['type', 'location', 'storageBox'])
            ->whereHas('location', function ($q) use ($boat) {
                $q->where('boat_id', $boat->id);
            })
            ->whereNotNull('minimum_recommended')
            ->whereRaw('quantity <= minimum_recommended');

        // Optional location filter
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Optional type filter
        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        $items = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'minimum_recommended' => $item->minimum_recommended,
                    'location' => $item->location->name,
                    'type' => $item->type->name,
                    'storage_box' => $item->storageBox ? $item->storageBox->name : null,
                    'risk_level' => $this->calculateRiskLevel($item->quantity, $item->minimum_recommended)
                ];
            })
        ]);
    }

    /**
     * Calculate risk level based on current quantity and minimum recommended
     */
    private function calculateRiskLevel($quantity, $minimumRecommended)
    {
        if ($quantity === 0) {
            return 'critical';
        }
        
        $ratio = $quantity / $minimumRecommended;
        
        if ($ratio <= 0.25) {
            return 'high';
        } elseif ($ratio <= 0.5) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Group items by type showing counts and total quantities
     */
    public function groupByType(Request $request)
    {
        $boat = Auth::user();

        $types = Type::with(['items' => function ($query) use ($boat) {
            $query->whereHas('location', function ($q) use ($boat) {
                $q->where('boat_id', $boat->id);
            });
        }])
        ->whereHas('items.location', function ($query) use ($boat) {
            $query->where('boat_id', $boat->id);
        })
        ->get()
        ->map(function ($type) {
            return [
                'type_id' => $type->id,
                'type_name' => $type->name,
                'total_items' => $type->items->count(), // Number of different items
                'total_quantity' => $type->items->sum('quantity'), // Sum of all quantities
                'items' => $type->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'quantity' => $item->quantity,
                        'location' => $item->location->name,
                        'storage_box' => $item->storageBox ? $item->storageBox->name : null
                    ];
                })
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $types
        ]);
    }
}
