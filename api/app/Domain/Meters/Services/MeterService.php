<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Enums\MeterPermission;
use App\Domain\Meters\Models\Meter;
use App\Domain\Users\Models\User;

class MeterService
{
    public function __construct(
        private MeterCodeService $meterCodeService
    ) {}

    public function resolveCampusId(
        User $creator,
        ?int $requestedCampusId
    ): ?int
    {
        if (
            MeterPermission::requiresManualCampus(
                $creator->role
            )
        ) {
            return $requestedCampusId;
        }

        return $creator->campus_id;
    }

    public function meterCodeExists(
        int $campusId,
        string $meterCode
    ): bool
    {
        return Meter::where(
            'campus_id',
            $campusId
        )
        ->where(
            'meter_code',
            $meterCode
        )
        ->exists();
    }

    public function create(array $data)
    {
        $data['meter_code'] =
            $this->meterCodeService
                ->generate(
                    $data['campus_id'],
                    $data['resource_type']
                );

        return Meter::create($data);
    }
}