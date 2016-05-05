<!-- create: 2015.11.21
后台管理主界面
 马学杰 -->

<!DOCTYPE html>
<html>
<meta name="renderer" content="webkit" />
<link rel="stylesheet" type="text/css" href="/site/css/add_main.css">
<script src="/site/js/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script>
<script src="/layer/layer.js" type="text/javascript" charset="utf-8"></script>
<script src="/laydate/laydate.js" type="text/javascript" charset="utf-8"></script>
<script src="/site/js/jsAddress.js" type="text/javascript" charset="utf-8"></script>
<head>
    <title>录入故障信息</title>
</head>
<body>
    <div class="device_type_content">
        <div class="device_type_div">
            <div class="device_type_div_title">请输入业主姓名：</div>
            <div class="device_type_div_content"><input id="typeName" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入所在省：</div>
            <div class="device_type_div_content"><select id="cmbProvince" class="device_type_div_input"> </select></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入所在市：</div>
            <div class="device_type_div_content"><select id="cmbCity" class="device_type_div_input"> </select></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入所在区/县：</div>
            <div class="device_type_div_content"><select id="cmbArea" class="device_type_div_input" ></select></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入小区名称：</div>
            <div class="device_type_div_content"><input id="community" class="device_type_div_input" type="text" /></div>
        </div>
         <div class="device_type_div">
            <div class="device_type_div_title">请输入所在楼号：</div>
            <div class="device_type_div_content"><input id="floor" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入业主电话：</div>
            <div class="device_type_div_content"><input id="tel" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请选择故障时间：</div>
            <div class="device_type_div_content"><input id="fault_time" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" readonly="readonly" class="device_type_div_input" type="text" /></div>
        </div>
        <div class="device_type_div">
            <div class="device_type_div_title">请输入维修记录：</div>
            <div class="device_type_div_content"><textarea id="remark" class="device_type_div_input" style="height:55px;" > </textarea></div>
        </div>
        <hr style="border:1px solid #d9dbdc;" />
        <div class="device_type_div" style="margin-top:10px;">
            <input type="button" onclick="btnOk()" class="device_type_div_button" value="确定" />
        </div>
    </div>

    <script type="text/javascript">
        addressInit('cmbProvince', 'cmbCity', 'cmbArea', '北京', '市辖区', '东城区');
        // addressInit('Select1', 'Select2', 'Select3');

        var count = 0;
        function btnOk() {
             $.ajax({url:"/admin/create/fault",
                 type:"post",
                 data:{name:$("#typeName").val(),
                       province:$("#cmbProvince").val(),
                       city:$("#cmbCity").val(),
                       county:$("#cmbArea").val(),
                       community:$("#community").val(),
                       floor:$("#floor").val(),
                       tel:$("#tel").val(),
                       fault_time:$("#fault_time").val(),
                       remark:$("#remark").val()
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