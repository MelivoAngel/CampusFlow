<?php

namespace App\Domain\Meters\Requests;

use Illuminate\Support\Facades\Validator;

class SubmitReadingRequest
{
    public function validate(
        array $data
    ): array
    {
        return Validator::make(
            $data,
            [

                'meter_id' => [
                    'required',
                    'exists:meters,id'
                ],

                'current_reading' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'photo' => [
                    'required',
                    'image',
                    'max:5120'
                ]
            ]
        )->validate();
    }
}