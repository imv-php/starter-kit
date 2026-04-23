<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])
    ->withoutMiddleware('auth:sanctum')
    ->name('login');

Route::get('/me', [AuthController::class, 'me'])->name('me');
