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

    public function generateCode(
        int $campusId
    ): string
    {
        $count =
            Building::where(

                'campus_id',

                $campusId

            )->count() + 1;

        return
            'BLD-' .
            str_pad(

                $count,

                3,

                '0',

                STR_PAD_LEFT
            );
    }

    public function createBuilding(
        User $creator,
        array $data
    ): Building
    {
        $campusId =
            $this->resolveCampusId(

                $creator,

                $data['campus_id'] ?? null
            );

        $buildingCode =
            $this->generateCode(
                $campusId
            );

        return Building::create([

            'campus_id' =>
                $campusId,

            'created_by' =>
                $creator->id,

            'name' =>
                $data['name'],

            'code' =>
                $buildingCode,

            'description' =>
                $data['description']
                ?? null
        ]);
    }
}