<?php 
namespace app\model\Mg;

use app\lib\Db\MongoDb;

//继承自数据库操作类
class DemoMG extends MongoDb //继承自Mysql的操作
{	

	protected $_db_name = "nnh"; //库名

	protected $_collection_name = "demo"; //表名

	protected $_db = "nnh_mongo"; //MongoDb的配置项 和mongodb.php对应
}