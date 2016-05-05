<!html>
<html lang="zh-cn">
<head>
 <meta charset="utf-8"/>
<title>更改密码</title>
<link href="/static/platform/platformcss/bootstrap.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/changepass.css" rel="stylesheet"/>
<script src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script>
function reg_passwd()//验证密码
{
	$.ajax(
	{
		type:"post",

		data:{ oldpass:document.getElementById("oldpass").value},
		url:"/user/reg_passwd",
		
		success:function(data)
		{
		    data = data.replace(/\r\n/g,'');
			switch(data)
			{
				case "0":
				oldpass.focus();
				showdiv(document.getElementById('oldpassshow'),'密码不匹配');
				return false;
				break;
				case "1":
				hidediv();
				break;
			
			}
		},
		error:function()
		{
			alert('23');
		}
	
	});
}


	function re_pass()//验证密码，返回状态
	{
		var pass=document.getElementById("pass");
		// var reg =new RegExp("([a-zA-Z]{1,}{0-9]{1,})|([0-9]{1,}_)|([A-Za-z_]+){8,16}","g");
		// var reg = new RegExp("\w+","g");
		var reg =/^[a-z0-9_-]{8,16}$/;//这个地方要改写一下正则

		if(reg.test(pass.value))
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
		var state =re_pass();
		
		if(state==1)
		{
			// showdiv(msg,"输入的密码不符合规则");
			hidediv();
		}else if(state==0)
		{
			showdiv(msg,"输入的密码不符合规则");
			pass_length("pass","showpass")
		}
	}
	function pass_length(input,div)//验证密码长度，返回提示信息
	{
		var state=re_pass_length(input);
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
	function re_pass_length(input)//验证密码长度,input是被验证的密码输入框。返回状态值
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
		
		if(state==1)
		{
			hidediv();
		}else if(state==0)
		{
			showdiv(msg,"两次输入的密码不一致");
		}
		
	}
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
	function passeditaction()//修改密码的动作
	{
		if(!re_pass_length('pass')&&re_pass1()&&re_pass())//要先验证一下
		{
			//这里要有一个遮罩层的效果的
			oldpass = document.getElementById("oldpass");
			pass = document.getElementById("pass");
			pass1 = document.getElementById("pass1");
			
			$.ajax(
			{
				type:"post",
				url:"/user/password/editaction",
				data:{oldpass:oldpass.value,pass:pass.value,pass1:pass1.value},
				success:function(data)
				{
					// alert(data);
					data = data.replace(/\r\n/g,'');
					switch(data)
					{
						// case 0
						case "-1":
						alert('密码未做任何更改');
						return false;
						break;
						case "0":
						alert('输入的原密码不匹配');
						oldpass.focus();
						return false;
						break;
						case "1":
						var r=confirm("您已成功修改密码。是否跳转到个人中心")
						if(r==true)
						{
							window.location.href = "/usercenter";
						}
						break;
						case "2":
						alert('您输入的新密码不符合要求哦');
						pass.focus();
						break;
						case "3":
						alert('两次输入的密码不一致哦，检查一下吧');
						pass1.focus();
						break;
					}
				},
				error:function()
				{
					alert('未知错误');
				}
			
			});
		
		}
		else
		{
			return false;
		}
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
<h4>爱悠账号&nbsp;>&nbsp;<span>修改密码</span> </h4>
<h6>zzx11235&nbsp;&nbsp;<a href="/user/loginout">|&nbsp;&nbsp;退出</a></h6>
</div>
<div class="form">
<table  cellspacing="0" cellpadding="0" >
<tr><td></td><td>请重设您的密码</td><td></td></tr>
<tr><td class="span">当前密码:</td><td><input style="width:300px" class="form-control" type="password" name="oldpass" id="oldpass"  onBlur="reg_passwd()"/></td><td><div id="oldpassshow" style="color:red;text-align:left;"></div></td></tr>
<tr><td class="span">新密码:</td><td><input style="width:300px" class="form-control" type="password" id="pass"  onkeyup="pass_length('pass','showpass')" onBlur="pass()"/></td><td style="font-size:12px;"><div id="showpass" style="color:red;text-align:left;">长度为8-16位，至少包含数字、字母、下划线的两种</div></td></tr>
<tr><td class="span">再次输入新密码:</td><td><input style="width:300px" class="form-control" type="password" id="pass1" onkeyup="pass1()"/></td><td><div id="showpass1" style="color:red;text-align:left;"></div></td></tr>

<tr><td class="span"></td><td><button type="button" class="btn btn-warning" style="float:left;display:block;width:79px;height:30px;background:#ec680c;" onclick="passeditaction()"><font style="color:white">确认</font></button></td><td></td></tr>
</table>
</div>
</div>
<!--content结束-->
<!--页脚开始-->
<div class="footer">
<br/>
<br/>
<p style="margin:0 auto;width:200px;">爱悠公司版权所有</p>
</div>
<!--页脚结束-->
</body>
</html>