<!html>
<html lang="zh-cn">
<head>
 <meta charset="utf-8"/>
<title>修改昵称</title>
<link href="/static/platform/platformcss/bootstrap.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/changepass.css" rel="stylesheet"/>
<script src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script>
	
	function showdiv(msg,msginfo)//msg参数为需要提示的div,msginfo为提示信息
	{
		msg.style.display="block";
		msg.innerHTML=msginfo;
	}
	function hidediv()//隐藏一个div,msg为div的ID
	{
		document.getElementById('oldpassshow').style.display="none";
		document.getElementById('showpass').style.display="none";
		document.getElementById('showpass1').style.display="none";		
		
	}
	function passeditaction()//修改昵称的动作
	{
		var nickname = $('#nickname').val();
		//alert(nickname);
		$.ajax(
		{
			type:"post",
			url:"/user/changenickname",
			data:{nickname:nickname},
			success:function(data)
			{
				//alert(data);
				data = data.replace(/\r\n/g,'');
				switch(data)
				{
					case "0":
					alert('输入的格式不正确');
					nickname.focus();
					return false;
					break;
					case "1":
					var r=confirm("您已成功修改昵称。是否跳转到个人中心")
					if(r==true)
					{
						window.location.href = "/user/center";
					}
				}
			},
			error:function()
			{
				alert('未知错误');
			}
		
		});
	}
</script>
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
<h4><a href="/user/account">爱悠账号</a>&nbsp;>&nbsp;<span>修改昵称</span> </h4>
<h6><?php
	if($_SESSION['user']['user']['email']!=''){
		echo $_SESSION['user']['user']['email'];
	}
	if($_SESSION['user']['user']['phone']!=''){
		echo $_SESSION['user']['user']['phone'];
	}
?>&nbsp;&nbsp;<a href="/user/loginout">|&nbsp;&nbsp;退出</a></h6>
</div>
<div class="form">
<table  cellspacing="0" cellpadding="0" >
    <tr><td></td><td>请设置您的昵称</td><td></td>
    </tr>
    <tr><td class="span">当前昵称:</td><td><input style="width:300px" class="form-control" type="text" name="oldpass" id="oldpass" value="<?php 
	$userInfo = DB::TABLE('user_info')->where('id', $_SESSION['user']['user_info']['id'])->first();
	echo $userInfo['nickname'];
	?>" readonly="true"/></td><td><div id="oldpassshow" style="color:red;text-align:left;"></div></td>
    </tr>
    <tr><td class="span">新昵称:</td><td><input style="width:300px" class="form-control" type="text" id="nickname" name="nickname" /></td>
    <!--<td style="font-size:12px;"><div id="showpass" style="color:red;text-align:left;">长度为8-16位，至少包含数字、字母、下划线的两种</div></td>-->
    </tr>
    <tr><td class="span"></td><td>
	<button type="button" class="btn btn-warning" style="float:left;display:block;width:79px;height:30px;background:#ec680c;" onClick="passeditaction()"><font style="color:white">确认</font></button></td><td></td>
    </tr>
</table>
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