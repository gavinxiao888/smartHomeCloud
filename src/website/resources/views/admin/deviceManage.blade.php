<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
 		<div class="gzhlb_kpejh">
    	<div class="gzhlb_mier"><img src="/site/images/admin_6.png">当前位置：首页>用户管理>管理员列表</div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--管理员列表开始-->
<div class="gzhlb_pwen">设备类型列表</div>
<div class="gzhlb_kpwq">
	<input style="width:216px;height:35px;border:none;float:left;" type="text" id="find" />
    <a class="a_click" onclick="findName()"><div class="gzhlb_bmoe"><img src="/site/images/admin_9.png"></div></a>
</div>
<div class="clear"></div>
<table class="data_list" width="960" cellspacing="0" style="font-size:16px;color:#616161;text-align:center;">
    <thead class="gzhlb_ejrp">
        <tr>
            <td>厂商产品</td>
            <td>产品类型</td>
            <td>添加时间</td>
            <td>执行者</td>
            <td>Manufacturer ID</td>
            <td>Product ID</td>
            <td>Product Type</td>
            <td>查看</td>
        </tr>
    </thead>
    <tbody>
    	<?php if (!empty($list))  { ?>
            <?php foreach ($list as $device) { ?>
                <tr height="39">
                    <td width="97"><?= $device->name?></td>
                    <td width="100"><?= $device->device_type?></td>
                    <td width="160"><?= $device->init_time?></td>
                    <td width="100"><?= $device->charge_id?></td>
                    <td width="146"><?= $device->manufacturer_id?></td>
                    <td width="126"><?= $device->product_id?></td>
                    <td width="126"><?= $device->product_type?></td>
                    <td width="">
                    <a data-id="<?= $device->id?>" class="a_click"><img src="/site/images/admin_11.png"></a></td>
                </tr>
            <?php } ?>
        <?php } else {?>
            <h3>没有找到符合条件的设备类型。</h3>
        <?php }?>
    </tbody>
</table>
    <script type="text/javascript">
        function addNewType() {
             console.log('触发了这个动作');
                layer.open({
                type: 2,
                title: '新录入厂商设备',
                shade: [0.8, '#393D49'],
                area: ['380px', '315px'],
                shift: 2,
                content: ['/admin/device/type/new', 'no'], //iframe的url，no代表不显示滚动条
                end: function(){ //此处用于演示
                  
                }
            });
        }
        
        // $('.a_click').click(function(event) {
        //     // console.log('点击了该按钮');
        //     var id = $(this).attr('data-id');
        //     // console.log('id获取到了：'+id);
        //     $.ajax({url:"/admin/delete/admin",
        //          type:"post",
        //          data:{id:id},
        //          success:function(data){
        //                 console.log(data);
        //                 if (data == 2) {
        //                     layer.alert('删除失败！');
        //                 } else if (data == 1) {
        //                     location.href = "/admin/admin/list";
        //                 }
        //          },error:function(){
        //                 layer.alert('网络错误，请检查您的网络是否畅通!');
        //          }
        //     });
        // });
        
        function findName() {
            location.href = "/admin/admin/list?name="+$('#find').val();
        }


    </script>
<div style="float:right;margin-top:15px;margin-right:38px;">
    <input type="button" id="newDeviceType" onclick="addNewType()" value="录入厂商设备"  class="a_click submitInput" >
</div>
<!--管理员列表结束-->
    @stop