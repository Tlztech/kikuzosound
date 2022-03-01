<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\AfterRequest::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth_customer_admin' => \App\Http\Middleware\AuthenticateCustomerAdmin::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'user_request' => \App\Http\Middleware\UserRequest::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'role_root' => \App\Http\Middleware\RedirectRoleRoot::class,
        'log' => \App\Http\Middleware\LogAfterRequest::class,
        'no_auth_only' => \App\Http\Middleware\NoAuthOnly::class,
        'admin_only' => \App\Http\Middleware\AdminOnly::class,
        'customer_admin_only' => \App\Http\Middleware\CustomerAdminOnly::class,
        'member_only' => \App\Http\Middleware\MemberOnly::class,
        'cancel' => \App\Http\Middleware\Cancel::class,
        //other middlewares
        'cors' => 'App\Http\Middleware\Cors',
        'auth.api' => 'App\Http\Middleware\ApiAuthMiddleware',
    ];
}
