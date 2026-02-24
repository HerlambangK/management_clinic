<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/v1',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SetCurrentStore::class,
            \App\Http\Middleware\PreventBackHistory::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'setup.required' => \App\Http\Middleware\EnsureSetupIsRequired::class,
            'setup.completed' => \App\Http\Middleware\EnsureSetupIsCompleted::class,
            'customer.auth' => \App\Http\Middleware\EnsureCustomerIsAuthenticated::class,
            'customer.guest' => \App\Http\Middleware\RedirectIfCustomerAuthenticated::class,
            'customer.membership' => \App\Http\Middleware\EnsureCustomerHasActiveMembership::class,
            'customer.portal.premium' => \App\Http\Middleware\EnsureCustomerHasPremiumPortal::class,
            'feature' => \App\Http\Middleware\CheckFeature::class,
            'no-back' => \App\Http\Middleware\PreventBackHistory::class,
            'store.access' => \App\Http\Middleware\EnsureStoreIsAccessible::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
