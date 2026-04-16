<?php

namespace App\Helpers;

class ResponseFormatterHelper
{
    /**
     * Format success response
     */
    public static function success($data = null, string $message = '', int $code = 200): array
    {
        return [
            'success'  => true,
            'message' => $message,
            'data'    => $data,
        ];
    }

    /**
     * Format error response
     */
    public static function error(string $message = '', int $code = 400, $errors = null): array
    {
        return [
            'success'  => false,
            'message' => $message,
            'errors'  => $errors,
        ];
    }

    /**
     * Format paginated response
     */
    public static function paginated($data, string $message = ''): array
    {
        $responseData = [
            'success'  => true,
            'message' => $message,
            'data'    => [],
            'meta'    => [],
        ];

        if (is_array($data)) {
            $responseData['data'] = $data['data'] ?? [];

            if (isset($data['meta']) && is_array($data['meta'])) {
                $responseData['meta'] = [
                    'current_page' => $data['meta']['current_page'] ?? null,
                    'last_page'    => $data['meta']['last_page'] ?? null,
                    'per_page'     => $data['meta']['per_page'] ?? null,
                    'total'        => $data['meta']['total'] ?? null,
                ];
            } elseif (isset($data['current_page'])) {
                $responseData['meta'] = [
                    'current_page' => $data['current_page'] ?? null,
                    'last_page'    => $data['last_page'] ?? null,
                    'per_page'     => $data['per_page'] ?? null,
                    'total'        => $data['total'] ?? null,
                ];
            }
        }

        return $responseData;
    }
}
