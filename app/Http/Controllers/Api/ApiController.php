<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="Torneo de Tenis API",
 *     version="1.0",
 *     @OA\Contact(
 *         email="julian.andres.arguello@gmail.com"
 *     )
 * ),
 * @OA\Server(
 *     url="http://localhost/api/",
 *     description="Servidor local de desarrollo"
 * )
 */
class ApiController extends Controller
{

    protected function successResponse($data = [], string $message = "ok", $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse(string $message, $code = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}
