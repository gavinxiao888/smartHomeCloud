<?php

namespace App\Http\Controllers\Admin;
include_once(dirname(__FILE__) . '/../Api/' . 'notification/android/AndroidBroadcast.php');
include_once(dirname(__FILE__) . '/../Api/' . 'notification/android/AndroidFilecast.php');
include_once(dirname(__FILE__) . '/../Api/' . 'notification/android/AndroidGroupcast.php');
include_once(dirname(__FILE__) . '/../Api/' . 'notification/android/AndroidUnicast.php');
include_once(dirname(__FILE__) . '/../Api/' . 'notification/android/AndroidCustomizedcast.php');
include_once(dirname(__FILE__) . '/../Api/' . 'notification/ios/IOSBroadcast.php');
include_once(dirname(__FILE__) . '/../Api/' . 'notification/ios/IOSFilecast.php');
include_once(dirname(__FILE__) . '/../Api/' . 'notification/ios/IOSGroupcast.php');
include_once(dirname(__FILE__) . '/../Api/' . 'notification/ios/IOSUnicast.php');
include_once(dirname(__FILE__) . '/../Api/' . 'notification/ios/IOSCustomizedcast.php');
//定义每页条目🌲
define('PAGENUMBER', 10);

use View;
use Cache;
use Events;
use Requests;
use Mail;
use Illuminate\Http\Request;
use DB;
use Session;
// use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends \App\Http\Controllers\Controller
{


    /**
     * POST: 操作网关
     * Create: 2016/03/03
     * @return int
     */
    public function checkShadingPost() {
        if (Session::has('uid') && isset($_REQUEST['bind']) && isset($_REQUEST['deviceId']) && isset($_REQUEST['value'])) {
            $ctrl = $this->findDeviceCtrl($_REQUEST['bind'], $_REQUEST['deviceId']);
            if (!is_null($ctrl)) {
                $ctrl = $this->findDeviceCtrl($_REQUEST['bind'], $_REQUEST['deviceId']);
                $gateway = $this->selectBindInfoToGatewayID($_REQUEST['bind']);
                $sipUser = $this->searchGatewaySipUsername($gateway);
                if (!is_null($ctrl) && !is_null($gateway) && !is_null($sipUser)) {
                    $gateway = 'sip:'.$sipUser.'@'.env('SIP_URL');//'chaos001';
                    $result = $this->bulidSipJson(12, $gateway, array(
                        'ctrlid' => $ctrl,
                        'value' => $_REQUEST['value'],
                        'time' => date('Y-m-d H:i:s', time())
                    ));
                    $sip = $this->curlToSip(env('SIP_URL').env('SIP_PORT').'sip/return', $result);
                    return $sip;
                } else {
                    return 3;
                }
            } else{
                return 2;
            }
        };
    }

    public function searchGatewaySipUsername($gateawy) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $gateawy)
            ->where('status', 1)
            ->get();
        return count($result, 0) > 0 ? $result[0]->sip_user : null;
    }

    /**
     * Function: 根据网关的绑定关系查找网关ID
     * Create: 2016/03/03
     * @param $bind 绑定ID
     * @return null
     */
    public function selectBindInfoToGatewayID($bind) {
        $result = \App\UserGatewayBind::where('id', '=', $bind)
            ->where('status', 1)
            ->get();
        return count($result, 0) > 0 ? $result[0]->gateway_id : null;
    }

    /**
     * Function: 组建要发送给sip的json串
     * Create: 2016/03/03
     * @param $type sip服务器识别的type值
     * @param $gateway 要控制的网关ID
     * @param $msg 要控制网关的指令集
     * @return string 返回的结果
     */
    public function bulidSipJson($type, $gateway, $msg) {
       return $this->backJSON(array(
           'userid' => 'sip:abc@'.env('SIP_URL'),
           'type' => $type,
           'gatewayid' => $gateway,
           'msg' => $msg
       ));
    }

    /**
     * Function:
     * @param $bind
     * @param $device
     * @return null
     */
    public function findDeviceCtrl($bind, $device) {
        $ctrl = \App\DefineCtrl::where('bind_id', '=', $bind)
            ->where('device_id', $device)
            ->where('status', 1)
            ->where('enable', 1)
            ->where('action_id', '937c032c-a7b3-11e5-b360-b8975ab8b52a')
            ->get();
        return count($ctrl, 0) > 0 ? $ctrl[0]->ctrl_id : null;
    }

    public function curlToSip($url, $post_data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_data))
        );
        //执行并获取url地址的内容
        $output = curl_exec($ch);
        $errorCode = curl_errno($ch);
        //释放curl句柄
        curl_close($ch);
        var_dump($output);
        var_dump($errorCode);
        if(0 !== $errorCode) {
            return 'false';
        }
    }

    /**
     * POST：展示遮阳机设备管理界面
     * Create: 2016/03/01
     * @return View
     */
    public function checkShadingShow() {
        if (Session::has('uid') && isset($_REQUEST['id'])) {
            $result = $this->searchShadingInfo($_REQUEST['id']);
            return view('admin.checkShading', [
                'result' => $result
            ]);
        } else {
            return view('admin.checkShading');
        }
    }

    /**
     * Function: 获取该遮阳机设备的详细信息
     * Create: 2016/03/01
     * @param $id 该设备ID
     * @return mixed
     */
    public function searchShadingInfo($id) {
        $device = \App\DeviceManage::find($id);
        if (!is_null($device)) {
            //获取该遮阳机网关绑定的主用户
            $userid = $this->selectUserSip($device->bind_id);
            //获取主用户昵称
            $device->init_time = $this->selectUserNickName($userid);

            $result = \App\DefineCtrl::where('bind_id', $device->bind_id)
                ->where('device_id', $device->device_id)
                //  '8321dc5c-a7b3-11e5-b360-b8975ab8b52a' 为控制这样电机开关百分比。
                ->where('action_id', '8321dc5c-a7b3-11e5-b360-b8975ab8b52a')
                ->where('status', 1)
                ->get();
            //设备状态
//            dd($result);
            $device->status = count($result, 0) > 0 ? $result[0]->value : '暂无数据';
            //设备最后操作时间
            $device->update_time = count($result, 0) > 0 ? $result[0]->update_time : '暂无操作';
        }
        return $device;
    }

    /**
     * POST: 删除网关版本
     * Create: 2016/03/01
     * @return int
     */
    public function editVersionStatus() {
        if (Session::has('uid') && isset($_POST['id'])) {
            return \App\GatewayVersionManage::where('id','=' ,$_POST['id'])
                ->where('status', 1)
                ->update(array(
                    'status' => 0
                ));
        } else {
            return -1;
        }
    }

    /**
     * POST: 修改网关版本启用状态
     * Create: 2016/03/01
     * @return int
     */
    public function editVersionEnable() {
        if (Session::has('uid') && isset($_POST['id']) && isset($_POST['enable'])) {
            return \App\GatewayVersionManage::where('id','=' ,$_POST['id'])
                ->where('status', 1)
                ->update(array(
                    'enable' => $_POST['enable']
                ));
        } else {
            return -1;
        }
    }

    /**
     * POST：发布网关新版本
     * create: 2016/02/27
     * @return GateWayController|int
     */
    public function issueNewestVersion() {
        if (Session::has('uid')) {
            if (isset($_POST['versionCode']) && isset($_POST['deviceType']) && isset($_POST['url'])) {
                $charge = Session::get('uid');
                return count($this->insertNewestVersion($_POST['versionCode'], $_POST['url'], $charge, $_POST['deviceType']), 0);
            } else {
                return 2;
            }
        } else {
            return 0;
        }

    }

    /**
     * function: 新增版本
     * create: 2016/02/27
     * @param $name 版本号
     * @param $href 版本链接
     * @param $charge 发布者ID
     * @param $type 设备类型
     * @return static
     */
    public function insertNewestVersion($name, $href, $charge, $type) {
        $result = \App\GatewayVersionManage::create(array(
            'name' => $name,
            'href' => 'http://'.$_SERVER ['HTTP_HOST'].$href,
            'charage_id' => $charge,
            'type' => $type,
            'init_time' => date('Y-m-d H:i:s', time()),
        ));
        return $result;
    }
    /**
     * 展示界面： 发布新版本页面
     * create 2016/02/25
     * @return View
     */
    public function newVersion() {
        return view('admin.addVersion');
    }

    /**
     * 展示界面： 版本管理列表
     * create: 2016/02/25
     */
    public function versionListShow() {
        $result = \App\GatewayVersionManage::orderBy('init_time', 'desc')
            ->where('status', 1)
            ->paginate(PAGENUMBER);
        $result = $this->bulidVersionList($result);
        return view('admin.versionList')->with('list', $result);
    }

    /**
     * function: 组建要显示的版本列表信息
     * create: 2016/02/25
     * @param $result 要解析的版本信息数据集
     * @return mixed
     */
    public function bulidVersionList($result) {
        foreach($result as $version) {
            $user = $this->selectAdminInfo($version->charge_id);
            $version->charge_id = $user == null ? null : $user->username;
        }
        return $result;
    }

    public function uploadImg() {
        if (!empty($_FILES ['everyoo']['name'])) {
            $base_path = "uploads/"; //接收文件目录
            $target_path = $base_path.basename($_FILES ['everyoo']['name']);
            $target=explode(".",$target_path,2);
            $target_path=$target[0].date('YmdHis',time()).".".$target[1];
            if(move_uploaded_file( $_FILES ['everyoo'] ['tmp_name'], $target_path )) {
                var_dump ('http://192.168.101.150'.$target_path);
                die();
            }else{
                var_dump('123');
                die();
            }
        }
         var_dump('456');
                die();
    }

    /**
     * 调试页面：pjax调试页面.
     * create 2015.11.9
     * @return Response
    **/
    public function pjaxViewShow() {
        return view('admin.pjax');
    }

    /**
     * 展示界面：管理员登陆页面.
     * create 2015.10.29
     * @return Response
    **/
    public function adminLoginViewShow() {
        return view('admin.login');
    }


    /**
     * 展示界面：首页.
     * create 2015.10.29
     * @return Response
    **/
    public function indexShow() {
        return view('admin.index');
    }


    /**
     * 展示界面：新增故障页面.
     * create 2015.12.07
     * @return Response
    **/
    public function newFault() {
        return view('admin.addFault');
    }

    /**
     * POST：新增故障报备.
     * create 2015.12.07
     * @return Response
    **/
    public function createFault() {
        if ((!empty($_REQUEST['name'])) && (!empty($_REQUEST['province']))
            && (!empty($_REQUEST['city'])) && (!empty($_REQUEST['county']))
             && (!empty($_REQUEST['community'])) && (!empty($_REQUEST['floor']))
              && (!empty($_REQUEST['tel'])) && (!empty($_REQUEST['fault_time']))
               && (!empty($_REQUEST['remark']))) {
            $result = $this->insertFault($_REQUEST['name'], $_REQUEST['province'], $_REQUEST['city'], $_REQUEST['county'],
                $_REQUEST['community'], $_REQUEST['floor'], $_REQUEST['tel'], $_REQUEST['fault_time'], $_REQUEST['remark']);
            return $result;
        }
        return 2;
    }

    /**
     * function：填充故障表.
     * create 2015.12.07
     * @return Response
    **/
    public function insertFault($name, $province, $city, $county, $community, $floor, $tel, $time, $remark) {
        if (Session::has('uid')) {
            $uid = Session::get('uid');
            $uuid = DB::SELECT("select uuid() as uuid");
            $fault = \App\FaultRemark::create(array(
              'id' => $uuid[0]['uuid'],
              'name' => $name,
              'province' => $province,
              'city' => $city,
              'county' => $county,
              'community' => $community,
              'floor' => $floor,
              'tel' => $tel,
              'fault_time' => $time,
              'charge_id' => $uid,
              'init_time' => date('Y-m-d H:i:s', time()),
              'update_time' => date('Y-m-d H:i:s', time()),
              'remark' => $_REQUEST['remark']
            ));
        }
        return 1;
    }


    /**
     * 展示界面：设备类型.
     * create 2015.11.21
     * @return Response
    **/
    public function deviceTypeShow() {
        $list = \App\DeviceAttributes::orderBy('init_time', 'desc')
                ->where('status', '1')
                ->paginate(PAGENUMBER);
        foreach ($list as $admin) {
            $admin->charge_id = $this->selectAdminInfo($admin->charge_id)->nickname;
            $admin->device_type = $this->selectDeviceTypeInfo($admin->device_type);
        }
        return view('admin.deviceManage')->with('list', $list);
    }

     /**
     * 查找相关设备类型信息.
     * create 2015.11.21
     * @return Response
    **/
    public function selectDeviceTypeInfo($id) {
        $result = \App\DeviceType::find($id);
        return count($result, 0) > 0 ? $result->name : null;
    }

    /**
     * 查找相关管理员信息.
     * create 2015.11.21
     * @return Response
    **/
    public function selectAdminInfo($id) {
        $result = \App\AdminManage::find($id);
        return count($result, 0) > 0 ? $result : null ;
    }


     /**
     * 功能：新增设备类型. 改为新录入厂商设备。
     * create 2015.11.21
     * update 2015.11.24 新录入厂商设备
     * @return Response
    **/
    public function createDeviceType() {
        // var_dump($_POST);
        if ((!empty($_REQUEST['name'])) && (!empty($_REQUEST['type']))
            && (!empty($_REQUEST['manufacturer_id'])) && (!empty($_REQUEST['product_id']))
            && (!empty($_REQUEST['product_type']))) {
            $result = $this->selectOnceDeviceType($_REQUEST['manufacturer_id'], $_REQUEST['product_id'], $_REQUEST['product_type']);
            if (empty($result)) {
              $result = $this->insertDeviceAttributes($_REQUEST['type'], $_REQUEST['name'], $_REQUEST['manufacturer_id'], 
                $_REQUEST['product_id'], $_REQUEST['product_type']);
            }
            return $result;
        }
        return 2;
    }

    
    /**
     * 查找当前新增设备类型.
     * create 2015.11.21
     * @return Response
    **/
    public function selectOnceDeviceType($manufacturer_id, $product_id, $product_type) {
        $result = \App\DeviceAttributes::where('manufacturer_id', '=', $manufacturer_id)
                  ->where('product_id', $product_id)
                  ->where('product_type', $product_type)
                  ->where('status', 1)
                  ->get();
        return count($result, 0) > 0 ? $result[0]->name : null;
    }

    /**
     * 录入厂商设备.
     * create 2015.11.21
     * @return Response
    **/
    public function insertDeviceAttributes($type, $name, $manufacturer_id, $product_id, $product_type) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $uid = Session::has('uid') ? Session::get('uid') : null;
        $device = \App\DeviceAttributes::create(array(
              'id' => $uuid[0]['uuid'],
              'name' => $name,
              'manufacturer_id' => $manufacturer_id,
              'product_id' => $product_id,
              'product_type' => $product_type,
              'device_type' => $type,
              'charge_id' => $uid,
              'init_time' => date('Y-m-d H:i:s', time()),
              'update_time' => date('Y-m-d H:i:s', time())
            ));
        return 1;
    }


    /**
     * 展示界面：录入用户资料.
     * create 2015.11.21
     * @return Response
    **/
    public function newUserInfo() {
        $list = \App\GatewayManage::lists('id')
            ->toArray();
        $result = array();
        for ($i = 0, $y = count($list, 0); $i < $y; $i++) {
            $result[$i]['id'] = $i;
            $result[$i]['text'] = $list[$i];
        }
        return view('admin.addUser')->with('list', $result);
    }

     /**
     * 展示界面：录入用户资料.
     * create 2015.11.21
     * @return Response
    **/
    public function editUserInfoShow() {
        if (!empty($_REQUEST['code'])) {
            $list = \App\GatewayRemark::find($_REQUEST['code']);
            $array = \App\GatewayManage::lists('id')->toArray();
            $result = array();
            $result[0]['id'] = 0;
            $result[0]['text'] = $this->selectGatewayInfoForBind($_REQUEST['code']);
            for ($i = 1, $y = count($list, 0); $i < $y; $i++) {
                $result[$i]['id'] = $i;
                $result[$i]['text'] = $list[$i];
            }
        } 
        return view('admin.editUser')->with('result', $result)->with('list', $list)->with('editor', $_REQUEST['code']);
    }

     /**
     * POST：录入用户资料.
     * create 2015.11.21
     * @return Response
    **/
    public function editUserInfo() {
        if ((!empty($_REQUEST['gateway'])) && (!empty($_REQUEST['province'])) && (!empty($_REQUEST['city']))
            && (!empty($_REQUEST['county'])) && (!empty($_REQUEST['community'])) && (!empty($_REQUEST['floor']))) {
            if ($this->verificationGateway($this->selectGatewayInfoForBind($_REQUEST['gateway']))) {
                    if ($this->verificationUserInfo($_REQUEST['gateway'])) {
                         $result = $this->insertUserInfo($_REQUEST['gateway'],$_REQUEST['province'],$_REQUEST['city'],$_REQUEST['county']
                        ,$_REQUEST['community'],$_REQUEST['floor']);
                        return $result;
                    } else {
                        $result = $this->updateUserInfo($_REQUEST['gateway'],$_REQUEST['province'],$_REQUEST['city'],$_REQUEST['county']
                        ,$_REQUEST['community'],$_REQUEST['floor']);
                        return $result;
                    }
                   
            } else {
                //网关SN码无效
                return 3;
            }
        }   
        //参数不完整
        return 2;
    }

     /**
     * function：编辑用户资料.
     * create 2015.11.21
     * @return Response
    **/
    public function updateUserInfo($gateway,$province,$city,$county,$community,$floor) {
         if (Session::has('uid')) {
            $result = \App\GatewayRemark::where('id', '=', $gateway)->update(array(
                'province' => $province,
                'city' => $city,
                'county' => $county,
                'community' => $community,
                'floor' => $floor,
                'update_time' =>date('Y-m-d H:i:s', time())
                ));
            return count($result);
        }
        return 0;
    }

    /**
     * POST：录入用户资料.
     * create 2015.11.21
     * @return Response
    **/
    public function createUserInfo() {
        if ((!empty($_REQUEST['gateway'])) && (!empty($_REQUEST['province'])) && (!empty($_REQUEST['city']))
            && (!empty($_REQUEST['county'])) && (!empty($_REQUEST['community'])) && (!empty($_REQUEST['floor']))) {
            if ($this->verificationGateway($_REQUEST['gateway'])) {
                if ($this->verificationUserInfo($_REQUEST['gateway'])) {
                    $result = $this->insertUserInfo($_REQUEST['gateway'],$_REQUEST['province'],$_REQUEST['city'],$_REQUEST['county']
                        ,$_REQUEST['community'],$_REQUEST['floor']);
                    return $result;
                } else {
                    //存在重复录入信息
                    return 4;
                }
            } else {
                //网关SN码无效
                return 3;
            }
        }   
        //参数不完整
        return 2;
    }

    /**
     * function：验证网关设备sn码是否有效.
     * create 2015.11.21
     * @return Response
    **/
    public function verificationGateway($gateway) {
        $result = \App\GatewayManage::find($gateway);
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * function：验证网关设备sn码是否有效.
     * create 2015.11.21
     * @return Response
    **/
    public function verificationUserInfo($gateway) {
        $result = \App\GatewayRemark::find($gateway);
        return count($result, 0) > 0 ? false : true;
    }


    /**
     * function：新增用户资料.
     * create 2015.11.21
     * @return Response
    **/
    public function insertUserInfo($gateway,$province,$city,$county,$community,$floor) {
        if (Session::has('uid')) {
            $uid = Session::get('uid');
            $result = \App\GatewayRemark::create(array(
                'id' => $this->selectGatewayInfoForID($gateway),
                'province' => $province,
                'city' => $city,
                'county' => $county,
                'community' => $community,
                'floor' => $floor,
                'init_time' =>date('Y-m-d H:i:s', time()),
                'charge_id' => $uid,
                ));
            return count($result);
        }
        return 0;
    }

    /**
     * function:查看网关绑定ID
     * create 2016.01.20
     * @param $gateway 网关ID
     * @return array|null
     */
    public function selectGatewayInfoForID($gateway) {
        $result = null;
        if ($this->verificationUserGatewayBind($gateway)) {
            $result = \App\UserGatewayBind::where('status', '=', 1)
                ->where('gateway_id', '=', $gateway)
                ->get();
            $result = count($result, 0) > 0 ? $result[0]->id : null;
        } else {
            $result = $this->createSipAndBind($gateway);
        }
        return $result;
    }

    /**
     * 创建绑定关系和sip账户
     * @param $id 网关id
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

        return $uuid[0]['uuid'];
    }

    /**
     * function:判断是否存在该设备绑定信息
     * @param $id 网关id
     * @return string  返回的结果
     * update from SipController 2015.12.28
     */
    public function verificationUserGatewayBind($id) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $id)
            ->where('status', 1)
            ->get();
        return count($result, 0) > 0 ? true : false;
    }
    /**
     * 展示界面：添加新设备类型.
     * create 2015.11.21
     * @return Response
    **/
    public function newDeviceTypeShow() {
        $list = \App\DeviceType::orderBy('init_time', 'desc')
                ->get();
        return view('admin.addDeviceType')->with('list', $list);
    }



    /**
     * 展示界面：管理员列表页面.
     * create 2015.10.29
     * @return Response
    **/
    public function adminListShow() {
        $result = null;
        $name = null;
        if ((!empty($_REQUEST['name']))) {
            //有查询条件
            $name = $_REQUEST['name'];
            $result = \App\AdminManage::where('nickname', '=', $_REQUEST['name'])
                   ->where('status', 1)
                   ->where('role', 0)
                   ->paginate(PAGENUMBER);
        } else {
            //没有查询条件
            $result = \App\AdminManage::where('status', '=', 1)
                      ->where('role', 0)
                      ->paginate(PAGENUMBER);
        }
        return view('admin.adminList')->with('list', $result)->with('type', $name);
    }

    /**
     * 展示界面：新增管理员页面.
     * create 2015.10.29
     * @return Response
    **/
    public function adminNew() {
        return View('admin.addAdmin');
    }


    /**
     * 展示界面：编辑管理员资料.
     * create 2015.11.24
     * @return Response
    **/
    public function editAdmin() {
        if (!empty($_REQUEST['id'])) {
            $admin = \App\AdminManage::find($_REQUEST['id']);
            return View('admin.editAdmin')->with('admin', $admin);
        }
        return View('admin.editAdmin');
    }

    /**
     * Post：编辑管理员资料.
     * create 2015.11.24
     * @return Response
    **/
    public function editAdminInfo() {
       if ((!empty($_REQUEST['nickname'])) && (!empty($_REQUEST['username'])) 
            && (isset($_REQUEST['password'])) && (!empty($_REQUEST['email']))
             && (!empty($_REQUEST['phone'])) && (!empty($_REQUEST['sex']))
              && (!empty($_REQUEST['id']))){
            $admin = \App\AdminManage::find($_REQUEST['id']);
            if (count($admin, 0) > 0) {
                if (($this->verificationReAdminUsername($_REQUEST['username'], 'username')) && ($_REQUEST['username']!= $admin->username)) {
                    //验证用户名重复
                    return 3;
                } else if (($this->verificationReAdminUsername($_REQUEST['phone'], 'phone')) && ($_REQUEST['phone'] != $admin->phone)) {
                    //手机号重复
                    return 2;
                } else {
                    $result = $this->updateAdmin($_REQUEST['id'],$_REQUEST['nickname'], $_REQUEST['username'], $_REQUEST['email'], $_REQUEST['phone'], $_REQUEST['sex'], $_REQUEST['password']);

                    return $result;
                }
            } else {
                return 5;
            }
          
        }
        //数据不完整
        return 4;
    }

    /**
     * function：修改管理员资料.
     * create 2015.11.24
     * @return Response
    **/
    public function updateAdmin($id, $nickname, $username, $email, $phone, $sex, $password) {
        if (empty($password)) {
            $result = \App\AdminManage::where('id', '=', $id)
                  ->update(array(  
                      'username' => $username,
                      'nickname' => $nickname,
                      'phone' => $phone,
                      'email' => $email,
                      'sex' => $sex,
                      'update_time' => date('Y-m-d H:i:s', time())
                    ));
            return $result;
        } else {
            //update语句返回的是执行完后影响的行数。
            $result = \App\AdminManage::where('id', '=', $id)
                      ->update(array(  
                          'username' => $username,
                          'nickname' => $nickname,
                          'password' => md5($username.$password),
                          'phone' => $phone,
                          'email' => $email,
                          'sex' => $sex,
                          'update_time' => date('Y-m-d H:i:s', time())
                        ));
            return $result;
        }
    }


    /**
     * 展示界面：用户列表页面.
     * create 2015.10.29
     * @return Response
    **/
    public function userListShow() {
        $list = \App\GatewayUserAccount::where('status', 1)
            ->where('role', 1)
            ->paginate(PAGENUMBER);
        $result = $this->gatewayListForUserSip($list);
        return View('admin.userList')->with('list', $result);
    }
    /**
     * function：组建用户列表信息.
     * create 2015.10.29
     * @return Response
    **/
    public function gatewayListForUserSip($list) {
        foreach($list as $user) {
            $user->init_time = $user->bind_id;
            $user->remark = $this->selecGatewayInfo($user->bind_id);
            $user->bind_id = $this->selectGatewayInfoForBind($user->bind_id);
            $user->user_role = $this->selectUserPhone($user->user_id);
            $user->user_id =  $this->selectUserNickName($user->user_id);
        }
        return $list;
    }

    /**
     * function: 根据绑定ID获取网关SN码
     * create 2016.01.20
     * @param $bind 绑定关系ID
     * @return null
     */
    public function selectGatewayInfoForBind($bind) {
        $result = \App\UserGatewayBind::find($bind);
        return count($result, 0) > 0 ? $result->gateway_id : null;
    }

    /**
     * function：获取用户手机号.
     * create 2015.12.11
     * @return Response
    **/
    public function selectUserPhone($user) {
        $result = \App\User::find($user);
        return count($result, 0) > 0 ? $result->phone : null;
    }

    /**
     * function：网关用户信息.
     * create 2015.12.11
     * @return Response
    **/
    public function selecGatewayInfo($id) {
        $result = \App\GatewayRemark::find($id);
        return count($result, 0) > 0 ? $result : null;
    }



    /**
     * 展示界面：网关列表页面.
     * create 2015.10.29
     * @return Response
    **/
    public function gatewayListShow() {
        $list = \App\UserGatewayBind::where('status', '=', 1)
                ->paginate(PAGENUMBER);
        $gatewayStatus = $this->selectGatewayOnline();
        $list = $this->formationGatewayListInfo($list);
        return View('admin.gatewayList')->with('list', $list)->with('status', $gatewayStatus);
    }

    public function selectGatewayOnline() {
        $result = array(
            'count' => 0,
            'open' => 0,
            'close' => 0
            );
        $result = $this->countOnline($result);
        return $result;
    }

    public function countOnline($array) {
        $count = \App\UserGatewayBind::where('status', '=', 1)
                 ->get();
        $online = \App\GatewayStatus::where('online', 1)
                  ->where('status', 1)
                  ->get();
        $array['count'] = count($count);
        $array['open'] = count($online);
        $array['close'] = $array['count']-$array['open'];
        return $array;
    }

    /**
     * 组合需要展示的网关信息.
     * create 2015.10.29
     * @return Response
    **/
    public function formationGatewayListInfo($array) {
        foreach ($array as $gateway) {
            $status = $this->selectGatewayStatus($gateway->user_id);
            if (!empty($status)) {
                //修改网关运行时间
                $gateway->update_time = $status->init_time;
                //修改网关运行状态
                $gateway->status = $status->online;
            } else {
                //修改网关运行时间
                $gateway->update_time = null;
                //修改网关运行状态
                $gateway->status = null;
            }
            //修改网关用户展示资料
            $gateway->user_id = $this->selectGatewayUser($gateway->user_id);
            //赋值网关名称
            $gateway->remark = $this->selectGatewayNickName($gateway->sn_code);
        }
        return $array;
    }

    /**
     * 查看网关最新状态.
     * create 2015.11.22
     * @return Response
    **/
    public function selectGatewayStatus($id) {
        $result = \App\GatewayStatus::where('id', '=', $id)
                  ->where('status', 1)
                  ->get();
        return count($result, 0) > 0 ? $result[0] : null;
    }

    /**
     * 查看网关主用户信息.
     * create 2015.11.22
     * @return Response
    **/
    public function selectGatewayUser($id) {
        $result = \App\UserInfo::where('user_id', '=', $id)
                  ->get();
        return count($result, 0) > 0 ? $result[0]->nickname : null;
    }



    /**
     * 展示界面：故障列表页面.
     * create 2015.10.29
     * @return Response
    **/
    public function faultListShow() {
        $list = \App\FaultRemark::orderBy('init_time', 'desc')
                ->where('status', 1)
                ->paginate(PAGENUMBER);
        return View('admin.faultList')->with('list', $list);
    }

    /**
     * 展示界面：编辑故障页面.
     * create 2015.10.29
     * @return Response
    **/
    public function editFaultShow() {
        if (!empty($_REQUEST['code'])) {
            $list = \App\FaultRemark::find($_REQUEST['code']);
            return view('admin.editFault')->with('list', $list);
        }
    }

    /**
     * POST：编辑故障报备.
     * create 2015.10.29
     * @return Response
    **/
    public function editFault() {
        if ((!empty($_REQUEST['name'])) && (!empty($_REQUEST['province']))
            && (!empty($_REQUEST['city'])) && (!empty($_REQUEST['county']))
             && (!empty($_REQUEST['community'])) && (!empty($_REQUEST['floor']))
              && (!empty($_REQUEST['tel'])) && (!empty($_REQUEST['fault_time']))
               && (!empty($_REQUEST['remark'])) && (!empty($_REQUEST['code']))) {
            $result = $this->updateFault($_REQUEST['name'], $_REQUEST['province'], $_REQUEST['city'], $_REQUEST['county'],
                $_REQUEST['community'], $_REQUEST['floor'], $_REQUEST['tel'], $_REQUEST['fault_time'], $_REQUEST['remark'],
                $_REQUEST['code']);
            return $result;
        }
        return 2;
    }

    /**
     * function：填充故障表.
     * create 2015.12.07
     * @return Response
    **/
    public function updateFault($name, $province, $city, $county, $community, $floor, $tel, $time, $remark, $code) {
        if (Session::has('uid')) {
            $result = \App\FaultRemark::where('id', '=', $code)
                      ->update(array(
                      'name' => $name,
                      'province' => $province,
                      'city' => $city,
                      'county' => $county,
                      'community' => $community,
                      'floor' => $floor,
                      'tel' => $tel,
                      'fault_time' => $time,
                      'update_time' => date('Y-m-d H:i:s', time()),
                      'remark' => $_REQUEST['remark']
                    ));
        }
        return 1;
    }


    /**
     * 展示界面：遮阳机列表页面.
     * create 2015.10.29
     * @return Response
    **/
    public function shadingListShow() {
        $list = \App\DeviceManage::where('device_type', '=', env('ADMIN_NAV_DEVICE_CODE'))
                ->where('status', 1)
                ->paginate(PAGENUMBER);
        $result = $this->formationShading($list);
        $count = $this->getDeviceManageCount();
        return View('admin.shadingList')->with('list', $result)->with('count', $count);
    }


    /**
     * 展示界面：遮阳机列表页面.
     * create 2015.10.29
     * @return Response
    **/
    public function getDeviceManageCount() {
        $result = array();

        //
        $result['all'] = \App\DeviceManage::where('status', '=', 1)
                         ->where('device_type', '=', 5)
                         ->count();
        //
        $count = DB::table('define_ctrl')
                 ->leftJoin('device_manage', 'device_manage.bind_id', '=', 'define_ctrl.bind_id')
                 ->where('device_manage.device_id', 'define_ctrl.device_id')
                 ->where('device_manage.device_type', 5)
                 ->where('define_ctrl.status', 1)
                 ->count(); 
        $result['close'] =  DB::table('define_ctrl')
                            ->leftJoin('device_manage', 'device_manage.bind_id', '=', 'define_ctrl.bind_id')
                            ->where('device_manage.device_id', 'define_ctrl.device_id')
                            ->where('device_manage.device_type', 5)
                            ->where('define_ctrl.action_id', '8321dc5c-a7b3-11e5-b360-b8975ab8b52a')
                            ->where('define_ctrl.value', 0)
                            ->where('define_ctrl.status', 1)
                            ->count();
        //
        $result['open'] = $result['all']-$result['close'];
        return $result;
    }

    /**
     * 组合需要展示的遮阳机信息.
     * create 2015.11.29
     * @return Response
    **/
    public function formationShading($array) {
        $result = array();
        for ($i = 0, $y = count($array, 0); $i < $y; $i++) {
            //获取该遮阳机的ID
            $result[$i]['id'] = $array[$i]->id;
            //获取该遮阳机名称
            $result[$i]['nickname'] = $array[$i]->nickname;
            //获取该遮阳机网关绑定的主用户
            $userid = $this->selectUserSip($array[$i]->bind_id);
            //获取主用户昵称
            $result[$i]['user'] = $this->selectUserNickName($userid);
            //获取该网关名称
            $result[$i]['gateway'] = $this->selectGatewayNickName($array[$i]->bind_id);
            //获取当前开关状态
            $result[$i]['status'] = $this->selectShadingStatus($array[$i]->bind_id, $array[$i]->device_id);
        }
        return $result;
    }   

    /**
     * function: 查询绑定当前网关的主用户.
     * create 2015.11.29
     * @return Response
    **/
    public function selectUserSip($id) {
        $result = \App\UserGatewayBind::find($id);
        return count($result, 0) > 0 ? $result->user_id : null;
    }

    /**
     * function: 查询遮阳机设备当前状态.
     * create 2015.11.29
     * @return Response
    **/
    public function selectShadingStatus($gateway, $device) {
        $result = \App\DefineCtrl::where('bind_id', $gateway)
                  ->where('device_id', $device)
            //  '8321dc5c-a7b3-11e5-b360-b8975ab8b52a' 为控制这样电机开关百分比。
                  ->where('action_id', '8321dc5c-a7b3-11e5-b360-b8975ab8b52a')
                  ->where('status', 1)
                  ->get();
        return count($result, 0) > 0 ? $result[0]->value : 0;
    }

    /**
     * function: 查询用户的昵称.
     * create 2015.11.29
     * @return Response
    **/
    public function selectUserNickName($id) {
        $result = \App\UserInfo::where('user_id', '=', $id)
                  ->get();
        return count($result, 0) > 0 ? $result[0]->nickname : null;
    }

   /**
     * function: 查询需网关的昵称.
     * create 2015.11.29
     * @return Response
    **/
    public function selectGatewayNickName($id) {
        $result = \App\UserGatewayBind::find($id);
        return count($result, 0) > 0 ? $result->nickname : null;
    } 


    /**
     * 展示界面：修改密码页面.
     * create 2015.10.29
     * @return Response
    **/
    public function updatePasswordShow() {
        return View('admin.updatePassword');
    }




    /**
     * 展示界面：推送列表页面.
     * create 2015.10.29
     * @return Response
    **/
    public function pushListShow() {
        $result = null;
        $sub = null;
        if (empty($_REQUEST['sub'])){
            $result = \App\PushLog::orderBy('init_time', 'desc')
                      ->paginate(PAGENUMBER);

        } else {
            // $whereLike = '%'.$_REQUEST['sub'].'％';
            $result = \App\PushLog::where('title', 'like', '%'.$_REQUEST['sub'].'%')
                      ->orderBy('init_time', 'desc')
                      ->paginate(PAGENUMBER);
            $sub = $_REQUEST['sub'];
        }
        //组建要返回的推送消息记录
        $result = $this->formationPushList($result);
        return View('admin.pushList')->with('list', $result)->with('sub', $sub);
    }

    /**
     * 解析要返回的内容.
     * create 2015.11.03
     * @return Response
    **/
    public function formationPushList($list) {
        foreach ($list as $push) {
            $push->charge_id = $this->formationChargeAdmin($push->charge_id);
            $push->type = $this->formationPushType($push->type);
            $push->init_time = $this->formationTime($push->init_time);
            $push->title = mb_substr($push->title, 0, 10, 'utf-8');
            $push->remark = mb_substr($push->content, 0, 10, 'utf-8');
        }
        return $list;
    }



    /**
     * 解析操作时间.
     * create 2015.11.03
     * @return Response
    **/
    public function formationTime($time) {
        if (!empty($time)) {
            return date('Y-m-d', strtotime($time));
        } else {
            return date('Y-m-d', time());
        }
    }



    /**
     * 解析操作员姓名.
     * create 2015.11.03
     * @return Response
    **/
    public function formationChargeAdmin($id) {
        $result = '数据错误';
        $admin = \App\AdminManage::find($id);
        if (!empty($admin)) {
            $result = $admin->nickname;
        }
        return $result;
    }

    /**
     * 解析推送类型.
     * create 2015.11.03
     * @return Response
    **/
    public function formationPushType($type) {
        $result = '数据错误';
        switch ($type) {
            case '1':
                $result = '短信推送';
                break;
            
            case '2':
                $result = '邮件推送';
                break;

            case '3':
                $result = 'App推送';
                break;
        }
        return $result;
    }

    /**
     * 展示界面：1级页面=>设备管理.
     * create 2015.10.29
     * @return Response
    **/
    public function firstDeviceShow() {
        $result = $this->formationDeviceManage();
        return view('admin/deviceShow')->with('count', $result);
    }

    /**
     * function:组建设备管理数据.
     * create 2015.10.29
     * @return Response
    **/
    public function formationDeviceManage() {
        $result = array();
        //获取网关数量
        $result['gateway'] = \App\UserGatewayBind::where("status",1)->count();
        //获取遮阳机数量
        $result['shading'] = \App\DeviceManage::where('device_type', '=', '5')->where('status', 1)->count();
        //获取鼓掌设备数量
        $result['fault'] = \App\FaultRemark::where('status', '=', 1)->count();
        return $result;
    }

    /**
     * 展示界面：1级页面=>用户管理.
     * create 2015.10.29
     * @return Response
    **/
    public function firstUserShow() {
        $result = $this->formationUserManage();
        return view('admin/userShow')->with('count', $result);
    }

    /**
     * function:组建用户管理数据.
     * create 2015.10.29
     * @return Response
    **/
    public function formationUserManage() {
        $result = array();
        //获取用户数量
        $result['user'] = \App\User::orderBy('init_time', 'desc')->count();
        //获取管理员数量
        $result['admin'] = \App\AdminManage::where('status', '=', 1)->count();
        return $result;
    }

    /**
     * 展示界面：1级页面=>推送管理.
     * create 2015.10.29
     * @return Response
    **/
    public function firstPushShow() {
        $result = \App\PushLog::orderBy('init_time', 'desc')->count();
        return view('admin/pushShow')->with('count', $result);
    }

    /**
     * 展示界面：推送页面.
     * create 2015.10.29
     * @return Response
    **/
    public function pushShow() {
        return View('admin.push');
    }

    /**
     * POST：新增管理员.
     * create 2015.10.29
     * @return Response
    **/
    public function cretaeAdmin() {
        if ((!empty($_REQUEST['nickname'])) && (!empty($_REQUEST['username'])) 
            && (!empty($_REQUEST['password'])) && (!empty($_REQUEST['email']))
             && (!empty($_REQUEST['phone'])) && (!empty($_REQUEST['sex']))){
            if ($this->verificationReAdminUsername($_REQUEST['username'], 'username')) {
                //验证用户名重复
                return 3;
            } else if ($this->verificationReAdminUsername($_REQUEST['phone'], 'phone')) {
                //手机号重复
                return 2;
            } else {
                $result = $this->insertAdmin($_REQUEST['nickname'], $_REQUEST['username'], $_REQUEST['password'], $_REQUEST['email'], $_REQUEST['phone'], $_REQUEST['sex']);

                return $result;
            }
        }
        //数据不完整
        return 4;
    }

    /**
     * 执行插入管理员表操作.
     * create 2015.10.30
     * type: 1=>用户名； 2=>email; 3=>phone
     * @return Response
     */
    public function insertAdmin($nickname, $username, $password, $email, $phone, $sex) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $result = \App\AdminManage::create(array(
                  'id' => $uuid[0]['uuid'],
                  'username' => $username,
                  'nickname' => $nickname,
                  'password' => md5($username.$password),
                  'phone' => $phone,
                  'email' => $email,
                  'sex' => $sex,
                  'init_time' => date('Y-m-d H:i:s', time()),
                  'update_time' => date('Y-m-d H:i:s', time())
                  ));
        return count($result, 0);
    }


    /**
     * 验证管理员是否有重复.
     * create 2015.10.29
     * type: 1=>用户名； 2=>email; 3=>phone
     * @return Response
     */
    public function verificationReAdminUsername($value, $type) {
        $result = false;
        $list = \App\AdminManage::where($type, '=', $value)
                ->where('status', 1)
                ->get();
        if (count($list, 0) > 0) {
            $result = true;
        }
        return $result;
    }

    /**
     * 登陆操作.
     * create 2015.10.30
     * @return Response
     */
    public function adminLogin() {
        if ((!empty($_REQUEST['username'])) && (!empty($_REQUEST['password']))) {
            $result = $this->selectAdmin($_REQUEST['username'], $_REQUEST['password']);
            if (count($result, 0) > 0) {
                $sessionEnd = $this->sessionAdmin($result[0]);
                return $sessionEnd;
            }
            return 2;
        }
        return 3;
    }

    /**
     * 根据用户名密码查询当前用户.
     * create 2015.10.30
     * @return Response
     */
    public function selectAdmin($username, $password) {
        $result = \App\AdminManage::where('username', '=', $username)
                  ->where('password', md5($username.$password))
                  ->where('status', 1)
                  ->get();
        return $result;
    }
    
    /**
     * 存储session数据.
     * create 2015.10.30
     * @return Response
     */
    public function sessionAdmin($admin){
        // $expiresAt = Carbon::now()->addMinutes(1000);
        Session::put('uid', $admin->id);
        Session::put('nickname', $admin->nickname);
        Session::put('name', $admin->username);
        Session::put('role', $admin->role);
        return 1;
    }


    /**
     * 删除管理员.
     * create 2015.11.02
     * @return Response
     */
    public function deleteAdmin() {
        if (!empty($_REQUEST['id'])) {
            return $result = $this->updateAdminAsDelete($_REQUEST['id']);
        }
        return 3;
    }


    /**
     * 修改管理员有效状态.
     * create 2015.11.02
     * @return Response
     */
    public function updateAdminAsDelete($id) {
        $result = \App\AdminManage::where('id', '=', $id)
                  ->update(array(
                    'status' => 0
                    ));
        return count($result) ? 1 : 0;
    }


    /**
     * 查看管理员用户密码是否正确.
     * create 2015.11.02
     * @return Response
     */
    public function selectAdminPassword($id, $password) {
        $result = false;
        $admin = \App\AdminManage::find($id);
        if (!empty($admin)) {
            if ($admin->password == md5($admin->username.$password)) {
                $result = true;
            }
        }
        return $result;
    }


    /**
     * 验证当前管理员有效性.
     * create 2015.11.02
     * @return Response
     */
    public function verificationAdminPassword() {
        if (!empty($_REQUEST['password'])) {
            $uid = Session::get('uid');
            if ($this->selectAdminPassword($uid, $_REQUEST['password'])) {
                return 1;
            } else {
                return 2;
            }
        }
        return 3;
    }


    /**
     * 修改管理员密码.
     * create 2015.11.02
     * @return Response
     */
    public function editorAdminPassword() {
        if ($_REQUEST['password']) {
            if ($this->updateaAdminPassword($_REQUEST['password'])) {
                return 1;
            } else {
                return 2;
            }
        }
        return 3;
    }


    /**
     * 执行修改密码操作.
     * create 2015.11.02
     * @return Response
     */
    public function updateaAdminPassword($password) {
        $uid = Session::get('uid');
        $admin = \App\AdminManage::find($uid);
        if (!empty($admin)) {
            $result = \App\AdminManage::where('id', '=', $uid)
                   ->update(array(
                    'password' => md5($admin->username.$password),
                    'update_time' => date('Y-m-d H:i:s', time())
                    ));
            return true;
        }
        return false;
    }


    /**
     *处理推送请求
     *2015.09.28
    **/
    public function pushMessage() {
        // return $_REQUEST['type'].$_REQUEST['content'].$_REQUEST['title'];
        if ((!empty($_REQUEST['type'])) && (!empty($_REQUEST['content'])) 
            && (!empty($_REQUEST['title']))) {
            $uid = Session::get('uid');
            //根据类型决定使用方法 1:手机； 2:邮箱； 3:app；

            $result = $this->pushLog($uid, $_REQUEST['type'], $_REQUEST['title'], $_REQUEST['content']);

            switch ($_REQUEST['type']) {
                case 1:
                    // $resulst = $this->pushForPhoneFunction('13054579207', '123121131');                    
                    $this->pushForPhone($_REQUEST['title'], $_REQUEST['content']);
                    $result = $this->updatePushStatus($result);
                    break;
                case 2:
                     // $this->pushEmailFunction('1352879857@qq.com', $_REQUEST['content'], $_REQUEST['title']);
                    $this->pushForEmail($_REQUEST['title'], $_REQUEST['content']);
                    $result = $this->updatePushStatus($result);
                    break;
                case 3:
                    $count = $this->pushMsgToApp($_REQUEST['title'], $_REQUEST['content']);
                    $result = $this->updatePushStatus($result);
                    break;
            }
            return $result;       
        }
        return 2;
    }

    /**
     * 推送消息入库处理
     * 2015.11.03  create
     * $uid:执行者id; $type:推送类型 1=>推送到手机, 2=>推送到邮箱, 3=>推送到app: $title:推送标题; $content:推送内容; 
    **/
    public function pushLog($uid, $type, $title, $content) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $result = \App\PushLog::create(array(
                      'id' => $uuid[0]['uuid'],
                      'type' =>$type,
                      'charge_id' => $uid,
                      'title' => $title,
                      'content' => $content,
                      'init_time' => date('Y-m-d H:i:s', time()),
                      'update_time' => date('Y-m-d H:i:s', time())
                  ));
        return count($result, 0) > 0 ? $uuid[0]['uuid'] : null;
    }

    /**
     * 推送消息成功后修改状态
     * 2015.11.03  create
     * $id:消息id;
    **/
    public function updatePushStatus($id) {
        $result = null;
        if (!empty($id)){
            $result = \App\PushLog::where('id', '=', $id)
                      ->where('status', '0')
                      ->update(array(
                        'update_time' => date('Y-m-d H:i:s', time()),
                        'status' => 1
                        ));
        }
        return count($result, 0);
    }

    /**
     *处理手机短信推送请求
     *2015.09.28  暂为所有用户推送
     * $content:发送内容
    **/
    public function pushForPhone($title, $content) {
       $list = \App\User::all();
        foreach ($list as $user) {
            # code...
            if (!empty($user->phone)) {
                $this->pushForPhoneFunction($user->phone, $title, $content);
            }
        }
        return 1;
    }

    /**
     *处理邮箱推送请求 暂为所有用户推送
     *2015.09.28
     * $content:推送内容
    **/
    public function pushForEmail($title, $content) {
        $list = \App\User::all();
        foreach ($list as $user) {
            # code...
            if (!empty($user->email)) {
                $this->pushEmailFunction($user->email, $content, $title);
            }
        }
        return 1;
        
    }


    /**
     * 处理邮箱推送请求 
     * 2015.09.28
     * update : 2015.12.11 更改为调用云平台接口
     * $email:邮箱用户
     * $content:推送内容
    **/
    public function pushEmailFunction($email, $content, $title) {

        $key = env('SMS_SECRET_KEY'); //手动新增`super_api`.`project_key` 表数据的secret_key 值
        $time = time();
        $datetime = date("Y-m-d H:i:s", $time);
        $data = array();
        $data['email'] = $email;//要发送的邮件地址
        $data['template_id'] = "0F11F041-3D5D-C58F-086E-EBBCF1E600BB";//模板由云平台管理后台设置
        $data['timestamp'] = $time;
        $data['sign'] = md5($data['template_id'] . $key . $time);
        $data['vars'] = array("action" => $title,"result" => $content);//date,userName


        $data['from'] = "smarthomecloud";//手动新增`super_api`.`project_key` 表数据的project_name 值，实际项目不应该使用此值
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, "http://common.api.everyoo.com/message/send/email");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     *处理手机短信推送请求
     *2015.09.28 
     * update : 2015.12.11 更改为调用云平台接口
     * $phone:手机号
     * $content:短信内容
    **/
    public function pushForPhoneFunction($phone, $title, $content) {
        $key = env('SMS_SECRET_KEY');//手动新增`super_api`.`project_key` 表数据的secret_key 值
        $time = time();
        $datetime = date("Y-m-d H:i:s", $time);
        $data = array();
        $data['mobile'] = $phone;//要发送的手机号码
        $data['template_id'] = "F667E457-27AE-7B4D-D502-CA287C837BE1";//模板由云平台管理后台设置
        $data['timestamp'] = $time;//时间戳
        $data['sign'] = md5($data['template_id'] . $key . $time);//签名
        $data['vars'] = array("result" => $content);
        $data['from'] = "smarthomecloud";//手动新增`super_api`.`project_key` 表数据的project_name 值，实际项目不应该使用此值
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, "http://common.api.everyoo.com/message/send/sms");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);  
        return $result;
    }



    public function pushMsgToApp($title, $content) {
        try {            
            //江西客户版
            // $this->appkey = '565f9596e0f55a2da200512d';
            // $this->appMasterSecret = 'dukyevuvyzeboxxntltlyrwbn4xhg1ad';
            //测试环境
            $this->appkey = env('ANDROIDKEY');
            $this->appMasterSecret = env('ANDROIDAMS');
            $this->timestamp = strval(time());
            $unicast = new \AndroidBroadcast();
            $unicast->setAppMasterSecret($this->appMasterSecret);
            $unicast->setPredefinedKeyValue("appkey",           $this->appkey);
            $unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            // Set your device tokens here
            // $unicast->setPredefinedKeyValue("device_tokens",    "Au4a0QvMbzjtPI3Y_z_qaMaXKQNGCop41F6mGWvU0z2t"); 
            $unicast->setPredefinedKeyValue("ticker",           "Android unicast ticker");
            $unicast->setPredefinedKeyValue("title",            $title);
            $unicast->setPredefinedKeyValue("text",             $content);
            $unicast->setPredefinedKeyValue("after_open",       "go_app");
            // Set 'production_mode' to 'false' if it's a test device. 
            // For how to register a test device, please see the developer doc.
            $unicast->send();
            // print("Sent SUCCESS\r\n");
            return 1;
        } catch (Exception $e) {
            // print("Caught exception: " . $e->getMessage());
            return 2;
        }
    }
    
    /**
     * 输入手机号 或 邮箱查询对应的网关、设备信息
     * @return view
     */
    public function searchDevice($value = null) {
        if ( !$value){
            return view('admin/searchDevice');
        }
        
        if ( preg_match('/^1[3587][0-9]{9}$/i', $value )){
            $userinfo = \App\User::where("phone",$value)->first();
        } else {
            $userinfo = \App\User::where("email",$value)->first();
        }
        if (count($userinfo,0) == 0){
            return view('admin/searchDevice');
        }
        $user_id = $userinfo['id'];
        
        
    }
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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
