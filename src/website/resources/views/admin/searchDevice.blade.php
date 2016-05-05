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
            <a href="/admin/search/device">设备查询</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png"></div>
        <div class="clear"></div>

<div class="gzhlb_pwen">用户设备列表</div>
<div class="gzhlb_kpwq">
    <input id="search_device" style="width:216px;height:35px;border:none;float:left;" placeholder="请输入邮箱或手机号查询">
    <a href="javascript:void(0)" id="search_user_btn"><div class="gzhlb_bmoe"><img src="/site/images/admin_9.png"></div></a>
</div>
<div class="clear"></div>
<table class="data_list" width="960" cellspacing="0" style="font-size:16px;color:#616161;text-align:center;">
    <thead class="gzhlb_ejrp">
        <tr>
            <td>设备名称</td>
            <td>用户</td>
            <td>网关</td>
            <td>当前状态</td>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($list))  { ?>
            <?php for($i = 0, $y = count($list); $i < $y; $i++) { ?>
                <tr height="39">
                    <td width="310"><?= $list[$i]['nickname']?></td>
                    <td width="100"><?= $list[$i]['user']?></td>
                    <td width="315"><?= $list[$i]['gateway']?></td>
                    <td><?php if ($list[$i]['status'] == 0){ echo '关';} else {echo '开';}?></td>
                </tr>
            <?php } ?>
        <?php } else {?>
            <h3>没有找到符合条件的用户，试试换个邮箱或手机号查询。</h3>
        <?php }?>
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function (){
       $("#search_device").bind("keydown",function (event){
          if (event.which === 13 && $(this).val() !== ""){
              window.location.href = "/admin/search/device/"+$(this).val();
          } 
       });
       $("#search_user_btn").bind("click",function (){
           if ($("#search_device").val() !== ""){
                window.location.href = "/admin/search/device/"+$("#search_device").val();
           }
       });
    });
</script>
<!--网关列表结束-->
    @stop