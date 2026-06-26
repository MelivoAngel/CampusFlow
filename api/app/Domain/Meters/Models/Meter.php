<?php

namespace App\Domain\Meters\Models;

use App\Domain\Buildings\Models\Building;
use App\Domain\Campuses\Models\Campus;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'campus_id',
    'created_by',
    'resource_type',
    'meter_code',
    'name',
    'description',
    'is_active'
])]
class Meter extends Model
{
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

    public function readings()
    {
        return $this->hasMany(
            MeterReading::class
        );
    }

    public function anomalies()
    {
        return $this->hasMany(
            MeterAnomaly::class
        );
    }

    public function buildings()
    {
        return $this->belongsToMany(

            Building::class,

            'building_meter'

        )->withTimestamps();
    }

    public function assignment()
    {
        return $this->hasOne(
            MeterAssignment::class
        );
    }
}