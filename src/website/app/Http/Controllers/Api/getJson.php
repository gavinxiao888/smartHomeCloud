<?php namespace App\Http\Controllers;

use App\CloudPhoto;
use App\CloudPhotoPicture;
use App\ChildPhoto;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Request;
use Illuminate\Support\Facades\Input;

class getJson extends Controller{



    /**
     * 数组返unicode编码
     * @param $array
     * @return string
     */
    public function backJSON($array) {
        header("Content-type:text/html;charset=utf-8");
        $this->arrayRecursive($array, 'urlencode', true);

        $json = json_encode($array);

        return urldecode($json);

    }

/*
*  使用特定function对数组中所有元素做处理
*  @param  string  &$array     要处理的字符串
*  @param  string  $function   要执行的函数
*  @return boolean $apply_to_keys_also     是否也应用到key上
*  @access public
*
*************************************************************/
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false){
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
    }
}