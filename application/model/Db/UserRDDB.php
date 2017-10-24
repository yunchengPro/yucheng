<?php 
namespace app\model\Db;

use app\lib\Db\RedisDb;

//继承自数据库操作类
class UserRDDB extends RedisDb //继承自Mysql的操作
{	

	protected $_name = "user"; //表名

	protected $_db = "nnh_redis"; //MongoDb的配置项 和mongodb.php对应
}