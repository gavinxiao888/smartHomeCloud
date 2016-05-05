@extends('web.default')
    @section('content')
       <!--推送记录开始-->
        <div class="push_oohnu">
            <div class="push_erww">推送模块数据</div>
            <div class="push_bewep">
                <input style="height:33px;width:216px;border-radius:5px;border:none;float:left;">
                <a href=""><img src="/site/images/select.png" style="padding-top:7px;"></a>
            </div>
        </div>
        <div class="push_ewrb">
            <div class="push_vberpo">标题</div>
            <div class="push_vberpo">时间</div>
            <div class="push_vberpo">操作</div>
        </div>
        <table style="width:1000px;" cellspacing="0">
            <tr class="push_bvewo" height="39">
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="push_bvewo-2" height="39">
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="push_bvewo" height="39">
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="push_bvewo-2" height="39">
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <!--推送记录结束-->
        <div style="width:1000px;height:50px;"></div>
        <!--设备推送开始-->
        <div class="push_wgwe">
            <div class="push_weqwg"><img src="/site/images/de_13.png">当前位置：推送模块>设备推送</div>
            <div class="push_wrbv"><img src="/site/images/de_15.png">今天是2014年12月29日&nbsp;星期一</div>
        </div>
        <div class="push_ewrb">
            <div class="push_ergb">新建设备推送消息</div>
            <div class="push_vbew">为保障用户体验，本平台严谨恶意营销。严禁发布色情低俗、暴力血腥、政治谣言等各类违法信息！</div>
        </div>
        <div class="push_vwefg">
            <select id="kinds" style="width:216px;height:38px;border:1px solid #b8b8b8;color:#a6a6a6;font-size:18px;margin-top:32px;margin-left:46px;border-radius:5px;">
                <option id="1">推送到手机</option>
                <option id="2">推送到邮箱</option>
                <option id="3">推送到App</option>    
            </select><br>
            <input id="title" name="title" style="border:1px solid #b8b8b8;height:38px;width:398px;border-radius:5px;margin-top:32px;margin-left:46px;font-size:18px;color:#a6a6a6;" placeholder="标题"><br>
            <input style="border:1px solid #b8b8b8;height:38px;width:216px;border-radius:5px;margin-top:32px;margin-left:46px;font-size:18px;color:#a6a6a6; display:none;" placeholder="上传图片文件...">
            <label style="color:#de2721;font-size:16px; display:none;">（要求图片大小为1208*440）</label>
            <textarea id="content" name="content" placeholder="文本信息..." rows="5" style="width:880px;border:1px solid #b8b8b8;border-radius:5px;margin-top:32px;margin-left:46px;font-size:18px;color:#a6a6a6;"></textarea>
            <input type="button" onclick="pushAll()" value="确定" style="border:1px solid #b8b8b8;height:38px;width:198px;border-radius:5px;margin-top:32px;margin-left:46px;font-size:18px; cursor:pointer;" />
        </div>
        <script type="text/javascript">
            function pushAll() {
                var type = $("#kinds option:selected").attr("id");
                console.log(type);
                 $.ajax({
                     type: "post",
                     url: "/push/msg",
                     data: {title:$("#title").val(), content:$("#content").val(), type:type},
                     // dataType: "json",    // 2015.09.28 ： 决定暂不用json；
                     success: function(data){
                        console.log(data);
                      }, error: function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log(XMLHttpRequest.status);
                        console.log(XMLHttpRequest.readyState);
                         console.log(textStatus);
                        },
                    });

            }
        </script>
<!--设备推送结束-->
    @stop
@stop