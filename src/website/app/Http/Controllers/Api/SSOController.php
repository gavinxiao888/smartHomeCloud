<?php namespace App\Http\Controllers\Api;
/************************************************************
   Copyright (C), 2015-2016, Everyoo Tech. Co., Ltd.
   FileName: SSOController.php
   代码狂魔，凡人勿动！勇士，乐观的接受现实吧！
   User: Squirrel  Version :1.0.0  Date: 2015/6/23
 *************************************************************/

use DB;
use View;
class SSOController extends \App\Http\Controllers\Controller{


    /**
     * 根据用户id 设备id 录入token
     * @return string
     */
    public function inputTokenForDevice() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            $token = \App\Token::where('user_id', '=', $app->{'userid'})->where('token', '=', $app->{'accesstoken'})->update(array(
                'update_time' => date('Y-m-d H:i:s', time()),
                'device_id' => $app->{'device'},
            ));
            return $back = $this->backJSON(array(
                'code' => '200',
                'info' => array(
                    'result' => 1,
                    'msg' => '操作成功'
                )
            ));
        }
        return $back = $this->backJSON(array(
            'code' => '500',
            'info' => array(
                'result' => 0,
                'msg' => '未获取到数据'
            )
        ));
    }


    /**
     * 根据用户提供的用户id和设备id获取用户当下token。
     * @return string
     */
    public function selectTokenForDevice() {
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)) {
            $token = \App\Token::where('user_id', '=', $app->{'userid'})->where('device_id', '=', $app->{'device'})->get();
            $profile = \App\UserInfo::where('user_id', '=', $app->{'userid'})->get();
            if ((count($token, 0) > 0) && (count($profile, 0) > 0) ){
                return $back = $this->backJSON(array(
                    'code' => '200',
                    'info' => array(
                        'result' => 1,
                        'avatar' => $profile[0]->headimg,
                        'nickname' => $profile[0]->nickname,
                        'accesstoken' => $token[0]->token
                    )
                ));
            }
        }
        return $back = $this->backJSON(array(
            'code' => '500',
            'info' => array(
                'result' => 0,
                'msg' => '未获取到数据'
            )
        ));
    }

    /**
     * 根据设备id获取相应的sip帐号
     * update 2015.10.21
     */
    public function UserSipCode($uid, $type) {
        $result = $this->verificationSipCode($uid, $type);
        if ($result == null) {
            $result = $this->createSipCode($uid, $type);
            $result = $this->verificationSipCode($uid, $type);
        }
        return $result;
    }

    /**
     * 查询是否存在该device对应的sip帐号
     * update 2015.10.21
     */
    public function verificationSipCode($uid, $type) {
        $result = null;
        $sip = \App\Subscriber::where('username', '=', $uid)->get();
        if (count($sip, 0) > 0) {
            $result = array(
                'username' => $sip[0]->username,
                'password' => $sip[0]->password,
                'sipaddress' => $sip[0]->domain
                );
        }
        return $result;
    }

    /**
     * 创建sip帐号
     * create 2015.10.21
     */
    public function createSipCode($uid, $type) {
        $result = null;
        $sip = \App\Subscriber::create(array(
            'username' => $uid,
            'password' => md5($uid.time())
            ));
        return $result;
    }

    /**
     * 用户登录
     */
    public function login(){
        $app = json_decode(file_get_contents('php://input'));$this->log($app, $_SERVER['REQUEST_URI'], __FILE__, __FUNCTION__, __LINE__);
        if (!empty($app)){
            $token = array();
            if( !preg_match("/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i", $app->{'username'}) ){
                $token = $this->selectState($app->{'username'}, $app->{'password'}, env('LOGIN_PHONE_SP'));// 'producesmarthomecloudPhone-sp'
            }else{
                $token = $this->selectState($app->{'username'}, $app->{'password'}, env('LOGIN_EMAIL_SP'));//'producesmarthomecloud-sp'
            }

            if ($token != null) {
                    $register = \App\UserDevice::find($token['user'][0]);
                    if (!empty($register)){
                    $appType = $register->app_type;
                    $tokenFill = $this->loginToken($token['user'][0], $appType, $token['token'], $app->{'deviceid'}, $app->{'appid'}, $app->{'platform'});
                    $info = \App\UserInfo::where('user_id', '=', $token['user'][0])->get();
                    $subSip = $this->UserSipCode($token['user'][0], '2');
                    if (count($info, 0) > 0){
                        return $back = $this->backJSON(array(
                            'code' => '200',
                            'info' => array(
                                'result' => 1,
                                'uid' => $token['user'][0],
                                'nickname' => $info[0]->nickname,
                                'avatar' => $info[0]->headimg,
                                'accesstoken' => $token['token'],
                                'sipuid' => $subSip['username'],
                                'sippwd' => $subSip['password'],
                                'sipaddress' => $subSip['sipaddress'],
                                'msg' => '操作成功',
                            )
                        ));
                    }
                    return $back = $this->backJSON(array(
                        'code' => '200',
                        'info' => array(
                            'result' => 1,
                            'uid' => $token['user'][0],
                            'nickname' => '',
                            'avatar' => '',
                            'accesstoken' => $token['token'],
                            'sipuid' => $subSip['username'],
                            'sippwd' => $subSip['password'],
                            'sipaddress' => $subSip['sipaddress'],
                            'msg' => '操作成功',
                        )
                    ));
                    }
                } else {
                    return $this->backWrongLogin('用户名或密码错误');
                }
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
                'accesstoken' => ''
            )
        ));
    }


    /**
     * 登录时生成token
     * @param $uid 用户id
     * @param $appType app类型
     * @return string  返回的结果
     */
    public function loginToken($uid, $appType, $token, $device, $appId, $platform){
        /*测试数据写入文件
        $file = fopen('../public/uploads/' . 'token.txt',"w");
        fwrite($file, $uid.'--apptype--'.$appType.'--token--'.$token.'--device--'.$device.'--appid--'.$appId);//写入
        fclose($file);//关闭
        */
        $list = \App\Token::where('user_id', '=', $uid)->where('type', $appId)->get();
        if (count($list, 0) > 0){
            $token = \App\Token::where('user_id', '=', $uid)->where('type', $appId)->update(array(
                'token' => $token,
                'device_id' => $device,
                'end_time' => date("Y-m-d H:i:s",strtotime("+1 day")),
                'remark' => $platform
            ));
            return $token;
        }
        $id = DB::SELECT("select uuid() as uuid");
        $token = \App\Token::create(array(
            'id' => $id[0]['uuid'],
            'type' => $appId,
            'user_id' => $uid,
            'token' => $token,
            'device_id' => $device,
            'init_time' => date('Y-m-d H:i:s', time()),
            'end_time' => date("Y-m-d H:i:s",strtotime("+1 day")),
            'remark' => $platform
        ));
        return $token;
    }
    /**
     * 登录，并获取登录返回token。
     * @param $userName 用户名
     * @param $passWord 密码
     * @return null 登录失败则返回空，成功返回token;
     */
    public function selectState($userName, $passWord, $sp){
        try {
            $cookie_jar = tempnam('./tmp','cookie');
            /**
             * 获取提交需要的AuthState参数 和post指向的链接
             */
            $url=env('LOGIN_HTTP').'module.php/core/authenticate.php?as='.$sp;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            //设置头文件的信息作为数据流输出
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar); //设定返回的数据是否自动显示
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            //执行命令
            $data = curl_exec($curl);
            curl_close($curl);
            $para = explode('AuthState=', $data);
            $content = explode('">',$para[2]);// 第一个是 AuthState参数，第二个是post指向的url
            $back = explode('&',urldecode($content[0]));
            $post_jar = tempnam('./tmp','cookie');
            $post_data = 'username='.$userName.'&password='.$passWord.'&origin=cloud&AuthState='.$back[0].'%26'.urlencode($back[1]);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $content[1]);//'http://192.168.22.33/app/login/back');

            curl_setopt($ch, CURLOPT_COOKIEJAR, $post_jar); //设定返回的数据是否自动显示
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //1:关闭返回参数; 0:开启返回参数。
            $result = curl_exec($ch);
            curl_close($ch);
            if (!empty($post_jar)){
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                //设置头文件的信息作为数据流输出
                curl_setopt($curl, CURLOPT_HEADER, 1);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $post_jar);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar); //设定返回的数据是否自动显示
                //设置获取的信息以文件流的形式返回，而不是直接输出。
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                //执行命令
                $end = curl_exec($curl);
                curl_close($curl);
                $list = explode('<td class="attrname"><tt>', $end);
                $result = array();
                for ($i = 1, $y = count($list, 0); $i < $y; $i++) {
                    $result[$i-1] = $this->splitData($list[$i]);
                }
