<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterReading;

class UpdateReadingService
{
    public function calculateConsumption(
        float $currentReading,
        float $previousReading
    ): ?float
    {
        if (
            $currentReading <
            $previousReading
        ) {
            return null;
        }

        return $currentReading -
            $previousReading;
    }

    public function isAnomaly(
        float $currentReading,
        float $previousReading
    ): bool
    {
        return $currentReading <
            $previousReading;
    }
}