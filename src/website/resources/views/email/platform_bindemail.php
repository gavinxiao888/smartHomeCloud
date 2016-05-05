<!html>
<head>
<style>
h1{
    text-align:center;
}
</style>
</head>
<body>
<h1>邮箱验证码</h1>
<h3>本邮件由山东智慧生活数据系统有限公司系统发出，请勿回复。您申请的邮箱验证的验证码为<font style="color:green;font-size:20px;">
        <?php if(!empty($key)) echo $key;?></font>。</h3>



</body>
</html>