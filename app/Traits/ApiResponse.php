<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * پاسخ موفقیت‌آمیز
     */
    protected function successResponse($data, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * پاسخ خطا
     */
    protected function errorResponse(string $message, int $code = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }

    /**
     * پاسخ خطای 404
     */
    protected function notFoundResponse(string $message = 'مورد درخواستی یافت نشد.'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * پاسخ خطای اعتبارسنجی
     */
    protected function validationErrorResponse($errors): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'خطا در اعتبارسنجی داده‌ها',
            'errors' => $errors
        ], 422);
    }
} 