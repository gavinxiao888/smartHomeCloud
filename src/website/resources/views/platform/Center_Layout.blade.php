<!html>
<html lang="zh-cn">
<head>
 <meta charset="utf-8"/>
<title>个人中心</title>
<link href="/static/platform/platformcss/center.css" rel="stylesheet"/>
<script src="/static/platform/js/jquery-1.9.1.min.js"></script>
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
</script>
</head>
<body>

<!--头部开始-->
<div class="head">
<p style="height:30px;line-height:30px;overflow:hidden; ">欢迎您<span>zzx11235</span>&nbsp;&nbsp;|<a href="/personalcenter/device">我的设备</a>|&nbsp;&nbsp;<a href="/user/account">我的爱悠账号</a ></p>
</div>
<!--头部结束-->
<!--content开始-->
<div class="content">
<!--搜索栏-->
<div class="sibar">
<img src="/static/platform/platformimages/logo2.png" class="sibar-logo" />
<div class="sibar-right">
 <div class="input-group" style="width:270px"> 
<input class="form-control" type="email" placeholder="搜一下">   
<div class="input-group-addon"><a><img src="/static/platform/platformimages/sibar.png" /></a></div>
</div>

<div class="cart">
<a ><img src="/static/platform/platformimages/cart.png" height="26" style="position: absolute; top: 6px;left: 15px;">&nbsp;<font style="color:white;font-size:12px;  height: 30px;line-height: 30px;">购物车</font></a>
</div>
</div>
</div>
<!--搜索栏结束-->
<!--头部导航开始-->
<div class="navi" style="">
<ul id="nav">
    <li class="mainlevel" style="width:200px;margin-left:-40px;"><a href="">全部商品分类&nbsp;<img src="/static/platform/platformimages/arrow-white.png"/></a>
        <ul>
        	<li  style="width:200px;"><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>


        </ul>
    </li>
    
    <li class="mainlevel" style="background:#404144"><a href="">首页</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
    
    <li class="mainlevel" style="background:#404144"><a href="">智慧云盒</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
    <li class="mainlevel" style="background:#404144"><a href="">智慧云投影</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
	    <li class="mainlevel" style="background:#404144"><a href="">智慧云平板</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
	    <li class="mainlevel" style="background:#404144"><a href="">智慧云摄像头</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
	    <li class="mainlevel" style="background:#404144"><a href="">服务</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
        <li class="mainlevel" style="background:#404144"><a href="">在线留言</a>
        <ul>
            <li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
</ul>
</div>
<!--头部导航结束-->

<!--位置导航-->
<div class="position">
<h5><a>首页</a>&nbsp;/&nbsp;<a>个人中心</a></h5>
</div>
<!--位置导航结束-->

<!--左侧导航开始-->
<div class="navi-left">
<ul>
    <li style=""><a  class="line" href="">个人中心</a> </li>

    <li class="hit"><a href="/personalcenter/personalcenter" >我的个人中心</a></li>
    <li ><a class="" href="/personalcenter/device" >我的设备</a></li>
    <li ><a class="" href="" >我的增值服务</a></li>

   
</ul>    
<ul>
    <li class="line" style=""><a href="/custom_service/center">售后服务</a></li>
    <li><a href="/custom_service/repair" >我的维修</a></li>
    <li><a href="/custom_service/return" >我的退换货</a></li>
    <li><a href="/custom_service/appointment" >我要预约服务</a></li>

    
</ul>
<ul>
    <li class="line" style=""><a href="">账号管理</a> </li>

	<li><a href="" >收货地址管理</a></li>
    <li><a href="" >修改密码</a></li>
</ul>
</div>
<!--左侧导航结束-->
@yield('content')
<!--content结束-->


</body>
</html>