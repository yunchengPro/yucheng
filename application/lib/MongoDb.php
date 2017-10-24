<?php
/**
 * MongoDb 存储类
 */
namespace app\lib;

use \think\Config;

class MongoDb {

    /**
     * 实例数组
     * @var array
     */
    protected static $instance = null;


    /**
     * 初始化实例
     * @Author   zhuangqm
     * @DateTime 2017-02-22T14:19:24+0800
     * @param    $collection_name 表名 如product,user
     * @param    $db_name 库名 默认是nnh，这个参数可以不用传
     * @return   [type]
     */
    public static function ins($collection_name,$db_name="")
    {
        
        if(empty($collection_name)){
            return null;
        }

        //获取redis配置
        $mongodb_conf = Config::get("mongodb");

        $db_name = $db_name!=""?$db_name:$mongodb_conf['db'];

        if(empty(self::$instance)){
            self::$instance = new \app\lib\MongodbConnection($db_name,$collection_name,$mongodb_conf);
        }else{
            self::$instance->set($db_name,$collection_name);
        }

        return self::$instance;

    }



    /**
     * 关闭连接
     *
     * @param int $flag 关闭选择 0:关闭 Master 1:关闭 Slave 2:关闭所有
     * @return boolean
     */
    /*
    public function close($config_name){
        if(isset(self::$instance[$config_name]))
        {
            self::$instance[$config_name]->closeConnection();
            self::$instance[$config_name] = null;
        }
    }
    */

    /**
     * 关闭所有redis实例
     */
    public static function close()
    {
        self::$instance->close();
        /*
        foreach(self::$instance as $connection)
        {
            $connection->closeConnection();
        }
        self::$instance = array();
        */
    }


}

