<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->as('auth.')->group(__DIR__ . '/auth.php');
