<?php
	$dir = "sdf2349dsfhn2picmz2/thumb/uimg/20160106";
	if (!is_dir($dir)){
        $ret = @mkdir($dir, 0755, true);
        chmod($dir,0755);
        
    }
    echo $dir;
    echo "---OK";
?>