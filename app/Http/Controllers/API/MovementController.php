<?php

namespace App\Http\Controllers\API;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $boat = Auth::user(); // Barco autenticado

    $movements = Movement::whereHas('profile', function ($query) use ($boat) {
            $query->where('boat_id', $boat->id);
        })
        ->when($request->input('item_id'), function ($query, $itemId) {
            return $query->where('item_id', $itemId);
        })
        ->when($request->input('profile_id'), function ($query, $profileId) {
            return $query->where('profile_id', $profileId);
        })
        ->when($request->input('movement_date'), function ($query, $movementDate) {
            return $query->whereDate('movement_date', $movementDate);
        })
        ->with([
            'profile',
            'item',
            'fromLocation',
            'toLocation',
            'fromBox',
            'toBox'
        ])
        ->orderBy('movement_date', 'desc') // Opcional: para que lo veas más organizado, el más reciente primero
        ->paginate(15);

    return response()->json([
        'status' => 'success',
        'data' => $movements
    ]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $boat = Auth::user(); // Barco autenticado

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            //'movement_date' => 'required|date',
            'reason' => 'required|string|max:200',
            'observations' => 'nullable|string|max:200',
            'profile_id' => 'required|exists:profiles,id',
            'item_id' => 'required|exists:items,id',
            'from_location_id' => 'required|exists:locations,id',
            'from_box_id' => 'nullable|exists:storage_boxes,id',
            'to_location_id' => 'required|exists:locations,id',
            'to_box_id' => 'nullable|exists:storage_boxes,id',
        ]);

        // Verifica que las ubicaciones pertenezcan al barco autenticado
        $fromLocation = Location::where('id', $validated['from_location_id'])->where('boat_id', $boat->id)->first();
        $toLocation = Location::where('id', $validated['to_location_id'])->where('boat_id', $boat->id)->first();



        if (!$fromLocation || !$toLocation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Una de las ubicaciones no pertenece a tu barco.'
            ], 400);
        }

        // Buscamos el item original
        $item = Item::findOrFail($validated['item_id']);

        // Validamos que haya suficiente cantidad
        if ($item->quantity < $validated['quantity']) {
            return response()->json([
                'status' => 'error',
                'message' => 'No hay suficiente cantidad disponible para mover.'
            ], 400);
        }

        $movement = Movement::create([
            'item_id' => $item->id,
            'quantity' => $validated['quantity'],
            'movement_date' => now(),
            'profile_id' => $validated['profile_id'],
            'reason' => $validated['reason'],
            'location_id' => $validated['from_location_id'],
            'observations' => $validated['observations'] ?? null,
            'from_location_id' => $validated['from_location_id'],
            'from_box_id' => $validated['from_box_id'] ?? null,
            'to_location_id' => $validated['to_location_id'],
            'to_box_id' => $validated['to_box_id'] ?? null,
        ]);

        // Restar la cantidad al item original
        $item->quantity -= $validated['quantity'];

        if ($item->quantity <= 0) {
            $item->quantity = 0; 
            $item->save();//  Si no queda stock, lo eliminamos
        } else {
            $item->save(); // Si aún queda stock, solo lo guardamos
        }
        



        // Creamos o actualizamos el item en el destino
        $existingItem = Item::where('name', $item->name)
            ->where('location_id', $validated['to_location_id'])
            ->where('storage_box_id', $validated['to_box_id'])
            ->first();

        if ($existingItem) {
            // Si ya existe el mismo item en el destino, sumamos la cantidad
            $existingItem->quantity += $validated['quantity'];
            $existingItem->save();
        } else {
            // Si no existe, creamos uno nuevo
            Item::create([
                'name' => $item->name,
                'description' => $item->description,
                'location_id' => $validated['to_location_id'],
                'storage_box_id' => $validated['to_box_id'],
                'quantity' => $validated['quantity'],
                'boat_id' => $item->boat_id,
                'type_id' => $item->type_id,
                'minimum_recommended' => $item->minimum_recommended,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $movement,
            'message' => 'Movimiento registrado correctamente.'
        ], 201);

        // Ejeemplo: {
        //     "item_id": 6,
        //     "quantity": 2,
        //     "reason": "Traslado a zona de",
        //     "observations": "Mover para  urgente",
        //     "profile_id":1,
        //     "from_location_id": 1,
        //     "from_box_id": 4,
        //     "to_location_id": 1,
        //     "to_box_id": 3
        //   }
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
    public function update(Request $request, Movement $movement)
    {
        $boat = Auth::user();

        if ($movement->profile->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $validated = $request->validate([
            'motivo' => 'required|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $movement->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $movement,
            'message' => 'Movimiento actualizado correctamente.'
        ]);
    }


    /**
     * Remove the specified movemnt from storage.
     */
    public function destroy(Movement $movement)
    {
        $boat = Auth::user();

        if ($movement->profile->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $movement->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Movimiento eliminado correctamente.'
        ]);
    }
}
