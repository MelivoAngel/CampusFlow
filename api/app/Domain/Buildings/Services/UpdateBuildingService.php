<?php

namespace App\Domain\Buildings\Services;

use App\Domain\Buildings\Models\Building;

class UpdateBuildingService
{
    public function update(
        Building $building,
        array $validated
    ): Building
    {
        $building->update([

            'name' =>
                $validated['name'],

            'description' =>
                $validated['description'] ?? null
        ]);

        return $building;
    }
}