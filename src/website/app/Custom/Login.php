<?php
//一个php文件可以有多个类
abstract class Login//抽象类
{
    abstract public function logintf($data);//强烈要求子类必须实现该功能函数
}

class AdminLogin extends Login//everyoo后台用户登陆
{

    /*
     *@power 验证登陆信息
     * @return{-1:IP收到服务器拒绝;1:OK;2:不存在用户;3:密码不对;5:验证码不对}
     */
    public function logintf($data)//$data是一个定义的一维数组
    {
        @session_start();
  
        if (!isset($_SESSION['admincode']))//检查session
        {
            return 5;
        }
        if ($data['code'] != $_SESSION['admincode']['code'])//检查验证码
        {
            return 5;
        }
        $info = DB::SELECT('SELECT `id`, `passwd` FROM admin WHERE nickname = ?', array($data['user']));
        if (!count($info, 0)) {
            return 2;
        }

        if ($info[0]['passwd'] != md5($data['pass'])) {
            if (self::BanIp() == 1) {
                return -1;
            } else {
                return 3;
            }
        } else {
            //销毁之前的SESSION
            session_unset();
            session_destroy();
            return $info[0]['id'];
        }

    }

    //约定返回值{0:ip被锁定;1:没有被锁定;}
    public function BanIp()//这个地方要设置一定的请求，多次输错密码则禁止登陆，需要等待一段时间
    {
        $ip = GetInfo::IP();

        if (!(Cache::tags('BAN')->get('admin_login_Ban' . $ip)))//判断存在这个cache
        {
            $value = array('ip' => $ip, 'start_time' => date('Y-m-d h-i-s'), 'count' => 1);
        } else {
            $old_value = Cache::tags('BAN')->get('admin_login_Ban' . $ip);//存放老的cache信息
            $value = array('ip' => $old_value['ip'], 'start_time' => $old_value['start_time'], 'count' => (int)$old_value['count'] + 1);
        }

        $expire = Carbon::now()->addMinutes(5);//过期时间
        Cache::tags('BAN')->put('admin_login_Ban' . $ip, $value, $expire);

        if ($value['count'] >= 5) {
            return 1;
        } else {
            return 0;
        }
    }

}

class PlatformLoginAction extends Login//云平台用户登陆
{

    //约定返回值{0:账号不存在;1:成功登陆;2:密码不对;-1:IP被锁定;}
    //参数$data为一维数组
    //@todo缺少验证
    public function logintf($data)
    {

        $rst = DB::SELECT("select passwd from user where email =? or phone=?", array($data["user"], $data["user"]));

        if (!count($rst)) {
            return 0;
        }

        if ($rst[0]["passwd"] == md5($data["pass"])) {
            session_unset();
//			session_destroy();
            return 1;
        } elseif ($rst[0]["passwd"] != md5($data["pass"])) {

            if ($this->BanIp() == 1) {
                return -1;
            } else {
                return 2;
            }
        }
    }

    //约定返回值{0:ip被锁定;1:没有被锁定;}
    public function BanIp()//这个地方要设置一定的请求，多次输错密码则禁止登陆，需要等待一段时间
    {
        $ip = GetInfo::IP();

        if (!(Cache::tags('BAN')->get('platform_login_Ban' . $ip)))//判断存在这个cache
        {
            $value = array('ip' => $ip, 'start_time' => date('Y-m-d h-i-s'), 'count' => 1);
        } else {
            $old_value = Cache::tags('BAN')->get('platform_login_Ban' . $ip);//存放老的cache信息
            $value = array('ip' => $old_value['ip'], 'start_time' => $old_value['start_time'], 'count' => (int)$old_value['count'] + 1);
        }

        $expire = Carbon::now()->addMinutes(5);//过期时间
        Cache::tags('BAN')->put('platform_login_Ban' . $ip, $value, $expire);

        if ($value['count'] >= 5) {
            return 1;
        } else {
            return 0;
        }
    }
}

class ApiPlatformLoginAction extends Login//云平台登陆API
{
    //约定返回值{-1:账号不存在;36位字符串:成功登陆;0:密码不对;}
    //参数$data为一维数组
    //@todo缺少验证, 没有对登陆设备做防护
    public function logintf($data)
    {

        $rst = DB::SELECT("select id,passwd from user where email =? or phone=?", array($data["user"], $data["user"]));

        if (!count($rst)) {
            return -1;
        }
        if ($rst[0]["passwd"] == $data["pass"]) {
            return $rst[0]['id'];
        } elseif ($rst[0]["passwd"] != $data["pass"]) {
            return 0;
        }
    }
}

//login的工厂类，决定实例化那个类
class FacLogin
{
    public static function createObj($operate)
    {
        switch ($operate) {
            case 'everyoo':
                return new AdminLogin();
                break;
            case 'platform':
                return new PlatformLoginAction();
                break;
            case 'apiplatform':
                return new ApiPlatformLoginAction();
                break;
        }
    }
}
?>