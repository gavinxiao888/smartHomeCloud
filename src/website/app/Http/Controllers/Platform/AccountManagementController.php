<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/13
 * Time: 13:22
 * @power 售后服务
 *
 */
namespace App\Http\Controllers\Platform;
use View;
use DB;
class AccountManagementController extends \App\Http\Controllers\Controller
{
    public function view_account()//显示我的爱悠账号
	{
		//$userInfo = DB::TABLE('user')->where('id', $_SESSION['user']['user']['id'])->first();
		//var_dump($_SESSION['user']['user']['id']);die();
		return View::make('platform.Account');
	}
	public function view_return_address_management()//显示收货地址管理页面
	{
		//session_start();
		$arr=DB::table('address')->where('user_id','=',$_SESSION['user']['user']['id'])->where('delete','=',0)->get();
		for($i=0;$i<count($arr,0);$i++){
			if($arr[$i]['province']!="-1"){
				$province=DB::table('t_area')->where('id','=',$arr[$i]['province'])->get();
				$arr[$i]['province']=$province[0]['areaname'];
			}
			else{
				$arr[$i]['province']="";
			}
			if($arr[$i]['city']!="-1"){
				$city=DB::table('t_area')->where('id','=',$arr[$i]['city'])->get();
				$arr[$i]['city']=$city[0]['areaname'];
			}
			else{
				$arr[$i]['city']="";
			}
			if($arr[$i]['area']!="-1"){
				$area=DB::table('t_area')->where('id','=',$arr[$i]['area'])->get();
				$arr[$i]['area']=$area[0]['areaname'];
			}
			else{
				$arr[$i]['area']="";
			}
		}
		$department=DB::table('t_area')->where('level','=',1)->get();
		return View::make('platform.accountmanagement.ReturnAddress')->with('department',$department)->with('address',$arr);
	}
	//获取市
    public function getsubject()
	{
		$department = $_GET["department"]; 
		if(isset($department)){ 
			$select=array();
			$res=DB::table('t_area')->where('parentid','=',$department)->where('level','=',2)->get();
			for($i=0;$i<count($res,0);$i++){
				$select[$i]["ds_id"]=$res[$i]["id"];
				$select[$i]["ds_name"]=$res[$i]["areaname"];
			}
			echo urldecode(json_encode($select)); 
		} 
    }
	//获取区
    public function getunit()
	{
		$subject = $_GET["subject"]; 
		if(isset($subject)){ 
			$select=array();
			$res=DB::table('t_area')->where('parentid','=',$subject)->where('level','=',3)->get();
			for($i=0;$i<count($res,0);$i++){
				$select[$i]["ds_id"]=$res[$i]["id"];
				$select[$i]["ds_name"]=$res[$i]["areaname"];
			}
			echo urldecode(json_encode($select)); 
		} 
    }
	public function add_return_address()//添加收货地址
	{
		//session_start();
		$chebox=0;
		if(isset($_POST['selectdefault'])){
			$res=DB::table('address')->update(array('type'=>0));
			$chebox=$_POST['selectdefault'];
		}
		$phone = $_REQUEST['phone1'].$_REQUEST['phone2'];
		//var_dump($phone);die();
		$uuid=trim(com_create_guid(), '{}');
		$useraddress=array(
			'id'=>$uuid,
			'user_id'=>$_SESSION['user']['user']['id'],
			'consignee_name'=>$_REQUEST['name'],
			'province'=>$_REQUEST['department'],
			'city'=>$_REQUEST['subject'],
			'area'=>$_REQUEST['unit'],
			'address'=>$_REQUEST['street'],
			'code'=>$_REQUEST['code'],
			'phone'=>$phone,
			'mobilephone'=>$_REQUEST['mobilephone'],
			'type'=>$chebox,
			'delete'=>0
		);
		//$useraddress=$_REQUEST;
		//var_dump($useraddress);die();
		if(!empty($_REQUEST["name"])){
			$happen_time=date('Y-m-d H:i:s',time());
			if(!empty($_REQUEST["happen_time"])){
				$happen_time=$_REQUEST["happen_time"];
			}
			$res=DB::table('address')->insert($useraddress);
			if($res>0){
				
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加成功~")</script>';
				echo '<script language="javascript">window.location.href="/accountmanagement/returnaddressmanagement";</script>';
			}
			else{
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加失败~");window.history.back();</script>';
			}
		}
		else{
			header("Content-type: text/html; charset=utf-8");
			echo '<script>alert("请添加收货人~");window.history.back();</script>';
		}
	}
	public function del_return_address()//删除地址
	{
		$addressid = $_REQUEST['addressid'];
		$res = DB::table('address')->where('id','=',$addressid)->update(array('delete'=>1));
		if($res > 0){
			header("Content-type: text/html; charset=utf-8");
			echo '<script language="javascript">window.location.href="/accountmanagement/returnaddressmanagement";</script>';
		}
		else{
			header("Content-type: text/html; charset=utf-8");
			echo '<script>alert("删除失败~");window.history.back();</script>';
		}
	}
	public function default_return_address()//设置默认收货地址
	{
		$addressid = $_REQUEST['addressid'];
		DB::table('address')->where('user_id','=',$_SESSION['user']['user']['id'])->where('type','=',1)->update(array('type'=>0));
		$res = DB::table('address')->where('id','=',$addressid)->update(array('type'=>1));
		if($res > 0){
			header("Content-type: text/html; charset=utf-8");
			echo '<script language="javascript">window.location.href="/accountmanagement/returnaddressmanagement";</script>';
		}
		else{
			header("Content-type: text/html; charset=utf-8");
			echo '<script>alert("设置失败~");window.history.back();</script>';
		}
	}
	public function view_change_password()//显示修改密码页面
	{
		return View::make('platform.ChangePassword');
	}
}











