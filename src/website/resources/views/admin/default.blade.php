<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="renderer" content="webkit" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="/site/css/adminStyle.css">
            <script src="/site/js/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script>
            <script src="/site/js/admin.js" type="text/javascript" charset="utf-8"></script>
            <script src="/layer/layer.js" type="text/javascript" charset="utf-8"></script>
            <script src="/site/js/jquery.pjax.js" type="text/javascript" charset="utf-8"></script>
            <script src="/site/js/validVal/Validform_v5.3.2_min.js" type="text/javascript" charset="utf-8"></script>
            <title><?php echo env('ADMIN_TITLE');?></title>
    </head>

    <body>
        @section('header')
        <!--header开始-->
        <div class="gzhlb_toep">
            <div class="gzhlb_wqou">
                <div class="gzhlb_wjot a_click" id="home"><img src="/site/images/<?php echo env('ADMIN_LOGO');?>"><?php echo env('ADMIN_CLOUD_NAME');?></div>
                <div class="gzhlb_wpbrd">
                    <ul>
                        <li><img src="/site/images/admin_2.png">欢迎您，<?php
                                if (Session::has('name')) {
                                    echo Session::get('name');
                                } else {
                                    echo 'admin';
                                }
                                ?></li>
                        <li>|</li>
                        <li style="display:none;"><a href="">消息</a></li>
                        <!--  <li>|</li> -->
                        <li style="display:none;"><a href="">帮助</a></li>
                        <!-- <li>|</li> -->
                        <li><a id="quit" class="a_click">退出</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!--header结束-->


        <div class="gzhlb_yier">
            <!--left开始-->
            <div class="gzhlb_wgfwo">
                <div class="gzhlb_fkwp"><a class="a_click" href="/admin/1/device/show"><img src="/site/images/admin_3.png">设备管理</a></div>
                <div class="gzhlb_fjew">
                    <div class="gzhlb_ope">设备列表</div>
                    <div class="gzhlb_pjno">
                        <a href="/admin/gateway/list"><div class="gzhlb_openal index_nav">网关列表</div></a>
                        <a href="/admin/shading/list"><div class="gzhlb_openal2 index_nav"><?php echo env('ADMIN_NAV_DEVICE_NAME');?></div></a>
                    </div>
                </div>
                <a href="/admin/fault/list">
                    <div class="gzhlb_open index_nav">故障列表</div>
                </a>
                <a href="/admin/version/list">
                    <div class="gzhlb_open index_nav">版本管理</div>
                </a>
                <div class="gzhlb_fkwp">
                    <a class="a_click" href="/admin/1/user/show"><img src="/site/images/admin_4.png">用户管理</a>
                </div>
                <a href="/admin/user/list">
                    <div class="gzhlb_open index_nav">用户列表</div>
                </a>
                
                <?php
                if (Session::has('role')) {
                    if (Session::get('role') != 0) {
                        echo '<a href="/admin/admin/list"> <div class="gzhlb_open index_nav">管理员列表</div></a>';
                        echo '<a href="/admin/search/device"> <div class="gzhlb_open index_nav">设备查询</div></a>';
                    }
                }
                ?>
                
                <a href="/admin/editor/password">
                    <div class="gzhlb_open index_nav">修改密码</div>
                </a>
                <div class="gzhlb_fkwp">
                    <a class="a_click" href="/admin/1/push/show"><img src="/site/images/admin_5.png">推送管理</a>
                </div>
                <a href="/admin/push/show">
                    <div class="gzhlb_open index_nav">推送消息</div>
                </a>
                <a href="/admin/push/list">
                    <div class="gzhlb_open index_nav">推送消息记录</div>
                </a>
            </div>
            <!--left结束-->
            @show

            <!--content开始-->

            <div id="container">

                @yield('content')
            </div>
            <script>
                $(function () {
                    $(".add_admin").Validform({
                        tiptype: function (msg, o, cssctl) {
                            //msg：提示信息;
                            //o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
                            //cssctl:内置的提示信息样式控制函数，该函数需传入两个参数：显示提示信息的对象 和 当前提示的状态（既形参o中的type）;
                            if (!o.obj.is("form")) {//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
                                var objtip = o.obj.siblings(".Validform_checktip");
                                cssctl(objtip, o.type);
                                objtip.text(msg);
                            } else {
                                var objtip = o.obj.find("#msgdemo");
                                cssctl(objtip, o.type);
                                objtip.text(msg);
                            }
                        },
                        ajaxPost: true
                    });
                    var d, s = "";
                    var x = new Array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
                    d = new Date();
                    s += d.getFullYear() + "年" + (d.getMonth() + 1) + "月" + d.getDate() + "日　";
                    s += "&nbsp;&nbsp;" + x[d.getDay()];
                    $(".gzhlb_mnow").html('<img src="/site/images/admin_7.png">今天是' +  s);
                });
                $('#home').click(function (event) {
                    location.href = "/admin/index/show";
                });
                $('#quit').click(function (event) {
                    layer.confirm('您确定要退出吗?', {
                        btn: ['取消', '确定'] //按钮
                    }, function () {
                        window.parent.location.reload();
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    }, function () {
                        window.parent.location = "/admin/login";
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    });
                });
                $.pjax({
                    selector: 'a',
                    container: '#container', //内容替换的容器
                    show: 'fade', //展现的动画，支持默认和fade, 可以自定义动画方式，这里为自定义的function即可。
                    cache: false, //是否使用缓存
                    storage: false, //是否使用本地存储
                    titleSuffix: '', //标题后缀
                    filter: function () {},
                    callback: function () {}
                });
            </script>
            <!--content结束-->

        </div>
    </body>
</html>
