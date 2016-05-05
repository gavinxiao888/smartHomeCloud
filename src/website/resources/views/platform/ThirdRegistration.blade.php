<!html>
<html lang="zh-cn">
<head>
 <meta charset="utf-8"/>
<title>注册爱悠账号</title>
 <link href="/static/platform/platformcss/bootstrap.css" rel="stylesheet"/>
 <link href="/static/platform/platformcss/registration.css" rel="stylesheet"/>
 <script src="/static/platform/js/jquery-1.9.1.min.js"></script>
 <script type="text/javascript">
	function re_email()//验证邮箱，返回状态
	{
		var email=document.getElementById("email");		
		
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
		if(!reg.test(email.value))
		{					
			// 邮箱不符合规则
			return 0;
		}
		else
		{
			// 邮箱符合规则
			return 1;
		}
	}
	function email()//验证邮箱，返回提示
	{
		var state=re_email();
		var msg=document.getElementById("showeamil");
		
		if(state==0)
		{
			showdiv(msg,"邮箱不符合规则");
		}else if(state==1)
		{
			hidediv();
		}
		
	}
	function re_pass()//验证密码，返回状态
	{
		var pass=document.getElementById("pass");
		// var reg =new RegExp("([a-zA-Z]{1,}{0-9]{1,})|([0-9]{1,}_)|([A-Za-z_]+){8,16}","g");
		// var reg = new RegExp("\w+","g");

		var reg =/^(([0-9]{1,}(?=[a-zA-Z_]+))|([a-zA-Z_]{1,}(?=[0-9_]+)))/;//这个地方要改写一下正则
//		var reg= /^([0-9]+[a-zA-Z_]+[A-Za-z_0-9]+)|([a-zA-Z_]+[0-9]+[A-Za-z_0-9]+)$/;
		var rst = reg.exec(pass.value);
		if(reg.exec(pass.value) != null)
		{		
			//验证成功
			return 1;
		}
		else
		{
			 // re_pass_length("pass","showpass")
			 //验证失败
			 return 0;
		}		
	}
	function pass()//验证密码，返回提示
	{
		var msg = document.getElementById("showpass");

		pass_length("pass","showpass");

		var state =re_pass();
		
		if(state==1)
		{
			// showdiv(msg,"输入的密码不符合规则");
			hidediv();
		}else if(state==0)
		{
			showdiv(msg,"输入的密码不符合规则");

		}
	}
	function pass_length(input,div)//验证密码长度，返回提示信息
	{
		var state=re_pass_length(input,div);
		var showpass=document.getElementById(div);
		switch(state)
		{
			case -1:
			 showdiv(showpass,"<font style='font-size:16px'>字符长度不够</font>");
			 break;
			case 0:
			hidediv();
			break;
			case 1:
			showdiv(showpass,"字符长度超出");	
			break;
		}
		
	}
	function re_pass_length(input,div)//验证密码长度,input是被验证的密码输入框，div是提示框。返回状态值
	{
		var pass=document.getElementById(input);

		var passlength=pass.value.length;
		
		if(passlength<8)
		{
			//
			return -1;
		}
		else if(passlength>16)
		{
			// 
			return 1;
		}
		else
		{
			// 
			return 0;
		}
	}
	function re_pass1()//验证第二次输入的密码，返回状态
	{
		var pass=document.getElementById("pass");
		var pass1=document.getElementById("pass1");

		if(pass1.value!=pass.value)
		{
			// showdiv(msg,"两次输入的密码不一致");
			return 0;
		}
		else
		{
			//两次输入的密码一致
			return 1;
		}
	}
	function pass1()//验证第二次输入的密码，返回提示
	{
		var msg = document.getElementById("showpass1");
		var state=re_pass1();
		
		if(state==1)//1表示第二次输入的密码和第二次输入的密码一致
		{
			hidediv();
			pass();//这个地方还要判断第一次输入的密码格式

		}else if(state==0)
		{
			showdiv(msg,"两次输入的密码不一致");
		}
		
	}
	function code()//验证验证码，返回提示信息
	{	
		re_code();
	}
	function re_code()//ajax验证验证码
	{
		var code= document.getElementById("code");
		if(code.value.length==4)
		{
			$.ajax(
				{
				type: "post",
				data:{ code: code.value},  
				url: "/user/code",
				success: function(data)
				{
					data = data.replace(/\r\n/g,'');
					var showcode=document.getElementById("showcode");		
					switch(data)
					{		
						case "0":			
						showdiv(showcode,"验证码已过期");
						setTimeout(changeCode,1000);		
						break;
						case "1":
						document.getElementById("iok").style.display="block";
						hidediv(showcode);
						break;
						case "2":
						document.getElementById("iok").style.display="none";
						showdiv(showcode,"输入验证码不符");				
						break;	
						
						default:		
						break;
					}	
				},error:function ()
				{                
					return 0;
				}
				});
		}
	
	}
	function registration()
	{
	//这里要有一个遮罩层的效果的
	if(re_email() == false) {
		alert('邮箱不符合规则');
		$('#email')[0].focus();
		return false;
	}
	if(re_pass() == false) {
		alert('密码不符合规则');
		$('#pass')[0].focus();
		return false;
	}
	if(re_pass1() == false) {
		alert('两次输入的密码不一致');
		$('#pass1')[0].focus();
		return false;
	}
	$.ajax({
		type: 'post',
		url: '/user/registrationplatform',
		data:{email: $('#email').val(), pass: $('#pass').val(), pass1: $('#pass1').val(), code: $('#code').val()},
		success: function(data) {
			data = data.replace(/\r\n/g,'');
			var showcode = document.getElementById('showcode');
			var showemail=document.getElementById('showeamil');
			var showpass=document.getElementById('showpass');
			var showpass1=document.getElementById('showpass1');		

			alert(data);
			switch(data) {
				case '-1':
					hide();
					alert('位置的错误发生了，请重试');
					break;
				case '0':
					hidediv();
					showdiv(showcode,'验证码已过期');
					setTimeout(changeCode,1000);
					break;
				case '1':
					window.location.href="/user/center";
					break;
				case '2':
					hidediv();
					showdiv(showcode,'输入验证码不符');
					break;
				case '3':
					hidediv();
					showdiv(showemail,"邮箱不匹配");
					break;
				case "4":
					hidediv();
					showdiv(showpass,'<font style="font-size:16px">密码不符合规则</font>');
					break;
				case "5":
					hidediv();
					showdiv(showpass1,"第二次输入的密码不对");
					break;
				case '6':
					hidediv();
					showdiv(showemail,'该邮箱已被注册');
					break;
				case '7':
					hidediv();
					alert('密码为弱密码类型,请您修改的您的密码');
					break;
				default:
				break;
			}
		}, error: function () {
			return false;
		}
	});
}
	function showdiv(msg,msginfo)//msg参数为需要提示的div,msginfo为提示信息
	{
		msg.style.display="block";
		msg.innerHTML=msginfo;
	}
	function hidediv()//隐藏一个div,msg为div的ID
	{
		document.getElementById('showcode').style.display="none";
		document.getElementById('showeamil').style.display="none";
		document.getElementById('showpass').style.display="none";
		document.getElementById('showpass1').style.display="none";		
		
	}
	function changeCode()//验证码
	{		
		document.getElementById("codeimg").src = "/code/platformcode?id="+Math.random();
		hidediv(document.getElementById("iok"));
	}
    function keyUp(e) {
       var currKey=0,e=e||event;
       currKey=e.keyCode||e.which||e.charCode;
       var keyName = String.fromCharCode(currKey);
       // alert("按键码: "+ currKey + " 字符: " + keyName);
	   if(currKey==13)
	   {
		registration();
	   }
   }
	  document.onkeyup  = keyUp ;
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
	<h4 style="color:#ec680c;margin-left:30px;font-weight:bold">欢迎您！
	<?php 
		if(!empty($name)){
			echo $name;
		}
	?>
	</h4>
	<p style="margin-left:30px;">注册爱悠账号可以享受爱悠的一切服务。<a href="/user/login" style="color:#CC9933;"></a></p>
