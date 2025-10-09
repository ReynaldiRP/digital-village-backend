<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function login(
        array $data
    ): ?object;

    public function logout(): object;

    public function me(): object;
}
