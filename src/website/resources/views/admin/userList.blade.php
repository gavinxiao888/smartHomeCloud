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
            <a href="/admin/user/list">用户列表</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--用户列表开始-->
<div class="gzhlb_pwen">用户列表</div>
<div class="gzhlb_kpwq">
    <input style="width:216px;height:35px;border:none;float:left;">
    <a href=""><div class="gzhlb_bmoe"><img src="/site/images/admin_9.png"></div></a>
</div>
<div class="clear"></div>
<table class="data_list" width="960" cellspacing="0" style="font-size:16px;color:#616161;text-align:center;">
    <thead class="gzhlb_ejrp">
        <tr>
            <td>业主姓名</td>
            <td>联系电话</td>
            <td>网关SN码</td>
            <td>省</td>
            <td>地市</td> 
            <td>区县</td>
            <td>小区</td>
            <td>楼号</td>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($list))  { ?>
            <?php foreach ($list as $user) { ?>
                <tr height="39">
                    <td width="110"><?= $user->user_id?></td>
                    <td width="57"><?php if(!empty($user->user_role)) {echo $user->user_role;} else {echo '未认证';}?></td>
                    <td width="120"><span class="edit" data-id="<?= $user->init_time?>" style="cursor:pointer;"><?= $user->bind_id?></span></td>
                    <td width="54"><?php if(!empty($user->remark)) {echo $user->remark->province;} else {echo '未认证';}?></td>
                    <td width="97"><?php if(!empty($user->remark)) {echo $user->remark->city;} else {echo '未认证';}?></td>
                    <td width="60"><?php if(!empty($user->remark)) {echo $user->remark->county;} else {echo '未认证';}?></td>
                    <td width="140"><?php if(!empty($user->remark)) {echo $user->remark->community;} else {echo '未认证';}?></td>
                    <td width="60"><?php if(!empty($user->remark)) {echo $user->remark->floor;} else {echo '未认证';}?></td>
                </tr>
            <?php } ?>
        <?php } else {?>
            <h3>没有找到符合条件的信息。</h3>
        <?php }?>
    </tbody>
</table>
<div style="text-align:center;">
    <?php if (!empty($list)){
                if (!empty($sub)) {
                       echo $list->appends(['sub' => $sub])->render();  
                }else {
                     echo $list->render(); 
                }
            }?>
</div>
<div style="float:right;margin-top:15px;margin-right:38px;">
    <input type="button" id="newDeviceType" onclick="addNewInfo()" value="录入用户资料"  class="a_click submitInput" >
</div>
<script type="text/javascript">
        function addNewInfo() {
//             console.log('触发了这个动作');
                layer.open({
                type: 2,
                title: '录入用户资料',
                shade: [0.8, '#393D49'],
                area: ['440px', '350px'],
                shift: 2,
                content: ['/admin/create/user', 'no'], //iframe的url，no代表不显示滚动条
                end: function(){ //此处用于演示
                  
                }
            });
        }
        $('.edit').click(function(event) {
            console.log('点击编辑用户信息');
            var id = $(this).attr('data-id');
             console.log('触发了这个动作');
                layer.open({
                type: 2,
                title: '编辑用户信息',
                shade: [0.8, '#393D49'],
                area: ['440px', '350px'],
                shift: 2,
                content: ['/admin/edit/user?code='+id, 'no'], //iframe的url，no代表不显示滚动条
                end: function(){ //此处用于演示
                  
                }
            });
         })
</script>
<!--用户列表结束-->
    @stop