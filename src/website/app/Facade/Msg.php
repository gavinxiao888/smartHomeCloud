<?php
namespace App\Facade;
use Illuminate\Support\Facades\Response;
/*
 * @power 主要为后台提交表单返回值设置提示页面
 */
class Msg
{
    //icon对应文件数组
    private static $icon = ['error' => 2, 'right' => 1];

    public function show($icon, $msg, $url)
    {
        $data = ['msg' => 'alert(' . $icon . ', "' . $msg . '");', 'url' => 'window.location.href="' . $url .'"'];
        return Response::view('admin.Error', ['data' => $data]);
    }


    public function error($msg, $url) 
    {
        return self::show(self::$icon['error'], $msg, $url);
    }

    public function right($msg, $url)
    {
        return self::show(self::$icon['right'], $msg, $url);
    }

}