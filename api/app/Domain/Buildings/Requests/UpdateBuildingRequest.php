<?php

namespace App\Domain\Buildings\Requests;

use Illuminate\Support\Facades\Validator;

class UpdateBuildingRequest
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

                'code' => [
                    'required',
                    'string',
                    'max:50'
                ],

                'type' => [
                    'required',
                    'string'
                ],

                'description' => [
                    'nullable',
                    'string'
                ]
            ]
        )->validate();
    }
}