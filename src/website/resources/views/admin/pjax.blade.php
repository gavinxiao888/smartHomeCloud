 @section('header')
 	@show()
  		@yield('content')
                <script type="text/javascript">
                    var d, s = "";
                    var x = new Array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
                    d = new Date();
                    s += d.getFullYear() + "年" + (d.getMonth() + 1) + "月" + d.getDate() + "日　";
                    s += "&nbsp;&nbsp;" + x[d.getDay()];
                    $(".gzhlb_mnow").html('<img src="/site/images/admin_7.png">今天是' +  s);
                </script>