<?php
/*
//变量$state用来标识上传的状态。
0:不存在文件
1：文件太大；
2：文件类型不对；
3：移动文件失败
4：正常运行
@param $name提交表单的名字，$filesize上传文件的限制大小
*/
class upfile
{
		public $path_name;
		public function up($path, $name, $filesize)
		{

			//上传文件类型列表
			$up_file_types=array(
			'application/x-zip-compressed',
			'application/octet-stream',
			'application/zip',
			'text/plain',
			'application/msword',
			'application/vnd.ms-execl',
			'application/vnd.ms-powerpoint',
			'application/pdf',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/xhtml+xml',
				
			);
			

			$max_file_size=$filesize;     //上传文件大小限制, 单位BYTE
		
			$file_destination_folder=$path;//"../public/uploadfile/";
		
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				if (!empty($_FILES[$name]["tmp_name"]))
				//是否存在文件
				{
					$file = $_FILES[$name];
					if($max_file_size < $file["size"])
					//检查文件大小
					{
						return 1;			
					}
					if(!in_array($file["type"], $up_file_types))
					//检查文件类型
					{
						return 2;
					}
					if(!file_exists($file_destination_folder))
					{
						mkdir('../'.$file_destination_folder);
					}
					$filename=$file["tmp_name"];
					$pinfo=pathinfo($file["name"]);
					$ftype=$pinfo['extension'];
					// $file_destination =substr($file_destination_folder,2,strlen($file_destination_folder)).time().".".$ftype;
					$file_destination =$file_destination_folder.time().".".$ftype;
					$this->path_name=$file_destination;

					if(!move_uploaded_file($filename, $file_destination))		
					{
						return 3;
					}						
					return 4;
				}
				else
				{
					return 0;
				}
			
			}
		}
}