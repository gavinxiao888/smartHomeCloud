<?php namespace App\Http\Controllers\Api;

define('SERVER_PATH', 'http://192.168.101.150:93/');


use DB;
use View;
use Request;
class UserController extends \App\Http\Controllers\Controller{


    /**
     * 根据用户id和token验证用户是否合法
     * @param $uid 用户id
     * @param $token token
     * @return bool 返回结果；
     */
    public function verifyToken($uid, $token) {
        $result = false;
        $list = \App\Token::where('user_id', '=', $uid)->where('token', $token)->get();
        if (count($list) > 0) {
            return $result = true;
        }
        return $result;
    }


    public function toTry(){
        // return date('Y-m-d H:i:s', time());
        // $list = array('a','b','c');

        // print_r($list[4]);
        print_r('123456789');
        $uuid = DB::SELECT("select uuid() as uuid");
        var_dump($uuid[0]['uuid']);
        die();
        $new = \App\User::orderBy('init_time', 'desc')->get();
        $list = \App\User::create(array(
            'id' => $uuid->uuid,
            'init_time' => date('Y-m-d H:i:s', time()),
            'email' => 'SquirrelNo1@163.com',
            'phone' => '13054579207',
            'passwd' => '123456',
        ));
        print_r("abc");
        return "edf";
    }


