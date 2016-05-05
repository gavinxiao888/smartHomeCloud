<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use DB;
use View;
use Cache;
use Events;
use Requests;
use Mail;
use App\Http\Controllers\Controller;
use Carbon;

class WebController extends \App\Http\Controllers\Controller
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
     *跳转到忘记密码页面
     *create. 2015.12.09
    **/
    public function goFindPassViewFirst() {
        return view('web.findPassFirst');
    }

    /**
     *跳转到忘记密码页面第二页
     *create. 2015.12.09
    **/
    public function goFindPassViewSecond() {
        if ($_REQUEST['code']) {
            if (Cache::has($_REQUEST['code'])) {
                return view('web.findPassSecond')->with('code', $_REQUEST['code']);
            }
        }
        return view('web.findPassFirst');
    }


    /**
     *POST:修改密码
     *create. 2015.12.09
    **/
    public function editPass() {
        if ((!empty($_REQUEST['user'])) && (!empty($_REQUEST['pass'])) && (!empty($_REQUEST['code']))) {
            if ($this->verificationCode($_REQUEST['user'], $_REQUEST['code'])) {
                $user = \App\User::find($_REQUEST['user']);

                if (count($user, 0) > 0) {
                     $pass = md5($_REQUEST['pass']);
                     $result = $this->updatePassword($user->id, $pass);
                     $expiresAt = Carbon::now()->addMinutes(30);
                     $pass = md5($user->id.$pass.time());
                     Cache::put($pass, '1', $expiresAt);
                     return $pass; 
                 }else {
                     // 用户不存在
                     return 2;
                  }
            //验证码不正确。
            } else {
                return 3;
            }
        
        }
        // 数据不完整
        return 4;
    }

     /**
     *function:验证码是否有效
     *create. 2015.12.09
    **/
    public function verificationCode($user, $code) {
        if (Cache::has($user)) {
            $userCode = Cache::get($user);
            return $userCode == $code ? true : false;
        }
        return false;
    }

    /**
     *function:执行修改密码操作
     *create. 2015.12.09
    **/
    public function updatePassword($user, $pass) {
        $result = \App\User::where('id', '=', $user)
                  ->update(array(
                    'passwd' => $pass
                    ));
        return $result;
    }

    /**
     *跳转到忘记密码页面第三页
     *create. 2015.12.09
    **/
    public function goFindPassViewThird() {
        if ($_REQUEST['code']) {
            if (Cache::has($_REQUEST['code'])) {
                return view('web.findPassThird');
            }
        }
        return view('web.findPassFirst');
    }

    /**
     *跳转到推送页面
     *2015.09.24
    **/
    public function pushViewsShow() {
        return view('Web.pushMessage');
    }

    /**
     *处理推送请求
     *2015.09.28
    **/
    public function pushMessage() {
        // return $_REQUEST['type'].$_REQUEST['content'].$_REQUEST['title'];
        if ((!empty($_REQUEST['type'])) && (!empty($_REQUEST['content'])) 
            && (!empty($_REQUEST['title']))) {

            //根据类型决定使用方法 1:手机； 2:邮箱； 3:app；
            switch ($_REQUEST['type']) {
                case 1:
                    $resulst = $this->pushForPhoneFunction('13054579207', '123121131');
                    // $this->pushForPhone($_REQUEST['content']);
                    break;
                case 2:
                    $resulst = $this->pushEmailFunction('1352879857@qq.com', $_REQUEST['content'], $_REQUEST['title']);
                    // $this->pushForEmail($_REQUEST['content']);
                    break;
                case 3:
                    break;
            }
            return $resulst;
        }
        return 2;
    }

    /**
     *处理手机短信推送请求
     *2015.09.28  暂为所有用户推送
     * $content:发送内容
    **/
    public function pushForPhone($content) {
       $list = \App\User::all();
        foreach ($list as $user) {
            # code...
            if (!empty($user->phone)) {
                $this->pushForPhoneFunction($user->phone, $content);
            }
        }
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
                $this->pushEmailFunction($user->email, array('0' => $content), $title);
            }
        }
        return 1;
        
    }


    /**
     *处理邮箱推送请求 
     *2015.09.28
     * $email:邮箱用户
     * $content:推送内容
    **/
    public function pushEmailFunction($email, $data, $title) {

         Mail::send('email.pushEmail', ['content' => $data], function ($m) use ($email) {
            $m->to($email, 'John Smith')->subject('sucess');
        });
        //  Mail::queue('email.pushEmail', $data, function($message) use ($email){
        //     $message->to($email, 'John Smith')->subject($title);
        // });
    }

    /**
     *处理手机短信推送请求
     *2015.09.28  
     * $phone:手机号
     * $content:短信内容
    **/
    public function pushForPhoneFunction($phone, $content) {
        $post_data = array();
        $post_data['account'] = iconv('GB2312', 'GB2312', "Zhsh888");// env('PHONE_USER'));
        $post_data['pswd'] = iconv('GB2312', 'GB2312', "Zhsh888888");// env('PHONE_PASS'));
        $post_data['mobile'] = $phone;
        $post_data['msg']=mb_convert_encoding($content,'UTF-8','auto');
        $url='http://222.73.117.158/msg/HttpBatchSendSM?';
        $o="";
        foreach ($post_data as $k=>$v)
        {
            $o.= "$k=".urlencode($v)."&";
        }
        $post_data=substr($o,0,-1);
        // return $url.$post_data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //1:关闭返回参数; 0:开启返回参数。
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     *跳转到登陆后的个人主页面
     *2015.09.25
    **/
    public function indexViewsShow() {
        return view('Web.index');
    }

    /**
     *POST: 找回密码发送验证码
     *create.2015.12.09
     * return 1:验证码发送成功, 2:验证码发送失败, 3:无此用户, 4:非法用户,
    **/
    public function userPushCode() {
        if ((!empty($_REQUEST['type'])) && (!empty($_REQUEST['user']))) {
            if ($this->verificationCheckCode($_REQUEST['code'])) {
                //验证码错误
                return 5;
            }
            $verUser = $this->verificationUser($_REQUEST['user'], $_REQUEST['type']);
            if (!empty($verUser)) {
                $code = rand(100000,999999);
                $pushCode = $this->pushCode($_REQUEST['user'], $_REQUEST['type'], $code);
                if ($pushCode == 'SUCCESS') {
                    // 验证码发送成功
                    $expiresAt = Carbon::now()->addMinutes(30);
                    Cache::put($verUser, $code, $expiresAt);
                    return $verUser;
                } else {
                    // 验证码发送失败
                    return 2;
                }
            } else {
                // 不存在该用户
                return 3;
            }
        } else {
            // 非法用户
            return 4;
        }
    }

    /**
     *funciont: 验证是验证码是否有效
     *create.2015.12.09
    **/
    public function verificationCheckCode($code) {
        // var_dump($_SESSION['verification']);
        return $_SESSION['verification'] == md5($code) ? false : true;
    }

    /**
     *funciont: 验证是否存在该用户
     *create.2015.12.09
    **/
    public function verificationUser($user, $type) {
        $result = null;
        switch ($type) {
            case '1':
                //手机
                $result = \App\User::where('phone', '=', $user)
                          ->get();
                break;
            
            case '2':
                //邮箱
                $result = \App\User::where('email', '=', $user)
                          ->get();
                break;
        }
        return count($result, 0) > 0 ? $result[0]->id : null;
    }

    /**
     *funciont: 发送手机验证码
     *create.2015.12.09
    **/
    public function pushCode($user, $type, $code) {
        $result = null;
        switch ($type) {
               case '1':
                //手机
                $result = $this->pushCodeForPhone($user, $code);
                break;
            
            case '2':
                //邮箱
                $result = $this->pushCodeForEmail($user, $code);
                break;
        }
        return $result;
    }

    /**
     *funciont: 发送手机验证码
     *create.2015.12.09
    **/
    public function pushCodeForPhone($phone,$code){
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
     *funciont: 发送邮箱验证码
     *create.2015.12.09
    **/
    public function pushCodeForEmail($email, $captcha){
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
