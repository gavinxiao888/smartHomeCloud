@extends('platform.personalcenter.Default')
	@section('content')
	<!--位置导航-->
	<div class="position">
		<h5><a>首页</a>&nbsp;/&nbsp;<a>个人中心</a></h5>
	</div>
	<!--位置导航结束-->

	<!--左侧导航开始-->
	<div class="navi-left">
		<ul>
			<li class="line"><div class="line" style="border-bottom:0px;">个人中心</div> </li>

			<li  class="hit"><a href="/personalcenter/personalcenter" >个人中心</a></li>
			<li class=""><a href="/personalcenter/device" >我的设备</a></li>
			<!--<li ><a class="" href="" >增值服务</a></li>-->

		   
		</ul>    
		<ul>
			<li class="line" style=""><div class="line" style="border-bottom:0px;">售后服务</div></li>
			<li><a href="/customservice/repair" >维修</a></li>
			<li><a href="/customservice/return" >退换货</a></li>
			<!--<li><a href="/customservice/appointment" >预约服务</a></li>-->

			
		</ul>
		<ul>
			<li class="line" style=""><div class="line" style="border-bottom:0px;">账号管理</div> </li>

			<li><a href="/accountmanagement/returnaddressmanagement" >收货地址管理</a></li>
			<li><a href="/user/password/edit" >修改密码</a></li>
		</ul>	
	</div>
	<!--左侧导航结束-->
	<!--news开始-->
	<div class="news">
		<!--概览-->
		<div class="overview">
			<div id="left">
				<?php
					if($_SESSION['user']['user']['id']!=''){
						//echo '<img src="/upfile/avatar/m_'. $_SESSION['user']['user']['id'] .'.jpg" style="height:60px;width:60px;">';
						$imghead = '/upfile/avatar/m_'. $_SESSION['user']['user']['id'] .'.jpg';
						if(file_exists($imghead)){
							echo '<img src="/upfile/avatar/head.png" style="height:60px;width:60px;">';
						}
						else{
							echo '<img src="/upfile/avatar/m_'. $_SESSION['user']['user']['id'] .'.jpg" style="height:60px;width:60px;">';
						}
					}
					else{
						echo '<img src="/upfile/avatar/head.png" style="height:60px;width:60px;">';
					}
						
				?>
			</div>
			<div id="right">
				<h5>
				<?php
					if($_SESSION['user']['user_info']['nickname']!=''){
						echo $_SESSION['user']['user_info']['nickname'];
					}
					if($_SESSION['user']['user']['phone']!=''){
						echo $_SESSION['user']['user']['phone'];
					}
				?>
				，
				<?php
					$h=date('G');
					//echo date('G');
					if ($h<11) echo '早上好！';
					else if ($h<13) echo '中午好！';
					else if ($h<17) echo '下午好！';
					else echo '晚上好！';
				?> 
				</h5>
				<p>账号安全级别&nbsp;
				<?php
					$count = 0;
					if($_SESSION['user']['user']['email']!=''){
						$count = $count +1;
					}
					if($_SESSION['user']['user']['phone']!=''){
						$count = $count +1;
					}
					if($_SESSION['user']['user_info']['answer_first']!=''||$_SESSION['user']['user_info']['answer_second']!=''||$_SESSION['user']['user_info']['answer_third']!=''){
						$count = $count +1;
					}
					if($count==1){
						echo '<img src="/static/platform/platformimages/safe1.png" />';
					}
					if($count==2){
						echo '<img src="/static/platform/platformimages/safe2.png" />';
					}
					if($count==3){
						echo '<img src="/static/platform/platformimages/safe3.png" />';
					}
				?>
					
					&nbsp;|&nbsp;
					<?php 
						if($_SESSION['user']['user']['phone']!=''){
							echo '<img src="/static/platform/platformimages/bind.png">';
						}
						else{
							echo '<img src="/static/platform/platformimages/nobind.png">';
						}
					?>
					<a href="/user/bindphone">绑定手机</a>&nbsp;|&nbsp;
					<?php 
						if($_SESSION['user']['user']['email']!=''){
							echo '<img src="/static/platform/platformimages/bind.png">';
						}
						else{
							echo '<img src="/static/platform/platformimages/nobind.png">';
						}
					?>
					<a href="/user/bindemail">绑定邮箱</a>&nbsp;|&nbsp;
					<a href="/user/safelevel">如何提高安全级别？</a>
				</p>
			</div>
		</div>
		<!--概览结束-->
	</div>
	<!--news结束--->
	
	@stop
@stop