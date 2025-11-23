<?php

namespace App\Http\Helper;
use Illuminate\Support\Facades\Auth;
class ResponseHelper
{
    public static function success($data = null)
    {
        $message = __('message.operation_success');
        return response()->json(['status' => 'OK', 'data' => $data ?: $message], 200);
    }
    public static function create($data = null)
    {
        return response()->json(['status' => 'OK', 'data' => $data], 201);
    }

    public static function MissingParameter($message = 'message.missing_param')
    {
        $message = __('message.missing_param');
        return response()->json(['status' => 'WARNING', 'message' => $message], 400);
    }

    public static function DataNotFound($message = 'message.data_not_found')
    {
        $message = __('message.data_not_found');
        return response()->json(['status' => 'WARNING', 'message' => $message], 400);
    }

    public static function notFound($message = null)
    {
        $message = $message ?: __('message.data_not_found');
        return response()->json(['status' => 'ERROR','message' => $message ], 404);
    }

    public static function AlreadyExists($message = null)
    {
        $message = $message ?: __('message.already_exists');
        return response()->json(['status' => 'WARNING', 'message' => $message], 400);
    }

    public static function authorizationFail($message = 'message.not_authorized')
    {
        $message = __('message.not_authorized');
        return response()->json(['status' => 'ERROR', 'message' => $message], 401);
    }

    public static function authenticationFail($message = 'message.auth_fail')
    {
        $message = __('message.auth_fail');
        return response()->json(['status' => 'ERROR', 'message' => $message], 401);
    }

    public static function invalidData($message = null)
    {
        $message = $message ?: __('message.invalid_data');
        return response()->json(['status' => 'ERROR', 'message' => $message], 401);
    }

    // public static function operationFail($message = 'message.operation_fail')
    // {
    //     $message = __('message.operation_fail');
    //     return response()->json(['status' => 'ERROR', 'message' => $message], 500);
    // }
    public static function operationFail($message = null)
    {
        $message = $message ?: __('message.operation_fail');
        return response()->json(['status' => 'ERROR', 'message' => $message], 500);
    }

    /**
     * return error with extra details
     * @param string $message
     * @param mixed $details
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function errorDetails(string $message, ?array $details = null, int $statusCode = 500)
    {
        $response = [
            'status' => 'ERROR',
            'message' => $message,
        ];

        if (!is_null($details)) {
            $response['details'] = $details;
        }

        return response()->json($response, $statusCode);
    }

}
