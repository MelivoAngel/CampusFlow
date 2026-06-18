<?php

namespace App\Domain\Buildings\Requests;

use App\Domain\Buildings\Enums\BuildingPermission;
use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Validator;

class CreateBuildingRequest
{
    public function validate(
        array $data,
        User $creator
    ): array
    {
        $rules = [

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'code' => [
                'required',
                'string',
                'max:50'
            ],

            'type' => [
                'nullable',
                'string'
            ],

            'description' => [
                'nullable',
                'string'
            ]
        ];

        if (
            BuildingPermission::requiresManualCampus(
                $creator->role
            )
        ) {
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