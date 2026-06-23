<?php

namespace App\Domain\Campuses\Services;

use App\Domain\Campuses\Models\Campus;
use Illuminate\Database\Eloquent\Collection;

class GetCampusesService
{
    public function get(): Collection
    {
        return Campus::where(
            'is_active',
            true
        )->get();
    }
}