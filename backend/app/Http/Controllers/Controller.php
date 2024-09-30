<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

abstract class Controller
{
    protected function errorResponse($message = null, $errors = [], $code = 500)
    {
        return Response::json([
            'message' => $message == null && is_string($errors) ? $errors : $message,
            'errors' => $errors,
            'data' => [],
            'status' => $code
        ], $code);
    }

    protected function successResponse(
        $message,
        $data = [],
        $code = 200
    ) {
        return Response::json([
            'message' => $message,
            'data' => $data,
            'errors' => [],
            'status' => $code
        ], $code);
    }
}
