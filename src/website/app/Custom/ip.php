<?php

$file = "../public/ips.txt";//保存的文件名
$file=realpath($file);


$ip = $_SERVER['REMOTE_ADDR'];
$time=date("Y-m-d H:i:s");
$handle = fopen($file, 'a+');
fwrite($handle, "\r\n");
fwrite($handle, "IP Address: ");
fwrite($handle, "$ip ");

fwrite($handle, "Time: ");
fwrite($handle, "$time ");

fwrite($handle,	"Url ");
if(!empty($_SERVER["QUERY_STRING"]))
{
fwrite($handle,	'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER["QUERY_STRING"]);
}
else
{
fwrite($handle,	'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] );
}

fclose($handle);
?>