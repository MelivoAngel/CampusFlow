<?php

namespace App\Domain\Schedules\Requests;

use Illuminate\Support\Facades\Validator;

class UpdateScheduleRequest
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

                'organization' => [

                    'required',

                    'string',

                    'max:255'
                ],

                'event_name' => [

                    'required',

                    'string',

                    'max:255'
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
                ],

                'description' => [

                    'nullable',

                    'string'
                ]
            ]
        )->validate();
    }
}