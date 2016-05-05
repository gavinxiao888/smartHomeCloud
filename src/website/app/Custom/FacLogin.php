<?php
/*
*实现登陆事件的整合，尝试使用工厂方法来写OOP
*@author zzx
*@date 2014-12-30
*/
/*
*ALogin是一个能被重写的类
*该有一个能被重写的方法
*/
abstract class ALogin 
{
	public abstract function LoginTF($data=array());
}
/*
*Admin是后台登陆类
*要实现父类的方法
*/
class AdminLogin extends ALogin
{
	protected $key;
	
	public function __construct()
	{
		$this->key="25";
	}
	public function LoginTF($data=array())
	{
		echo $this->key;
		var_dump($data);
		return 'Admin';
	}
}
/*
*接口类
*继承接口的类，可以有多个。
*每一个都要实现createOperation()方法，该方法用来return一个类
*/
 interface IFactory
{
	public function createOperation();
}
/*
*继承接口的类，可以有多个
*return 一个继承ALogin的类
*/
 class AdminFactory  implements IFactory 
{
	public function createOperation()
	{
		return new AdminLogin();
	}
}

?>