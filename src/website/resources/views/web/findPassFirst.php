<html lang=”zh-CN”>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width">
    <link type="text/css" rel="stylesheet" href="/site/css/mainMobile.css" />
<script type="text/javascript" src="/site/js/jquery-1.11.1.js"></script>
<script src="/layer/layer.js" type="text/javascript" charset="utf-8"></script>
<title>找回密码</title>
</head>

<body>
	<div class="titleBody" align="center">
    	<div class="titleContent">
         	<img src="/site/images/findPassLogo.png" />
        </div>
    </div>
    
    <div class="contentBody" align="center">
    	<div class="fincPassContent" align="center">
        	<h3 class="findPassContentTitle">
            	找回密码
            </h3>
            <h1>1、输入邮箱或手机</h1>
<!--            <img class="findPassContentImg" src="/site/images/findPassImgFirst.png" />-->
            <div class="findPassContentDiv">
            	<div class="findPassContentLeft">
                	请选择注册类型
                </div>
                <div class="findPassContentRight">
                	<select class="findPassContentSelect" align="center">
                    	<option id='1'>手机</option>
                    	<option id='2'>邮箱</option>
                    </select>
                </div>
            </div>
            <div class="findPassContentDiv">
            	<div class="findPassContentLeft">
                	请输入注册手机或邮箱
                </div>
                <div class="findPassContentRight">
                	<input class="findPassContentText" id="user" name="user" maxlength="32" type="text" />
                </div>
            </div>
            <div class="findPassContentDiv">
            	<div class="findPassContentLeft">
                	验证码
                </div>
                <div class="findPassContentRight">
                	<input class="findPassContentText" id="code" name="code" maxlength="8" type="text" />
                    <img class="findPassContentCodeImg" style="cursor:pointer;" src="/site/checkcode.php" />
                </div>
            </div>
            <div class="findPassContentDiv">
            	<input class="findPassContentButton" type="button" value="确定" />
            </div>
             <script type="text/javascript">
                $('.findPassContentCodeImg').click((function(event) {
                       $(this).attr("src",'/site/checkcode.php?' + Math.random()); 
                    }));
                $('.findPassContentButton').click(function(event) {
                    var type = $(".findPassContentSelect>option:selected").attr("id");
                    console.log(type);
                     $.ajax({url:"/user/find/pass",
                         type:"post",
                         data:{user:$("#user").val(),
                               type:type,
                               code:$("#code").val()},
                         success:function(data){
                                console.log(data);
                                if (data == 2) {
                                    layer.alert('验证码发送失败，请稍候再试。');
                                } else if (data == 3) {
                                    layer.alert('不存在该用户，请重新输入。');
                                } else if (data == 4) {
                                    layer.alert('非法操作！');
                                } else if (data == 5) {
                                    layer.alert('输入的验证码不正确');
                                } else {
                                    location.href='/user/forgotpassword/second?code='+data;
                                }
                         },error:function(){
                                layer.alert('网络错误，请检查您的网络是否畅通!');
                         }
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>
