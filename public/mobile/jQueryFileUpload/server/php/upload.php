<?php
    header('Access-Control-Allow-Origin: *'); //设置http://www.baidu.com允许跨域访问
    header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
    //包含一个文件上传类中的上传类
    include "fileupload.class.php";
  
    $fileupload = new fileupload();
    //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
    $fileupload -> set("path", "./images/");
    $fileupload -> set("maxsize", 2000000);
    $fileupload -> set("allowtype", array("gif", "png", "jpg","jpeg"));
    $fileupload -> set("israndname", false);
  
    //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
    if($fileupload -> upload("files")) {
        echo '<pre>';
        //获取上传后文件名子
        var_dump($fileupload->getFileName());
        echo '</pre>';
  
    } else {
        echo '<pre>';
        //获取上传失败以后的错误提示
        var_dump($fileupload->getErrorMsg());
        echo '</pre>';
    }
?>