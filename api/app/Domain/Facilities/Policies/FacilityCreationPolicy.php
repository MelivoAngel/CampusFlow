<?php

namespace App\Domain\Facilities\Policies;

use App\Domain\Facilities\Enums\FacilityPermission;
use App\Domain\Users\Models\User;

class FacilityCreationPolicy
{
    public function canCreate(
        User $creator
    ): bool
    {
        return FacilityPermission::canCreate(
            $creator->role
        );
    }
}