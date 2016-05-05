<?php

namespace App\Http\Controllers\Api;

include_once(dirname(__FILE__) . '/' . 'notification/android/AndroidBroadcast.php');
include_once(dirname(__FILE__) . '/' . 'notification/android/AndroidFilecast.php');
include_once(dirname(__FILE__) . '/' . 'notification/android/AndroidGroupcast.php');
include_once(dirname(__FILE__) . '/' . 'notification/android/AndroidUnicast.php');
include_once(dirname(__FILE__) . '/' . 'notification/android/AndroidCustomizedcast.php');
include_once(dirname(__FILE__) . '/' . 'notification/ios/IOSBroadcast.php');
include_once(dirname(__FILE__) . '/' . 'notification/ios/IOSFilecast.php');
include_once(dirname(__FILE__) . '/' . 'notification/ios/IOSGroupcast.php');
include_once(dirname(__FILE__) . '/' . 'notification/ios/IOSUnicast.php');
include_once(dirname(__FILE__) . '/' . 'notification/ios/IOSCustomizedcast.php');

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class GateWayController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    
    /**
     *  10.2 网关新增设备对应关系备份
    *  先清后加 where gateway_id and device_id
    *  网关添加设备时对应的 device_id，devicetype之间的应射
    *  table device_relation
     * @param {gatewayid,sipaccount,sippwd,deviceid,devicetype}
     */
    public function addDeviceRelation(){
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (empty($app)){
            return $this->returnBackEnd('200', '操作失败', '-3', null);
        }
        // 默认接受到有效信息，无效信息则跑catch
        try {
            //验证用户是有效性
            if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                if ($this->dealAddDeviceRelation($app)) {
                    return $this->returnBackEnd('200', '操作成功', '1', null);
                } else {
                    return $this->returnBackEnd('200', '操作失败', '-4', null);
                }
            }
            return $this->returnBackEnd('200', '网关无效', '-1', null);
        } catch(Exception $e) {
            return $this->returnBackEnd('200', '操作失败', '-2', null);
        }
    }
    
    public function deleteDeviceRelation (){
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (empty($app)){
            return $this->returnBackEnd('200', '操作失败', '-3', null);
        }
        // 默认接受到有效信息，无效信息则跑catch
        try {
            //验证用户是有效性
            if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                $this->dealDeleteDeviceRelation($app);
                return $this->returnBackEnd('200', '操作成功', '1', null);
            }
            return $this->returnBackEnd('200', '网关无效', '-1', null);
        } catch(Exception $e) {
            return $this->returnBackEnd('200', '操作失败', '-2', null);
        }
    }
    
    public function listDeviceRelation(){
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (empty($app)){
            return $this->returnBackEnd('200', '操作失败', '-3', null);
        }
        // 默认接受到有效信息，无效信息则跑catch
        try {
            //验证用户是有效性
            if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                $list = $this->dealListDeviceRelation($app);
                return $this->returnBackEnd('200', '操作成功', '1', $list);
            }
            return $this->returnBackEnd('200', '网关无效', '-1', null);
        } catch(Exception $e) {
            return $this->returnBackEnd('200', '操作失败', '-2', null);
        }
    }
    private function dealAddDeviceRelation ($app){
        $this->dealDeleteDeviceRelation($app);
        $result = \App\DeviceRelation::create(
                [
                    'id'=>$this->createUuid(),
                    'gateway_id'=>$app->gatewayid,
                    'device_id'=>$app->deviceid,
                    'device_type'=>$app->devicetype,
                    'init_time'=>date("Y-m-d H:i:s",time())
                ]);
        return $result;
    }
    
    private function dealDeleteDeviceRelation($app){
        //删除原有关系
        \App\DeviceRelation::where("gateway_id",$app->gatewayid)
                ->where("device_id",$app->deviceid)
                ->delete();
        return true;
    }
    
     private function dealListDeviceRelation ($app){
        $list = \App\DeviceRelation::where("gateway_id",$app->gatewayid)
                ->get();
        $result = [];
        //{"id":"d84bf972-ecb1-11e5-8229-00163e0006e2","gateway_id":"KuoEr_99999","device_id":"65","device_type":"1","init_time":null}
        if (!empty($list)) {
            for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
                $result[$i]['id'] = $list[$i]->id;
                $result[$i]['gateway_id'] = $list[$i]->gateway_id;
                $result[$i]['device_id'] = $list[$i]->device_id;
                $result[$i]['device_type'] = $list[$i]->device_type;
                $result[$i]['init_time'] = $list[$i]->init_time;
            }
        }
        return $result;
    }
    
    /**
     * 32.Api:解除设备绑定.
     *  2015.11.30
     * 2015.12.01. update . 新增验证是否为主用户function
     * @return Response
     */
    public function removeGatewayDeviceBind() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户是有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                    // 验证是否有绑定该设备。
                    if ($this->verificationGatewayDeviceBind($app)) {
                        $result = $this->cancelGatewayDeviceBind($app);
                        return $this->returnGatewayBind('200', '操作成功', $result);
                    } else {
                        // 未发现该绑定设备。
                        return $this->returnGatewayBind('200', '并没有绑定该设备', '0');
                    }
                }
                return $this->returnGatewayBind('200', '网关无效', '-1');
            } catch(Exception $e) {
                return $this->returnGatewayBind('200', '操作失败', '-2');
            }
        }
        return $this->returnGatewayBind('200', '操作失败', '-3');
    }

    /**
     * 34.function：验证是否存在设备绑定关系.
     *  2015.11.30
     * @return Response
     */
    public function verificationGatewayDeviceBind($app) {
        $result = DB::table('device_manage')
            ->leftJoin('user_gateway_bind','user_gateway_bind.id', '=', 'device_manage.bind_id')
            ->where('user_gateway_bind.gateway_id', '=', $app->{'gatewayid'})
            ->where('device_manage.device_id', '=', $app->{'deviceid'})
            ->where('user_gateway_bind.status', '=', 1)
            ->where('device_manage.status', '=', 1)
            ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * 35.function:执行解除设备绑定操作.
     *  2015.11.30
     * @return Response
     */
    public function cancelGatewayDeviceBind($app) {
        $bind = $this->selectUserGatewayBind($app->{'gatewayid'});
        if (is_null($bind)){
            return "-6";
        }
        $result = \App\DeviceManage::where('bind_id', '=', $bind)
            ->where('device_id', $app->{'deviceid'})
            ->where('status', 1)
            ->update(['status' => 0]);
        $removeInfo = $this->removeDeviceInfo($bind, $app->{'deviceid'});
        return $removeInfo;
    }


    /**
     * Function: 清除当前设备所有相关信息
     * Create: 2016/02/29
     * @param $bind 绑定关系ID
     * @param $device 设备ID
     * @return mixed
     */
    public function removeDeviceInfo($bind, $device) {
        $ctrlArray = \App\DefineCtrl::where('bind_id', '=', $bind)
            ->where('device_id', $device)
//            ->where('status', 1)
            ->lists('ctrl_id')
            ->toArray();
        //执行清除定时操作
        $timer = $this->removeDeviceTimer($ctrlArray, $bind);
        //执行清除场景操作
        $robot = $this->removeDeviceRobot($ctrlArray, $bind);
        //执行清除联动操作
        $linkAge = $this->removeDeviceLinkAge($ctrlArray, $bind);
//        var_dump('timer'.$timer);
//        var_dump('robot'.$robot);
//        var_dump('link'.$linkAge);
//        die();
        //返回解析结果
        return $this->bluidResultForRemoveDeviceInfo($timer, $robot, $linkAge);
    }

    /**
     * Function: 解析删除设备解析类型
     * Create: 2016/02/29
     * @param $timer 删除定时返回的相应数据量
     * @param $robot 删除场景返回的相应数据量
     * @param $linkAge 删除联动返回的相应数据量
     * @return int
     */
    public function bluidResultForRemoveDeviceInfo($timer, $robot, $linkAge) {
        if ($timer != 0 && $robot != 0 && $linkAge != 0) {
            return 8;       //定时、场景、联动都存在
        } else if ($robot != 0 && $linkAge != 0) {
            return 7;       //场景和联动都存在
        } else if ($timer != 0 && $linkAge != 0) {
            return 6;       //定时和联动都存在
        } else if ($timer != 0 && $robot != 0) {
            return 5;       //定时和场景都存在
        } else if ($linkAge != 0) {
            return 4;       //只联动存在
        } else if ($robot != 0) {
            return 3;       //只场景存在
        } else if ($timer != 0) {
            return 2;       //只定时存在
        } else {
            return 1;       //定时、场景、联动都不存在
        }
    }

    /**
     * Function: 清除该设备的定时信息
     * Create 2016/02/29
     * @param $ctrl 指令集ID组
     * @param $bind 绑定关系ID
     * @return mixed
     */
    public function removeDeviceTimer($ctrl, $bind) {
        $result = \App\DefineTimer::where('bind_id', '=', $bind)
            ->whereIn('ctrl_id', $ctrl)
            ->update(array(
                'status' => 0
            ));
        return $result;
    }

    /**
     * Function: 清除该设备的场景信息
     * Create 2016/02/29
     * @param $ctrl 指令集ID组
     * @param $bind 绑定关系ID
     * @return mixed
     */
    public function removeDeviceRobot($ctrl, $bind) {
        $robot = \App\RobotCtrl::where('bind_id', '=', $bind)
            ->whereIn('ctrl_id', $ctrl)
            ->where('status', 1)
            ->lists('robot_id')
            ->toArray();
        $result = \App\DefineRobot::where('bind_id', '=', $bind)
            ->whereIn('id', $robot)
            ->update(array(
                'status' => 0
            ));
        return $result;
    }

    /**
     * Function: 清除该设备的联动信息
     * Create 2016/02/29
     * @param $ctrl 指令集ID组
     * @param $bind 绑定关系ID
     * @return mixed
     */
    public function removeDeviceLinkAge($ctrl, $bind) {
        $linkAge = \App\LinkAgeCtrl::where('bind_id', '=', $bind)
            ->whereIn('ctrl_id', $ctrl)
            ->where('status', 1)
            ->lists('link_age_id')
            ->toArray();
        
        $result = \App\DefineLinkAge::where('bind_id', '=', $bind)
            ->whereIn('id', $linkAge)
            ->update(array(
                'status' => 0
            ));
        //查询触发条件id
        $linkTriggered = \App\DefineTriggered::where('bind_id', '=', $bind)
                ->whereIn('ctrl_id', $ctrl)
                ->where('status', 1)
                ->lists('triggered_id')
                ->toArray();
        $result1 = \App\DefineLinkAge::where('bind_id', '=', $bind)
            ->whereIn('triggered', $linkTriggered)
            ->update(array(
                'status' => 0
            ));
        return $result+$result1;
    }

    /**
     * POST: 测试网关是否在线
     * Create: 2016/02/29
     * @return string
     */
    public function searchGatewayStatus() {
        return $this->returnGatewayBind('200', '操作成功', '1');
    }

    /**
     * POST: 网关获取最新版本信息
     * create: 2016/02/25
     * @return string
     */
    public function searchNewestVersion() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户是有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                    $newest = $this->bulidNewestVersion();
                    if (!empty($newest)) {
                        return $this->returnBackEnd('200', '操作成功', '1', $newest);
                    } else {
                        return $this->returnBackEnd('200', '操作成功', '0', $newest);
                    }
                }
                return $this->returnBackEnd('200', '网关无效', '-1', null);
            } catch(Exception $e) {
                return $this->returnBackEnd('200', '操作失败', '-2', null);
            }
        }
        return $this->returnBackEnd('200', '操作失败', '-3', null);
    }

    /**
     * function: 组建网关最新版本信息数据
     * create: 2016/02/25
     * @return null
     */
    public function bulidNewestVersion() {
        $result = null;
        $newest = $this->selectNewestVersion();
        if (!empty($newest)) {
            $result['version'] = $newest->name;
            $result['inittime'] = $newest->init_time;
            $result['href'] = $newest->href;
        }
        return $result;
    }

    /**
     * function: 获取网关最新版本信息
     * create：2016/02/25
     * @return null
     */
    public function selectNewestVersion() {
        $result = \App\GatewayVersionManage::orderBy('init_time', 'desc')
            ->where('type', 1)
            ->where('enable', 1)
            ->where('status', 1)
            ->get();
        return count($result, 0) > 0 ? $result[0] : null;
    }

    /**
     * Post:网关解除主用户绑定操作
     * create: 2016.02.17
     * @return string
     */
    public function removeGatewayBind() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户是否为主用户
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'}, $app->{'userid'})){
                    //执行解绑操作
                    return $this->returnGatewayBind('200', '操作成功',$this->updateGatewayBind($app));
                }
                return $this->returnGatewayBind('200', '网关无效', '-1');
            } catch(Exception $e) {
                return $this->returnGatewayBind('200', '操作失败', '-2');
            }
        }
        return $this->returnGatewayBind('200', '操作失败', '-3');
    }

    /**
     * 验证网关sn码是否有效
     * create 2015.10.13
     * update 2015.12.28 修改验证表
     * @return $result true or false.
     */
    public function verificationGatewayUserRole($snCode, $sipUser, $sipPass, $user) {
        $result = DB::table('subscriber')
            ->leftJoin('user_gateway_bind','user_gateway_bind.sip_user', '=', 'subscriber.username')
            ->where('subscriber.password', '=', $sipPass)
            ->where('user_gateway_bind.gateway_id', '=', $snCode)
            ->where('user_gateway_bind.sip_user', '=', $sipUser)
            ->where('user_gateway_bind.user_id', '=', $user)
            ->where('user_gateway_bind.status', '=', 1)
            ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * function: 解除网关绑定
     * create: 2016.02.17
     * @param $gateway 网关发来的信息
     * @return int
     */
    public function updateGatewayBind($gateway) {
        $gatewayBind = \App\UserGatewayBind::where('gateway_id', $gateway->{'gatewayid'})
            ->where('user_id', $gateway->{'userid'})
            ->where('status', 1)
            ->update(array(
               'update_time' => date('Y-m-d H:i:s', time()),
               'status' => 0
            ));
        return 1;
    }


    public function addedDevice() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                    //验证是否存在该设备
                    if ($this->verificationDeviceInfo($app)) {
                        //已存在相同的设备ID
                        return $this->returnGatewayBind('200', '设备重复', '-1' );
                    } else {
                        //执行新增设备操作
                        $result = $this->insertDeviceManage($app);
                        return $this->returnGatewayBind('200', '操作成功', '1');
                    }
                }
                return $this->returnGatewayBind('200', '网关无效', '-2');
            } catch(Exception $e) {
                return $this->returnGatewayBind('200', '操作失败', '-3');
            }
        }
        return $this->returnGatewayBind('200', '操作失败', '-4');
    }

    public function insertDeviceManage($app) {
        $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
        $result = \App\DeviceManage::create(array(
            'id' => $this->createUuid(),
            'bind_id' => $bindId,
            'device_id' => $app->{'deviceid'},
            'device_type' => $app->{'devicetype'},
            'version' => $app->{'firmversion'},
            'init_time' => $app->{'inclusiontime'},
        ));
        return 1;
    }

    public function verificationDeviceInfo($app) {
        $result = DB::table('device_manage')
            ->Join('user_gateway_bind','user_gateway_bind.id', '=', 'device_manage.bind_id')
            ->where('device_manage.device_id', '=', $app->{'deviceid'})
            ->where('user_gateway_bind.gateway_id', '=', $app->{'gatewayid'})
            ->where('user_gateway_bind.status', '=', 1)
            ->where('device_manage.status', '=', 1)
            ->get();
            if (count($result) > 0){
                $this->log($result, "bindagain", __FILE__, __FUNCTION__, __LINE__);
            }
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * POST:修改场景启用状态
     * create 2016.01.26
     * @return string
     */
    public function editRobotEnable() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                    //验证是否存在场景
                    $exist = $this->verificationDefineRobot($app->{'gatewayid'}, $app->{'robotid'});
                    if ($exist['exist']) {
                        //获取bind_id
                        $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
                        //执行删除操作
                        $result = $this->updateRobotEnable($app, $bindId);
                        return $this->returnGatewayBind('200', '操作成功', '1');
                    } else {
                        //不存在该场景
                        return $this->returnGatewayBind('200', '场景无效', '-3' );
                    }
                }
                return $this->returnGatewayBind('200', '网关无效', '-1');
            } catch(Exception $e) {
                return $this->returnGatewayBind('200', '操作失败', '-2');
            }
        }
        return $this->returnGatewayBind('200', '操作失败', '-4');
    }


    /**
     * function: 修改场景启用状态
     * create 2016/01/26
     * @param $app
     * @param $bind
     * @return mixed
     */
    public function updateRobotEnable($app, $bind) {
        $result = \App\DefineRobot::where('bind_id', '=', $bind)
            ->where('robot_id', $app->{'robotid'})
            ->where('status', 1)
            ->update(array(
                'enable' => $app->{'enable'},
                'update_time' => date('Y-m-d H:i:s', time())
            ));
        return $result;
    }

    /**
     * 查看场景详细
     * create 2016.01.10
     * @return string
     */
    public function searchRobotInfo() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                    //获取bind_id
                    $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
                    //组建场景详细
                    $result = $this->formationRobotInfo($bindId, $app->{'robotid'});
                    if (!empty($result)) {
                        return $this->returnBackEnd('200', '操作成功', '1', $result);
                    } else {
                        return $this->returnBackEnd('200', '操作成功', '0', null);
                    }
                }
                return $this->returnBackEnd('200', '网关无效', '-1', null);
            } catch(Exception $e) {
                return $this->returnBackEnd('200', '操作失败', '-2', null);
            }
        }
        return $this->returnBackEnd('200', '操作失败', '-4', null);
    }

    /**
     * function: 组建场景详细
     * create 2016.01.10
     * @param $bind
     * @param $robot
     * @return null
     */
    public function formationRobotInfo($bind, $robot) {
        $result = null;
        $list = $this->selectRobotInfo($bind, $robot);
        if (!empty($list)) {
            for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
                $result[$i]['robotid'] = $list[$i]['robot_id'];
//                $result[$i]['deviceid'] = $list[$i]['device_id'];
                $result[$i]['ctrlid'] = $list[$i]['ctrl_id'];
                $result[$i]['value'] = $list[$i]['value'];
                $result[$i]['position'] = $list[$i]['position'];
                $result[$i]['enable'] = $list[$i]['enable'];
            }
        }
        return $result;
    }

    /**
     * function：查找场景详细
     * create 2016.01.10
     * @param $bind
     * @param $robot
     * @return null
     
    public function selectRobotInfo__________($bind, $robot) {
        $result = DB::table('robot_ctrl')
            ->Join('define_robot','define_robot.id', '=', 'robot_ctrl.robot_id')
            ->where('define_robot.status', '=', 1)
            ->where('define_robot.robot_id', '=', $robot)
            ->where('define_robot.bind_id', '=', $bind)
            ->where('robot_ctrl.bind_id', '=', $bind)
            ->where('robot_ctrl.status', '=', 1)
            ->get();
        return count($result, 0) > 0 ? $result : null;
    }
*/
    /**
     * POST: 获取场景列表
     * create 2016.01.10
     * @return string
     */
    public function searchRobot() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                    //获取bind_id
                    $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
                    //组建场景列表
                    $result = $this->formationRobotList($bindId);
                    if (!empty($result)) {
                        return $this->returnBackEnd('200', '操作成功', '1', $result);
                    } else {
                        return $this->returnBackEnd('200', '操作成功', '0', null);
                    }
                }
                return $this->returnBackEnd('200', '网关无效', '-1', null);
            } catch(Exception $e) {
                return $this->returnBackEnd('200', '操作失败', '-2', null);
            }
        }
        return $this->returnBackEnd('200', '操作失败', '-4', null);
    }

    /**
     * function: 组建场景列表
     * create 2016.01.10
     * @param $bind
     * @return null
     */
    public function formationRobotList($bind) {
        $list = $this->selectRobot($bind);
        $result = null;
        if (!empty($list)) {
            for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
                $result[$i]['robotid'] = $list[$i]->robot_id;
                $result[$i]['robotname'] = $list[$i]->robot_name;
                $result[$i]['length'] = $list[$i]->length;
                $result[$i]['begin'] = $list[$i]->begin;
                $result[$i]['end'] = $list[$i]->end;
                $result[$i]['createtime'] = $list[$i]->create_time;
                $result[$i]['userid'] = $list[$i]->charge_id;
                $result[$i]['enable'] = $list[$i]->enable;
            }
        }
        return $result;
    }

    /**
     * function:查找场景
     * create 2016.01.10
     * @param $bind
     * @return mixed
     */
    public function selectRobot($bind) {
        $result = \App\DefineRobot::orderBy('create_time', 'asc')
            ->where('bind_id', '=', $bind)
            ->where('status', 1)
            ->get();
        return $result;
    }

    /**
     * POST:删除场景
     * create 2016.01.10
     * @return string
     */
    public function deleteDefineRobot() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                    //验证是否存在该场景
                    $exist = $this->verificationDefineRobot($app->{'gatewayid'}, $app->{'robotid'});
                    if ($exist['exist'] && $exist['is_init'] == 0) {
                        //获取bind_id
                        $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
                        //执行删除操作
                        $result = $this->updateRobotStatus($app, $bindId);
                        return $this->returnGatewayBind('200', '操作成功', $result);
                    } 
                    return $this->returnGatewayBind('200', '场景无效', '-3');
                    
                }
                return $this->returnGatewayBind('200', '网关无效', '-1');
            } catch(Exception $e) {
                return $this->returnGatewayBind('200', '操作失败', '-2');
            }
        }
        return $this->returnGatewayBind('200', '操作失败', '-4');
    }

    /**
     * POST:编辑场景
     * create 2016.01.10
     * @return string
     */
    public function editRobot() {
            $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
            if (!empty($app)){
                // 默认接受到有效信息，无效信息则跑catch
                try {

                    //验证用户有效性
                    if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                        //验证是否存在场景
                        $exist = $this->verificationDefineRobot($app->{'gatewayid'}, $app->{'robotid'});
                        if ($exist['exist']) {
                            //获取bind_id
                            $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
                            //执行删除操作
                            $update = $this->updateRobotStatus($app, $bindId);
                            //执行新建操作
                            $result = $this->createRobot($app, $bindId,$exist['is_init']);
                            return $this->returnGatewayBind('200', '操作成功', $result);
                        } 
                        //不存在该场景
                        return $this->returnGatewayBind('200', '场景无效', '-3' );
                        
                    }
                    return $this->returnGatewayBind('200', '网关无效', '-1');
                } catch(Exception $e) {
                    return $this->returnGatewayBind('200', '操作失败', '-2');
                }
            }
            return $this->returnGatewayBind('200', '操作失败', '-4');
    }

    /**
     * POST:新建场景
     * create 2016.01.10
     * @return string
     */
    public function defineRobot() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                    //验证是否存在重复
                    $exist = $this->verificationDefineRobot($app->{'gatewayid'}, $app->{'robotid'});
                    if ($exist['exist']) {
                        return $this->returnGatewayBind('200', '重复记录', '-3' );
                    } 
                    //获取bind_id
                    $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
                    //执行新建操作
                    $result = $this->createRobot($app, $bindId);
                    return $this->returnGatewayBind('200', '操作成功', $result);
                    
                }
                return $this->returnGatewayBind('200', '网关无效', '-1');
            } catch(Exception $e) {
                return $this->returnGatewayBind('200', '操作失败', '-2');
            }
        }
        return $this->returnGatewayBind('200', '操作失败', '-4');
    }

    /**
     * 修改场景动作的删除状态
     * 只能修改is_init = 0 的删除状态 1 2 3 4 5 分别对应默认创建的5个场景
     * @param $app
     * @param $bind
     * @return mixed
     */
    public function updateRobotStatus($app, $bind) {
        $result = \App\DefineRobot::where('bind_id', '=', $bind)
            ->where('robot_id', $app->{'robotid'})
            ->where('status', 1)
//            ->where('is_init',0)
            ->update(array(
                'update_time' => date('Y-m-d H:i:s', time()),
                'status' => 0
            ));
        return $result;
    }

    /**
     * function:新建场景
     * create 2016.01.10
     * @param $app
     * @return null
     */
    public function createRobot($app, $bind,$is_init = 0) {
        $robot = $this->insertDefineRobot($app, $bind,$is_init);
//        if (!is_array($app->info) && strpos($app->info, "[")){
//            
//        }
        if (is_array($app->{'info'})){
            foreach ($app->{'info'} as $ctrl) {
                $result = $this->insertRobotCtrl($ctrl, $bind, $robot);
            } 
            return $result;
        }
        $this->log($app->{'info'}, " infoisnotarray ", __FILE__, __FUNCTION__, __LINE__);
        return "0";
    }

    /**
     * function:新增场景定义
     * create 2016.01.10
     * @param $app
     * @return int
     */
    public function insertDefineRobot($app, $bind ,$is_init) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $result = \App\DefineRobot::create(array(
            'id' => $uuid[0]['uuid'],
            'bind_id' => $bind,
            'robot_id' => $app->{'robotid'},
            'robot_name' => $app->{'robotname'},
            'length' => $app->{'length'},
            'begin' => $app->{'begin'},
            'end' => $app->{'end'},
            'create_time' => $app->{'createtime'},
            'init_time' => date('Y-m-d H:i:s', time()),
            'charge_id' => $app->{'userid'},
            'enable' => $app->{'enable'},
            'status' => 1,
            'is_init' => $is_init
        ));
        return $uuid[0]['uuid'];
    }

    /**
     * function:新增场景映射
     * create 2016.01.10
     * @param $app
     * @return int
     */
    public function insertRobotCtrl($app, $bind, $robot) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $result = \App\RobotCtrl::create(array(
            'id' => $uuid[0]['uuid'],
            'bind_id' => $bind,
            'robot_id' => $robot,
//            'device_id' => $app['deviceid'],
            'ctrl_id' => $app->ctrlid,
            'value' => $app->value,
            'position' => $app->position,
            'init_time' => date('Y-m-d H:i:s', time()),
//            'enable' => $app['enable']
        ));
        return count($result, 0);
    }

    /**
     * function:判断是否存在重复的场景定义
     * create 2016.01.10
     * @param $app
     * @return bool
     */
    public function verificationDefineRobot($gateway, $robot) {
        $result =  DB::table('define_robot')
            ->Join('user_gateway_bind','user_gateway_bind.id', '=', 'define_robot.bind_id')
            ->where('user_gateway_bind.gateway_id', '=', $gateway)
            ->where('define_robot.robot_id', '=', $robot)
            ->where('user_gateway_bind.status', '=', 1)
            ->where('define_robot.status', '=', 1)
            ->select('define_robot.is_init')
            ->get();
        return count($result, 0) > 0 ? ["exist"=>true,"is_init"=>$result[0]['is_init']] : ["exist"=>false,"is_init"=>0];
    }

    /**
     * POST:删除定时操作
     * create.2016/01/08
     * @return string
     */
    public function deleteDefineTimer() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){

//                       if ($this->verificationReDefineTimer($app)) {
//                           //该时间点该设备已存在操作
//                           return $this->returnEndBackG('200', '操作成功', '-3', null);
//                       } else {
                    //执行新建操作
                    $result = $this->updateDefineTimerStatus($app);
                    return $this->returnGatewayBind('200', '操作成功', $result );
//
                }
                return $this->returnGatewayBind('200', '网关无效', '-1');
            } catch(Exception $e) {
                return $this->returnGatewayBind('200', '操作失败', '-2');
            }
        }
        return $this->returnGatewayBind('200', '操作失败', '-4');
    }

    public function updateDefineTimerStatus($app) {
        $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
        $result = \App\DefineTimer::where('status', '=', 1)
            ->where('bind_id', $bindId)
            ->where('timer_id', $app->{'timerid'})
            ->update(array(
                'update_time' => date('Y-m-d H:i:s', time()),
                'status' => 0
            ));
        return $result;
    }

    /**
     * POST:编辑定时操作
     * create.2016/01/08
     * @return string
     */
    public function editDefineTimer() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){

//                       if ($this->verificationReDefineTimer($app)) {
//                           //该时间点该设备已存在操作
//                           return $this->returnEndBackG('200', '操作成功', '-3', null);
//                       } else {
                    //执行新建操作
                    $result = $this->updateDefineTimer($app);
                    return $this->returnGatewayBind('200', '操作成功', $result );
//
                }
                return $this->returnGatewayBind('200', '网关无效', '-1');
            } catch(Exception $e) {
                return $this->returnGatewayBind('200', '操作失败', '-2');
            }
        }
        return $this->returnGatewayBind('200', '操作失败', '-4');
    }

    /**
     * function:修改定时操作
     * create 2016.01.08
     * @param $app
     * @return mixed
     */
    public function updateDefineTimer($app) {
        $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
        $result = \App\DefineTimer::where('status', '=', 1)
            ->where('bind_id', $bindId)
            ->where('timer_id', $app->{'timerid'})
            ->update(array(
                'ctrl_id' => $app->{'ctrlid'},
                'value' => $app->{'value'},
                'alarm_time' => $app->{'alarmtime'},
                'loop' => $app->{'loop'},
                'enable' => $app->{'enable'},
                'update_time' => date('Y-m-d H:i:s', time())
            ));
        return $result;
    }

    /**
     * POST:获取定时列表
     * create.2016/01/07
     * @return string
     */
    public function serachDefineTimer() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
