<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
 		<div class="gzhlb_kpejh">
    	<div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/device/show">设备管理</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--管理员列表开始-->
<div class="gzhlb_pwen">设备管理</div>
<div class="clear"></div>
<div class="tsxx_jkpw">
    <div class="shuliang">
        <div class="shuliang-1">网关数量</div>
        <div class="shuliang-2"><?php if (isset($count)) {echo $count['gateway'];} else{ echo '0';}?></div>
    </div>
    <div class="shuliang">
        <div class="shuliang-1">遮阳机数量</div>
        <div class="shuliang-3"><?php if (isset($count)) {echo $count['shading'];} else{ echo '0';}?></div>
    </div>
    <div class="shuliang">
        <div class="shuliang-1">故障设备数量</div>
        <div class="shuliang-4"><?php if (isset($count)) {echo $count['fault'];} else{ echo '0';}?></div>
    </div>
</div>
<!--管理员列表结束-->
    @stop