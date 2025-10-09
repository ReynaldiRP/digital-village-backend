<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginStoreRequest;
use App\Interfaces\AuthRepositoryInterface;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(LoginStoreRequest $request): ?object
    {
        $data = $request->validated();

        return $this->authRepository->login($data);
    }

    public function logout(): object
    {
        return $this->authRepository->logout();
    }

    public function me(): object
    {
        return $this->authRepository->me();
    }
}
