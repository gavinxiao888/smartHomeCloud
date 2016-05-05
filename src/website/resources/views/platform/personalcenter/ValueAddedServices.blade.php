@extends('platform.personalcenter.Default')
	@section('content')
	<script>
	function gotoTop(min_height){
		//预定义返回顶部的html代码，它的css样式默认为不显示
		var gotoTop_html = '<div id="gotoTop">返回顶部</div>';
		//将返回顶部的html代码插入页面上id为page的元素的末尾 
		$("#page").append(gotoTop_html);
		$("#gotoTop").click(//定义返回顶部点击向上滚动的动画
			function(){$('html,body').animate({scrollTop:0},700);
		}).hover(//为返回顶部增加鼠标进入的反馈效果，用添加删除css类实现
			function(){$(this).addClass("hover");},
			function(){$(this).removeClass("hover");
		});
		//获取页面的最小高度，无传入值则默认为600像素
		min_height ? min_height = min_height : min_height = 600;
		//为窗口的scroll事件绑定处理函数
		$(window).scroll(function(){
			//获取窗口的滚动条的垂直位置
			var s = $(window).scrollTop();
			//当窗口的滚动条的垂直位置大于页面的最小高度时，让返回顶部元素渐现，否则渐隐
			if( s > min_height){
				$("#gotoTop").fadeIn(100);
			}else{
				$("#gotoTop").fadeOut(200);
			};
		});
	};
	gotoTop();
	
	
	</script>
	
	<!--位置导航-->
	<div class="position">
		<h5><a>首页</a>&nbsp;/&nbsp;<a>个人中心</a></h5>
	</div>
	<!--位置导航结束-->

	<!--左侧导航开始-->
	<div class="navi-left">
		<ul>
			<li style=""><a  class="line" href="">个人中心</a> </li>

			<li  class=""><a href="/personalcenter/personalcenter" >个人中心</a></li>
			<li ><a class="" href="/personalcenter/device" >我的设备</a></li>

		   
		</ul>    
		<ul>
			<li class="line" style=""><a href="">售后服务</a></li>

			<li><a href="" >售后服务</a></li>
			<li><a href="" >领取快递补偿</a></li>
			<li><a href="" >历史记录</a></li>

			
		</ul>
		<ul>
			<li class="line" style=""><a href="">账号管理</a> </li>

			<li><a href="" >收货地址管理</a></li>
			<li><a href="" >修改密码</a></li>
		</ul>	
	</div>
	<!--左侧导航结束-->
	<!--news开始-->
	<div class="news">
	<form id="form1" action="">
		<div class="newsdiv1">
			增值服务<div class="newsdiv3"><a href="">全部订单</a>｜<a href="">未支付订单</a>｜<a href="">已关闭订单</a></div>
		</div>
		
		<div style="float:left;">
			<div class="divlist">
				<table class="divlisttable">
					<tr><td colspan="2"><div class="divlisttitle3">爱悠云幕M1</div></td></tr>
					<tr><td><div class="divlisttitle4">3999元</div></td><td><div class="divlisttitle4">详情</div></td></tr>
					<tr><td colspan="2"><img src="/static/platform/platformimages/bigheadimg.png" class="divlistimg"></td></tr>
				</table>
			</div>
			<div class="divlist">222</div>
			<div class="divlist">333</div>
			<div class="divlist">444</div>
			<div class="divlist">555</div>
			<div class="divlist">666</div>
			<div class="divlist">777</div>
			<div class="divlist">888</div>
		</div>
		<div style="width:100%;" id="page"><!--
		<a href="javascript:scroll(0,0)" class="gotop-btn" title="回到顶部" style="display:none; ">回到顶部</a>
		<script type="text/javascript">$(window).scroll(function(){$(this).scrollTop()>400?$('.gotop-btn').css('display','block'):$('.gotop-btn').hide()})</script>-->
		<a onclick="pageScroll()" style="float:right;margin-right:50px;"><img src="/static/platform/platformimages/backtop-062.gif" style="height:26px;width:90px;"/></a></div>
	</form>
	</div>
	<!--news结束--->
	<!--content结束-->
	
	@stop
@stop













