<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use \think\Route;
use \think\Request;

$request = Request::instance();

if(BIND_MODULE=='sale'){
	$pathinfo = $request->pathinfo();
	$_pathinfo_arr = explode("/",$pathinfo);
	
	$_pathinfo_arr[0] = $_pathinfo_arr[0]!=''&&$_pathinfo_arr[0]!='/'?ucfirst($_pathinfo_arr[0]):"Index";
	$_pathinfo_arr[1] = $_pathinfo_arr[1]!=''?ucfirst($_pathinfo_arr[1]):"Index";
	$_pathinfo_arr[2] = $_pathinfo_arr[2]!=''?$_pathinfo_arr[2]:"index";
	
	Route::rule($pathinfo,$_pathinfo_arr[0].".".$_pathinfo_arr[1]."/".$_pathinfo_arr[2]);
}else if(BIND_MODULE=='api'){
	$request_arr = $request->only("_apiname");

	$_apiname = $request_arr['_apiname'];

	$payflag = 0;
	if(empty($_apiname)){
		$path = $request->path();

		//echo "--apiname:".$apiname."--".$path."---";
		if(strpos($path,"Payment/Noity/")!==false){
			$_apiname = str_replace('/', '.', $path);
			$payflag = 1;

		}else{
			echo json_encode(array(
					"code"=>"400",
					"msg"=>"api not exit",
				));
			exit;
		}
	}

	$_apiname_arr = explode(".",$_apiname);

	if($payflag==1){
		Route::rule("/".ucfirst($_apiname_arr[0])."/".ucfirst($_apiname_arr[1])."/".$_apiname_arr[2],ucfirst($_apiname_arr[0]).".".ucfirst($_apiname_arr[1])."/".$_apiname_arr[2]);
	}else{
		Route::rule("",ucfirst($_apiname_arr[0]).".".ucfirst($_apiname_arr[1])."/".$_apiname_arr[2]);
	}
}else if(BIND_MODULE=='superadmin'){
	$pathinfo = $request->pathinfo();
	$_pathinfo_arr = explode("/",$pathinfo);
	
	$_pathinfo_arr[0] = $_pathinfo_arr[0]!=''&&$_pathinfo_arr[0]!='/'?ucfirst($_pathinfo_arr[0]):"Index";
	$_pathinfo_arr[1] = $_pathinfo_arr[1]!=''?ucfirst($_pathinfo_arr[1]):"Index";
	$_pathinfo_arr[2] = $_pathinfo_arr[2]!=''?$_pathinfo_arr[2]:"index";
	
	Route::rule($pathinfo,$_pathinfo_arr[0].".".$_pathinfo_arr[1]."/".$_pathinfo_arr[2]);
}else if(BIND_MODULE=='auto'){

	//自动执行任务
	$param = $_SERVER['argv'];

    if($param[1]==''){
        echo json_encode(array(
				"code"=>"400",
				"msg"=>"autoapi not exit",
			));
		exit;
	}

    $_apiname_arr = explode(".",$param[1]);
    $_apiname_arr[0] = $_apiname_arr[0]!=''?ucfirst($_apiname_arr[0]):"Index";
    $_apiname_arr[1] = $_apiname_arr[1]!=''?ucfirst($_apiname_arr[1]):"Index";
    $_apiname_arr[2] = $_apiname_arr[2]!=''?$_apiname_arr[2]:"index";

	Route::rule($param[1],$_apiname_arr[0].".".$_apiname_arr[1]."/".$_apiname_arr[2]);

}else if(BIND_MODULE=='tool'){
	$pathinfo = $request->pathinfo();
	$_pathinfo_arr = explode("/",$pathinfo);
	
	$_pathinfo_arr[0] = $_pathinfo_arr[0]!=''&&$_pathinfo_arr[0]!='/'?ucfirst($_pathinfo_arr[0]):"Index";
	$_pathinfo_arr[1] = $_pathinfo_arr[1]!=''?ucfirst($_pathinfo_arr[1]):"Index";
	$_pathinfo_arr[2] = $_pathinfo_arr[2]!=''?$_pathinfo_arr[2]:"index";

	Route::rule($pathinfo,$_pathinfo_arr[0].".".$_pathinfo_arr[1]."/".$_pathinfo_arr[2]);


}