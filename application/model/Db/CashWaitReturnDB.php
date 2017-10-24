<?php
/**
*
* 用户余额使用明细表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CashWaitReturn.php 10319 2017-03-03 15:48:53Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CashWaitReturnDB extends MysqlDb {

	protected $_tableName = "cash_wait_return";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_orderid 	= null;

	protected $_cashnumber 	= null;

	protected $_bindnumber 	= null;

	protected $_bullnumber 	= null;

	protected $_addtime 	= null;

	protected $_finishtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户余额使用明细表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_orderid) && $data['orderid'] = $this->_orderid;
		! is_null($this->_cashnumber) && $data['cashnumber'] = $this->_cashnumber;
		! is_null($this->_bindnumber) && $data['bindnumber'] = $this->_bindnumber;
		! is_null($this->_bullnumber) && $data['bullnumber'] = $this->_bullnumber;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_finishtime) && $data['finishtime'] = $this->_finishtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户余额使用明细表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_orderid) && $data['orderid'] = $this->_orderid;
		! is_null($this->_cashnumber) && $data['cashnumber'] = $this->_cashnumber;
		! is_null($this->_bindnumber) && $data['bindnumber'] = $this->_bindnumber;
		! is_null($this->_bullnumber) && $data['bullnumber'] = $this->_bullnumber;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_finishtime) && $data['finishtime'] = $this->_finishtime;
		return $this->update($data);
	}

	/**
	 * 删除用户余额使用明细表
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
	 * 获取所有用户余额使用明细表--分页
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
	 * 获取所有用户余额使用明细表
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

				$this->_orderid 	= null;

				$this->_cashnumber 	= null;

				$this->_bindnumber 	= null;

				$this->_bullnumber 	= null;

				$this->_addtime 	= null;

				$this->_finishtime 	= null;

			}
}
?>