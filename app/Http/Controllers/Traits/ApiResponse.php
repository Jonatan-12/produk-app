<?php
namespace App\Http\Controllers\Traits;

trait ApiResponse
{
    protected function success($data = null, $message = '', $code = 200) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($message = '', $code = 400, $data = null) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
