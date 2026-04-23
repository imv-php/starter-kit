<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['response_formatter', 'throttle:api'])
    ->group(__DIR__.'/api/index.php');