//                var_dump($result);
                $endCookie =  file_get_contents($post_jar);
                $end = explode('impleSAMLAuthToken', $endCookie);
                if (count($end, 0) > 1) {
                    $impleSAMLAuthToken = explode('  ', $end[1]);
                    $backData = array(
                        'token' => $this->wipeOutChar($impleSAMLAuthToken[0]),
                        'user' => $result
                    );
                    return $backData;
                }
            }
            return null;
        }catch (Exception $e){
            return null;
        }
    }

    public function selectTry(){
        $userName = '1';
        $passWord = 'c4ca4238a0b923820dcc509a6f75849b';
        $kinds = 'platform-sp';
        $cookie_jar = tempnam('./tmp','cookie');
        /**
         * 获取提交需要的AuthState参数 和post指向的链接
         */
        $url='http://192.168.100.165:89/module.php/core/authenticate.php?as='.$kinds;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar); //设定返回的数据是否自动显示
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        curl_close($curl);
        $para = explode('AuthState=', $data);
        $content = explode('">',$para[2]);// 第一个是 AuthState参数，第二个是post指向的url
        $back = explode('&',urldecode($content[0]));
        $post_jar = tempnam('./tmp','cookie');
        $post_data = 'username='.$userName.'&password='.$passWord.'&origin=cloud&AuthState='.$back[0].'%26'.urlencode($back[1]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $content[1]);//'http://192.168.22.33/app/login/back');

        curl_setopt($ch, CURLOPT_COOKIEJAR, $post_jar); //设定返回的数据是否自动显示
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //1:关闭返回参数; 0:开启返回参数。
        $result = curl_exec($ch);
        curl_close($ch);
        if (!empty($post_jar)){
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            //设置头文件的信息作为数据流输出
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $post_jar);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar); //设定返回的数据是否自动显示
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            //执行命令
            $end = curl_exec($curl);
            curl_close($curl);
            $list = explode('<td class="attrname"><tt>', $end);
            $result = array();
            for ($i = 1, $y = count($list, 0); $i < $y; $i++) {
                $result[$i-1] = $this->splitData($list[$i]);
            }
            $endCookie =  file_get_contents($post_jar);
            $end = explode('impleSAMLAuthToken', $endCookie);
            $impleSAMLAuthToken = explode('  ', $end[1]);
             $backData = array(
               'token' => $this->wipeOutChar($impleSAMLAuthToken[0]),
               'user' => $result
             );
            return $backData;

        }
        return '1';
    }

    /**
     * 去掉字符串中的换行 及制表位；
     * @param $result 要处理的字符串
     * @return mixed 处理结束后的字符串
     */
    public function wipeOutChar($result) {
        $arr= array("\n", "\r", "\r\n", "\t");
        $str=str_replace($arr,"",$result);
        return $str;
    }

    /**
     * 拆分字符串
     * @param $content
     * @return string
     */
    public function splitData($content){
        $result = '';
        $para = explode('<td class="attrvalue">', $content);
        $end = explode('</td>', $para[1]);
        $result = $end[0];
        return $result;
    }



    /**
     * squirrel  2015-06-30 16:57
     * 模拟登录fly论坛；
     */
    public function goFlyLay(){
        $cookie_jar = tempnam('./tmp','cookie');
        $url='http://fly.layui.com/login/';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);

        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar); //设定返回的数据是否自动显示
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        $para = explode('Cookie:', $data);
        $cookie = explode(' ', $para[1]);
        var_dump($data);
