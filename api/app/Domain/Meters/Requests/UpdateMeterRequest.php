<?php

namespace App\Domain\Meters\Requests;

use Illuminate\Support\Facades\Validator;

class UpdateMeterRequest
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
                ],

                'is_active' => [
                    'required',
                    'boolean'
                ]
            ]
        )->validate();
    }
}