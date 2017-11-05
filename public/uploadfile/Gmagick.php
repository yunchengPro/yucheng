<?php
header("Cache-Control: public, max-age=".(30*24*3600).", pre-check=".(30*24*3600));
header("Pragma: public");
header("Expires: " . date(DATE_RFC822,strtotime(" 30 day")));
// 下面是 按指定大小缩放
$gmagick = new Gmagick();
$gmagick->readimage("sdf2349dsfhn2picmz2/uimg/20160106/1452060477fl878.jpg");
$gmagick->cropthumbnailimage(100, 100); //按指定大小缩放
$gmagick->write('sdf2349dsfhn2picmz2/uimg/20160106/1452060477fl878_100*100.jpg');
header('Content-type: image/jpeg');
echo $gmagick;
$gmagick->destroy();