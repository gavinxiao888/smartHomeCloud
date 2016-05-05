<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
<style>
.Validform_checktip{margin:4px 0;}
</style>
<link rel="stylesheet" href="/site/css/style.css" type="text/css" media="all" />
<link href="/site/css/demo.css" type="text/css" rel="stylesheet" />
 <div class="gzhlb_kpejh">
        <div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/user/show">用户管理</a>>
            <a href="/admin/admin/new">新增管理员</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--新增用户开始-->
<div class="gzhlb_pwen">新增用户</div>
<div class="clear"></div>
<div class="tsxx_jkpw">
    <form class="add_admin">
    <div class="tsxx_iupw">用户姓名
        <input id="nickname" name="nickname" type="text" datatype="s3-16" nullmsg="请输入用户姓名！" errormsg="用户姓名至少3个字符,最多10个字符！" class="text_admin inputxt" style="width:268px;">
        <div class="Validform_checktip" style="margin-left:122px;">用户姓名为3~16个字符</div>
    </div>
    
    <div class="xzyh_noyu">
    用户名<input id="username" name="username" type="text" datatype="s3-16" nullmsg="请输入用户名！" errormsg="用户名至少3个字符,最多10个字符！"  class="text_admin inputxt" style="width:180px;">
        <div class="Validform_checktip" style="margin-left:100px;">用户名范围在3~16位之间</div>           
    </div>
    <div class="xzyh_nopl">
        密码<input id="password" name="password" class="text_admin inputxt" datatype="s6-18" nullmsg="请输入密码！" errormsg="密码至少6个字符,最多18个字符！"  style="width:180px;" type="password" maxlength="16" />
        <div class="Validform_checktip" style="margin-left:100px;">密码范围在6~16位之间</div>
    </div>
    <div class="clear"></div>
    <div class="xzyh_njouy">
        邮箱<input id="email" name="email" type="text" datatype="e" nullmsg="请输入邮箱！" errormsg="邮箱格式不正确！"   class="text_admin inputxt" style="width:180px;">
        <div class="Validform_checktip" style="margin-left:80px;">请填写邮箱！</div>
    </div>
    <div class="xzyh_nopl" style="line-height:46px;">性别<input type="radio" type="text" checked="true" value="1" name="sex" style="margin-left:24px;margin-right:12px;">男
            <input type="radio" value="2" name="sex" style="margin-right:12px;margin-left:29px;">女</div>
    <div class="clear"></div>
    <div class="xzyh_noyu">
        手机号<input id="phone" name="phone" type="text" datatype="m" nullmsg="请输手机号！" errormsg="手机号格式不正确！"   maxlength="11" class="text_admin inputxt mobile" style="width:180px;">
        <div class="Validform_checktip" style="margin-left:100px;">手机号为11位</div>
    </div>
    <div class="clear"></div>
    <div class="tsxx_jowna"><input type="button" onclick="btnSubmit()" value="提交"  class="a_click submitInput" style="margin-right:40px;" ></div>
    <div class="tsxx_jownak"><input type="reset" value="重置" style="background-color:#828282;margin-left:108px;" class="a_click submitInput" ></div>
    </form>
</div>
<script type="text/javascript">
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
        if (pass.test($('#password').val())) {
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

    function btnSubmit() {
        if (verNickname() && verPassword() && verUsername() && verEmail() && verPhone()) {
            console.log('yes');
             var sex = $(':radio[name="sex"]:checked').val();
             $.ajax({url:"/admin/create/admin",
                     type:"post",
                     data:{username:$("#username").val(),
                           password:$("#password").val(),
                           nickname:$("#nickname").val(),
                           phone:$("#phone").val(),
                           email:$("#email").val(),
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
                                location.href = "/admin/admin/list";
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

<!--新增用户结束-->
    @stop
@stop