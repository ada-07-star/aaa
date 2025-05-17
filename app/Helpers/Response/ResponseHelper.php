<?php

namespace App\Helpers\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    /**
     * @param object $object
     * @param object $errors
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function responseDefault(object $object = new \stdClass(), object $errors = new \stdClass(), string $message = "", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $response = [];
        $response['message'] = $message;
        $response['errors'] = $errors;
        $response['object'] = $object;

        $statusCode = array_key_exists($statusCode, Response::$statusTexts) ? $statusCode : Response::HTTP_UNPROCESSABLE_ENTITY;
        return response()->json($response, $statusCode, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array|object $object
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function responseSuccess(array|object $object = [], string $message = "", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        if (is_array($object)) {
            $allData = new \stdClass();
            foreach ($object as $key => $value) {
                $allData->$key = $value;
            }

            $object = $allData;
        }

        $statusCode = array_key_exists($statusCode, Response::$statusTexts) ? $statusCode : Response::HTTP_UNPROCESSABLE_ENTITY;

        $response = [];
        $response['status'] = "success";
        $response['data'] = $object;

        $statusCode = array_key_exists($statusCode, Response::$statusTexts) ? $statusCode : Response::HTTP_OK;
        return response()->json($response, $statusCode, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array|object $errors
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function responseError(array|object $errors = new \stdClass(), string $message = "", int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        if ($message == ""){
            if (is_array($errors)) {
                $message = $errors[0] ?? null;
            } elseif ($errors instanceof Collection) {
                $message = $errors->first() ?? null;
            }

            if (empty($message) || (is_string($message) && !filled($message))) {
                $message = __("custom.defaults.failed");
            }
        }

        $statusCode = array_key_exists($statusCode, Response::$statusTexts) ? $statusCode : Response::HTTP_UNPROCESSABLE_ENTITY;
        $object = new \stdClass();
        $object->code = $statusCode;
        $object->message = [$message];


        $response = [];
        $response['status'] = "error";
        $response['error'] = $object;
        return response()->json($response, $statusCode, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);

    }

    /**
     * @param string $errorMessage
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function responseCustomError(string $errorMessage = "", int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $errors = [$errorMessage];
        return self::responseError($errors, statusCode: $statusCode);
    }


}
