<?php

namespace App\Domain\Meters\Requests;

use Illuminate\Support\Facades\Validator;

class UpdateReadingRequest
{
    public function validate(
        array $data
    ): array
    {
        return Validator::make(
            $data,
            [

                'current_reading' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'photo' => [
                    'nullable',
                    'image',
                    'max:5120'
                ]
            ]
        )->validate();
    }
}