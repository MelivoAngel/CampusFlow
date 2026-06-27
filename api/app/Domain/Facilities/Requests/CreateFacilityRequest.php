<?php

namespace App\Domain\Facilities\Requests;

use App\Domain\Facilities\Enums\FacilityPermission;
use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Validator;

class CreateFacilityRequest
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

            'description' => [

                'nullable',

                'string'
            ]
        ];

        if (
            FacilityPermission::requiresManualCampus(
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