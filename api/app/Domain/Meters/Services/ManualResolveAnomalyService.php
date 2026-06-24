<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterAnomaly;
use App\Domain\Users\Models\User;

class ManualResolveAnomalyService
{
    public function resolve(
        MeterAnomaly $anomaly,
        User $user,
        string $note
    ): void
    {
        $anomaly->update([

            'is_resolved' => true,

            'resolved_by' => $user->id,

            'resolved_at' => now(),

            'resolution_note' => $note
        ]);
    }
}