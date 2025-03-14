<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponseService
{
    public function success(array $data = [], int $code = 200): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'code' => $code,
            'result' => $data,
        ], $code);
    }

    public function error(string $message, int $code = 400, array $details = []): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'code' => $code,
            'result' => null,
            'error' => [
                'message' => $message,
                'details' => $details,
            ],
        ], $code);
    }
}
