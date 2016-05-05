<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->
@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
 		<!--左侧导航结束-->
    <div class="gzhlb_kpejh">
        <div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/user/show">用户管理</a>>
            <a href="/admin/editor/password">修改密码</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--修改密码开始-->
<div class="gzhlb_pwen">修改密码</div>
<div class="clear"></div>
<div class="tsxx_jkpw">
    <div class="tsxx_iupw">请输入原密码<input id="old" type="password" maxlength="16" class="admin_password" /></div>
    <div class="tsxx_uonex">请输入新密码<input id="first" type="password" maxlength="16" class="admin_password" /></div>
    <div class="tsxx_uonealy">再次输入新密码<input id="second" type="password" maxlength="16" class="admin_password" /></div>
    <div class="tsxx_jowna"><input type="button" onclick="editorPassword()" value="提交"  class="a_click submitInput" style="margin-right:40px;" ></div>
    <div class="tsxx_jownak"><input type="button" onclick="reload()" value="重置" style="background-color:#828282;margin-left:108px;" class="a_click submitInput" ></div>
    
</div>
	<script type="text/javascript">
        var pass = /^[\w\W]{6,16}$/;
		//验证密码有效性
		$('#old').blur(function() {
			$.ajax({url:"/admin/verification/password",
                 type:"post",
                 data:{password:$("#old").val()},
                 success:function(data){
                        if (data == 2) {
                            layer.alert('原密码不正确');
                        } else if (data == 3) {
                            layer.alert('非法用户');
                        }
                 },error:function(){
                        layer.alert('网络错误，请检查您的网络是否畅通!');
                 }
            });	
		}) 
			
		function reload() {
			$('#old').val('');
			$('#first').val('');
			$('#second').val('');
		}	

        function verPassword() {
            if (pass.test($('#first').val())) {
                return true;
            } else {
                return false;
            }
        }
		//修改密码
		function editorPassword() {
            if (verPassword()) {
                $.ajax({url:"/admin/verification/password",
                 type:"post",
                 data:{password:$("#old").val()},
                 success:function(data){
                        console.log(data);
                        if (data == 2) {
                            layer.alert('密码不正确');
                        } else if (data == 3) {
                            layer.alert('非法用户');
                        } else if (data == 1) {
                            if ($('#first').val() != $('#second').val()) {
                                layer.alert('两次密码输入不一致！');
                            } else {
                                $.ajax({url:"/admin/editor/password",
                                    type:"post",
                                    data:{password:$("#first").val()},
                                    success:function(data){
                                            console.log(data);
                                            if (data == 2) {
                                                layer.alert('暂无更改！');
                                            } else if (data == 3) {
                                                layer.alert('非法用户！');
                                            } else if (data == 1) {
                                                layer.alert('修改成功！');
                                                reload();
                                            }
                                    },error:function(){
                                            layer.alert('网络错误，请检查您的网络是否畅通!');
                                    }
                                });
                            }
                        }
                 },error:function(){
                        layer.alert('网络错误，请检查您的网络是否畅通!');
                 }
                });
            } else {
                 layer.alert('密码格式错误，请重新输入！(必须为6-16位字符)');
            }
		}
	</script>
<!--修改密码结束-->
    @stop