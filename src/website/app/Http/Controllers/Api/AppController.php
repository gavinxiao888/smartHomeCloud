<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AppController extends Controller {

    public function receiveUserFeedback() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    $result = $this->insertFeedback($app);
                    return $this->returnEndBackG('200', '操作完成', $result, null);
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-4', null);
    }

    /**
     * Function: 记录用户反馈
     * create 2016.03.16
     * @param $app 用户发送的信息
     * @return int 1=操作完成
     */
    public function insertFeedback($app) {
        $user = \App\UserFeedback::create(array(
                    'user_id' => $app->{'userid'},
                    'tel' => $app->{'tel'},
                    'init_time' => $app->{'inittime'}
        ));
        return $this->insertFeedBackContent($user->id, $app->{'list'});
    }

    /**
     * Function: 插入用户反馈内容
     * create 2016.03.16
     * @param $id  用户反馈记录ID
     * @param $list 用户反馈内容数组
     * @return int 1=操作完成
     */
    public function insertFeedBackContent($id, $list) {
        foreach ($list as $feedback) {
            $FeedbackContent = \App\FeedbackContent::create(array(
                        'id' => $this->creteBackUuid(),
                        'feedback_id' => $id,
                        'content' => $feedback->content
            ));
        }
        return 1;
    }

    /**
     * app检测网关在线状态
     * @url /app/gateway/status
     * @param :
     * {
      "sipaccount":"5515856a-65bc-11e5-9aab-005056b543f6"
      }
     * select count(1) from `kamailio`.`location` where username = {sipaccount}
     */
    public function getGatewayStatus() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (empty($app)) {
            return $this->returnEndBackG('200', '操作失败', '0', null);
        }
        // 默认接受到有效信息，无效信息则跑catch
        try {
            $result = \App\Location::where("username", $app->sipaccount)->count();
            if ($result > 0) {
                return $this->returnEndBackG('200', '操作成功', '1', "true");
            } else {
                return $this->returnEndBackG('200', '操作成功', '1', "false");
            }
        } catch (Exception $e) {
            return $this->returnEndBackG('200', '操作失败', '-2', null);
        }
    }

    /**
     *  10.7  APP获取设备版本信息，型号(暂无)，创建时间等信息
     * @url: /app/device/getDeviceVersion
     *  @param: 
     *  {
      "userid":"5515856a-65bc-11e5-9aab-005056b543f6",
      "accesstoken":"_f80d5a11ff537e7c9f6123c760240513a698589f00"，
      "gatewayid":"newGateWay",
      "deviceid":"86"
      }
     * @return json 
     */
    public function getDeviceVersion() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (empty($app)) {
            return $this->returnEndBackG('200', '操作失败', '0', null);
        }
        // 默认接受到有效信息，无效信息则跑catch
        try {
            //验证用户有效性
            if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                $bindid = $this->selectUserGatewayBind($app->gatewayid);
                if (is_null($bindid)) {
                    return $this->returnEndBackG('200', '操作成功', '-4', null);
                }
                $result = $this->getDeviceVersionInfo($bindid, $app->deviceid);
                if (count($result, 0) > 0) {
                    return $this->returnEndBackG('200', '操作成功', '1', array(
                                'version' => $result[0]->version,
                                'init_time' => $result[0]->init_time
                    )); //$result);
                } else {
                    return $this->returnEndBackG('200', '操作成功', '-3', null);
                }
            } else {
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            }
        } catch (Exception $e) {
            return $this->returnEndBackG('200', '操作失败', '-2', null);
        }
    }

    private function getDeviceVersionInfo($bindid, $deviceid) {
        $list = \App\DeviceManage::where("bind_id", $bindid)
                ->where("device_id", $deviceid)
                ->where("status", 1)
//                ->field("version","init_time")
//                ->first();
                ->get();
        return $list;
    }

    /**
     *  Api:用户根据网关id按设备操控性分类获取设备列表
     *  condition = 条件分类：1条件,2不可做条件
     *  or
     *  controll = 控制分类: 1可控制，2不可控制
     *  or
     *  feature = 1灯光,2传感器,3窗帘,4插座 
     *  2016.03.08
     * {"gatewayid":"EVERYOO_00007","userid":"99048c2a-9116-11e5-83d3-005056b543f6","condition":"1","accesstoken":"_0263256aa66dc24ca5ef1c8a704e849fb1bdd2f506"}
     * {"gatewayid":"EVERYOO_00007","userid":"99048c2a-9116-11e5-83d3-005056b543f6","feature":"3","accesstoken":"_0263256aa66dc24ca5ef1c8a704e849fb1bdd2f506"}
     * @return Response
     * \"info\":[{\"deviceid\":\"83\",\"name\":\"客厅\",\"location\":\"客厅\",\"devicetype\":\"1\"}]
     */
    public function deviceListByCondition() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (empty($app)) {
            return $this->returnEndBackG('200', '操作失败', '0', null);
        }
        $app->devicetype = 0; //查询全部设备
        // 默认接受到有效信息，无效信息则跑catch
        try {
            //验证用户有效性
            if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                $result = $this->filterDeviceType($this->formationDevicelistArray($app), $app);
                if (count($result, 0) > 0) {
                    return $this->returnEndBackG('200', '操作成功', '1', $result);
                } else {
                    return $this->returnEndBackG('200', '操作成功', '-3', null);
                }
            } else {
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            }
        } catch (Exception $e) {
            return $this->returnEndBackG('200', '操作失败', '-2', null);
        }
    }

    /**
     * [{\"deviceid\":\"83\",\"name\":\"客厅\",\"location\":\"客厅\",\"devicetype\":\"1\"}]
     * 
     * @param type $result
     * @param type $app
     * @return type
     */
    private function filterDeviceType($result, $app) {

        if (array_key_exists("condition", $app)) {
            $list = \App\DeviceType::where("type_condition", $app->condition)->where("status", 1)->get();
        } else if (array_key_exists("feature", $app)) {
            $list = \App\DeviceType::where("type_feature", $app->feature)->where("status", 1)->get();
        } else if (array_key_exists("controll", $app)) {
            $list = \App\DeviceType::where("type_controll", $app->controll)->where("status", 1)->get();
        } else {
            return $result;
        }
        if (count($list, 0) <= 0) {
            return null;
        }
        $allDeviceType = [];
        foreach ($list as $row) {
            array_push($allDeviceType, $row['id']);
        }
        $return = [];
        foreach ($result as $key => $val) {
            if (in_array($val['devicetype'], $allDeviceType)) {
                //unset($result[$key]);
//                array_push($return, $val['devicetype']);
                array_push($return, $val);
            }
        }
        return $return;
    }

    /**
     * POST: 查询联动列表
     * create 2016.01.18
     * @return string
     */
    public function searchDefineLinkAgeList() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    if ($this->verificationUserRole($app->{'userid'}, $app->{'gatewayid'})) {
                        $bind = $this->selectUserGatewayBind($app->{'gatewayid'});
                        //组建联动列表
                        $result = $this->buildDefineLinkAgeList($bind);
                        if (count($result, 0) > 0) {
                            //操作成功
                            return $this->returnEndBackG('200', '操作成功', '1', $result);
                        } else {
                            //操作成功，但无联动信息
                            return $this->returnEndBackG('200', '没有数据', '0', null);
                        }
                    } else {
                        //用户无权限
                        return $this->returnEndBackG('200', '操作成功', '-3', null);
                    }
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-4', null);
    }

    /**
     * POST: 查询联动详细
     * create 2016.01.18
     * @return string
     */
    public function searchLinkAgeInfo() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    if ($this->verificationUserRole($app->{'userid'}, $app->{'gatewayid'})) {
                        $bind = $this->selectUserGatewayBind($app->{'gatewayid'});
                        //组建联动列表
                        $result = $this->buildDefineLinkInfo($bind, $app->{'linkageid'}, $app->{'triggered'});
                        if (count($result[0], 0) > 0) {
                            //操作成功
                            return $this->returnBackEndForElse('200', '操作成功', '1', 'linkagelist', $result[0], 'triggeredlist', $result[1]);
                        } else {
                            //操作成功，但无联动信息
                            return $this->returnBackEndForElse('200', '没有数据', '-1', 'linkagelist', null, 'triggeredlist', null);
                        }
                    } else {
                        //用户无权限
                        return $this->returnEndBackG('200', '操作成功', '-3', null);
                    }
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-4', null);
    }

    /**
     * function: 组建联动列表
     * create 2016.01.18
     * @param $bind 绑定关系ID
     * @return array 组建好的数组
     */
    public function buildDefineLinkAgeList($bind) {
        $result = array();
        $list = $this->selectDefineLinkAge($bind);
        for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
            $result[$i]['linkageid'] = $list[$i]->link_age_id;
            $result[$i]['linkagename'] = $list[$i]->link_age_name;
            $result[$i]['length'] = $list[$i]->length;
            $result[$i]['begin'] = $list[$i]->begin;
            $result[$i]['end'] = $list[$i]->end;
            $result[$i]['triggered'] = $list[$i]->triggered;
            $result[$i]['createtime'] = $list[$i]->create_time;
            $result[$i]['enable'] = $list[$i]->enable;
        }
        return $result;
    }

    /**
     * function: 组建联动详情信息
     * create 2016.01.18
     * @param $bind 绑定关系ID
     * @param $linkAge 联动ID
     * @param $triggered 联动触发器ID
     * @return array
     */
    public function buildDefineLinkInfo($bind, $linkAge, $triggered) {
        $linkResult = array();
        $triggeredResult = array();
        // 获取联动映射信息
        $triggeredList = $this->selectTriggered($bind, $triggered);

        // 获取联动触发器详情
        $linkAgeList = $this->selectDefineLinkInfo($bind, $linkAge);
        for ($i = 0, $y = count($linkAgeList, 0); $i < $y; $i++) {
            $linkResult[$i]['ctrlid'] = $linkAgeList[$i]['ctrl_id'];
            $linkResult[$i]['value'] = $linkAgeList[$i]['value'];
            $linkResult[$i]['nickname'] = $this->robotInfoSearchDeviceNickname($bind, $linkAgeList[$i]['ctrl_id']);
            $linkResult[$i]['position'] = $linkAgeList[$i]['position'];
            $linkResult[$i]['enable'] = $linkAgeList[$i]['enable'];
        }
        for ($i = 0, $y = count($triggeredList, 0); $i < $y; $i++) {
            $triggeredResult[$i]['triggeredid'] = $triggeredList[$i]->triggered_id;
            $triggeredResult[$i]['ctrlid'] = $triggeredList[$i]->ctrl_id;
            $triggeredResult[$i]['nickname'] = $this->robotInfoSearchDeviceNickname($bind, $triggeredList[$i]['ctrl_id']);
            $triggeredResult[$i]['value'] = $triggeredList[$i]->value;
            $triggeredResult[$i]['rule'] = $triggeredList[$i]->rule;
            $triggeredResult[$i]['enable'] = $triggeredList[$i]->enable;
        }
        return array(
            '0' => $linkResult,
            '1' => $triggeredResult
        );
    }

    /**
     * function: 获取联动列表
     * create 2016.01.18
     * @param $bind 绑定关系ID
     * @return mixed
     */
    public function selectDefineLinkAge($bind) {
        $result = \App\DefineLinkAge::orderBy('init_time', 'desc')
                ->where('bind_id', $bind)
                ->where('status', 1)
                ->get();
        return $result;
    }

    /**
     * function: 获取联动详细
     * create 2016.01.18
     * @param $bind 绑定关系ID
     * @param $linkAge 联动ID
     * @return mixed
     */
    public function selectDefineLinkInfo($bind, $linkAge) {
        $result = DB::table('link_age_ctrl')
                ->leftJoin('define_link_age', 'define_link_age.id', '=', 'link_age_ctrl.link_age_id')
                ->where('define_link_age.link_age_id', '=', $linkAge)
                ->where('define_link_age.bind_id', '=', $bind)
                ->where('link_age_ctrl.status', '=', 1)
                ->where('define_link_age.status', '=', 1)
                ->get();
        return $result;
    }

    /**
     * function: 查询联动触发条件详细
     * @param $bind 绑定关系ID
     * @param $triggered 触发ID
     * @return mixed
     */
    public function selectTriggered($bind, $triggered) {
        $triggeredArray = $this->splitTriggered($triggered);
        $result = \App\DefineTriggered::orderBy('init_time', 'desc')
//            ->whereIn('triggered_id', $triggeredArray)
                ->where('triggered_id', $triggered)
                ->where('bind_id', $bind)
                ->where('status', 1)
                ->get();
//        dd($triggered);
        return $result;
    }

    /**
     * function: 拆分触发条件ID
     * create 2016.01.18
     * @param $triggered 触发条件ID
     * @return array 拆分后的数组
     */
    public function splitTriggered($triggered) {
        return explode(',', $triggered);
    }

    /**
     * 查看场景详细
     * create 2016.01.10
     * @return string
     */
    public function searchRobotInfo() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    if ($this->verificationUserRole($app->{'userid'}, $app->{'gatewayid'})) {
                        //获取bind_id
                        $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
                        //组建场景详细
                        $result = $this->formationRobotInfo($bindId, $app->{'robotid'});
                        if (empty($result)) {
                            return $this->returnEndBackG('200', '操作成功', '0', null);
                        } else {
                            return $this->returnEndBackG('200', '操作成功', '1', $result);
                        }
                    } else {
                        //用户无权限
                        return $this->returnEndBackG('200', '操作成功', '-3', null);
                    }
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-4', null);
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
        $deviceList = $this->selectDeviceListFromBind($bind);
        if (!empty($list)) {
            $existDeviceid = [];
            for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
                $result[$i]['robotid'] = $list[$i]['robot_id'];
                $result[$i]['join'] = 1;
//                $result[$i]['deviceid'] = $list[$i]['device_id'];
                $result[$i]['deviceid'] = $this->selectDeviceIdFromBindAndCtrl($list[$i]['bind_id'], $list[$i]['ctrl_id']);
                $result[$i]['devicetype'] = $this->selectDeviceTypeFromDeviceID($list[$i]['bind_id'], $result[$i]['deviceid']);
                $result[$i]['ctrlid'] = $list[$i]['ctrl_id'];
                $result[$i]['value'] = $list[$i]['value'];
                $result[$i]['nickname'] = $this->robotInfoSearchDeviceNickname($bind, $list[$i]['ctrl_id']);
                $result[$i]['position'] = $list[$i]['position'];
                $result[$i]['enable'] = $list[$i]['enable'];
                array_push($existDeviceid, $result[$i]['deviceid']);
            }
            for ($i = 0, $y = count($deviceList, 0); $i < $y; $i++) {
                if (!in_array($deviceList[$i]['device_id'], $existDeviceid) && $deviceList[$i]['type_controll'] == 1) {
                    $z = count($result, 0);
                    $result[$z]['join'] = 0;
                    $result[$z]['nickname'] = $deviceList[$i]['nickname'];
                    $result[$z]['deviceid'] = $deviceList[$i]['device_id'];
                    $result[$z]['devicetype'] = $deviceList[$i]['device_type'];
                }
            }
             return $result;
        }
        //默认场景无设备时
        for ($i = 0, $y = count($deviceList, 0); $i < $y; $i++) {
            if ($deviceList[$i]['type_controll'] == 1){
                $z = count($result, 0);
                $result[$z]['join'] = 0;
                $result[$z]['nickname'] = $deviceList[$i]['nickname'];
                $result[$z]['deviceid'] = $deviceList[$i]['device_id'];
                $result[$z]['devicetype'] = $deviceList[$i]['device_type'];
            }
        }
        return $result;
    }

    /**
     * Function: 获取该绑定关系下的所有设备。
     * @param $bind 绑定关系ID
     * @return mixed
     * @update add type_controll
     */
    public function selectDeviceListFromBind($bind) {
//        return \App\DeviceManage::where('bind_id', '=', $bind)
//                        ->where('status', 1)
//                        ->get();
           return \DB::table("device_manage")
                   ->Join("device_type","device_manage.device_type","=","device_type.id")
                   ->where("device_manage.bind_id",$bind)
                   ->where("device_manage.status",1)
                   ->select("device_manage.id","device_manage.bind_id","device_manage.device_type","device_manage.device_id","device_manage.nickname","device_manage.location","device_manage.version","device_manage.init_time","device_manage.update_time","device_manage.status","device_manage.remark","device_type.type_controll")
                   ->get();
                   
    }

    /**
     * Function: 根据Ctrl指令ID获取该设备ID
     * Create: 2016/03/04
     * @param $bind 绑定关系ID
     * @param $ctrl Ctrl指令ID
     * @return null
     */
    public function selectDeviceIdFromBindAndCtrl($bind, $ctrl) {
        $result = \App\DefineCtrl::where('bind_id', '=', $bind)
                ->where('ctrl_id', $ctrl)
                ->where('status', 1)
                ->get();
        return count($result, 0) > 0 ? $result[0]->device_id : null;
    }

    /**
     * Function: 根据设备ID查看该设备类型
     * Create: 2016/03/04
     * @param $bind 绑定关系ID
     * @param $device 设备ID
     * @return null
     */
    public function selectDeviceTypeFromDeviceID($bind, $device) {
        $result = \App\DeviceManage::where('bind_id', '=', $bind)
                ->where('device_id', $device)
                ->where('status', 1)
                ->get();
        return count($result, 0) > 0 ? $result[0]->device_type : null;
    }

    /**
     * function: 根据网关信息和ctrl指令信息获取该设备昵称
     * @param $bind 绑定ID
     * @param $ctrl 指令ID
     * @return string 设备名称
     */
    public function robotInfoSearchDeviceNickname($bind, $ctrl) {
        $result = DB::table('device_manage')
                ->Join('define_ctrl', 'define_ctrl.device_id', '=', 'device_manage.device_id')
                ->where('define_ctrl.ctrl_id', '=', $ctrl)
                ->where('define_ctrl.bind_id', '=', $bind)
                ->where('device_manage.bind_id', '=', $bind)
                ->where('device_manage.status', '=', 1)
                ->where('define_ctrl.status', '=', 1)
                ->get();
        return count($result, 0) > 0 && !empty($result[0]['nickname']) ? $result[0]['nickname'] : '设备';
    }

   
    /**
     * "init":"1" 只取默认创建的5个场景
     * POST: 获取场景列表
     * create 2016.01.10
     * @return string
     */
    public function searchRobot() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        $init = "";
        if (array_key_exists("init", $app) && $app->init == "1") {
            $init = "1";
        }
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    if ($this->verificationUserRole($app->{'userid'}, $app->{'gatewayid'})) {
                        //获取bind_id
                        $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
                        //组建场景列表
                        $result = $this->formationRobotList($bindId, $init);
                        if (empty($result)) {
                            return $this->returnEndBackG('200', '操作成功', '0', null);
                        } else {
                            return $this->returnEndBackG('200', '操作成功', '1', $result);
                        }
                    } else {
                        //用户无权限
                        return $this->returnEndBackG('200', '操作成功', '-3', null);
                    }
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-4', null);
    }

    /**
     * function: 组建场景列表
     * create 2016.01.10
     * @param $bind
     * @return null
     */
    public function formationRobotList($bind, $init = "") {
        $list = $this->selectRobot($bind, $init);
        $result = null;
        if (!empty($list)) {
            for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
                $result[$i]['robotid'] = $list[$i]->robot_id;
                $result[$i]['robotname'] = $list[$i]->robot_name;
                $result[$i]['length'] = $list[$i]->length;
                $result[$i]['begin'] = $list[$i]->begin;
                $result[$i]['end'] = $list[$i]->end;
                $result[$i]['createtime'] = $list[$i]->create_time;
                $user = $this->selecUserInfo($list[$i]->charge_id);
                $result[$i]['user'] = count($user, 0) > 0 ? $user->nickname : null;
                $result[$i]['enable'] = $list[$i]->enable;
                $result[$i]['is_init'] = $list[$i]->is_init; //大于1值为默认场景不可删除
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
    public function selectRobot($bind, $init="") {
        if ("" == $init) {
            $result = \App\DefineRobot::where('bind_id', '=', $bind)
                    ->where('status', 1)
                    ->get();
        } else {
            $result = \App\DefineRobot::where('bind_id', '=', $bind)
                    ->where('status', 1)
                    ->where("is_init", ">", "0")
                    ->orderBy("is_init","asc")
                    ->get();
        }
        return $result;
    }

    /**
     * POST:获取定时列表
     * create.2016/01/07
     * @return string
     */
    public function serachDefineTimer() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    if ($this->verificationUserRole($app->{'userid'}, $app->{'gatewayid'})) {
//                       if ($this->verificationReDefineTimer($app)) {
//                           //该时间点该设备已存在操作
//                           return $this->returnEndBackG('200', '操作成功', '-3', null);
//                       } else {
                        //执行新建操作
                        $result = $this->formationTimer($app);
                        if (!empty($result)) {
                            return $this->returnEndBackG('200', '操作成功', '1', $result);
                        } else {
                            return $this->returnEndBackG('200', '操作成功', '0', $result);
                        }
//                       }
                    } else {
                        //用户无权限
                        return $this->returnEndBackG('200', '操作成功', '-3', null);
                    }
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-4', null);
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
            $result[$i]['loop'] = explode(",", str_replace(["[", "]", " ", "\""], "", $list[$i]['loop']));
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
        $result = DB::table('define_timer')
                ->Join('user_gateway_bind', 'user_gateway_bind.id', '=', 'define_timer.bind_id')
                ->orderBy('define_timer.create_time', 'asc')
                ->where('user_gateway_bind.gateway_id', '=', $gateway)
                ->where('define_timer.device_id', '=', $device)
                ->where('define_timer.status', '=', 1)
                ->get();
        return count($result, 0) > 0 ? $result : null;
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
     * 验证同一时间是否存在重复行为
     * create.2016/01/07
     * @param $app 手机app传送的信息
     * @return bool 存在与否
     */
    public function verificationReDefineTimer($app) {
        $result = DB::table('define_timer')
                ->Join('user_gateway_bind', 'user_gateway_bind.id', '=', 'define_timer.bind_id')
                ->where('user_gateway_bind.gateway_id', '=', $app->{'gatewayid'})
                ->where('define_timer.device_id', '=', $app->{'deviceid'})
                ->where('define_timer.alarm_time', '=', $app->{'alramtime'})
                ->where('define_timer.create_time', '=', $app->{'createtime'})
                ->where('define_timer.status', '=', 1)
                ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * 获取最新的ctrl指令
     * create.2016/01/05
     * @return string
     */
    public function getGatewayCtrl() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    if ($this->verificationUserRole($app->{'userid'}, $app->{'gatewayid'})) {
                        $result = $this->selectCtrl($app->{'gatewayid'});
//                      return $result;
                        if (!empty($result)) {
                            //操作成功
                            return $this->returnEndBackG('200', $result[0]['update_time'], '1', $this->formationCtrl($result));
                        } else {
                            //无数据
                            return $this->returnEndBackG('200', '操作成功', '0', null);
                        }
                    } else {
                        //用户无权限
                        return $this->returnEndBackG('200', '操作成功', '-3', null);
                    }
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-4', null);
    }

    /**
     * function:验证用户是否有权限 访问网关信息
     * create: 2016/01/04
     * @param $user 用户id
     * @param $gateway 网关id
     * @return bool 是否有权
     */
    public function verificationUserRole($user, $gateway) {
        $result = DB::table('gateway_user_account')
                ->Join('user_gateway_bind', 'user_gateway_bind.id', '=', 'gateway_user_account.bind_id')
                ->where('user_gateway_bind.gateway_id', '=', $gateway)
                ->where('gateway_user_account.user_id', '=', $user)
                ->where('gateway_user_account.status', '=', 1)
                ->where('user_gateway_bind.status', '=', 1)
                ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * function:组合ctrl指令
     * create: 2016/01/04
     * @param $list 获取到的指令集合
     * @return array 组合完成的结果
     */
    public function formationCtrl($list) {
        $result = array();
        for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
            $result[$i]['ctrlid'] = $list[$i]['ctrl_id'];
            $result[$i]['deviceid'] = $list[$i]['device_id'];
            $result[$i]['actionid'] = $list[$i]['action_id'];
            $result[$i]['value'] = $list[$i]['value'];
            $result[$i]['inittime'] = $list[$i]['update_time'];
            $result[$i]['enable'] = $list[$i]['enable'];
        }
        return $result;
    }

    /**
     * function:获取ctrl指令集
     * create: 2016/01/04
     * @param $gateway 网关id
     * @return null
     */
    public function selectCtrl($gateway) {
        $result = DB::table('define_ctrl')->select("define_ctrl.*")
                ->Join('user_gateway_bind', 'user_gateway_bind.id', '=', 'define_ctrl.bind_id')
                ->Join('device_manage', 'device_manage.device_id', '=', 'define_ctrl.device_id')
                ->where('user_gateway_bind.gateway_id', '=', $gateway)
                ->where('user_gateway_bind.status', '=', 1)
                ->where('device_manage.status', '=', 1)
                ->orderBy('define_ctrl.update_time', 'desc')
                ->get();
        return count($result, 0) > 0 ? $result : null;
    }

    /**
     * 1、获取设备详情
     * create. 2015.10.25
     * @return Response
     */
    public function deviceDetail() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    $device = $this->selectDeviceDetail($app);
                    if (count($device, 0) > 0) {
                        return $this->returnEndBackG('200', '操作成功', '1', $device);
                    }
                    return $this->returnEndBackG('200', '操作成功', '-3', null);
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '0', null);
    }

    /**
     * 2、 查询设备详情信息.
     * create. 2015.10.25
     * @return Response
     */
    public function selectDeviceDetail($app) {
        $result = array();
        $bindInfo = $this->selectAppGatewayInfo($app);
        if (!empty($bindInfo)) {
            $device = \App\DefineCtrl::where('bind_id', $bindInfo->id)
                    ->where('device_id', $app->{'deviceid'})
                    ->where('enable', 1)
                    ->get();
            if (count($device, 0) > 0) {
                for ($i = 0, $y = count($device, 0); $i < $y; $i++) {
                    $result[$i] = array(
                        'ctrlid' => $device[$i]->ctrl_id,
                        'value' => $device[$i]->value
                    );
                }
            }
        } return $result;
    }

    /**
     * 3、 用户获取网关列表.
     *  2015.10.28
     * @return Response
     */
    public function userGatewayList() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    $result = $this->formationGatewaylistArray($app->{'userid'});
                    if (count($result, 0) > 0) {
                        return $this->returnEndBackG('200', '操作成功', '1', $result);
                    }
                } else {
                    return $this->returnEndBackG('200', 'token验证失败', '-1', null);
                }
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
            return $this->returnEndBackG('200', '操作成功', '-3', null);
        }
        return $this->returnEndBackG('200', '操作失败', '0', null);
    }

    /**
     * 4、 组建要返回的网关设备的列表.
     *  2015.10.28
     * @return Response
     */
    public function formationGatewaylistArray($user) {
        $result = array();
        $gatewayList = $this->getGatewaylistArray($user);
        for ($i = 0, $y = count($gatewayList, 0); $i < $y; $i++) {
            $bindInfo = $this->selectGatewayBindInfo($gatewayList[$i]['bind_id']);
            if (!empty($bindInfo)) {
                $result[$i]['gatewayid'] = $bindInfo['gateway_id'];
                $result[$i]['sipaccount'] = $bindInfo['sip_user'];
                $result[$i]['gatewayname'] = $bindInfo['nickname'];
                $result[$i]['online'] = $this->searchGatewayOnlineStatus($bindInfo['sip_user']);
            } else {
                $result[$i]['gatewayid'] = null;
                $result[$i]['sipaccount'] = $bindInfo['gateway_id'];
                $result[$i]['gatewayname'] = null;
                $result[$i]['online'] = null;
            }
            $result[$i]['role'] = $gatewayList[$i]['role'];
        }
        return $result;
    }

    /**
     * function: 查询网关是否在线
     * @param $gateway 网关当前登陆的sip账号
     * @return int  返回结果。
     */
    public function searchGatewayOnlineStatus($gateway) {
        $result = \App\Location::where('username', '=', $gateway)
                ->exists();
        return $result ? 1 : 0;
    }

    /**
     * function:查询网关绑定信息.
     *  2015.10.28
     * @return Response
     */
    public function selectGatewayBindInfo($id) {
        $result = \App\UserGatewayBind::find($id);
        return count($result, 0) > 0 ? $result : null;
    }

    /**
     * 6、 获取网关列表.
     *  2015.10.28
     * @return Response
     */
    public function getGatewaylistArray($user) {
        $result = DB::table('gateway_user_account')
                ->leftJoin('user_gateway_bind', 'gateway_user_account.bind_id', '=', 'user_gateway_bind.id')
                ->where('user_gateway_bind.status', '=', 1)
                ->where('gateway_user_account.status', '=', 1)
                ->where('gateway_user_account.user_id', '=', $user)
                ->get();
//        $result = \App\GatewayUserAccount::where('user_id', '=', $user)
//                       ->where('status', 1)
//                       ->get();
        return $result;
    }

    /**
     * 7、 组建设备列表数组.
     *  2015.10.28
     * @return Response
     */
    public function formationDevicelistArray($app) {
        $result = array();
        $gatewayInfo = $this->selectAppGatewayInfo($app);
        if (!empty($gatewayInfo)) {
            $device = $this->getDevicelistArray($gatewayInfo->id, $app->{'devicetype'});
            for ($i = 0, $y = count($device, 0); $i < $y; $i++) {
                $result[$i]['deviceid'] = $device[$i]->device_id;
                $result[$i]['name'] = $this->selectDeviceProfile($device[$i]->nickname, '设备');
                $result[$i]['location'] = $this->selectDeviceProfile($device[$i]->location, '位置');
                $result[$i]['devicetype'] = $device[$i]->device_type;
                $result[$i]['actionid'] = $this->findDeviceValueAction($device[$i]->device_type);
                if (!is_null($result[$i]['actionid'])) {
                    $result[$i]['value'] = $this->findDeviceValueNow($device[$i]->bind_id, $result[$i]['deviceid'], $result[$i]['actionid']);
                } else {
                    $result[$i]['value'] = null;
                }
                // $result[$i]['specific'] = $device[$i]->specific;
            }
        }

        return $result;
    }

    /**
     * Function: 查看网关当前状态
     * create 2016.03.16
     * @param $bind 网关绑定ID
     * @param $device 设备ID
     * @param $action 状态指令集ID
     * @return null 返回结果
     */
    public function findDeviceValueNow($bind, $device, $action) {
        $result = \App\DefineCtrl::where('bind_id', $bind)
                ->where('device_id', $device)
                ->where('action_id', $action)
                ->where('status', 1)
                ->get();
        return count($result, 0) > 0 ? $result[0]->value : null;
    }

    /**
     * function: 根据设备类型获取设备需要显示的action属性
     * create 2016.03.16
     * @param $type
     * @return string
     * todo getdatafrom database
     */
    public function findDeviceValueAction($type) {
        if ($type == 1 || $type == 3 || $type == 14 || $type == 15 || $type == 16) {
            return 'c30da48a-a7b2-11e5-b360-b8975ab8b52a';
        } else if ($type == 2 || $type == 5 || $type == 6 || $type == 7) {
            return '8321dc5c-a7b3-11e5-b360-b8975ab8b52a';
        } else if ($type == 8) {
            return 'c0d677e8-a7b3-11e5-b360-b8975ab8b52a';
        } else if ($type == 9) {
            return 'b6899590-a7b3-11e5-b360-b8975ab8b52a';
        } else if ($type == 10) {
            return 'c7bc1ed2-a7b3-11e5-b360-b8975ab8b52a';
        } else if ($type == 11) {
            return '97e72359-a7b2-11e5-b360-b8975ab8b52a';
        } else if ($type == 12) {
            return '97e72359-a7b2-11e5-b360-b8975ab8b52a';
        } else if ($type == 18) {
            return 'b1e9dd4f-a7b3-11e5-b360-b8975ab8b52a';
        } else if ($type == 20) {
            return 'c30da48a-a7b2-11e5-b360-b8975ab8b52a';
        } else {
            return null;
        }
    }

    /**
     * 8、 判断昵称等内容是否为空.
     *  2015.10.28
     * @return Response
     */
    public function selectDeviceProfile($value, $remark) {
        $result = $remark;
        if (!empty($value)) {
            $result = $value;
        }
        return $result;
    }

    /**
     * 9、 Api:用户根据网关id获取终端设备列表.
     *  2015.10.28
     * @return Response
     */
    public function deviceListArray() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    $result = $this->formationDevicelistArray($app);
                    if (count($result, 0) > 0) {
                        return $this->returnEndBackG('200', '操作成功', '1', $result);
                    }
                } else {
                    return $this->returnEndBackG('200', 'token验证失败', '-1', null);
                }
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
            return $this->returnEndBackG('200', '操作成功', '-3', null);
        }
        return $this->returnEndBackG('200', '操作失败', '0', null);
    }

    /**
     * 10、 终端列表.
     *  2015.10.28
     * @return Response
     */
    public function getDevicelistArray($gateway, $type) {
        $result = null;
        if ($type == 0) {
            $result = \App\DeviceManage::where('bind_id', '=', $gateway)
                    ->where('status', 1)
                    ->get();
        } else {
            $result = \App\DeviceManage::where('bind_id', '=', $gateway)
                    ->where('device_type', $type)
                    ->where('status', 1)
                    ->get();
        }
        return $result;
    }

    /**
     * 11、 Api:修改设备名称.
     *  2015.10.29
     * @return Response
     */
    public function deviceName() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    $result = $this->updateDeviceName($app);
                    if (count($result, 0) > 0) {
                        return $this->returnEndBack('200', '操作成功', 'result', $result);
                    }
                } else {
                    return $this->returnEndBack('200', 'token验证失败', 'result', '-1');
                }
            } catch (Exception $e) {
                return $this->returnEndBack('200', '操作失败', 'result', '-2');
            }
            return $this->returnEndBack('200', '操作成功', 'result', '-3');
        }
        return $this->returnEndBack('200', '操作失败', 'result', '0');
    }

    /**
     * 12、 执行修改设备名称操作.
     *  2015.10.29
     * @return Response
     */
    public function updateDeviceName($app) {
        $bindInfo = $this->selectAppGatewayInfo($app);
        if (!empty($bindInfo)) {
            $result = \App\DeviceManage::where('bind_id', '=', $bindInfo->id)
                    ->where('device_id', $app->{'deviceid'})
                    ->where('status', 1)
                    ->update(array(
                'location' => $app->{'location'},
                'nickname' => $app->{'nickname'}
            ));
            return $result;
        } else {
            return 0;
        }
    }

    /**
     * 13、 Api:App获取设备日志.
     * create 2015.10.31
     * @return Response
     */
    public function getAppLog() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    $result = $this->formationAppLog($app);
                    if (count($result, 0) > 0) {
                        return $this->returnEndBackG('200', '操作成功', '1', $result);
                    }
                } else {
                    return $this->returnEndBackG('200', 'token验证失败', '-1', null);
                }
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
            return $this->returnEndBackG('200', '操作成功', '-3', null);
        }
        return $this->returnEndBackG('200', '操作失败', '0', null);
    }

    /**
     * 14、 根据设备id获取设备日志.
     * create 2015.10.31
     * @return Response
     */
    public function SelectAppLog($app) {
        $bindId = $this->selectGatewayBindForId($app);
        $result = null;
        if (!empty($bindId)) {
            $result = \App\DeviceLog::orderBy('event_time', 'desc')
                    ->where('bind_id', $bindId)
                    ->where('device_id', $app->{'deviceid'})
                    ->take($app->{'count'})
                    ->get();
        }
        return $result;
    }

    /**
     * 15、 根据日志组建返回信息.
     * create 2015.10.31
     * @return Response
     */
    public function formationAppLog($app) {
        $result = array();
        $device = $this->SelectAppLog($app);
        for ($i = 0, $y = count($device, 0); $i < $y; $i++) {
            $result[$i]['eventtime'] = $device[$i]->event_time;
            $result[$i]['userid'] = $device[$i]->user_id;
            $result[$i]['ctrlid'] = $device[$i]->ctrl_id;
            $result[$i]['value'] = $device[$i]->value;
            $result[$i]['deviceid'] = $device[$i]->device_id;
        }
        return $result;
    }

    /**
     * 16、 根据设备id获取指定日期之前的日志.
     * create 2015.10.31
     * @return Response
     */
    public function selectAppLogBeTime($app) {
        $bindId = $this->selectGatewayBindForId($app);
        $result = null;
        if (!empty($bindId)) {
            $result = \App\DeviceLog::where('event_time', '<', $app->{'eventtime'})
                    ->orderBy('event_time', 'desc')
                    ->where('bind_id', $bindId)
                    ->where('device_id', $app->{'deviceid'})
                    ->take($app->{'count'})
                    ->get();
        }
        return $result;
    }

    /**
     * 17、 根据日志组建返回指定日期之前的几条信息.
     * create 2015.10.31
     * @return Response
     */
    public function formationAppLogBeTime($app) {
        $result = array();
        $device = $this->selectAppLogBeTime($app);
        for ($i = 0, $y = count($device, 0); $i < $y; $i++) {
            $result[$i]['eventtime'] = $device[$i]->event_time;
            $result[$i]['userid'] = $device[$i]->user_id;
            $result[$i]['deviceid'] = $device[$i]->device_id;
            $result[$i]['ctrlid'] = $device[$i]->ctrl_id;
            $result[$i]['value'] = $device[$i]->value;
        }
        return $result;
    }

    /**
     * 18、 Api:App上拉加载以前的日志.
     * create 2015.10.31
     * @return Response
     */
    public function getAppLogBeTime() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    $result = $this->formationAppLogBeTime($app);
                    if (count($result, 0) > 0) {
                        return $this->returnEndBackG('200', '操作成功', '1', $result);
                    }
                } else {
                    return $this->returnEndBackG('200', 'token验证失败', '-1', null);
                }
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
            return $this->returnEndBackG('200', '操作成功', '-3', null);
        }
        return $this->returnEndBackG('200', '操作失败', '-4', null);
    }

    public function selectDeviceStatusAsSwitch($app) {
        $result = \App\DeviceOutlet::where('gateway_id', '=', $app->{'gatewayid'})
                ->where('device_id', $app->{'deviceid'})
                ->where('status', 1)
                ->get();
        return $result;
    }

    /**
     * 19.查询绑定网关状态.
     *  2015.11.23
     * @return Response
     */
    public function appGatewayStatus() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    $result = $this->selectAppGatewayStatus($app);
                    return $this->returnEndBack('200', '操作成功', 'result', $result);
                } else {
                    return $this->returnEndBack('200', 'token验证失败', 'result', '-1');
                }
            } catch (Exception $e) {
                return $this->returnEndBack('200', '操作失败', 'result', '-2');
            }
        }
        return $this->returnEndBack('200', '操作失败', 'result', '-3');
    }

    /**
     * 20.判断是否绑定该网关.
     *  2015.11.23
     * @return Response
     */
    public function selectAppGatewayStatus($app) {
        $result = DB::table('gateway_user_account')
                ->leftJoin('user_gateway_bind', 'user_gateway_bind.id', '=', 'gateway_user_account.bind_id')
                ->where('gateway_user_account.user_id', '=', $app->userid)
                ->where('user_gateway_bind.gateway_id', '=', $app->gatewayid)
                ->where('user_gateway_bind.status', '=', 1)
                ->where('gateway_user_account.status', '=', 1)
                ->get();
        return count($result, 0) > 0 ? 1 : 0;
    }

    /**
     * 21.获取网关信息.
     *  2015.11.25
     * @return Response
     */
    public function searchGatewayInfo() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    $result = array(
                        'nickname' => null, //网关名称
                        'commonaddress' => null, //常用地址
                        'firmwareversion' => null, //固件版本
                        'gatewayid' => $app->{'gatewayid'}, //产品编号
                        'manager' => null, //管理员
                        'activationtime' => null            //激活时间
                    );
                    $result = $this->formationGatewayInfo($app, $result);
                    return $this->returnEndBackG('200', '操作成功', '1', $result);
                } else {
                    return $this->returnEndBackG('200', 'token验证失败', '-1', null);
                }
            } catch (Exception $e) {
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-3', null);
    }

    /**
     * 22.组建网关信息.
     *  2015.11.25
     * @return Response
     */
    public function formationGatewayInfo($app, $array) {
        //查询网关信息
        $gatewaySub = $this->selectAppGatewayInfo($app);

        if (!empty($gatewaySub)) {
            $array['nickname'] = $gatewaySub->nickname;
            $array['firmwareversion'] = $gatewaySub->version;
            $array['activationtime'] = $gatewaySub->init_time;
            //查询用户信息
            $userInfo = $this->selecUserInfo($gatewaySub->user_id);
            if (!empty($userInfo)) {
                $array['manager'] = $userInfo->nickname;
            }
        }
        return $array;
    }

    /**
     * 23.查询网关信息.
     *  2015.11.25
     * @return Response
     */
    public function selectAppGatewayInfo($app) {
        $app = \App\UserGatewayBind::where('gateway_id', '=', $app->{'gatewayid'})
                ->where('status', 1)
                ->get();
        return count($app, 0) > 0 ? $app[0] : null;
    }

    /**
     * 25.查询用户资料.
     *  2015.11.25
     * @return Response
     */
    public function selecUserInfo($uid) {
        $app = \App\UserInfo::where('user_id', '=', $uid)
                ->get();
        return count($app, 0) > 0 ? $app[0] : null;
    }

    /**
     * 26.修改网关资料.
     *  2015.11.26
     * @return Response
     */
    public function editGatewayNickname() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {

                //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    if ($this->verificationGatewayRole($app)) {
                        $result = $this->updateGatewayNickname($app);
                        return $this->returnEndBack('200', '操作成功', 'result', $result);
                    } else {
                        return $this->returnEndBack('200', 'token验证失败', 'result', '-4');
                    }
                } else {
                    return $this->returnEndBack('200', 'token验证失败', 'result', '-1');
                }
            } catch (Exception $e) {
                return $this->returnEndBack('200', '操作失败', 'result', '-2');
            }
        }
        return $this->returnEndBack('200', '操作失败', 'result', '-3');
    }

    /**
     * 27.查询是否有权限执行修改操作.
     *  2015.11.25
     * @return Response
     */
    public function verificationGatewayRole($app) {
        $result = \App\UserGatewayBind::where('user_id', '=', $app->{'userid'})
                ->where('gateway_id', $app->{'gatewayid'})
                ->where('status', 1)
                ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * 28.执行修改网关名称操作.
     *  2015.11.25
     * @return Response
     */
    public function updateGatewayNickname($app) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $app->{'gatewayid'})
                ->where('user_id', $app->{'userid'})
                ->where('status', 1)
                ->update(['nickname' => $app->{'nickname'}]);
        return $result;
    }

