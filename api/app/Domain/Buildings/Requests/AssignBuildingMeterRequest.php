<?php

namespace App\Domain\Buildings\Requests;

use Illuminate\Support\Facades\Validator;

class AssignBuildingMeterRequest
{
    public function validate(
        array $data
    ): array
    {
        return Validator::make(

            $data,

            [

                'meter_ids' => [

                    'array'
                ],

                'meter_ids.*' => [

                    'exists:meters,id'
                ]
            ]

        )->validate();
    }
}