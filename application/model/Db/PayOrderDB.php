<?php
/**
*
* 公司现金流水表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: AmoFlowComCash.php 10319 2017-03-20 11:23:28Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class PayOrderDB extends MysqlDb {

	protected $_tableName = "pay_order";

	protected $_primary = "id";

	protected $_db = "nnh_db";

	protected $_table_prefix = "";

}
?>