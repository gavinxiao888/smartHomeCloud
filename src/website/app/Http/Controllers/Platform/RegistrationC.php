<?php
//注册C文件
namespace App\Http\Controllers\Platform;
use DB;
use View;
use Cache;
class RegistrationC  extends \App\Http\Controllers\Controller
{
	public function platform()//云平台
	{
		$ban_value = Cache::tags('BAN')->get('platform_register_Ban'.\GetInfo::IP());
		if ($ban_value)//判断有没有这个Cache
		{
			if ($ban_value['count'] >= 5)
			{
				return -1;
			}
		}

		include_once app_path().'/Custom/registration.php';//加载简单工厂类
		
		$registration= \FacRegistration::createObj('platform');//实例化需要的类

		return $registration->tf(array('email' => $_POST['email'], 'pass' => $_POST['pass'], 'pass1' =>$_POST['pass1'], 'code' => $_POST['code']));
	}
	public function view_registration()//显示注册页面
	{
		return View::make('platform.registrationV');
	}
	// public function view_thirdregistration()//第三方登录验证通过后注册页面
	// {
		// return View::make('platform.RegistrationV');
	// }
}

?>