//                       if ($this->verificationReDefineTimer($app)) {
//                           //该时间点该设备已存在操作
//                           return $this->returnEndBackG('200', '操作成功', '-3', null);
//                       } else {
                        //执行新建操作
                        $result = $this->formationTimer($app);
                        if (!empty($result)) {
                            return $this->returnBindGatewayAsNewVisition('200', '操作成功', '1', $result);
                        } else {
                            return $this->returnBindGatewayAsNewVisition('200', '操作成功', '0', $result);
                        }
//                       }
                }
                return $this->returnBindGatewayAsNewVisition('200', '网关无效', '-1', null);
            } catch(Exception $e) {
                return $this->returnBindGatewayAsNewVisition('200', '操作失败', '-2', null);
            }
        }
        return $this->returnBindGatewayAsNewVisition('200', '操作失败', '-4', null);
    }

    /**
     * function:组合timer列表
     * create.2016/01/07
     * @param $app app发出的请求条件
     * @return array
     */
    public function formationTimer($app) {
        $list = $this->selectDefineTimer($app->{'gatewayid'}, $app->{'deviceid'});
        $result = array();
        for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
            $result[$i]['timerid'] = $list[$i]['timer_id'];
            $result[$i]['deviceid'] = $list[$i]['device_id'];
            $result[$i]['alarmtime'] = $list[$i]['alarm_time'];
            $result[$i]['createtime'] = $list[$i]['create_time'];
            $result[$i]['loop'] = explode(",", str_replace(["[","]"," ","\""], "", $list[$i]['loop']));
            $result[$i]['ctrlid'] = $list[$i]['ctrl_id'];
            $result[$i]['value'] = $list[$i]['value'];
            $result[$i]['enable'] = $list[$i]['enable'];
        }
        return $result;
    }

    /**
     * function:查找所有的timer定义
     * create.2016/01/07
     * @param $gateway 网关ID
     * @param $device 设备ID
     * @return null
     */
    public function selectDefineTimer($gateway, $device) {
        $result = null;
        if ($device == 0) {
            $result = DB::table('define_timer')
                ->Join('user_gateway_bind','user_gateway_bind.id', '=', 'define_timer.bind_id')
                ->orderBy('define_timer.create_time', 'asc')
                ->where('user_gateway_bind.gateway_id', '=', $gateway)
                ->where('define_timer.status', '=', 1)
                ->get();
        } else {
            $result = DB::table('define_timer')
                ->Join('user_gateway_bind','user_gateway_bind.id', '=', 'define_timer.bind_id')
                ->orderBy('define_timer.create_time', 'asc')
                ->where('user_gateway_bind.gateway_id', '=', $gateway)
                ->where('define_timer.device_id', '=', $device)
                ->where('define_timer.status', '=', 1)
                ->get();
        }
        return $result;
    }

    /**
     * POST:新增定时操作
     * create.2016/01/08
     * @return string
     */
    public function appDefineTimer() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){

                       if ($this->verificationTimer($app)) {
                           //该时间点该设备已存在操作
                           return $this->returnGatewayBind('200', '操作成功', '-3');
                       } else {
                           //执行新建操作
                           $result = $this->insertIntoTimer($app);
                           return $this->returnGatewayBind('200', '操作成功', $result);
                       }
                }
                return $this->returnGatewayBind('200', '网关无效', '-1');
            } catch(Exception $e) {
                return $this->returnGatewayBind('200', '操作失败', '-2');
            }
        }
        return $this->returnGatewayBind('200', '操作失败', '-4');
    }

    /**
     * 验证是否存在重复的timer
     * @param $app 网关传送过来的内容
     * @return bool
     */
    public function verificationTimer($gateway) {
        $bindId = $this->selectUserGatewayBind($gateway->{'gatewayid'});
        $result = \App\DefineTimer::where('status', '=', 1)
            ->where('bind_id', $bindId)
            ->where('timer_id', $gateway->{'timerid'})
            ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * 新增定时操作
     * create.2016/01/07
     * @param $app 用户传过来的信息
     * @return static 返回响应条目数
     */
    public function insertIntoTimer($app) {
        $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
        $uuid = DB::SELECT("select uuid() as uuid");
        $result = \App\DefineTimer::create(array(
            'id' => $uuid[0]['uuid'],
            'bind_id' => $bindId,
            'timer_id' => $app->{'timerid'},
            'ctrl_id' => $app->{'ctrlid'},
            'device_id' => $app->{'deviceid'},
            'alarm_time' => $app->{'alarmtime'},
            'value' => $app->{'value'},
            'loop' => $app->{'loop'},
            'create_time' => $app->{'createtime'},
            'init_time' => date('Y-m-d H:i:s', time()),
            'charge_id' => $app->{'userid'}
        ));
        return count($result, 0);
    }

    /**
     * function:获取网关绑定ID
     * function 2016.01.07
     * @param $gatewayid 网关ID
     * @return null
     */
    public function selectUserGatewayBind($gatewayid) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $gatewayid)
            ->where('status', 1)
            ->get();
        return count($result, 0) > 0 ? $result[0]->id : null;
    }

    /**
     * post:验证设备类别
     * create 2016.01.04
     * @return Response
     */
    public function gatewaySearchDeviceType() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证sn码是否有效
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                    $result = $this->selectDeviceType();
                    if (!empty($result)) {
                        //操作失败 网关验证失败
                        $result = $this->formationDeviceAttributes($result);
                        return $this->returnBindGatewayAsNewVisition('200', '操作成功', '1', $result);
                    }else {
                        //操作成功 没有找到设备类型
                        return $this->returnBindGatewayAsNewVisition('200', '操作成功', '0', null);
                    }
                }
                //操作失败 网关验证失败
                return $this->returnBindGatewayAsNewVisition('200', '操作失败', '3', null);
            } catch(Exception $e) {
                return $this->returnBindGatewayAsNewVisition('500', '操作失败', '2', null);
            }
        } return $this->returnBindGatewayAsNewVisition('500', '操作失败', '5', null);
    }

    /**
     * function:组合device_attributes内容
     * create：2016/01/04
     * @param $list 获取到的数据
     * @return array 组合完成的结果
     */
    public function formationDeviceAttributes($list) {
        $result = array();
        for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
            $result[$i]['manufacturerid'] = $list[$i]->manufacturer_id;
            $result[$i]['productid'] = $list[$i]->product_id;
            $result[$i]['producttype'] = $list[$i]->product_type;
            $result[$i]['devicetype'] = $list[$i]->device_type;
        }
        return $result;
    }

    /**
     * function:获取所有设备分类信息
     * create: 2016/01/04
     * @return null
     */
    public function selectDeviceType() {
        $result = \App\DeviceAttributes::where('status', '=', 1)
            ->get();
        return count($result, 0) > 0 ? $result : null;
    }

    /**
     * 查看用户用户info信息
     * create 2015.11.10
     * @return Response
     */
    public function backUserSip($uid) {
        $sip = $this->searchAddress($uid);
        $result = array(
            'usersip' => $sip
            );
        return $result;
    }

    /**
     * 查询组建当前用户sip信息
     * @param $uid 用户的sip账户
     * @return null|string
     */
    public function searchAddress($uid) {
        $result = \App\Subscriber::where('username', '=', $uid)
                  ->get();
        return count($result, 0) > 0 ? $result[0]->username.'@'.$result[0]->domain: null;
    }

    /**
     * 返回用户绑定网关信息，最新版
     * create 2015.11.10
     * @return Response
     */
    public function returnBindGatewayAsNewVisition($code, $msg, $result, $info) {
         return $back = $this->backJSON(array(
            'code' => $code,
            'msg' => $msg,
            'result' => $result,
            'info' => $info
            ));
    }

