<?php

namespace App\Domain\Buildings\Services;

use App\Domain\Buildings\Enums\BuildingPermission;
use App\Domain\Buildings\Models\Building;
use App\Domain\Users\Models\User;

class BuildingService
{
    public function resolveCampusId(
        User $creator,
        ?int $requestedCampusId
    ): ?int
    {
        if (
            BuildingPermission::requiresManualCampus(
                $creator->role
            )
        ) {
            return $requestedCampusId;
        }

        return $creator->campus_id;
    }

    public function createBuilding(
        User $creator,
        array $data
    ): Building
    {
        $campusId = $this->resolveCampusId(
            $creator,
            $data['campus_id'] ?? null
        );

        return Building::create([

            'campus_id' => $campusId,

            'created_by' => $creator->id,

            'name' => $data['name'],

            'code' => $data['code'],

            'type' => $data['type'] ?? null,

            'description' =>
                $data['description'] ?? null,

            'is_active' => false
        ]);
    }
}