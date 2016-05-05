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
			<li style=""><a class="line" href="">个人中心</a> </li>

			<li  class="hit"><a href="/personalcenter/personalcenter" >个人中心</a></li>
			<li class=""><a href="/personalcenter/device" >我的设备</a></li>
			<!--<li ><a class="" href="" >增值服务</a></li>-->

		   
		</ul>    
		<ul>
			<li class="line" style=""><a href="/customservice/center">售后服务</a></li>
			<li><a href="/customservice/repair" >维修</a></li>
			<li><a href="/customservice/return" >退换货</a></li>
			<!--<li><a href="/customservice/appointment" >预约服务</a></li>-->

			
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
		<!--概览-->
		<div class="overview" style="height:40px;">
			<div style="padding-top: 24px;padding-bottom: 24px;text-align:center;font-size:16px;">如何提高安全级别？</div>
		</div>
		<!--概览结束-->
		<!--未支付订单-->
		<div class="nopaymentorder">
			<h5>&nbsp;&nbsp;&nbsp;第一步：设置密保问题</h5>
			<p style="padding-top: 24px;padding-bottom: 24px;text-align:center"><a href="/user/securityquestion">
			<img src="/static/platform/platformimages/level1.png" style="width:640px;height:480px;"/></a></p>
		</div>
		<!--未支付订单结束-->
		<!--未收货订单-->
		<div class="notreceivedorders">
			<h5>&nbsp;&nbsp;&nbsp;第二步：绑定手机</h5>
			<p style="padding-top: 24px;padding-bottom: 24px;text-align:center"><a href="/user/bindphone">
			<img src="/static/platform/platformimages/level2.png" style="width:640px;height:480px;"/></a></p>
		</div>
		<!--未收货订单结束-->
		<!--购物车-->
		<div class="notreceivedorders">
			<h5 style="width:1000px;">&nbsp;&nbsp;&nbsp;第三步：绑定邮箱</h5>
			<p style="padding-top: 24px;padding-bottom: 24px;text-align:center"><a href="/user/bindemail">
			<img src="/static/platform/platformimages/level3.png" style="width:640px;height:480px;"/></a></p>
		</div>
		<!--购物车结束-->
	</div>
	<!--news结束--->
	
	@stop
@stop