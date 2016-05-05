<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Http\Controllers\Controller;

class SyncController extends \App\Http\Controllers\Controller {

    public function syncGatewayIP() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    $result = $this->updateGatewayIP($app->{'gatewayid'}, $app->{'ip'});
                    return $this->returnBackEnd('200', '操作成功', '1', null);
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-1', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-2', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-3', null);
    }

    /**
     * Function:修改网关IP信息
     * Create: 2016/03/04
     * @param $gateway 网关ID
     * @param $ip IP地址
     * @return mixed
     */
    public function updateGatewayIP($gateway, $ip) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $gateway)
                ->where('status', 1)
                ->update(array(
            'ip' => $ip,
            'update_time' => date('Y-m-d H-i-s', time())
        ));
        return $result;
    }

    /**
     * POST: 查询联动列表
     * create 2016.01.18
     * @return string
     */
    public function editTimerEnable() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    $bind = $this->selectUserGatewayBind($app->{'gatewayid'});
                    //组建联动列表
                    if ($this->verificationDefineTimer($app->{'gatewayid'}, $app->{'timerid'})) {
                        $result = $this->updateTimerEnable($app, $bind);
                        //操作成功， 执行成功
                        return $this->returnBackEnd('200', '操作成功', '1', null);
                    } else {
                        //不存在该记录
                        return $this->returnBackEnd('200', '记录不存在', '-1', null);
                    }
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-2', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-3', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-4', null);
    }

    /**
     * function: 修改联动启用状态
     * create 2016/01/26
     * @param $app
     * @param $bind
     * @return mixed
     */
    public function updateTimerEnable($app, $bind) {
        $result = \App\DefineTimer::where('bind_id', '=', $bind)
                ->where('timer_id', $app->{'timerid'})
                ->where('status', 1)
                ->update(array(
            'enable' => $app->{'enable'},
            'update_time' => date('Y-m-d H:i:s', time())
        ));
        return $result;
    }

    public function searchCtrlForGatewayAsOnce() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    $bind = $this->selectUserGatewayBind($app->{'gatewayid'});
                    $result = $this->selectCtrlOnce($app->{'ctrlid'}, $bind);
                    //组建联动列表
                    if (!empty($result)) {
                        $result = $this->bulidCtrlOnce($result);
                        //操作成功， 执行成功
                        return $this->returnBackEnd('200', '操作成功', '1', $result);
                    } else {
                        //不存在该记录
                        return $this->returnBackEnd('200', '记录不存在', '-1', null);
                    }
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-2', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-3', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-4', null);
    }

    /**
     * function: 组建ctrl详细信息
     * create 2016/02/01
     * @param $list
     * @return array
     */
    public function bulidCtrlOnce($list) {
        return array(
            'ctrlid' => $list->ctrl_id,
            'nodeid' => $list->node_id,
            'deviceid' => $list->device_id,
            'actionid' => $list->action_id,
            'value' => $list->value
        );
    }

    /**
     * function: 根据ctrlId查询ctrl详细
     * create 2016/02/01
     * @param $ctrl ctrl ID
     * @param $bind bind ID
     * @return null
     */
    public function selectCtrlOnce($ctrl, $bind) {
        $result = \App\DefineCtrl::where('bind_id', '=', $bind)
                ->where('ctrl_id', $ctrl)
                ->where('status', 1)
                ->get();
        return count($result, 0) > 0 ? $result[0] : null;
    }

    /**
     * POST: 查询联动列表
     * create 2016.01.18
     * @return string
     */
    public function editLinkAgeEnable() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    $bind = $this->selectUserGatewayBind($app->{'gatewayid'});
                    //组建联动列表
                    if ($this->verificationDefineLinkAge($app->{'gatewayid'}, $app->{'linkageid'})) {
                        $result = $this->updateLinkAgeEnable($app, $bind);
                        //操作成功， 执行成功
                        return $this->returnBackEnd('200', '操作成功', '1', null);
                    } else {
                        //不存在该记录
                        return $this->returnBackEnd('200', '记录不存在', '-1', null);
                    }
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-2', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-3', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-4', null);
    }

    /**
     * function: 修改联动启用状态
     * create 2016/01/26
     * @param $app
     * @param $bind
     * @return mixed
     */
    public function updateLinkAgeEnable($app, $bind) {
        $result = \App\DefineLinkAge::where('bind_id', '=', $bind)
                ->where('link_age_id', $app->{'linkageid'})
                ->where('status', 1)
                ->update(array(
            'enable' => $app->{'enable'},
            'update_time' => date('Y-m-d H:i:s', time())
        ));
        return $result;
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
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    $bind = $this->selectUserGatewayBind($app->{'gatewayid'});
                    //组建联动列表
                    $result = $this->buildDefineLinkAgeList($bind);
                    if (count($result, 0) > 0) {
                        //操作成功
                        return $this->returnBackEnd('200', '操作成功', '1', $result);
                    } else {
                        //操作成功，但无联动信息
                        return $this->returnBackEnd('200', '没有数据', '0', null);
                    }
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-2', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-3', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-4', null);
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
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
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
                }
                //操作失败 网关验证失败
                return $this->returnBackEndForElse('200', '验证失败', '-2', 'linkagelist', null, 'triggeredlist', null);
            } catch (Exception $e) {
                return $this->returnBackEndForElse('200', '操作失败', '-3', 'linkagelist', null, 'triggeredlist', null);
            }
        } return $this->returnBackEndForElse('200', '操作失败', '-4', 'linkagelist', null, 'triggeredlist', null);
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
            $linkResult[$i]['position'] = $linkAgeList[$i]['position'];
            $linkResult[$i]['enable'] = $linkAgeList[$i]['enable'];
        }
        for ($i = 0, $y = count($triggeredList, 0); $i < $y; $i++) {
            $triggeredResult[$i]['triggeredid'] = $triggeredList[$i]->triggered_id;
            $triggeredResult[$i]['ctrlid'] = $triggeredList[$i]->ctrl_id;
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
                ->where('triggered_id', 1)
                ->where('bind_id', $bind)
                ->where('status', 1)
                ->get();
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
     * POST: 删除联动信息
     * create 2016.01.18
     * @return string
     */
    public function deleteLinkAge() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    if ($this->verificationDefineLinkAge($app->{'gatewayid'}, $app->{'linkageid'})) {
                        $result = $this->updateLinkAgeStatus($app->{'gatewayid'}, $app->{'linkageid'});
                        return $this->returnBackEnd('200', '操作成功', $result, null);
                    } else {
                        //不存在该记录
                        return $this->returnBackEnd('200', '记录不存在', '-1', null);
                    }
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-2', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-3', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-4', null);
    }

    /**
     * POST: 编辑修改联动信息
     * create 2016.01.18
     * @return string
     */
    public function editLinkAge() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    if ($this->verificationDefineLinkAge($app->{'gatewayid'}, $app->{'originallinkageid'})) {
                        $result = $this->updateLinkAgeStatus($app->{'gatewayid'}, $app->{'originallinkageid'});
                        if ($this->createLinkAgeCtrlAndTriggered($app)) {
                            //操作成功， 执行成功
                            return $this->returnBackEnd('200', '操作成功', '1', null);
                        } else {
                            //操作失败 添加失败
                            return $this->returnBackEnd('200', '操作成功', '0', null);
                        }
                    } else {
                        //不存在该记录
                        return $this->returnBackEnd('200', '记录不存在', '-1', null);
                    }
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-2', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-3', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-4', null);
    }

    /**
     * function: 执行删除联动操作
     * create 2016.01.18
     * @param $gateway 网关ID
     * @param $linkage 联动ID
     * @return bool
     */
    public function updateLinkAgeStatus($gateway, $linkage) {
        $result = DB::table('define_link_age')
                ->Join('user_gateway_bind', 'user_gateway_bind.id', '=', 'define_link_age.bind_id')
                ->where('user_gateway_bind.gateway_id', '=', $gateway)
                ->where('define_link_age.link_age_id', '=', $linkage)
                ->where('define_link_age.status', '=', 1)
                ->where('user_gateway_bind.status', '=', 1)
                ->update(array(
            'define_link_age.update_time' => date('Y-m-d H:i:s', time()),
            'define_link_age.status' => 0
        ));
        return 1;
    }

    /**
     * POST: 从网管同步联动信息
     * create 2016.01.18
     * @return string
     * 
     * update param:
     * {
      "createtime": "",
      "sipaccount": "93510b7a5d82c255",
      "trigger": [
        {
        "value": "1",
        "ctrlid": "adac5b11-dde7-4dc4-b377-cb896bd5e4af",
        "deviceid": "89",
        "rule": "1"
        }
      ],
      "order": [
        {
        "value": "1",
        "ctrlid": "53560a5d-3343-4aaa-a4ad-e8322812b906",
        "deviceid": "93"
        },
        {
        "value": "40",
        "ctrlid": "c3d8ba91-af4c-40a7-a72c-b04079b646ed",
        "deviceid": "81"
        }
      ],
      "gatewayid": "3916050029",
      "linkageid": "5243f94c-098e-4160-8a2d-418bd498d5cd",
      "sippwd": "f08c7d3c1fed755a96cac46163689520",
      "linkagename": "测试",
      "length": "2",
      "enable": "1",
      "end": {
      "value": "40",
      "ctrlid": "c3d8ba91-af4c-40a7-a72c-b04079b646ed",
      "deviceid": "81"
      },
      "begin": {
      "value": "1",
      "ctrlid": "53560a5d-3343-4aaa-a4ad-e8322812b906",
      "deviceid": "93"
      }
      }
     * 
     */
    public function syncLinkAge() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (empty($app)) {
            return $this->returnBackEnd('500', '操作失败', '-4', null);
        }
        if (!$this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
            //操作失败 网关验证失败
            return $this->returnBackEnd('200', '验证失败', '-2', null);
        }
        if ($this->verificationDefineLinkAge($app->{'gatewayid'}, $app->{'linkageid'})) {
            //存在重复记录
            return $this->returnBackEnd('200', '重复记录', '-1', null);
        }
        if ($this->createLinkAgeCtrlAndTriggered($app)) {
            //操作成功， 执行成功
            return $this->returnBackEnd('200', '操作成功', '1', null);
        }
        //操作失败 添加失败
        return $this->returnBackEnd('200', '操作成功', '0', null);
    }

    public function syncLinkAge_______() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    if ($this->verificationDefineLinkAge($app->{'gatewayid'}, $app->{'linkageid'})) {
                        //存在重复记录
                        return $this->returnBackEnd('200', '重复记录', '-1', null);
                    } else {
                        if ($this->createLinkAgeCtrlAndTriggered($app)) {
                            //操作成功， 执行成功
                            return $this->returnBackEnd('200', '操作成功', '1', null);
                        } else {
                            //操作失败 添加失败
                            return $this->returnBackEnd('200', '操作成功', '0', null);
                        }
                    }
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-2', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-3', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-4', null);
    }

    /**
     * POST: 验证是否存在重复的联动信息
     * create 2016.01.18
     * @param $gateway 网关id
     * @param $linkageid 联动id
     * @return bool
     */
    public function verificationDefineTimer($gateway, $timer) {
        $result = DB::table('define_timer')
                ->Join('user_gateway_bind', 'user_gateway_bind.id', '=', 'define_timer.bind_id')
                ->where('user_gateway_bind.gateway_id', '=', $gateway)
                ->where('define_timer.timer_id', '=', $timer)
                ->where('define_timer.status', '=', 1)
                ->where('user_gateway_bind.status', '=', 1)
                ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * POST: 验证是否存在重复的联动信息
     * create 2016.01.18
     * @param $gateway 网关id
     * @param $linkageid 联动id
     * @return bool
     */
    public function verificationDefineLinkAge($gateway, $linkageid) {
        $result = DB::table('define_link_age')
                ->Join('user_gateway_bind', 'user_gateway_bind.id', '=', 'define_link_age.bind_id')
                ->where('user_gateway_bind.gateway_id', '=', $gateway)
                ->where('define_link_age.link_age_id', '=', $linkageid)
                ->where('define_link_age.status', '=', 1)
                ->where('user_gateway_bind.status', '=', 1)
                ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * function: 执行创建LINKAGE操作
     * create 2016.01.18
     * @param $app
     * @return bool
     */
    public function createLinkAgeCtrlAndTriggered($app) {
        $bind = $this->selectUserGatewayBind($app->{'gatewayid'});
        $linkAge = $this->insertIntoDefineLinkAge($app, $bind);
        foreach ($app->{'order'} as $ctrl) {
            $ctrlResult = $this->insertIntoLinkAgeCtrl($ctrl, $bind, $linkAge);
        }
        foreach ($app->{'trigger'} as $triggered) {
            $triggeredResult = $this->insertIntoDefineTriggered($triggered, $bind);
        }
        return $ctrlResult + $triggeredResult == 2 ? true : false;
    }
    
    public function createLinkAgeCtrlAndTriggered_____________________($app) {
        $bind = $this->selectUserGatewayBind($app->{'gatewayid'});
        $linkAge = $this->insertIntoDefineLinkAge($app, $bind);
        foreach ($app->{'ctrllist'} as $ctrl) {
            $ctrlResult = $this->insertIntoLinkAgeCtrl($ctrl, $bind, $linkAge);
        }
        foreach ($app->{'triggeredlist'} as $triggered) {
            $triggeredResult = $this->insertIntoDefineTriggered($triggered, $bind);
        }
        return $ctrlResult + $triggeredResult == 2 ? true : false;
    }

    /**
     * function: 新增联动定义
     * create 2016.01.18
     * @param $app 联动信息
     * @param $bind 网关绑定ID
     * @return int 生成的LINKAGEID
     */
    public function insertIntoDefineLinkAge($app, $bind) {
        $id = $this->createUuid();
        $result = \App\DefineLinkAge::create(array(
                    'id' => $id,
                    'bind_id' => $bind,
                    'link_age_id' => $app->{'linkageid'},
                    'link_age_name' => $app->{'linkagename'},
                    'length' => $app->{'length'},
                    'begin' => $app->{'begin'},
                    'end' => $app->{'end'},
                    'triggered' => $app->{'triggered'},
                    'create_time' => $app->{'createtime'},
                    'init_time' => date('Y-m-d H:i:s', time()),
                    'user_id' => $app->{'userid'},
                    'enable' => '1', //$app->{'enable'},
        ));
        return $id;
    }

    /**
     * function: 新增联动映射
     * create 2016.01.18
     * @param $list 映射数组对象
     * @param $bind 网关绑定ID
     * @return int 生效条目数
     * "order": [
        {
            "value": "1",
            "ctrlid": "53560a5d-3343-4aaa-a4ad-e8322812b906",
            "deviceid": "93"
        },
        {
            "value": "40",
            "ctrlid": "c3d8ba91-af4c-40a7-a72c-b04079b646ed",
            "deviceid": "81"
        }
    ],
     */
    public function insertIntoLinkAgeCtrl($list, $bind, $linkAge) {
        $result = \App\LinkAgeCtrl::create(array(
                    'id' => $this->createUuid(),
                    'bind_id' => $bind,
                    'link_age_id' => $linkAge,
                    'ctrl_id' => $list->ctrlid,
                    'value' => $list->value,
                    'deviceid'=>$list->deviceid,
                    'position' => 0,
                    'init_time' => date('Y-m-d H:i:s', time()),
                    'enable' => 1,
        ));
        return count($result, 0);
    }

    /**
     * function: 新增联动触发器信息
     * create 2016.01.18
     * @param $list 联动触发器数组对象
     * @param $bind 网关绑定ID
     * @return int 生效条目数
     * "trigger": [
        {
            "value": "1",
            "ctrlid": "adac5b11-dde7-4dc4-b377-cb896bd5e4af",
            "deviceid": "89",
            "rule": "1"
        }
    ],
     */
    public function insertIntoDefineTriggered($listArray, $bind) {
        if (!is_array($listArray) && count($listArray) == 0){
            return 0;
        }
        foreach ($listArray as $list){
            $result = \App\DefineTriggered::create(array(
                        'id' => $this->createUuid(),
                        'bind_id' => $bind,
                       // 'triggered_id' => $list->triggeredid,
                        'ctrl_id' => $list->ctrlid,
                        'value' => $list->value,
                        'rule' => $list->rule,
                        'init_time' => date('Y-m-d H:i:s', time()),
                        'enable' => 1,
                        'deviceid'=>$list->deviceid
            ));
        }
        return count($result, 0);
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
     * POST: 网关查询ctrl信息
     * create 2016.01.18
     * @return string
     */
    public function searchCtrl() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    $result = $this->selectValidCtrl($app->{'gatewayid'});
                    if (!empty($result)) {
                        //操作成功
                        return $this->returnBackEnd('200', $result[0]['init_time'], '1', $this->formationCtrl($result));
                    } else {
                        //无数据
                        return $this->returnBackEnd('200', '操作成功', '0', null);
                    }
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-2', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-3', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-4', null);
    }

    /**
     * function: 组建网关要获取的ctrl指令
     * create 2016.01.18
     * @param $list
     * @return array
     */
    public function formationCtrl($list) {
        $result = array();
        for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
            $result[$i]['ctrlid'] = $list[$i]['ctrl_id'];
            $result[$i]['nodeid'] = $list[$i]['node_id'];
            $result[$i]['deviceid'] = $list[$i]['device_id'];
            $result[$i]['actionid'] = $list[$i]['action_id'];
            $result[$i]['value'] = $list[$i]['value'];
            $result[$i]['inittime'] = $list[$i]['init_time'];
            $result[$i]['enable'] = $list[$i]['enable'];
        }
        return $result;
    }

    /**
     * function: 查询有效的网关生成的ctrl指令
     * create 2016.01.18
     * @param $gateway
     * @return null
     */
    public function selectValidCtrl($gateway) {
        $result = DB::table('define_ctrl')
                ->Join('user_gateway_bind', 'user_gateway_bind.id', '=', 'define_ctrl.bind_id')
                ->Join('device_manage', 'device_manage.device_id', '=', 'define_ctrl.device_id')
                ->orderBy('define_ctrl.init_time', 'desc')
                ->where('user_gateway_bind.gateway_id', '=', $gateway)
                ->where('user_gateway_bind.status', '=', 1)
                ->where('device_manage.status', '=', 1)
                ->get();
        return count($result, 0) > 0 ? $result : null;
    }

    /**
     * POST:从网关同步ctrl信息
     * create 2016.01.01
     * @return string
     */
    public function syncCtrlForGateway() {
        $app = json_decode(file_get_contents('php://input'));
        $this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            // 默认接受到有效信息，无效信息则跑catch
            try {
                //验证用户名密码有效性
                if ($this->verificationSnCode($app->{'gatewayid'}, $app->{'sipaccount'}, $app->{'sippwd'})) {
                    $bind = $this->verificationGatewayRole($app->{'gatewayid'});
                    if (!empty($bind)) {
                        if (count($app->{'list'}, 0) > 0 && $this->verificationDeviceCtrl($bind, $app->{'list'}[0]->deviceid)) {
                            $result = $this->insertCtrl($bind, $app->{'list'});
                            return $this->returnBackEnd('200', '操作成功', '1', null);
                        }
                        return $this->returnBackEnd('200', '已存在该设备信息', '0', null);
                    }

                    //
                    return $this->returnBackEnd('200', '没有权限', '-1', null);
                }
                //操作失败 网关验证失败
                return $this->returnBackEnd('200', '验证失败', '-2', null);
            } catch (Exception $e) {
                return $this->returnBackEnd('500', '操作失败', '-3', null);
            }
        } return $this->returnBackEnd('500', '操作失败', '-4', null);
    }

    /**
     * Function:验证网关是否已上报过此设备的ctrl
     * Create: 2016/03/03
     * @param $bind 绑定关系ID
     * @param $device 设备ID
     * @return bool 返回结果
     */
    public function verificationDeviceCtrl($bind, $device) {
        $result = \App\DefineCtrl::where('bind_id', '=', $bind)
                ->where('device_id', $device)
                ->where('status', 1)
                ->where('enable', 1)
                ->get();
        return count($result, 0) > 0 ? false : true;
    }

    /**
     * function: 插入ctrl表
     * create 2016.01.01
     * @param $bind
     * @param $list
     * @return int
     */
    public function insertCtrl($bind, $list) {
        foreach ($list as $ctrl) {
            $uuid = \DB::SELECT("select uuid() as uuid");
            if ($this->selectCtrl($bind, $ctrl)) {
                $insert = \App\DefineCtrl::create(array(
                            'id' => $uuid[0]['uuid'],
                            'bind_id' => $bind,
                            'ctrl_id' => $ctrl->id,
                            'node_id' => $ctrl->nodeid,
                            'device_id' => $ctrl->deviceid,
                            'action_id' => $ctrl->actionid,
                            'value' => $ctrl->value,
                            'init_time' => $ctrl->inittime,
                            'enable' => $ctrl->enable,
                            'update_time' => date('Y-m-d H:i:s', time()),
                ));
            }
        }
        return 1;
    }

    /**
     * function: 验证是否存在重复ctrl信息
     * create 2016.01.01
     * @param $bind
     * @param $ctrl
     * @return bool
     */
    public function selectCtrl($bind, $ctrl) {
        $result = \App\DefineCtrl::where('bind_id', '=', $bind)
                ->where('ctrl_id', $ctrl->id)
                ->where('device_id', $ctrl->deviceid)
                ->where('action_id', $ctrl->actionid)
                ->where('status', 1)
                ->get();
        return count($result, 0) > 0 ? false : true;
    }

    /**
     * 验证网关sn码是否有效
     * create 2015.10.13
     * update 2015.12.28 修改验证表
     * @return $result true or false.
     */
    public function verificationSnCode($sncode, $sipUser, $sipPass) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $sncode)
                ->where('status', 1)
                ->get();
        if (count($result, 0) > 0 && $result[0]->sip_user == $sipUser) {
            $result = $this->verificationGateway($sipUser, $sipPass);
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * 验证sip用户是否合法
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    /**
     * 验证网关是否有权限
     * create 2015.10.20
     * @return $obj.
     */
    public function verificationGatewayRole($gateway) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $gateway)
                ->where('status', 1)
                ->get();
        return count($result, 0) > 0 ? $result[0]->id : null;
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
