<?php

namespace App\Domain\Meters\Requests;

use Illuminate\Support\Facades\Validator;

class ResolveAnomalyRequest
{
    public function validate(
        array $data
    ): array
    {
        return Validator::make(
            $data,
            [

                'resolution_note' => [

                    'required',

                    'string',

                    'min:5'
                ]
            ]
        )->validate();
    }
}