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
namespace App\Custom\Upload;

class UpFile extends Upload
{
    private $pathName;
    private $path;
    private $name;
    private $fileSize;
    //上传文件类型列表
    private $upFileTypes = array(
    'application/x-zip-compressed',
    'application/octet-stream',
    'application/zip',
    'text/plain',
    'application/msword',
    'application/vnd.ms-execl',
    'application/vnd.ms-powerpoint',
    'application/pdf',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/xhtml+xml'
    );
    //上传文件类型列表
    private $upVideoType = array(
    'application/octet-stream',
    'application/x-shockwave',
    'video/x-flv',
    'video/mp4',
    'video/avi',
    'video/rmvb',
    'video/rm',
    'video/3gp',
    );
    //上传文件类型列表
    private $upImagesType = array(
    'image/jpg',
    'image/jpeg',
    'image/png',
    'image/pjpeg',
    'image/gif',
    'image/bmp',
    'image/x-png'
    );
    //判断文件类型用的array
    private $fileType = [];
    /*
   * @param $path是上传路径
   * @param $name是上传表单的NAME属性
   * @param $fileSize是上传文件大小
   * @param $fileType 是上传文件类型
   */
    public function setParam($path, $name, int $fileSize = null, string $fileType)
    {
        $this->path = $path;
        $this->name = $name;
        //设置默认上传大小
        $this->fileSize = isset($fileSize) ? $fileSize : 1048576;
        //根据$fileType决定$this->fileTpe的值
        $this->fileType = $this->$fileType;
    }
    /*
     * @power 实际的上传方法
     * @return 1：文件太大
     * @return 2: 文件类型错误
     * @return 3: 移动文件失败
     * @return 4: 成功
     * @return 0： 不存在上传文件
     */
    public function uploadAction()
    {
        //上传文件大小限制, 单位BYTE
        $maxFileSize=$this->fileSize;
        //上传路径，例如"../public/uploadfile/";
        $fileDestinationFolder=$this->path;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //是否存在文件
            if (!empty($_FILES[$this->name]["tmp_name"])) {
                //得到文件句柄
                $file = $_FILES[$this->name];
                //检查文件大小
                if($maxFileSize < $file["size"]) {
                    return 1;
                }
                //检查文件类型
                if(!in_array($file["type"], $this->fileType)) {
                    return 2;
                }
                //检查上传
                if(!file_exists($fileDestinationFolder))
                {
                    mkdir($fileDestinationFolder);
                }
                //文件句柄
                $filename = $file["tmp_name"];
                //得到文件路径信息
                $pinfo = pathinfo($file["name"]);
                //获取文件扩展
                $ftype = $pinfo['extension'];
                //获取文件路径
                $fileDestination = $fileDestinationFolder.time().".".$ftype;
                //赋值给pathname
                $this->pathName = substr($fileDestination, 9, strlen($fileDestination)-9);
                //移动文件
                if(!move_uploaded_file($filename, $fileDestination)) {
                    return 3;
                }
                return 4;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
}