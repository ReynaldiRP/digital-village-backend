<?php

namespace App\Interfaces;

interface PasswordResetRepositoryInterface
{
    public function sendResetLink(string $email): string;
    public function resetPassword(array $data): string;
}
