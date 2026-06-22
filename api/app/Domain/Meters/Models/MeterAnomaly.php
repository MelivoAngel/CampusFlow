<?php

namespace App\Domain\Meters\Models;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'meter_id',
    'meter_reading_id',
    'reported_by',
    'resolved_by',
    'type',
    'severity',
    'message',
    'is_resolved',
    'resolution_note',
    'resolved_at'
])]
class MeterAnomaly extends Model
{
    public function meter()
    {
        return $this->belongsTo(
            Meter::class
        );
    }

    public function reading()
    {
        return $this->belongsTo(
            MeterReading::class,
            'meter_reading_id'
        );
    }

    public function reporter()
    {
        return $this->belongsTo(
            User::class,
            'reported_by'
        );
    }

    public function resolver()
    {
        return $this->belongsTo(
            User::class,
            'resolved_by'
        );
    }
}