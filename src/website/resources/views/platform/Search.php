<!html>
<html lang="zh-cn">
<head>
 <meta charset="utf-8"/>
<title>个人中心</title>
<link href="/static/platform/platformcss/search.css" rel="stylesheet"/>
<script src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">//导航下拉
$(document).ready(function(){
	jQuery.navlevel2 = function(level1,dytime) {
		
	  $(level1).mouseenter(function(){
		  varthis = $(this);
		  delytime=setTimeout(function(){
			varthis.find('ul').slideDown();
		},dytime);
		
	  });
	  $(level1).mouseleave(function(){
		 clearTimeout(delytime);
		 $(this).find('ul').slideUp();
	  });
	  
	};
  $.navlevel2("li.mainlevel",200);
});

function addCookie(name,value,expires,path, domain)//设置cookie
{
    var str=name+"="+escape(value);
    if(expires!=""){
        var date=new Date();
        date.setTime(date.getTime()+expires*24*3600*1000);//expires单位为天
        str+=";expires="+date.toGMTString();
    }
    if(path!=""){
        str+=";path="+path;//指定可访问cookie的目录
    }
    if(domain!=""){
        str+=";domain="+domain;//指定可访问cookie的域
    }
    document.cookie=str;
}
</script>
<script type="text/javascript">
    function BindEnter(obj)
    {
        if(obj.keyCode == 13) {
            var word = $('#search').val();
            if ($.trim($('#search').val()) != "")                {
                document.location.href="/user/search?query="+$('#search').val();
            }
            if ($.trim($('#appendedInputButtons').val()) != "")                {
                document.location.href="/user/search?query="+$('#appendedInputButtons').val();
            }
            obj.returnValue = false;
        }
    }
    function searchd()
    {
        document.location.href="/user/search?query="+$('#appendedInputButtons').val();
    }
</script>
</head>
<body  onkeydown="BindEnter(event)">
<!--头部开始-->
<div class="head">
<p style="height:30px;line-height:30px;overflow:hidden; ">欢迎您<span>zzx11235</span>&nbsp;&nbsp;|<a href="/user/zzx11235/mydevice">我的设备</a>|&nbsp;&nbsp;<a href="/user/zzx11235/account">我的爱悠账号</a ></p>
</div>
<!--头部结束-->
<!--content开始-->
<div class="content">
<!--搜索栏-->
<div class="sibar">
<img src="/static/platform/platformimages/logo2.png" class="sibar-logo" />
<div class="sibar-right">
 <div class="input-group" style="width:265px">
<input class="form-control" type="text" placeholder="搜一下" id="appendedInputButtons">
<div class="input-group-addon"><a onclick="searchd()"><img src="/static/platform/platformimages/sibar.png" /></a></div>
</div>

<div class="cart">
<a ><img src="/static/platform/platformimages/cart.png" style="position: absolute; top: 6px;left: 15px;">&nbsp;<font style="color:white;font-size:12px;  height: 30px;line-height: 30px;">购物车</font></a>
</div>
</div>
</div>
<!--搜索栏结束-->
<!--头部导航开始-->
<div class="navi" style="">
<ul id="nav">
    <li class="mainlevel" style="width:200px;margin-left:-40px;"><a href="">全部商品分类&nbsp;<img src="/static/platform/platformimages/arrow-white.png"/></a>
        <ul>
        	<li  style="width:200px;"><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>


        </ul>
    </li>
    
    <li class="mainlevel" style="background:#404144"><a href="">首页</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
    
    <li class="mainlevel" style="background:#404144"><a href="">智慧云盒</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
    <li class="mainlevel" style="background:#404144"><a href="">智慧云投影</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
	    <li class="mainlevel" style="background:#404144"><a href="">智慧云平板</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
	    <li class="mainlevel" style="background:#404144"><a href="">智慧云摄像头</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
	    <li class="mainlevel" style="background:#404144"><a href="">服务</a>
        <ul>
        	<li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
        <li class="mainlevel" style="background:#404144"><a href="">在线留言</a>
        <ul>
            <li><a href="http://www.51xuediannao.com/js/texiao/">网页特效</a></li>

        </ul>
    </li>
</ul>
</div>
<!--头部导航结束-->

