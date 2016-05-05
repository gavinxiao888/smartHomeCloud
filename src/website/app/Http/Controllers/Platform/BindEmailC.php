<?php
/*
*这里是绑定邮箱的C文件
*/
namespace App\Http\Controllers\Platform;
use DB;
use View;
use Events;
class BindEmailC extends \App\Http\Controllers\Controller
{
	public function platform_bindemail_show()//显示页面
	{

		return View::make('platform.BindEmailV');
	}
	/*
	 * @power 检查提交的邮箱有没有被绑定。绑定邮箱的动作：提交邮箱地址。
	 * @return {-1:update失败;1:成功;2:邮箱不合法;3邮箱已被注册}
	 * @todo 更改绑定邮箱的业务，可以在platform_bindemail_show方法里面检查一下该用户有没有bing邮箱
	 * @todo 如果用户没有找到收到邮件
	 */
	public function platformC_bindemailaction()
	{
//		return 1;
		$email = $_POST['email'];
//		$token = $_POST['token'];

		if (!isset($email))
		{
			return Response::make('参数不合法', 407);
		}
		if(!preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/', $email))//验证邮箱
		{
			return 2;
		}
		$ext = DB::SELECT('SELECT COUNT(*) AS count  from user where email = ?', array($email));//取得邮箱的记录

		if (!$ext[0]['count'])
		{
/*			$tf = DB::UPDATE('UPDATE user SET email = ? where id = ?', array($email, $_SESSION['user']['user']['id']));//更新记录

			if ($tf)
			{
				return 1;
			}
			else//update 失败
			{
				return -1;
			}*/

			$code = "";

			for($i=0;$i<4;$i++)
			{
				$code .= (string)rand(0,9);
			}

			$data = array('key'=>$code);


			Mail::queue('email.platform_bindemail', $data, function($message) use ($email)
			{
				$message->to($email, 'John Smith')->subject('Welcome!');
			});
			return 1;

		}
		else//邮箱已被注册
		{
			return 3;
		}
	}
	public function mailexist()//验证邮箱是否存在 
	{
		$email = $_POST['email'];
		$usermail = DB::table('user')->where('email','=',$email)->get();//取得邮箱的记录
		if (count($usermail,0)>0){
			$userinfo = DB::table('user_info')->where('user_id','=',$usermail[0]['id'])->get();
			$userinfo = array(
				'id'=>$userinfo[0]['id'],
				'problem_first'=>$userinfo[0]['problem_first'],
				'problem_second'=>$userinfo[0]['problem_second'],
				'problem_third'=>$userinfo[0]['problem_third']
			);
			return json_encode($userinfo);//存在
		}
		else{
			return json_encode(array('id'=>0));//不存在
		}
	}
	public function checkquestion()//验证密保问题
	{
		$count1 = 0;
		$count2 = 0;
		$count3 = 0;
		$userinfoid = $_REQUEST['id'];
		$answerfirst = $_REQUEST['answerfirst'];
		$answersecond = $_REQUEST['answersecond'];
		$answerthird = $_REQUEST['answerthird'];
		$userinfo = DB::table('user_info')->where('id','=',$userinfoid)->get();
		if($userinfo[0]['answer_first']!=''){
			if($answerfirst == $userinfo[0]['answer_first']){
				$count1 = 1;
			}
			else{
				$count1 = -1;
			}
		}
		if($userinfo[0]['answer_second']!=''){
			if($answersecond == $userinfo[0]['answer_second']){
				$count2 = 2;
			}
			else{
				$count2 = -2;
			}
		}
		if($userinfo[0]['answer_third']!=''){
			if($answerthird == $userinfo[0]['answer_third']){
				$count3 = 3;
			}
			else{
				$count3 = -3;
			}
		}
		if($count1>0||$count2>0||$count3>0){
			$user = DB::table('user')->where('id','=',$userinfo[0]['user_id'])->get();
			if($user[0]['email']==''){
				return 2;//邮箱未绑定
			}
			$code = "";
			for($i=0;$i<8;$i++){
				$code .= (string)rand(0,9);
			}
			$res = DB::table('user')->where('id','=',$userinfo[0]['user_id'])->update(array('passwd'=>md5($code)));
			$data = array('key'=>$code);
			// Mail::queue('email.platform_lost', $data, function($message) use ($user[0]['email']){
				// $message->to($user[0]['email'], 'John Smith')->subject('邮箱验证');
			// });
			return 1;//验证邮箱成功
		}
		if($count1<0&&$count2<0&&$count3<0){
			return 0;//验证失败
		}
		else{
			return -1;//密保未设置
		}
	}
	/*
	 * @power 验证验证码
	 * @return {1:匹配;0:失败;-1:验证码没通过}
	 *
	 */
	public function platform_code()
	{
		//return 1;
		$code = $_POST['code'];
		$mailname = $_POST['mailname'];

		if(!preg_match('/[0-9]{4}/', $code))//验证邮箱
		{
			return -1;
		}
		else{
			$res = DB::TABLE('user')->where('id', $_SESSION['user']['user']['id'])->update(array('email'=>$mailname));
			if($res>0){
				return 1;
			}
			else{
				return 0;
			}
		}
		/*
		 * todo验证代码
		 */
	}
	public function view_bindphone()//显示手机绑定页面
	{
		return View::make('platform.BindPhone');
	}
	public function platformC_bindphoneaction()//绑定手机的动作:提交手机
	{
		$phone = $_POST['email'];
		$ext = DB::SELECT('SELECT COUNT(*) AS count from user where phone = ?', array($phone));//取得手机的记录
		if (!$ext[0]['count'])
		{
			//Event::fire(new App\Events\SendNote([18363876252], '{$var}', [[date('y-m-d h:i:s', intval(LARAVEL_START))]]));
			return 1;
		}
		else//手机已被注册
		{
			return 3;
		}
	}
	public function platform_phone_code()
	{
		$code = $_POST['code'];
		$mailname = $_POST['mailname'];

		if(!preg_match('/[0-9]{4}/', $code))//验证邮箱
		{
			return -1;
		}
		else{
			$res = DB::TABLE('user')->where('id', $_SESSION['user']['user']['id'])->update(array('phone'=>$mailname));
			if($res>0){
				return 1;
			}
			else{
				return 0;
			}
		}
	}
	public function view_forgotpassword()//显示忘记密码页面
	{
		return View::make('platform.accountmanagement.ForgotPassword');
	}
}
?>











