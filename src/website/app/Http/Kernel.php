<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	//这里是全局中间件
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
//		'App\Http\Middleware\VerifyCsrfToken',
		// 'App\Http\Middleware\Admin\AdminStatusMiddleware',
		//自定义全局中间件。
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	//这里是自定义中间件
	protected $routeMiddleware = [		
		//自定义
		//用户登陆登录验证参数
		'PlatformLoginAction' => 'App\Http\Middleware\Platform\PlatformLoginAction',
		//用户权限验证
		'PlatformCompetence' => 'App\Http\Middleware\Platform\PlatformCompetence',
		//后台登陆验证参数
		'AdminStatusMiddleware'=>'App\Http\Middleware\Admin\AdminStatusMiddleware',
		//后台用户权限验证
		'AdminCompetence'=>'App\Http\Middleware\Admin\AdminCompetence',
		'Everyoo' => 'App\Http\Middleware\Everyoo\Everyoo',
 	];

}
