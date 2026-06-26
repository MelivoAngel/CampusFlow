<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\Meter;

class MeterCodeService
{
    public function generate(
        int $campusId,
        string $resourceType
    ): string
    {
        $prefix = match ($resourceType) {
            'electricity' => 'EL',
            'water' => 'WT'
        };

        $latestMeter = Meter::where(
            'campus_id',
            $campusId
        )
        ->where(
            'resource_type',
            $resourceType
        )
        ->latest()
        ->first();

        $nextNumber = 1;

        if ($latestMeter) {
            $lastNumber = (int) substr(
                $latestMeter->meter_code,
                -3
            );

            $nextNumber = $lastNumber + 1;
        }

        return $prefix . '-' .
            str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );
    }
}