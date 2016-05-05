@extends('platform.Center_Layout')
@section('content')
<!--news开始-->
<div class="news">
<!--概览-->
<div class="overview">
<div id="left">
<img src="/static/platform/platformimages/head.png"></div>
<div id="right">
<h5>zzx11235，早上好！</h5>
<p>账号安全级别&nbsp<img src="/static/platform/platformimages/safe1.png"/>&nbsp;|&nbsp;<img src="/static/platform/platformimages/bind.png">绑定手机&nbsp;|&nbsp;<img src="/static/platform/platformimages/nobind.png">绑定邮箱&nbsp;|&nbsp;如何提交安全级别？&nbsp;</p>
</div>
</div>
<!--概览结束-->
<!--未支付订单-->
<div class="nopaymentorder">
<h5>&nbsp;&nbsp;&nbsp;未支付订单</h5>
<p style="padding-top: 24px;padding-bottom: 24px;text-align:center">您暂时没有未支付订单，<a>挑挑喜欢的商品去</a></p>
</div>
<!--未支付订单结束-->
<!--未收货订单-->
<div class="notreceivedorders">
<h5>&nbsp;&nbsp;&nbsp;未收货订单</h5>
<p style="padding-top: 24px;padding-bottom: 24px;text-align:center">您暂时没有未支付订单，<a>挑挑喜欢的商品去</a></p>
</div>
<!--未收货订单结束-->
<!--购物车-->
<div class="shoppingcart">
<h5>&nbsp;&nbsp;&nbsp;购物车</h5>
<p style="padding-top: 24px;padding-bottom: 24px;text-align:center">您的购物车是空的呦，<a>挑挑喜欢的商品去</a></p>
</div>
<!--购物车结束-->
<!--收藏夹-->
<div class="favorite">
<h5>&nbsp;&nbsp;&nbsp;收藏夹</h5>
<p style="padding-top: 24px;padding-bottom: 24px;text-align:center">您的购物车是空的呦，<a>挑挑喜欢的商品去</a></p>
</div>
<!--收藏夹结束-->
</div>
<!--news结束--->
@endsection