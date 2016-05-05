<!-- create: 2015.11.21
后台管理主界面
 马学杰 -->

<!DOCTYPE html>
<html>
<meta name="renderer" content="webkit" />
<link rel="stylesheet" type="text/css" href="/site/css/add_main.css">
<script src="/site/js/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script>
<script src="/layer/layer.js" type="text/javascript" charset="utf-8"></script>
<head>
    <title>控制面板</title>
</head>
<style>
    #rangeValue { margin-left: 5px; line-height: 25px;}
</style>
<body>
    <div class="device_type_content">
        <input type="hidden" id="gateway" value="<?php if (!is_null($result)) {echo $result->bind_id;}?>" />
        <input type="hidden" id="device" value="<?php if (!is_null($result)) {echo $result->device_id;}?>" />
        <div class="device_type_div">
            <div class="device_type_div_title">设备名称：</div>
            <div class="device_type_div_content">
                <div class="device_type_div_input" style="text-indent: 1em;"><?php if (!is_null($result)) {echo $result->nickname;} else {echo '设备';}?></div>
            </div>
        </div>
        <div class="device_type_div" style="margin-top:15px; ">
            <div class="device_type_div_title">用户名：</div>
            <div class="device_type_div_content">
                <div class="device_type_div_input" style="text-indent: 1em;"><?php if (!is_null($result)) {echo $result->init_time;} else {echo '暂无昵称';}?></div>
            </div>
        </div>
        <div class="device_type_div" style="margin-top:15px; ">
            <div class="device_type_div_title">最近控制时间：</div>
            <div class="device_type_div_content" style="text-indent: 1em;">
                <div class="device_type_div_input"><?php if (!is_null($result)) {echo $result->update_time;} else {echo '暂无操作';}?></div>
            </div>
        </div>
        <div class="device_type_div" style="margin-top:15px; ">
            <div class="device_type_div_title">操作：</div>
            <div class="device_type_div_content" style="text-indent: 1em;">
                    <input id="rangeCheck" type="range" max="99" min="1" onchange="rangeChange()" value="<?php if (!is_null($result)) {echo $result->status;}?>" /> <span id="rangeValue"><?php if (!is_null($result)) {echo $result->status;}?></span>%
<!--                    <a href="#" id="checkUp" class="toggle toggle----><?php //if (!is_null($result)) {if ($result->status == 0) {echo 'on';} else {echo 'off';}} else {echo 'on';}?><!--"></a>-->
            </div>
        </div>
        <hr style="border:1px solid #d9dbdc; margin-top:15px;" />
        <div class="device_type_div" style="margin-top:20px;">
            <input type="button" onclick="btnOk()" class="device_type_div_button" value="确定" />
        </div>
    </div>
    <script type="text/javascript">
        function rangeChange() {
            $('#rangeValue').html($('#rangeCheck').val());
        }
    </script>


    <script type="text/javascript">
        function btnOk() {
           var deviceType = $("#deviceType option:selected").attr("id");
            console.log('bind:'+$("#gateway").val());
            console.log('deviceId:'+$("#device").val());
            var value = $('#rangeCheck').val();
            console.log(value);

            $.ajax({url:"/admin/check/shading",
                 type:"post",
                 data:{bind:$("#gateway").val(),
                       deviceId:$("#device").val(),
                       value:value},
                 success:function(data){
                        console.log(data);
                        if(data.indexOf("成功") >= 0) {
                            console.log('成功');
                            layer.alert('操作成功');
                        } else {
                            layer.alert('操作失败');
                        }
                 },error:function(){
                        layer.alert('网络错误，请检查您的网络是否畅通!');
                 }
            });
        }
    </script>
</body>
</html>