<?php

namespace App\Domain\Buildings\Models;

use App\Domain\Campuses\Models\Campus;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'campus_id',
    'created_by',
    'name',
    'code',
    'type',
    'description',
    'is_active'
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
}