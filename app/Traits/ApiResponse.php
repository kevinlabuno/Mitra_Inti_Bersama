<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{
    public function successResponse($data, $statusCode)
    {
        return response()->json($data)->setStatusCode($statusCode);
    }
    public function errorResponse($errorMessage, $statusCode)
    {
        return response()->json([$errorMessage], $statusCode);
    }
    public function errorMessage($errorMessage, $statusCode)
    {
        return response($errorMessage, $statusCode)->header('Content-Type', 'application/json');
    }
}
