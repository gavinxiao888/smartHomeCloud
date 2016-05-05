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
class ServiceRepairController extends \App\Http\Controllers\Controller
{
    public function view_custom_repair()//显示我的维修页面
	{
		$userdevice = array();
		$arr = DB::table('user_equipment')->where('user_id','=',$_SESSION['user']['user']['id'])->get();
		for($i=0;$i<count($arr,0);$i++){
			$device = DB::table('device')->where('id','=',$arr[$i]['device_id'])->get();
			$return = DB::table('return_apply')
			->where('user_id','=',$_SESSION['user']['user']['id'])
			->where('product_id','=',$device[0]['id'])->where('type','=',2)->get();
			if(count($return,0)>0){
				if($return[0]['status']==0){
					if($return[0]['type']==0){
						$userdevice[$i]['devicestatus']='申请退货中';
					}
					if($return[0]['type']==1){
						$userdevice[$i]['devicestatus']='申请换货中';
					}
					if($return[0]['type']==2){
						$userdevice[$i]['devicestatus']='正在维修中';
					}
				}
				if($return[0]['status']==1){
					$userdevice[$i]['devicestatus']='商家处理中';
				}
				if($return[0]['status']==2){
					if($return[0]['type']==0){
						$userdevice[$i]['devicestatus']='已完成退货';
					}
					if($return[0]['type']==1){
						$userdevice[$i]['devicestatus']='已完成换货';
					}
					if($return[0]['type']==2){
						$userdevice[$i]['devicestatus']='已完成维修';
					}
				}
				$userdevice[$i]['type']=$return[0]['type'];
				$userdevice[$i]['status']=$return[0]['status'];
			}
			else{
				$userdevice[$i]['devicestatus']='正常使用中';
				$userdevice[$i]['type'] = 0;
				$userdevice[$i]['status'] = 0;
			}
			$userdevice[$i]['deviceaddtime']=$arr[$i]['init_time'];
			$userdevice[$i]['deviceid']=$arr[$i]['id'];//我的设备ID
			$userdevice[$i]['devicename']=$device[0]['name'];
			$userdevice[$i]['devicesummary']=$device[0]['summary'];
			$userdevice[$i]['deviceimg']=$device[0]['img_url'];
			$userdevice[$i]['deviceprice']=$device[0]['price'];
		}
		return View::make('platform.customservice.Repair')->with('userdevice',$userdevice);
	}
	public function view_repair_status()//显示维修状态页面
	{
		$deviceid = $_REQUEST['deviceid'];//我的设备ID
		$userdevice = array();
		$arr = DB::table('user_equipment')->where('id','=',$deviceid)->get();
		$device = DB::table('device')->where('id','=',$arr[0]['device_id'])->get();
		$return = DB::table('return_apply')
		->where('user_id','=',$_SESSION['user']['user']['id'])
		->where('product_id','=',$device[0]['id'])->where('type','=',2)->get();
		if(count($return,0)>0){
			$userdevice['type']=$return[0]['type'];
			$userdevice['check']=$return[0]['check'];
			$userdevice['status']=$return[0]['status'];
			$userdevice['reason']=$return[0]['reason'];
			$userdevice['returnid']=$return[0]['id'];
		}
		else{
			$userdevice['type'] = 2;
			$userdevice['check'] = 0;
			$userdevice['status'] = 0;
			$userdevice['reason'] = '';
			$userdevice['returnid'] = '';
		}
		$userdevice['deviceaddtime']=$arr[0]['init_time'];
		$userdevice['deviceid']=$arr[0]['id'];//我的设备ID
		$userdevice['devicename']=$device[0]['name'];
		$userdevice['devicesummary']=$device[0]['summary'];
		$userdevice['deviceimg']=$device[0]['img_url'];
		$userdevice['deviceprice']=$device[0]['price'];
		//var_dump($userdevice);die();
		return View::make('platform.customservice.RepairStatus')->with('userdevice',$userdevice);
	}
	public function addrepair()//添加维修单
	{
		$devicestatus = $_REQUEST['devicestatus'];
		$reason = $_REQUEST['reason'];
		$deviceid = $_REQUEST['deviceid'];
		$radio = $_REQUEST['radiotype'];
		$returnid = $_REQUEST['returnid'];
		//var_dump($reason.' | '.$deviceid.' | '.$devicestatus.' | '.$radio.' | '.$returnid);die();
		if($devicestatus==0){
			$uuid=trim(com_create_guid(), '{}');
			$returnapply = array(
				'id'=>$uuid,
				'user_id'=>$_SESSION['user']['user']['id'],
				'init_time'=>date('Y-m-d H:i:s',time()),
				'product_id'=>$deviceid,
				'reason'=>'0',
				'admin_id'=>'0',
				'result'=>'0',
				'check'=>2,
				'check_result'=>'0',
				'type'=>2,
				'status'=>1,
				'delete'=>0
			);
			$res = DB::table('return_apply')->insert($returnapply);
			if($res>0){
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加成功~")</script>';
				echo '<script language="javascript">window.location.href="/customservice/repair";</script>';
			}
			else{
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加失败~");window.history.back();</script>';
			}
		}
		else{
			$res = DB::table('return_apply')->where('id','=',$returnid)->update(array('reason'=>$reason,'type'=>$radio));
			if($res>0){
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加成功~")</script>';
				echo '<script language="javascript">window.location.href="/customservice/repair";</script>';
			}
			else{
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加失败~");window.history.back();</script>';
			}
		}
	}
	public function view_custom_return()//显示退换货页面
	{
		$userdevice = array();
		$arr = DB::table('user_equipment')->where('user_id','=',$_SESSION['user']['user']['id'])->get();
		for($i=0;$i<count($arr,0);$i++){
			$device = DB::table('device')->where('id','=',$arr[$i]['device_id'])->get();
			$return = DB::table('return_apply')
			->where('user_id','=',$_SESSION['user']['user']['id'])
			->where('product_id','=',$device[0]['id'])->where('type','<',2)->get();
			if(count($return,0)>0){
				if($return[0]['status']==0){
					if($return[0]['type']==0){
						$userdevice[$i]['devicestatus']='申请退货中';
					}
					if($return[0]['type']==1){
						$userdevice[$i]['devicestatus']='申请换货中';
					}
					if($return[0]['type']==2){
						$userdevice[$i]['devicestatus']='申请维修中';
					}
				}
				if($return[0]['status']==1){
					$userdevice[$i]['devicestatus']='商家处理中';
				}
				if($return[0]['status']==2){
					if($return[0]['type']==0){
						$userdevice[$i]['devicestatus']='已完成退货';
					}
					if($return[0]['type']==1){
						$userdevice[$i]['devicestatus']='已完成换货';
					}
					if($return[0]['type']==2){
						$userdevice[$i]['devicestatus']='已完成维修';
					}
				}
				$userdevice[$i]['type']=$return[0]['type'];
				$userdevice[$i]['status']=$return[0]['status'];
			}
			else{
				$userdevice[$i]['devicestatus']='正常使用中';
				$userdevice[$i]['type'] = 0;
				$userdevice[$i]['status'] = 0;
			}
			$userdevice[$i]['deviceaddtime']=$arr[$i]['init_time'];
			$userdevice[$i]['deviceid']=$arr[$i]['id'];//我的设备ID
			$userdevice[$i]['devicename']=$device[0]['name'];
			$userdevice[$i]['devicesummary']=$device[0]['summary'];
			$userdevice[$i]['deviceimg']=$device[0]['img_url'];
			$userdevice[$i]['deviceprice']=$device[0]['price'];
		}
		return View::make('platform.customservice.Return')->with('userdevice',$userdevice);
	}
	public function view_return_status()//显示退换货状态页面
	{
		$deviceid = $_REQUEST['deviceid'];//我的设备ID
		$userdevice = array();
		$arr = DB::table('user_equipment')->where('id','=',$deviceid)->get();
		$device = DB::table('device')->where('id','=',$arr[0]['device_id'])->get();
		//var_dump($arr);die();
		$return = DB::table('return_apply')
		->where('user_id','=',$_SESSION['user']['user']['id'])
		->where('product_id','=',$device[0]['id'])->where('type','<',2)->get();
		if(count($return,0)>0){
			$userdevice['type']=$return[0]['type'];
			$userdevice['check']=$return[0]['check'];
			$userdevice['status']=$return[0]['status'];
			$userdevice['reason']=$return[0]['reason'];
			$userdevice['returnid']=$return[0]['id'];
		}
		else{
			$userdevice['type'] = 0;
			$userdevice['check'] = 0;
			$userdevice['status'] = 0;
			$userdevice['reason'] = '';
			$userdevice['returnid'] = '';
		}
		$userdevice['deviceaddtime']=$arr[0]['init_time'];
		$userdevice['deviceid']=$arr[0]['id'];//我的设备ID
		$userdevice['devicename']=$device[0]['name'];
		$userdevice['devicesummary']=$device[0]['summary'];
		$userdevice['deviceimg']=$device[0]['img_url'];
		$userdevice['deviceprice']=$device[0]['price'];
		//var_dump($userdevice);die();
		return View::make('platform.customservice.ReturnStatus')->with('userdevice',$userdevice);
	}
	public function addreturn()//添加退换货
	{
		$devicestatus = $_REQUEST['devicestatus'];
		$reason = $_REQUEST['reason'];
		$deviceid = $_REQUEST['deviceid'];
		$radio = $_REQUEST['radiotype'];
		$returnid = $_REQUEST['returnid'];
		//var_dump($reason.' | '.$deviceid.' | '.$devicestatus.' | '.$radio.' | '.$returnid);die();
		if($devicestatus==0){
			$uuid=trim(com_create_guid(), '{}');
			$returnapply = array(
				'id'=>$uuid,
				'user_id'=>$_SESSION['user']['user']['id'],
				'init_time'=>date('Y-m-d H:i:s',time()),
				'product_id'=>$deviceid,
				'reason'=>'0',
				'admin_id'=>'0',
				'result'=>'0',
				'check'=>2,
				'check_result'=>'0',
				'type'=>$radio,
				'status'=>1,
				'delete'=>0
			);
			$res = DB::table('return_apply')->insert($returnapply);
			if($res>0){
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加成功~")</script>';
				echo '<script language="javascript">window.location.href="/customservice/return";</script>';
			}
			else{
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加失败~");window.history.back();</script>';
			}
		}
		else{
			$res = DB::table('return_apply')->where('id','=',$returnid)->update(array('reason'=>$reason,'type'=>$radio));
			if($res>0){
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加成功~")</script>';
				echo '<script language="javascript">window.location.href="/customservice/return";</script>';
			}
			else{
				header("Content-type: text/html; charset=utf-8");
				echo '<script>alert("添加失败~");window.history.back();</script>';
			}
		}
	}
	public function view_appointment()//显示我的预约服务页面
	{
		return View::make('platform.personalcenter.PersonalCenter');
	}
}














