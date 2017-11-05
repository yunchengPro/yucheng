<?php 
	header('Access-Control-Allow-Origin: *'); //设置http://www.baidu.com允许跨域访问
    header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header

    $result = include_once("fileupload.php");
    echo $result;
    exit;
?>