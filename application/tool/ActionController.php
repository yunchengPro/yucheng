<?php
namespace app\tool;

use app\ComController;

use \think\Config;

class ActionController extends ComController
{   
    
    public function __construct(){
        parent::__construct();
    }

    /*
    * 获取当前url请求相对路径
    * return 如：user/index/detail
    */
    public function getPath(){
        $Path_arr = explode("/",$this->request->path());
        $Module     = isset($Path_arr[0])&&$Path_arr[0]!=''?ucfirst($Path_arr[0]):"Index";
        $Controller = isset($Path_arr[1])&&$Path_arr[1]!=''?ucfirst($Path_arr[1]):"Index";
        $Method     = isset($Path_arr[2])&&$Path_arr[2]!=''?$Path_arr[2]:"index";

        return ucfirst($Module).'/'.$Controller.'/'.$Method;
    }

    /*
    * 跳到视图页面
    * $data = array() 数组格式 带到视图页面的参数
    */
    public function view($data = array()){
        return view($this->getPath(), (array)$data);
    }

    /*
    * 获取视图路径
    */
    public function getViewUrl(){
        
        return ucfirst($this->param['_apiname']);
    }
}
