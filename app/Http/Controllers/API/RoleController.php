<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class RoleController extends Controller
{
    public function index()
    {
        $boat = Auth::user();
        $roles = Role::select('id', 'name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }
}
