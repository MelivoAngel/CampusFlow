<?php

namespace App\Domain\Meters\Models;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'meter_id',
    'technician_id',
    'approved_by',
    'updated_by',
    'previous_reading',
    'current_reading',
    'consumption',
    'photo_path',
    'has_anomaly',
    'anomaly_reason',
    'is_approved',
    'was_corrected',
    'recorded_date'
])]
class MeterReading extends Model
{
    public function technician()
    {
        return $this->belongsTo(
            User::class,
            'technician_id'
        );
    }

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function meter()
    {
        return $this->belongsTo(
            Meter::class
        );
    }
}