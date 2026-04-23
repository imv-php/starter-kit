<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->middleware(['auth:sanctum'])
    ->as('auth.')
    ->group(__DIR__.'/auth.php');
