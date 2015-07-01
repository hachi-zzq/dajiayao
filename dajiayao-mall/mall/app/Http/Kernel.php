<?php namespace Dajiayao\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession'
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'Dajiayao\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'Dajiayao\Http\Middleware\RedirectIfAuthenticated',
        'front.wx.shop.auth'=>'Dajiayao\Http\Middleware\FrontShopAuthenticate',
        'front.wx.seller.auth'=>'Dajiayao\Http\Middleware\FrontSellerAuthenticate',
        'auth.admin'=>'Dajiayao\Http\Middleware\AdminAuthenticate'
    ];

}
