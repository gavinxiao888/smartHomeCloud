@extends('platform.personalcenter.default')
	@section('content')
	<script type="text/javascript">
		$("#selectdevice").on("click",function(event){//搜索设备
			//event.preventDefault();
			var code = $('#select').val();
			$.ajax({
				type: "get",
				//url: "http://b2b.everyoo.com/personalcenter/deviceinfo",
				url: 'http://192.168.100.165:86/personalcenter/deviceinfo',
				data:"code=" + code,  
				dataType:"json",
				success: function(data){
					alert('aa');
					//alert(data);
					//data = data.replace(/\r\n/g,'');
					if(!empty(data)){
						$('#devicename').text(data[0].deviceinfo.name);
						$('#devicecode').text(data[0].deviceinfo.device_code);
						$('#devicesummary').text(data[0].deviceinfo.summary);
						$('#devicecontent').val(data[0].deviceinfo.content);
						$('#deviceprice').val(data[0].deviceinfo.price);
						$('#deviceimg').text(data[0].deviceinfo.img_url);
						$('#adddevice').append('<a href="" id="deviceadd">添加设备</a>');
					}
					else{
						alert("设备没找到");
					}
				},
				error:function (){                
					alert("请求失败,请重试");            
				}
			});
		});
		
		$("#deviceadd").on("click",function(event){
			event.preventDefault();//使a自带的方法失效
			var devicename = $('#devicename').text();
			var devicecode = $('#devicecode').text();
			var devicesummary = $('#devicesummary').text();
			var deviceimg = $('#deviceimg').text();
			var devicecontent = $('#devicecontent').val();
			var deviceprice = $('#deviceprice').val();
			$.ajax({
				type: "post",
				url: "/personalcenter/deviceinfo",
				data: {
					devicename:devicename,
					devicecode:devicecode,
					devicesummary:devicesummary,
					deviceimg:deviceimg,
					devicecontent:devicecontent,
					deviceprice:deviceprice
				},
				success: function(data){
					if(data=="1"){
						alert("添加设备成功");
						window.location.href = "/personalcenter/device";
					}
					else{
						alert("添加设备出错");
					}
				},
				error:function (){                
					alert("请求失败,请重试");            
				}
			});
		});
	</script>
	<!--位置导航-->
	<div class="position">
		<h5><a>首页</a>&nbsp;/&nbsp;<a>个人中心</a></h5>
	</div>
	<!--位置导航结束-->

	<!--左侧导航开始-->
	<div class="navi-left">
		<ul>
			<li style=""><a class="line" href="">个人中心</a> </li>

			<li  class=""><a href="/personalcenter/personalcenter" >个人中心</a></li>
			<li class="hit"><a href="/personalcenter/device" >我的设备</a></li>

		   
		</ul>    
		<ul>
			<li class="line" style=""><a href="/customservice/center">售后服务</a></li>
			<li><a href="/customservice/repair" >维修</a></li>
			<li><a href="/customservice/return" >退换货</a></li>

			
		</ul>
		<ul>
			<li class="line" style=""><a href="">账号管理</a> </li>

			<li><a href="/accountmanagement/returnaddressmanagement" >收货地址管理</a></li>
			<li><a href="/user/password/edit" >修改密码</a></li>
		</ul>	
	</div>
	<!--左侧导航结束-->
	<!--news开始-->
	<div class="news">
		<!--概览-->
		<div class="newsdiv1">
			我的设备<div style="float:right;font-size:14px;"><a href="/personalcenter/device">已有设备</a>｜<a style="color:#EF6909;" href="/personalcenter/viewdevice">添加设备</a></div>
		</div>
		<div class="newsdiv2">
			<input class="selectinput" type="text" placeholder="设备搜索" name="select" id="select" />
			<a href="" class="selecta" id="selectdevice"></a>
		</div>
		<div class="newslist">
		<form method="post" id="form1" action="/personalcenter/adddevice" enctype="multipart/form-data">
			<table cellspacing="5" class="newslisttable">
				<tr>
					<th><b>名称</b></th>
					<th><b>串码</b></th>
					<th><b>简介</b></th>
					<th><b>图片</b></th>
					<th><b>操作</b></th>
				</tr>
				<tr>
					<td id="devicename"></td>
					<td id="devicecode"></td>
					<td id="devicesummary"></td>
					<td id="deviceimg"></td>
					<td id="adddevice"></td>
				</tr>
			</table>
			<input type="text" style="display:none;" id="devicecontent" name="devicecontent" />
			<input type="text" style="display:none;" id="deviceprice" name="deviceprice" />
		</form>
		</div>
	</div>
	<!--news结束--->
	
	@stop
@stop