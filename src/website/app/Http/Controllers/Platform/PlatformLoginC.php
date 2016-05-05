<?php
//云平台登陆,注册,找回密码
//不支持nickname登陆
namespace App\Http\Controllers\Platform;
use DB;
use View;
use Cache;
use Carbon;
use GetInfo;
class PlatformLoginC extends \App\Http\Controllers\Controller 
{
	
	public function login()//登陆动作
	{
		$ban_value = Cache::tags('BAN')->get('platform_login_Ban'.GetInfo::IP());
		if ($ban_value)//判断有没有这个Cache
		{
			if ($ban_value['count'] >= 5)
			{
				return -1;
			}
		}
		
		include_once app_path().'/Custom/Login.php';//加载Login的工厂类，使用了简单工厂的设计模式
		$login = \FacLogin::createObj('platform');//$login是得到的类
		
		$rst = $login->logintf(array("user"=>$_POST["user"],"pass"=>$_POST["pass"]));

		if($rst==1)
		{
			$this->setplatformcookie($_POST["twoweek"],$_POST["user"]);
		}
		return $rst;
	}
	public function setplatformcookie($twoweek,$email)//设置云平台cookie、session
	{
		$session_extension=7200+$twoweek*1209600;
		$cookie_extension=time()+7200+$twoweek*1209600;
		$mostinfo=$this->getplatforminfo($email);

		@ini_set('session.cookie_lifetime', $session_extension);//设置session的cookie的过期时间
		@ini_set('session.gc_maxlifetime', $session_extension);//设置过期时间
		@session_start();
		
//		@ini_set('session.cookie_httponly', 1);
		
		$sequence = md5(rand());
		$token = md5(rand());
		
		
		setcookie('user[email]', $email, $cookie_extension, '/', null, null, 0);
		setcookie('user[sequence]',$sequence, $cookie_extension, '/', null, null, 0);
		setcookie('user[token]', $token, $cookie_extension, '/', null, null, 0);
		setcookie('user[deadline]', $cookie_extension, $cookie_extension, '/', null, null, 0);
		//这个地方的setcookie现在还不完善，以后要修改一下
		
		$_SESSION["user"] = array("deadline"=> time()+($_POST['twoweek'] == 'true' ?1209600 : 7200),"user_info"=>$mostinfo, 'sequence' =>$sequence, 'token' =>$token);
	}
	
	function getplatforminfo($email)//得到云平台个人数据
	{
		
		$info=DB::table('user')->where('email',$email)->first();

		$moreinfo=DB::table('user_info')->where('user_id',$info["id"])->first();

		if (is_array($moreinfo))
		{
			return array_merge($info, $moreinfo);
		}
		else
		{
			return $info;
		}
	}
	public function view_login()//显示登录页面
	{
		if(isset($_COOKIE['username'])||isset($_COOKIE['password'])){
			@session_start;
			$res = $this->getlogininfo($_COOKIE['username'],$_COOKIE['password'],'false');
			//var_dump($res);die();
			if($res == 1){
				// header('Content-Type: text/html; charset=UTF-8');
				// echo '<script>alert("你已成功登录")</script>';
				// echo '<script>window.location.href="/user/center"</script>';
				return View::make('platform.personalcenter.PersonalCenter');
			}
		}
		return View::make('smarthomecloud.viewsLogin');
	}
	public function logout()//退出
	{
		@session_destroy();
		@session_unset();
		return View::make('smarthomecloud.viewsLogin');
	}
	public function loginaction()//登录
	{
		$user = $_REQUEST['user'];
		$pass = $_REQUEST['pass'];
		$twoweek = $_REQUEST['twoweek'];
		$res = $this->getlogininfo($user,$pass,$twoweek);
		return $res;
	}
	function getlogininfo($user,$pass,$twoweek)
	{
		$cookie_extension = time()+($twoweek == 'true' ?1209600 : 7200);
		$logininfo = DB::table('user')->where('email','=',$user)->first();
		if(count($logininfo,0)>0){
			if($logininfo['passwd'] == md5($pass)){
				@session_start();
				$sequence = md5(rand());
				$token = md5(rand());
				$userinfo = DB::table('user_info')->where('user_id','=',$logininfo['id'])->first();
				$_SESSION['user'] = array(
					'user'=>$logininfo,
					'user_info'=>$userinfo,
					'deadline'=>time()+($twoweek == 'true' ?1209600 : 7200),
					'sequence' =>$sequence,
					'token' =>$token
				);
				if($twoweek=='true'){
					setcookie('username',$user,time()+3600*24*14);
					setcookie('password',$pass,time()+3600*24*14);
					setcookie('user[email]', $user, $cookie_extension, '/', null, null, 0);
					setcookie('user[sequence]',$sequence, $cookie_extension, '/', null, null, 0);
					setcookie('user[token]', $token, $cookie_extension, '/', null, null, 0);
					setcookie('user[deadline]', $cookie_extension, $cookie_extension, '/', null, null, 0);
				}
				else{
					setcookie('username',$user,time()+3600*24*1);
					setcookie('password',$pass,time()+3600*24*1);
					setcookie('user[email]', $user, $cookie_extension, '/', null, null, 0);
					setcookie('user[sequence]',$sequence, $cookie_extension, '/', null, null, 0);
					setcookie('user[token]', $token, $cookie_extension, '/', null, null, 0);
					setcookie('user[deadline]', $cookie_extension, $cookie_extension, '/', null, null, 0);
				}
				return 1;//登录成功
			}
			else{
				return 2;//密码错误
			}
		}
		else{
			$logininfo = DB::table('user')->where('phone','=',$user)->first();
			if(count($logininfo,0)>0){
				if($logininfo['passwd'] == md5($pass)){
					@session_start();
					$sequence = md5(rand());
					$token = md5(rand());
					//var_dump($logininfo);die();
					$userinfo = DB::table('user_info')->where('user_id','=',$logininfo['id'])->first();
					$_SESSION['user'] = array(
						'user'=>$logininfo,
						'user_info'=>$userinfo,
						'deadline'=>time()+($twoweek == 'true' ?1209600 : 7200),
						'sequence' =>$sequence,
						'token' =>$token
					);
					if($twoweek=='true'){
						setcookie('username',$user,time()+3600*24*14);
						setcookie('password',$pass,time()+3600*24*14);
						setcookie('user[email]', $user, $cookie_extension, '/', null, null, 0);
						setcookie('user[sequence]',$sequence, $cookie_extension, '/', null, null, 0);
						setcookie('user[token]', $token, $cookie_extension, '/', null, null, 0);
						setcookie('user[deadline]', $cookie_extension, $cookie_extension, '/', null, null, 0);
					} 
					else{
					setcookie('username',$user,time()+3600*24*1);
					setcookie('password',$pass,time()+3600*24*1);
					setcookie('user[email]', $user, $cookie_extension, '/', null, null, 0);
					setcookie('user[sequence]',$sequence, $cookie_extension, '/', null, null, 0);
					setcookie('user[token]', $token, $cookie_extension, '/', null, null, 0);
					setcookie('user[deadline]', $cookie_extension, $cookie_extension, '/', null, null, 0);
				}
					return 1;//登录成功
				}
				else{
					return 2;//密码错误
				}
			}
			else{
				return 0;//用户不存在
			}
		}
	}
}

?>












