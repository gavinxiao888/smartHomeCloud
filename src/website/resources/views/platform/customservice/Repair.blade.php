@extends('platform.personalcenter.Default')
	@section('content')
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

		   
		</ul>    
		<ul>
			<li class="line" style=""><a href="/customservice/center">售后服务</a></li>
			<li class="hit"><a href="/customservice/repair" >维修</a></li>
			<li><a href="/customservice/return" >退换货</a></li>
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
			<?php 
				if(!empty($userdevice)){
					for($i=0;$i<count($userdevice,0);$i++){
						echo '<div class="returndiv">';
						echo '<table class="returndivtable">';
						echo '<tr style="height:40px;background-color:#eeeeee;">';
						echo '<td colspan="5" class="returndivtable">产品信息：</td>';
						echo '</tr>';
						echo '<tr style="height:120px;text-align:center;">';
						echo '<td style="border-left:1px solid #dadada;border-top:1px solid #dadada;text-align:left;">';
						if(!file_exists($userdevice[$i]['deviceimg'])){
							echo '<img src="/static/platform/platformimages/imgnone.jpg" class="divlistimg" />';
						}
						else{
							echo '<img src="'. $userdevice[$i]['deviceimg'] .'" class="divlistimg">';
						}						
						echo '</td>';
						echo '<td style="border-left:0px;border-top:1px solid #dadada;text-align:left;">';
						echo $userdevice[$i]['devicename'] .' '. $userdevice[$i]['deviceprice'] .'元 × 1';
						echo '</td>';
						echo '<td style="border-left:1px solid #dadada;border-top:1px solid #dadada;">'. $userdevice[$i]['devicestatus'] .'</td>';
						echo '<td style="border-left:1px solid #dadada;border-top:1px solid #dadada;">'. $userdevice[$i]['deviceaddtime'] .'</td>';
						echo '<td style="border-left:1px solid #dadada;border-top:1px solid #dadada;"><a href="/customservice/repairstatus?deviceid='. $userdevice[$i]['deviceid'] .'">';
							if($userdevice[$i]['status']==0){
								echo '申请维修';
							}
							else{
								echo '进度查询';
							}
						echo '</a></td>';
						echo '</tr>';
						echo '</table>';
						echo '</div>';
					}
				}
			?>
		</div>
	</div>
	<!--news结束--->
	<!--content结束-->
	
	@stop
@stop









