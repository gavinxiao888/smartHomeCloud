<?php
	/******************************************************************************

			参数说明:
			$max_file_size  : 上传文件大小限制, 单位BYTE
			$destination_folder : 上传文件路径
			$watermark   : 是否附加水印(1为加水印,其他为不加水印);

			使用说明:
			1. 将PHP.INI文件里面的"extension=php_gd2.dll"一行前面的;号去掉,因为我们要用到GD库;
			2. 将extension_dir =改为你的php_gd2.dll所在目录;
			******************************************************************************/
			/*
//变量$state用来标识上传的状态。
0：不存在上传文件
1：文件太大；
2：文件类型不对；
3：移动文件失败
4：正常运行
*/
/*
 * @param $path文件存放位置,$name 文件上传表单的name值，$file_size为文件上传限制大小
 */
class upimg
{
public $path_name;

function up($path, $name, $file_size)
{
	//上传文件类型列表
	$uptypes = array(
		'image/jpg',
		'image/jpeg',
		'image/png',
		'image/pjpeg',
		'image/gif',
		'image/bmp',
		'image/x-png'
	);

	$max_file_size = $file_size;     //上传文件大小限制, 单位BYTE
	$destination_folder = $path;//"../public/uploadimg/";//正确的路径

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		 if (!is_uploaded_file($_FILES[$name]["tmp_name"]))
		 {
			return 0;//表示不存在上传文件
		 }

		$file = $_FILES[$name];
		if ($max_file_size < $file["size"]) //检查文件大小
		{
			return 1;//表示文件太大了
		}

		if (!in_array($file["type"], $uptypes)) //检查文件类型
		{
			return 2;//表示文件类型不对
		}

		if (!file_exists($destination_folder)) {
			mkdir($destination_folder);
		}

		$filename = $file["tmp_name"];
		$image_size = getimagesize($filename);
		$pinfo = pathinfo($file["name"]);
		$ftype = $pinfo['extension'];
//		$destination = $destination_folder . $_SERVER['REQUEST_TIME'] . "." . $ftype;
		$destination = $destination_folder . $_SERVER['REQUEST_TIME'] . '.jpg';
		$this->path_name = $destination;
		if (!move_uploaded_file($filename, $destination)) {
			return 3;//表示文件移动过程失败
		}
		return 4;//正常运行


	}
}
}
