<?php
/*
*第三方登录
*/
namespace App\Http\Controllers\Platform;
use DB;
use View;
use Events;
class ThirdLoginController extends \App\Http\Controllers\Controller
{
	public function weibologin()//微博第三方登录
	{
		include_once app_path().'/Custom/thirdlogin/saetv2.ex.class.php';
		$obj = new \SaeTOAuthV2('4149177243','0225f8f2d3dad50033f2e5840377bbdb');
		$sina_url = $obj->getAuthorizeURL(THIRDLOGIN . 'weibothird');
		//return $sina_url;
		echo '<script language="javascript">window.location.href="'. $sina_url .'";</script>';
	}
	public function weibothird()
	{
		include_once app_path().'/Custom/thirdlogin/saetv2.ex.class.php';
		$token = array();
		//echo $_REQUEST['code'];
		$code = $_REQUEST['code'];
		$obj = new \SaeTOAuthV2('4149177243','0225f8f2d3dad50033f2e5840377bbdb');
		if (isset($code)) {
			$keys = array();
			$keys['code'] = $code;
			$keys['redirect_uri'] = THIRDLOGIN . 'weibothird';
			try {
				$token = $obj->getAccessToken( 'code', $keys ) ;//完成授权
			} 
			catch (OAuthException $e) {
			}
		} 
		//echo $token['access_token'];
		
		$c = new \SaeTClientV2('4149177243','0225f8f2d3dad50033f2e5840377bbdb', $token['access_token']);
		//$ms =$c->home_timeline();
		$uid_get = $c->get_uid();//获取u_id
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id($uid);//获取用户信息
		// header('Content-Type: text/html; charset=UTF-8');
		// var_dump($user_message);die();
		$res = $this->findid($uid,1);
		if($res == 1){
			return View::make('platform.ThirdRegistration')->with('name',$user_message['name']);
		}
		if($res == 2){
			header('Content-Type: text/html; charset=UTF-8');
			echo '<script>alert("验证成功，请使用注册账号登录")</script>';
			echo '<script>window.location.href="/user/login"</script>';
		}
		else{
			header('Content-Type: text/html; charset=UTF-8');
			echo '<script>alert("验证失败")</script>';
			echo '<script>window.location.href="/user/login"</script>';
		}
		//return $user_message;
		//var_dump($uid);die();
	}
	function findid($uid,$type)//判断第三方用户是否存在,type:1 微博 2 QQ 3 微信
	{
		if($type == 1){//微博用户
			$user = DB::table('third_parties')->where('sina_id','=',$uid)->get();
			if(count($user,0)>0){
				return 2;//微博用户已存在
			}
			else{
				$third = array('id'=>trim(com_create_guid(), '{}'),'user_id'=>0,'sina_id'=>$uid,'qq_id'=>0,'weixin_id'=>0);
				$res = DB::table('third_parties')->insert($third);
				if($res > 0){
					return 1;//绑定用户
				}
				else{
					return 0;//绑定失败
				}
			}
		}
	}
}














