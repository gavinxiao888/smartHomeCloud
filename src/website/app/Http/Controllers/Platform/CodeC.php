<?php
/*
 * @name验证码的C类
 * @power 验证验证码的正确性
 */
namespace App\Http\Controllers\Platform;
class CodeC extends \App\Http\Controllers\Controller
{
	/*
	*@return {0:不存在验证码(验证码过期);1:正确;2:验证码不匹配}
	*/
	public function platform_reg_code()//验证云平台登陆时候的代码
	{
		session_start();
		if (!isset($_SESSION["platformcode"]))
		{
			return 0;
			die();
		}
		if (intval($_POST["code"]) == $_SESSION["platformcode"])
		{
			return 1;
			die();
		}
		else
		{
			return 2;
			die();
		}
	}
	public function platformcode() //获取随机数
	{
		header("Content-Type:image/png");
		// ini_set('session.save_handler','files');
		  // ini_set("session.save_path", app_path().'/storage/sessions/');
		// ini_set("session.save_path", "../app/storage/sessions/");
		//����session
		@ini_set('session.gc_maxlifetime',200);
		@session_start();
		
		//���4������
		$code = "";
		$arr = array();
		for($i=0;$i<4;$i++){
			
			$arr[$i] = rand(0,9);
			$code .= (string)$arr[$i];
		}
		
		//������session�У�����ȶ�
		$_SESSION["platformcode"] = $code;
		
		//��ʼ��ͼ
		$width = 100;
		$height = 25;
		$img = imagecreatetruecolor($width,$height);
		
		//��䱳��ɫ
		$backcolor = imagecolorallocate($img,201,201,202);
		imagefill($img,0,0,$backcolor);
		
		//��ȡ��������ɫ
		for($i=0;$i<4;$i++){
			
			$textcolor = imagecolorallocate($img,rand(50,180),rand(50,180),rand(50,180)); 
			imagechar($img,12,7+$i*25,3,(string)$arr[$i],$textcolor);
		}
		  
		//��ʾͼƬ
		imagepng($img);
		// imagepng($img,"../public/images/circle.png");
		
		//���ͼƬ
		imagedestroy($img);
	}
}
?>