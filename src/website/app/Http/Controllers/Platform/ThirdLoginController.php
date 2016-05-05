<?php
/*
*��������¼
*/
namespace App\Http\Controllers\Platform;
use DB;
use View;
use Events;
class ThirdLoginController extends \App\Http\Controllers\Controller
{
	public function weibologin()//΢����������¼
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
				$token = $obj->getAccessToken( 'code', $keys ) ;//�����Ȩ
			} 
			catch (OAuthException $e) {
			}
		} 
		//echo $token['access_token'];
		
		$c = new \SaeTClientV2('4149177243','0225f8f2d3dad50033f2e5840377bbdb', $token['access_token']);
		//$ms =$c->home_timeline();
		$uid_get = $c->get_uid();//��ȡu_id
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id($uid);//��ȡ�û���Ϣ
		// header('Content-Type: text/html; charset=UTF-8');
		// var_dump($user_message);die();
		$res = $this->findid($uid,1);
		if($res == 1){
			return View::make('platform.ThirdRegistration')->with('name',$user_message['name']);
		}
		if($res == 2){
			header('Content-Type: text/html; charset=UTF-8');
			echo '<script>alert("��֤�ɹ�����ʹ��ע���˺ŵ�¼")</script>';
			echo '<script>window.location.href="/user/login"</script>';
		}
		else{
			header('Content-Type: text/html; charset=UTF-8');
			echo '<script>alert("��֤ʧ��")</script>';
			echo '<script>window.location.href="/user/login"</script>';
		}
		//return $user_message;
		//var_dump($uid);die();
	}
	function findid($uid,$type)//�жϵ������û��Ƿ����,type:1 ΢�� 2 QQ 3 ΢��
	{
		if($type == 1){//΢���û�
			$user = DB::table('third_parties')->where('sina_id','=',$uid)->get();
			if(count($user,0)>0){
				return 2;//΢���û��Ѵ���
			}
			else{
				$third = array('id'=>trim(com_create_guid(), '{}'),'user_id'=>0,'sina_id'=>$uid,'qq_id'=>0,'weixin_id'=>0);
				$res = DB::table('third_parties')->insert($third);
				if($res > 0){
					return 1;//���û�
				}
				else{
					return 0;//��ʧ��
				}
			}
		}
	}
}














