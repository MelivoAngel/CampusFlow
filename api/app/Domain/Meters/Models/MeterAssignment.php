<?php

namespace App\Domain\Meters\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([

    'meter_id',

    'technician_id',

    'assigned_by'
])]
class MeterAssignment extends Model
{
    public function meter()
    {
        return $this->belongsTo(
            Meter::class
        );
    }

    public function technician()
    {
        return $this->belongsTo(
            \App\Domain\Users\Models\User::class,

            'technician_id'
        );
    }

    public function assigner()
    {
        return $this->belongsTo(
            \App\Domain\Users\Models\User::class,

            'assigned_by'
        );
    }
}