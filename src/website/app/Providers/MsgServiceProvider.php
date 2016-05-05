<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use App\Facade\MyFacade;
use Illuminate\Support\Facades\Facade;


class MsgServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		App::bind('Msg', function()
		{
			return new App\Facade\Msg;
		});
	}
}


class Msg extends Facade {

	protected static function getFacadeAccessor() 
	{
		return 'Msg'; 
	}
}
