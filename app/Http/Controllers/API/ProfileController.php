<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $boat = Auth::user();
        $profiles = $boat->profiles; // Obtiene solo los perfiles del barco autenticado

        return response()->json([
            'status' => 'success',
            'data' => $profiles
        ]);
    }

    public function store(Request $request)
    {
        $boat = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|exists:users,email', // 🔹 Si ingresa un email, debe existir en la BD
            'avatar' => 'nullable|string|max:255', // Opcional, ruta de imagen
            
        ]);

        //  Buscar usuario por email si se proporciona
        if (!empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
            $message = 'Perfil creado y vinculado a tu cuenta existente.';
        } else {
            // 🔹 Crear usuario automáticamente (sin email)
            $user = User::create([
                'first_name' => $validated['name'], // Usa el nombre del perfil
                'last_name' => null, // Se agregará después cuando lo reclame
                'email' => null, // Se agregará después cuando lo reclame

            ]);
            $message = 'Perfil creado correctamente, el usuario lo puede reclamar después.';
        }



        // 🔹 Crear perfil vinculado al usuario creado
        $profile = $boat->profiles()->create([
            'name' => $validated['name'],
            'avatar' => $validated['avatar'] ?? null,
            'user_id' => $user->id, // Asignamos el usuario creado
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $profile,
            'message' => $message
        ], 201);
    }

    public function claimProfile(Request $request, Profile $profile)
    {
        $user = $profile->user;

        if (!$user || $user->email) {
            return response()->json(['error' => 'Este perfil ya ha sido reclamado'], 403);
        }

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:50', // Opcional, si no lo envía, se mantiene igual
            'last_name' => 'string|max:200',
            'email' => 'required|email|unique:users,email',

        ]);

        // 🔹 Completar los datos del usuario
        $user->update([
            'first_name' => $validated['first_name'] ?? $user->first_name, // Si envía un nuevo nombre, se actualiza; si no, se mantiene
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],

        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Perfil reclamado exitosamente.'
        ]);
    }


    public function show(Profile $profile)
    {
        $boat = Auth::user();

        // Asegurar que el perfil pertenece al barco autenticado
        if ($profile->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }
        

        return response()->json([
            'status' => 'success',
            'data' => $profile
        ]);
    }

    public function update(Request $request, Profile $profile)
    {
        $boat = Auth::user();

        // Asegurar que el perfil pertenece al barco autenticado
        if ($profile->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|string|max:255', // Opcional
        ]);

        $profile->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $profile,
            'message' => 'Perfil actualizado correctamente'
        ]);
    }

    // Eliminar un perfil
    public function destroy(Profile $profile)
    {
        $boat = Auth::user();

        // Asegurar que el perfil pertenece al barco autenticado
        if ($profile->boat_id !== $boat->id) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $profile->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Perfil eliminado correctamente'
        ]);
    }
}
