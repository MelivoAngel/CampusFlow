<?php

namespace App\Domain\Users\Controllers;

use App\Domain\Users\Policies\UserCreationPolicy;
use App\Domain\Users\Requests\CreateUserRequest;
use App\Domain\Users\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Domain\Users\Policies\ViewUsersPolicy;
use App\Domain\Users\Services\GetUsersService;
use App\Domain\Users\Requests\UpdateUserRequest;
use App\Domain\Users\Services\UpdateUserService;
use App\Domain\Users\Policies\UserUpdatePolicy;
use App\Domain\Users\Models\User;

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

    public function index(
        Request $request,
        ViewUsersPolicy $policy,
        GetUsersService $service
    ): JsonResponse
    {
        $user = $request->user();

        if (
            ! $policy->canView(
                $user
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $users = $service->get(
            $user
        );

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function update(
        Request $request,
        int $id,
        UpdateUserRequest $updateUserRequest,
        UpdateUserService $service,
        UserUpdatePolicy $policy
    ): JsonResponse
    {
        $editor = auth()->user();

        $user = User::findOrFail(
            $id
        );

        if (
            ! $policy->canUpdate(
                $editor,
                $user
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $validated = $updateUserRequest->validate(
            $request->all(),
            $id
        );

        $updated = $service->update(
            $user,
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $updated
        ]);
    }

    public function destroy(Request $request,int $id): JsonResponse
    {
        $currentUser =
            $request->user();

        $user =
            User::findOrFail(
                $id
            );

        if (
            $user->id ===
            $currentUser->id
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'You cannot delete your own account'

            ],422);
        }

        $user->delete();

        return response()->json([

            'success' => true,

            'message' =>
                'User deleted successfully'
        ]);
    }
}