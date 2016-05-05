<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    /**
     * 接收日志.
     * create 2015.10.23
     * update 2016.01.07
     * updateContent nodeid=>deviceid,cmdcode+contenttype+content=>ctrl_id+value
     * @return Response
     */
    public function gatewayLog() {
         $gateway = json_decode(file_get_contents('php://input'));$this->log($gateway, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($gateway)){
            // 默认接受到有效信息，无效信息则跑catch
            try {
                
              //验证用户名密码有效性
                if ($this->verificationSnCode($gateway->{'gatewayid'}, $gateway->{'sipaccount'}, $gateway->{'sippwd'})) {
                    
                    /* 11月28 取消上传用户ID
                    // //验证用户有效性
                    // if ($this->verificationUser($gateway->{'userid'})) {
                      */  
                        //验证用户日志是否有重复
                            //插入日志
                            $result = $this->insertGatewayLog($gateway);
                            //操作结束 返回响应条数
                            return $this->returnEndBack('200', '操作成功', 'result', $result);
                    // }
                }return  $this->returnEndBack('200', '操作成功', 'result', '4');
            } catch(Exception $e) {
                return $this->returnEndBack('200', '操作失败', 'result', '2');
            }
        }
        return $this->returnEndBack('200', '操作失败', 'result', '0');
    }

    /**
     * function:获取网关绑定ID
     * function 2016.01.07
     * @param $gatewayid ID
     * @return null
     */
    public function selectUserGatewayBind($gatewayid) {
        $result = \App\UserGatewayBind::where('gateway_id', '=', $gatewayid)
            ->where('status', 1)
            ->get();
        return count($result, 0) > 0 ? $result[0]->id : null;
    }


    /**
     * 插入日志数据库.
     * create 2015.10.23
     * @return Response
     */
    public function insertGatewayLog($app) {
        // 生成唯一uuid
        $uuid = DB::SELECT("select uuid() as uuid");
        $bindId = $this->selectUserGatewayBind($app->{'gatewayid'});
        $result = null;
        foreach ($app->{'list'} as $log) {
            if ($this->verificationDeviceLog($log, $app->{'gatewayid'})) {
                $result = $this->createGatewayLog($log, $bindId);
            }
        }
        return 1;
    }

    public function createGatewayLog($log, $bindId) {
        $uuid = DB::SELECT("select uuid() as uuid");
        $device = $this->selectDeviceType($bindId, $log->deviceid);
        $result = \App\DeviceLog::create(array(
            'id' => $uuid[0]['uuid'],
            'user_id' => $log->userid,
            'bind_id' => $bindId,
            'device_type' => $device,
            'device_id' => $log->deviceid,
            'event_time' => $log->eventtime,
            'ctrl_id' => $log->ctrlid,
            'value' => $log->value,
            'init_time' => date('Y-m-d H:i:s', time())
        ));
        return count($result, 0);
    }


    /**
     * function: 查找设备类型
     * create 2016.01.07
     * @param $bind 绑定关系ID
     * @param $device 设备ID
     * @return null
     */
    public function selectDeviceType($bind, $device) {
        $result = \App\DeviceManage::where('device_id', '=', $device)
            ->where('bind_id', $bind)
            ->get();
        return count($result, 0) > 0 ? $result[0]->device_type : null;
    }


    /**
     * 验证日志是否有重复.
     * create 2015.10.23
     * @return Response
     */
    public function verificationDeviceLog($app, $gateway) {
        $result = DB::table('device_log')
            ->Join('user_gateway_bind','user_gateway_bind.id', '=', 'device_log.bind_id')
            ->where('device_log.event_time', '=', $app->eventtime)
            ->where('user_gateway_bind.gateway_id', '=', $gateway)
            ->where('device_log.device_id', '=', $app->deviceid)
            ->where('device_log.ctrl_id', '=', $app->ctrlid)
            ->where('device_log.value', '=', $app->value)
            ->get();
        return count($result, 0) > 0 ? false : true;
    }

    /**
     * 验证用户有效性.
     * create 2015.10.23
     * @return Response
     */
    public function verificationUser($uid) {
        $result = true;
        if (!empty($uid)) {
            $userinfo = \App\User::find($uid);
            if (empty($userinfo)) {
                $result = false;
            }
        }
        return $result;
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
     * POST:主用户查看网关日志.
     * create 2015.11.27
     * @return Response
     */
    public function serachGatewayLog() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {
              //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    if ($this->verificationUserRole($app->{'gatewayid'}, $app->{'userid'})) {
                        $result = $this->formationGatewayLog($app);
                        return $this->returnEndBackG('200', '操作成功', '1', $result);
                    } else {
                        return $this->returnEndBackG('200', '操作成功', '－4', null);
                    }
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch(Exception $e) {
                return $this->returnEndBackG('200', '操作成功', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-3', null);
    }

    /**
     * POST:上拉加载网关全部日志
     * create 2015.11.27
     * @return string
     */
    public function reloadGatewayLog() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            // 默认接受到有效信息，无效信息则跑catch
            try {
              //验证用户有效性
                if ($this->verificationTonken($app->{'userid'}, $app->{'accesstoken'})) {
                    if ($this->verificationUserRole($app->{'gatewayid'}, $app->{'userid'})) {
                        $result = $this->formationGatewayLog($app, $app->{'eventtime'});
                        return $this->returnEndBackG('200', '操作成功', '1', $result);
                    } else {
                        return $this->returnEndBackG('200', '操作成功', '-4', null);
                    }
                }
                return $this->returnEndBackG('200', 'token验证失败', '-1', null);
            } catch(Exception $e) {
                return $this->returnEndBackG('200', '操作成功', '-2', null);
            }
        }
        return $this->returnEndBackG('200', '操作失败', '-3', null);
    }

    /**
     * function:验证用户是否为主用户.
     * create 2015.11.27
     * @return Response
     */
    public function verificationUserRole($gatewayid, $uid) {
        $result = \App\UserGatewayBind::where('gateway_id', $gatewayid)
            ->where('user_id', $uid)
            ->where('status', 1)
            ->get();
        return count($result, 0) > 0 ? true : false;
    }

    /**
     * function:组建日志.
     * create 2015.11.27
     * @return Response
     */
    public function formationGatewayLog($app, $eventtime = null) {
        $result = array();
        $array = $this->selectGatewayLogToUser($app->{'gatewayid'}, 10, $eventtime);
        for($i = 0, $y = count($array, 0); $i < $y; $i++) {
            //获取设备类型
//            $result[$i]['devicetype'] = $this->matchDeviceType($array[$i]->gatewayid, $array[$i]->nodeid);
            //匹配设备名称
            $result[$i]['devicename'] = $this->matchDeviceTypeInfo($array[$i]['device_type']);
            //填充cmdcode
            $result[$i]['ctrlid'] = $array[$i]['ctrl_id'];
            //匹配设备昵称
            $result[$i]['nickname'] = $this->matchDeviceInfo($array[$i]);
            //匹配状态结果
//            $result[$i]['status'] = $this->matchContentType($array[$i]->contenttype, $array[$i]->content);
            $result[$i]['value'] = $array[$i]['value'];
            //匹配用户姓名
            $result[$i]['user'] = $this->matchUserNickname($array[$i]['user_id']);
            //匹配路数开关
//            $result[$i]['endpoint'] = $array[$i]->endpoint;
            //填充时间
            $result[$i]['eventtime'] = $array[$i]['event_time'];
        }
        return $result;
    }


    /**
     * function:匹配终端设备类型.
     * create 2015.11.27
     * @return Response
    **/
    public function matchDeviceType($gatewayid, $nodeid) {
        $result = \App\DeviceManage::where('gateway_id', '=', $gatewayid)
                  ->where('node_id', $nodeid)
                  ->get();
        return count($result, 0) > 0 ? $result[0]->device_type : null;
    }

    /**
     * function:查看日志.
     * create 2015.11.27
     * @return Response
    **/
    public function selectGatewayLogToUser($gatewayid, $count, $eventtime = null) {
        $result = null;
        if (!empty($eventtime)) {
            $result = DB::table('device_log')
                ->leftJoin('user_gateway_bind','user_gateway_bind.id', '=', 'device_log.bind_id')
                ->leftJoin('device_manage','device_manage.device_id', '=', 'device_log.device_id')
                ->orderBy('device_log.event_time', 'desc')
                ->where('device_manage.status', '=', 1)
                ->where('user_gateway_bind.gateway_id', '=', $gatewayid)
                ->where('user_gateway_bind.status', '=', 1)
                ->where('device_log.eventtime', '<', $eventtime)
                ->take($count)
                ->get();
            return count($result, 0) > 0 ? true : false;
        } else {
             $result = DB::table('device_log')
                 ->leftJoin('user_gateway_bind','user_gateway_bind.id', '=', 'device_log.bind_id')
                 ->leftJoin('device_manage','device_manage.device_id', '=', 'device_log.device_id')
                 ->orderBy('device_log.event_time', 'desc')
                 ->where('device_manage.status', '=', 1)
                 ->where('user_gateway_bind.gateway_id', '=', $gatewayid)
                 ->where('user_gateway_bind.status', '=', 1)
                 ->take($count)
                 ->get();
        }
        return $result;
    }

    /**
     * function:根据content_type和content匹配要返回的结果.
     * create 2015.11.28
     * @return Response
    **/
    public function matchContentType($type, $content) {
        $result = null;
        $contentType = \App\CmdDictionary::where('content_type', $type)
                       ->get();
        if (count($contentType, 0) > 0) {
            $result = $this->matchContentTypeEnd($contentType[0], $content);
        }
        return $result;
    }


    public function matchContentTypeEnd($dictionary, $content) {
        $result = null;
        switch ($dictionary->type) {
            case '1':
                if ($dictionary->content_type == 4) {
                    if ($content != 0) {
                        $result = '移动开';
                    } else {
                        $result = '移动关';
                    }
                } else {
                    if ($content != 0) {
                        $result = '开启';
                    } else {
                        $result = '关闭';
                    }
                }
                break;
            
            case '2':
                $result = $content.$dictionary->unit;
                break;

            case '3':
                if ($content != 0) {
                    $result = '报警';
                } else {
                    $result = '清除';
                }
                break;
        }
        return $result;
    }

    /**
     * function:匹配设备类型.
     * create 2015.11.28
     * @return Response
    **/
    public function matchDeviceTypeInfo($type) {
        $result = \App\DeviceType::find($type);
        return count($result, 0) > 0 ? $result->name : null;
    }

    /**
     * function:匹配用户的nickname.
     * create 2015.11.28
     * @return Response
    **/
    public function matchUserNickname($uid) {
        $result = null;
        if (!empty($uid)) {
            $result = \App\UserInfo::where('user_id', '=', $uid)
                      ->get();
        }
        return count($result, 0) > 0 ? $result[0]->nickname : null;
    }

    /**
     * function:匹配设备的nickname.
     * create 2015.11.28
     * @return Response
    **/
    public function matchDeviceInfo($log) {
        $result = DB::table('device_manage')
            ->leftJoin('user_gateway_bind','user_gateway_bind.id', '=', 'device_manage.bind_id')
            ->where('user_gateway_bind.gateway_id', '=', $log['gateway_id'])
            ->where('user_gateway_bind.status', '=', 1)
            ->where('device_manage.status', '=', 1)
            ->where('device_manage.device_id', '=', $log['device_id'])
            ->get();
        return count($result, 0) > 0 ? $result[0]['nickname'] : null;
    }


    /**
     * 要返回的数据结果
     * @return string
     * create 2015.10.21
     */
    public function returnEndBack($code, $msg, $type, $content) {
        $result = $this->backJSON(array(
            'code'   => $code,
            'msg'    => $msg,
            $type    => $content
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
            'code'   => $code,
            'msg'    => $msg,
            'result' => $result,
            'info'   => $content
            ));
        return $result;
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


    /**
     * 验证用户有效性
     * @return string
     * create 2015.10.23
     */
    public function verificationTonken($uid, $token) {
        $user = \App\Token::where('user_id', '=', $uid)
                ->where('token', $token)
                ->get();
        return count($user, 0) > 0 ? true : false;
    }   


}
