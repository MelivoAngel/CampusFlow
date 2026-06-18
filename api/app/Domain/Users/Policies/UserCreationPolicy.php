<?php

namespace App\Domain\Users\Policies;

use App\Domain\Users\Enums\UserRole;
use App\Domain\Users\Models\User;

class UserCreationPolicy
{
    public function canCreate(
        User $creator,
        string $targetRole
    ): bool
    {
        $creatorRole = UserRole::from(
            $creator->role
        );

        return $creatorRole->canCreate(
            $targetRole
        );
    }
}