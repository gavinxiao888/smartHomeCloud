<?php
/*
*这里是处理用户头像的类
*/
namespace App\Http\Controllers\Platform;
use DB;
use View;
class HeadimgC extends \App\Http\Controllers\Controller
{
	public function exist()//是否存在图片,return{0:不存在;json:存在}
	{
	
		@session_start();
		
		if(file_exists(public_path().'/upfile/avatar/b_'.$_SESSION['user']['user']['id'].'.jpg'))
		{
			return json_encode(array('/upfile/avatar/b_'.$_SESSION['user']['user']['id'].'.jpg','/upfile/avatar/m_'.$_SESSION['user']['user']['id'].'.jpg','/upfile/avatar/l_'.$_SESSION['user']['user']['id'].'.jpg'));
		}
		else
		{
			return 0;
		}
		
	
	}
	public function showHeadimg()//这个地方的过滤器只用了一个，用来判断有没有session，cookies。这里依据$name来进行判断
	{
		
		// '@'关键字效率很低。不知道该不该用 error_reporting(0);
		// @ini_set('session.gc_maxlifetime', GetInfo::SESSION_TIME());
		//@session_start();	

		return View::make('platform.Headimg')->with('headimg', $this->img_exists($_SESSION['user']['user']['id']))->with('info', $_SESSION['user']['user_info']);
		//return View::make('platform.personalcenter.Headimg');
	}
	public function get_showHeadimg()//重新获取头像
	{
		$img = '/upfile/avatar/b_'.$_SESSION['user']['user']['id'].'.jpg';
		return $img;
	}
	public function img_exists($path)//图片路径，图片规定为JPG格式，上传过程中也是被限制的
	{
		if (file_exists('b_'.$path.'.jpg'))
		{
			return array('b_'.$path.'.jpg', 'm_'.$path.'.jpg', 'l_'.$path.'.jpg');
		}
		else
		{
			return 0;
		}
		
		// else 
		//2014年12月29日  AM 11:29
		 
	}
	

}

?>