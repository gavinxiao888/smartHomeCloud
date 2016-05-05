<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/site/css/four.css">
<script type="text/javascript" src="/site/js/jquery-1.11.1.js"></script>
</head>

<body>
@section('default')
<div style="width:1200px;margin:0 auto;">
	<div class="gwerbv">
    	<div class="eweg"><img src="/site/images/de_03.png" width="181" height="47"></div>
        <div class="wgerw"><img src="/site/images/de_05.png" width="528" height="47"></div>
        <ul>
        	<li><img src="/site/images/de_08.png" width="19" height="19"></li>
            <li>欢迎您，admin</li>
            <li>|</li>
            <a href=""><li>消息</li></a>
            <li>|</li>
            <a href=""><li>帮助</li></a>
            <li>|</li>
            <a href=""><li>退出</li></a>
        </ul>
    </div>
    <div class="ewnfd">
    	<div class="bwepr"><img src="/site/images/de_18.png" width="18" height="18">设备管理</div>
        <div class="bewoi">设备列表</div>
        <div class="bewoi" style="border-top:1px solid #e6e6e6;">添加设备</div>
        <div class="bwepr"><img src="/site/images/de_34.png" width="13" height="18">个人中心</div>
        <div class="bewoi">账号设置</div>
        <div class="bewoi" style="border-top:1px solid #e6e6e6;">收获地址管理</div>
        <div class="bwepr" style="border-radius:5px;margin-top:32px;"><img src="/site/images/de_41.png" width="25" height="24">添加新设备</div>
    </div>
    <div class="hbrep">
    	<div style="width:960px;height:27px;margin-top:15px;">
        	<div class="bewep"><img src="/site/images/de_13.png" width="18" height="19">当前位置：智慧生态圈>管理</div>
            <div class="whter"><img src="/site/images/de_15.png" width="18" height="19">今天是2014年12月29日&nbsp;星期一</div>
        </div>
        @yield('content')
    </div>
</div>
@show
</body>
</html>
