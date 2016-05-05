<?php
//header('location:http://m.everyoo.com');die();
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/
//添加com_create_guid函数
if (!function_exists('com_create_guid')) {
	function com_create_guid()
	{
		 mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
         $charid = strtoupper(md5(uniqid(rand(), true)));
         $hyphen = chr(45);// "-"
         $uuid = chr(123)// "{"
                 .substr($charid, 0, 8).$hyphen
                 .substr($charid, 8, 4).$hyphen
                 .substr($charid,12, 4).$hyphen
                 .substr($charid,16, 4).$hyphen
                 .substr($charid,20,12)
                 .chr(125);// "}"
        return $uuid;
	}	
}


/**
 * 判断$arr中元素字符串是否有出现在$string中
 * @param  $string     $_SERVER['HTTP_USER_AGENT']
 * @param  $arr          各中浏览器$_SERVER['HTTP_USER_AGENT']中必定会包含的字符串
 * @param  $returnvalue 返回浏览器名称还是返回布尔值，true为返回浏览器名称，false为返回布尔值【默认】
 * @author           discuz3x
 * @lastmodify    2014-04-09
 */
function dstrpos($string, $arr, $returnvalue = false) {
	if(empty($string)) return false;
	foreach((array)$arr as $v) {
		if(strpos($string, $v) !== false) {
			$return = $returnvalue ? $v : true;
			return $return;
		}
	}
	return false;
}
function is_mobile()
{
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
	{
		return true;
	}
	// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset ($_SERVER['HTTP_VIA']))
	{
		// 找不到为flase,否则为true
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	}
	//下面是利用HTTP_USER_AGENT
	if ( !isset($_SERVER['HTTP_USER_AGENT']))
	{
		return false;
	}
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$is_wp = (strpos($agent, 'wp')) ? true : false;
	$is_pc = (strpos($agent, 'windows nt')) ? true : false;
	// $is_mac = (strpos($agent, 'mac os')) ? true : false;
	$is_iphone = (strpos($agent, 'iphone')) ? true : false;
	$is_android = (strpos($agent, 'android')) ? true : false;
	$is_ipad = (strpos($agent, 'ipad')) ? true : false;

	if ($is_wp)
	{
		return true;
	}
	if($is_pc){
		return  false;
	}

	// if($is_mac){
		// return  true;
	// }

	if($is_iphone){
		return  true;
	}

	if($is_android){
		return  true;
	}

	if($is_ipad){
		return  true;
	}
	return false;
}
if (is_mobile()){
	if (isset($_SERVER['HTTP_HOST'])){
		if ($_SERVER['HTTP_HOST'] == 'www.everyoo.com' || $_SERVER['HTTP_HOST'] == 'everyoo.com') {
			header('location:http://m.everyoo.com');
			die();
		}
	}
}
//如果访问域名是www.itelife.com则
if (isset($_SERVER['HTTP_HOST'])) {
	if ($_SERVER['HTTP_HOST'] == 'www.itelife.com' || $_SERVER['HTTP_HOST'] == 'itelife.com') {
		header("Location:http://www.everyoo.com");
		die();
	}
}
require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can simply call the run method,
| which will execute the request and send the response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

$response = $kernel->handle(
	$request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);







