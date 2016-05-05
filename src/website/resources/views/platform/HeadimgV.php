<!html>
<html lang="zh-cn">
<head>
 <meta http-equiv="pragma" content="no-cache">
 <meta http-equiv="cache-control" content="no-cache">
 <meta http-equiv="expires" content="0">   
 <meta charset="utf-8"/>
<title>设置头像</title>
<link href="/static/platform/platformcss/bootstrap.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/headimg.css" rel="stylesheet"/>
<script src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script>

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
// window.opener.location.reload();//刷新父窗口中的网页
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
		}
		// var m_img = document.createElement("img"); /*创建一个img结点*/ 
		// var l_img = document.createElement("img");
		// var b_img = document.createElement("img");
		// var leftimg = document.getElementById('leftimg');
		// var rightimg = document.getElementById('rightimg');
		
		// leftimg.removeChild(document.getElementById('b_headimg'));		
		// leftimg.appendChild(b_img);//把p结点接上去alert(testdiv.innerHTML);
		// b_img.setAttribute('id','b_headimg');
		
		// b_img.src=ar[0];
		
		// rightimg.removeChild(document.getElementById('m_headimg'));
		// rightimg.removeChild(document.getElementById('l_headimg'));
		// rightimg.appendChild(m_img);
		// rightimg.appendChild(l_img);
		// m_img.setAttribute('id','m_headimg');
		// l_img.setAttribute('id','l_headimg');
		
		// m_img.src = ar[1];
		// l_img.src = ar[2];
		
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
<div class="cont-head"> 
<h4>爱悠账号&nbsp;>&nbsp;<font style="color:#ec680c">设置头像</font></h4>
<h6><?php if(!empty($info)) echo $info["nickname"]
?>&nbsp;&nbsp;<a href="/user/loginout">|&nbsp;&nbsp;退出</a></h6>
</div>

<div class="form" >
<div style="min-height:100px">
<div class="form-head-left">
<a style="margin-top:15px;margin-left:40px;display:block" onclick="showupload()" ><img src="/static/platform/platformimages/photo.png"/></a>
<p style="margin-left:40px;display:block">仅支持JPG、PNG、JIF格式图片，且文件大小小于3M。</p>
</div>
<div class="form-head-right">
<a href="javascript:void(0)" onclick="save()" style="width:100px;line-height:33px;background:#ec680c;display:block;vertical-align:50%;text-align:center;border-radius:5px;"><font style="color:#ffffff">保存</font></a>
<a href="http://www.asqql.com/gifc/" target="_blank" style="width:100px;line-height:33px;background:#ec680c;display:block;vertical-align:50%;text-align:center;border-radius:5px;margin-top:3px;"><font style="color:#ffffff">在线切割图片</font></a>
</div>
</div>
<div class="form-foot-left">
<a onclick="showupload()">
<img src="/static/platform/platformimages/choicephoto.png" style="margin-left:40px"/></a>
</div>
<div class="form-foot-right">
<p style="margin-left:50px;padding-top:10px;">您上传的头像将自动生成三种尺寸的头像和高清头像<br/>请注意中小尺寸的头像是否清晰</p>
<div>
<div class="leftimg" id="leftimg"><img src="/static/platform/platformimages/bigheadimg.png" id="b_headimg"/><p>大尺寸头像，180*180像素</p></div>
<div class="rightimg" id="rightimg"><img src="/static/platform/platformimages/mediumheadimg.png" id="m_headimg"/><p>中尺寸头像，50*50像素</p>
<img src="/static/platform/platformimages/littleheadimg.png" id="l_headimg"/><p>小尺寸头像，30*30像素</p></div>
</div>
</div>
</div>
</div>
<!--content结束-->
<!--页脚开始-->
<div class="footer">
<br/>
<br/>
<p style="margin:0 auto;width:200px;">爱悠公司版权所有</p>
</div>
<!--页脚结束-->
</body>
</html>