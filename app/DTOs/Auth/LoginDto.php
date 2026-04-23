<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use Spatie\LaravelData\Data;

class LoginDto extends Data
{
    public function __construct(
        public string $redirect_url,
        public string $code_verifier,
        public string $code,
    ) {}
}
