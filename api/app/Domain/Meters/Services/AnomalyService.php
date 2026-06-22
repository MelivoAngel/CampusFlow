<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterAnomaly;
use App\Domain\Meters\Models\MeterReading;
use App\Domain\Users\Models\User;

class AnomalyService
{
    public function create(MeterReading $reading,User $user,array $anomaly): ?MeterAnomaly{
        $existing = MeterAnomaly::where(
            'meter_reading_id',
            $reading->id
        )->where(
            'is_resolved',
            false
        )->first();

        if ($existing) {
            return null;
        }

        return MeterAnomaly::create([

            'meter_id' => $reading->meter_id,

            'meter_reading_id' => $reading->id,

            'reported_by' => $user->id,

            'type' => $anomaly['type'],

            'severity' => $anomaly['severity'],

            'message' => $anomaly['message']
        ]);
    }

    public function resolveExisting(MeterReading $reading,User $user): void{
        $existing = MeterAnomaly::where(
            'meter_reading_id',
            $reading->id
        )->where(
            'is_resolved',
            false
        )->first();

        if (! $existing) {
            return;
        }

        $existing->update([

            'is_resolved' => true,

            'resolved_by' => $user->id,

            'resolved_at' => now(),

            'resolution_note' =>
                'Resolved through technician correction'
        ]);
    }
}