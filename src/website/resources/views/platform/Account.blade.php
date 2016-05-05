<!html>
<html lang="zh-cn">
<head>
 <meta charset="utf-8"/>
<title>爱悠账号</title>
<link href="/static/platform/platformcss/bootstrap.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/account.css" rel="stylesheet"/>

</head>
<body>
<!--头部开始-->
<div class="head" style="">
<img src="/static/platform/platformimages/logo1.png"/>
</div>
<!--头部结束-->
<!--content开始-->
<div class="content"> 
<div class="cont-head"> 
<h4><a href="/user/center">用户中心&nbsp;>&nbsp;</a><font style="color:#ec680c">爱悠账号</font></h4>
<h6><a href="/user/center" style="color:#ec680c">
<?php
	if($_SESSION['user']['user']['email']!=''){
		echo $_SESSION['user']['user']['email'];
	}
	if($_SESSION['user']['user']['phone']!=''){
		echo $_SESSION['user']['user']['phone'];
	}
?></a>
&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/user/loginout">退出</a></h6>
</div>
<div class="form">
<div class="form-left">
<div>
	<?php
		if($_SESSION['user']['user']['id']!=''){
			// echo '<img src="/upfile/avatar/m_'. $_SESSION['user']['user']['id'] .'.jpg" class="headimg" style="height:160px;width:160px;">';
			$imghead = '/upfile/avatar/b_'. $_SESSION['user']['user']['id'] .'.jpg';
			if(file_exists($imghead)){
				echo '<img src="/upfile/avatar/head.png" style="height:160px;width:160px;">';
			}
			else{
				echo '<img src="/upfile/avatar/b_'. $_SESSION['user']['user']['id'] .'.jpg" style="height:160px;width:160px;">';
			}
		}
		else{
			echo '<img src="/upfile/avatar/head.png" class="headimg" style="height:160px;width:160px;">';
		}
	?>
	<a href="/user/headimg" style="margin-left:48%;margin-top:10px;font-size:14px;">&nbsp;&nbsp;&nbsp;&nbsp;修改头像</a>
</div>
</div>
<div class="form-right" style="padding-left:100px;">
<table cellspacing="0" cellpadding="0" style="font-size:14px;">
<tr><td class="righttd">爱悠账号:</td><td>
<?php
	if($_SESSION['user']['user']['email']!=''){
		echo $_SESSION['user']['user']['email'];
	}
	if($_SESSION['user']['user']['phone']!=''){
		echo $_SESSION['user']['user']['phone'];
	}
?>
</td></tr>
<tr><td class="righttd">昵称:</td><td>
<?php 
	if($_SESSION['user']['user_info']['nickname']==''){
		echo '(未设置)';
	}
	else{
		echo $_SESSION['user']['user_info']['nickname'];
	}
?>&nbsp;&nbsp;<a href="/user/nickname">设置</a>&nbsp;&nbsp;<img src="/static/platform/platformimages/unknown.png"></td></tr>
<tr><td class="righttd">邮箱:</td><td>
<?php
	if($_SESSION['user']['user']['email']==''){
		echo '(未设置)';
	}
	else{
		echo $_SESSION['user']['user']['email'];
	}
?>&nbsp;&nbsp;<a href="/user/bindemail">设置</a>&nbsp;&nbsp;<img src="/static/platform/platformimages/unknown.png"></td></tr>
<tr><td class="righttd">手机:</td><td>
<?php
	if($_SESSION['user']['user']['phone']==''){
		echo '(未设置)';
	}
	else{
		echo $_SESSION['user']['user']['phone'];
	}
?>&nbsp;&nbsp;<a href="/user/bindphone">设置</a>&nbsp;&nbsp;<img src="/static/platform/platformimages/unknown.png"></td></tr>
<tr><td class="righttd">密码:</td><td><a href="/user/changepassword">更改</a>&nbsp;&nbsp;<img src="/static/platform/platformimages/unknown.png"></td></tr>
<tr><td class="righttd">密保问题:</td><td>&nbsp;&nbsp;<a href="/user/securityquestion">设置密保问题</a>&nbsp;&nbsp;<img src="/static/platform/platformimages/unknown.png"></td></tr>

</table> 
</div>
</div>
</div>
<!--content结束-->
<!--页脚开始-->
<div class="foots">
	<br/>
	<br/>
	<p style="text-align:center;">  山东智慧生活数据系统有限公司 鲁ICP备140332269号-1</p>
</div>
<!--页脚结束-->
</body>
</html>