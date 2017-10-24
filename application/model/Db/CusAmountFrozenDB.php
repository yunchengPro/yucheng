<?php
/**
*
* 用户资金流失冻结表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusAmountFrozen.php 10319 2017-03-03 16:01:36Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusAmountFrozenDB extends MysqlDb {

	protected $_tableName = "cus_amount_frozen";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_cashType 	= null;

	protected $_frozenType 	= null;

	protected $_frozenObject 	= null;

	protected $_cashCount 	= null;

	protected $_status 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户资金流失冻结表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_cashType) && $data['cash_type'] = $this->_cashType;
		! is_null($this->_frozenType) && $data['frozen_type'] = $this->_frozenType;
		! is_null($this->_frozenObject) && $data['frozen_object'] = $this->_frozenObject;
		! is_null($this->_cashCount) && $data['cash_count'] = $this->_cashCount;
		! is_null($this->_status) && $data['status'] = $this->_status;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户资金流失冻结表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_cashType) && $data['cash_type'] = $this->_cashType;
		! is_null($this->_frozenType) && $data['frozen_type'] = $this->_frozenType;
		! is_null($this->_frozenObject) && $data['frozen_object'] = $this->_frozenObject;
		! is_null($this->_cashCount) && $data['cash_count'] = $this->_cashCount;
		! is_null($this->_status) && $data['status'] = $this->_status;
		return $this->update($data);
	}

	/**
	 * 删除用户资金流失冻结表
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
	 * 获取所有用户资金流失冻结表--分页
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
	 * 获取所有用户资金流失冻结表
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

				$this->_cashType 	= null;

				$this->_frozenType 	= null;

				$this->_frozenObject 	= null;

				$this->_cashCount 	= null;

				$this->_status 	= null;

			}
}
?>