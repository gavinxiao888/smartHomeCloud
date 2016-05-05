<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/2/3
 * Time: 18:35
 * @power操作image的工具类
 */
class Img_handle
{
    /*
     * @power压缩图片的内存到一定大小
     * @param $path为原地址;$target_path为目标地址;$filesize为被压缩大小;$lv为压缩等级
     * @idea 递归
     * @return 没有找到图片时返回1，处理成功返回1，处理失败返回0
     */
    public function img_compress($path, $targer_path, $filesize, $lv = 90)
    {

            try {
                $img = Image::make($path);
            }
            catch ( Exception $e)
            {
                Log::warning('{人为错误：在图片缩小时；代码行'.__FILE__.__FILE__.';时间:',date('y-m-d h-i-s', $_SERVER['REQUEST_TIME']));
                return 1;
            }



            $img->save($targer_path, $lv);

//            $targer_img = Image::make($targer_path);
            $targer_filesize = $img->filesize();//得到压缩后的大小

            if ($targer_filesize > $filesize)
            {
                if ($lv > 10)//确定$lv为正数
                {
                    $img->destroy();
                    self::img_compress($path, $targer_path, $filesize, $lv - 10);
                }
            }
            else
            {
                return 1;
            }
    }
}