//        var_dump($cookie[1]);
//        print_r($data);
        curl_close($curl);
        die();



        $post_data = 'email=SquirrelNo1@163.com&pass=shiJIE092&vercode=男';
        $url = 'http://fly.layui.com/login/';
        $cookie = 'connect.sid=s%3Ady3NBMN4igw_q5Ve6qjvW4tOYW5SLWLl.IN5YClPQX%2BzBsDGdvPNNteW22sBtotiBWd66kl5tk%2B0';
        var_dump($post_data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);//'http://192.168.22.33/app/login/back');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);  //1:关闭返回参数; 0:开启返回参数。
        $result = curl_exec($ch);
        curl_close($ch);
    }


    /**
     * 阴天雨推荐的方法； qq:
     * @param $lan
     * @param int $id
     * @return mixed
     */
    function postCookie($lan, $id = 0){
        $url = "https://mp.weixin.qq.com/cgi-bin/getregions?t=setting/ajax-getregions&id=$id&token=318051268&lang=$lan&f=json&ajax=1&random=0.7641997353140674
";
        $weixin_cookie = 'remember_acct=jason.shen@d1m.cn; data_bizuin=3077305833; data_ticket=AgXVlUXYA6Rjx0j6Z3btOeUe; slave_user=gh_f5ace024836b; slave_sid=YXBqR01KWmhpQXN0UmQzVEVnbnRLZW80RHdEY3ZXVUJNaXRqOF9Ub3FWY2JCcW9WMFlfOEh4X2NQdUhqY0QyRjZMT3A3dktMUXp6bFNKNkNPaGZEZFVqemtOTlFGMGx0MWphVFkyNURpcmlMS2t6bnZoK3BIanVJZkpRTVFqWVQ=; bizuin=3081370519';
        $ch = curl_init($url);
        $curl_opt = array(
            CURLOPT_POST => false,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_ENCODING => 'gzip',
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0",
            CURLOPT_COOKIE => $weixin_cookie,
        );
        curl_setopt_array($ch, $curl_opt);

        $result = curl_exec($ch);
        $data = json_decode($result);
        if(!isset($data->data))
            die('timeout! pid: ' . $id);

        $data = $data->data;
        curl_close($ch);

        return $data;
    }


}