<!--位置导航-->
<div class="position">
<h5><a>首页</a>&nbsp;/&nbsp;<a>搜索结果</a></h5>
</div>
<!--位置导航结束-->
    <!--CONTENT开始-->
    <div id="content" style="overflow: hidden">
        <div class="head-title">
            <h3><?php
                    echo $word;
                ?></h3>

        </div>
        <div id="info">
            <ul>
                <li  style="float: left;margin-right: 50px">
                    <div class="item">
                        <a>
                            <img src="sb" width="180px" height="144px"/>
                            <h5>这里是标题</h5>
                        </a>
                    </div>
                </li>
                <li  style="float: left;margin-right: 50px">
                    <div class="item">
                        <a>
                            <img src="sb" width="180px" height="144px"/>
                            <h5>这里是标题</h5>
                        </a>
                    </div>
                </li>
                <li  style="float: left;margin-right: 50px">
                    <div class="item">
                        <a>
                            <img src="sb" width="180px" height="144px"/>
                            <h5>这里是标题</h5>
                        </a>
                    </div>
                </li>
                <li  style="float: left;margin-right: 50px">
                    <div class="item">
                        <a>
                            <img src="sb" width="180px" height="144px"/>
                            <h5>这里是标题</h5>
                        </a>
                    </div>
                </li>
                <?php
                //输出信息
                    foreach ($info as $item) {
                        echo '<li style="float: left;margin-left:50px;"><div class="item"><a href="', $item['uri'] ,'"><img src="', $item['path'], '" width="180px" height="144px" /><h5>', $item['title'],'</h5></a></div>';
                    }
                ?>
            </ul>
        </div>
    </div>
    <!--CONTENT结束-->
    <div id="ye">
        <style>
            .yema a, .yema span.miy {
                border: 1px solid #aaaadd;
                padding: 2px 5px 2px 5px;
                margin: 2px;
                color: #000099;
                text-decoration: none;

            }

            .yema a:hover {
                border: 1px solid #000099;
                color: #000000;
            }

            .yema a:active {
                border: 1px solid #000099;
                color: #000000;
            }

            .yema span.one {
                border: 1px solid #ec6911;
                background-color: #ec6911;
                padding: 2px 5px 2px 5px;
                margin: 2px;
                color: #FFFFFF;
                text-decoration: none;
            }

            .yema span.shou {
                border: 1px solid #eee;
                padding: 2px 5px 2px 5px;
                margin: 2px;
                color: #ddd;
            }
        </style>
        <div class="yema" style="text-align: center">
            <?php

            if ((!empty($pageIndex)) && (!empty($pageMax))) {
                $pagepri = $pageIndex - 1;//上一页
                $pagenext = $pageIndex + 1;//下一页

                if ($pagepri <= 1) {
                    $pagepri = 1;
                }
                if ($pagenext >= $pageMax) {
                    $pagenext = $pageMax;
                }
                if ($pageIndex == 1)//当前页面是第一页的时候
                {
                    echo '<span class="shou" style="font-family:微软雅黑;">首页</span>
        	<span class="shou" style="font-family:微软雅黑;"> < </span> ';
                } else//当前页面不是第一页的时候
                {
                    echo '<a href="', $url, '/p1?query=', $word, '">首页</a><a href="', $url, '/p', $pagepri, '?query=', $word, '">
         < </a></a>';
                }
                if ($pageIndex >= 4)//请求的页面大于4
                {
                    if ($pageIndex + 2 <= $pageMax)//请求的页面数+2小于等于页面总数
                    {
                        for ($i = $pageIndex - 2; $i <= $pageIndex + 2; $i++) {
                            if ($i == $pageIndex)//如果输出的页面中等于请求的页面
                            {
                                echo '<span class="one" >', $i, '</span>';
                            } else {
                                echo '<a href="', $url, '/p', $i, '?query=', $word, '"><span>', $i, '</span></a>';
                            }
                        }
                    } else//请求的页面大于4， 之后的页面小2
                    {
                        if ($pageMax == ($pageIndex + 1))//请求的页面之后还有一个页面
                        {
                            $pagestart = $pageIndex - 3;
                        } else//请求的页面是最后一页
                        {
                            $pagestart = $pageIndex - 4;
                        }
                        for ($i = $pagestart; $i <= $pageMax; $i++) {
                            if ($i == $pageIndex)//如果输出的页面中等于请求的页面
                            {
                                echo '<span class="one" >', $i, '</span>';
                            } else {
                                echo '<a href="', $url, '/p', $i, '?query=', $word, '"><span>', $i, '</span></a>';
                            }
                        }
                    }
                } else {
                    for ($i = 1; $i <= $pageMax; $i++) {
                        if ($i <= 5) {
                            if ($i == $pageIndex)//如果输出的页面中等于请求的页面
                            {
                                echo '<span class="one" >', $i, '</span>';
                            } else {
                                echo '<a href="', $url, '/p', $i, '?query=', $word, '"><span>', $i, '</span></a>';
                            }
                        }
                    }
                }
                if ($pageIndex == $pageMax)//当前页面等于最后一页
                {
                    echo '<span class="shou" style="font-family:微软雅黑;"> > </span><span class="shou" style="font-family:微软雅黑;">尾页</span>';
                } else {
                    echo '<a href="', $url, '/p', $pagenext, '?query=', $word, '"/><span class="" style="font-family:微软雅黑;"> > </span></a><a href="', $url, '/p', $pageMax, '?query=', $word, '">尾页</a>';
                }

            }

            ?>
            <!--
            <span class="shou" style="font-family:微软雅黑;">首页</span>
            <span class="shou" style="font-family:微软雅黑;"> < </span>
            <span class="one" >1</span>
                <a href="" >2</a>
            <a href="" >3</a>
            <a href="" >4</a>
            <span class="shou" style="font-family:微软雅黑;"> > </span>
               <a href="">尾页</a>
               -->
        </div>
<?php
var_dump($info);
?>
</body>
</html>