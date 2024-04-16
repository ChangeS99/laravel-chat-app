<?php

use App\Http\Middleware\CustomAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'custom-auth' => CustomAuth::class
        ]);
    })
    ->withEvents(discover: [
        __DIR__ . '../app/Events',
        __DIR__ . '../app/Jobs'
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
