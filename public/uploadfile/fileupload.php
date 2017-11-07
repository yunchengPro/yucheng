<?php
    include "fileupload.class.php";
    $fileupload = new fileupload();

    //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
    //$fileupload -> set("path", "./images/");
    //$fileupload -> set("maxsize", 2000000);
    //$fileupload -> set("allowtype", array("gif", "png", "jpg","jpeg"));
    //$fileupload -> set("israndname", false);
    $server_type = $_POST['server_type']!=''?$_POST['server_type']:'uimg'; 
    //$fileupload -> set("dir", dirname(__FILE__)."/");
    $fileupload -> set("path", $server_type."/");
    
    $imageFieldName = $_POST['imageFieldName']!=''?$_POST['imageFieldName']:'files'; //提交过来的表单名称
    //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 files, 如果成功返回true, 失败返回false
    if($fileupload -> upload($imageFieldName)) {
        //$file = $fileupload->getFileName();
        $url = $fileupload->get('newFileName');
        $original = $fileupload->get('originName');
        $type = $fileupload->get('fileType');
        $size = $fileupload->get('fileSize');

        
        //生成压缩图
        $dir      = $fileupload->get("dir");
        $ext_path = $fileupload->get('ext_path');
        $thumb    = $fileupload->get('thumb');
        $path     = $fileupload->get('path');
        //echo $dir.$ext_path."/".$thumb."/".$path;
        if($fileupload->mkdirs($dir.$ext_path."/".$thumb."/".$path)){
            
            try{
              include_once("gmagick.class.php");
              $gmagick = new GmagickClass();
              
              foreach($url as $value){
                  $img_path     = $dir.$ext_path."/".$value;
                  $new_img_path = $dir.$ext_path."/".$thumb."/".$value;
                  $gmagick->buildQualityImg($img_path,$new_img_path,70);
              }
              
              $gmagick->close();
            }catch(Exception $e){
              //print_r($e->getMessage()); 
              
            }
            
        }
        $dir      = null;
        $ext_path = null;
        $thumb    = null;
        $path     = null;
      
        $url      = count($url)==1?$url[0]:json_encode($url);
        $original = count($original)==1?$original[0]:json_encode($original);
        $type     = count($type)==1?$type[0]:json_encode($type);
        $size     = count($size)==1?$size[0]:json_encode($size);

        $arr = array(
              "state" => "SUCCESS",   //上传状态，上传成功时必须返回"SUCCESS"
              "url" => $url,      //返回的地址
              "title" => "",          //新文件名
              "original" => $original,       //原始文件名
              "type" => $type,           //文件类型
              "size" => $size,           //文件大小
          );
        return json_encode($arr);

    } else {
        $arr = array(
              "state" => "ERROR-".$fileupload->get('errorNum'),     //上传状态，上传成功时必须返回"SUCCESS"
              "url" => '',            //返回的地址
              "title" => "",          //新文件名
              "original" => "",       //原始文件名
              "type" => "",           //文件类型
              "size" => "",           //文件大小
          );
        return json_encode($arr);
    }

?>