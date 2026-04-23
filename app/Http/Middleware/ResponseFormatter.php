<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Helpers\ResponseFormatterHelper;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResponseFormatter
{
    public function handle(Request $request, Closure $next): Response
    {
        $response   = $next($request);
        $statusCode = $response->getStatusCode();

        // 1. Skip formatting for certain response types
        if ($statusCode === 204) {
            return $response;
        }

        if ($response->isRedirection()) {
            return $response;
        }

        if (! $request->is('api/*') && ! $request->expectsJson()) {
            return $response;
        }

        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
        } else {
            $content = $response->getContent();
            if (is_string($content)) {
                $decoded = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = $decoded;
                } else {
                    $data = $content;
                }
            } else {
                $data = $content;
            }
        }

        // 3. Skip if already formatted
        if (is_array($data) && isset($data['status'])) {
            if (array_key_exists('data', $data) || array_key_exists('errors', $data)) {
                return $response;
            }
        }

        // 4. Extract message if exists
        $message = '';
        if (is_array($data) && isset($data['message'])) {
            $message = (string) $data['message'];
            unset($data['message']);
        }

        // 5. Format based on success or error
        $formatted = [];
        if ($statusCode >= 200 && $statusCode < 300) {
            // Check for pagination
            if (is_array($data) && (isset($data['meta']) || isset($data['current_page']))) {
                $formatted = ResponseFormatterHelper::paginated($data, $message);
            } else {
                $formatted = ResponseFormatterHelper::success($data, $message);
            }
        } else {
            // Check for explicit errors array
            $errors = $data;
            if (is_array($data) && isset($data['errors'])) {
                $errors = $data['errors'];
            }
            $formatted = ResponseFormatterHelper::error($message ?: 'An error occurred', $statusCode, $errors);
        }

        // 6. Return response
        if ($response instanceof JsonResponse) {
            return $response->setData($formatted);
        }

        return response()->json($formatted, $statusCode);
    }
}
