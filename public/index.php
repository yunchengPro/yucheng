<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
// 
use \think\Request;

$SERVER_NAME = $_SERVER['SERVER_NAME'];  

$SERVER_NAME_ARR = explode(".",$SERVER_NAME);

$bind_module_key = in_array($SERVER_NAME_ARR[0], ["sale","superadmin","mobile","api","tool"])?$SERVER_NAME_ARR[0]:"sale";

if($bind_module_key=='mobile')
	$bind_module_key = 'sale';

// $_phpstarttime = microtime(true);
// define('PHPRUNTIME',$_phpstarttime);

// function phpruntimefunc($data,$filename=''){

//     $file = ROOT_PATH."runtime".DS."log".DS."phpruntime"."_".date("Y-m-d").".txt";
    
//     $f = fopen($file,'a');
//     $data = json_encode($data,JSON_UNESCAPED_UNICODE)."\n";
//     fwrite($f,$data);
//     fclose($f);
// }

// 绑定当前访问到index模块
define('BIND_MODULE',$bind_module_key);

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');


// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';


// $request = Request::instance();
// $request_arr = $request->only("_apiname");

// if($bind_module_key=='superadmin')
// 	phpruntimefunc([
// 			"time"=>date("Y-m-d H:i:s"),
// 			"model"=>$bind_module_key,
// 			"apiname"=>$request_arr['_apiname'],
// 			"phpruntime"=>microtime(true)-$_phpstarttime,
// 		]);