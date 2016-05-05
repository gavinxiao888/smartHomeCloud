<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->
@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
    <style>
        .enable{ cursor: pointer;}
        .deleteStatus{cursor:pointer;}
    </style>
 		<div class="gzhlb_kpejh">
        <div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/device/show">设备管理</a>>
            <a href="/admin/fault/list">版本列表</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--故障列表开始-->
<div class="gzhlb_pwen">版本列表</div>
<div class="gzhlb_kpwq">
    <input style="width:216px;height:35px;border:none;float:left;">
    <a href=""><div class="gzhlb_bmoe"><img src="/site/images/admin_9.png"></div></a>
</div>
<div class="clear"></div>
<table width="960" cellspacing="0" style="font-size:16px;color:#616161;text-align:center;">
     <thead class="gzhlb_ejrp">
        <tr>
            <td>序号</td>
            <td>设备类型</td>
            <td>版本号</td>
            <td width="54" style="overflow: hidden;">版本链接</td>
            <td>发布人</td>
            <td>发布时间</td>
            <td>启用状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($list))  { ?>
            <?php foreach ($list as $version) { ?>
                <tr height="39">
                    <td width="110"><span class="edit" style="cursor:pointer;"><?=$version->id ?></span></td>
                    <td width="70"><?php echo $version->type == 1 ? '网关' : '其它'; ?></td>
                    <td width="120"><?=$version->name ?></td>
                    <td width="70"><input type="text" readonly="readonly" style="width:70px !important;"value="<?=$version->href ?>"/></td>
                    <td width="157"><?=$version->charge_id ?></td>
                    <td width="150"><?php echo date('Y-m-d',strtotime($version->init_time));?></td>
                    <td width="100"><span class="enable" data-id="<?= $version->id?>"><?php echo $version->enable == 1 ? '启用' : '关闭';?></span></td>
                    <td width="100"><span class="deleteStatus" data-id="<?= $version->id?>">删除</span></td>
                </tr>
            <?php } ?>
        <?php } else {?>
            <h3>暂无最新版本信息。</h3>
        <?php }?>
    </tbody>
    
</table>
<div style="float:right;margin-top:15px;margin-right:38px;">
    <input type="button" id="newDeviceType" onclick="addNewFault()" value="发布新版本"  class="a_click submitInput" >
</div>
<script type="text/javascript">
         $('.enable').click(function(event) {
            console.log('点击了修改状信息');
            var id = $(this).attr('data-id');
            var enable = 0;
            if ($(this).html() == '关闭') {
                enable = 1;
            }
            console.log(enable);
             $.ajax({url:"/admin/edit/enable",
                    type:"post",
                    data:{id:id,
                          enable:enable},
                    success:function(data){
                         console.log(data);
                         if (data == 0) {
                             layer.alert('删除失败');
                         } else if (data == 1) {
                             console.log(data);
                             location.reload();
                         } else {
                            layer.alert('有点儿小故障，请联系系统运维人员！');
                         }
                  },error:function(){
                         layer.alert('网络错误，请检查您的网络是否畅通!');
                  }
                 });
         })

         $('.deleteStatus').click(function(event) {
            var id = $(this).attr('data-id');
             $.ajax({url:"/admin/edit/status",
                     type:"post",
                     data:{id:id},
                     success:function(data){
                            console.log(data);
                            if (data == 0) {
                                layer.alert('删除失败');
                            } else if (data == 1) {
                                location.reload();
                            } else {
                               layer.alert('有点儿小故障，请联系系统运维人员！');
                            }
                     },error:function(){
                            layer.alert('网络错误，请检查您的网络是否畅通!');
                     }
                });
         })
        function addNewFault() {
                layer.open({
                type: 2,
                title: '故障报备',
                shade: [0.8, '#393D49'],
                area: ['440px', '300px'],
                shift: 2,
                content: ['/admin/new/version', 'no'], //iframe的url，no代表不显示滚动条
                end: function(){ //此处用于演示
                  
                }
            });
        }
</script>
<!--故障列表结束-->
    @stop