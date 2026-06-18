<?php

namespace App\Domain\Meters\Policies;

use App\Domain\Meters\Enums\MeterPermission;
use App\Domain\Users\Models\User;

class MeterCreationPolicy
{
    public function canCreate(
        User $creator
    ): bool
    {
        return MeterPermission::canCreate(
            $creator->role
        );
    }
}