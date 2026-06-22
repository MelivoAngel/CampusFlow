<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterReading;

class ReadingValidationService
{
    public function getLastReading(
        int $meterId
    ): ?MeterReading
    {
        return MeterReading::where(
            'meter_id',
            $meterId
        )->latest(
            'recorded_date'
        )->first();
    }
}