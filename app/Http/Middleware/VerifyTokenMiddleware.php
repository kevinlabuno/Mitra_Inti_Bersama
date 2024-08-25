<?php

namespace App\Http\Middleware;

use App\Models\InvalidToken;
use Illuminate\Http\Request;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class VerifyTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Cek apakah token sudah invalid
            $token = $request->bearerToken(); // Ambil token dari header Authorization

            if ($token && InvalidToken::where('token', $token)->exists()) {
                return response()->json(['status' => false, 'status_code' => 401, 'message' => 'Token is invalid'], 401);
            }

            // Validasi token dan ambil user
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'Token is expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'Token is missing or invalid'], 401);
        }

        return $next($request);
    }
}
