<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/2/28
 * Time: 15:23
 * @power验证云平台登陆提交参数
 */
namespace App\Http\Middleware\PlatForm;
use Cache;
use Response;
class PlatformLoginAction  {

    public function handle($request, $next)
    {
        if (!(isset($_POST["user"])&&isset($_POST["pass"])&&isset($_POST["twoweek"])))//参数完整验证
        {
            return Response::make('参数不完整', 407);
        }
        if($_POST['twoweek'] != 1 && $_POST['twoweek'] !=0)//判断POST过来的参数
        {
            return Response::make('参数不合法', 407);
        }
        return $next($request);
    }
}