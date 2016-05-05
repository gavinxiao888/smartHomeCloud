<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
 		<div class="gzhlb_kpejh">
    	<div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/user/show">用户管理</a>>
            <a href="/admin/admin/list">管理员列表</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--管理员列表开始-->
<div class="gzhlb_pwen">管理员列表</div>
<div class="gzhlb_kpwq">
	<input style="width:216px;height:35px;border:none;float:left;" type="text" id="find" />
    <a class="a_click" onclick="findName()"><div class="gzhlb_bmoe"><img src="/site/images/admin_9.png"></div></a>
</div>
<div class="clear"></div>
<table class="data_list" width="960" cellspacing="0" style="font-size:16px;color:#616161;text-align:center;">
    <thead class="gzhlb_ejrp">
        <tr>
            <td>用户姓名</td>
            <td>用户名</td>
            <td>性别</td>
            <td>邮箱</td>
            <td>手机号</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    	<?php if (!empty($list))  { ?>
            <?php foreach ($list as $admin) { ?>
                <tr height="39">
                    <td width="97"><span class="edit" data-id="<?= $admin->id?>" style="cursor:pointer;"><?= $admin->nickname?></span></td>
                    <td width="200"><?= $admin->username?></td>
                    <td width="65"><?php if ($admin->sex == 1){echo '男';} else {echo '女';}?></td>
                    <td width="250"><?= $admin->email?></td>
                    <td width="126"><?= $admin->phone?></td>
                    <td width="">
                        <a data-id="<?= $admin->id?>" class="a_click delete_admin_user" title="删除"><img src="/site/images/admin_11.png"></a></td>
                </tr>
            <?php } ?>
        <?php } else {?>
            <h3>没有找到符合条件的管理员。</h3>
        <?php }?>
    </tbody>
</table>
    <script type="text/javascript">
        
        $('.delete_admin_user').click(function(event) {
            // console.log('点击了该按钮');
            var id = $(this).attr('data-id');
            if(confirm ("确定要删除用户吗？")){
                $.ajax({url:"/admin/delete/admin",
                     type:"post",
                     data:{id:id},
                     success:function(data){
                            if (data == 2) {
                                layer.alert('删除失败！');
                            } else if (data == 1) {
                                location.href = "/admin/admin/list";
                            }
                     },error:function(){
                            layer.alert('网络错误，请检查您的网络是否畅通!');
                     }
                });
            }
        });

         $('.edit').click(function(event) {
            // console.log('点击了该按钮');
            var id = $(this).attr('data-id');
             console.log('触发了这个动作');
                layer.open({
                type: 2,
                title: '编辑管理员资料',
                shade: [0.8, '#393D49'],
                area: ['426px', '516px'],
                shift: 2,
                content: ['/admin/edit/admin?id='+id, 'no'], //iframe的url，no代表不显示滚动条
                end: function(){ //此处用于演示
                  
                }
            });
            // $.ajax({url:"/admin/delete/admin",
            //      type:"post",
            //      data:{id:id},
            //      success:function(data){
            //             console.log(data);
            //             if (data == 2) {
            //                 layer.alert('删除失败！');
            //             } else if (data == 1) {
            //                 location.href = "/admin/admin/list";
            //             }
            //      },error:function(){
            //             layer.alert('网络错误，请检查您的网络是否畅通!');
            //      }
            // });
        });

        
        function findName() {
            location.href = "/admin/admin/list?name="+$('#find').val();
        }
    </script>
<div style="float:right;margin-top:15px;margin-right:38px;"><a href="/admin/admin/new"><img src="/site/images/admin_12.png"></a></div>
<!--管理员列表结束-->
    @stop