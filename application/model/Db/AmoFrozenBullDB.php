<?php
/**
*
* 用户牛豆冻结表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: AmoFrozenBull.php 10319 2017-03-20 11:55:59Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class AmoFrozenBullDB extends MysqlDb {

	protected $_tableName = "amo_frozen_bull";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_customerid 	= null;

	protected $_amount 	= null;

	protected $_status 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户牛豆冻结表
	 */
	public function add() {
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_status) && $data['status'] = $this->_status;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户牛豆冻结表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_status) && $data['status'] = $this->_status;
		return $this->update($data);
	}

	/**
	 * 删除用户牛豆冻结表
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
	 * 获取所有用户牛豆冻结表--分页
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
	 * 获取所有用户牛豆冻结表
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

				$this->_orderno 	= null;

				$this->_customerid 	= null;

				$this->_amount 	= null;

				$this->_status 	= null;

			}
}
?>