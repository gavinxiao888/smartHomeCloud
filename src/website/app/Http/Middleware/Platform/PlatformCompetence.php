<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/2/28
 * Time: 15:23
 * @powr验证云平台权限
 */
namespace App\Http\Middleware\Platform;
use Cache;
use GetInfo;
class Platformcompetence  {

    public function handle($request, $next)
    {

        if (GetInfo::EVEVRYOO_SESSION_TIME()  <= 0)//判断服务器上存在SESSION，SESSION用COOKIE来存放
        {
            header('Content-Type: text/html; charset=UTF-8');
            echo '<script>alert("还没有登陆呦,快点去登陆吧！")</script>';
            echo '<script>window.location.href="/user/login"</script>';
            die();
        }

        @ini_set('session.gc_maxlifetime', GetInfo::EVEVRYOO_SESSION_TIME());//这个地方开启了SESSION其他地方可以不用开启了
        @session_start();
		//echo __FILE__ .__LINE__;
        if (!isset($_COOKIE['user'])) {
            header('Content-Type: text/html; charset=UTF-8');
            echo '<script>alert("登陆信息出了问题，请重新登陆！[cookie]")</script>';
            echo '<script>window.location.href="/user/login"</script>';
            die();
        }
		if (empty($_COOKIE['user']['email'])) {
            header('Content-Type: text/html; charset=UTF-8');
            echo '<script>alert("登陆信息已失效，请重新登陆！[cookie]37")</script>';
            echo '<script>window.location.href="/user/login"</script>';
            die();
        }
        try
        {
            // if ($_COOKIE['user']['sequence'] == $_SESSION['user']['sequence'])
            // {
                // if ($_COOKIE['user']['token'] == $_SESSION['user']['token'])//判断客户端的TOKEN信息和服务器端的TOKEN信息符合不符合
                // {


                // }
                // else
                // {
                    // header('Content-Type: text/html; charset=UTF-8');
                    // echo '<script>alert("登陆信息出了问题，请重新登陆！[token]")</script>';
                    // echo '<script>window.location.href="/user/login"</script>';
                    // die();
                // }
            // }
            // else
            // {
                // header('Content-Type: text/html; charset=UTF-8');
                // echo '<script>alert("登陆信息出了问题，请重新登陆！[sequence]")</script>';
                // echo '<script>window.location.href="/user/login"</script>';
                // die();
            // }

            // if (isset($_SERVER['HTTP_REFERER']))//对于请求头部存在的HTTP_REFERER属性的要进行过滤。
            // //现在的网站架构有点前后端分离了。
            // {
            // if ($_SERVER['HTTP_REFERER'] !='192.168.70.226')//这个地方的参数要设置一下
            // //这个地方使用了$_SERVER['HTTP_REFERER']，$_SERVER['HTTP_REFERER']是不稳定的，是有客户端决定的参数。
            // {
            // return Response::make('系统把你视为攻击者', 500);
            // }
            // }

            //以下是更新COOKIE和SESSION中的token

            // $old_cookie = $_COOKIE['user']['token'];

            // $token = md5(rand());//获取TOKEN
            // error_reporting(E_ALL);
            // //		$tf = setcookie('user[token]', $token, $_COOKIE['user']['deadline'],  '/', null, null, 1);//更新COOKIE
            // $tf = setcookie('user[token]', $token, $_COOKIE['user']['deadline'], '/', null, null, 0);

            // //		if($_COOKIE['user']['token'] != $token)//如果cookie没有成功设置的话，就把cookie还原
            // //		{
            // //			setcookie('user[token]', $old_cookie,$_COOKIE['user']['deadline'],  '/', null, null, 1);
            // //		}

            // $_SESSION['user']['token'] = $token;//更新SESSION

            //		header('Content-Type: text/html; charset=UTF-8');
            //		echo '<script defer="defer">function addCookie(name,value,expires,path){var str=name+"="+escape(value);if(expires!=""){str+=";expires="+expires}if(path!=""){str+=";path="+path}document.cookie=str};addCookie(', '"user[token]","', $token,'","', $_COOKIE['user']['deadline'], '","/"',');</script>';
            //		echo '<script defer="defer">window.onload = function (){ alert("1");}</script>';
            //		die();
        }

        catch (Exception $e)//catch防止错误
        {
            log::warning('{人为错误:权限发生错误;time:'.$_SERVER['REQUEST_TIME'].';错误代码:'.__FILE__.__LINE__.';}');
            header('Content-Type: text/html; charset=UTF-8');
            echo '<script>alert("登陆信息出了问题，请重新登陆！[catch]")</script>';
            echo '<script>window.location.href="/user/login"</script>';
            die();
        }
        return $next($request);
    }
}