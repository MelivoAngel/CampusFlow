<?php

namespace App\Domain\Meters\Services;

class CorrectionService
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
}