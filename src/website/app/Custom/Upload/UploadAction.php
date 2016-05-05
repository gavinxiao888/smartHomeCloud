<?php
 /************************************************************
  Copyright (C), 2014-2015, Everyoo Tech. Co., Ltd.
  @FileName: UpLoadAction.php
  @Author: zzx       Version :   V.1.0.0       Date: 2015/4/23
  @Description:     上传的实现类

***********************************************************/
namespace App\Custom\Upload;

class UploadAction
{
    //上传的文件的具体类
    private $uploadclass;
    //获取上传的实际类
    public function upload(Upload $upload)
    {
        $this->uploadclass = $upload;
    }
    //上传的动作
    public function action()
    {
        //调用真正的上传方法
       return $this->uploadclass->uploadAction();
    }
}