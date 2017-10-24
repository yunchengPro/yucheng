<?php
	//jies
	$param = $_POST;

	function writefile($data){
		
        $file = "images/log.txt";
        $f = fopen($file,'a');
        $data.="\n";
        fwrite($f,$data);
        fclose($f);
	}

	if(!empty($param)){
		if($param['agentid']>0 && $param['authid']>0){

			$can = '';
			foreach($param as $key=>$value){
				$can.="&".$key."=".urlencode($value);
			}
			$can = $can!=''?substr($can,1):"";
			$url = $param['modelUrl']."?".$can;
			$image_name = $param['agentid'].$param['authid'].rand(1000,9999).time().".jpg";
			$str = 'wkhtmltoimage --crop-w 740 "'.$url.'" '.$param['imageurl'].$image_name;
			
			//$post_str = json_encode($param,JSON_UNESCAPED_UNICODE);
			//writefile($str."\n".$post_str);

			exec($str,$return);
			//wkhtmltoimage --crop-w 760 http://10.70.40.250/img/letter_authorization.html le5.jpg
			echo $image_name;
			exit;
		}else{
			exit;
		}
	}else{
		exit;
	}