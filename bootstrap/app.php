<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php', // Add this line
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'prevent.back' => \App\Http\Middleware\PreventBackHistory::class,
            'check.user.status' => \App\Http\Middleware\CheckUserStatus::class,
            'update.activity' => \App\Http\Middleware\UpdateUserActivity::class,
        ]);
        
        // Add CheckUserStatus and UpdateUserActivity middleware to web group for authenticated users
        $middleware->web(append: [
            \App\Http\Middleware\CheckUserStatus::class,
            \App\Http\Middleware\UpdateUserActivity::class,
        ]);
        
        // Use custom CSRF middleware to exclude logout route
        $middleware->validateCsrfTokens(except: [
            'logout'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
