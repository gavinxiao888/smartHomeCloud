<!html>
<html lang="zh-cn">
<head>
 <meta charset="utf-8"/>
<title>设置密保问题</title>
<link href="/static/platform/platformcss/bootstrap.css" rel="stylesheet"/>
<link href="/static/platform/platformcss/securityquestion.css" rel="stylesheet"/>
<script src="/static/platform/js/jquery-1.9.1.min.js"></script>
<script>
function securityaction()//密保问题的提交动作
{

	var select1 = document.getElementById('select1');
	var select2 = document.getElementById('select2');
	var select3 = document.getElementById('select3');
	
	if(select1.value == select2.value  && select1.value != '请选择任何一项' )
	{
		select2.focus();
		alert('不能有相同的项');
		return false;
	}
	if(select1.value == select3.value && select1.value != '请选择任何一项')
	{
		select3.focus();
		alert('不能有相同的项');
		return false;
	}
	if(select2.value == select3.value && select2.value != '请选择任何一项' )
	{
		select3.focus();
		alert('不能有相同的项');
		return false;
	}
	if(select1.value == '请选择任何一项' )
	{
		select1.focus();
		alert('请选择');
		return false;
	}
	if(select2.value == '请选择任何一项')
	{
		select2.focus();
		alert('请选择');
		return false;
	}
	if(select3.value == '请选择任何一项')
	{
		select3.focus();
		alert('请选择');
		return false;
	}
	var a1 = document.getElementById('a1');
	var a2 = document.getElementById('a2');
	var a3 = document.getElementById('a3');
	
	if($.trim(a1.value).length == 0)//验证填写的内容是不是空的
	{
		a1.focus();
		alert('请填写非空内容');
		return false;
	}
	if($.trim(a2.value).length == 0)
	{
		a2.focus();
		alert('请填写非空内容');
		return false;
	}
	if($.trim(a3.value).length == 0)
	{
		a3.focus();
		alert('请填写非空内容');
		return false;
	}
	var options = document.getElementsByTagName('option');
	var option1_id ;
	var option2_id ; 
	var option3_id ;
	for(var i=0; i< options.length; i++)
	{
		if (options[i].value == select1.value)
		{
			option1_id = options[i].id;
		}
		if (options[i].value == select2.value)
		{
			option2_id = options[i].id;
		}
		if (options[i].value == select3.value)
		{
			option3_id = options[i].id;
		}
	}
	$.ajax({
		url:"/user/securityquestion/addaction",
		data:{problem1:option1_id,problem2:option2_id,problem3:option3_id,answer1:a1.value,answer2:a2.value,answer3:a3.value},
		type:"post",
		success:function(data)
		{
			data = data.replace(/\r\n/g,'');
			switch (data)
			{
				case '0':
				alert('已失败，请重试');
				break;
				case '1':
				save();
				break;
			}
		},error:function()
		{
			alert('未知错误');
		}
	});
	
	
}
 function save()//保存
 {
  var r=confirm("您已经保存了您的设置。是否跳转到个人中心")
  if (r==true)
    {
	window.location.href = "/usercenter";
    }
 }
function reg_problem()//得到焦点时触发
{
	// alert(document.activeElement.id);
	var select_id = document.activeElement.id;//得到焦点的select控件
	
	if(select_id == 'select2')
	{
		hide('select1', 'select3', 'select2');		
	}
	if(select_id == 'select3')
	{
		hide('select1','select2', 'select3');
	}
	if(select_id == 'select1')
	{
		hide('select2','select3', 'select1');
	}
}
function hide(select1,select2,select3)//隐藏option,select1为一个select控件的ID，select2为一个select控件的ID。select3为需要隐藏的ID
{
		var select1 = document.getElementById(select1);
		var select2 = document.getElementById(select2);
		var option1 = select1.getElementsByTagName('option');
		var option2 = select2.getElementsByTagName('option');
		var select3 = document.getElementById(select3);
		var option3 = select3.getElementsByTagName('option');
		
		// alert('1:'+option1.length);
		
		for (var i = 1; i <option3.length; i++)
		{			// option3[i].disabled = true;
			// option3[i].innerHTML="";
			
			if (option3[i].value == select1.value || option3[i].value == select2.value)
			{
				option3[i].style.display = "none";
				// option3[i].disabled = false;
				// option3[i].innerHTML="";
			}
		}
	}
function show_option()
{
var option = document.getElementsByTagName('option');
for(var i = 0; i < option.length; i++)
{
option[i].style.display = "block";
}
}
// document.activeElement
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
<h4>爱悠账号&nbsp;>&nbsp;<font style="color:#ec680c">设置密保问题</font></h4>
<h6>zzx11235&nbsp;&nbsp;<a href="/user/loginout">|&nbsp;&nbsp;退出</a></h6>
</div>

<div class="form" >
<table style="margin:0 auto;">
<tr><td></td><td>请选择不易忘记的问题进行回答，并牢记答案。<br/>安全问题将成为找回密码的重要途径。</td></tr>


<tr><td style="text-align:right">问题一:&nbsp;</td><td><select class="form-control" id="select1" onfocus="reg_problem()" onBlur="show_option()">
<?php
$pro = '';
if (!empty ($problem))
{
	$pro = '<option >请选择任何一项</option>';
	for ($i = 0; $i < count($problem,0); $i++)
	{
		$pro .='<option id="'.$problem[$i]['id'].'">'. $problem[$i]['problem']. '</option>';
	}
	echo $pro;
}

?>
</select></td></tr>
<tr><td style="text-align:right">答案:&nbsp;</td><td><input class="form-control" type="text" style="float:right;display:block" id="a1"/></td></tr>
<tr><td style="text-align:right">问题二:&nbsp;</td><td><select class="form-control" id="select2" onfocus="reg_problem()" onBlur="show_option()">
<?php
	echo $pro;
?>
</select></td></tr>
<tr><td style="text-align:right">答案:&nbsp;</td><td><input class="form-control" type="text" style="float:right;display:block" id="a2"/></td></tr>
<tr><td style="text-align:right">问题三:&nbsp;</td><td><select class="form-control" id="select3" onfocus="reg_problem()" onBlur="show_option()">
<?php
	echo $pro;
?>
</select></td></tr>
<tr><td style="text-align:right">答案:&nbsp;</td><td><input class="form-control" type="text" style="float:right;display:block" id="a3"/></td></tr>
<tr><td></td><td style=""><a style="width:100px;line-height:33px;background:#ec680c;display:block;vertical-align:50%;text-align:center;border-radius:5px;" href="javascript:void(0)" onclick="securityaction()"><font style="color:#ffffff">下一步</font></a></td></tr>
</table>
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