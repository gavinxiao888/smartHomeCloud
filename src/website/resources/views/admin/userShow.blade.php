<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
 		<div class="gzhlb_kpejh">
    	<div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/user/show">用户管理</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--用户管理开始-->
<div class="gzhlb_pwen">用户管理</div>
<div class="clear"></div>
<div class="tsxx_jkpw">
    <div class="shuliang-6">
        <div class="shuliang-1">用户数量</div>
        <div class="shuliang-2"><?php if (isset($count)) {echo $count['user'];} else{ echo '0';}?></div>
    </div>
    <div class="shuliang-6">
        <div class="shuliang-1">管理员数量</div>
        <div class="shuliang-4"><?php if (isset($count)) {echo $count['admin'];} else{ echo '0';}?></div>
    </div>
</div>

<!--用户管理结束-->
    @stop