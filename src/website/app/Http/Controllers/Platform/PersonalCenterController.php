<?php
//处理个人中心业务
namespace App\Http\Controllers\Platform;
use DB;
use View;
use Cache;
use Carbon;
use GetInfo;
class PersonalCenterController extends \App\Http\Controllers\Controller 
{
	//我的个人中心页面
	public function personalcenterview()
	{
		return View::make('platform.personalcenter.PersonalCenter');
	}
	//我的设备
	public function deviceview()
	{
		return View::make('platform.personalcenter.Device');
	}
	//添加我的设备
	public function adddeviceview()
	{
		return View::make('platform.personalcenter.AddDevice');
	}
	
}

?> 