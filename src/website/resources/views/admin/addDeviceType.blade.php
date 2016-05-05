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
    <title>新增设备类型</title>
</head>
<body>
    <div class="device_type_content">
        <div class="device_type_div">
            <div class="device_type_div_title">新增设备类型名称：</div>
            <div class="device_type_div_content"><input id="typeName" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入设备属性1：</div>
            <div class="device_type_div_content"><input id="first" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入设备属性2：</div>
            <div class="device_type_div_content"><input id="second" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入设备属性3：</div>
            <div class="device_type_div_content"><input id="third" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入设备属性4：</div>
            <div class="device_type_div_content"><input id="fouth" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div" style="margin-top:30px;">
            <input type="button" onclick="btnOk()" class="device_type_div_button" value="确定" />
        </div>
    </div>
    <script type="text/javascript">
        var count = 0;
        function btnOk() {
            if ($("#fouth").val() != "") {
                count = 3;
            } else if ($("#third").val() != "") {
                count = 2;
            } else if ($("#second").val() != "") {
                count = 1;
            }
             $.ajax({url:"/admin/create/device/type",
                 type:"post",
                 data:{name:$("#typeName").val(),
                       first:$("#first").val(),
                       second:$("#second").val(),
                       third:$("#third").val(),
                       fouth:$("#fouth").val(),
                       count:count
                    },
                 success:function(data){
                        console.log(data);
                        if (data == 2) {
                            layer.alert('添加失败');
                        } else if (data == 1) {
                            window.parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        }
                 },error:function(){
                        layer.alert('网络错误，请检查您的网络是否畅通!');
                 }
            });
        }
    </script>
</body>
</html>