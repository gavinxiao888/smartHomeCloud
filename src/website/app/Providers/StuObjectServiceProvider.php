<?php namespace App\Providers;

include_once app_path() . '/Facade/StuObject.php';
use Illuminate\Support\ServiceProvider;
use App;
use MyFacade;
use Illuminate\Support\Facades\Facade;


class StuObjectServiceProvider extends ServiceProvider {

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
		App::bind('StuObject', function()
		{
			return new \MyFacade\GetInfo;
		});
	}

}


class StuObject extends Facade {

	protected static function getFacadeAccessor() { return 'StuObject'; }

}
