<?php 
namespace app\model\Db;

use app\lib\Db\MongoDb;

//继承自数据库操作类
class DemoMGDB extends MongoDb //继承自Mysql的操作
{	

	protected $db_name = "nnh"; //库名

	protected $collection_name = "user"; //表名

	protected $_db = "nnh_mongo"; //MongoDb的配置项 和mongodb.php对应
}