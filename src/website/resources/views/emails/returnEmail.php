<?php
 /************************************************************
  Copyright (C), 2014-2015, Everyoo Tech. Co., Ltd.
  @FileName: returnEmail.php
  @Author: zzx       Version :   V.1.0.0       Date: 2015/6/2
  @Description:    该文件是回执用户申请退换修货

***********************************************************/
?>
<!<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>关于退换维修货物的回执邮件</title>
    <style>

    </style>
</head>
<body>
<div id="head">
    <h3>尊敬的<?php
                if (!empty($name)) {
                    echo $name;
                }
             ?>您好：</h3>
</div>
<div id="content">
    <p>首先感谢您使用爱悠智慧生活的产品(<a href="www.everyoo.com" target="_blank">www.everyoo.com</a>)!</p>
    <p>关于您提交的<?php
                    //输出产品
                    if (!empty($product)) {
                        echo $product . '的';
                    }
                    //申请类型
                    if (!empty($type)) {
                        echo $type . '的';
                    }
                    //结果 boolean类型
                    if (!empty($result)) {
                        if ($result) {
                            echo '很高兴的告诉您，您的申请通过了！请您提供快递单号给我们，我们会继续给您处理的';
                        } else {
                            echo '很遗憾的告诉您，您的申请被驳回了！驳回原因为：' . isset($info['reason']) ? $info['reason'] : '不详';
                        }
                    }
?>
                </p>
</div>
</body>
</html>