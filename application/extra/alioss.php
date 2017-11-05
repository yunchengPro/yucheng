<?php 

	return [

	    "OSSServerName"=>"oss-cn-shenzhen.aliyuncs.com/",
	    
		"ImgServerName"=>"http://nnhcoupon.oss-cn-shenzhen.aliyuncs.com/",

		"bucketName"=>"nnhcoupon",

		"accessKeyId"=>"LTAIzdRdFNN9MMwf",

		"accessKeySecret"=>"ylSmogFtWMgK1aPr0x5GmNX5spYNQ2",

		"expire"=>30, //设置该policy超时时间是30s. 即这个policy过了这个有效时间，将不能访问

		"maxsize"=>10485760, //最大文件 大小 10m

		"success_action_status"=>"200",
		
		//"callbackUrl"=>"http://shopnc.shaoslu.me/osscallback.php",
		
		//保存的文件夹
		"server_type"=>"nnhcoupon/images",

		"domain"=>"http://nnhcoupon.oss-cn-shenzhen.aliyuncs.com/",


	];