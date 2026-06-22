<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterReading;

class ApproveReadingService
{
    public function isAlreadyApproved(
        MeterReading $reading
    ): bool
    {
        return $reading->is_approved;
    }
}