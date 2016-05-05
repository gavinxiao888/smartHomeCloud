<?php
namespace App\Facade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
/*
 * @power 获取一些基本信息
 * @todo还有很多
 */
class GetInfo
{
    /*
     * @power获取IP
     */
    public function IP()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"]))
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        else if (!empty($_SERVER["REMOTE_ADDR"]))
            $cip = $_SERVER["REMOTE_ADDR"];
        else
            $cip = "0.0.0.0";//没正确获取
        return $cip;
    }

    /*
     * 获取everyooo用户信息的SESSION过期时间
     */
    public function EVEVRYOO_SESSION_TIME()//获取session的剩余过期时间,单位秒
    {
        @session_start();
        if (isset($_SESSION["user"])) {
            // return ceil(strtotime($time))/86400-(time( 'Y-m-d'))+1;过期时间的天数
            $session_extension = ceil($_SESSION["user"]["deadline"] - time());    //过期时间的秒数
//			echo $_SESSION["user"]["deadline"].'<br>'.time().'<br>',$session_extension;die();

            // @ini_set('session.gc_maxlifetime',$session_extension);
            // @session_start();
            return $session_extension;
            // die();
        } else {
            return 0;
        }
    }

    /*
     * @power 获取admin的用户信息的session的过期时间
     */
    public function adminSessionTime()
    {
        @session_start();

        if (isset($_SESSION['admin'])) {
            $session_extension = $_SESSION['admin']['deadline'] - intval(LARAVEL_START);/*//过期时间的秒数
            Log::alert($session_extension);
            Log::alert($_SESSION['admin']['deadline']);
*/
            return $session_extension;//剩余时间, 有可能是负值
        } else {
             
            return 0;
        }
    }

    /*
     * @power 得到当前请求的URL
     */
    public function getUrl()
    {
        return Request::url();
    }
}