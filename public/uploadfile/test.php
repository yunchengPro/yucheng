<?php 
	header('Access-Control-Allow-Origin: *'); //设置http://www.baidu.com允许跨域访问
    header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header

    $id 	= $_POST['id'];
    $data 	= $_POST['data'];

    $myfile = fopen("sdf2349dsfhn2picmz2/test.txt", "w") or die("Unable to open file!");
	$txt = "id:$id;data:$data\n";
	fwrite($myfile, $txt);
	fclose($myfile);
	echo "OK-$id-$data";
    exit;
?>