    public function infoBind() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            if ($this->verifyToken($app->{'userid'}, $app->{'accesstoken'})) {
                $result = $this->BackUserInfo($app->{'userid'});
                return $back = $this->backJSON(array(
                    'code' => 200,
                    'msg' => '成功',
                    'info' => $result
                ));
            }
            return $back = $this->backJSON(array(
                'code' => 200,
                'msg' => 'token验证失败',
                'info' => array(
                    'result' => 0
                )
            ));
        }
        // 数据获取失败
        return $back = $this->backJSON(array(
            'code' => 200,
            'msg' => '数据获取失败',
            'info' => array(
                'result' => 0
            )
        ));
    }

    public function BackUserInfo($uid) {
        $list = \App\User::find($uid);
        $result = array();
        $result['result'] = 1;
        if (!empty($list)) {
            if (!empty($list->email)) {
                $result['email'] = '已绑定';
            }else {
                $result['email'] = '未绑定';
            }
            if (!empty($list->phone)) {
                $result['phone'] = '已绑定';
            } else {
                $result['phone'] = '未绑定';
            }
            $third = \App\ThirdParties::where('third_type', '=', 1)->where('user_id', $uid)->get();
            if (count($third, 0) > 0) {
                $result['qq'] = '已绑定';
            } else {
                $result['qq'] = '未绑定';
            }
            $third = \App\ThirdParties::where('third_type', '=', 2)->where('user_id', $uid)->get();
            if (count($third, 0) > 0) {
                $result['weixin'] = '已绑定';
            } else {
                $result['weixin'] = '未绑定';
            }
            $third = \App\ThirdParties::where('third_type', '=', 3)->where('user_id', $uid)->get();
            if (count($third, 0) > 0) {
                $result['sina'] = '已绑定';
            } else {
                $result['sina'] = '未绑定';
            }
        }
        return $result;
    }

    public function versionUpdating() {
        if ((!empty($_REQUEST["userid"])) && (!empty($_REQUEST["deviceid"])) && (!empty($_REQUEST["accesstoken"]))
            && (!empty($_REQUEST["version"]))) {
            if ($this->verifyToken($_REQUEST["userid"], $_REQUEST["accesstoken"])) {
                $list = AppVersionUpdating::orderBy('init_time', 'desc')->get();

                //存在版本数据
                if ((count($list, 0) > 0)) {

                    //传输版本为最新版本
                    if ($list[0]->version == $_REQUEST["version"]) {
                        return $back = $this->backJSON(array(
                            'code' => 200,
                            'msg' => '操作成功',
                            'info' => array(
                                'result' => 2,
                                'version' => $_REQUEST["version"],
                                'content' => $list[0]->content,
                                'level' => $list[0]->level
                            )
                        ));
                    }

                    //不是最新版本的情况下，查询该版本是否存在
                    $version = AppVersionUpdating::where('version', '=', $_REQUEST["version"])->get();
                    if (count($version, 0) == 0) {
                        return $back = $this->backJSON(array(
                            'code' => 200,
                            'msg' => '操作失败，无此版本',
                            'info' => array(
                                'result' => 0,
                                'version' => $_REQUEST["version"],
                                'content' => '',
                                'level' => ''
                            )
                        ));
                    } else {
                        return $back = $this->backJSON(array(
                            'code' => 200,
                            'msg' => '操作成功',
                            'info' => array(
                                'result' => 1,
                                'version' => $list[0]->version,
                                'content' => $list[0]->content,
                                'level' => $list[0]->level
                            )
                        ));
                    }
                }
                return $back = $this->backJSON(array(
                    'code' => 200,
                    'msg' => '操作失败，数据库版本信息不足。',
                    'info' => array(
                        'result' => 0,
                        'version' => $_REQUEST["version"],
                        'content' => '',
                        'level' => ''
                    )
                ));
            } // token验证失败
            return $back = $this->backJSON(array(
                'code' => 200,
                'msg' => 'token验证失败',
                'info' => array(
                    'result' => 0
                )
            ));
        }
        // 数据获取失败
        return $back = $this->backJSON(array(
            'code' => 200,
            'msg' => '数据获取失败',
            'info' => array(
                'result' => 0
            )
        ));
    }

    /**
     * 1.9 验证手机号/邮箱验证码
     * @return string
     */
    public function doBind() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            if ($this->verifyToken($app->{'userid'}, $app->{'accesstoken'})) {
                $list = \App\AppAuthCode::where('user_name', '=', $app->{'username'})->where('user_id', $app->{'userid'})->where('type', $app->{'type'})
                    ->where('device_id', $app->{'deviceid'})->get();
                if (count($list, 0) == 0) {
                    return $back = $this->backJSON(array(
                        'code' => 200,
                        'msg' => '不存在该绑定验证！',
                        'info' => array(
                            'result' => 0,
                            'msg' => '不存在该绑定验证,请重新获取验证码。'
                        )
                    ));
                }
                if ($list[0]->code == $app->{'authcode'}) {
                    $result = $this->AuthProfile($app->{'userid'}, $app->{'type'}, $app->{'username'});
                    return $back = $this->backJSON(array(
                        'code' => 200,
                        'msg' => '成功',
                        'info' => array(
                            'result' => 1,
                            'msg' => '绑定成功'
                        )
                    ));
                }
            }
            // token验证失败
            return $back = $this->backJSON(array(
                'code' => 200,
                'msg' => 'token验证失败',
                'info' => array(
                    'result' => 0
                )
            ));
        }
        // 数据获取失败
        return $back = $this->backJSON(array(
            'code' => 200,
            'msg' => '数据获取失败',
            'info' => array(
                'result' => 0
            )
        ));
    }


    /**
     * 根据用户id 修改用户手机号或邮箱绑定
     * @param $id 用户id
     * @param $type 绑定类型
     * @param $user 绑定数据
     * @return string 返回影响结果行数
     */
    public function AuthProfile($id, $type, $user) {
        $result = '';
        switch ($type) {
            case '1':
                $result = \App\User::where('id', '=', $id)->update(array(
                    'phone' => $user
                ));
                break;
            case '2':
                $result = \App\User::where('id', '=', $id)->update(array(
                    'email' => $user
                ));
                break;
        }
        return $result;
    }


    /**
     * 绑定手机号获取验证码；
     * @return string
     */
    public function getBindAuthCode() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            try {
                if ($this->verifyToken($app->{'userid'}, $app->{'accesstoken'})) {
                    $code = rand(100000, 999999);
                    $data = "您好！您本次绑定验证码为：" . $code;
                    $profile = \App\User::find($app->{'userid'});
                    // 1为手机号
                    if ($app->{'type'} == 1) {
                        //该用户已绑定手机
                        if ($profile->phone != null) {
                            return $back = $this->backJSON(array(
                                'code' => 200,
                                'msg' => '用户已绑定',
                                'info' => array(
                                    'result' => -1
                                )
                            ));
                        }
                        $mobile = $this->registerForMobilePhone($app->{'username'}, $data);

                        // 2 为邮箱
                    } else if ($app->{'type'} == 2) {
                        // 该用户已绑定email
                        if ($profile->email != null) {
                            return $back = $this->backJSON(array(
                                'code' => 200,
                                'msg' => '用户已绑定',
                                'info' => array(
                                    'result' => -1
                                )
                            ));
                        }
                        $email = $this->pushToEmail($app->{'username'}, $code);
                    }
                    $user = $this->FillAuthCode($app->{'userid'}, $app->{'type'}, $code,$app->{'username'}, $app->{'deviceid'});
                    return $back = $this->backJSON(array(
                        'code' => 200,
                        'msg' => '操作成功',
                        'info' => array(
                            'result' => 1
                        )
                    ));
                }
                // token验证失败
                return $back = $this->backJSON(array(
                    'code' => 200,
                    'msg' => 'token验证失败',
                    'info' => array(
                        'result' => 0
                    )
                ));
            } catch (Exception $e) {
                $back = $this->backJSON(array(
                    'code' => 500,
                    'msg' => '操作失败，请检查您的数据格式',
                    'info' => array(
                        'result' => 0,
                    )
                ));
                return $back;
            }
        }
        // 数据获取失败
        return $back = $this->backJSON(array(
            'code' => 200,
            'msg' => '数据获取失败',
            'info' => array(
                'result' => 0
            )
        ));
    }

    /**
     * 填充app验证
     * @param $uid  用户id
     * @param $type 用户绑定类型
     * @param $code 验证码
     * @param $user 绑定帐号
     * @param $deviceid 设备id
     * @return static 返回影响结果行数。
     */
    public function FillAuthCode($uid, $type,$code,$user,$deviceid) {
        $list = \App\AppAuthCode::where('user_name', '=', $user)->where('user_id', $uid)->where('type', $type)->where('device_id', $deviceid)->get();
        if (count($list, 0) > 0) {
            $authCode = \App\AppAuthCode::where('user_name', '=', $user)->where('user_id', $uid)->where('type', $type)->where('device_id', $deviceid)->update(array(
                'code' => $code,
                'expire_time' => date("Y-m-d H:i:s", strtotime("+1 day")),
                'update_time' => date('Y-m-d H:i:s', time())
            ));
            return $authCode;
        }
        $uuid = DB::SELECT("select uuid() as uuid");
        $authCode = \App\AppAuthCode::create(array(
            'id' => $uuid[0]['uuid'],
            'code' => $code,
            'user_id' => $uid,
            'type' => $type,
            'user_name' => $user,
            'device_id' => $deviceid,
            'expire_time' => date("Y-m-d H:i:s", strtotime("+1 day")),
            'init_time' => date('Y-m-d H:i:s', time()),
            'update_time' => date('Y-m-d H:i:s', time())
        ));
        return $authCode;
    }

    /**
     * 第三方帐号绑定
     * @return string
     */
    public function thirdBind() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);

        if (!empty($app)) {
            if ($this->verifyToken($app->{'userid'}, $app->{'accesstoken'})) {
                $user = \App\ThirdParties::where('user_id', '=', $app->{'userid'})->where('role', 1)->get();

                //如果存在已绑定帐号。
                if (count($user, 0) > 0) {
                    $third = \App\ThirdParties::where('third_type', '=', $app->{'platformid'})->where('third_id', $app->{'openid'})->get();
                    if (count($third, 0) > 0) {
                        $user = \App\ThirdParties::where('third_type', '=', $app->{'platformid'})->where('third_id', $app->{'openid'})->update(array(
                            'user_id' => $app->{'userid'},
                            'update_time' => date('Y-m-d H:i:s', time()),
                        ));
                        return $back = $this->backJSON(array(
                            'code' => 200,
                            'msg' => '成功',
                            'info' => array(
                                'result' => 1,
                                'msg' => '绑定成功！'
                            )
                        ));
                    } else {
                        $uuid = DB::SELECT("select uuid() as uuid");
                        $user = \App\ThirdParties::create(array(
                            'id' => $uuid[0]['uuid'],
                            'user_id' => $app->{'userid'},
                            'device_id' => $app->{'deviceid'},
                            'third_type' => $app->{'platformid'},
                            'third_id' => $app->{'openid'},
                            'third_name' => $app->{'nickname'},
                            'init_time' => date('Y-m-d H:i:s', time()),
                            'update_time' => date('Y-m-d H:i:s', time()),
                        ));
                        return $back = $this->backJSON(array(
                            'code' => 200,
                            'msg' => '成功',
                            'info' => array(
                                'result' => 1,
                                'msg' => '绑定成功！'
                            )
                        ));
                    }
                }

                //如果 不存在已绑定帐号。
                $user = $this->SplitThirdInsert($app->{'platformid'}, $app->{'userid'}, $app->{'openid'}, $app->{'nickname'}, $app->{'deviceid'});
                return $back = $this->backJSON(array(
                    'code' => 200,
                    'msg' => '成功',
                    'info' => array(
                        'result' => 1,
                        'msg' => '绑定成功！'
                    )
                ));
            }
           return $back = $this->backJSON(array(
                'code' => 200,
                'msg' => '失败',
                'info' => array(
                    'result' => 0,
                    'msg' => 'token验证失败！'
                )
            ));
        }
        return $back = $this->backJSON(array(
            'code' => 200,
            'msg' => '失败',
            'info' => array(
                'result' => 0,
                'msg' => '数据不完整！'
            )
        ));
    }

    /**
     * 如果不存在已绑定帐号需执行操作，即没有主帐号
     * @param $type 第三方类型
     * @param $id 用户id
     * @param $third 第三方id
     * @param $name 昵称
     * @param $device 设备id
     * @return int 返回结果。
     */
    public function SplitThirdInsert($type, $id, $third, $name, $device) {
            $list = \App\ThirdParties::where('third_type', '=', $type)->where('third_id', $third)->get();
            if (count($list, 0) > 0) {
                \App\ThirdParties::where('user_id', '=', $id)->where('third_type', $type)->where('third_id', $third)->update(array(
                    'role' => '1',
                    'update_time' => date('Y-m-d H:i:s', time())
                ));
            } else {
                $uuid = DB::SELECT("select uuid() as uuid");
                $thirdUser = \App\ThirdParties::create(array(
                    'id' => $uuid[0]['uuid'],
                    'user_id' => $id,
                    'device_id' => $device,
                    'third_type' => $type,
                    'third_id' => $third,
                    'third_name' => $name,
                    'init_time' => date('Y-m-d H:i:s', time()),
                    'update_time' => date('Y-m-d H:i:s', time()),
                    'role' => 1
                ));
            }
            return 1;
    }

    /**
     * 根据类型获取要修改的第三方平台信息
     * @param $type 用户id
     * 2015/08/11 修改第三方绑定
     */
    public function SplitThirdUpdate($uid) {
        $result = array();
        $result = array(
            'user_id' => $uid,
            'role' => 1
        );
        return $result;
    }

    /**
     * 修改用户信息
     * @return string
     */
    public function editProfile() {

        try {
                $token = Request::input('accesstoken');
                $userid = Request::input('userid');
                $deviceid = Request::input('deviceid');
                $this->log( [$token ,$userid], $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
            if ($this->verifyToken($userid, $token)) {
                $signature = Request::input('signature');
                $nickname = Request::input('nickname');
                $realname = Request::input('realname');
                $gender = Request::input('gender');
                $birthday = date('Y-m-d H:i:s', Request::input('birthday'));
                $avatar = 'avatar';
//                $file = fopen('../public/uploads/' . '20150731.txt',"w");
//                fwrite($file, $userid);//写入
//                fclose($file);//关闭
//                die();
                $profie = $this->uploadImgAsOne($avatar);
//                $uuid = DB::SELECT("select uuid() as uuid");

                $headimg = \App\UserInfo::where('user_id', '=', $userid)->get();
                if ($profie == 2) {
                    $info = \App\UserInfo::where('user_id', '=', $userid)->update(array(
                        'nickname' => $nickname,
                        'real_name' => $realname,
                        'gender' => $this->switchSex($gender),
                        'birthday' => $birthday,
                        'signature' => $signature
                    ));
                    $back = $this->backJSON(array(
                        'code' => 200,
                        'msg' => '成功',
                        'info' => array(
                            'result' => 1,
                            'msg' => '操作成功,本次操作无头像上传！',
                            'avatar' => $headimg[0]->headimg
                        )
                    ));
                    return $back;
                }
                if ($profie == 1) {
                    $info = \App\UserInfo::where('user_id', '=', $userid)->update(array(
                        'nickname' => $nickname,
                        'real_name' => $realname,
                        'gender' => $this->switchSex($gender),
                        'birthday' => $birthday,
                        'signature' => $signature
                    ));
                    $back = $this->backJSON(array(
                        'code' => 200,
                        'msg' => '成功',
                        'info' => array(
                            'result' => 1,
                            'msg' => '操作成功,但是头像上传失败！',
                            'avatar' => $headimg[0]->headimg
                        )
                    ));
                    return $back;
                } else {
                    $info = \App\UserInfo::where('user_id', '=', $userid)->update(array(
                        'nickname' => $nickname,
                        'real_name' => $realname,
                        'gender' => $this->switchSex($gender),
                        'birthday' => $birthday,
                        'headimg' => $profie,
                        'signature' => $signature
                    ));
                    $back = $this->backJSON(array(
                        'code' => 200,
                        'msg' => '成功',
                        'info' => array(
                            'result' => 1,
                            'msg' => '修改成功',
                            'avatar' => $profie
                        )
                    ));
                    return $back;
                }
            }
            return $back = $this->backJSON(array(
                'code' => 500,
                'msg' => 'token验证失败',
                'info' => null
            ));
        } catch (Exception $e){
             $back = $this->backJSON(array(
                'code' => 500,
                'msg' => '失败',
                'info' => array(
                    'result' => 1,
                    'msg' => '操作失败'
                )
            ));
            return $back;
        }

    }

    /**
     * 上传图片
     * @param $file
     */
    public function uploadImgAsOne($file){
        if (!empty($_FILES [$file]['name'])) {
            $base_path = "uploads/"; //接收文件目录
            $target_path = $base_path.basename($_FILES [$file]['name']);
            $target=explode(".",$target_path,2);
            $target_path=$target[0].date('YmdHis',time()).".".$target[1];
            if(move_uploaded_file( $_FILES [$file] ['tmp_name'], $target_path )) {
                return $target_path;
            }else{
                return 1;
            }
        }
        return 2;
    }

    /**
     *查询用户是否已注册
     * @return mixed 返回结果json
     */
    public function isRegistered(){
        try{
            if (!empty($_REQUEST["username"])){
                $userName = $_REQUEST["username"];
                $email = \App\User::where('email', '=', $userName)->get();
                $phone = \App\User::where('phone', '=', $userName)->get();

                if ((count($email, 0) > 0) || (count($phone, 0) > 0)){
                    $back = $this->backJSON(array(
                        'code' => 200,
                        'msg' => '成功',
                        'info' => array(
                            'isRegistered' => '1'
                        )
                    ));
                    return $back;
                }
                $info['isRegistered'] = '1';
                $back = $this->backJSON(array(
                    'code' => 200,
                    'msg' => '成功',
                    'info' => array(
                        'isRegistered' => '1'
                    )
                ));
                return $back;
            }
            $back = $this->backJSON(array(
                'code' => 200,
                'msg' => '失败',
                'info' => array(
                    'isRegistered' => '0'
                )
            ));
            return $back;
        } catch(Exception $e) {
            $back = $this->backJSON(array(
                'code' => 500,
                'msg' => '失败',
                'info' => array(
                    'isRegistered' => '0'
                )
            ));
            return $back;
        }
    }

    public function PushEmail(){
        $back = array(
            'token' => '123456',
               'userid' => '123456',
               'cloudplatformid' => '123456'
        );
        $file = fopen('../public/uploads/' . 'deleteimg.txt',"w");
        fwrite($file, 'abcssd');//写入
        fclose($file);//关闭
        return $back;
    }



    /**
     * function: 执行添加操作，添加用户注册信息
     * create 2015.12.07
    **/
    public function insertRegister($uid, $app, $code) {
        $register = \App\Register::create(array(
                    'id' => $uid,
                    'user' => $app->{'username'},
                    'type' => $app->{'type'},
                    'app_type' => $app->{'apptype'},
                    'app_name' => $app->{'appname'},
                    'app_key' => $app->{'appkey'},
                    'platform_type' => $app->{'platform'},
                    'device_id' => $app->{'deviceid'},
                    'captcha' => $code,
                    'expire_time' => date("Y-m-d H:i:s",strtotime("+30 min")),
                    'init_time' => date('Y-m-d H:i:s', time()),
                    'update_time' => date('Y-m-d H:i:s', time()),
                ));
        return 1;
    }

    /**
     * function: 执行修改操作，修改用户注册信息
     * create 2015.12.07
    **/
    public function updateRegister($app, $code) {
        $register = \App\Register::where('user', '=', $app->{'username'})
                    ->update(array(
                    'type' => $app->{'type'},
                    'app_type' => $app->{'apptype'},
                    'app_name' => $app->{'appname'},
                    'app_key' => $app->{'appkey'},
                    'platform_type' => $app->{'platform'},
                    'device_id' => $app->{'deviceid'},
                    'captcha' => $code,
                    'expire_time' => date("Y-m-d H:i:s",strtotime("+30 min")),
                    'update_time' => date('Y-m-d H:i:s', time()),
                ));
        return 1;
    }

    /**
     * function: 查看是否存在该用户名的纪录
     * create 2015.12.07
    **/
    public function verifyRegister($app) {
        $result = \App\Register::where('user', '=', $app->{'username'})
                  ->where('app_type', $app->{'apptype'})
                  ->where('app_key', $app->{'appkey'})
                  ->where('platform_type', $app->{'platform'})
                  ->get();
        return count($result, 0) > 0 ? false : true;
    }

    /**
     * 注册发送验证码
     * @return string  返回执行结果
     */
    public function getAuthCode(){
        $app=json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            if ($this->verifyUser($app->{'type'}, $app->{'username'})) {
                $uuid = DB::SELECT("select uuid() as uuid");
                $code = rand(100000,999999);
                if ($app->{'type'} == 1){
                    $mobile = $this->registerForMobilePhone($app->{'username'}, $code);
                }else{
                    $email = $this->pushToEmail($app->{'username'}, $code);
                }
                $result = 0;
                //如果不存在该账号的记录，则新增。
                if ($this->verifyRegister($app)) {
                    $result = $this->insertRegister($uuid[0]['uuid'], $app, $code);
                } else {
                    //如果存在该账号记录，直接修改。
                    $result = $this->updateRegister($app, $code);
                }

                return $this->returnEndBack('200', '操作成功', array(
                        'result' => $result,
                        'uid' => $uuid[0]['uuid']
                    ));
            } else {
                //已存在相同账号的有效用户
                return $this->returnEndBack('200', '该用户已注册', array(
                        'result' => '-1',
                        'uid' => null
                    ));
            }
        } else {
            //数据不完整
            return $this->returnEndBack('200', '数据不完整！', array(
                        'result' => '0',
                        'uid' => null
                    ));
        }
         
    }

    /**
     * 修改版用户激活
     * @return string
     */
    public function doRegister(){
        $app=json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            $register = \App\Register::where('expire_time', '>', date('Y-m-d H:i:s', time()))->where('captcha', $app->{'authcode'})->where('user', $app->{'username'})->get();

            if (count($register) > 0){
                $registerStatus = \App\Register::where('id', '=', $register[0]->id)->update(array(
                    'remark' => '验证通过'
                ));
                $uuid = DB::SELECT("select uuid() as uuid");
                if ($register[0]->type == 1){
                    $user = \App\User::create(array(
                        'id' => $uuid[0]['uuid'],
                        'init_time' => date('Y-m-d H:i:s', time()),
                        'phone' => $app->{'username'},
                        'passwd' => $app->{'password'},
                    ));
                    $info = $this->initializeInfo($uuid[0]['uuid'], $app->{'nickname'});
                    $token = $this->initializeToken($uuid[0]['uuid'], $register[0]->apptype);
                    $device = $this->backUserDevice($uuid[0]['uuid'], $register[0]);
                    return $this->returnBackActivate($uuid[0]['uuid'], $token);
                }else{
                    $user = \App\User::create(array(
                        'id' => $uuid[0]['uuid'],
                        'init_time' => date('Y-m-d H:i:s', time()),
                        'email' => $app->{'username'},
                        'passwd' => $app->{'password'},
                    ));
                    $info = $this->initializeInfo($uuid[0]['uuid'], $app->{'nickname'});
                    $token = $this->initializeToken($uuid[0]['uuid'], $register[0]->apptype);
                    $device = $this->backUserDevice($uuid[0]['uuid'], $register[0]);
                    return $this->returnBackActivate($uuid[0]['uuid'], $token);
                }
            }
            return $back = $this->backJSON(array(
                'code' => '500',
                'info' => array(
                    'result' => 0,
                    'uid' => '',
                    'access_token' => '',
                    'msg' => '用户不存在',
                )
            ));
        }
        return $back = $this->backJSON(array(
            'code' => '500',
            'info' => array(
                'result' => 0,
                'uid' => '',
                'access_token' => '',
                'msg' => '操作失败,内容缺失',
            )
        ));
    }

    /**
     * 验证用户是否存在
     * @param $type 用户类型
     * @param $user 用户名
     * @return int 返回结果，0：未存在， 1：已存在
     */
    public function verifyUser($type, $user){
        $list = null;
        if ($type == 1){
            $list = \App\User::where('phone', '=', $user)->get();
        } else {
            $list = \App\User::where('email', '=', $user)->get();
        }
        return count($list, 0) > 0 ? false : true;
    }

    public function registerForMobilePhone($phone,$code){
        $key = env('SMS_SECRET_KEY');//手动新增`super_api`.`project_key` 表数据的secret_key 值
        $time = time();
        $datetime = date("Y-m-d H:i:s", $time);
        $data = array();
        $data['mobile'] = $phone;//要发送的手机号码
        $data['template_id'] = "BC04A904-CA1C-BE03-CCDB-433D52E6016E";//模板由云平台管理后台设置
        $data['timestamp'] = $time;//时间戳
        $data['sign'] = md5($data['template_id'] . $key . $time);//签名
        $data['vars'] = array("address" => "智能家居云平台", "result" => $code);
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


    /**
     * 用户激活
     * @return string
     */
    public function activate(){
        $app=json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            $register = \App\Register::where('expire_time', '>', date('Y-m-d H:i:s', time()))->where('captcha', $app->{'authcode'})->where('user', $app->{'username'})->get();
            if (count($register) > 0){
                $registerStatus = \App\Register::where('id', '=', $register[0]->id)->update(array(
                    'remark' => '验证通过'
                ));
                $uuid = DB::SELECT("select uuid() as uuid");
                if ($register[0]->type == 1){
                    $user = \App\User::create(array(
                        'id' => $uuid[0]['uuid'],
                        'init_time' => date('Y-m-d H:i:s', time()),
                        'phone' => $register[0]->user,
                        'passwd' => $register[0]->password,
                    ));
                   $info = $this->initializeInfo($uuid[0]['uuid'], $app->{'nickname'});
                   $token = $this->initializeToken($uuid[0]['uuid'], $register[0]->apptype);
                   $device = $this->backUserDevice($uuid[0]['uuid'], $register[0]);
                    return $this->returnBackActivate($uuid[0]['uuid'], $token);
                }else{
                    $user = \App\User::create(array(
                        'id' => $uuid[0]['uuid'],
                        'init_time' => date('Y-m-d H:i:s', time()),
                        'email' => $register[0]->user,
                        'passwd' => $register[0]->password,
                    ));
                    $info = $this->initializeInfo($uuid[0]['uuid'], $app->{'nickname'});
                    $token = $this->initializeToken($uuid[0]['uuid'], $register[0]->apptype);
                    $device = $this->backUserDevice($uuid[0]['uuid'], $register[0]);
                    return $this->returnBackActivate($uuid[0]['uuid'], $token);
                }
            }
            return $back = $this->backJSON(array(
                'code' => '500',
                'info' => array(
                    'result' => 0,
                    'uid' => ''
                )
            ));
        }
        return $back = $this->backJSON(array(
            'code' => '500',
            'msg' => '操作失败,内容缺失',
            'info' => array(
                'result' => 0,
                'uid' => ''
            )
        ));
    }

    /**
     * 用户设备录入
     * @param $uid  用户id
     * @param $userName 用户注册对象
     * @return static 执行结果
     */
    public function backUserDevice($uid, $userName){
        $userDevice = \App\UserDevice::create(array(
            'id' => $uid,
            'app_type' => $userName->app_type,
            'app_name' => $userName->app_name,
            'app_key' => $userName->app_key,
            'platform_type' => $userName->platform_type,
            'device_id' => $userName->device_id,
            //'push_id' => $userName->push_id,
            'init_time' => date('Y-m-d H:i:s', time()),
            'update_time' => date('Y-m-d H:i:s', time()),
            'remark' => $userName->remark
        ));
        return $userDevice;
    }

    /**返回成功状态码用户激活
     * @param $uid 用户id
     * @param $token ken值
     * @return string 返回结果
     */
    public function returnBackActivate($uid, $token){
        return $back = $this->backJSON(array(
            'code' => '200',
            'info' => array(
                'result' => 1,
                'uid' => $uid
                //,
//                'token' => $token,
//                'msg' => '操作成功',
            )
        ));
    }

    /**
     * 初始化生成用户token
     * @param $uid  用户id
     * @param $appType app类型
     * @return string  用户token
     */
    public function initializeToken($uid, $appType){
        $id = DB::SELECT("select uuid() as uuid");  //记录id
        $result = DB::SELECT("select uuid() as uuid"); //token验证码
        $token = \App\Token::create(array(
            'id' => $id[0]['uuid'],
            'type' => $appType,
            'user_id' => $uid,
            'token' => $result[0]['uuid'],
            'init_time' => date('Y-m-d H:i:s', time()),
            'end_time' => date("Y-m-d H:i:s",strtotime("+1 day"))
        ));
        return $result;
    }

    /**
     * 初始化用户详情
     * @param $uid  用户id
     * @return static
     */
    public function initializeInfo($uid, $nickName){
        $info = DB::SELECT("select uuid() as uuid");
        return $userInfo = \App\UserInfo::create(array(
            'id' => $info[0]['uuid'],
            'user_id' => $uid,
            'nickname' => $nickName,
            'real_name' => ' ',
            'birthday' => date('Y-m-d H:i:s', time()),
            'problem_first' => ' ',
            'problem_second' => ' ',
            'problem_third' => ' ',
            'answer_first' => ' ',
            'answer_second' => ' ',
            'headimg' => 'http://192.168.100.165:88/20150706.jpg',
            'answer_third' => ' '
        ));
    }

    /**
     * 用户登录
     */
    public function login(){
        $app=json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        $appType = 1;
        if (!empty($app)){
            $user = \App\User::where('passwd', '=', $app->{'password'})->get();
            if (count($user, 0) > 0){
                $userId = '';
                $status = 0;
                $code = 0;
                for ($i = 0, $y = count($user, 0); $i < $y; $i++){
                    if ((($user[$i]->phone == $app->{'username'}) && (!empty($app->{'username'})))
                        || (($user[$i]->email == $app->{'username'}) && (!empty($app->{'username'})))){
                        $status = 1;
                        $userId = $user[$i]->id;
                        $code = $i;
                    }
                }
                if ($status != 0){
                    $register = \App\UserDevice::find($userId);
//                    if (!empty($register)){
                        $appType = $register->app_type;
                        $token = $this->loginToken($userId, $appType);
                        $info = \App\UserInfo::where('user_id', '=', $userId)->get();
                        if (count($info, 0) > 0){
                            return $back = $this->backJSON(array(
                                'code' => '200',
                                'info' => array(
                                    'result' => 1,
                                    'uid' => $userId,
                                    'nickname' => $info[$code]->nickname,
                                    'avatar' => '',
                                    'access_token' => $token,
                                    'msg' => '操作成功',
                                )
                            ));
                        }
                        return $back = $this->backJSON(array(
                            'code' => '200',
                            'info' => array(
                                'result' => 1,
                                'uid' => $userId,
                                'nickname' => '',
                                'avatar' => '',
                                'access_token' => $token,
                                'msg' => '操作成功',
                            )
                        ));
//                    }
                }
                return $this->backWrongLogin('用户名错误');
            }return $this->backWrongLogin('密码错误');
        }return $this->backWrongLogin('未接收到数据');
    }

    /**登录错误返回结果
     * @return string 返回错误登录提示loginToken
     */
    public function backWrongLogin($msg){
        return $back = $this->backJSON(array(
            'code' => '500',
            'msg' => $msg,
            'info' => array(
                'result' => 0,
                'uid' => '',
                'nickname' => '',
                'avatar' => '',
                'access_token' => ''
            )
        ));
    }

    /**
     * 登录时生成token
     * @param $uid 用户id
     * @param $appType app类型
     * @return string  返回的结果
     */
    public function loginToken($uid, $appType){
        $result = DB::SELECT("select uuid() as uuid");
        $list = \App\Token::where('user_id', '=', $uid)->get();
        if (count($list, 0) > 0){
            $token = \App\Token::where('user_id', '=', $uid)->update(array(
                'token' => $result[0]['uuid'],
                'end_time' => date("Y-m-d H:i:s",strtotime("+1 day"))
            ));
            return $result[0]['uuid'];
        }
        $id = DB::SELECT("select uuid() as uuid");
        $token = \App\Token::create(array(
            'id' => $id[0]['uuid'],
            'type' => $appType,
            'user_id' => $uid,
            'token' => $result[0]['uuid'],
            'init_time' => date('Y-m-d H:i:s', time()),
            'end_time' => date("Y-m-d H:i:s",strtotime("+1 day"))
        ));
        return $result[0]['uuid'];
    }


    /**
     * 转换男女字符串
     * @param $sex
     */
    public function switchSex($sex) {
        $result = 0;
        switch ($sex) {
            case '男':
                $result = '0';
                break;
            case '女':
                $result = '1';
                break;
            case '0':
                $result = '男';
                break;
            case '1':
                $result = '女';
                break;
        }
        return $result;
    }

    /**
     * 第三方注册
     */
    public function registerOAuth($platform, $openid, $nickname, $avatar, $gender, $appid, $platformid, $device){
        $uuid = DB::SELECT("select uuid() as uuid");

           $userLogin = \App\User::create(array(
               'id' => $uuid[0]['uuid'],
               'init_time' => date('Y-m-d H:i:s', time())
           ));
           $userInfo = \App\UserInfo::create(array(
                'id' => $uuid[0]['uuid'],
                'user_id' => $uuid[0]['uuid'],
                'nickname' => $nickname,
                'headimg' => $avatar,
                'gender' => $this->switchSex($gender)
//                'birthday' => $app->{'birthday'}
           ));
           $third = $this->fillThird($platform, $uuid[0]['uuid'], $openid, $appid, $platformid, $device, $nickname);
           $thirdPar = \App\ThirdParties::create($third);
            $token = $this->ThirdToken($uuid[0]['uuid'], $device, $appid);
            return $back = $this->backJSON(array(
                'code' => '200',
                'info' => array(
                    'result' => 1,
                    'uid' => $uuid[0]['uuid'],
                    'nickname' => $nickname,
                    'avatar' => $avatar,
                    'accesstoken' => $token
                )
            ));
    }

    /**
     * 填充第三方记录表
     * @param $type  第三方类型
     * @param $uid   用户id
     * @param $code 第三方uid
     */
    public function fillThird($type, $uid, $code, $app, $platform, $device, $nickname) {
        $insertArray = array();
        $id = DB::SELECT("select uuid() as uuid");
        $insertArray = array(
            'id' => $id[0]['uuid'],
            'user_id' => $uid,
            'app_id' => $app,
            'platform' => $platform,
            'device_id' => $device,
            'third_id' => $code,
            'third_type' => $type,
            'third_name' => $nickname,
            'init_time' => date('Y-m-d H:i:s', time()),
            'update_time' => date('Y-m-d H:i:s', time())
        );
        return $insertArray;
    }


    /**
     * 根据第三方登录接口生成token并返回。
     * @param $uid 用户id
     * @param $device 设备id
     * @param $appId appid
     * @return mixed 返回token
     */
    public function ThirdToken($uid, $device, $appId) {
        /*测试数据写入文件
        $file = fopen('../public/uploads/' . 'token.txt',"w");
        fwrite($file, $uid.'--apptype--'.$appType.'--token--'.$token.'--device--'.$device.'--appid--'.$appId);//写入
        fclose($file);//关闭
        */
        $list = \App\Token::where('user_id', '=', $uid)->where('type', $appId)->get();
        $uuid = DB::SELECT("select uuid() as uuid");
        if (count($list, 0) > 0){
            $token = \App\Token::where('user_id', '=', $uid)->where('type', $appId)->update(array(
                'token' => $uuid[0]['uuid'],
                'device_id' => $device,
                'end_time' => date("Y-m-d H:i:s",strtotime("+1 day"))
            ));
            return $uuid[0]['uuid'];
        }
        $token = \App\Token::create(array(
            'id' => $uuid[0]['uuid'],
            'type' => $appId,
            'user_id' => $uid,
            'token' => $uuid[0]['uuid'],
            'device_id' => $device,
            'init_time' => date('Y-m-d H:i:s', time()),
            'update_time' => date('Y-m-d H:i:s', time()),
            'end_time' => date("Y-m-d H:i:s",strtotime("+1 day"))
        ));
        return $uuid[0]['uuid'];
    }

    /**
     * 第三方登录
     * 2015/08/10 待改善流程
     */
    public function oauthActivate(){
        $app=json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            $third = $this->selectThird($app->{'platformid'}, $app->{'openid'});
            if (!empty($third)) {
                $user = \App\UserInfo::where('user_id', '=', $third)->get();
                if (!empty($user)) {
                    $token = $this->ThirdToken($third, $app->{'deviceid'}, $app->{'appid'});
                    return $back = $this->backJSON(array(
                        "code" => 200,
                        'exist' => 1,
                        "info" => array(
                            "result" => 1,
                            "uid" => $third,
                            "avatar" => $user[0]->headimg,
                            "nickname" => $user[0]->nickname,
                            'accesstoken' => $token
                        )
                    ));
                }
            } else {
                return $back = $this->registerOAuth($app->{'platformid'}, $app->{'openid'}, $app->{'nickname'},
                $app->{'avatar'}, $app->{'gender'}, $app->{'appid'}, $app->{'platform'}, $app->{'deviceid'});
            }
        }
        return $back = $this->backJSON(array(
            "code" => 500,
            'exist' => 2,
            "info" => null
        ));
    }


    /**
     * 查重第三方登录
     * @param $type
     * @param $code
     * @return string
     */
    public function selectThird($type, $code) {
        $third = \App\ThirdParties::where('third_type', '=', $type)->where('third_id', $code)->get();
        return count($third, 0) > 0 ? $third[0]->user_id : null;
    }

    /**
     * 根据用户id返回用户第三方昵称
     * @param $id 用户id
     * @return array 返回第三方昵称，键值0：qq; 1：微信； 2：微博
     */
    public function BackThirdParties($id) {
        $result = array();
        for ($i = 0; $i < 3; $i++) {
            $list = \App\ThirdParties::where('third_type', '=', ($i+1))->where('user_id', $id)->get();
            if (count($list, 0) > 0) {
                $result[$i] = $list[0]->third_name;
            } else {
                $result[$i] = '';
            }
        }
        return $result;
    }

    /**
     * 获取用户信息
     * @return string
     */
    public function userProfile() {
        $app=json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            if ($this->verifyToken($app->{'userid'}, $app->{'accesstoken'})) {
                $user = \App\User::find($app->{'userid'});
                $info = \App\UserInfo::where('user_id', '=', $app->{'userid'})->get();
                if ((!empty($user)) && (count($info, 0) > 0)) {
                    $third = $this->BackThirdParties($app->{'userid'});
                    //如果存在第三方注册信息
                        return $back = $this->backJSON(array(
                            'code' => 200,
                            'msg' => '操作成功',
                            'info' => array(
                                'result' => 1,
                                'userid' => $app->{'userid'},
                                'phone' => $user->phone,
                                'email' => $user->email,
                                'weixin' => $third[1],
                                'weibo' => $third[2],
                                'qq' => $third[0],
                                'nickname' => $info[0]->nickname,
                                'avatar' => $info[0]->headimg,
                                'birthday' => strtotime($info[0]->birthday),
                                'gender' => $this->switchSex($info[0]->gender),
                                'signature' => $info[0]->signature,
                                'realname' => $info[0]->real_name
                            )
                        ));

                }
                return $back = $this->backJSON(array(
                    'code' => 500,
                    'info' => array(
                        'result' => 0,
                        'msg' => '不存在的用户'
                    )
                ));
            }
            return $back = $this->backJSON(array(
                'code' => 500,
                'msg' => 'token验证失败',
                'info' => null
            ));
        }
        return $back = $this->backJSON(array(
            'code' => 500,
            'info' => array(
                'result' => 0,
                'msg' => '未收到确认数据'
            )
        ));
    }

    /**
     * 组件最终返回结果格式
     * @param $code 状态码
     * @param $msg 文字说明
     * @param $info 结果数组
     * @return array 指定格式数组
     */
    public function returnEndBack($code, $msg, $info) {
           $result = $this->backJSON(array(
            'code'   => $code,
            'msg'    => $msg,
            'info'    => $info
            ));
        return $result;
    }

    /**
     * 测试发送邮件
     * 2015-05-26 测试成功
     * 用户发送邮箱验证码
     */
    public function pushToEmail($email, $captcha){
        $key = env('SMS_SECRET_KEY'); //手动新增`super_api`.`project_key` 表数据的secret_key 值
        $time = time();
        $datetime = date("Y-m-d H:i:s", $time);
        $data = array();
        $data['email'] = $email;//要发送的邮件地址
        $data['template_id'] = "95B53891-80A1-C363-A8EC-5C968944BBC2";//模板由云平台管理后台设置
        $data['timestamp'] = $time;
        $data['sign'] = md5($data['template_id'] . $key . $time);
        $data['vars'] = array("address" => "智能家居云平台",  "result" => $captcha);//date,userName
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

    public function getEnv() {
        $key = env('SMS_SECRET_KEY');
        echo $key;
    }

}