//    public function insertQueues() {
//
//    }
    

    /**
     * 用户绑定网关
     * create 2015.10.13
     * @return Response
     */
    public function gatewayBind() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            if (!isset($app->userid) || $app->userid == ""){
                 return $this->returnBindGatewayAsNewVisition('200', '缺少用户ID参数', '6', null);
            }
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证sn码是否有效
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})){
                        //如果已存在重复绑定
                        if ($this->reVerificationReUserSip($app)) {
                            return $this->returnBindGatewayAsNewVisition('200', '重复绑定', 0, null);
                        } else {
                            if ($this->verificationUser($app->{'userid'})) {
                                 //组建要推送的内容
                                $array = $this->formationPushContent($app);
                                $pushType = $this->verificationPhoneType($app->{'userid'});
                                if ($pushType == '2') {
                                    //推送到ios内容
                                    $result = $this->pushToIos($app->{'userid'}, 'everyoo_user', '绑定网关成功', $array);
                                } else {
                                    //推送到android内容
                                    $result = $this->pushMsgToApp($app->{'userid'}, 'everyoo_user', '绑定网关成功', '您已成功绑定网关', $array);
                                }

                                    //暂不考虑存在历史绑定的情况
                                $end = $this->verificationReUserSip($app->{'userid'}, $app->{'gatewayid'});
                                $usersip = $this->backUserSip($app->{'userid'});
                                //操作成功  
                                
                                return $this->returnBindGatewayAsNewVisition('200', '操作成功', $result, $usersip);
                                
                            }//操作失败 用户无效
                            return $this->returnBindGatewayAsNewVisition('200', '操作失败', '4', null);
                        }
                }
                //操作失败 网关验证失败
                return $this->returnBindGatewayAsNewVisition('200', '操作失败', '3', null);
            } catch(Exception $e) {
                return $this->returnBindGatewayAsNewVisition('500', '操作失败', '2', null);
            }
        } return $this->returnBindGatewayAsNewVisition('500', '操作失败', '5', null);
    }
    

    /**
     * function:验证手机类型
     * create 2015.11.30
     * @return string
    **/
    public function verificationPhoneType($uid) {
        $token = \App\Token::where('user_id', '=', $uid)
                 ->where('type', 1)
                 ->get();
        return count($token, 0) > 0 ? $token[0]->remark : 1;
    }

    /**
     * function:推送到ios
     * create 2015.11.30
     * @return string
    **/
    public function pushToIos($alias, $aliasType, $title, $array)  {
          try {
            //测试环境
            $this->appkey = env('IOSKEY');
            $this->appMasterSecret = env('IOSAMS');
            $this->timestamp = strval(time());
            $customizedcast = new \IOSCustomizedcast();
            $customizedcast->setAppMasterSecret($this->appMasterSecret);
            $customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
            $customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);

            // Set your alias here, and use comma to split them if there are multiple alias.
            // And if you have many alias, you can also upload a file containing these alias, then 
            // use file_id to send customized notification.
            $customizedcast->setPredefinedKeyValue("alias", $alias);
            // Set your alias_type here
            $customizedcast->setPredefinedKeyValue("alias_type", $aliasType);
            $customizedcast->setPredefinedKeyValue("alert", $title);
            $customizedcast->setPredefinedKeyValue("badge", 0);
            $customizedcast->setPredefinedKeyValue("sound", "chime");
            foreach ($array as $key => $value) {
                $customizedcast->setCustomizedField($key,              $value);
            }
            // Set 'production_mode' to 'true' if your app is under production mode
            $customizedcast->setPredefinedKeyValue("production_mode", "false");
            $customizedcast->send();
            return 1;
            
        } catch (Exception $e) {
            return 1;   
            //echo $e->getMessage();
        }
    }

    /**
     * 组建要推送的的信息内容
     * create 2015.10.28
     * @return string
    **/
    public function formationPushContent($app) {
        $result = array(
            'pushtype'  => 2, //网关绑定成功
            'gatewayid' => $app->{'gatewayid'},
            'gatewayname'    => '网关',
            'role'   => 1,
            'eventtime' => date('Y-m-d H:i:s', time())
            );
        return $result;
    }

    /**
     * 返回绑定执行结果
     * create 2015.10.13
     * @return $obj.
     */
    public function returnGatewayBind($code, $msg, $result) {
        return $back = $this->backJSON(array(
            'code' => $code,
            'msg' => $msg,
            'result' =>$result
            ));
    }


    /**
     * 推送消息。
     * create 2015.11.03
     * @return $obj.
     */
    public function flagPush($app, $array) {
        if ($app->{'flag'} == 1) {
            $push = $this->pushMsgToApp($app->{'gatewayid'}, 'everyoo', '上报状态信息', '上报信息成功', $array);
            return $this->returnGatewayBind('200', '操作成功', $push);
        } else if ($app->{'flag'} == 0) {
            return $this->returnGatewayBind('200', '操作成功', '1');
        }
        return $this->returnGatewayBind('200', '操作失败', '5');
    }


    /**
     * 组建上报的设备信息
     * create 2015.11.01
     * @return $obj.
     */
    public function formationDeviceStatus($app) {
         $result = array(
            'pushtype'  => 1, //上报信息
            'gatewayid' => $app->{'gatewayid'},
            'nodeid'    => $app->{'nodeid'},
            'contenttype'   => $app->{'contenttype'},
            'cmdcode'   => $app->{'cmdcode'},
            'content'   => $app->{'content'},
            'switch' => $app->{'switch'},
            'eventtime' => $app->{'eventtime'}
            );
         return $result;
    }


    /**
     * 查看是否存在该状态信息
     * create 2015.11.01
     * @return $obj.
     */
    public function selectStatusOfDevice($app) {
        $result  = \App\DeviceOutlet::where('gateway_id', '=', $app->{'gatewayid'})
                  ->where('node_id', $app->{'nodeid'})
                  ->where('status', 1)
                  ->get();
        return count($result, 0) > 0 ? false : true; 
    }


     /**
     * 推送消息到android端.
     * @alias:      推送别名
     * @aliasType:  app类型
     * @title:      推送标题
     * @text:       推送内容
     * @array:      推送数组
     * @return Response
     */
    public function pushMsgToApp($alias, $aliasType, $title, $text, $array) {
        try {
            $this->appkey = env('ANDROIDKEY');
            $this->appMasterSecret = env('ANDROIDAMS');

            $this->timestamp = strval(time());
            $customizedcast = new \AndroidCustomizedcast();
            $customizedcast->setAppMasterSecret($this->appMasterSecret);
            $customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
            $customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            // Set your alias here, and use comma to split them if there are multiple alias.
            // And if you have many alias, you can also upload a file containing these alias, then 
            // use file_id to send customized notification.
            $customizedcast->setPredefinedKeyValue("alias",            $alias);
            // Set your alias_type here
            $customizedcast->setPredefinedKeyValue("alias_type",       $aliasType);
            $customizedcast->setPredefinedKeyValue("ticker",           "Android customizedcast ticker");
            $customizedcast->setPredefinedKeyValue("title",            $title);
            $customizedcast->setPredefinedKeyValue("text",             $text);
            $customizedcast->setPredefinedKeyValue("after_open",       "go_app");
            foreach ($array as $key => $value) {
                $customizedcast->setExtraField($key,              $value);
            }
            $customizedcast->send();
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * 验证网关sn码是否有效
     * create 2015.10.13
     * update 2015.12.28 修改验证表
     * @return $result true or false.
     */
    public function verificationSnCode($snCode, $sipUser, $sipPass) {
        $result = DB::table('subscriber')
            ->leftJoin('user_gateway_bind','user_gateway_bind.sip_user', '=', 'subscriber.username')
            ->where('subscriber.password', '=', $sipPass)
            ->where('user_gateway_bind.gateway_id', '=', $snCode)
            ->where('user_gateway_bind.sip_user', '=', $sipUser)
            ->where('user_gateway_bind.status', '=', 1)
            ->get();
        return count($result, 0) > 0 ? true : false;
    }

     /**
     * 验证网关是否合法
     * create 2015.10.13
     * @return $result true or false.
     */
    public function verificationGateway($sipUser, $sipPass) {
        $result = \App\Subscriber::where('username', '=', $sipUser)
                  ->where('password', $sipPass)
                  ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * 验证sip用户是否合法
     * create 2015.10.29
     * @return $result true or false.
     */
    public function reVerificationReUserSip($app) {
        $list = \App\UserGatewayBind::where('gateway_id', $app->{'gatewayid'})
                ->where('status', 1)
                ->get();
        if (count($list, 0) > 0 && !empty($list[0]->user_id)) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * 验证网关是否合法并执行绑定操作
     * create 2015.10.13
     * @return $result true or false.
     */
    public function verificationReUserSip($uid, $snCode) {

        //2015.10.13 此处考虑 是否存在 role权限为零但已绑定的情况
        // 暂不考虑
        // $sip = \App\UserSip::where('user_id', '=', $uid)->where('sn_code', $snCode)->where('status', 1)->get();
        //执行绑定操作
        $UserGatewayBind = $this->createUserGatewayBind($uid, $snCode);
        $result = \App\UserGatewayBind::where('gateway_id', '=', $snCode)
                      ->where('status', 1)
                      ->get();
        $gatewayUser = $this->createGatewayBindUser($result[0]->id, $uid);
        return $gatewayUser;
    }

    /**
     * 根据绑定关系把数据存放到用户与网关管理表中。
     * create 2015.12.14
     * @return $result true or false.
    **/
    public function createGatewayBindUser($bind, $uid) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $gateway = \App\GatewayUserAccount::create(array(
            'id' => $uuid[0]['uuid'],
            'bind_id' => $bind,
            'user_id' => $uid,
            'init_time' => date('Y-m-d H:i:s', time())
        ));
        return count($gateway, 0);
    }

    /**
     * 验证用户是否有效合法
     * create 2015.10.13
     * @return $result true or false.
     */
    public function verificationUser($uid) {
        $userList = \App\User::find($uid);
        return count($userList, 0) > 0 ? true : false;
    }

    /**
     * 建立网管与用户之间的绑定关系
     * create 2015.10.13
     * @return $result true or false.
    **/
    public function createUserGatewayBind($uid, $snCode) {
        $list = \App\UserGatewayBind::where('gateway_id', '=', $snCode)
                ->where('status', 1)
                ->update(array(
                    'user_id' => $uid,
                    'status' => 1,
                    'init_time' => date('Y-m-d H:i:s', time())
                ));
        return 1;
    }

   

    /**
     * 根据用户获取网关列表.
     * create 2015.10.20
     * $uid 用户id
     * @return list 结果。
     */
    public function userGatewaylist($uid) {
        $result = \App\UserSip::where('user_id', '=', $uid)
                  ->where('status', 1)
                  ->get();
        return $result;
    }

    
    /**
     * 验证token.
     * create 2015.10.20
     * @return Response
     */
    public function verifyToken($uid, $token) {
        $result = false;
        $list = \App\Token::where('user_id', '=', $uid)->where('token', $token)->get();
        if (count($list) > 0) {
            return $result = true;
        }
        return $result;
    }

    /**
     * 删除设备状态信息.
     * create 2015.11.02
     * @return Response
     */
    public function deleteDeviceStatus($app) {
        $result = \App\DeviceFull::where('gateway_id', '=', $app->{'gatewayid'})
                  ->where('node_id', $app->{'nodeid'})
                  ->delete();
        return $result;
    }

    /**
     * function: 生成uuid
     * create 2016.01.18
     * @return mixed
     */
    public function createUuid() {
        $uuid = \DB::SELECT("select uuid() as uuid");
        return $uuid[0]['uuid'];
    }
    /**
     * Api:设备退网(删除设备).
     * create 2015.11.02
     * @return Response
     */
    public function deleteDevice() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证sn码是否有效
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    //验证用户名密码有效性
                    if ($this->verificationGateway($app->{'gatewayid'}, $app->{'password'})) {
                       $result = $this->deleteDeviceStatus($app);
                       return $this->returnGatewayBind('200', '操作成功', '1');
                    }
                }
                //操作失败 网关验证失败
                return $this->returnGatewayBind('200', '操作失败', '3');
            } catch(Exception $e) {
                return $this->returnGatewayBind('500', '操作失败', '2');
            }
        } return $this->returnGatewayBind('500', '操作失败', '4');
    }


    /**
      * Api:同步action信息
      * create 2015.12.22
      * @return Response
     **/
    public function syncAction() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                   $result = $this->formationAction();
                   return $this->returnBackEnd('200', '操作成功', '1', $result);
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '操作失败', '-1', null);
            } catch(Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-2', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-3', null);
    }

    /**
      * function:组建action信息
      * create 2015.12.22
      * @return Response
     **/
    public function formationAction() {
        $result = array();
        $list = \App\DefineAction::orderBy('init_time', 'asc')
                ->where('status', 1)
                ->get();
        for ($i = 0, $y = count($list, 0); $i < $y; $i++) { 
            $result[$i]['id'] = $list[$i]->id;
            $result[$i]['content'] = $list[$i]->content;
            // $result[$i]['enable'] = $list[$i]->enable;
        }
        return $result;
    }

    /**
      * Api:同步device_action信息
      * create 2015.12.22
      * @return Response
     **/
    public function syncDeviceAction() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                   $result = $this->formationDeviceAction($app->{'deviceid'});
                   return $this->returnBackEnd('200', '操作成功', '1', $result);
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '操作失败', '-1', null);
            } catch(Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-2', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-3', null);
    }

    /**
      * function:组建device_action信息
      * create 2015.12.22
      * @return Response
     **/
    public function formationDeviceAction($type) {
        $result = array();
        $list = $this->selectDeviceAction($type);
        for ($i = 0, $y = count($list, 0); $i < $y; $i++) { 
            $result[$i]['deviceid'] = $list[$i]->device_type_id;
            $result[$i]['actionid'] = $list[$i]->action_id;
            // $result[$i]['enable'] = $list[$i]->enable;
        }
        return $result;
    }

    /**
      * function:查找所同步的device_action源
      * create 2015.12.22
      * @return Response
     **/
    public function selectDeviceAction($type) {
        $result = null;
        if ($type == 0) {
            $result = \App\DeviceAction::orderBy('init_time', 'asc')
                      ->where('status', 1)
                      ->get();
        } else {
            $result = \App\DeviceAction::orderBy('init_time', 'asc')
                      ->where('status', 1)
                      ->where('device_type_id', $type)
                      ->get();
        }
        return $result;
    }


    public function getArrayForApp() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {
                  //*测试数据写入文件
                    $file = fopen('../public/uploads/' . 'array.txt',"w");
                    fwrite($file, $app->{'name'}.'-----'.$app->{'list'});//写入
                    fclose($file);//关闭
                   // */

                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '操作成功', '1', null);
            } catch(Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-2', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-3', null);
    }

    /**
     * /gateway/upgrade
     * 网关自动升级
     */
    public function downLoadUpgradeFile(){
        $path = base_path('public');
        $file = "gatewaylitedaemon.apk";
        header("Content-Disposition: attachment; filename=" . urlencode($file));   
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Description: File Transfer");            
        header("Content-Length: " . filesize($file));
        //flush(); // this doesn't really matter.
        $fp = fopen($path . DIRECTORY_SEPARATOR .  $file, "r");
        while (!feof($fp))
        {
            echo fread($fp, 65536);
            flush(); // this is essential for large downloads
        } 
        fclose($fp);
    }
    /**
     * 返回正确执行结果
     * create 2015.10.20
     * @return $obj.
     */
    public function returnBackEnd($code, $msg, $result, $info) {
        return $back = $this->backJSON(array(
            'code' => $code,
            'msg' => $msg,
            'result' => $result,
            'info' => $info
            ));
    }


}
