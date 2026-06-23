<?php

namespace App\Domain\Users\Services;

use App\Domain\Users\Models\User;

class UpdateUserService
{
    public function update(
        User $user,
        array $data
    ): User
    {
        if (
            empty(
                $data['password']
            )
        ) {
            unset(
                $data['password']
            );
        }

        $user->update(
            $data
        );

        return $user;
    }
}