<?php

namespace App\Classes;


class ApiResponse
{
   /**
     * Summary of throw
     * @param string $e
     * @param string $message
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    public static function throw(string $e, string $message = "Internal Server Error")
    {
        \Illuminate\Support\Facades\Log::error($e);
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json(["message" => $message], 500));
    }

    /**
     * Summary of throwValidation
     * @param string $message
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    public static function throwValidation($errors, $message = "Validation Error")
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], 422));
    }

    /**
     * Summary of sendResponse
     * @param mixed $result
     * @param mixed $message
     * @param mixed $code
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public static function sendResponse($result, $message = null, $code = 200)
    {
        $response = [
            'success' => true,
            'data' => $result
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        return response()->json($response, $code);
    }
}
