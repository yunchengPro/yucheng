<?php 
namespace app\model\Db;

use app\lib\Db\MysqlDb;

//继承自数据库操作类
class OrdOrder extends MysqlDb //继承自Mysql的操作
{	
	protected $_tableName = "ord_order"; //表名

	protected $_primary = "id"; //主键名

	protected $_db = "nnh_db"; //Db的配置项 和database.php对应
    
	protected $_table_prefix = ""; //表前缀
}