<!html>
<html lang="zh-cn">
<head>
 <meta http-equiv="pragma" content="no-cache">
 <meta http-equiv="cache-control" content="no-cache">
 <meta http-equiv="expires" content="0">   
 <meta charset="utf-8"/>
<title>设置头像</title>
<link rel="stylesheet" type="text/css" href="/static/platform/upload/css/main.css" />
<link rel="Stylesheet" type="text/css" href="/static/platform/upload/css/jquery-ui-1.7.2.custom.css"  /> 
<style type="text/css">
*{margin:0px;font-size:12px}
.crop{width:680px; margin:20px auto; border:1px solid #d3d3d3; padding:4px; background:#fff}
#cropzoom_container{float:left; width:500px}
#preview{float:right; width:180px; height:150px; border:1px solid #999; margin-right:15px; padding:4px; background:#f7f7f7;}
.page_btn{float:right; width:180px;  margin-top:20px; line-height:30px; text-align:center}
.clear{clear:both}
.btn{cursor:pointer}


.back{background:#d8d8d8;height:70px;margin:auto 0;}
.ulcls{list-style:none;padding-left:20px;line-height:70px;}
.logowidth{width:220px;}
.pl10{padding-left:10px;}
.menupos{top: 0px; position: absolute; left: 260px; overflow: hidden;height:70px;width:700px;line-height:70px;}
.firstli{background:#65765f;width:80px;padding:5px;height:70px;float:left;text-align:center;line-height:70px;}
.sencli{background:#39bd05;width:100px;text-align:center;float:left;height:70px;line-height:70px;border-left:1px solid #d8d8d8;}
.threeli{background:#65765f;width:80px;padding:5px;height:70px;float:left;text-align:center;line-height:70px;border-left:1px solid #d8d8d8;}
.lia{color:#ffffff;font-weight:bold;text-decoration:none;}

.textpos{text-align:left;top: 2px; position: absolute; right: 50px; overflow: hidden;font-size:12px;color:#363d34;line-height:16px;}
.texta{color:#0652ec;text-decoration:none;}
</style>
<link href="/static/platform/platformcss/bootstrap.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/headimg.css" rel="stylesheet"/>

<script type="text/javascript" src="/static/platform/upload/js/jquery.js"></script>
<script type="text/javascript" src="/static/platform/upload/js/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="/static/platform/upload/js/jquery.cropzoom.js"></script>
<script type="text/javascript">
var winWidth = 0; //全局变量一
var winHeight = 0; //全局变量二
function findDimensions() //函数：获取尺寸 
{ 
	//获取窗口宽度 
	if (window.innerWidth) 
		winWidth = window.innerWidth; 
	else if ((document.body) && (document.body.clientWidth)) 
		winWidth = document.body.clientWidth; 
	//获取窗口高度 
	if (window.innerHeight) 
		winHeight = window.innerHeight; 
	else if ((document.body) && (document.body.clientHeight)) 
		winHeight = document.body.clientHeight; 
	//通过深入Document内部对body进行检测，获取窗口大小 
	if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth) 
	{ 
		winHeight = document.documentElement.clientHeight; 
		winWidth = document.documentElement.clientWidth; 
	} 
} 
function showupload()//显示上传图片的DIV或者window
{
	findDimensions();
	window.open ('/plug-in/FileDrop/demo/basic.html','newwindow','height=150,width=400,top='+(winHeight/2 - 50)+'px,left='+(winWidth/2-50)+'px,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
	//window.opener.location.reload();//刷新父窗口中的网页
}
function cutphoto()
{
	$('#generated').attr('src', '/upfile/avatar/b_<?php echo $_SESSION['user']['user']['id'].".jpg";?>'); 
}
function exist()//ajax取图片的url，该方法在子窗口关闭时被调用
{
	$.ajax(
	{
		type:'get',
		url:'/ajax/headimg/exist',
		success: function(data)
		{
			data = data.replace(/\r\n/g,'');
			if (data != '0')//返回的数据是不是空值
			{
				var ar = JSON.parse(data);
				document.getElementById('l_headimg').src = ar[2];
				document.getElementById('m_headimg').src = ar[1];
				document.getElementById('b_headimg').src = ar[0];
				$('#generated').attr('src', ar[0]); 
			}
		},error:function (){
		//没有正常获取数据
		}
	});
}
window.onload = exist();
function save()//保存
{
	var r=confirm("您已经保存了。是否跳转到个人中心")
	if (r==true)
	{
		window.location.href = "/user/center";
	}
}
<?php
	$filename=$_SESSION['user']['user']['id'].".jpg";
	if (file_exists(public_path() . '/upfile/avatar/' . $filename)) {
		echo '$(function() {
			var cropzoom = $("#cropzoom_container").cropzoom({
				width: 450,
				height: 300,
				bgColor: "#ccc",
				enableRotation: true,
				enableZoom: true,
				selector: {
					w:120,
					h:120,
					showPositionsOnDrag:true,
					showDimetionsOnDrag:false,
					centered: true,
					bgInfoLayer:"#fff",
					borderColor: "blue",
					animated: false,
					//maxWidth:100,
					//maxHeight:100,
					borderColorHover: "yellow"
				},
				image: {
					source: "/upfile/avatar/' . $filename . '",
					width: 450,
					height: 300,
					minZoom: 30,
					maxZoom: 150
				}
			});
			//console.log("156");
			$("#crop").click(function(){
				cropzoom.send("/static/platform/upload/resize_and_crop.php", "POST", {}, function(imgRet) {
					if (imgRet == 1) {
						$("#generated").attr("src", "/upfile/avatar/b_' . $filename . '"); 
					}
					window.location.reload();
				});
			});
			$("#restore").click(function(){
				$("#generated").attr("src", "/static/platform/upload/tmp/head.gif");
				cropzoom.restore();		  
			});
			
		});
		';
	}
?>
function refreshphoto()
{
	var rand = Math.random();
	$.ajax({
		type:"get",
		url:"/user/getheadimg?id="+rand,
		success:function(data){
			//alert(data);
			document.getElementById("generated").src = data;
		}
	});
}
function cutphoto()
{
	$("#generated").attr("src", "/upfile/avatar/b_<?php echo $_SESSION['user']['user']['id'].'.jpg';?>"); 	
}
</script>
</head>
<body>
<!--头部开始-->
<div class="head" style="">

<img src="/static/platform/platformimages/logo1.png"/>
</div>
<!--头部结束-->
<!--content开始-->
<div class="content"> 
<div class="cont-head" > 
	<h4><a href="/user/account">爱悠账号</a>&nbsp;>&nbsp;<font style="color:#ec680c">设置头像</font></h4>
	<h6><?php
	if($_SESSION['user']['user']['email']!=''){
		echo $_SESSION['user']['user']['email'];
	}
	if($_SESSION['user']['user']['phone']!=''){
		echo $_SESSION['user']['user']['phone'];
	}
?>&nbsp;&nbsp;<a href="/user/loginout">|&nbsp;&nbsp;退出</a></h6>
</div>

<div class="form" style="height:660px !important;">
	<!--JS插件里面的HTML-->
	<div id="">
		<div class="crop">
			<div id="cropzoom_container"></div>
			<div id="preview"><img id="generated" src="/static/platform/upload/tmp/head.gif" style="height:120px;width:120px;margin-left:25px;margin-top:10px;" /></div>
			<div class="page_btn" style="">
				<input type="button" class="btn" id="crop" value="剪切照片" style="background:#ec680c;color:#ffffff;font-size:12px;"/>
				<input type="button" class="btn" id="restore" value="照片复位" style="background:#ec680c;color:#ffffff;font-size:12px;"/>
				<a href="javascript:void(0)" onclick="save()" style="width:100px;height:30px;line-height:33px;background:#ec680c;display:block;vertical-align:50%;text-align:center;border-radius:5px;float:right;margin-right:15px;margin-top:30px;"><font style="color:#ffffff;">保存</font></a>
			</div>
			<div class="clear" >
			</div>
			<div style="min-height:70px">
				<div class="form-head-left">
				<a style="margin-top:15px;margin-left:40px;display:block" onclick="showupload()" ><img src="/static/platform/platformimages/photo.png"/></a><!---->
				<p style="margin-left:40px;display:block">仅支持JPG、PNG、JIF格式图片，且文件大小小于3M。</p>
				</div>
				<div class="form-head-right">
					
				</div>
			</div>
		</div>
		<!--JS插件里面的HTML-->

		<div class="form-foot-left" style="margin-right:0px;float:left;">
		<?php
		//查看有没有图片存在

		if (file_exists(public_path() . '/upfile/avatar/' . $filename)) {
			echo '<img src="/upfile/avatar/' . $filename .'" style="margin-left:40px;width:200px;height:200px;" />';
		} else {
		echo '<img src="/static/platform/platformimages/choicephoto.png" style="margin-left:40px"/>';
		}?>
		</a>
		</div>
		<div class="form-foot-right" style="font-size:12px;float:left;">
		<p style="margin-left:50px;padding-top:10px;">您上传的头像将自动生成三种尺寸的头像和高清头像<br/>请注意中小尺寸的头像是否清晰</p>
		<div>
			<div class="leftimg" id="leftimg"><img src="/static/platform/platformimages/bigheadimg.png" id="b_headimg" style="height:100px;width:100px;"/><p>大尺寸头像，100*100像素</p></div>
			<div class="rightimg" id="rightimg" style="margin-right:10px;"><img src="/static/platform/platformimages/mediumheadimg.png" id="m_headimg" style="height:50px;width:50px;"/><p>中尺寸头像，50*50像素</p>
			<img src="/static/platform/platformimages/littleheadimg.png" id="l_headimg" style="height:30px;width:30px;"/><p>小尺寸头像，30*30像素</p></div>
		</div>
		</div>
	</div>
</div>
</div>
<!--content结束-->
<!--页脚开始-->
	<div class="foots">
		<br/>
		<br/>
		<p style="text-align:center;">  山东智慧生活数据系统有限公司 鲁ICP备140332269号-1</p>
	</div>
	<!--页脚结束-->
</body>
</html>