<!html>
<html lang="zh-cn">
<head>
 <meta charset="utf-8"/>
<title>绑定手机号码</title>
<link href="/static/platform/platformcss/bootstrap.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/bindemail.css" rel="stylesheet"/>
 <script src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script>
 function re_email()//验证手机号码，返回状态
 {
  var email=document.getElementById("email");

  var reg = /^([a-zA-Z0-9_-])+/;
  if(!reg.test(email.value))
  {
   // 手机号码不符合规则
   return 0;
  }
  else
  {
   // 手机号码符合规则
   return 1;
  }
 }
 function email()//验证手机号码，返回提示
 {
  var state=re_email();
  var msg=document.getElementById("showemail");

  if(state==0)
  {
   showdiv(msg,"手机号码不符合规则");
  }else if(state == 1)
  {
   hidediv();
  }

 }

 function bindemailaction()//ajax验证
 {
  var state = re_email();
  var email = document.getElementById('email');

  if(state)
  {
   $.ajax(
   {
      type:"post",
      url:"/user/bindphoneaction",
      data:{email:email.value},
      success:function(data)
      {
       data = data.replace(/\r\n/g,'');

      //alert(data);
       switch(data)
       {
        case '-1':
               alert('未知错误');
               return false;
               break;
        case '1':
               second_step();
               break;
        case '2':
               alert('手机号码不合法');
               return false;
               break;
        case '3':
               alert('手机号码已注册');
               return false;
               break;
       }
      },
      error:function()
      {
       alert('未知的错误');
       return false;
      }
   })

  }
  else
  {
   alert('手机号码不符合规则');
   email.focus();
   return false;
  }
 }
 function second_step()//第二步
 {
  $("strong.strong2").first().css('border-color', '#ec680c #ec680c #ec680c transparent');
  $("span.span2").first().css('background', '#ec680c');
  $("span.span2").first().css('color', 'white');
  $("strong.strong3").first().css('border-color', 'transparent transparent transparent #ec680c');
  $('#enter_email').css('display', 'none');
  $('#enter_code').css('display', 'block');
  $('#second_step').css('display', 'none');
  $('#third_step2').css('display', 'block');
 }
 function third_step()//第三步
 {
  $("strong.strong2").eq(1).css('border-color', '#ec680c #ec680c #ec680c transparent');
  $("span.span2").eq(1).css('background', '#ec680c');
  $("span.span2").eq(1).css('color', 'white');
  $('#enter_code').css('display', 'none');
	$('#third_step').css('display', 'block');
	$('#third_step2').css('display', 'none');
	$('#third_step3').css('display', 'block');
 }
 function re_code()//验证验证码格式，返回状态
 {
   var code = $('#code').val();
   var reg = /[0-9]{4}/;
   if (!reg.test(code))
   {
     alert('验证码不符合标准');
     code.focus();
     return false;
   }
   return true;
 }
 function code()//验证验证码
 {
   var state = 1;
   var code = $('#code').val();
   var mailname = $('#email').val();
  if(state)
  {
   $.ajax(
       {
        type:"post",
        url:"/user/platform_bindphone_code",
        data:{code:code,mailname:mailname},
        success:function(data)
        {
			 data = data.replace(/\r\n/g,'');
			//alert(mailname);
			  //alert(data);
			   switch(data)
			   {
				case '-1':
					   alert('未知错误');
					   return false;
					   break;
				case '1':
					   third_step();
					   break;
			    case '0':
					   alert('手机号码绑定失败');
					   return false;
					   break;
			   }
        },error:function()
       {
			alert('未知的错误');
			return false;
       }
       })
  }
  else
  {
    return false;
  }
 }
 window.onload = function ()
 {
//  second_step();
//  third_step();
// alert(code());
 }
 function showdiv(msg,msginfo)//msg参数为需要提示的div,msginfo为提示信息
 {
  msg.style.display="block";
  msg.innerHTML=msginfo;
 }
 function hidediv()//隐藏一个div,msg为div的ID
 {
  document.getElementById('showemail').style.display="none";
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
<h4><a href="/user/account">爱悠账号</a>&nbsp;>&nbsp;<font style="color:#ec680c">绑定手机号码</font></h4>
<h6><?php
	if($_SESSION['user']['user']['email']!=''){
		echo $_SESSION['user']['user']['email'];
	}
	if($_SESSION['user']['user']['phone']!=''){
		echo $_SESSION['user']['user']['phone'];
	}
?>&nbsp;&nbsp;<a href="/user/loginout">|&nbsp;&nbsp;退出</a></h6>
</div>
<div class="arrow">
<div class="arrow1">
<span class="span1">&nbsp;1&nbsp;输入手机号码</span>
<strong class="strong1"></strong>
</div>
<div class="arrow2">
<strong class="strong2"></strong>
<span class="span2">&nbsp;2&nbsp;验证手机号码</span>
<strong class="strong3"></strong>
</div>
<div class="arrow3">
<strong class="strong2"></strong>
<span class="span2">&nbsp;3&nbsp;完成</span>
</div>
</div>
<div class="form" >
<table style="margin:0 auto;">
	<tr id="enter_email">
		<td style="text-align:left;width:220px;">请输入您要绑定的手机号码:&nbsp;</td>
		<td><input class="form-control" type="text" style="width:30%;min-width:200px;float:right;display:block" id="email" onBlur="email()"/></td>
		<td><div id="showemail" style="color:red;display:none">手机号码不符合规则</div></td>
	</tr>
	<tr id="enter_code" style="display:none">
		<td style="text-align:left;width:180px;">请输入您手机号码中的验证码</td>
		<td><input class="form-control" type="text" style="width:30%;min-width:200px;float:right;display:block" id="code" /></td>
		<td><div id="showcode" style="color:red;display:none">验证码错误</div></td>
	</tr>
	<tr id="third_step" style="display:none">
		<td style="text-align:left;width:180px;">手机号码验证完成</td>
		<td><input class="form-control" type="text" style="width:30%;min-width:200px;float:right;display:none" /></td>
		<td><div style="color:red;display:block"></div></td>
	</tr>
	<tr id="second_step">
		<td style="text-align:left;width:180px;"></td>
		<td style="text-align:right"><a type="button" onclick="bindemailaction()" style="background:#ec680c;float:left;display:block;width:109px;line-height:30px;margin:0 auto;border-radius:5px;text-align:center"><font style="color:#ffffff">下一步</font></a></td>
		<td></td>
	</tr>
	<tr id="third_step2" style="display:none">
		<td style="text-align:left;width:180px;"></td>
		<td style="text-align:right"><a type="button" onclick="code()" style="background:#ec680c;float:left;display:block;width:109px;line-height:30px;margin:0 auto;border-radius:5px;text-align:center"><font style="color:#ffffff">下一步</font></a></td>
		<td></td>
	</tr>
	<tr id="third_step3" style="display:none">
		<td style="text-align:left;width:180px;"></td>
		<td style="text-align:right"><a href="/user/center" style="background:#ec680c;float:left;display:block;width:109px;line-height:30px;margin:0 auto;border-radius:5px;text-align:center"><font style="color:#ffffff">返回</font></a></td>
		<td></td>
	</tr>
</table>
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