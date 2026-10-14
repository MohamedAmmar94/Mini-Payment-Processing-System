<?php
namespace App\Traits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
trait ResponseTrait {
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'error'   => false,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    protected function errorResponse(
        string $message = 'Something went wrong',
        int $statusCode = 400,
        mixed $data = null
    ): JsonResponse {
        return response()->json([
            'error'   => true,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }
}