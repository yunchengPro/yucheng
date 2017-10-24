<?php
namespace app\lib;

use \think\Config;

/**
 * 加载模块
 */
class Load{

    /**
     * 实例数组
     * @var array
     */
    public static $instance = array();
    
    /**
     * 加载api接口体系接口
     * @Author   zhuangqm
     * @DateTime 2017-08-03T11:33:23+0800
     * @param    [type]                   $param = [
     *                                          apiname=>"", 接口名
     *                                          param=>[],
     *                                     ]
     * @return   [type]                   [description]
     */
    public static function API($param){
        $apiname = $param['apiname'];

        if(!empty($apiname)){

            $tmp = explode(".",$apiname);
            $classname = "\app\api\controller\\".ucfirst($tmp[0])."\\".ucfirst($tmp[1])."Controller";
            self::$instance[$table_name."Model"] = new $classname();
            
            

        }else{
            return ["code"=>"404"];
        }
    }
}
