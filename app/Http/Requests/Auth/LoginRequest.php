<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\DTOs\Auth\LoginDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'redirect_url'  => ['required', 'string'],
            'code'          => ['required', 'string'],
            'code_verifier' => ['required', 'string'],
        ];
    }

    public function toDto(): LoginDto
    {
        return LoginDto::from($this->validated());
    }
}
