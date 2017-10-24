<?php
namespace app\model\Sys;

use app\lib\Model;

/**
 * 页面结束后执行
 * 可多次调用，按调用顺序执行
 */
class RegisterModel
{

    /**
     * 页面执行完成后执行
     * @Author   zhuangqm
     * @DateTime 2017-06-02T19:45:33+0800
     * @param    [type]                   $param [
     *                                           "class"=> 执行的类名
     *                                           "method"=> 方法
     *                                           "param"=>  参数    
     *                                    ]
     * @return   [type]                          [description]
     */
    public static function shutdown($param){
        
        try{

            $callback = [];
            if(!empty($param['class']))
                $callback[] = $param['class'];
            if(!empty($param['method']))
                $callback[] = $param['method'];

            register_shutdown_function($callback,$param['param']);
            
        } catch (\Exception $e) {

        }
        return true;
    }

    

}