<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\UserResource;
use App\Services\AuthService;

readonly class AuthController
{
    public function __construct(protected AuthService $service) {}

    public function login(LoginRequest $request)
    {
        return LoginResource::make((object) $this->service->login($request->toDto()));
    }

    public function me()
    {
        return UserResource::make(auth()->user());
    }

    public function logout()
    {
        return $this->service->logout();
    }
}
