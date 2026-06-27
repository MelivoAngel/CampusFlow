<?php

namespace App\Domain\Schedules\Requests;

use Illuminate\Support\Facades\Validator;

class RescheduleScheduleRequest
{
    public function validate(
        array $data
    ): array
    {
        return Validator::make(
            $data,
            [

                'facility_id' => [

                    'required',

                    'exists:facilities,id'
                ],

                'event_date' => [

                    'required',

                    'date'
                ],

                'start_time' => [

                    'required'
                ],

                'end_time' => [

                    'required'
                ]
            ]
        )->validate();
    }
}