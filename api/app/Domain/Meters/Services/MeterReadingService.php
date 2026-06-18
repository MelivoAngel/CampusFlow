<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterReading;
use App\Domain\Users\Models\User;

class MeterReadingService
{
    public function calculateConsumption(
        float $currentReading,
        ?MeterReading $lastReading
    ): ?float
    {
        if (! $lastReading) {
            return null;
        }

        if (
            $currentReading <
            $lastReading->current_reading
        ) {
            return null;
        }

        return $currentReading -
            $lastReading->current_reading;
    }

    public function storeReading(
        array $validated,
        User $user,
        ?MeterReading $lastReading,
        bool $hasAnomaly,
        ?float $consumption,
        string $photoPath
    ): MeterReading
    {
        return MeterReading::create([

            'meter_id' => $validated['meter_id'],

            'technician_id' => $user->id,

            'updated_by' => $user->id,

            'previous_reading' =>
                $lastReading?->current_reading ?? 0,

            'current_reading' =>
                $validated['current_reading'],

            'consumption' =>
                $consumption,

            'photo_path' =>
                $photoPath,

            'has_anomaly' =>
                $hasAnomaly,

            'anomaly_reason' =>
                $hasAnomaly
                    ? 'Reading lower than previous reading'
                    : null,

            'recorded_date' =>
                now()->toDateString()
        ]);
    }
}