//    /**
//     * 29.解除网关绑定.
//     *  2015.11.25
//     * @return Response
//     */
//    public function removeUserAndGatewayStatus() {
//        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
//        if (!empty($app)){
//            // 默认接受到有效信息，无效信息则跑catch
//            try {
//
//              //验证用户有效性
//                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
//                    if ($this->verificationUserAndGatewayStatus($app)) {
//                        $result = $this->updateUserAndGatewayStatus($app);
//                        return $this->returnEndBack('200', '操作成功', 'result', $result);
//                    } else {
//                        return $this->returnEndBack('200', '并没有绑定该网关', 'result', '-4');
//                    }
//                } else {
//                    return $this->returnEndBack('200', 'token验证失败', 'result', '-1');
//                }
//            } catch(Exception $e) {
//                return $this->returnEndBack('200', '操作失败', 'result', '-2');
//            }
//        }
//        return $this->returnEndBack('200', '操作失败', 'result', '-3');
//    }
//    /**
//     * 30.执行解除网关绑定操作.
//     *  2015.11.25
//     * @return Response
//     */
//    public function updateUserAndGatewayStatus($app) {
//        $result = \App\UserGatewayBind::where('status', '=', 1)
//            ->where('gateway_id', $app->gatewayid)
//            ->get();
//        if ($result[0]->user_id == $app->userid) {
//            $result = \App\UserGatewayBind::where('id', '=', $result[0]->id)
//                ->where('status', 1)
//                ->update(['status' => 0]);
//        } else {
//            $result = \App\GatewayUserAccount::where('user_id', '=', $app->{'userid'})
//                ->where('bind_id', $result[0]->id)
//                ->where('status', 1)
//                ->update(['status' => 0]);
//        }
//        return $result;
//    }
//    /**
//     * 31.查询是否具有绑定关系.
//     *  2015.11.25
//     * @return Response
//     */
//    public function verificationUserAndGatewayStatus($app) {
//        $result =  DB::table('gateway_user_account')
//            ->leftJoin('user_gateway_bind','user_gateway_bind.id', '=', 'gateway_user_account.bind_id')
//            ->where('gateway_user_account.user_id', '=', $app->userid)
//            ->where('user_gateway_bind.gateway_id', '=', $app->gatewayid)
//            ->where('user_gateway_bind.status', '=', 1)
//            ->where('gateway_user_account.status', '=', 1)
//            ->get();
//        return count($result, 0) > 0 ? true : false;
//    }

    /**
     * 32.Api:解除设备绑定.
     *  2015.11.30
     * 2015.12.01. update . 新增验证是否为主用户function
     * @return Response
     */
    public function removeGatewayDeviceBind() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch。
            try {

                // 验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    // 验证是否为主用户，暂定为只有主用户才会。
                    if ($this->verificationGatewayBind($app)) {
                        // 验证是否有绑定该设备。
                        if ($this->verificationGatewayDeviceBind($app)) {
                            $result = $this->cancelGatewayDeviceBind($app);
                            return $this->returnEndBack('200', '操作成功', 'result', $result);
                        } else {
                            // 未发现该绑定设备。
                            return $this->returnEndBack('200', '并没有绑定该设备', 'result', '-4');
                        }
                    } else {
                        // 该用户非主用户，所以无法执行相关操作。
                        return $this->returnEndBack('200', '并没有权限解除绑定', 'result', '-5');
                    }
                } else {
                    // token验证失败。
                    return $this->returnEndBack('200', 'token验证失败', 'result', '-1');
                }
            } catch (Exception $e) {
                // 服务器错误。
                return $this->returnEndBack('200', '操作失败', 'result', '-2');
            }
        }
        // 参数不完整。
        return $this->returnEndBack('200', '操作失败', 'result', '-3');
    }

    /**
     * 33.function：验证是否存在网关绑定关系.
     *  2015.12.01
     * @return Response
     */
    public function verificationGatewayBind($app) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $app->{'gatewayid'})
                ->where('user_id', $app->{'userid'})
                ->where('status', 1)
                ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * 34.function：验证是否存在设备绑定关系.
     *  2015.11.30
     * @return Response
     */
    public function verificationGatewayDeviceBind($app) {
        $result = DB::table('device_manage')
                ->leftJoin('user_gateway_bind', 'user_gateway_bind.id', '=', 'device_manage.bind_id')
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
        $bind = $this->selectGatewayBindForId($app);
        if (is_null($bind)) {
            return "-6";
        }
        $result = \App\DeviceManage::where('bind_id', '=', $bind)
                ->where('device_id', $app->{'deviceid'})
                ->where('status', 1)
                ->update(['status' => 0]);
        return $result;
    }

    /**
     * 36.Api：查看网关ip.
     *  2015.11.30
     * @return Response
     */
    public function searchgatewayIP() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch。
            try {

                // 验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    // 验证是否为主用户，暂定为只有主用户才会。
                    // if ($this->verificationGatewayBind($app)) {
                    $result = $this->selectGatewayIP($app);

                    return $this->returnEndBackG('200', '操作成功', '1', array('commonaddress' => $result));
                    // } else {
                    // 该用户非主用户，所以无法执行相关操作。
                    // return $this->returnEndBack('200', '并没有权限解除绑定', 'result', '-5');
                    // }  
                } else {
                    // token验证失败。
                    return $this->returnEndBackG('200', 'token验证失败', '-1', null);
                }
            } catch (Exception $e) {
                // 服务器错误。
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        // 参数不完整。
        return $this->returnEndBackG('200', '操作失败', '-3', null);
    }

    /**
     * 37.function:执行查看网关ip操作.
     *  2015.11.30
     * @return Response
     */
    public function selectGatewayIP($app) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $app->{'gatewayid'})
                ->where('status', 1)
                ->get();
        return count($result, 0) > 0 ? $result[0]->ip : null;
    }

    /**
     * 38.Api:添加子用户.
     *  2015.11.30
     * @return Response
     */
    public function addSubUser() {
//        $result = \App\getAppLogBeTime::where('gateway_id', '=', $app->{'gatewayid'})
//                  ->get();
//        return count($result, 0) > 0 ? $result[0]->common_address : null;
    }

    /**
     * 39.function:验证自用户合法性.
     *  2015.11.30
     * @return Response
     */
    public function verificationSubUser($app) {
        $result = null;
        switch ($app->{'type'}) {
            //
            case '1':
                $result = \App\User::where('phone', '=', $app->{'code'})
                        ->where('delete', 0)
                        ->get();
                break;

            case '2':
                $result = \App\User::where('email', '=', $app->{'code'})
                        ->where('delete', 0)
                        ->get();
                break;
        }
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * 41.POST: 查询服务条款.
     *  2015.12.31
     * @return Response
     */
    public function getTermsOfService() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch。
            try {
                $result = \App\TermsOfService::where('page_name', '=', $app->{'pagename'})
                        ->get();
                return count($result, 0) > 0 ? $this->returnEndBackG('200', '操作成功', '1', array('content' => $result[0]->content)) : $this->returnEndBackG('200', '操作成功', '0', null);
            } catch (Exception $e) {
                // 服务器错误。
                return $this->returnEndBackG('200', '操作失败', '-2', null);
            }
        }
        // 参数不完整。
        return $this->returnEndBackG('200', '操作失败', '-3', null);
    }

    /**
     * 42.function:执行查看网关Bind_id操作.
     *  2015.11.30
     * @return Response
     */
    public function selectGatewayBindForId($app) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $app->{'gatewayid'})
                ->where("status", 1)
                ->get();
        return count($result, 0) > 0 ? $result[0]->id : null;
    }

    /**
     *  10.9 查询网关版本信息
     *   名称，位置，网关型号，软件版本，添加时间
     * @url /gateway/getgatewayinfo
     * {
      "gatewayid":"EVERYOO_33333",
      "userid":"xxxxxxxxxxx",
      "accesstoken":"xxxxxxxxxxxxxxxxxxxxxx"
      }
     */
    public function getGatewayInfo() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (empty($app)) {
            return $this->returnEndBackG('200', '操作失败', '-3', null);
        }
        // 默认接受到有效信息，无效信息则跑catch
        try {
            //验证用户是有效性
            if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                $list = $this->getGatewayBindInfo($app->{'gatewayid'});
                $return = [];
                $return['gatewayid'] = $list[0]['gateway_id'];
                $return['nickname'] = $list[0]['nickname'];
                $return['version'] = $list[0]['version'];
                $return['firmware_version'] = $list[0]['firmware_version'];
                $return['init_time'] = $list[0]['init_time'];
                return $this->returnEndBackG('200', '操作成功', '1', $return);
            }
            return $this->returnEndBackG('200', '网关无效', '-1', null);
        } catch (Exception $e) {
            return $this->returnEndBackG('200', '操作失败', '-2', null);
        }
    }

    private function getGatewayBindInfo($gatewayid) {
        $result = \App\UserGatewayBind::where("gateway_id", $gatewayid)
                        ->where("status", 1)->get();
        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    /**
     * 验证用户有效性
     * @return string
     * create 2015.10.23
     */
    public function verificationTonken($uid, $token) {
        $result = false;

        $user = \App\Token::where('user_id', '=', $uid)
                ->where('token', $token)
                ->get();
        if (count($user, 0) > 0) {
            $result = true;
        }
        return $result;
    }

    /**
     * function: 双层数组返回结果
     * create 2016.01.18
     * @return $obj.
     */
    public function returnBackEndForElse($code, $msg, $result, $infoName, $info, $elseName, $else) {
        return $this->backJSON(array(
                    'code' => $code,
                    'msg' => $msg,
                    'result' => $result,
                    $infoName => $info,
                    $elseName => $else
        ));
    }

    /**
     * 要返回的数据结果
     * @return string
     * create 2015.10.21
     */
    public function returnEndBack($code, $msg, $type, $content) {
        $result = $this->backJSON(array(
            'code' => $code,
            'msg' => $msg,
            $type => $content
        ));
        return $result;
    }

    /**
     * 要返回的数据结果 优化版，要求加info
     * @return string
     * create 2015.11.17
     */
    public function returnEndBackG($code, $msg, $result, $content) {
        $result = $this->backJSON(array(
            'code' => $code,
            'msg' => $msg,
            'result' => $result,
            'info' => $content
        ));
        return $result;
    }

}
