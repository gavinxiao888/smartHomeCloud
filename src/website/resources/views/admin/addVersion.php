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
<script type="text/javascript" src="/upload/jquery.form.js"></script>
<head>
    <title>发布新版本</title>
</head>
<body>
    <div class="device_type_content">
        <div class="device_type_div">
            <div class="device_type_div_title">请选择设备类型：</div>
            <div class="device_type_div_content">
                <select id="deviceType" class="device_type_div_input">
                    <option id="1" >网关</option>
                </select>
            </div>
        </div>
        <div class="device_type_div" style="margin-top:15px; ">
            <div class="device_type_div_title">请输入版本号：</div>
            <div class="device_type_div_content"><input type="number" id="versionCode" class="device_type_div_input"/></div>
        </div>
        <div class="device_type_div" style="margin-top:15px; ">
            <div class="device_type_div_title">请上传文件：</div>
            <div class="device_type_div_content"><input type="file" id="fileupload" name="mypic" class="device_type_div_input" /> </div>
            <input type="hidden" id="url" />
        </div>
        <hr style="border:1px solid #d9dbdc; margin-top:15px;" />
        <div class="device_type_div" style="margin-top:20px;">
            <input type="button" onclick="btnOk()" class="device_type_div_button" value="确定" />
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            var bar = $('.bar');
            var percent = $('.percent');
            var showimg = $('#showimg');
            var progress = $(".progress");
            var files = $(".files");
            var btn = $(".btn span");
            $("#fileupload").wrap("<form id='myupload' action='/upload/action.php' method='post' enctype='multipart/form-data'></form>");
            $("#fileupload").change(function(){
                $("#myupload").ajaxSubmit({
                    dataType:  'json',
                    beforeSend: function() {
                        showimg.empty();
                        progress.show();
                        var percentVal = '0%';
                        bar.width(percentVal);
                        percent.html(percentVal);
                        btn.html("上传中...");
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentVal = percentComplete + '%';
                        bar.width(percentVal);
                        percent.html(percentVal);
                    },
                    success: function(data) {
                        files.html("<b>"+data.name+"("+data.size+"k)</b> <span class='delimg' rel='"+data.pic+"'>删除</span>");
                        var img = "/upload/files/"+data.pic;
                        $("#url").val(img);
//                        console.log($("#url").val());
                        btn.html("添加完成");
                    },
                    error:function(xhr){
                        btn.html("上传失败");
                        bar.width('0')
                        files.html(xhr.responseText);
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        function btnOk() {
           var deviceType = $("#deviceType option:selected").attr("id");
             $.ajax({url:"/admin/new/version",
                 type:"post",
                 data:{versionCode:$("#versionCode").val(),
                       url:$("#url").val(),
                       deviceType:deviceType},
                 success:function(data){
                        console.log(data);
                        if (data == 2) {
                            layer.alert('添加失败');
                        } else if (data == 1) {
                            console.log(data);
                            window.parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        } else if (data == 0) {
                            layer.alert('非法用户');
                        }
                 },error:function(){
                        layer.alert('网络错误，请检查您的网络是否畅通!');
                 }
            });
        }
    </script>
</body>
</html>