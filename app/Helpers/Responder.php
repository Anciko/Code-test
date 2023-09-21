<?php

namespace App\Helpers;

class Responder
{
    public static function success($message = 'success', $data = [])
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public static function fail($message, $data = [], $status_code = 404)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status_code);
    }
}
