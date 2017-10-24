<?php
/**
*
* 用户待返绑定现金流水表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: AmoFlowFutProfit.php 10319 2017-03-20 11:53:29Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class AmoFlowFutProfitDB extends MysqlDb {

	protected $_tableName = "amo_flow_fut_cus_profit";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_flowid 	= null;

	protected $_userid 	= null;

	protected $_direction 	= null;

	protected $_amount 	= null;

	protected $_finalAmount 	= null;

	protected $_futstatus 	= null;

	protected $_flowtime 	= null;

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

				$this->_flowid 	= null;

				$this->_userid 	= null;

				$this->_direction 	= null;

				$this->_amount 	= null;

				$this->_finalAmount 	= null;

				$this->_futstatus 	= null;

				$this->_flowtime 	= null;

			}
}
?>