<?php

if(!empty($_SERVER['QUERY_STRING']))//取得id参数
{
	// $id=substr($_SERVER['QUERY_STRING'],-(strlen($_SERVER['QUERY_STRING'])-3));

	if(!empty($_GET["id"]))
	{
		$id=$_GET["id"];
		$pv=DB::SELECT('select pv from news where id=?',array($id));
		$time=date("Y-m-d H:i:s");
		if($pv[0]["pv"]==null)
		{
		DB::UPDATE('update news set pv=?,pvdate=? where id=?',array($pv[0]["pv"]+1,$time,$id));
		}
		else
		{
			DB::UPDATE('update news set pv=?,pvdate=? where id=?',array(1,$time,$id));
		}
	}
	else
	{
		//echo '没有id信息。在pv.php中';
	}
}


?>