<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
 		<div class="gzhlb_kpejh">
        <div class="gzhlb_mier"><img src="/site/images/admin_6.png">当前位置：首页>用户管理>用户列表</div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--用户列表开始-->
<div style="width:80%; margin-top:10%;">
    <form action="/admin/upload/img" method="post" enctype="multipart/form-data">
        <label for="file">Filename:</label> 
        <input type="file" name="everyoo" id="everyoo" /> 
        <br /> 
        <input type="submit" name="submit" value="Submit" /> 
    </form>
</div>
<div class="clear"></div>

<!--用户列表结束-->
    @stop
@stop