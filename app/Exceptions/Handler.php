<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Detect instance
        if ($exception instanceof UnauthorizedHttpException) {
            // Detect previous instance
            if ($exception->getPrevious() instanceof TokenExpiredException) {
                return response()->json(['error' => 'TOKEN_EXPIRED'], $exception->getStatusCode());
            } elseif ($exception->getPrevious() instanceof TokenInvalidException) {
                return response()->json(['error' => 'TOKEN_INVALID'], $exception->getStatusCode());
            } elseif ($exception->getPrevious() instanceof TokenBlacklistedException) {
                return response()->json(['error' => 'TOKEN_BLACKLISTED'], $exception->getStatusCode());
            } else {
                return response()->json(['error' => "Unauthorized"], 401);
            }
        }
        return parent::render($request, $exception);
    }
}
