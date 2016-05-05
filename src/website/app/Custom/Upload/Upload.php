<?php
 /************************************************************
  Copyright (C), 2014-2015, Everyoo Tech. Co., Ltd.
  @FileName: Upload.php
  @Author: zzx       Version :   V.1.0.0       Date: 2015/4/23
  @Description:     上传的抽象类，实现策略模式

***********************************************************/
namespace App\custom\Upload;

abstract class Upload{
    abstract function uploadAction();
}