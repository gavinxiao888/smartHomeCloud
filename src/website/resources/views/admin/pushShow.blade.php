<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
 		<div class="gzhlb_kpejh">
    	<div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/push/show">推送管理</a>></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--推送管理开始-->
<div class="gzhlb_pwen">推送管理</div>
<div class="clear"></div>
<div class="tsxx_jkpw">
    <div class="shuliang-5">
        <div class="shuliang-1">推送消息数量</div>
        <div class="shuliang-3"><?php if (isset($count)) {echo $count;} else {echo '0';}?></div>
    </div>
</div>
<!--推送管理结束-->
    @stop