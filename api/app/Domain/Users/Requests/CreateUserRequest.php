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

        if ($creatorRole->requiresManualCampus()) {
            $rules['campus_id'] = [
                'required',
                'exists:campuses,id'
            ];
        }

        return Validator::make(
            $data,
            $rules
        )->validate();
    }
}