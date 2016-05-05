@extends('platform.personalcenter.Default')
	@section('content')
	<!--位置导航-->
	<div class="position">
		<h5><a>首页</a>&nbsp;/&nbsp;<a href="/personalcenter/personalcenter">个人中心</a></h5>
	</div>
	<!--位置导航结束-->

	<!--左侧导航开始-->
	<div class="navi-left">
		<ul>
			<li style=""><a class="line" href="/personalcenter/personalcenter">个人中心</a> </li>

			<li  class=""><a href="/personalcenter/personalcenter" >个人中心</a></li>
			<li class="hit"><a class="" href="/personalcenter/device" >我的设备</a></li>

		   
		</ul>    
		<ul>
			<li class="line" style=""><a href="/customservice/center">售后服务</a></li>
			<li><a href="/customservice/repair" >维修</a></li>
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
			我的设备<div style="float:right;font-size:14px;"><a style="color:#EF6909;" href="/personalcenter/device">已有设备</a>｜<a href="/personalcenter/viewdevice">添加设备</a></div>
		</div>
		<!--<div class="newsdiv2">
			<input class="selectinput" type="text" placeholder="设备搜索" name="select" id="select" />
			<a href="" onclick="" class="selecta"></a>
		</div>-->
		<div style="float:left;">
		<?php
			if(!empty($userdevice)){
				for($i=0;$i<count($userdevice,0);$i++){
					echo '<div class="divlist">';
						echo '<table class="divlisttable">';
							if(!file_exists($userdevice[$i]['deviceimg'])){
									echo '<tr><td><img src="/static/platform/platformimages/imgnone.jpg" class="divlistimg"/></td></tr>';
								}
								else{
									echo '<tr><td><img src="'. $userdevice[$i]['deviceimg'] .'" class="divlistimg"></td></tr>';
								}
							echo '<tr><td><div class="divlisttitle1">'. $userdevice[$i]['devicename'] .'</div></td></tr>';
							echo '<tr><td><div class="divlisttitle2">'. $userdevice[$i]['devicesummary'] .'</div></td></tr>';
						echo '</table>';
					echo '</div>';
				}
			}
			else{
				echo '<div class="divlist">';
					echo '<table class="divlisttable">';
						echo '<tr><td><a href="/personalcenter/viewdevice"><img src="/static/platform/platformimages/plus2.png" class="divlistimg" /></a></td></tr>';
						echo '<tr><td><a href="/personalcenter/viewdevice"><div class="divlisttitle1">请添加设备</div></a></td></tr>';
					echo '</table>';
				echo '</div>';
			}
		?>
			<!--
			<div class="divlist">
				<table class="divlisttable">
					<tr><td><img src="/static/platform/platformimages/bigheadimg.png" class="divlistimg"></td></tr>
					<tr><td><div class="divlisttitle1">爱悠云幕M1</div></td></tr>
					<tr><td><div class="divlisttitle2">爱悠云幕M1爱悠云幕M1</div></td></tr>
					<tr><td><div class="divlisttitle2">详情</div></td></tr>
				</table>
			</div>
			-->
		</div>
	</div>
	<!--news结束--->
	<!--content结束-->
	
	@stop
@stop