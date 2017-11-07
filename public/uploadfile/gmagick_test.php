<?php 
	include_once("gmagick.class.php");
	$path = "sdf2349dsfhn2picmz2/thumb/uimg/20160106";
	$gmagick = new GmagickClass();
	$gmagick->int_img("sdf2349dsfhn2picmz2/uimg/20160106/1.gif");
	
	//$gmagick->buildQualityImg("sdf2349dsfhn2picmz2/uimg/20160106/2.jpg","sdf2349dsfhn2picmz2/thumb/uimg/20160106/2.jpg",70);
	//$gmagick->buildQualityImg("sdf2349dsfhn2picmz2/uimg/20160106/1.gif","sdf2349dsfhn2picmz2/thumb/uimg/20160106/1.gif",70);
	
	//$gmagick->make_thumb('sdf2349dsfhn2picmz2/thumb/uimg/20160106/',200,0,false,80);
	$gmagick->resize(400,0);
	$gmagick->write("sdf2349dsfhn2picmz2/thumb/uimg/20160106/1.gif");
	/*
	//压缩jpg图片
	$gmagick->stripimage(); //去除图片信息
	$gmagick->setImageCompressionQuality(70);
	*/
	//print_r($gmagick->getimageunits());
	//$width = $gmagick->getimagewidth();
	//$height= $gmagick->getimageheight();
	//echo "width:$width;height:$height";
	//var_dump($gmagick->queryFormats());
	//$gmagick->setImageFormat('PNG8');
	//$colors = min(255, $gmagick->getImageColors());
	//$gmagick->quantizeImage($colors, Gmagick::COLORSPACE_RGB, 0, false, false );
	//$gmagick->setImageDepth(8);
	
	//var_dump($gmagick->getQuantumDepth());
	//$gmagick->setImageDepth(4);
	//$gmagick->setImageColorspace(Gmagick::COLOR_ALPHA);
	//$gmagick->setCompressionQuality(50);
	//setImageDepth
	//$gmagick->setImageFormat("PNG8");
	//$gmagick->setImageCompression(Gmagick::COMPRESSION_UNDEFINED);
	//$gmagick->setOption('png:format', 'png8');
	//$gmagick->writeImage("PNG8:sdf2349dsfhn2picmz2/thumb/uimg/20160106/7_95.png");
	//$gmagick->getImageHeight();

	//png图片用这种压缩方式
	//$gmagick->make_thumb("sdf2349dsfhn2picmz2/thumb/uimg/20160106",$gmagick->getImageWidth(),$gmagick->getImageHeight(),false,80,'png');
	

	/*
	//压缩png
	$gmagick->setImageFormat('png');
	$gmagick->stripimage();//去除图片信息
	$flag = $gmagick->getImageAlphaChannel();
	//if(Gmagick::CHANNEL_ALPHA ==$flag || Gmagick::ALPHACHANNEL_DEACTIVATE==$flag){
		$gmagick->setImageCompressionQuality(0);
		$gmagick->setImageType(Gmagick::IMGTYPE_PALETTE);
		$gmagick->writeImage("sdf2349dsfhn2picmz2/thumb/uimg/20160106/9_72.png");
	//}
	*/

	/*
	$gmagick->setImageFormat('PNG');
	//$gmagick->setImageCompression(Gmagick::COMPRESSION_UNDEFINED);
	$a = $gmagick->getImageCompressionQuality() * 0.60;
	if ($a == 0) {
		$a = 60;
	}
	//$gmagick->setImageCompressionQuality($a);

	$gmagick->setImageCompression(\Gmagick::COMPRESSION_UNDEFINED);
	$gmagick->setImageCompressionQuality(0);

	$gmagick->stripImage();
	$gmagick->writeImage("sdf2349dsfhn2picmz2/thumb/uimg/20160106/6_75.png");
	*/
	//$gmagick->setImageCompressionQuality(70);
	//$gmagick->setCompressionQuality(70);
	//echo $gmagick;

	//等比率缩小
	//$gmagick->scaleimage(100,0);

	//$gmagick->write("sdf2349dsfhn2picmz2/thumb/uimg/20160106/6_71.png");
	/*
	//生成压缩质量图-90
	$gmagick->setCompressionQuality(90);
	$gmagick->write("sdf2349dsfhn2picmz2/thumb/uimg/20160106/2_90.jpg");
	$gmagick->setCompressionQuality(80);
	$gmagick->write("sdf2349dsfhn2picmz2/thumb/uimg/20160106/2_80.jpg");
	$gmagick->setCompressionQuality(70);
	$gmagick->write("sdf2349dsfhn2picmz2/thumb/uimg/20160106/2_70.jpg");
	*/
	//用原图--生成200*200缩略图并且缩略图压缩80 
	//$gmagick->make_thumb('sdf2349dsfhn2picmz2/thumb/uimg/20160106/',200,200,false,80);

	$gmagick->close();
	echo "OK3344";
	exit;
?>