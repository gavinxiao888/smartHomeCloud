<!html>
<html lang="zh-cn">
<head>
 <meta charset="utf-8"/>
<title>个人中心</title>
<link href="/static/platform/platformcss/center.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/lsf.css" rel="stylesheet"/>
<script src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script src="/static/platform/platformjs/jquery-1.9.1.min.js" type="text/javascript"></script>
<script type="text/javascript">//导航下拉
$(document).ready(function(){
	jQuery.navlevel2 = function(level1,dytime) {
		
		$(level1).mouseenter(function(){
			varthis = $(this);
			delytime=setTimeout(function(){
			varthis.find('ul').slideDown();
		},dytime);
		
		});
		$(level1).mouseleave(function(){
			clearTimeout(delytime);
			$(this).find('ul').slideUp();
		});
	  
	};
	$.navlevel2("li.mainlevel",200);
});

function addCookie(name,value,expires,path, domain)//设置cookie
{
    var str=name+"="+escape(value);
    if(expires!=""){
        var date=new Date();
        date.setTime(date.getTime()+expires*24*3600*1000);//expires单位为天
        str+=";expires="+date.toGMTString();
    }
    if(path!=""){
        str+=";path="+path;//指定可访问cookie的目录
    }
    if(domain!=""){
        str+=";domain="+domain;//指定可访问cookie的域
    }
    document.cookie=str;
}
// $("#searchinfo").on("click",function(event){//搜索设备
	// event.preventDefault();
	// alert($('#appendedInputButtons').val());
	// if ($.trim($('#appendedInputButtons').val()) != ""){
		// windows.location.href="http://www.everyoo.com/search_rst.html?word="+$('#appendedInputButtons').val();
	// }
	// else{
		// alert('请您输入内容后再搜索吧！');
	// }
// }
function searchkey(){
	var ss = $('#appendedInputButtons').val();
	if (ss != ''){
		//alert(ss);
		//header("Content-type: text/html; charset=utf-8");
		document.location.href='http://www.everyoo.com/search_rst.html?word=' + ss;
	}
	else{
		alert('请您输入内容后再搜索吧！');
	}
}

</script>
</head>
<body>

@section('Default')
<!--头部开始-->
<div class="head">
	<p style="height:30px;line-height:30px;overflow:hidden; ">欢迎您<a href="/user/center"><span>
	<?php 
		if(isset($_SESSION['user'])){
			if(isset($_SESSION['user']['user_info']['nickname'])){
				echo $_SESSION['user']['user_info']['nickname'];
			}
			else{
				echo $_SESSION['user']['user']['email'];
			}
		}
	?></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/personalcenter/device">我的设备</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/user/account">我的账号</a>
	&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/user/loginout">退出登录</a></p>
</div>
<!--头部结束-->
<!--content开始-->
<div class="content">
	<!--搜索栏-->
	<div class="sibar">
		<img src="/static/platform/platformimages/logo2.png" class="sibar-logo" />
		<div class="sibar-right">
			<div class="input-group" style="width:270px;float:right;"> 
				<input class="form-control" type="email" placeholder="搜一下" id="appendedInputButtons" name="appendedInputButtons">   
				<div class="input-group-addon"><a href="" onclick="searchkey();return false;"><img src="/static/platform/platformimages/sibar.png" /></a></div>
			</div>
<!--
			<div class="cart">
				<a ><img src="/static/platform/platformimages/cart.png" style="position: absolute; top: 6px;left: 15px;">&nbsp;<font style="color:white;font-size:12px;  height: 30px;line-height: 30px;">购物车</font></a>
			</div>-->
		</div>
	</div>
	<!--搜索栏结束-->
	<!--头部导航开始-->
	<div class="navi" style="">
		<ul id="nav">
			<li class="mainlevel" style="width:200px;margin-left:-40px;"><a href="">全部商品分类&nbsp;<img src="/static/platform/platformimages/arrow-white.png"/></a>
				
			</li>
			
			<li class="mainlevel" style="background:#404144"><a href="/">首页</a>
				<!--<ul>
					<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>
				</ul>-->
			</li>
			
			<li class="mainlevel" style="background:#404144"><a href="/circle/cloudbox.html">智慧云盒</a>
			</li>
			<li class="mainlevel" style="background:#404144"><a href="/circle/screen/caf6894e-bf11-11e4-809e-0819a62482ff.html">智慧云幕</a>
			</li>
				<li class="mainlevel" style="background:#404144"><a href="">智慧云眼</a>
			</li>
		</ul>
	</div>
	<!--头部导航结束-->
	@yield('content')
</div>
<!--footer开始-->
<div class="foots">
	<div class="" style="height:10px;margin:0 auto;position:relative;">
		<a id="" href="" style="position:absolute;top:-8px;right:15%;"><img src="/static/everyoo/images/top.png" style="float:left;"/></a>		
	</div>
	<div class="" style="width:100%;text-align:center;margin-left:9%;">
		<div class="" style="width:140px;height:130px;margin-top:10px;float:left;">
			<img src="/static/everyoo/images/erweima.png" style="float:left;margin-top:12px;"/>
			<hr style="height:130px;width:1px;border:none;border-left:1px solid #555555;float:left;margin-left:10px;" />
		</div>
		<!--footer公司信息-->
		<div class="footsinfo">
			<ul>
                <li class="footsinfoli"><a href="http://www.everyoo.com/contact.html">联系我们</a></li>
                <li class="footsinfoli"><a href="http://www.everyoo.com/recruitment.html">精英集结号</a></li>
                <li class="footsinfoli"><a href="http://www.everyoo.com/team.html">爱悠团队</a></li>
                <li class="footsinfoli"><a href="http://www.everyoo.com/salesnetwork.html">销售网络</a></li>
                <li class="footsinfoli"><a href="http://www.everyoo.com/merchantsjoin.html">招商加盟</a></li>
                <li class="footsinfoli"><a href="http://www.everyoo.com/down.html">下载中心</a></li>
                <li class="footsinfoli"><a href="http://www.everyoo.com/sitemap.html">网站地图</a></li>
			</ul>
			<ul>
                <li class="footsli1">电话:0535-2105556</li><li class="footsli1">传真:0535-2103333</li>
			</ul>
			<ul>
                <li class="footsli2">山东省烟台市莱山经济开发区明达西路11号 惠通大厦2F</li>
			</ul>
			<ul>
                <li class="footsli2">山东智慧生活数据系统有限公司 鲁ICP备140332269号-1</li>
			</ul>
		</div>
		<div class="" style="float:left;margin-top:30px;">
			<img src="/static/everyoo/images/dianhua.png" style="padding-top:7px"/>
		</div>
		<!--footer公司信息结束-->
	</div>
</div>


@show
</body>
</html>
