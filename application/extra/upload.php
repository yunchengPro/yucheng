<?php
return [

    "url"=>"/uploadfile/jqueryfileupload.php", //上传的接收路径 --以后独立使用 目前用阿里oss代替
    
    "domain"=>"//nnhtest.oss-cn-shenzhen.aliyuncs.com/", //图片访问域名
    //"domain"=>"//spakeys.img-cn-shenzhen.aliyuncs.com/", //图片访问域名

    "maxFileSize"=>2097152,//文件最大大小2048000
    
    "maxNumberOfFiles"=>"1",//文件数
    
    "savefileurl"=>"/Sys/upload/getfile", //保存图片提交
    
    "getParamUrl"=>"/Sys/upload/policy",//阿里oss的配置  /sys/upload/policy
    
    "server_type"=>"NNH/images", //默认上传目录	
			
];
