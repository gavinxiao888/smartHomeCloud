@extends('platform.personalcenter.Default')
	@section('content')
	<script type="text/javascript">
		$().ready(function(){ 
			deviceinfo();
			devicecheck();
			devicetype();
			devicestatus();
		});
		function deviceinfo(){//设备信息
			<?php if(count($userdevice,0)>0){?>
				$('#deviceimg').append('<?php 
					if(!file_exists($userdevice['deviceimg'])){
						echo '<img src="/static/platform/platformimages/imgnone.jpg" class="divlistimg" />';
					}
					else{
						echo '<img src="'. $userdevice['deviceimg'] .'" class="divlistimg">';
					}
				?>');
				$('#devicename').text('<?php echo $userdevice['devicename']. ' ' .$userdevice['deviceprice']. '元 × 1';?>');
				$('#deviceaddtime').text('<?php echo $userdevice['deviceaddtime'];?>');
				$('#returnid').val('<?php echo $userdevice['returnid'];?>');
				$('#deviceid').val('<?php echo $userdevice['deviceid'];?>');
			<?php } ?> 
		}
		function devicetype(){//操作类型
			<?php if($userdevice['type']==0){ ?>
				$('#devicetype').text('退货中');
				$('#radioreturn').attr('checked',true);
				$('#radiochenge').attr('checked',false);
				$('#radiorepair').attr('checked',false);
			<?php } ?> 
			<?php if($userdevice['type']==1){ ?>
				$('#devicetype').text('换货中');
				$('#radioreturn').attr('checked',false);
				$('#radiochenge').attr('checked',true);
				$('#radiorepair').attr('checked',false);
			<?php } ?> 
			<?php if($userdevice['type']==2){ ?>
				$('#devicetype').text('维修中');
				$('#radioreturn').attr('checked',false);
				$('#radiochenge').attr('checked',false);
				$('#radiorepair').attr('checked',true);
			<?php } ?> 
		};
		function devicestatus(){//申请状态
			<?php if($userdevice['status']==0){ ?>
				$('#devicestatus').val('0');
				$('#reason').val('<?php echo $userdevice['reason'];?>');
				$('#returnreason').css('display','block');
				$('#statusfirst').css('display','block');
				$('#statussecond').css('display','none');
				$('#statusthird').css('display','none');
			<?php } ?> 
			<?php if($userdevice['status']==1){ ?>
				$('#returnreason').css('display','none');
				$('#statusfirst').css('display','none');
				$('#statussecond').css('display','block');
				$('#statusthird').css('display','none');
			<?php } ?>
			<?php if($userdevice['status']==2){ ?>
				$('#returnreason').css('display','none');
				$('#statusfirst').css('display','none');
				$('#statussecond').css('display','none');
				$('#statusthird').css('display','block');
			<?php } ?>
		};
		function devicecheck(){//审核状态
			<?php if($userdevice['check']==0){ ?>
				$('#returnreason').css('display','none');
				$('#devicecheck').text('未审核');
			<?php } ?> 
			<?php if($userdevice['check']==1){ ?>
				$('#returnreason').css('display','none');
				$('#devicecheck').text('已审核');
			<?php } ?>
			<?php if($userdevice['check']==2){ ?>
				$('#returnreason').css('display','none');
				$('#devicecheck').text('审核中');
			<?php } ?>
		};
	</script>
	<!--位置导航-->
	<div class="position">
		<h5><a>首页</a>&nbsp;/&nbsp;<a>售后服务</a></h5>
	</div>
	<!--位置导航结束-->

	<!--左侧导航开始-->
	<div class="navi-left">
		<ul>
			<li style=""><a class="line" href="">个人中心</a> </li>
			<li class=""><a href="/personalcenter/personalcenter" >个人中心</a></li>
			<li class=""><a class="" href="/personalcenter/device" >我的设备</a></li>
			<li ><a class="" href="" >增值服务</a></li>
		</ul>    
		<ul>
			<li class="line" style=""><a href="/customservice/center">售后服务</a></li>
			<li class="hit"><a href="/customservice/repair" >维修</a></li>
			<li><a href="/customservice/return" >退换货</a></li>
			<li><a href="/customservice/appointment" >预约服务</a></li>
		</ul>
		<ul>
			<li class="line" style=""><a href="">账号管理</a> </li>
			<li><a href="/accountmanagement/returnaddressmanagement" >收货地址管理</a></li>
			<li><a href="/user/password/edit" >修改密码</a></li>
		</ul>	
	</div>
	<!--左侧导航结束-->
	<!--news开始-->
	<div class="news">
		<div class="newsdiv1">
			维修
		</div>
		
		<div style="margin-left:10px;">
			<div class="returndiv">
				<table class="returndivtable">
					<tr style="height:40px;background-color:#eeeeee;">
						<td colspan="5" class="returndivtable">产品信息：</td>
					</tr>
					<tr style="height:120px;text-align:center;">
						<td id="deviceimg" style="border-left:1px solid #dadada;border-top:1px solid #dadada;text-align:left;"></td>
						<td id="devicename" style="border-left:0px;border-top:1px solid #dadada;text-align:left;"></td>
						<td id="devicetype" style="border-left:1px solid #dadada;border-top:1px solid #dadada;">维修中</td>
						<td id="deviceaddtime" style="border-left:1px solid #dadada;border-top:1px solid #dadada;"></td>
						<td id="devicecheck" style="border-left:1px solid #dadada;border-top:1px solid #dadada;">审核中</td>
					</tr>
				</table>
			</div>
		</div>
		<div style="float:left;width:700px;margin-top:20px;margin-bottom:10px;margin-left:30px;">
			<div style="float:left;margin-bottom:20px;" id="statusfirst"><img src="/static/platform/platformimages/21-returnline_03 (1).png" /></div>
			<div style="float:left;margin-bottom:20px;display:none;" id="statussecond"><img src="/static/platform/platformimages/21-returnline_03 (2).png" /></div>
			<div style="float:left;margin-bottom:20px;display:none;" id="statusthird"><img src="/static/platform/platformimages/21-returnline_03 (3).png" /></div>
			<div style="float:left;width:33%;">申请维修</div><div style="float:left;width:31%;text-align:right;">维修中</div><div style="float:left;width:36%;text-align:right;">维修完成</div>
		</div>
		<div style="margin-top:10px;float:left;border-top: 1px solid #cccccc;width:778px;"></div>
		<div style="margin-left:30px;float:left;width:700px;display:none;" id="returnreason">
			<form method="post" id="form1" action="/customservice/addrepair" enctype="multipart/form-data">
				<p><textarea id="reason" name="reason" placeholder="请输入维修原因" style="width:700px;height:120px;resize:none;overflow-y:auto;font-size:16px;"></textarea></p>
				<input id="devicestatus" name="devicestatus" type="text" value="" style="display:none;"/>
				<input id="returnid" name="returnid" type="text" value="" style="display:none;"/>
				<input id="deviceid" name="deviceid" type="text" value="" style="display:none;"/>
				<input type="submit" value="提交申请"/>
			</form>
		</div>
	</div>
	<!--news结束--->
	<!--content结束-->
	
	@stop
@stop









