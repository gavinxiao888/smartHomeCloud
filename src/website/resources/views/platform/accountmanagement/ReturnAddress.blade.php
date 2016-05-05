@extends('platform.personalcenter.Default')
	@section('content')
	<!--收货地址页面-->
	<script src="/static/platform/platformjs/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		function codevali()//验证手机号码，返回状态
		{
			var code=document.getElementById("code");
			var reg = /^[0-9]*$/;
			if(!reg.test(code.value)){
				if(code.value==''){
					
				}
				else{
					alert('邮编输入不正确');
					code.focus();
				}
			}
		}
		function phoneone()//验证手机号码，返回状态
		{
			var code1=document.getElementById("phone1");
			var reg1 = /^[0-9]*$/;
			if(!reg1.test(code1.value)){
				if(code1.value==''){
					
				}
				else{
					alert('电话区号输入不正确');
					code1.focus();
				}
			}
		}
		function phonetwo()//验证手机号码，返回状态
		{
			var code2=document.getElementById("phone2");
			var reg2 = /^[0-9]*$/;
			if(!reg2.test(code2.value)){
				if(code2.value==''){
					
				}
				else{
					alert('坐机输入不正确');
					code2.focus();
				}
			}
		}
		function mobilephonevali()//验证手机号码，返回状态
		{
			var code3=document.getElementById("mobilephone");
			var reg3 = /^[0-9]*$/;
			if(!reg3.test(code3.value)){
				if(code3.value==''){
					
				}
				else{
					alert('手机输入不正确');
					code3.focus();
				}
			}
		}
		$().ready(function(){ 
			//当$subject不为空，表示加载修改状态的表单，此时就不能在页面加载时立即调用getVal()函数，以避免无法显示要修改的账目所在的收支分类 
			<?php if( empty($subject) ){ ?>//当$subject为空，表示加载添加表单，此时要在页面加载时立即调用getVal()函数，以显示当前收支类型的子分类 
			getsubject(); 
			<?php } ?> 
			<?php if( empty($unit) ){ ?>
			getunit(); 
			<?php } ?> 
			$("#department").change(function(){ 
				getsubject(); getunit(); 
			}); 
			$("#subject").change(function(){ 
				getunit(); getunit(); 
			});
		}); 

		function getsubject(){ 
			$.getJSON("/accountmanagement/getcity",{department:$("#department").val()},function(json){ 
				var subject = $("#subject"); var unit = $("#unit"); 
				$("option",subject).remove();$("option",unit).remove(); //清空原有的选项，也可使用 subject.empty(); 
				var option = "<option value='-1' select='selected' style='font-family:Microsoft YaHei !important;'>选择市</option>";
				subject.append(option);
				unit.append(option);
				$.each(json,function(index,array){ 
					option = "<option id='"+array['ds_id']+"' value='"+array['ds_id']+"' name='subject'>"+array['ds_name']+"</option>"; 
					subject.append(option); 
				}); 
			}); 
		};
		function getunit(){ 
			$.getJSON("/accountmanagement/gettown",{subject:$("#subject").val()},function(json){ 
				var unit = $("#unit"); 
				$("option",unit).remove(); //清空原有的选项，也可使用 unit.empty(); 
				var option = "<option value='-1' select='selected'>选择区</option>";
				unit.append(option);
				$.each(json,function(index,array){ 
					var option = "<option id='"+array['ds_id']+"' value='"+array['ds_id']+"' name='unit' style='font-family:Microsoft YaHei !important;'>"+array['ds_name']+"</option>"; 
					unit.append(option); 
				}); 
			}); 
		};
		//下面是页面加载时自动执行一次getVal()函数 
		$().ready(function(){ 
			getsubject(); getunit(); 
			$("#department").change(function(){//省份部分有变动时，执行getVal()函数 
				getsubject(); getunit(); 
			}); 
			$("#subject").change(function(){ 
				getunit(); getunit(); 
			});
		});
		// $('#addressid').click(function (){ //设置默认收货地址
			// var addressid = $('#addressid').val();
			// alert(addressid);
		// });
		// function setreturn(){
			// var addressid = $('#addressid').val();
			// alert(addressid);
			// ///accountmanagement/defaultreturnaddress
		// }
	</script>
	<!--位置导航-->
	<div class="position">
		<h5><a>首页</a>&nbsp;/&nbsp;<a>账号管理</a></h5>
	</div>
	<!--位置导航结束-->

	<!--左侧导航开始-->
	<div class="navi-left">
		<ul>
			<li style=""><a  class="line" href="">个人中心</a> </li>

			<li class=""><a href="/personalcenter/personalcenter" >个人中心</a></li>
			<li class=""><a href="/personalcenter/device" >我的设备</a></li>

		   
		</ul>    
		<ul>
			<li class="line" style=""><a href="/customservice/center">售后服务</a></li>
			<li class=""><a href="/customservice/repair" >维修</a></li>
			<li><a href="/customservice/return" >退换货</a></li>

			
		</ul>
		<ul>
			<li class="line" style=""><a href="">账号管理</a> </li>

			<li class="hit"><a href="/accountmanagement/returnaddressmanagement" >收货地址管理</a></li>
			<li><a href="/user/password/edit" >修改密码</a></li>
		</ul>	
	</div>
	<!--左侧导航结束-->

	<div class="news" style="font-size:14px;">
		<!---->
		<div class="newsdiv1">
			<b>已有收货地址</b>
		</div>
		<!---->
		<div class="newslist">
			<!--收货地址列表-->
			<table cellspacing="5" class="newslisttable" style="font-size:13px;">
				<tr style="color:#ec680c;">
					<th><b>收货人</b></th>
					<th><b>所在地区</b></th>
					<th><b>街道地址</b></th>
					<th><b>邮编</b></th>
					<th><b>手机/电话</b></th>
					<th><b>操作</b></th>
				</tr>
				<?php
					//输出table信息
					if(count($address,0)>0){
						for($i=0;$i<count($address,0);$i++){
							echo '<tr>';
							$province=$address[$i]["province"];if(mb_strlen($province)>4){$province = mb_substr($province,0,4,"UTF8")."..";}
							$city=$address[$i]["city"];if(mb_strlen($city)>4){$city = mb_substr($city,0,4,"UTF8")."..";}
							$area=$address[$i]["area"];if(mb_strlen($area)>4){$area = mb_substr($area,0,4,"UTF8")."..";}
							echo '<td class="">'.$address[$i]['consignee_name'];
								if($address[$i]['type']==1){
									echo '<div style="color:red;"/>(默认)</div>';
								}
							echo '</td>';
							echo '<td class="">'.$province.' '.$city.' '.$area.'</td>';
							echo '<td class="">'.$address[$i]['address'].'</td>';
							echo '<td class="">'.$address[$i]['code'].'</td>';
							echo '<td class="">'.$address[$i]['mobilephone'].'/'.$address[$i]['phone'].'</td>';
							echo '<td class=""><a href="/accountmanagement/defaultreturnaddress?addressid='.$address[$i]["id"].'" style="color:#3366ff;"/>设置</a>';
							echo '|<a href="/accountmanagement/delreturnaddress?addressid='.$address[$i]["id"].'" style="color:#3366ff;">删除</a></td>'; 
							echo '</tr>';
						}                                                                            
					}
                ?>
			</table>
		</div>
		<!--新增收货地址-->
		<div class="newsdiv1">
			<b>新增收货地址</b>
		</div>
		<div style="margin-left:10px;padding-top:50px;">
			<p><div class="returndivinputdiv" style="font-size:13px;"><a href="" style="color:#3366ff;">新增收货地址</a></div><div style="font-size:13px;">电话号码、手机号选填一项，其余均为必填项</div></p>
			<form method="post" id="form1" action="/accountmanagement/addreturnaddress" enctype="multipart/form-data">
				<div class="returndivinput"><div class="returndivinputdiv">收货人姓名:</div><input type="text" id="name" name="name" /></div>
				<div class="returndivinput"><div class="returndivinputdiv">所在地区:</div><select id="department" name="department" style="width:120px;"><option value="-1" select="selected">选择省</option>
					<?php 
						if(!empty($department)){
							for($i=0;$i<count($department,0);$i++){
								echo '<option id="'.$department[$i]["id"].'" value="'.$department[$i]["id"].'" style="font-family:Microsoft YaHei !important;">'.$department[$i]["areaname"].'</option>';
							}
						}
					?>
					</select>
					<select name="subject" id="subject" style="width:120px;">
						<option value="" name="subject"></option>
					</select>
					<select name="unit" id="unit" style="width:120px;">
						<option value="" name="unit"></option>
					</select>
				</div>
				<div class="returndivinput"><div class="returndivinputdiv">街道地址:</div><input type="text" id="street" name="street" style="width:368px;"/></div>
				<div class="returndivinput"><div class="returndivinputdiv">邮编号码:</div><input type="text" id="code" name="code" onBlur="codevali()"/></div>
				<div class="returndivinput"><div class="returndivinputdiv">电话号码:</div><input type="text" id="phone1" name="phone1" style="width:60px;" onBlur="phoneone()"/> - <input type="text" id="phone2" name="phone2" style="width:99px;" onBlur="phonetwo()"/></div>
				<div class="returndivinput"><div class="returndivinputdiv">手机号码:</div><input type="text" id="mobilephone" name ="mobilephone" onBlur="mobilephonevali()"/></div>
				<div class="returndivinput"><div class="returndivinputdiv">设为默认:</div><input type="checkbox" id="selectdefault" name="selectdefault" value="1" /></div>
				<div class="returndivinput"><div class="returndivinputdiv">&nbsp;</div>
					<input type="submit" value="保存" class="btn btn-warning" style="float:left;display:block;width:80px;height:28px;background:#ec680c;color:#ffffff;border-radius:3px;border:none;font-family:Microsoft YaHei !important;"/>
				</div>
			</form>
		 </div>
	</div>
	<!---->
	@stop
@stop











