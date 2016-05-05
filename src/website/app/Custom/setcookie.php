<?php

if(!empty($_COOKIE["user"]))
{
	//这里要验证cookie的正确性
		$res=DB::select("select * from `admin` where name=?",array($_COOKIE["user"]["name"]));
		if(empty($res))
		{
			// echo "<script>window.location.href=/admin</script>";
			// return '1';
			return View::make('admin.login');
		
		}
		if($res[0]["key"]==$_COOKIE["user"]["key"])
		{
		
		}		
	else
	{	
		//header('Location:8000/admin');exit;			
	echo "<script>window.location.href='/admin'</script>";
	}	
}
else
{
	//header('Location:8000/admin');exit;//这样使用header会被routing接收
echo "<script>window.location.href='/admin'</script>";
		// return View::make('admin.login');
		
}
?>