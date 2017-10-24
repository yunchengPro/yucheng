<?php
namespace app\lib;

use \think\Config;
/**
调用方式
    $demoOBJ = Db::Table("DemoDB");  mysql操作
    $demoOBJ = Db::Table("DemoMGDB"); mongodb操作
    $demoOBJ = Db::Table("DemoRDDB"); redis操作
 */

class Model{
    
    /**
     * 实例数组
     * @var array
     */
    public static $instance = array();


    /**
     * 初始化Model层
     * @Author   zhuangqm
     * @DateTime 2017-03-02T15:13:07+0800
     * @param    [type]                   $table_name [description]
     */
    public static function new($table_name){ 
        if(empty(self::$instance[$table_name."Model"])){
            $tmp = explode(".",$table_name);
            $classname = "\app\model\\".ucfirst($tmp[0])."\\".ucfirst($tmp[1])."Model";
            self::$instance[$table_name."Model"] = new $classname();
        }

        return self::$instance[$table_name."Model"];
    }

    /**
     * 初始化Model对象
     * @Author   zhuangqm
     * @DateTime 2017-03-02T15:13:07+0800
     * @param    [type]                   $table_name [description]
     */
    public static function ins($table_name){
        if(empty(self::$instance[$table_name."Model"])){
            $classname = "\app\model\\".$table_name."Model";
            self::$instance[$table_name."Model"] = new $classname();
        }

        return self::$instance[$table_name."Model"];
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

    public static function Es($table_name){
        if(empty(self::$instance[$table_name."ES"])){
            $classname = "\app\model\\Es\\".$table_name."ES";
            self::$instance[$table_name."ES"] = new $classname();
        }

        return self::$instance[$table_name."ES"];
    }
}
