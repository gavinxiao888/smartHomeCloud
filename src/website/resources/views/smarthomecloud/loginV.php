<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="/static/platform/platformcss/style.css" />
<link rel="stylesheet" type="text/css" href="/static/platform/platformcss/bootstrap.min.css" />
<script type="text/javascript" src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/static/platform/js/bootstrap.min.js"></script>
<title>云平台登陆</title>
<script type="text/javascript">
function loginaction()//ajax验证登陆
{
	var user=document.getElementById("user");
	var pass=document.getElementById('pass');
	var twoweek=document.getElementById('twoweek');
	
	if(user.value=="")
	{
		alert('没有输入用户名');
		user.focus();
		return false;
	}
	else
	{
		var reg = /or/i; 
		if(reg.test(user.value))
		{
			alert('非法的用户名');
			user.focus();
			return false;
		}
		if(pass.value=="")
		{
			alert('没有输入密码');
			pass.focus();
			return false;
		}
		else
		{	
			var reg = /or/i; 
			if(reg.test(pass.value))
			{
				alert('非法的密码');
				pass.focus();
				return false;	
			}
			//这里应该有一些效果
			
			$.ajax(
			{
			type: "post",
			url: "/user/loginaction",
			data:{ user: user.value,pass:pass.value,twoweek:twoweek.checked },  
	
			success: function(data)
			{
//				 alert(data);
			   data = data.replace(/[\r\n\s]/g, '');
				switch(data)
				{
					case "-1":
					alert("IP被锁定,请稍后尝试");
					document.getElementById('login').onclick=function(){
					alert('IP被锁定,请稍后尝试');
					};
					setTimeout(UnlockIP,300000);
					break;
					case "0":
					alert("账户不存在");
					break;
					case "1":
					window.location.href="/user/center";
					break;
					case "2":
					alert("密码不对");
					break;			
				}
			},error:function (){                
                alert("请求失败,请重试");            
            }
			});
		}
	}
	
}
function UnlockIP()
{
	
document.getElementById('login').onclick=loginaction;
}

</script>
</head>

<body>
   <!--top部分开始-->
   <div class="loginDiv">
   
   		<div class="loginDiv0">
        
        	<div class="loginLogo">
            
            	<img src="/static/platform/platformimages/ayLogo.png" />
                
                <h3 class="loginLogoH3">一个账号，玩转所有爱悠服务！</h3>
                
                <p class="loginLogoP">爱悠云盒，爱悠网，爱悠服务</p>
            
            </div>
            
            <div class="loginLogoInput">
					<?php 
						if (!empty($_COOKIE['username'])||!empty($_COOKIE['password'])){
							echo '<input type="text" class="loginLogoInputText form-control" id="user" value="' .$_COOKIE['username']. '"/>';
							echo '<input type="password"  class="loginLogoInputTextEnd form-control" id="pass" value="' .$_COOKIE['password']. '"/>';
						}
						else{
							echo '<input type="text" class="loginLogoInputText form-control"  placeholder="邮箱/手机号码" id="user"/>';
							echo '<input type="password"  class="loginLogoInputTextEnd form-control" placeholder="密码" id="pass"/>';
						}
					?>
					<input type="button" class="loginBtn btn btn-warning" value="立即登录" onclick="loginaction()" id="login" />
				
             </div>
             
             <div class="loginReg">
             
             	<input type="checkbox" id="twoweek"/>两周内自动登录
                
                <a href="/user/forgotpassword">忘记密码?</a>
             
             </div>                
             <div class="loginLogoInput">          
                <a href="/user/registration" class="loginRegBtn btn btn-default" >注册爱悠帐号</a>           
            </div>
            <!-- 第三方登陆-->
            <div class="loginLogoInput" style="margin-top:15px;">
                <hr /><p align="center">使用第三方账号登陆</p>
                <div>
                <table>                
                <tr>
                <td style="padding:5px;"><a href="/weibologin"><img width="40px" height="40px" src="/static/platform/platformimages/thirdlogin1.png"/></a></td>
                <td style="padding:5px;"><a href=""><img width="40px" height="40px" src="/static/platform/platformimages/thirdlogin2.png"/></a></td>
                <td style="padding:5px;"><a href=""><img width="40px" height="40px" src="/static/platform/platformimages/thirdlogin3.png"/></a></td>
                </tr>
                </table>
                </div>                
            </div>
            <div class="loginFoter">
            
            	<p>简体丨繁体丨English丨   常见问题</p>
                
            <p class="bootsp">山东智慧生活数据系统有限公司 鲁ICP备140332269号-1</p>
            </div>
        
        </div>
   
   </div>
   <!--footer部分结束--> 
</body>
</html>
