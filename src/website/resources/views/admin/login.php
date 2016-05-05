<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="renderer" content="webkit" />
<link rel="stylesheet" type="text/css" href="/site/css/four.css" />
<script src="/site/js/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script>
<script src="/layer/layer.js" type="text/javascript" charset="utf-8"></script>
<title><?php echo env('ADMIN_TITLE');?></title>
</head>

<body>
<div class="login_wmpw"><img src="/site/images/<?php echo env('LOGIN_LOGO');?>"></div>
<div class="login_qgkwp">
	<div class="login_mnepo">用户名</div>
    <div class="login_iupw"><input type="text" id="username" class="login_input" /></div>
    <div class="login_mnepo">密码</div>
    <div class="login_iupw"><input type="password" id="password" class="login_input" /></div>
    <div class="login_vewpj"><a class="a_click" id="btnSumit" onclick="btnSubmit()"><img src="/site/images/login_new_4.png"></a></div>
</div>

    <script type="text/javascript">
	    function btnSubmit() {
	         $.ajax({url:"/admin/login",
	                 type:"post",
	                 data:{username:$("#username").val(),
	                       password:$("#password").val()},
	                 success:function(data){
	                        console.log(data);
	                        if (data == 2) {
	                            layer.alert('用户名或密码错误');
	                        } else if (data == 1) {
	                            location.href = "/admin/index/show";
	                        } else if (data == 3) {
	                            layer.alert('非法用户！');
	                        }
	                 },error:function(){
	                        layer.alert('网络错误，请检查您的网络是否畅通!');
	                 }
	        });
   		}
   		$("body").keydown(function(event) {
		    if (event.keyCode == "13") {//keyCode=13是回车键
		        $('#btnSumit').click();
		    }
		});   
    </script>
    <!-- <a href=""><div class="login_topwe">立即注册</div></a> -->
</body>
</html>
