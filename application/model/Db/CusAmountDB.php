<?php
/**
*
* 用户资金流失冻结表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusAmount.php 10319 2017-03-03 15:58:15Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusAmountDB extends MysqlDb {

	protected $_tableName = "cus_amount";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_cash 	= null;

	protected $_bindcash 	= null;

	protected $_bullcash 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户资金流失冻结表
	 */
	public function add() {
		! is_null($this->_cash) && $data['cash'] = $this->_cash;
		! is_null($this->_bindcash) && $data['bindcash'] = $this->_bindcash;
		! is_null($this->_bullcash) && $data['bullcash'] = $this->_bullcash;
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
		! is_null($this->_cash) && $data['cash'] = $this->_cash;
		! is_null($this->_bindcash) && $data['bindcash'] = $this->_bindcash;
		! is_null($this->_bullcash) && $data['bullcash'] = $this->_bullcash;
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

				$this->_cash 	= null;

				$this->_bindcash 	= null;

				$this->_bullcash 	= null;

			}
}
?>