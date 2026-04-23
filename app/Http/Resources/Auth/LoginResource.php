<?php

declare(strict_types=1);

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->token,
            'expires_at'   => $this->expires_at,
        ];
    }
}
