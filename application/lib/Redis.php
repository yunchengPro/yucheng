<?php
/**
 * Redis 存储类
 */
namespace app\lib;

//获取配置
use \think\Config;

class Redis {

    /**
     * 实例数组
     * @var array
     */
    protected static $instance = array();

    
    /**
     * 初始化实例
     * @Author   zhuangqm
     * @DateTime 2017-02-22T11:39:24+0800
     * @param    redisname 当前的redis对象
     * @return   redis操作对象
     */
    public static function ins($redisname)
    {

        if(empty($redisname)){
            return null;
        }

        if(empty(self::$instance[$redisname])){


            //获取redis配置
            $redis_conf = Config::get("redis.nnh_redis");

            //print_r($config);
            self::$instance[$redisname] = new \app\lib\RedisConnection($redis_conf['host'], $redis_conf['port'], $redis_conf['time_out'], $redis_conf['password'],$redisname);
        }

        return self::$instance[$redisname];

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
    public static function closeall($config_name='')
    {
        //echo "======redis count:".count(self::$instance)."======";
        foreach(self::$instance as $connection)
        {
            $connection->closeConnection();
        }
        self::$instance = array();
    }

    public static function close($config_name=''){
        
        foreach(self::$instance as $connection)
        {
            $connection->closeConnection();
        }
        self::$instance = array();
        
    }
}

