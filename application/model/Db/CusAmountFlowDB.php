<?php
/**
*
* 用户现金流水记录表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusAmountFlow.php 10319 2017-03-03 16:00:48Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusAmountFlowDB extends MysqlDb {

	protected $_tableName = "cus_amount_flow";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_flowtype 	= null;

	protected $_amounttype 	= null;

	protected $_direction 	= null;

	protected $_balance 	= null;

	protected $_amount 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户现金流水记录表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_flowtype) && $data['flowtype'] = $this->_flowtype;
		! is_null($this->_amounttype) && $data['amounttype'] = $this->_amounttype;
		! is_null($this->_direction) && $data['direction'] = $this->_direction;
		! is_null($this->_balance) && $data['balance'] = $this->_balance;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户现金流水记录表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_flowtype) && $data['flowtype'] = $this->_flowtype;
		! is_null($this->_amounttype) && $data['amounttype'] = $this->_amounttype;
		! is_null($this->_direction) && $data['direction'] = $this->_direction;
		! is_null($this->_balance) && $data['balance'] = $this->_balance;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除用户现金流水记录表
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
	 * 获取所有用户现金流水记录表--分页
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
	 * 获取所有用户现金流水记录表
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

				$this->_customerid 	= null;

				$this->_flowtype 	= null;

				$this->_amounttype 	= null;

				$this->_direction 	= null;

				$this->_balance 	= null;

				$this->_amount 	= null;

				$this->_addtime 	= null;

			}
}
?>