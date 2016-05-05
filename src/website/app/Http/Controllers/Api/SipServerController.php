<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SipServerController extends \App\Http\Controllers\Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function testMsgToSipServer()
    {
          // $url = 'http://192.168.1.19:5060/sip/return';
          $url = 'http://127.0.0.1/sip/return';
          
          $post_data = $this->backJSON(array(
                'type' => '3',
                'cmd' => '110011101111',
                'userid' => 'xiaohuahua',
                'token' => 'lisa',
                'gatewayid' => '1006'
                ));
          $ch = curl_init($url);                                                                        
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                       
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);                                                                    
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                        
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                            
              'Content-Type: application/json',                                                                                  
              'Content-Length: ' . strlen($post_data))                                                                         
          );  
              //对空格进行转义
            // $url = str_replace(' ','+',$url);
            // $ch = curl_init();
            // //设置选项，包括URL
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_HEADER, 0);
            // curl_setopt($ch,CURLOPT_TIMEOUT,3);  //定义超时3秒钟  
            //  // POST数据
            // curl_setopt($ch, CURLOPT_POST, 1);
            // // 把post的变量加上
            // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));    //所需传的数组用http_bulid_query()函数处理一下，就ok了
            
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
           
            // return $output;
    }

    public function backCurlPost() {
        $data = json_decode(file_get_contents('php://input'));
        var_dump($data);
        return '122321';
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
