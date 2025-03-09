<?php

namespace App\Http\Controllers\API;

use App\Models\Boat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthBoatController extends Controller
{
    // public function register(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'house' => 'required|string|max:255',
    //         'size' => 'required|string|max:50',

    //         'password' => 'required|string|min:4',
    //         'unique_code' => 'required|string|unique:boats,unique_code',
    //     ]);

    //     $boat = Boat::create([
    //         'name' => $validated['name'],
    //         'house' => $validated['house'],
    //         'size' => $validated['size'],
    //         'incorporation_date' =>now(),
    //         'password' => Hash::make($validated['password']),
    //         'unique_code' => $validated['unique_code'],
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $boat,
    //         'message' => 'Barco registrado correctamente'
    //     ], 201);
    // }

    public function login(Request $request)
{
    try {
        $validated = $request->validate([
            'unique_code' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscar barco por código único
        $boat = Boat::where('unique_code', $validated['unique_code'])->first();

        // 🚨 Si no se encuentra el código único
        if (!$boat) {
            return response()->json([
                'status' => 'error',
                'message' => 'El código único ingresado no está registrado.'
            ], 404);
        }

        // 🚨 Si la contraseña es incorrecta
        if (!Hash::check($validated['password'], $boat->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'La contraseña es incorrecta.'
            ], 401);
        }

        // (Opcional) Verificar si ya tiene una sesión activa
        if ($boat->tokens()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ya tienes una sesión activa. Cierra sesión antes de iniciar otra.'
            ], 403);
        }

        // Generar token de acceso
        $token = $boat->createToken('boat-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'boat' => $boat,
            'message' => 'Inicio de sesión exitoso'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Ocurrió un error inesperado.',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // Logout de barco
    public function logout(Request $request)
    {
        $boat = $request->user();


        if ($boat && $boat->currentAccessToken()) {
            $boat->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Sesión cerrada correctamente'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No hay sesión activa para cerrar'
        ], 401);
    }
}
