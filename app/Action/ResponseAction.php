<?php

namespace App\Action;

use Illuminate\Http\JsonResponse;

class ResponseAction
{
    /**
     * @param $error
     * @param $message
     * @param $code
     * @param $data
     * @return JsonResponse
     * In this class we'll handle any kinds of responses
     */
    public static function response($error, $message, $code, $data): JsonResponse
    {
        return response()->json(
            [
                'error' => $error,
                'message' => $message,
                'data' => $data,
            ],
            $code
        );
    }

    public static function validationResponse($message): JsonResponse
    {
        return response()->json(
            [
                'error' => true,
                'message' => $message,
                'data' => null,
            ],
            422
        );
    }

    public static function errorResponse($message): JsonResponse
    {
        return response()->json(
            [
                'error' => true,
                'message' => $message,
                'data' => null,
            ],
            422
        );
    }

    public static function successResponse($message, $data): JsonResponse
    {
        return response()->json(
            [
                'error' => false,
                'message' => $message,
                'data' => $data,
            ],
            200
        );
    }
}
