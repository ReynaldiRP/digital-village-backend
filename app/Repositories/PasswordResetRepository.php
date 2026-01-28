<?php

namespace App\Repositories;

use App\Interfaces\PasswordResetRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    public function sendResetLink(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);
        return $status;
    }

    public function resetPassword(array $data): string
    {
        DB::beginTransaction();

        try {
            $status = Password::reset(
                $data,
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => bcrypt($password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    $user->tokens()->delete();
                }
            );

            DB::commit();

            return $status;
        } catch (\Exception $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
        }
    }
}
