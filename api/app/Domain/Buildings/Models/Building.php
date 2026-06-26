<?php

namespace App\Domain\Buildings\Models;

use App\Domain\Campuses\Models\Campus;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Meters\Models\Meter;

#[Fillable([
    'campus_id',
    'created_by',
    'name',
    'code',
    'description'
])]
class Building extends Model
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

    public function meters()
    {
        return $this->belongsToMany(

            Meter::class,

            'building_meter'

        )->withTimestamps();
    }
}