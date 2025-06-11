<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StorageBox;

class StorageBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boat = Auth::user();

        $boxes = StorageBox::whereHas('location', function ($query) use ($boat) {
            $query->where('boat_id', $boat->id);
        })->get();
    
        return response()->json([
            'status' => 'success',
            'data' => $boxes
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
            'location_id' => 'required|exists:locations,id',
        ]);

        $box = StorageBox::create($validated);
        
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
        $box = StorageBox::find($id);
        if ($box->location->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
        ]);

        $box->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $box,
            'message' => 'Caja actualizada correctamente.'
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $boat = Auth::user();
        $box = StorageBox::find($id);
        if ($box->location->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }
        
        $box->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Caja eliminada correctamente.'
        ]);
    }
}
