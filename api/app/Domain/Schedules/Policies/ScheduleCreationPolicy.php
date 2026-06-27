<?php

namespace App\Domain\Schedules\Policies;

use App\Domain\Schedules\Enums\SchedulePermission;
use App\Domain\Users\Models\User;

class ScheduleCreationPolicy
{
    public function canCreate(
        User $creator
    ): bool
    {
        return SchedulePermission::canCreate(
            $creator->role
        );
    }
}