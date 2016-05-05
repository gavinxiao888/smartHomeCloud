
<!html>
<head>
<style>
h1{
text-align:center;
}
</style>
</head>
<body>
<h1>找回密码</h1>
<h3>本邮件由山东智慧生活数据系统有限公司系统发出，请勿回复。</h3>
<h3>您的密码已经重置为：
<?php
	if(!empty($key)){
		echo $key;
	}
	echo __FILE__.__LINE__.time();
?>
。</h3><h3>请登录网站进行修改。</h3>
<a href=>www.everyoo.com</a>


</body>
</html>