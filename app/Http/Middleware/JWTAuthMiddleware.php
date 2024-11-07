<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class JWTAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the Authorization header exists and is correctly formatted
        $token = $request->bearerToken();

        // If no token is provided, return a simplified Unauthorized message
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Attempt to authenticate using the token
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401); // User not found
            }
        } catch (JWTException $e) {
            // Catch any JWT errors (e.g., invalid token, expired token)
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Add the user to the request for further use in controllers
        $request->attributes->add(['user' => $user]);

        return $next($request);
    }
}
