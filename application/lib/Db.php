<?php
namespace app\lib;

use \think\Config;
/**
调用方式
    $demoOBJ = Db::Table("DemoDB");  mysql操作
    $demoOBJ = Db::Table("DemoMGDB"); mongodb操作
    $demoOBJ = Db::Table("DemoRDDB"); redis操作
 */

class Db{
    
    /**
     * 实例数组
     * @var array
     */
    public static $instance = array();

    /**
     * 初始化Model对象
     * @Author   zhuangqm
     * @DateTime 2017-03-02T15:13:07+0800
     * @param    [type]                   $table_name [description]
     */
    public static function Model($table_name){
        if(empty(self::$instance[$table_name."Model"])){
            $classname = "\app\model\\".$table_name."Model";
            self::$instance[$table_name."Model"] = new $classname();
        }

        return self::$instance[$table_name."Model"];
    }

    /**
    * mysql操作
    */
    public static function Table($table_name){
        
        if(empty(self::$instance[$table_name."DB"])){
            $classname = "\app\model\\Db\\".$table_name."DB";
            self::$instance[$table_name."DB"] = new $classname();
        }

        return self::$instance[$table_name."DB"];
    }

    /**
     * Mongodb-----暂时不用
     * @Author   zhuangqm
     * @DateTime 2017-02-24T15:41:13+0800
     * @param    [type]                   $table_name [description]
     */
    public static function Mongo($table_name){

        if(empty(self::$instance[$table_name."MG"])){
            $classname = "\app\model\\Mg\\".$table_name."MG";
            self::$instance[$table_name."MG"] = new $classname();
        }

        return self::$instance[$table_name."MG"];
    }

    /**
     * Redis-----暂时不用
     * @Author   zhuangqm
     * @DateTime 2017-02-24T15:41:13+0800
     * @param    [type]                   $table_name [description]
     */
    public static function Redis($table_name){
        if(empty(self::$instance[$table_name."RD"])){
            $classname = "\app\model\\Rd\\".$table_name."RD";
            self::$instance[$table_name."RD"] = new $classname();
        }

        return self::$instance[$table_name."RD"];
    }
}
