<?php
// +----------------------------------------------------------------------
// |  [ 2016-09-19 商家满包邮]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------

namespace app\model\Db;

use app\lib\Db\MysqlDb;

//继承自数据库操作类
class BusBusinessFreightDB extends MysqlDb {//继承自Mysql的操作
	protected $_tableName = "bus_business_freight"; //表名

	protected $_primary = "id"; //主键名

	protected $_db = "nnh_db"; //Db的配置项 和database.php对应
    
	protected $_table_prefix = ""; //表前缀
}