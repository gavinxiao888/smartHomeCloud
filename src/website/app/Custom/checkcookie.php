<?php 
//检查cookie有没有被禁用
	setcookie("CookieCheck","OK",time()+3600,"/");
                
	if (!isset($_COOKIE["CookieCheck"]))
	{
			echo "您浏览器的 cookie 功能被禁用，请启用此功能。";                
	}
	else 
	{
			return 1;
	}    
?>