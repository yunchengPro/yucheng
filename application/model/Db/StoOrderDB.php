<?php
/**
*
* 实体店订单表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdOrder.php 10319 2017-03-03 16:19:56Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoOrderDB extends MysqlDb {

	protected $_tableName = "sto_order";

	protected $_primary = "id";

	protected $_orderno 	= null;

	protected $_customerid 	= null;

	protected $_customer_name 	= null;

	protected $_actualfreight 	= null;

	protected $_productcount 	= null;

	protected $_productamount 	= null;

	protected $_totalamount 	= null;

	protected $_addtime 	= null;

	protected $_orderstatus 	= null;

	protected $_return_status	= null;

	protected $_businessid = null;

	protected $_businessname = null;

	protected $_evaluate = null;

	protected $_ordertime = null;

	protected $_delivertime = null;

	protected $_confirm_time = null;

	protected $_finish_time = null;

	protected $_return_time = null;

	protected $_return_success_time = null;

	protected $_cancelsource = null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入订单表
	 */
	public function add() {
		
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新订单表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		
		return $this->update($data);
	}

	/**
	 * 删除订单表
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
	 * 获取所有订单表--分页
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
	 * 获取所有订单表
	 * @return Ambigous 
	 */
	public function getAll() {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		return $this->select();
	}

	
	
}
?>