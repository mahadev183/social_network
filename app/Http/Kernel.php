<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Register individual route middleware
    protected $routeMiddleware = [
        'firebase.auth' => \App\Http\Middleware\FirebaseAuthMiddleware::class,
    ];

    // Define middleware groups for web and API routes
    protected $middlewareGroups = [
        'web' => [
            // Add other middleware for web routes here, e.g.:
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // other middleware...
        ],

        'api' => [
            // 'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
}
