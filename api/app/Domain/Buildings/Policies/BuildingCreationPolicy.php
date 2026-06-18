<?php

namespace App\Domain\Buildings\Policies;

use App\Domain\Buildings\Enums\BuildingPermission;
use App\Domain\Users\Models\User;

class BuildingCreationPolicy
{
    public function canCreate(
        User $creator
    ): bool
    {
        return BuildingPermission::canCreate(
            $creator->role
        );
    }
}