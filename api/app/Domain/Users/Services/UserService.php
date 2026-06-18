<?php

namespace App\Domain\Users\Services;

use App\Domain\Users\Enums\UserRole;
use App\Domain\Users\Models\User;

class UserService
{
    public function resolveCampusId(
        User $creator,
        ?int $requestedCampusId
    ): ?int
    {
        $creatorRole = UserRole::from(
            $creator->role
        );

        if ($creatorRole->requiresManualCampus()) {
            return $requestedCampusId;
        }

        return $creator->campus_id;
    }

    public function createUser(
        User $creator,
        array $data
    ): User
    {
        $campusId = $this->resolveCampusId(
            $creator,
            $data['campus_id'] ?? null
        );

        return User::create([

            'name' => $data['name'],

            'email' => $data['email'],

            'password' => $data['password'],

            'role' => $data['role'],

            'campus_id' => $campusId,

            'created_by' => $creator->id
        ]);
    }
}