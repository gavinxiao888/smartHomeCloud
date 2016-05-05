<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Queue;
use Illuminate\Queue\Listener;
use DB;
use Illuminate\Support\Facades\Log;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		//视图组件
		// \View::composer('index', 'App\Http\ViewComposers\index');

        //处理失败的任务
		Queue::failing(function($connection, $job, $data) {
	/*		var_dump($connection);
			var_dump($job);
			var_dump($data);*/
        });
		DB::listen(function($sql, $bindings, $time) {
                    // logger("$time query :" .$sql . " ==>params{ " . implode(",", $bindings) ." }");
                    Log::info(date("H",  time()) . "onceruyi:sql" ."$time query :" .$sql . " ==>params{ " . implode(",", $bindings) ." }");
        });
		//在cli下会输出内容
		Queue::looping(function() {
			// var_dump(__FILE__);
		});
        //不知道为什么没有作用
        // Log::listen(function($level, $message, $context) {
        //     //
        //     var_dump($level);
        //     var_dump($message);
        //     var_dump($context);
        //     die;
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
