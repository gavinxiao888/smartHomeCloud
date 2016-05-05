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

class PushController extends \App\Http\Controllers\Controller
{   
    protected $appkey           = NULL; 
    protected $appMasterSecret  = NULL;
    protected $timestamp        = NULL;
    protected $validation_token = NULL;

    // function __construct($key, $secret) {
    //     $this->appkey = $key;
    //     $this->appMasterSecret = $secret;
    //     $this->timestamp = strval(time());
    // }

//    /**
//     * Display a listing of the resource.
//     *
//     * @return Response
//     */
//    public function index()
//    {
//      try {
//            $this->appkey = '565f9596e0f55a2da200512d';
//            $this->appMasterSecret = 'dukyevuvyzeboxxntltlyrwbn4xhg1ad';
//            $this->timestamp = strval(time());
//            $customizedcast = new \AndroidCustomizedcast();
//            $customizedcast->setAppMasterSecret($this->appMasterSecret);
//            $customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
//            $customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
//            // Set your alias here, and use comma to split them if there are multiple alias.
//            // And if you have many alias, you can also upload a file containing these alias, then
//            // use file_id to send customized notification.
//            $customizedcast->setPredefinedKeyValue("alias",            "msj");
//            // Set your alias_type here
//            $customizedcast->setPredefinedKeyValue("alias_type",       "everyoo");
//            $customizedcast->setPredefinedKeyValue("ticker",           "Android customizedcast ticker");
//            $customizedcast->setPredefinedKeyValue("title",            "Android customizedcast 单播推送给张某某");
//            $customizedcast->setPredefinedKeyValue("text",             "Android customizedcast 单播推送给张某某的主要内容");
//            $customizedcast->setPredefinedKeyValue("after_open",       "go_app");
//            $array = $this->tryFormationPushContent($_GET['off']);
//            foreach ($array as $key => $value) {
//                $customizedcast->setExtraField($key,              $value);
//            }
//            print("Sending customizedcast notification, please wait...\r\n");
//            $customizedcast->send();
//            print("Sent SUCCESS\r\n");
//        } catch (Exception $e) {
//            print("Caught exception: " . $e->getMessage());
//        }
//    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return Response
//     */
//    public function create()
//    {
//        try {
//            $array = array(
//            'pushtype'  => 2, //网关绑定成功
//            'gatewayid' => '8e749ee4-9aa5-11e5-a0b3-005056b56678',
//            'gatewayname'    => '网关',
//            'role'   => 1,
//            'eventtime' => date('Y-m-d H:i:s', time())
//            );
//            $this->appkey = env('ANDROIDKEY');
//            $this->appMasterSecret = env('ANDROIDAMS');
//            $this->timestamp = strval(time());
//            // $title = $this->setOff($_REQUEST['off']);
//            $customizedcast = new \AndroidCustomizedcast();
//            $customizedcast->setAppMasterSecret($this->appMasterSecret);
//            $customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
//            $customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
//            // Set your alias here, and use comma to split them if there are multiple alias.
//            // And if you have many alias, you can also upload a file containing these alias, then
//            // use file_id to send customized notification.
//            $customizedcast->setPredefinedKeyValue("alias",            "wangzh");
//            // Set your alias_type here
//            $customizedcast->setPredefinedKeyValue("alias_type",       "everyoo");
//            $customizedcast->setPredefinedKeyValue("ticker",           "Android customizedcast ticker");
//            $customizedcast->setPredefinedKeyValue("title",            '标题->');
//            $customizedcast->setPredefinedKeyValue("text",             '内容->');
//            $customizedcast->setPredefinedKeyValue("after_open",       "go_app");
//            foreach ($array as $key => $value) {
//                $customizedcast->setExtraField($key,              $value);
//            }
//            print("Sending customizedcast notification, please wait...\r\n");
//            $customizedcast->send();
//            print("Sent SUCCESS\r\n".'->');
//        } catch (Exception $e) {
//            print("Caught exception: " . $e->getMessage());
//        }
//    }

    public function setOff($off) {
      $result = '开';
      if ($off == 0) {
        $result = '关';
      }
      return $result;
    }

