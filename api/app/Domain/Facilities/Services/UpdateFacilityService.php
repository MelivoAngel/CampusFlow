<?php

namespace App\Domain\Facilities\Services;

use App\Domain\Facilities\Models\Facility;

class UpdateFacilityService
{
    public function update(
        Facility $facility,
        array $validated
    ): Facility
    {
        $facility->update([

            'name' =>
                $validated['name'],

            'description' =>
                $validated['description'] ?? null
        ]);

        return $facility;
    }
}