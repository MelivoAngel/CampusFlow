<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterAnomaly;
use App\Domain\Users\Models\User;

class ResolveAnomalyService
{
    public function resolveByReading(
        int $readingId,
        User $user
    ): void
    {
        $anomaly = MeterAnomaly::where(
            'meter_reading_id',
            $readingId
        )->where(
            'is_resolved',
            false
        )->first();

        if (! $anomaly) {
            return;
        }

        $anomaly->update([

            'is_resolved' => true,

            'resolved_by' => $user->id,

            'resolved_at' => now(),

            'resolution_note' => 'Resolved through staff correction'
        ]);
    }
}