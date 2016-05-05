<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Log;
use DB;
abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;
        
        /**
         *   
          $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
         */
    public function log($param,$url,$file,$fun,$line){
        Log::info(date("H",  time()) . "onceruyi" . $url . " at file $file ;function $fun ; line $line " . json_encode($param));
    }


    /**
     * 生成uuid
     * @return mixed
     */
    public function creteBackUuid() {
        $uuid = DB::SELECT("select uuid() as uuid");
        return $uuid[0]['uuid'];
    }

    /**
     * 数组返unicode编码
     * @param $array
     * @return string
     */
    public function backJSON($array) {
        header("Content-type:text/html;charset=utf-8");
        $this->arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        $this->log($json . "\n____________________________________________________________", $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);

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
    
     /**
     * function：查找场景详细
     * create 2016.01.10
     * @param $bind
     * @param $robot
     * @return null
     */
    public function selectRobotInfo($bind, $robot) {
        $result = DB::table('robot_ctrl')
                ->Join('define_robot', 'define_robot.id', '=', 'robot_ctrl.robot_id')
                ->orderBy('robot_ctrl.position', 'asc')
                ->where('define_robot.status', '=', 1)
                ->where('define_robot.robot_id', '=', $robot)
                ->where('define_robot.bind_id', '=', $bind)
                ->where('robot_ctrl.bind_id', '=', $bind)
                ->where('robot_ctrl.status', '=', 1)
                ->get();
        return count($result, 0) > 0 ? $result : null;
    }
    
}
