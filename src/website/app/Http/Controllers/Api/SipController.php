<?php namespace App\Http\Controllers\Api;
/************************************************************
   Copyright (C), 2015-2016, Everyoo Tech. Co., Ltd.
   FileName: SSOController.php
   代码狂魔，凡人勿动！勇士，乐观的接受现实吧！
   User: Squirrel  Version :1.0.0  Date: 2015/6/23
 *************************************************************/

use DB;
use View;
class SipController extends \App\Http\Controllers\Controller{


    /**
     * 根据s/n码获取用户sip账户
     * update from SSOController 2015.10.21
     */
    public function gatewayForSip() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            //验证网关是否有效
            if ($this->verificationGateway($app->{'gateway_sn'})) {
                return $this->returnEndBack('200', '验证失败', '-1', null);
            } else {
                //获取网关sip帐号, 1:网关设备
                $result = $this->getSipCode($app->{'gateway_sn'});
                $result = array(
                    'sip_account' => $result['username'],
                    'sip_pwd' => $result['password']
                );
                return $this->returnEndBack('200', '成功', '1', $result);
            }
        }
        return $this->returnEndBack('500', '失败', '-2', null);
    }

    /**
     * 组建或获取sip信息并返回
     * @param $id 网关ID
     * @return array|null|string
     * create 2015.12.28
     */
    public function getSipCode($id) {
        $result = $this->verificationUserGatewayBind($id);
        if (!empty($result)) {
            $result = $this->SnSipCode($result);
        } else {
            $result = $this->createSipAndBind($id);
        }
        return $result;
    }

    /**
     * 验证是否存在该网关
     * @param $id 网关id
     * @return bool
     * create 2015.12.28
     */
    public function verificationGateway($id) {
        $result = \App\GatewayManage::where('id', '=', $id)
                  ->where('status', 1)
                  ->get();
        return count($result, 0) > 0 ? false : true;
    }

    /**
     * 创建绑定关系和sip账户
     * @param $id 网关id
     * @update  2016.03.08 yuruyi
     *      add 5 default can't delete robot for user who first bind the gateway
     * @return array
     * create 2015.12.28
     */
    public function createSipAndBind($id) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $username = substr(md5($id.time()), 8, 16);
        $bind = \App\UserGatewayBind::create(array(
            'id' => $uuid[0]['uuid'],
            'gateway_id' => $id,
            'sip_user' => $username,
            'init_time' => date('Y-m-d H:i:s', time())
        ));

        //创建5个空场景
        $this->buildEmptyRobot($uuid[0]['uuid']);
        
        $sipaccount = array(
            'username'=> $username,
            'password'=> md5($username)
        );
        $sip =\App\Subscriber::create($sipaccount);
        return $sipaccount;
    }

    
    /**
     * add yuruyi 20160308
     * 创建N个空场景
     * @param type $gateway_id
     * @param type $num
     */
    private function buildEmptyRobot($bind_id){
        $robotName = ["回家场景","离家场景","睡觉场景","起床场景","用餐场景"];
        for($i = 0;$i < 5 ;$i++){
            $uuid = DB::SELECT("select uuid() as uuid");
            \App\DefineRobot::create(array(
                "id"=>$uuid[0]['uuid'],
                "bind_id"=>"$bind_id" ,
                "robot_id"=>$uuid[0]['uuid'],
                "robot_name" => $robotName[$i],
                "length" => 0,
                "create_time"=>date("Y-m-d H:i:s",time()),
                "init_time"=>date("Y-m-d H:i:s",time()),
                "is_init"=>$i+1,
                "status"=>1,
                "enable"=>1,
                ));
        }
    }
    
    /**
     * 判断是否存在该设备绑定信息
     * @param $id 网关id
     * @return string  返回的结果
     * update from SipController 2015.12.28
     */
    public function verificationUserGatewayBind($id) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $id)
                  ->where('status', 1)
                  ->get();
        return count($result, 0) > 0 ? $result[0]->sip_user : null;
    }

    /**
     * 用户获取用户sip账户
     * @param $id 设备id
     * @return string  返回的结果
     * update from SSOController 2015.10.21
     */
    public function SnSipCode($id) {
       $result = null;
       $sip = \App\Subscriber::where('username', '=', $id)->get();
       if (count($sip, 0) > 0) {
            $result = array(
                'username' => $sip[0]->username,
                'password' => $sip[0]->password
                );
       }
       return $result;
    }

    
    /**
     * 要返回的数据结果
     * @return string
     * create 2015.10.21
     */
    public function returnEndBack($code, $msg, $result, $info) {
        $result = $this->backJSON(array(
            'code'   => $code,
            'msg'    => $msg,
            'result' => $result,
            'info' => $info
            ));
        return $result;
    }

   
    /**
     * 根据用户id 设备id 录入token
     * @return string
     */
    public function sipReturn() {
        $app = file_get_contents('php://input');
        return $app;
    }

}