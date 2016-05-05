<?php
namespace App\Facade;
/*
@creare 2015/08/24
@author zzx
@power 封装对于CUrl的操作
*/

class CUrl
{
	//先初始化一个url属性
	private $url = '';
	//初始化一个httpstatus。大概是用不到了，因为这个类可能在一个PHP周期中多次调用来验证不同的地址
	private $status = 0;
	//初始化返货body信息。大概是用不到了，因为这个类可能在一个PHP周期中多次调用来验证不同的地址
	private $content = '';

	//构造函数没有什么作用啊
	public function __construct()
	{

	}

	/*
	 * @power 发起请求
	 * @todo 这个函数需要进一步扩展 e.g.请求方式；请求header(cookie,param,referer...)
	 */
	private function sendRequest($url, $header = [])
	{
		if (!self::valiUrl($url)) {
			return [];
		}
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 
		$char = curl_exec($ch);
		$info = curl_getinfo($ch);
		 
		curl_close($ch);

		return ['status' => $info['http_code'], 'type' => $info['content_type'], 'content' => $char, 'size' => $info['header_size']];
	} 

	/**
	 * @power 验证url地址
	 * @todo 这个正则有点问题
	 */
	private function valiUrl($url)
	{
		return preg_match('/[a-zA-z]+:\/\/[^\s]*/', $url);
	}

	/**
	 * @power 获取http[s]的地址的response status
	 */
	public function getStatus($url)
	{
		$response = self::sendRequest($url);

		return empty($response) ? '' : $response['status'];
	}

	/**
	　* @power 获取http[s]的response content
	 */
	public function getContent($url) 
	{
		$response = self::sendRequest($url);

		return empty($response) ? '' : $response['content'];
	}

	/**
	 * @power 获取http[s]的response type
	 */
	public function getType($url)
	{
		$response = self::sendRequest($url);

		return empty($response) ? '' : $response['type'];
	}

	/**
	 * @power 返回全部参数
	 * @todo 该函数也需要修改
	 */
	public function getAll($url)
	{
		return self::sendRequest($url);
	}
}
?>