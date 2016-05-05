<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/2/28
 * Time: 15:23
 * @powr验证后台权限
 */
namespace App\Http\Middleware\Admin;

use App\Http\Requests\Request;
use Cache;
use GetInfo;
use Log;

class AdminCompetence
{
    /**
     * @power 后台管理员的全局中间件，开始了session，并且实现了一个session的小的实现机制
     * @todo 此处可能有bug，该方法可以重构（header函数调用的地方可以放入一个function, 并且该函数也太长了），该方法可读性太差
     */
    public function handle($request, $next)
    {
		session_start();
		// $_SESSION['admin'] = array('id'=>1, 'admin' => 1, 'pass' => 1, 'token' => 1, 'sequence' => 1, 'deadline' => 1, 'rule' => 1, 'verify' => 0);
        // return $next($request);
        //获取sesion剩余的时间
        $time = GetInfo::adminSessionTime();

//        var_dump('SESION剩余时间:' . $time . ';位置:' . __FILE__ . __LINE__);
        if ($time <= 0)//判断服务器上存在SESSION，SESSION用COOKIE来存放
        {
            header('Content-Type: text/html; charset=UTF-8');
            echo '<script>!function(){alert("还没有登陆呦,快点去登陆吧！")}()</script>';
            echo '<script>!function(){window.location.href="/admin/login?ru=' . \Illuminate\Support\Facades\Request::path() . '"}()</script>';
            die();
        }
        //这个地方开启了SESSION其他地方可以不用开启了
        @ini_set('session.gc_maxlifetime', $time);
        @session_start();

        if (!isset($_COOKIE['admin'])) {
            header('Content-Type: text/html; charset=UTF-8');
            echo '<script>alert("登陆信息出了问题，请重新登陆！[cookie]")</script>';
            echo '<script>window.location.href="/admin/login"</script>';
            die();
        }

        //判断客户端的sequence信息和服务器端的sequence是不是相等
        if ($_COOKIE['admin']['sequence'] == $_SESSION['admin']['sequence']) {
            //判断客户端的TOKEN信息和服务器端的TOKEN信息符合不符合
            if ($_COOKIE['admin']['token'] == $_SESSION['admin']['token']) {
                error_reporting(0);
                //@power 该匿名函数回收session
                $GC = function () {
                    $unset = function ($item, $key) {
                        if (isset($item['deadline']))//存在过期时间的字段的话
                        {
                            if ($item['deadline'] < intval(LARAVEL_START)) {
                                unset($_SESSION[$key]);
                            }
                        }
                    };
                    array_walk($_SESSION, $unset);
                };
                //每一分钟调用一次回收session的匿名函数
                if (!Cache::has('sessionGC')) {
                    $GC();//调用匿名函数
                    //设置过期时间为1mins
                    Cache::put('sessionGC', 1, 1);
                }

            } else {
                header('Content-Type: text/html; charset=UTF-8');
                echo '<script>alert("登陆信息出了问题，请重新登陆！[token]")</script>';
                echo '<script>window.location.href="/admin/login"</script>';
                die();
            }
        } else {
            header('Content-Type: text/html; charset=UTF-8');
            echo '<script>alert("登陆信息出了问题，请重新登陆！[sequence]")</script>';
            echo '<script>window.location.href="/admin/login"</script>';
            die();
        }
        //检查权限
        if (self::checkRole()) {
            return $next($request);
        } 
        //以下是更新COOKIE和SESSION中的token
        //生产TOKEN
        $token = md5(rand());
        setcookie('admin[token]', $token, $_COOKIE['admin']['deadline'], '/', null, null, 0);
        //更新session中的token
        $_SESSION['admin']['token'] = $token;
		
		//这里可以每一次请求都访问一次DB， 密码修改掉线全部该用户
		// if ($_SESSION[密码] != $db['密码']) {
			// die;
		// }
        //检查权限
        if (self::checkRole()) {
            return $next($request);
        } else {
            //
            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Content-Type: text/html; charset=UTF-8');
                echo '<script>alert("你没有权限访问页面")</script>';
                echo '<script>window.location.href="', $_SERVER['HTTP_REFERER'], '"</script>';
                die();
            } else {
                header('Content-Type: text/html; charset=UTF-8');
                echo '<script>alert("你没有权限访问页面")</script>';
                echo '<script>window.location.href="/admin/index"</script>';
                die();
            }
        }


    }

    /*
     * @power 检查请求的URL对于该用户有无权限
     */
    private function checkRole()
    {
        $uri = \Illuminate\Support\Facades\Request::path();
        //页面操作权限数组，$preg是一个多维数组
        $pregs = $_SESSION['admin']['rule']['operation'];

        foreach ($pregs as $k) {
            if (preg_match('/' . $k['regular'] . '/', $uri)) {
                return true;
            }
        }
        return false;
    }
}