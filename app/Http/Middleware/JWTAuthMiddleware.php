<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JWTAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a Bearer token is provided
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Attempt to authenticate using the token
            $user = JWTAuth::parseToken()->authenticate();

            // If the user is not found, return Unauthorized
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        } catch (TokenExpiredException $e) {
            // Let Handler handle specific exception responses
            throw new UnauthorizedHttpException('', $e->getMessage(), $e);
        } catch (TokenInvalidException $e) {
            // Let Handler handle specific exception responses
            throw new UnauthorizedHttpException('', $e->getMessage(), $e);
        } catch (JWTException $e) {
            // Any general JWT exception (e.g., token not provided or malformed)
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Add the authenticated user to the request for use in controllers
        $request->attributes->add(['user' => $user]);

        // Allow the request to proceed
        return $next($request);
    }
}
