<?php
/**
*
* 类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: Amo_amount.php 10319 2017-03-20 10:21:03Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class Amo_amountDB extends MysqlDb {

	protected $_tableName = "amo_amount";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_cashamount 	= null;

	protected $_profitamount 	= null;

	protected $_bullamount 	= null;

	protected $_futCashamount 	= null;

	protected $_futProfitamount 	= null;

	protected $_futBullamount 	= null;

	protected $_comamount 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";





	/**
	 *
	 * 根据ID获取一行
	 * @param interger $id
	 */
	public function getById($id) {
		$this->_id = is_null($id)? $this->_id : $id;
		return $this->getRow(array($this->_primary => $this->_id));
	}

	
	public function cleanAll() {
				$this->_id 	= null;

				$this->_cashamount 	= null;

				$this->_profitamount 	= null;

				$this->_bullamount 	= null;

				$this->_futCashamount 	= null;

				$this->_futProfitamount 	= null;

				$this->_futBullamount 	= null;

				$this->_comamount 	= null;

			}
}
?>