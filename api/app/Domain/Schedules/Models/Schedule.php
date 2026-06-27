<?php

namespace App\Domain\Schedules\Models;

use App\Domain\Facilities\Models\Facility;
use App\Domain\Campuses\Models\Campus;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([

    'campus_id',

    'created_by',

    'facility_id',

    'organization',

    'event_name',

    'event_date',

    'start_time',

    'end_time',

    'description'
])]
class Schedule extends Model
{
    use HasFactory;

    public function campus()
    {
        return $this->belongsTo(

            Campus::class
        );
    }

    public function creator()
    {
        return $this->belongsTo(

            User::class,

            'created_by'
        );
    }

    public function facility()
    {
        return $this->belongsTo(

            Facility::class
        );
    }
}