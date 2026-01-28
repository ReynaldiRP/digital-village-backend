<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
use App\Interfaces\PasswordResetRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    private PasswordResetRepositoryInterface $passwordResetRepository;

    public function __construct(PasswordResetRepositoryInterface $passwordResetRepository)
    {
        $this->passwordResetRepository = $passwordResetRepository;
    }

    public function sendResetLink(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = $this->passwordResetRepository->sendResetLink($request->email);

        return response()->json([
            'success' => $status === Password::RESET_LINK_SENT,
            'message' => 'Link reset password berhasil dikirim ke email Anda.',
        ], $status === Password::RESET_LINK_SENT ? 200 : 400);
    }

    public function reset(PasswordResetRequest $request): JsonResponse
    {
        $status = $this->passwordResetRepository->resetPassword($request->validated());

        return response()->json([
            'success' => $status === Password::PASSWORD_RESET,
            'message' => 'Password berhasil direset.'
        ], $status === Password::PASSWORD_RESET ? 200 : 400);
    }
}
