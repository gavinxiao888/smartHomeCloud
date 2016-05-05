<!-- create: 2015.11.21
后台管理主界面
 马学杰 -->

<!DOCTYPE html>
<html>
<meta name="renderer" content="webkit" />
<link rel="stylesheet" type="text/css" href="/site/css/add_main.css">
<link rel="stylesheet" type="text/css" href="/site/css/adminStyle.css">
<script src="/site/js/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script>
<script src="/layer/layer.js" type="text/javascript" charset="utf-8"></script>
<head>
    <title>编辑故障信息</title>
</head>
<body>
    <div class="andy-1">
        <div class="andy-3">
            <input type="hidden" id="uid" value="<?php if(!empty($admin)) {echo $admin->id;}?>" />
            <div class="andy-4">用户姓名</div>
            <div><input class="andy-5" type="text" id="nickname" value="<?php if(!empty($admin)) {echo $admin->nickname;}?>"/></div>
            <div class="andy-4">用户名</div>
            <div><input class="andy-5" type="text" id="username" value="<?php if(!empty($admin)) {echo $admin->username;}?>"/></div>
            <div class="andy-6">
                <div class="andy-4">手机号</div>
                <div><input class="andy-7" type="text" id="phone" value="<?php if(!empty($admin)) {echo $admin->phone;}?>"/></div>
            </div>
            <div class="andy-8">
                <div class="andy-4">性别</div>
                <div>
                    <input type="radio" name="sex" value="1" <?php if(!empty($admin)) { if($admin->sex == 1){ echo "checked='true'";}}?>>男
                    <input type="radio" name="sex" value="2" <?php if(!empty($admin)) { if($admin->sex == 0){ echo "checked='true'";}}?> style="margin-left:12px;">女
                </div>
            </div>
            <div style="clear:both;"></div>
            <div class="andy-4">邮箱</div>
            <div><input class="andy-5" type="text" id="email" value="<?php if(!empty($admin)) {echo $admin->email;}?>"/></div>
            <div class="andy-4">新密码</div>
            <div><input class="andy-5" id="password" type="password" /></div>
            <div style="margin-top:10px; text-align:center; width:100%;"><input type="button" onclick="btnOk()" class="a_click submitInput" value="提交修改" /></div>
    </div>
    <script type="text/javascript">
        var count = 0;
         var s3 = /^[\w\W]{3,16}$/;
        var pass = /^[\w\W]{6,16}$/;
        var email = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        var phone = /^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/;
        function verNickname() {
            if (s3.test($('#nickname').val())) {
                return true;
            } else {
                return false;
            }
        }

        function verUsername() {
              if (s3.test($('#username').val())) {
                return true;
            } else {
                return false;
            }
        }

        function verPassword() {
            if (pass.test($('#password').val()) || ($('#password').val() == '')) {
                return true;
            } else {
                return false;
            }
        }

        function verEmail() {
            if (email.test($('#email').val())) {
                return true;
            } else {
                return false;
            }
        }

        function verPhone() { 
            if (phone.test($('#phone').val())) {
                return true;
            } else {
                return false;
            }
        }

        function btnOk() {
         if (verNickname() && verUsername() && verPassword() && verEmail() && verPhone()) {
            // console.log('yes');
             var sex = $(':radio[name="sex"]:checked').val();
                $.ajax({url:"/admin/edit/admin",
                     type:"post",
                     data:{username:$("#username").val(),
                           password:$("#password").val(),
                           nickname:$("#nickname").val(),
                           phone:$("#phone").val(),
                           email:$("#email").val(),
                           id:$("#uid").val(),
                           sex:sex},
                     success:function(data){
                            console.log(data);
                            if (data == 3){
                                layer.alert('存在重复的用户名');
                            } else if (data == 4) {
                                layer.alert('请填补全内容！');
                            } else if (data == 2) {
                                layer.alert('存在重复的手机号！');
                            } else if (data == 1) {
                                window.parent.location.reload();
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                            }
                     },error:function(){
                            layer.alert('网络错误，请检查您的网络是否畅通!');
                     }
                });
            } else {
                 layer.alert('内容格式错误，请重新填写。');
                // console.log('');
            }
            // if (code == 1) {
               
            // }
    }
    </script>
</body>
</html>