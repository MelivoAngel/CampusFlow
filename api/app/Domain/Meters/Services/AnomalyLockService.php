<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterAnomaly;

class AnomalyLockService
{
    public function hasUnresolvedAnomaly(
        int $meterId
    ): bool
    {
        return MeterAnomaly::where(
            'meter_id',
            $meterId
        )->where(
            'is_resolved',
            false
        )->exists();
    }
}