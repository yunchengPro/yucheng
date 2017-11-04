<?php 
namespace app\model\Rd;

use app\lib\Db\RedisDb;

//继承自数据库操作类
class ActLimitRD extends RedisDb //继承自Mysql的操作
{	

	protected $_prefix = "actlimit"; //redis前缀

	protected $_db = "nnh_redis"; //redis配置项 和extra/redis.php对应


}