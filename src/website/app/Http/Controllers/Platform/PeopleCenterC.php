<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/13
 * Time: 13:22
 * @power 用户个人中心
 *
 */
namespace App\Http\Controllers\Platform;
use DB;
use View;
class PeopleCenterC extends \App\Http\Controllers\Controller
{
    public function platform_show_center()//显示个人中心的页面
    {
        //@session_start();
       // var_dump($_SESSION);
        return View::make('platform.personalcenter.PersonalCenter')->with('info', $_SESSION['user']['user_info']);
    }
	public function view_personal_center()//显示我的个人中心页面
	{
		//var_dump('/upfile/avatar/m_' . $_SESSION['user']['user']['id'] . '.jpg');die();
		
		// if(!empty($_SESSION['user']['user']['id'])){
			// $logininfo = DB::table('user')->where('email','=',$_SESSION['user']['user']['id'])->first();
			// $sequence = md5(rand());
			// $token = md5(rand());
			// //var_dump($logininfo);die();
			// $userinfo = DB::table('user_info')->where('user_id','=',$logininfo['id'])->first();
			// $_SESSION['user'] = array(
				// 'user'=>$logininfo,
				// 'user_info'=>$userinfo,
				// 'deadline'=>time()+ 7200,
				// 'sequence' =>$sequence,
				// 'token' =>$token
			// );
		// }
		return View::make('platform.personalcenter.PersonalCenter');
	}
	public function view_safelevel()
	{
		return View::make('platform.personalcenter.SafeLevel');
	}
	public function view_device()//显示我的设备页面
	{
		//@session_start();
		$userdevice = array();
		$arr = DB::table('user_equipment')->where('user_id','=',$_SESSION['user']['user']['id'])->get();
		for($i=0;$i<count($arr,0);$i++){
			$device = DB::table('device')->where('id','=',$arr[$i]['device_id'])->get();
			$userdevice[$i]['devicename']=$device[0]['name'];
			$userdevice[$i]['devicesummary']=$device[0]['summary'];
			$userdevice[$i]['deviceimg']=$device[0]['img_url'];
		}
		return View::make('platform.personalcenter.Device')->with('userdevice',$userdevice);
	}
	public function view_add_device()//显示我的设备添加设备页面
	{
		return View::make('platform.personalcenter.AddDevice');
	}
	public function view_nickname()//显示修改昵称页面
	{
		return View::make('platform.Nickname');
	}
	public function change_nickname()//修改昵称
	{
		$nickname = $_POST["nickname"];
		
		$res=DB::table('user_info')->where('id', $_SESSION['user']['user']['id'])->update(array('nickname'=>$nickname));
		if($res>0){
			return 1;
		}
		else{
			return 2;
		}
	}
	public function add_device()//添加到我的设备
	{
		//@session_start();
		$user_id = $_SESSION['user']['user']['id'];
		$devicename = $_REQUEST['devicename'];
		$devicecode = $_REQUEST['devicecode'];
		$devicesummary = $_REQUEST['devicesummary'];
		$devicecontent = $_REQUEST['devicecontent'];
		$deviceprice = $_REQUEST['deviceprice'];
		$deviceimg = $_REQUEST['deviceimg'];
		
		$device = DB::table('device')->where('device_code','=',$devicecode)->get();
		if(count($device,0)>0){
			$uuid=trim(com_create_guid(), '{}');
			$equipment = array(
				'id'=>$uuid,
				'user_id'=>$user_id,
				'device_id'=>$device[0]['id'],
				'init_time'=>date('Y-m-d H:i:s',time()),
				'delete'=>0
			);
			$res = DB::table('user_equipment')->insert($equipment);
			if($res>0){
				return 1;
			}
			else{
				return 0;
			}
		}
		else{
			$uuid=trim(com_create_guid(), '{}');
			$deviceinfo=array(
				'id'=>$uuid,
				'category_id'=>0,
				'device_code'=>$devicecode,
				'name'=>$devicename,
				'summary'=>$devicesummary,
				'content'=>$devicecontent,
				'price'=>$deviceprice,
				'init_time'=>date('Y-m-d H:i:s',time()),
				'update_time'=>date('Y-m-d H:i:s',time()),
				'status'=>0,
				'img_url'=>$deviceimg,
				'operator_id'=>0
			);
			$res = DB::table('device')->insert($deviceinfo);
			if($res>0){
				$equipment = array(
					'id'=>$uuid,
					'user_id'=>$user_id,
					'device_id'=>$device[0]['id'],
					'init_time'=>date('Y-m-d H:i:s',time()),
					'delete'=>0
				);
				$res = DB::table('user_equipment')->insert($equipment);
				return 1;
			}
			else{
				return 0;
			}
		}
	}
}