</div>
<div class="form">
<table  cellspacing="0" cellpadding="0" >
	<tr>
		<td class="span">邮箱:</td>
		<td style="width:300px"><input class="form-control" type="email" id="email" style="width:300px" onBlur="email()"/></td><td><div id="showeamil" style="display:none;color:red"></div></td>
	</tr>
	<tr>
		<td class="span">设置密码:</td>
		<td style="width:300px"><input style="width:300px" class="form-control" type="password" id="pass" onBlur="pass()" onkeyup="pass_length('pass','showpass')"/></td>
		<td style="overflow:hidden;font-size:14px;"><div id="showpass" style="color:red;text-align:left;">长度为8-16位，至少包含数字、字母、下划线的两种</div></td>
	</tr>
	<tr>
		<td class="span">确认密码:</td>
		<td style="width:300px"><input style="width:300px" class="form-control" type="password" id="pass1" onKeyUp="pass1()"/></td>
		<td><div id="showpass1" style="color:red"></div></td>
	</tr>
	<tr>
		<td class="span">验证码:</td>
		<td><input class="form-control" type="text" id="code" onKeyUp="code()" onBlur="code()" style="width:100px;display:block;float:left;"/>&nbsp;&nbsp;&nbsp;&nbsp;<img src="/code/platformcode" style="width:100px;height:34px;float:left;margin-left:10px;float:left;padding-left:5px;" id="codeimg" title="点击图片刷新验证码" /><span class="iron" onClick="changeCode()"></span><span class="iok" id="iok"></span></a></td>
		<td><div id="showcode" style="color:red"></div></td>
	</tr>
	<tr>
		<td class="span"></td>
		<td><a type="button" style="background:#ec680c;float:left;display:block;width:109px;line-height:30px;margin:0 auto;border-radius:5px;text-align:center" onClick="registration()"><font style="color:#ffffff">确认</font></a></td>
		<td></td>
	</tr>
</table>
<p style="text-align:center">点击立即注册，即表示您同意并愿意遵守爱悠<a href="/user/protocol" style="color:#CC9933">用户协议</a>和<a href="/user/policy" style="color:#CC9933">隐私政策</a></p>
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