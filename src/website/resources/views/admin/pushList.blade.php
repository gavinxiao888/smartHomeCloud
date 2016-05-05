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
            <a href="/admin/1/push/show">推送管理</a>>
            <a href="/admin/push/list">推送消息记录</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--推送消息记录开始-->
<div class="gzhlb_pwen">推送消息记录</div>
<div class="gzhlb_kpwq">
    <input id="sub" type="text" style="width:216px;height:35px;border:none;float:left;" />
    <a class="a_click" onclick="findSub()"><div class="gzhlb_bmoe"><img src="/site/images/admin_9.png"></div></a>
</div>
<div class="clear"></div>
<table class="data_list" width="960" cellspacing="0" style="font-size:16px;color:#616161;text-align:center;">
    <thead class="gzhlb_ejrp">
        <tr>
            <td>操作人员</td>
            <td>推送时间</td>
            <td>推送类型</td>
            <td>推送标题</td>
            <td>推送内容</td>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($list))  { ?>
            <?php foreach ($list as $admin) { ?>
                <tr height="39">
                    <td width="97"><?= $admin->charge_id?></td>
                    <td width="200"><?= $admin->init_time?></td>
                    <td width="65"><?= $admin->type?></td>
                    <td width="250"><?= $admin->title?></td>
                    <td width="126" title="<?= $admin->content?>"><?= $admin->remark?></td>
                </tr>
            <?php } ?>
        <?php } else {?>
            <h3>没有找到符合条件的管理员。</h3>
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
<!--推送消息记录结束-->
<script type="text/javascript">
    function findSub() {
        location.href = "/admin/push/list?sub="+$('#sub').val();
    }
</script>
    @stop