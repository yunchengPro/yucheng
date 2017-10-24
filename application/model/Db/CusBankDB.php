<?php
/**
*
* 用户银行记录表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusBank.php 10319 2017-03-22 09:49:01Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusBankDB extends MysqlDb {

	protected $_tableName = "cus_bank";

	protected $_primary = "id";

	protected $_id 	= null;
	
	protected $_customerid = null;

	protected $_typeId 	= null;
	
	protected $_bankTypeName = null;

	protected $_accountType 	= null;

	protected $_accountName 	= null;

	protected $_accountNumber 	= null;

	protected $_branch 	= null;

	protected $_mobile 	= null;

	protected $_sort 	= null;

	protected $_isDefault 	= null;

	protected $_enable 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户银行记录表
	 */
	public function add() {
	    ! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_typeId) && $data['type_id'] = $this->_typeId;
		! is_null($this->_bankTypeName) && $data['bank_type_name'] = $this->_bankTypeName;
		! is_null($this->_accountType) && $data['account_type'] = $this->_accountType;
		! is_null($this->_accountName) && $data['account_name'] = $this->_accountName;
		! is_null($this->_accountNumber) && $data['account_number'] = $this->_accountNumber;
		! is_null($this->_branch) && $data['branch'] = $this->_branch;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_isDefault) && $data['is_default'] = $this->_isDefault;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户银行记录表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_typeId) && $data['type_id'] = $this->_typeId;
		! is_null($this->_bankTypeName) && $data['bank_type_name'] = $this->_bankTypeName;
		! is_null($this->_accountType) && $data['account_type'] = $this->_accountType;
		! is_null($this->_accountName) && $data['account_name'] = $this->_accountName;
		! is_null($this->_accountNumber) && $data['account_number'] = $this->_accountNumber;
		! is_null($this->_branch) && $data['branch'] = $this->_branch;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_isDefault) && $data['is_default'] = $this->_isDefault;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除用户银行记录表
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
	 * 获取所有用户银行记录表--分页
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
	 * 获取所有用户银行记录表
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
				
				$this->_customerid = null;

				$this->_typeId 	= null;
				
				$this->_bankTypeName = null;

				$this->_accountType 	= null;

				$this->_accountName 	= null;

				$this->_accountNumber 	= null;

				$this->_branch 	= null;

				$this->_mobile 	= null;

				$this->_sort 	= null;

				$this->_isDefault 	= null;

				$this->_enable 	= null;

				$this->_addtime 	= null;

			}
}
?>