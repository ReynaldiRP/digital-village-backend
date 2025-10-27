<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    public function login(
        array $data
    ): ?object {
        if (!Auth::guard('web')->attempt($data)) {
            return response([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'Login successful',
        ]);
    }

    public function logout(): object
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        $response = [
            'success' => true,
            'message' => 'Logout successful',
        ];

        return response($response, 200);
    }

    public function me(): object
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->load('roles.permissions');
            $permissions = $user->roles->flatMap->permissions->pluck('name');
            $role = $user->roles->first()->name;

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'permissions' => $permissions,
                    'role' => $role,
                ]
            ]);
        }

        return response()->json([
            'message' => 'You are not logged in',
        ], 401);
    }
}
