<?php
//处理个人中心业务的C文件
namespace App\Http\Controllers\Platform;
use DB;
use View;
class UserInfoC extends \App\Http\Controllers\Controller
{

	public function center($nickname, $token, $s_id)//显示个人中心信息。token过期了怎么办？--2015年1月10日 Z
	//$nickname是昵称;$token是token字段;$s_id是SESSION_ID
	{
		
	}
}

?> 