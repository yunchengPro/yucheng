<?php 
	//echo $_GET['type']."<br>";
	//echo $_GET['filepath']."<br>";
	$type 		= $_GET['type'];
	$filepath 	= $_GET['filepath'];
	if(!empty($type) && !empty($filepath)){
		include "fileupload.class.php";
		$fileupload = new fileupload();
		//array ( [dirname] => 20160106/06/01 [basename] => 1452047097el710.jpg [extension] => jpg [filename] => 1452047097el710 ) 
		$pathinfo = pathinfo($filepath);
		$ContentType = array(
				"gif"=>"image/gif",
				"jpeg"=>"image/jpeg",
				"jpg"=>"image/jpeg",
				"png"=>"image/png",
			);
		
		header("Cache-Control: public, max-age=".(30*24*3600).", pre-check=".(30*24*3600));
		header("Pragma: public");
		header("Expires: " . date(DATE_RFC822,strtotime(" 30 day")));
		header( "Content-type: ".$ContentType[strtolower($pathinfo['extension'])]);
		
		//判断是否需要缩略图
		$thumbnail_flag = false;
		$file_name =''; //原图名称
		$filenamearr = array();
        //路径方式 如：1452047097el710_200x200.jpg
        if(strpos($pathinfo['filename'],"_")!==false){
        	$thumbnail_flag = true;
        	$filenamearr = explode("_",$pathinfo['filename']);
        	if(strpos($filenamearr[0],".".$pathinfo['extension'])!==false){
        		//路径格式：123123213213.jpg_150x150.jpg
        		$file_name = $filenamearr[0];
        		$pathinfo['basename'] = str_replace(".".$pathinfo['extension'], "_".$filenamearr[1].".".$pathinfo['extension'],$filenamearr[0]);
        	}else{
        		//路径格式：123213213_150x150.jpg
        		$file_name = $filenamearr[0].".".$pathinfo['extension'];
        	}
        	
        }

		$dir      = $fileupload->get("dir");
        $ext_path = $fileupload->get('ext_path');
        $thumb    = $fileupload->get('thumb');

        //原路径
        $dir_original 	= $dir.$ext_path."/".$type."/".$pathinfo['dirname']."/";
        //压缩路径
        $dir_thumb 		= $dir.$ext_path."/".$thumb."/".$type."/".$pathinfo['dirname']."/";

        if($thumbnail_flag){
        	$filepath_original = $dir_original.$pathinfo['basename']; //原始图片路径
        	$filepath_thumb = $dir_thumb.$file_name; //原始图片的压缩路径
        	$thumbnail_filepath = $dir_thumb.$pathinfo['basename']; //压缩图片的缩略图路径
        }else{
        	$filepath_original = $dir_original.$pathinfo['basename']; //原始图片路径
        	$filepath_thumb = $dir_thumb.$pathinfo['basename']; //原始图片的压缩路径
        }
        
        //取压缩图
        if(!file_exists($filepath_thumb)){
        	//生成压缩图
        	if($fileupload->mkdirs($dir.$ext_path."/".$thumb."/".$type."/".$pathinfo['dirname'])){
	            try{
	              	include_once("gmagick.class.php");
	              	$gmagick = new GmagickClass();
                  	$gmagick->buildQualityImg($filepath_original,$filepath_thumb,80);
	              	$gmagick->close();
	              	$gmagick = null;
	            }catch(Exception $e){
	              	//print_r($e->getMessage()); 

	            }
	            
	        }
        }
		
		//判断是否需要缩略图
        //路径方式 如：1452047097el710_200x200.jpg
        //生成缩略图
        if($thumbnail_flag){
        	if(!file_exists($thumbnail_filepath)){
	        	//生成压缩图
	        	try{
		          	include_once("gmagick.class.php");
		          	$gmagick = new GmagickClass();
		          	$gmagick->int_img($filepath_thumb);

		          	$arr = explode("x",$filenamearr[1]);
		          	$width  = intval($arr[0]);
		          	$height = intval($arr[1]);

		          	$gmagick->make_thumb($dir_thumb,$width,$height,false,90);
		          	$gmagick->close();

		        	$filepath_thumb = $thumbnail_filepath;
	            }catch(Exception $e){
	              //print_r($e->getMessage()); 
	            }
       	 	}else{
       	 		$filepath_thumb = $thumbnail_filepath;
       	 	}
        }

		//echo $filepath_thumb;
		echo fread(fopen($filepath_thumb, "r"), filesize($filepath_thumb));
	}
	exit;
?>