<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/2/28
 * Time: 15:23
 * @powr验证后台登陆参数
 */
namespace App\Http\Middleware\Admin;


use Response;

class AdminLoginAction
{

    public function handle($request, $next)
    {

        if (!(isset($_POST["user"]) && isset($_POST["pass"]) && isset($_POST["code"]) ))//参数完整验证
        {
            return Response::make('参数不完整', 407);
        }
        return $next($request);
    }
}