<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->
@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
 		<div class="gzhlb_kpejh">
        <div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/device/show">设备管理</a>>
            <a href="/admin/fault/list">故障列表</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--故障列表开始-->
<div class="gzhlb_pwen">故障列表</div>
<div class="gzhlb_kpwq">
    <input style="width:216px;height:35px;border:none;float:left;">
    <a href=""><div class="gzhlb_bmoe"><img src="/site/images/admin_9.png"></div></a>
</div>
<div class="clear"></div>
<table width="960" cellspacing="0" style="font-size:16px;color:#616161;text-align:center;">
     <thead class="gzhlb_ejrp">
        <tr>
            <td>业主姓名</td>
            <td>省</td>
            <td>地市</td>
            <td>区县</td>
            <td>小区</td>
            <td>楼号</td>
            <td>业主电话</td>
            <td>故障时间</td>
            <td>维修记录</td>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($list))  { ?>
            <?php foreach ($list as $fault) { ?>
                <tr height="39">
                    <td width="110"><span class="edit" data-id="<?= $fault->id?>" style="cursor:pointer;"><?=$fault->name ?></span></td>
                    <td width="57"><?=$fault->province ?></td>
                    <td width="120"><?=$fault->city ?></td>
                    <td width="54"><?=$fault->county ?></td>
                    <td width="157"><?=$fault->community ?></td>
                    <td width="60"><?=$fault->floor ?>#</td>
                    <td width="140"><?=$fault->tel ?></td>
                    <td width="140"><?php echo date('Y-m-d',strtotime($fault->fault_time));?></td>
                    <td width=""><span title=<?= $fault->remark?>><?php echo mb_substr($fault->remark,0,8);?></span></td>
                </tr>
            <?php } ?>
        <?php } else {?>
            <h3>暂无故障报告。</h3>
        <?php }?>
    </tbody>
    
</table>
<div style="float:right;margin-top:15px;margin-right:38px;">
    <input type="button" id="newDeviceType" onclick="addNewFault()" value="录入故障信息"  class="a_click submitInput" >
</div>
<script type="text/javascript">
         $('.edit').click(function(event) {
            console.log('点击编辑故障信息');
            var id = $(this).attr('data-id');
             console.log('触发了这个动作');
                layer.open({
                type: 2,
                title: '编辑故障报备信息',
                shade: [0.8, '#393D49'],
                area: ['440px', '400px'],
                shift: 2,
                content: ['/admin/edit/fault?code='+id, 'no'], //iframe的url，no代表不显示滚动条
                end: function(){ //此处用于演示
                  
                }
            });
         })
        function addNewFault() {
             console.log('触发了这个动作');
                layer.open({
                type: 2,
                title: '故障报备',
                shade: [0.8, '#393D49'],
                area: ['440px', '450px'],
                shift: 2,
                content: ['/admin/fault/new', 'no'], //iframe的url，no代表不显示滚动条
                end: function(){ //此处用于演示
                  
                }
            });
        }
</script>
<!--故障列表结束-->
    @stop