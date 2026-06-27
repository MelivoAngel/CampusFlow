<?php

namespace App\Domain\Facilities\Services;

use App\Domain\Facilities\Enums\FacilityPermission;
use App\Domain\Facilities\Models\Facility;
use App\Domain\Users\Models\User;

class FacilityService
{
    public function resolveCampusId(
        User $creator,
        ?int $requestedCampusId
    ): ?int
    {
        if (
            FacilityPermission::requiresManualCampus(
                $creator->role
            )
        ) {
            return $requestedCampusId;
        }

        return $creator->campus_id;
    }

    public function create(
        User $creator,
        array $data
    ): Facility
    {
        $campusId = $this->resolveCampusId(

            $creator,

            $data['campus_id'] ?? null
        );

        return Facility::create([

            'campus_id' => $campusId,

            'created_by' => $creator->id,

            'name' => $data['name'],

            'description' =>
                $data['description'] ?? null,

            'is_active' => true
        ]);
    }
}