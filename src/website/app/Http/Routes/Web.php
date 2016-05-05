<?php

Route::group(['namespace' => 'Web'], function () {

	ini_set('session.gc_maxlifetime', 5); //设置时间
	session_start();
	/*
	 * 推送页面show
	 * 2015.09.24 create
	 */
	route::get('/push/show', 'WebController@pushViewsShow');

	/*
	 * 推送页面show
	 * 2015.09.24 create
	 */
	route::get('/index/show', 'WebController@indexViewsShow');


	/*
	 * 推送请求
	 * 2015.09.28 create
	 */
	route::post('/push/msg', 'WebController@pushMessage');
	
	/*
	 * 跳转找回密码页面
	 * 2015.09.28 create
	 */
	route::get('/user/forgotpassword','WebController@goFindPassViewFirst');//显示忘记密码页面


	/*
	 * POST : 找回密码->发送验证码
	 * 2015.09.28 create
	 */
	route::post('/user/find/pass','WebController@userPushCode');//显示忘记密码页面

	/*
	 * 跳转找回密码页面第二步
	 * 2015.09.28 create
	 */
	route::get('/user/forgotpassword/second','WebController@goFindPassViewSecond');//显示忘记密码页面

	/*
	 * POST : 找回密码->修改密码
	 * 2015.09.28 create
	 */
	route::post('/user/find/pass/edit','WebController@editPass');//显示忘记密码页面

		/*
	 * 跳转找回密码页面第三步
	 * 2015.09.28 create
	 */
	route::get('/user/forgotpassword/third','WebController@goFindPassViewThird');//显示忘记密码页面



	/*
	 * 测试session过期时间
	 * 2015.12.09 create
	 */
	route::get('/session/set',function() {

		// ini_get('session.gc_maxlifetime');//得到ini中设定值
		$_SESSION['A'] = '嫣蛋';
	});

	/*
	 * 测试session过期时间
	 * 2015.12.09 create
	 */
	route::get('/session/show',function() {
		echo $_SESSION['A'];
	});
});