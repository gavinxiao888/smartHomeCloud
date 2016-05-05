<?php
/*
//变量$state用来标识上传的状态。
1：文件太大；
2：文件类型不对；
3：移动文件失败
4：正常运行
*/
class upvideo
{
public $name;
function up()
{
			//上传视频文件类型列表
			//这个地方的类型还要在调整
			$up_video_types=array(		
			'application/octet-stream',
			'application/x-shockwave',
			'video/x-flv',
			'video/mp4',
			'video/avi',
			'video/rmvb',
			'video/rm',
			'video/3gp',
			);			
			$max_video_size= 220000000;//上传文件大小限制, 单位BYTE
			
			$video_destination_folder="../public/uploadvideo/";//上传文件的路径

		
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				if (!empty($_FILES["video"]["tmp_name"]))
				//是否存在视频文件
				{
					$file = $_FILES["video"];//取得句柄（类似这样的作用）
					$videoname=$file["tmp_name"];
					$pinfo=pathinfo($file["name"]);
					$ftype=$pinfo['extension'];	
					
					
					if($max_video_size < $file["size"])
					//检查文件大小
					{					
						return 1;//$state=1表示文件太大						
					}
					if(!in_array($file["type"], $up_video_types))
					//检查文件类型
					{	
					
						return 2;//表示文件类型不对
					
					}
					if(!file_exists($video_destination_folder))
					{
						mkdir($video_destination_folder);						
					}
		
					$video_destination = $video_destination_folder.time().".".$ftype;
					// global $name;
					$this->name=$video_destination;
					if(!move_uploaded_file($videoname, $video_destination))		
					{							
						return 3;//表示文件移动过程失败				
					}	
				
					return 4;//正常运行
				}				
			}
}

}