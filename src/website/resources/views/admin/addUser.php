<!-- create: 2015.11.21
后台管理主界面
 马学杰 -->

<!DOCTYPE html>
<html>
<meta name="renderer" content="webkit" />
<link rel="stylesheet" type="text/css" href="/site/css/add_main.css">
<link rel="stylesheet" type="text/css" href="/site/css/select2.min.css">
<script src="/site/js/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script>
<script src="/layer/layer.js" type="text/javascript" charset="utf-8"></script>
<script src="/laydate/laydate.js" type="text/javascript" charset="utf-8"></script>
<script src="/site/js/jsAddress.js" type="text/javascript" charset="utf-8"></script>
<script src="/site/js/select2/select2.full.js" type="text/javascript" charset="utf-8"></script>
<head>
    <title>录入用户资料</title>
</head>
<body>
    <div class="device_type_content">
     <div class="device_type_div">
            <div class="device_type_div_title">请输入网关SN码：</div>
            <div class="device_type_div_content"><select id="gateway" class="device_type_div_input"></select></div>
        </div><!-- 
        <div class="device_type_div">
            <div class="device_type_div_title">请输入业主姓名：</div>
            <div class="device_type_div_content"><input id="typeName" class="device_type_div_input" type="text" /></div>
        </div> -->
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
        <div class="device_type_div" style="margin-top:30px;">
            <input type="button" onclick="btnOk()" class="device_type_div_button" value="确定" />
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
//            console.log('123');
            var data = [{ id: 0, text: 'enhancement' }, { id: 1, text: 'bug' }, { id: 2, text: 'duplicate' }, { id: 3, text: 'invalid' }, { id: 4, text: 'wontfix' }];
 
            $("#gateway").select2({
              data: <?php if (!empty($list)) {echo json_encode($list);}?>
            });
        })
        addressInit('cmbProvince', 'cmbCity', 'cmbArea', '北京', '市辖区', '东城区');
        // addressInit('Select1', 'Select2', 'Select3');

        var count = 0;
        function btnOk() {
            var gateway = $("#gateway  option:selected").text();
             $.ajax({url:"/admin/create/user",
                 type:"post",
                 data:{gateway:gateway,
                       province:$("#cmbProvince").val(),
                       city:$("#cmbCity").val(),
                       county:$("#cmbArea").val(),
                       community:$("#community").val(),
                       floor:$("#floor").val()
                    },
                 success:function(data){
                        console.log(data);
                        if (data == 2) {
                            layer.alert('非法操作。');
                        } else if (data == 0) {
                            layer.alert('添加失败，请稍后再试。');
                        } else if (data == 3) {
                            layer.alert('网关SN码无效');
                        } else if (data == 4) {
                            layer.alert('已存在该网关SN码用户信息！');
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