    /**
     * {
        "ctrlid":"dde68bd5-4745-45fd-93cb-b888be91da4f",
        "eventtime":"2016\/03\/30 13:29:37",
        "sipaccount":"0c3e65c4ff7e4065",
        "flag":"1",
        "gatewayid":"3916050022",
        "sippwd":"0fb49bc12f691b5663f4923cb59c0c09",
        "value":"1",
        "deviceid":"56"}
     * @return type
     */
    public function uploadMsgFromGateway() {
        $pushTime = microtime();
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            try {
                //验证网关设备是否合法  2015.10.26
                if ($this->verificationSnCode($app->{'gatewayid'},$app->{'sipaccount'}, $app->{'sippwd'})) {
                    $bind = $this->searchGatewayBind($app->{'gatewayid'});
                    if (!empty($bind)) {
                        $verUser = $this->verificationUserType($bind->id);
                        // 修改状态详情为ctrl表 2016.01.08
                        if ($this->verificationGatewayStatus($app->{'gatewayid'}, $app->{'deviceid'}, $app->{'ctrlid'})) {
                            //修改网关设备状态信息。 2015.10.26
                            $result = $this->updateGatewayStatus($bind->id, $app->{'deviceid'}, $app->{'ctrlid'}, $app->{'value'},$pushTime);
                        } else {

                            return $back = $this->returnEndBack('200', '成功', 'result', '4');
                            /* 2016.01.09 update 如库内不存在该条数据
                            //新增网关设备状态信息。 2015.10.26
                            $result = $this->insertGatewayStatus($bind->id
                              , $app->{'deviceid'}, $app->{'ctrlid'}, $app->{'value'}, $pushTime);
                            */
                        }
                        // 组建推送内容
                        $array = $this->formationPushContent($app);
                        if ($app->{'flag'} == 1) {
                            $push = 1;
                            if ($verUser['android'] == 1) {
                               //开始推送消息，返回1：成功； 返回0: 失败；
                              $push = $this->pushMsgToApp($app->{'gatewayid'}, 'everyoo_gateway', '成功', '成功', $array);
                            }
                            if ($verUser['ios'] == 1) {
                               //开始推送消息，返回1：成功； 返回0: 失败；
                              $push = $this->pushToIos($app->{'gatewayid'}, 'everyoo_gateway', '成功', $array);
                            }
                            return $back = $this->returnEndBack('200', '成功', 'result', $push);
                        } else if ($app->{'flag'} == 0){
                            //开始推送消息，返回1：成功； 返回0: 失败；
                            return $back = $this->returnEndBack('200', '成功', 'result', '1');
                        }
                      } else {

                            //开始推送消息，返回1：成功； 返回0: 失败；
                            return $back = $this->returnEndBack('200', '成功', 'result', '3');
                      }
                   
                }
                return $back = $this->returnEndBack('200', '成功', 'result', '5');
            } catch(Exception $e) {
                return $this->returnEndBack('200', '操作失败', 'result', '2');
            }
        }
        return $back = $this->returnEndBack('500', '失败', 'result', '6');
    }


    /**
     * 组建要上报的的信息内容
     * create 2015.10.26
     * @return string
    **/
    public function tryFormationPushContent($off) {
        $result = array(
            'pushtype'  => 1, //上报信息
            'gatewayid' => 'wangzh',
            'nodeid'    => '3',
            'contenttype'   => '0',
            'cmdcode'   => '1',
            'content'   => $off,
            'eventtime' => date('Y-m-d H:i:s', time())
            );
        return $result;
    }

    /**
     * 组建要上报的的信息内容
     * create 2015.10.26
     * @return string
    **/
    public function formationPushContent($app) {
        $result = array(
            'pushtype'  => 1, //上报信息
            'gatewayid' => $app->{'gatewayid'},
            'deviceid'    => $app->{'deviceid'},
            'ctrlid'   => $app->{'ctrlid'},
            'value'   => $app->{'value'},
            'eventtime' => $app->{'eventtime'}
            );
        return $result;
    }

    /**
     * 插入网关状态
     * create 2015.10.22
     * @return string
     */
    public function insertGatewayStatus($bind, $device, $ctrl, $value, $pushTime) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $result = \App\DefineCtrl::create(array(
              'id' => $uuid[0]['uuid'],
              'bind_id' => $bind,
              'device_id' => $device,
              'ctrl_id' => $ctrl,
              'value' => $value,
              'init_time' => date('Y-m-d H:i:s', time()),
//              'remark' => $pushTime.'-->'.microtime()
            ));

        //返回响应的条目数
        return count($result);
    }


    /**
     * 修改网关状态
     * create 2015.10.22
     * update 2016.01.08 修改设备详情为ctrl表
     * @return string
    **/
    public function updateGatewayStatus($bind, $device, $ctrl, $value, $pushTime) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $result = \App\DefineCtrl::where('bind_id', '=', $bind)
                  ->where('device_id', $device)
                  ->where('ctrl_id', $ctrl)
                  ->update(array(
                   'value' => $value,
                   'update_time' => date('Y-m-d H:i:s', time()),
//                   'remark' => $pushTime.'-->'.microtime()
                    ));
        //返回响应的条目数
        return count($result);
    }

    /**
     * function: 查询绑定关系ID
     * create 2015.10.22
     * @return string
    **/
    public function searchGatewayBind($gateway) {
      $result = \App\UserGatewayBind::orderBy('init_time', 'desc')
                ->where('gateway_id', $gateway)
                ->where('status', 1)
                ->get();
      return count($result, 0) > 0 ? $result[0] : null;
    }

    /**
     * function: 验证推送类型
     * create 2015.10.22
     * @return string
    **/
    public function verificationUserType($bid) {
        $list = $this->selectUserType($bid);
        $result = array(
          'android' => 0,
          'ios' => 0
          );
        foreach ($list as $user) {
          if ($user['remark'] == '1') {
            $result['android'] = 1;
          } else if ($user['remark'] == '2') {
            $result['ios'] = 1;
          }
        }
        return $result;
    }

    /**
     * function: 获取用户类型列表
     * create 2015.10.22
     * @return string
    **/
    public function selectUserType($bid) {
        $result = DB::table('token')
                  ->leftJoin('gateway_user_account', 'token.user_id', '=', 'gateway_user_account.user_id')
                  ->where('gateway_user_account.bind_id', $bid)
                  ->where('gateway_user_account.status', 1)
                  ->where('token.type', 1)
                  ->select('token.remark')
                  ->get();
        return $result;
    }

    /**
     * 查看是否存在当前网关状态
     * create 2015.10.22
     * @return string
     */
    public function verificationGatewayStatus($gateway, $device, $ctrl) {
        $result = DB::table('define_ctrl')
            ->Join('user_gateway_bind','user_gateway_bind.id', '=', 'define_ctrl.bind_id')
            ->where('user_gateway_bind.gateway_id', '=', $gateway)
            ->where('define_ctrl.device_id', '=', $device)
            ->where('define_ctrl.ctrl_id', '=', $ctrl)
            ->where('define_ctrl.enable', '=', 1)
            ->get();
        return count($result, 0) > 0 ? true : false;
    }


    /**
     * 要返回的数据结果
     * create 2015.10.22
     * @return string
     */
    public function returnEndBack($code, $msg, $type, $content) {
        $result = $this->backJSON(array(
            'code'   => $code,
            'msg'    => $msg,
            $type    => $content
            ));
        return $result;
    }

    // public function verificationIosOrAndroid($gateway) {
    //   $result = \App\
    // }

    /**
     * 推送消息到android端.
     * @alias:      网关ID
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
     * function:推送到ios
     * create 2015.11.30
     * @return string
    **/
    public function pushToIos($alias, $aliasType, $title, $array)  {
          try {
            //测试环境
            $this->appkey = env('IOSKEY'); // '5656a3a2e0f55aefa2000ea5';
            $this->appMasterSecret = env('IOSAMS');// 'hyj83khclemx2q3h2zoyfdmac15i2rru';
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
        }
    }
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @return Response
//     */
//    public function store()
//    {
//        //
//         try {
//            $array = array(
//            'pushtype'  => 2, //网关绑定成功
//            'gatewayid' => '5515856a-65bc-11e5-9aab-005056b543f6',
//            'gatewayname'    => '网关',
//            'role'   => 1,
//            'eventtime' => date('Y-m-d H:i:s', time())
//            );
//            $this->appkey = '5656a3a2e0f55aefa2000ea5';
//            $this->appMasterSecret = 'hyj83khclemx2q3h2zoyfdmac15i2rru';
//            $this->timestamp = strval(time());
//            $customizedcast = new \IOSCustomizedcast();
//            $customizedcast->setAppMasterSecret($this->appMasterSecret);
//            $customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
//            $customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
//
//            // Set your alias here, and use comma to split them if there are multiple alias.
//            // And if you have many alias, you can also upload a file containing these alias, then
//            // use file_id to send customized notification.
//            $customizedcast->setPredefinedKeyValue("alias", "5515856a-65bc-11e5-9aab-005056b543f6");
//            // Set your alias_type here
//            $customizedcast->setPredefinedKeyValue("alias_type", "everyoo");
//            $customizedcast->setPredefinedKeyValue("alert", "IOS 个性化测试,嗨,还能不能行了。");
//            $customizedcast->setPredefinedKeyValue("badge", 0);
//            $customizedcast->setPredefinedKeyValue("sound", "chime");
//            foreach ($array as $key => $value) {
//                $customizedcast->setCustomizedField($key,              $value);
//            }
//            // Set 'production_mode' to 'true' if your app is under production mode
//            $customizedcast->setPredefinedKeyValue("production_mode", "false");
//            print("Sending customizedcast notification, please wait...\r\n");
//            $customizedcast->send();
//            print("Sent SUCCESS\r\n");
//        } catch (Exception $e) {
//            print("Caught exception: " . $e->getMessage());
//        }
//    }

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
