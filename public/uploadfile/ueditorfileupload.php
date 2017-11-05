<?php
    /*
    * ueditor 入口
    */
    header('Access-Control-Allow-Origin: *'); //设置http://www.baidu.com允许跨域访问
    header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
    date_default_timezone_set("Asia/chongqing");
    error_reporting(E_ERROR);
    header("Content-Type: text/html; charset=utf-8");

    
    $action = $_GET['action'];

    switch ($action) {
        case 'config':
            $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("ueditor_config.json")), true);
            $result =  json_encode($CONFIG);
            break;
        /* 上传图片 */
        case 'uploadimage':
        /* 上传文件 */
        case 'uploadfile':
            $result = include("fileupload.php");
            break;
        default:
            $result = json_encode(array(
                'state'=> '请求地址出错'
            ));
            break;
    }

    /* 输出结果 */
    if (isset($_GET["callback"])) {
        if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
            echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
        } else {
            echo json_encode(array(
                'state'=> 'callback参数不合法'
            ));
        }
    } else {
        echo $result;
    }