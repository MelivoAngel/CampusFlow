<?php

namespace App\Domain\Facilities\Requests;

use Illuminate\Support\Facades\Validator;

class UpdateFacilityRequest
{
    public function validate(
        array $data
    ): array
    {
        return Validator::make(
            $data,
            [

                'name' => [

                    'required',

                    'string',

                    'max:255'
                ],

                'description' => [

                    'nullable',

                    'string'
                ]
            ]
        )->validate();
    }
}