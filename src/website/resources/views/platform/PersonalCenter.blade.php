@extends('platform.Center_Layout')
@section('content')
<!--设备管理开始-->
<div class="" style="float:left; width:80%; color:#999; margin-left:0px; padding-left:5%;">
  <div class="" style="">
    <!--条目-->
    <div style="float:left; ">
    <b>我的设备</b>
    </div>
    <div style="float:right;width:30%;">
    <b><a href="">已有设备</a>|<a href="">添加设备</a> </b>    
    </div>
  </div>
  <div style="float:left; overflow:hidden;">
  <hr class="lang">
  </div>
  <!--搜索-->
  <div class="" style="width:80%; float:; margin-bottom:30px; border:thick; border-color:#3FC;border-width:1px; height:70px; padding-bottom:30px;">
    <div class="input-group" style="">
	<input class="form-control" type="email" placeholder="设备搜索">   
     <div class="input-group-addon"><a><img src="/static/platform/platformimages/sibar.png" /></a>
     </div>
    </div>
  </div>
  <br>
  <!--图片细则-->
  
  <div style=" padding:20px; border:hidden;height:300px; width:1000px; height:auto;">
    <!--单个图片-->
  <table>
  <tr>
  <td>
  <div style="padding-top:15px; border:groove; height:250px; width:185px;" onMouseOver="bright(this)">
      <div style="margin:5px;border-color:#666; border-width:px; padding:5px; width:170px; height:120px;">
      <img style="width:150px; height:110px;"src="eqpexm.jpg">
      </div>
      <dr>
      <dr>
      <div style="padding-left:0px; margin-left:-35px;">
        <ul>
           <li>name</li>
           <li>fullname</li>
           <li><button><img src="sibar.png"></button></li>
        </ul>
      </div>
   </div>
   </td>
   <td>
    <div style="padding-top:15px; border:groove; height:250px; width:185px;" onMouseOver="bright(this)">
      <div style="margin:5px; border-color:#666; border-width:px; padding:5px; width:170px; height:120px;">
      <img style="width:150px; height:110px;" src="eqpexm.jpg">
      </div>
      <dr>
      <dr>
      <div style="padding-left:0px; margin-left:-35px;">
        <ul>
           <li>name</li>
           <li>fullname</li>
           <li><button><img src="sibar.png"></button></li>
        </ul>
      </div>
   </div>
   </td>
   </tr>
   </table>
</div>
<!--设备管理结束-->
@endsection