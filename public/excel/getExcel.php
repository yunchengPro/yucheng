<?php 
	function downloads($filepath,$filename=''){
        $dir = dirname(__FILE__)."/excelfile/";   
         
        if (!file_exists($dir.$filepath)){
            header("Content-type: text/html; charset=utf-8");
            echo "File not found!";
            exit; 
        } else {
        	$pathinfo = pathinfo($filepath);
            $file = fopen($dir.$filepath,"r"); 
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: ".filesize($dir.$filepath));
            if($filename!='')
                Header("Content-Disposition: attachment; filename=".$filename."_".$pathinfo['filename'].".".$pathinfo['extension']);
            else
                Header("Content-Disposition: attachment; filename=".$pathinfo['basename']);
            echo fread($file, filesize($dir.$filepath));
            fclose($file);
        }
    }

    $filepath = $_GET['filepath'];
    $filename = $_GET['filename'];
   
    if($filepath!=''){
        $pathinfo = pathinfo($filepath);
        //半小时有效
        if(( time()-intval(substr($pathinfo['filename'],4,10)))<1800)
            downloads($filepath,$filename);
        else
            echo "File not found!";
    }else{
    	header("Content-type: text/html; charset=utf-8");
        echo "File not found!";
    }
    exit; 
?>