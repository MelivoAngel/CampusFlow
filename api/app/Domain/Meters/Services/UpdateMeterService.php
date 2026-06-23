<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\Meter;

class UpdateMeterService
{
    public function update(
        Meter $meter,
        array $data
    ): Meter
    {
        $meter->update(
            $data
        );

        return $meter;
    }
}