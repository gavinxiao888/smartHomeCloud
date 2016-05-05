<?php
/*
*
*/
namespace App\Http\Controllers\Platform;
use DB;
use View;
class PasswordC extends \App\Http\Controllers\Controller
{
	public function platfrom_passwd_reg()//验证输入密码
	{
		$pass = $_POST["oldpass"];
		
		//session_start();

		$tf = DB::TABLE('user')->where('id', $_SESSION['user']['user']['id'])->first();
		
		if (!empty($tf))
		{
			if($tf["passwd"] == md5($pass))
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 0;
		}
	}
	public function platfrom_editaction()//
	{
		$pass = $_POST['pass'];
		$pass1 = $_POST['pass1'];
		
		if (!($this->platfrom_passwd_reg()))//
		{
			return 0;
		}
		
		if (!preg_match('/^[a-z0-9_-]{8,16}$/', $pass))//
		{
			return 2;
		}
		if ($pass != $pass1)
		{
			return 3;
		}
		
		//@session_start();
		
		$tf = DB::table('user')->where('id', $_SESSION['user']['user']['id'])->update(array('passwd' => md5($pass)));
		
		if ($tf)
		{
			return 1;
		}
		else
		{
			return -1;
		}
	}
	public function view_changepassword()//显示修改密码页面
	{
		return View::make('platform.ChangePassword');
	}
}
?>













