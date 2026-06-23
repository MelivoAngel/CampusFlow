<?php

namespace App\Domain\Users\Requests;

use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Validator;

class UpdateUserRequest
{
    public function validate(
        array $data,
        int $id
    ): array
    {
        return Validator::make(
            $data,
            [

                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'email' => [
                    'required',
                    'email',
                    'unique:users,email,' . $id
                ],

                'password' => [
                    'nullable',
                    'min:8'
                ]
            ]
        )->validate();
    }
}