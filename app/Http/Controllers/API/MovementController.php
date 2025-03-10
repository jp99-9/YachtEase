<?php

namespace App\Http\Controllers\API;

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

        // Filtrar por item, perfil o fecha si se envían parámetros
        $movements = Movement::whereHas('profile', function ($query) use ($boat) {
            $query->where('boat_id', $boat->id);
        })
            ->when($request->input('item_id'), function ($query, $itemId) {
                return $query->where('item_id', $itemId);
            })
            ->when($request->input('profile_id'), function ($query, $profileId) {
                return $query->where('profile_id', $profileId);
            })
            ->when($request->input('fecha'), function ($query, $fecha) {
                return $query->whereDate('fecha', $fecha);
            })
            ->with(['profile', 'item', 'location'])
            ->get();

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
            'profile_id' => 'required|exists:profiles,id',
            'item_id' => 'required|exists:items,id',
            'location_id' => 'required|exists:locations,id',
            'cantidad' => 'required|integer',
            'motivo' => 'required|string|max:100',
            'observaciones' => 'nullable|string',
            'fecha' => 'nullable|date',
        ]);

        $movement = Movement::create([
            'item_id' => $validated['item_id'],
            'profile_id' => $validated['profile_id'],
            'location_id' => $validated['location_id'],
            'quantity' => $validated['quantity'],
            'date' => now(), // Se registra la fecha actual automáticamente
            'reason' => $validated['reason'],
            'observations' => $validated['observations'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $movement,
            'message' => 'Movimiento registrado correctamente.'
        ], 201);
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
     * Remove the specified resource from storage.
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
