<?php

declare(strict_types=1);

use App\Exceptions\Handler;
use App\Http\Middleware\ResponseFormatter;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'response_formatter' => ResponseFormatter::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //        $exceptions->render(function (Throwable $e, Request $request) {
        //            $handler = app(Handler::class);
        //
        //            return $handler->render($request, $e);
        //        });
    })->create();
