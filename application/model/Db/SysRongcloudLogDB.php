<?php
/**
*
* 公司牛豆流水表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: AmoFlowComBull.php 10319 2017-03-20 11:18:51Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class SysRongcloudLogDB extends MysqlDb {

	protected $_tableName = "sys_rongcloud_log";

	protected $_primary = "id";

	protected $_db = "nnh_db";

	protected $_table_prefix = "";

}
?>