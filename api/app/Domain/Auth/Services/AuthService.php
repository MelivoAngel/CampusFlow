<?php

namespace App\Domain\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthService
{
    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            throw new Exception('Invalid credentials');
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            throw new Exception('Invalid credentials');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'campus_id' => $user->campus_id
            ]
        ];
    }

    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }
}