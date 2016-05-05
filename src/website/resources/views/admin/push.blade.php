<!-- create: 2015.10.27
后台管理主界面
 马学杰 -->

@extends(Request::pjax() ? 'admin.pjax':'admin.default')
    @section('content')
    <div class="gzhlb_kpejh">
        <div class="gzhlb_mier"><img src="/site/images/admin_6.png">
            当前位置：
            <a href="/admin/index/show">首页</a>>
            <a href="/admin/1/push/show">推送管理</a>>
            <a href="/admin/push/show">推送消息</a></div>
        <div class="gzhlb_mnow"><img src="/site/images/admin_7.png">今天是2014年12月29日&nbsp;&nbsp;星期一</div>
        <div class="clear"></div>
<!--推送消息开始-->
<div class="gzhlb_pwen">推送消息</div>
<div class="clear"></div>
<div class="tsxx_jkpw">
    <div id="kinds" class="tsxx_iupw">类型选择<select class="tsxx_kho">
        <option id="2">邮件推送</option>
        <option id="1">短信推送</option>
        <option id="3">手机APP推送</option>
    </select></div>
    <div class="tsxx_uone">标题<input id="title" style="width:268px;border:1px solid #b1b1b1;margin-left:24px;height:30px;border-radius:5px;" type="text" /></div>
    <div class="tsxx_uoneal">推送内容<textarea id="content" cols="60" rows="5" style="margin-left:24px;border-radius:5px;border:1px solid #b1b1b1;"></textarea></div>
    <div class="tsxx_jown"><a class="a_click" onclick="pushAll()"><img src="/site/images/admin_14.png"></a></div>
</div>
<script type="text/javascript">
    function pushAll() {
        var type = $("#kinds option:selected").attr("id");
        console.log(type);
         $.ajax({
             type: "post",
             url: "/admin/push/msg",
             data: {title:$("#title").val(), content:$("#content").val(), type:type},
             // dataType: "json",    // 2015.09.28 ： 决定暂不用json；
             success: function(data){
                console.log(data);
                if (data == 1) {
                  layer.alert('推送成功');
                } else {
                  layer.alert('推送失败，请稍后再试');
                }
              }, error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                 console.log(textStatus);
                },
            });

    }
</script>
<!--推送消息结束-->
    @stop