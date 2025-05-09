<?php

namespace App\Http\Controllers\API;

use Exception;
use Throwable;
use App\Models\Role;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserBoatRole;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $boat = Auth::user();

            // Validación defensiva: ¿hay usuario?
            if (!$boat) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Usuario no autenticado.'
                ], 401);
            }

            $profiles = $boat->profiles;

            // Validación defensiva: ¿hay perfiles?
            if (!$profiles || $profiles->isEmpty()) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'No se encontraron perfiles para este barco.'
                ], 204); // 204: No Content
            }

            return response()->json([
                'status' => 'success',
                'data' => $profiles
            ]);
        } catch (Throwable $e) {
            // Logging del error con contexto
            Log::error('Error al obtener perfiles del barco', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error interno al obtener los perfiles.'
            ], 500); // Internal Server Error
        }
    }

    public function store(Request $request)
    {
        $boat = Auth::user();



        //he incluido el rol_id para que se puedad añadir a la tabla intermedia al crearse el usuario, ya sea temporal o definitivo.

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|exists:users,email', // 🔹 Si ingresa un email, debe existir en la BD
            'avatar' => 'nullable|string|max:255', // Opcional, ruta de imagen
            'role_id' => 'required|exists:roles,id', // El rol es obligatorio
        ]);



        //  Buscar usuario por email si se proporciona
        if (!empty($validated['email'])) {
            
            $user = User::where('email', $validated['email'])->first();

            
            //ejemplo thunderclient para probar
            // {
            //     "name": "Juan Pérez",
            //     "email": "juan@example.com",
            //     "avatar": "juan.png",
            //     "role_id": 2
            // }

        } else {
            // Crear usuario automáticamente (sin email)
            $user = User::create([
                'first_name' => $validated['name'], // Usa el nombre del perfil
                'last_name' => null, // Se agregará después cuando lo reclame
                'email' => null, // Se agregará después cuando lo reclame

                //ejemplo thunderclient para probar
                // {
                //     "name": "Juan Pérez",
                //    
                //     "avatar": "juan.png", opcional
                //     "role_id": 2
                // }

            ]);
        }

        $message = $user->wasRecentlyCreated
            ? 'Perfil creado correctamente, el usuario lo puede reclamar después.'
            : 'Perfil creado y vinculado a tu cuenta existente.';

        if ($boat->profiles()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Este usuario ya tiene un perfil en el barco.'
            ], 409);
            
        }

        // Crear perfil vinculado al usuario creado
        $profile = $boat->profiles()->create([
            'name' => $validated['name'],
            'avatar' => $validated['avatar'] ?? null,
            'user_id' => $user->id, // Asignamos el usuario creado
        ]);

        // **Registrar la relación en la tabla `user_boat_roles`**


        $boat->users()->attach($user->id, [
            'role_id' => $validated['role_id'],
            'status' => 'active',
            'start_date' => now(),
            'end_date' => null
        ]);

        //uso este bloque para acceder al rol del usuario en la tabal intermedia y poder incluir el rol en el json de respuesta
        $roleName = Role::find($validated['role_id'])->name;


        return response()->json([
            'status' => 'success',
            'data' => [
                'profile' => $profile->only(['id', 'name', 'avatar']),
                'role' => $roleName
            ],
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
            'name' => 'nullable|string|max:255',
            'avatar' => 'nullable|string|max:255', // Opcional
            'role_id' => 'nullable|exists:roles,id'
        ]);

        $profile->update([
            'name' => $validated['name'],
            'avatar' => $validated['avatar'] ?? $profile->avatar,
        ]);

        // Si se proporciona un `role_id`, actualizar en `user_boat_roles`
        if (!empty($validated['role_id'])) {
            UserBoatRole::where('boat_id', $boat->id)
                ->where('user_id', $profile->user_id)
                ->update(['role_id' => $validated['role_id']]);
        }
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
