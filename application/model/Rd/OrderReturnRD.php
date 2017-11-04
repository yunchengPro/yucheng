<?php
namespace app\model\Rd;

use app\lib\Db\RedisDb;

class OrderReturnRD extends RedisDb
{
    protected $_prefix = "orderreturn";
    
    protected $_db = "nnh_redis"; //redis配置项 和extra/redis.php对应
}