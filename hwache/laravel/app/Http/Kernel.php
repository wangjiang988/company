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
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'lpss' => \App\Http\Middleware\LoadPhpSelfSession::class,
        'auth.member' => \App\Http\Middleware\AuthMember::class, // 原先会员中心，以后需要删除
    	'auth.seller' => \App\Http\Middleware\AuthSeller::class, // 代理会员中心
        'auth.agents' => \App\Http\Middleware\AuthAgents::class,
        'auth.admin' => \App\Http\Middleware\AuthAdmin::class,
        'user.status' => \App\Http\Middleware\UserStatus::class, // 新版会员中心检测登录状态中间件
        'home.job_time' => \App\Http\Middleware\JobTime::class, // 检查网站工作时间中间件
    ];
}
