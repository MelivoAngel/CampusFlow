<?php

namespace App\Domain\Schedules\Requests;

use App\Domain\Schedules\Enums\SchedulePermission;
use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Validator;

class CreateScheduleRequest
{
    public function validate(
        array $data,
        User $creator
    ): array
    {
        $rules = [

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
        ];

        if (
            SchedulePermission::requiresManualCampus(
                $creator->role
            )
        ) {
            $rules['campus_id'] = [

                'required',

                'exists:campuses,id'
            ];
        }

        return Validator::make(

            $data,

            $rules

        )->validate();
    }
}