<?php
//一个php文件可以有多个类
  abstract class Registration//抽象类
  {  	 
	  abstract public function tf($data);//强烈要求子类必须实现该功能函数
  }
// class AdminRegistration extends Registration//everyoo后台用户注册
// {

	// public function logintf($data)//$data是一个定义的一维数组
	// {
		// var_dump($data);
		// return $data;
	// }
	
// }
class PlatformRegistration extends Registration//云平台用户注册
{

	//约定返回值{-1:IP被锁定,在VIEW页面可以说成是未知错误;0:验证码已过期;1:注册成功;2:验证码不符;3:邮箱不匹配;4:密码不匹配;5:第二次输入的密码不一致;6:邮箱也被注册;7:密码为弱密码类型;}
	//参数$data为一维数组
	public function tf($data)
	{
		@session_start();

		if(!isset($_SESSION['platformcode']))//不存在session。判断在接收带数据时，验证码的SESSION还存在不存在
		{
			return 0;
		}
		else
		{		
			if($_SESSION["platformcode"] != $data['code'])//验证码不等于session中的值
			{
				return 2;
			}
			if(!preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/', $data['email']))//验证邮箱
			{
				return 3;
			}
			if (strlen($data['pass'] >16 || strlen($data['pass']) <8))//验证密码长度
			{
				return 4;
			}
			if(!preg_match('/^([0-9]+[a-zA-Z_]+[A-Za-z_0-9]+)|([a-zA-Z_]+[0-9]+[A-Za-z_0-9]+)/', $data['pass']))//验证密码。这个地方的验证码的正则不知道怎么验证字符数
			{
				return 4;
			}
			if($data['pass'] != $data['pass1'])//验证第二次输入的密码
			{
				return 5;
			}

/*			include app_path().'/Custom/xml.php';//加载读取XML类

			$xml = new xml;
			$word = $xml->read_1node(app_path().'/storage/XML/Weak_passwords.xml', 'all', 'word');//拿到弱口令的一维数组

			for($i = 0; $i < count($word, 0); $i++)
			{
				if(stristr($data['pass'], $word[$i]))//验证密码是否是弱密码
				{
					return 7;
				}
			}*/

		}
		$count=DB::table('user')->where("email", $data['email'])->count();
		
		if($count)
		{
			return 6;
		}
		else
		{
			if ($this -> BanIp())//限制在60分钟内只能成功注册5个账号
			{
				return -1;
			}
			$rst=DB::INSERT('insert into user(id,email,passwd,init_time) values(uuid(),?,?,?)',array($data['email'],md5($data['pass']),date('y-m-d h-i-s')));
			if($rst)
			{
				return 1;
			}
			else
			{
				return -1;
			}
			// else
			// {	
				// $rst=DB::INSERT('insert into user(id,email,pass,init_time) values(uuid(),?,?,?)',$email,$pass,date('y-m-d h-i-s'));		
				// return 70;
			// }
		}
	}
	
	 //约定返回值{0:ip被锁定;1:没有被锁定;}
	public function BanIp()//这个地方要设置一定的请求，多次输错密码则禁止登陆，需要等待一段时间
	{
		$ip = GetInfo::IP();

		if (!(Cache::tags('BAN')->get('platform_register_Ban'.$ip)))//判断存在这个cache
		{
			$value = array('ip'=>$ip, 'start_time'=>date('Y-m-d h-i-s'),'count'=>1);
		}
		else
		{
			$old_value = Cache::tags('BAN')->get('platform_register_Ban'.$ip);//存放老的cache信息
			$value = array('ip'=>$old_value['ip'], 'start_time'=>$old_value['start_time'], 'count'=>(int)$old_value['count']+1);
		}

		$expire = Carbon::now()->addMinutes(60);//过期时间
		Cache::tags('BAN')->put('platform_register_Ban'.$ip, $value, $expire);

		if($value['count']>=5)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
}
//login的工厂类，决定实例化那个类
class FacRegistration
{
	 public static function createObj($operate)
	 {
	   switch ($operate)
	   {
			 // case 'everyoo':
				 // return new AdminLogin();
				 // break;
			case 'platform':
				 return new PlatformRegistration();
				 break;	
		}
	}
}