<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>

<script type="text/javascript" src="/site/js/jquery-1.11.1.js"></script>
<style>
body{background-color:#41a8ec;font-family:"微软雅黑";}
a{text-decoration:none;}
.nber{width:461px;height:420px;margin:0 auto;background-image:url(/site/images/1.png);background-repeat:no-repeat;}
.bwtp{width:239px;height:37px;margin:0 auto;background-image:url(/site/images/2.png);background-repeat:no-repeat;text-align:center;overflow:hidden;}
.wtbre{width:239px;height:35px;margin:0 auto;margin-top:14px;}
.erty{width:128px;height:35px;float:left;text-align:center;overflow:hidden;background-image:url(/site/images/3.png);background-repeat:no-repeat;}
.rtntp{width:83px;height:31px;float:right;padding-top:3px;}
.rpth{font-size:12px;color:white;float:left;}
.wtrwe{font-size:12px;color:white;float:right;}
.vbwe{width:111px;height:31px;margin:0 auto;}
.wghrw{width:250px;height:30px;margin:0 auto;text-align:center;color:white;font-size:12px;padding-top:13px;}
.rwtw{width:150px;margin:0 auto;}
</style>
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
//               alert(data);
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
<div class="nber">
	<div style="width:400px;height:86px;margin:0 auto;"></div>
    <?php 
        if (!empty($_COOKIE['username'])||!empty($_COOKIE['password'])){
            echo '<div class="bwtp"><input type="text" id="user" value="' .$_COOKIE['username']. '" style="width:96%;height:26px;margin:0 auto;margin-top:5px;border:none;" placeholder="&nbsp;&nbsp;邮箱"></div>';
            echo '<div class="bwtp"><input type="password" id="pass" value="' .$_COOKIE['password']. '" style="width:96%;height:26px;margin:0 auto;margin-top:5px;border:none;" placeholder="&nbsp;&nbsp;邮箱"></div>';
        }
        else{
           echo '<div class="bwtp"><input type="text" id="user" style="width:96%;height:26px;margin:0 auto;margin-top:5px;border:none;" placeholder="&nbsp;&nbsp;邮箱"></div>';
            echo '<div class="bwtp"><input type="password" id="pass" style="width:96%;height:26px;margin:0 auto;margin-top:5px;border:none;" placeholder="&nbsp;&nbsp;邮箱"></div>';
        }
    ?>
	
    <div class="wtbre">
    	<div class="erty"><input type="text" style="width:96%;height:26px;margin:0 auto;margin-top:4px;border:none;" placeholder="&nbsp;&nbsp;验证码"></div>
        <div class="rtntp"><img src="/site/images/4.png" width="83" height="31"></div>
    </div>
    <div class="wtbre">
    	<a href="/user/forgotpassword"><div class="rpth">忘记密码？</div></a>
        <a href="/user/registration"><div class="wtrwe">立即注册</div></a>
    </div>
    <div class="vbwe"><a href=""><img onclick="loginaction()" id="login" src="/site/images/5.png" width="111" height="31"></a></div>
    <div class="wghrw">使用合作账号一键登录</div>
    <div class="rwtw">
    	<a href=""><img src="/site/images/6.png" height="39" width="39"></a>
        <a href="" style="margin-left:10px;margin-right:10px;"><img src="/site/images/7.png" height="39" width="39"></a>
        <a href="/weibologin"><img src="/site/images/8.png" width="37" height="37"></a>
    </div>
</div>
</body>
</html>
