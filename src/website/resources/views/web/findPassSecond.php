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
            <input type="hidden" value="<?php if (!empty($code)) {echo $code;}?>" id="code" />
        	<h3 class="findPassContentTitle">
            	找回密码
            </h3>
            <h1>2、验证密保</h1>
<!--            <img class="findPassContentImg" src="/site/images/findPassImgSencond.png" />-->
            <div class="findPassContentDiv">
            	<div class="findPassContentLeft">
                	请输入验证码
                </div>
                <div class="findPassContentRight">
                	<input class="findPassContentText" id="verCode" name="verCode"  maxlength="8" type="text" />
                </div>
            </div>
            <div class="findPassContentDiv">
            	<div class="findPassContentLeft">
                	请输入新密码
                </div>
                <div class="findPassContentRight">
                	<input class="findPassContentText" id="newPass1" name="newPass1" maxlength="16" type="password" />
                </div>
            </div>
            <div class="findPassContentDiv">
            	<div class="findPassContentLeft">
                	再次输入新密码
                </div>
                <div class="findPassContentRight">
                	<input class="findPassContentText" id="newPass2" name="newPass2" maxlength="16" type="password" />
                </div>
            </div>
            <div class="findPassContentDiv">
            	<input class="findPassContentButton" type="button" value="确定" />
            </div>
            <script type="text/javascript">
                $('.findPassContentButton').click(function(event) {
                    if ($('#newPass1').val() == $('#newPass2').val()) {
                          $.ajax({url:"/user/find/pass/edit",
                             type:"post",
                             data:{user:$("#code").val(),
                                   code:$('#verCode').val(),
                                   pass:$('#newPass1').val()},
                             success:function(data){
                                    console.log(data);
                                    if (data == 2) {
                                        layer.alert('修改失败，请稍后再试。');
                                    } else if (data == 3) {
                                        layer.alert('验证码错误！');
                                    } else if (data == 4) {
                                        layer.alert('非法操作！');
                                    } else {
                                        location.href='/user/forgotpassword/third?code='+data;
                                    }
                             },error:function(){
                                    layer.alert('网络错误，请检查您的网络是否畅通!');
                             }
                        });
                    } else {
                        layer.alert('两次密码输入不相同。');
                    }
                });
            </script>
        </div>
    </div>
</body>
</html>
