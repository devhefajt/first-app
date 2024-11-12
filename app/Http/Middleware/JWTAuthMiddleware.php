<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenEncoded;
use Nowakowskir\JWT\Exceptions\TokenExpiredException;
use Illuminate\Http\Request;

class JWTAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            // Decode and validate the token
            $tokenEncoded = new TokenEncoded($token);
            $publicKey = env('JWT_PUBLIC_KEY'); // Assuming RS256; for HS256, use the JWT_SECRET
            $tokenEncoded->validate($publicKey, JWT::ALGORITHM_RS256);

            // Decode payload to get user information
            $payload = $tokenEncoded->decode()->getPayload();

            // Set user resolver to access Auth::user() in Laravel
            $request->setUserResolver(function () use ($payload) {
                return \App\Models\User::find($payload['sub']);
            });

        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }

        return $next($request);
    }
}
