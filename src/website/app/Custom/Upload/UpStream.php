<?php
 /************************************************************
  Copyright (C), 2014-2015, Everyoo Tech. Co., Ltd.
  @FileName: UpStream.php
  @Author: zzx       Version :   V.1.0.0       Date: 2015/4/23
  @Description:     接受二进制的上传文件

***********************************************************/
namespace App\Custom\Upload;

class UpStream extends Upload
{
    //上传文件的路径
    private $path;
    //上传文件的限制大小
    private $fileSize;
	/*
    //上传图片的类型限制
    private $imageType = ['image/jpg','image/jpeg','image/png','image/pjpeg','image/gif','image/bmp','image/x-png'];
    //上传视频的类型限制
    private $videoType = ['video/mp4', 'video/3gp', 'video/avi'];
    //上传一般文件的类型限制
    private $objectType = ['application/x-msdownload', 'text/xml'];
	*/
    //在判断是上传文件是否合法时使用的变量
    private $fileType = [
	'imageType' => ['image/jpg','image/jpeg','image/png','image/pjpeg','image/gif','image/bmp','image/x-png'],
	'videoType' => ['video/mp4', 'video/3gp', 'video/avi'],
	'objectType' => ['application/x-msdownload', 'text/xml']	
	];
    /*
     * @param $path是上传路径
     * @param $fileSize是上传文件大小
     * @param $fileType 是上传文件类型
     */
    public function setParam($path,  $fileSize,  $fileType)
    {
        $this->path = $path;
        $this->fileSize = $fileSize;
        //根据$fileType决定$this->fileTpe的值
        $this->fileType = $this->fileType[$fileType];
    }
    /*
      * @power 实际的上传方法
      * @return 2: 文件类型不符
      * @return 3：文件太大
      * @return 0： 上传失败
      * @return 上传文件的路径
      */
    public function uploadAction()
    {
        //如果该值是空字符串的话
        if ($_SERVER['HTTP_X_FILE_TYPE'] == ''){
			if ($_SERVER['CONTENT_TYPE' ] != '') {
				if (!in_array($_SERVER['CONTENT_TYPE' ], $this->fileType)){
					return 2;
				}
			}
            //暂不做处理
        }else{
            if (!in_array($_SERVER['HTTP_X_FILE_TYPE'], $this->fileType)) {
                return 2;          
            }
        }
        //得到二进制参数
        $data = file_get_contents("php://input");
        //判断文件长度
        if($this->fileSize < strlen($data)){
            return 3;           
        } else{
            //IO操作
            try{
                //扩展名
                $fileExtension = self::getExtension();
                //生成的文件名字
                $fileName = time() . $fileExtension;
                //打开文件准备写入。这里的路由是相对与该php文件的路径
                $file = fopen($this->path . $fileName,"w");
                fwrite($file, $data);//写入
                fclose($file);//关闭
                return substr($this->path . $fileName, 9, strlen($this->path . $fileName));
                
            }catch (Exception $e){
				Log::error('{错误事件:上传文件写入出错 错误代码:'.__FILE__.__LINE__.'}');
                return 0;             
               
            }
        }
    }
    /*
     * @power 获取上传文件的扩展名
     * @return 扩展名
     */
    private function getExtension()
    {
        //$_SERVER['HTTP_X_FILE_NAME']
        if (isset($_SERVER['HTTP_X_FILE_NAME'])){
            return preg_replace('/.+?(?=\.)/i', '' ,$_SERVER['HTTP_X_FILE_NAME']);
        }
        return '';
    }

}