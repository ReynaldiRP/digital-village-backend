<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use Carbon\Carbon;
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
        $permissions = $user->getAllPermissions()->pluck('name');
        $token = $user->createToken(
            'auth_token',
            $permissions->toArray(),
            Carbon::now()->addMinutes(config('sanctum.expiration'))
        )->plainTextToken;

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

        return response([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    public function me(): object
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'You are not logged in',
            ], 401);
        }

        $user->load(['roles.permissions', 'headOfFamily']);
        $permissions = $user->roles->flatMap->permissions->pluck('name');
        $role = $user->roles->first()?->name;

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'head_of_family_id' => $user->headOfFamily?->id,
                'name' => $user->name,
                'email' => $user->email,
                'permissions' => $permissions,
                'role' => $role,
                'profile_picture_url' => asset('storage/' . $user->headOfFamily?->profile_picture),
            ]
        ]);
    }
}
