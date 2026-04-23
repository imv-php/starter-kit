<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Auth\LoginDto;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Imv\Sso\Facades\Sso;

class AuthService
{
    /**
     * @throws Exception
     */
    public function login(LoginDto $dto): array
    {
        try {
            $token = Sso::getToken(
                redirectUrl: $dto->redirect_url,
                code: $dto->code,
                codeVerifier: $dto->code_verifier,
            );
        } catch (Exception $e) {
            throw new Exception('SSO token olishda xatolik: '.$e->getMessage());
        }

        try {
            $ssoUser = Sso::getUserData(access_token: $token->access_token);
        } catch (Exception $e) {
            throw new Exception('SSO user ma\'lumotlarini olishda xatolik: '.$e->getMessage());
        }

        return DB::transaction(function () use ($ssoUser) {
            $dbUser = User::query()
                ->wherePinfl($ssoUser->pinfl)
                ->first();

            if (! $dbUser) {
                // TODO: userga bog'liq integratsiyalar va databasega saqlash jarayonlari qo'shilishi kerak
                $dbUser = User::query()->create([
                    'pinfl'       => $ssoUser->pinfl,
                    'first_name'  => $ssoUser->firstname,
                    'middle_name' => $ssoUser->patronymic,
                    'last_name'   => $ssoUser->lastname,
                    'email'       => $ssoUser->pinfl.'@imv.test',
                    'password'    => Hash::make($ssoUser->pinfl),
                ]);
            }

            $dbUser->tokens()->delete();

            $token = $dbUser->createToken(
                name: 'API Token',
                expiresAt: Carbon::now()->addMinutes(config('sanctum.expiration')),
            );

            return [
                'token'      => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at,
            ];
        });
    }

    /**
     * @throws Exception
     */
    public function logout(): bool
    {
        try {
            return auth()->user()->currentAccessToken()->delete();
        } catch (Exception $e) {
            throw new Exception('Tizimdan chiqishda muammo: '.$e->getMessage());
        }
    }
}
