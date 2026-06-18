<?php

namespace App\Domain\Meters\Requests;

use App\Domain\Meters\Enums\MeterPermission;
use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateMeterRequest
{
    public function validate(
        array $data,
        User $creator
    ): array
    {
        $rules = [

            'resource_type' => [
                'required',
                Rule::in([
                    'water',
                    'electricity'
                ])
            ],

            'meter_code' => [
                'required',
                'string',
                'max:50'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ]
        ];

        if (
            MeterPermission::requiresManualCampus(
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