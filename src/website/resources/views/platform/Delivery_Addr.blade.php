@extends('platform.Center_Layout')
@section('content')
<!--收货地址页面-->
<script src="/static/platform/platformjs/area.js" type="text/javascript"></script>
<script src="/static/platform/platformjs/location.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	showLocation();
});
</script>
<div class="" style="float:left; width:70%;padding-left:5%;">
    <!---->
    <div style="float:left;">
    <b>已有收货地址</b>
    </div>
    <div style="padding-top:15px; padding-bottom:15px;">
    <hr color="#999999"/>  
    </div>
  <!---->
  <div style=" padding:20px; border:hidden;height:300px; width:1000px; height:auto;">
    <!--收货地址列表-->
  <table width="693" cellspacing="5" style="border:groove; display:table-cell">
  <tr>
   <td width="69" height="53"><b>收货人</b></td>
   <td width="131"><b>所在地区</b></td>
   <td width="161"><b>街道地址</b></td>
   <td width="78"><b>邮编</b></td>
   <td width="157"><b>手机/电话</b></td>
   <td width="42"><b>操作</b></td>
   </tr>
   </table>
  </div>
  <!--新增收货地址-->
   <div style="float:left;">
    <b>新增收货地址</b>
    </div>
    <br />
    <div style="padding-top:15px; padding-bottom:15px;">
    <hr color="#999999" />  
    </div>
    <div style="">
        <p>新增收货地址&nbsp;电话号码、手机号选填一项，其余均为必填项</p>
        <form>
        <p>收货人姓名:<input type="text" id="name" /></p>
        <p>所在地区&nbsp;:<select id="loc_province" style="width:80px;"></select>
<select id="loc_city" style="width:100px;"></select>
<select id="loc_town" style="width:120px;"></select></p>
        <p>街道地址&nbsp;:<input type="text" id="street_num" /></p>
        <p>邮编号码&nbsp;:<input type="text" id="post_code" /></p>
        <p>电话号码&nbsp;:<input type="text" id="phone_num" /></p>
        <p>手机号码&nbsp;:<input type="text" id="cellphone_num" /></p>
        <p>设为默认&nbsp;:<input type="checkbox" id="checkbox" /></p>
        <input type="button" value="保存" formmethod="post"/>
        </form>
     </div>
</div>
<!---->
@endsection