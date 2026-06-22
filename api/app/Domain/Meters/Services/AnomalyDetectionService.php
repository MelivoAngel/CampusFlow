<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterReading;

class AnomalyDetectionService
{
    public function detect(
        float $currentReading,
        ?MeterReading $lastReading
    ): ?array
    {
        if (! $lastReading) {
            return null;
        }

        if (
            $currentReading <
            $lastReading->current_reading
        ) {
            return [

                'type' => 'reverse_reading',

                'severity' => 'critical',

                'message' => 'Current reading is lower than previous reading'
            ];
        }

        if (
            $lastReading->consumption &&
            ($currentReading - $lastReading->current_reading) >
            ($lastReading->consumption * 1.5)
        ) {
            return [

                'type' => 'consumption_spike',

                'severity' => 'warning',

                'message' => 'Consumption increased abnormally compared to previous reading'
            ];
        }

        if (
            $lastReading->consumption &&
            ($currentReading - $lastReading->current_reading) <
            ($lastReading->consumption * 0.3)
        ) {
            return [

                'type' => 'consumption_drop',

                'severity' => 'warning',

                'message' => 'Consumption dropped abnormally compared to previous reading'
            ];
        }

        return null;
    }
}