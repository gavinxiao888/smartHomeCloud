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
    <title>录入厂商产品</title>
</head>
<body>
    <div class="device_type_content">
        <div class="device_type_div">
            <div class="device_type_div_title">新录入厂商产品名称：</div>
            <div class="device_type_div_content"><input id="typeName" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请选择设备类型：</div>
            <div class="device_type_div_content">
                <select id="type" class="device_type_div_input">
                    <?php if (!empty($list)) {?>
                        <?php foreach ($list as $type) { ?>
                            <option id="<?= $type->id?>"><?= $type->name?></option>
                        <?php } ?>
                    <?php } else {?>
                        <option id="0">数据获取失败</option>
                    <?php }  ?>
                </select>
            </div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入Manufacturer ID：</div>
            <div class="device_type_div_content"><input id="manufacturer_id" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入Product ID：</div>
            <div class="device_type_div_content"><input id="product_id" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入Product Type：</div>
            <div class="device_type_div_content"><input id="product_type" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div" style="margin-top:30px;">
            <input type="button" onclick="btnOk()" class="device_type_div_button" value="确定" />
        </div>
    </div>
    <script type="text/javascript">
        var count = 0;
        function btnOk() {
            var type = $("#type>option:selected").attr("id");
            console.log(type);
             $.ajax({url:"/admin/create/device/type",
                 type:"post",
                 data:{name:$("#typeName").val(),
                       type:type,
                       manufacturer_id:$("#manufacturer_id").val(),
                       product_id:$("#product_id").val(),
                       product_type:$("#product_type").val()
                    },
                 success:function(data){
                        console.log(data);
                        if (data == 2) {
                            layer.alert('添加失败');
                        } else if (data == 1) {
                            window.parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        } else {
                            layer.alert('已存在相同参数的厂商设备：' + data);
                        }
                 },error:function(){
                        layer.alert('网络错误，请检查您的网络是否畅通!');
                 }
            });
        }
    </script>
</body>
</html>