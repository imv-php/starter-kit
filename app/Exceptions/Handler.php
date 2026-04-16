<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class Handler extends \Illuminate\Foundation\Exceptions\Handler
{
    public function render($request, Throwable $e): Response
    {
        if ($request->is('api/*') || $request->expectsJson()) {
            return match (true) {
                $e instanceof AuthenticationException => response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized Request.',
                    'errors' => null,
                ], Response::HTTP_UNAUTHORIZED),

                $e instanceof ValidationException => response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY),

                $e instanceof NotFoundHttpException, $e instanceof RouteNotFoundException => response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage() ?: 'Route not found.',
                    'errors' => null,
                ], Response::HTTP_NOT_FOUND),

                $e instanceof HttpException => response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage() ?: 'Http Error',
                    'errors' => null,
                ], $e->getStatusCode()),

                default => response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage() ?: 'An unexpected error occurred.',
                    'errors' => env('APP_DEBUG') ? [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ] : null,
                ], Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }

        return parent::render($request, $e);
    }
}
