<?php

namespace App\Domain\Users\Requests;

use App\Domain\Users\Enums\UserRole;
use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateUserRequest
{
    public function validate(
        array $data,
        User $creator
    ): array
    {
        $creatorRole = UserRole::from(
            $creator->role
        );

        $rules = [

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'min:8'
            ],

            'role' => [
                'required',
                Rule::in([
                    UserRole::CAMPUS_ADMIN->value,
                    UserRole::STAFF->value,
                    UserRole::FIELD_TECHNICIAN->value,
                    UserRole::CALENDAR_ADMIN->value
                ])
            ]
        ];

        if (
            $creatorRole->requiresManualCampus()
        ) {
            $rules['campus_id'] = [
                'required',
                'exists:campuses,id'
            ];
        }

        $validated = Validator::make(
            $data,
            $rules
        )->validate();

        if (
            $validated['role'] ===
            UserRole::CAMPUS_ADMIN->value
        ) {
            $campusId =

                $creatorRole->requiresManualCampus()

                ? $validated['campus_id']

                : $creator->campus_id;

            $exists = User::where(
                'campus_id',
                $campusId
            )->where(
                'role',
                UserRole::CAMPUS_ADMIN->value
            )->exists();

            if ($exists) {
                abort(
                    response()->json([
                        'success' => false,
                        'message' => 'Campus already has a campus admin'
                    ], 422)
                );
            }
        }

        return $validated;
    }
}