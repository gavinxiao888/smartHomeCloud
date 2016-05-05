<!html>
<html lang="zh-cn">
<head>
<meta charset="utf-8"/>
<title>找回密码</title>
<link href="/static/platform/platformcss/bootstrap.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/bindemail.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/lsf.css" rel="stylesheet"/>
<script src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script>
	function code(){
		re_code();
	}
	function re_code(){//ajax验证验证码
		var code= document.getElementById("code");
		if(code.value.length==4){
			$.ajax({
				type: "post",
				data:{ code: code.value},  
				url: "/user/code",
				success: function(data)
				{
					data = data.replace(/\r\n/g,'');
					var showcode=document.getElementById("showcode");	
					
					switch(data)
					{		
						case "0":			
						showdiv(showcode,"验证码已过期");
						setTimeout(changeCode,1000);		
						break;
						case "1":
						document.getElementById("iok").style.display="block";
						hidediv(showcode);
						break;
						case "2":
						document.getElementById("iok").style.display="none";
						showdiv(showcode,"输入验证码不符");				
						break;	
						default:		
						break;
					}	
				},error:function (){                
					return 0;
				}
			});
		}
	}
	function changeCode()//验证码
	{		
		document.getElementById("codeimg").src = "/code/platformcode?id="+Math.random();
		hidediv(document.getElementById("iok"));
	}
	function validataquest(){//验证密保
		var answerfirst = $('#answerfirst').val();
		var answersecond = $('#answersecond').val();
		var answerthird = $('#answerthird').val();
		//alert(answerfirst);
		if(answerfirst!=''&&answersecond!=''&&answerthird!=''){
			$.ajax({
				type:'post',
				data:{id:$('#userinfoid').val(),answerfirst:answerfirst,answersecond:answersecond,answerthird:answerthird},
				url:'/user/checkquestion',
				success: function(data){
					data = data.replace(/\r\n/g,'');
					//alert(data);
					switch(data)
					{
						case "-1":
							alert('未设置密保');
							return false;
							break;
						case "0":			
							alert('答案写错了');
							return false;
							break;
						case "1":
							third_step();
							break;
						case "2":
							alert('邮箱未绑定，请联系管理员');
							return false;
							break;	
						default:		
							break;
					}
				},
				error:function (){                
					alert('网络超时或异常');
				}
			});
		}
		else{
			alert('答案不能为空');
		}
	}
	function re_email()//验证邮箱，返回状态
	{
		var email=document.getElementById("email");
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
		if(!reg.test(email.value))
		{
			// 邮箱不符合规则
			return 0;
		}
		if(email.value == ''){
			//为空
			return 2;
		}
		else
		{
			// 邮箱符合规则
			return 1;
		}
	}
	function email()//验证邮箱，返回提示
	{
		var state=re_email();
		var msg=document.getElementById("showemail");
		var msg1=document.getElementById("showemail1");
		if(state==0)
		{
			showdiv(msg,"邮箱不符合规则");
		}
		else if(state == 2)
		{
			showdiv(msg1,"请输入绑定邮箱");
		}
		else if(state == 1)
		{
			hidediv();
		}
	}
	function bindemailaction(){
		re_code();
		var state = re_email();
		var email = document.getElementById('email');
		if(state){
			$.ajax({
				type:"post",
				url:"/user/mailexist",
				data:{email:email.value},
				dataType:"json",
				success:function(data){
					//data = data.replace(/\r\n/g,'');
					//alert(data.user_id);
					if(data.id==0){
						alert('邮箱不存在');
						return false;
					}
					else{
						//alert('222');
						$('#userinfoid').val(data.id);
						$('#questfirst').text(data.problem_first);
						$('#questsecond').text(data.problem_second);
						$('#questthird').text(data.problem_third);
						second_step();
					}
				},
				error:function()
				{
					alert('未知的错误');
					return false;
				}
			})
		}
		else{
			alert('邮箱不符合规则');
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
		$('#enter_email1').css('display', 'none');
		$('#enter_email2').css('display', 'none');
		$('#enter_code1').css('display', 'block');
		$('#enter_code2').css('display', 'block');
		$('#enter_code3').css('display', 'block');
		$('#enter_code4').css('display', 'block');
		$('#enter_code5').css('display', 'block');
		$('#enter_code6').css('display', 'block');
		$('#enter_code7').css('display', 'block');
		$('#second_step').css('display', 'none');
		$('#third_step2').css('display', 'block');
	}
	function third_step()//第三步
	{
		$("strong.strong2").eq(1).css('border-color', '#ec680c #ec680c #ec680c transparent');
		$("span.span2").eq(1).css('background', '#ec680c');
		$("span.span2").eq(1).css('color', 'white');
		$('#enter_code1').css('display', 'none');
		$('#enter_code2').css('display', 'none');
		$('#enter_code3').css('display', 'none');
		$('#enter_code4').css('display', 'none');
		$('#enter_code5').css('display', 'none');
		$('#enter_code6').css('display', 'none');
		$('#enter_code7').css('display', 'none');
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
	window.onload = function ()
	{
		// second_step();
		// third_step();
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
	function hidediv1()//隐藏一个div,msg为div的ID
	{
		document.getElementById('showemail1').style.display="none";
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
<div class="content" style="height:none;mix-height:400px;">
	<div class="cont-head"> 
		<h4><a href="/user/login">爱悠账号</a>&nbsp;>&nbsp;<font style="color:#ec680c">找回密码</font></h4>
	</div>
	<div class="arrow">
		<div class="arrow1">
			<span class="span1">&nbsp;1&nbsp;输入绑定邮箱</span>
			<strong class="strong1"></strong>
		</div>
		<div class="arrow2">
			<strong class="strong2"></strong>
			<span class="span2">&nbsp;2&nbsp;验证密保</span>
			<strong class="strong3"></strong>
		</div>
		<div class="arrow3">
			<strong class="strong2"></strong>
			<span class="span2">&nbsp;3&nbsp;完成</span>
		</div>
	</div>
	<div class="changepwdform">
		<table style="margin:0 auto;font-size:14px;">
			<!--------------------第一步------------------------------------------------------------------------->
			<tr id="enter_email1" style="height:35px;">
				<td style="text-align:right;width:150px;">请输入您的绑定邮箱：&nbsp;</td>
				<td><input class="form-control input-sm" type="text" style="min-width:200px;float:right;display:block;" id="email" onBlur="email()"/></td>
				<td style="text-align:center;mix-width:150px;"><div id="showemail" style="color:red;display:none">邮箱不符合规则</div>
				<div id="showemail1" style="color:red;display:none;width:120px;">请输入绑定邮箱</div></td>
			</tr>
			<tr id="enter_email2" style="height:35px;">
				<td class="span" style="text-align:right;width:150px;">验证码：&nbsp;</td>
				<td style="width:240px;"><input class="form-control input-sm" type="text" id="code" onBlur="code()" style="width:100px;display:block;float:left;"/>
				<a onClick="changeCode()"><img src="/code/platformcode" style="width:90px;height:25px;float:left;margin-left:10px;margin-top:5px;float:left;padding-left:5px;" id="codeimg" title="点击图片刷新验证码" />
				<span class="iok" id="iok"></span></a></td>
				<td style="text-align:center;min-width:150px;"><div id="showcode" style="color:red"></div></td>
			</tr>	
			<!--------------------第二步------------------------------------------------------------------------->
			<tr id="enter_code1" style="display:none;margin-top:-30px;">
				<td style="text-align:right;width:150px;"><input type="text" style="display:none" id="userinfoid"/></td>
				<td colspan="2" style="text-align:left;mix-width:150px;">请输入您设置的密保答案</td>
			</tr>
			<tr id="enter_code2" style="display:none;">
				<td style="text-align:right;width:150px;">问题一：&nbsp;</td>
				<td id="questfirst" style="min-width:200px;"></td><td style="text-align:center;mix-width:150px;"></td>
			</tr>
			<tr id="enter_code3" style="display:none">
				<td style="text-align:right;width:150px;">答案：&nbsp;</td>
				<td><input class="form-control input-sm" type="text" style="width:30%;min-width:200px;float:left;" id="answerfirst"/></td>
				<td style="text-align:center;mix-width:150px;"></td>
			</tr>
			<tr id="enter_code4" style="display:none">
				<td style="text-align:right;width:150px;">问题二：&nbsp;</td>
				<td id="questsecond" style="min-width:200px;"></td><td style="text-align:center;mix-width:150px;"></td>
			</tr>
			<tr id="enter_code5" style="display:none">
				<td style="text-align:right;width:150px;">答案：&nbsp;</td>
				<td><input class="form-control input-sm" type="text" style="width:30%;min-width:200px;float:left;" id="answersecond"/></td>
				<td style="text-align:center;mix-width:150px;"></td>
			</tr>
			<tr id="enter_code6" style="display:none">
				<td style="text-align:right;width:150px;">问题三：&nbsp;</td>
				<td id="questthird" style="min-width:200px;"></td><td style="text-align:center;mix-width:150px;"></td>
			</tr>
			<tr id="enter_code7" style="display:none">
				<td style="text-align:right;width:150px;">答案：&nbsp;</td>
				<td><input class="form-control input-sm" type="text" style="width:30%;min-width:200px;float:left;" id="answerthird"/></td>
				<td style="text-align:center;mix-width:150px;"></td>
			</tr>
			<!--------------------第三步------------------------------------------------------------------------->
			<tr id="third_step" style="display:none;">
				<td style="text-align:left;width:150px;"></td>
				<td><input class="form-control input-sm" type="text" style="width:200px;float:right;display:none" />验证完成</td>
				<td style="text-align:center;mix-width:150px;"><div style="color:red;display:block"></div></td>
			</tr>
			<tr id="second_step" style="display:block;margin-top:5px;">
				<td style="text-align:left;width:150px;"></td>
				<td style="text-align:right"><a type="button" onclick="bindemailaction()" style="background:#ec680c;float:left;display:block;width:109px;line-height:30px;margin:0 auto;border-radius:5px;text-align:center"><font style="color:#ffffff">确认</font></a></td>
				<td style="text-align:center;mix-width:150px;"></td>
			</tr>
			<tr id="third_step2" style="display:none;margin-top:10px;">
				<td style="text-align:left;width:150px;"></td>
				<td style="text-align:right"><a type="button" onclick="validataquest()" style="background:#ec680c;float:left;display:block;width:109px;line-height:30px;margin:0 auto;border-radius:5px;text-align:center"><font style="color:#ffffff">确认</font></a></td>
				<td style="text-align:center;mix-width:150px;"></td>
			</tr>
			<tr id="third_step3" style="display:none;margin-top:10px;">
				<td style="text-align:left;width:150px;"></td>
				<td style="text-align:right"><a href="/user/login" style="background:#ec680c;float:left;display:block;width:109px;line-height:30px;margin:0 auto;border-radius:5px;text-align:center"><font style="color:#ffffff">返回登录</font></a></td>
				<td style="text-align:center;mix-width:150px;"></td>
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