<?php
/**
*
* 店铺收款流水表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoPayFlow.php 10319 2017-03-09 16:14:37Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoPayFlowDB extends MysqlDb {

	protected $_tableName = "sto_pay_flow";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_payCode 	= null;

	protected $_businessid 	= null;

	protected $_addcustomerid 	= null;

	protected $_addcustomername 	= null;

	protected $_status 	= null;

	protected $_amount 	= null;

	protected $_noinvamount 	= null;

	protected $_addtime 	= null;

	protected $_paytime 	= null;

	protected $_payamount 	= null;

	protected $_paycustomerid 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入店铺收款流水表
	 */
	public function add() {
		! is_null($this->_payCode) && $data['pay_code'] = $this->_payCode;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_addcustomerid) && $data['addcustomerid'] = $this->_addcustomerid;
		! is_null($this->_addcustomername) && $data['addcustomername'] = $this->_addcustomername;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_noinvamount) && $data['noinvamount'] = $this->_noinvamount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_paytime) && $data['paytime'] = $this->_paytime;
		! is_null($this->_payamount) && $data['payamount'] = $this->_payamount;
		! is_null($this->_paycustomerid) && $data['paycustomerid'] = $this->_paycustomerid;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新店铺收款流水表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_payCode) && $data['pay_code'] = $this->_payCode;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_addcustomerid) && $data['addcustomerid'] = $this->_addcustomerid;
		! is_null($this->_addcustomername) && $data['addcustomername'] = $this->_addcustomername;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_noinvamount) && $data['noinvamount'] = $this->_noinvamount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_paytime) && $data['paytime'] = $this->_paytime;
		! is_null($this->_payamount) && $data['payamount'] = $this->_payamount;
		! is_null($this->_paycustomerid) && $data['paycustomerid'] = $this->_paycustomerid;
		return $this->update($data);
	}

	/**
	 * 删除店铺收款流水表
	 */
	public function del($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		return $this->delete($data);
	}

	/**
	 *
	 * 根据ID获取一行
	 * @param interger $id
	 */
	public function getById($id) {
		$this->_id = is_null($id)? $this->_id : $id;
		return $this->getRow(array($this->_primary => $this->_id));
	}

	/**
	 *
	 * 获取所有店铺收款流水表--分页
	 * @param interger $status
	 */
	public function getAllForPage($page = 0, $pagesize = 20) {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		$this->_totalPage = $this->count();
		return $this->page($page, $pagesize)->order("{$this->_primary} desc")->select();
	}

	/**
	 * 获取所有店铺收款流水表
	 * @return Ambigous 
	 */
	public function getAll() {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		return $this->select();
	}
	
	public function cleanAll() {
				$this->_id 	= null;

				$this->_payCode 	= null;

				$this->_businessid 	= null;

				$this->_addcustomerid 	= null;

				$this->_addcustomername 	= null;

				$this->_status 	= null;

				$this->_amount 	= null;

				$this->_noinvamount 	= null;

				$this->_addtime 	= null;

				$this->_paytime 	= null;

				$this->_payamount 	= null;

				$this->_paycustomerid 	= null;

			}
}
?>