<?php

namespace App\Domain\Meters\Requests;

use Illuminate\Support\Facades\Validator;

class CorrectReadingRequest
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
                ]
            ]
        )->validate();
    }
}