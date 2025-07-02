<?php

use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error.',
                'data' => $e->validator->getMessageBag(),
            ], 422);
        });
        $exceptions->renderable(function (Throwable $e) {
            if($e->getMessage() && $e->getMessage()=='Unauthenticated'){
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated',
                    'data' => [],
                ], 401);
            }
            return response()->json([
                'status' => false,
                'message' => 'Server Error.',
                'data' => $e->getMessage(),
            ], 500);
        });
    })->create();
