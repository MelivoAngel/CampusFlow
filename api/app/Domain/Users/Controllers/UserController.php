<?php

namespace App\Domain\Users\Controllers;

use App\Domain\Users\Policies\UserCreationPolicy;
use App\Domain\Users\Requests\CreateUserRequest;
use App\Domain\Users\Services\UserService;
use Illuminate\Http\Request;

class UserController
{
    public function store(
        Request $request,
        CreateUserRequest $createUserRequest,
        UserCreationPolicy $policy,
        UserService $service
    )
    {
        $creator = auth()->user();

        $validated = $createUserRequest->validate(
            $request->all(),
            $creator
        );

        if (!$policy->canCreate(
            $creator,
            $validated['role']
        )) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to create this user'
            ], 403);
        }

        $user = $service->createUser(
            $creator,
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }
}