<?php

use Illuminate\Support\Facades\Route;

Route::middleware('response_formatter')
    ->group(__DIR__ . '/api/index.php');
