<?php
/**
*
* 用户订单数量信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdUserCount.php 10319 2017-03-03 16:41:15Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdUserCountDB extends MysqlDb {

	protected $_tableName = "ord_user_count";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_countPay 	= null;

	protected $_countDeliver 	= null;

	protected $_countReceipt 	= null;

	protected $_countEvaluate 	= null;

	protected $_countCart 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户订单数量信息表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_countPay) && $data['count_pay'] = $this->_countPay;
		! is_null($this->_countDeliver) && $data['count_deliver'] = $this->_countDeliver;
		! is_null($this->_countReceipt) && $data['count_receipt'] = $this->_countReceipt;
		! is_null($this->_countEvaluate) && $data['count_evaluate'] = $this->_countEvaluate;
		! is_null($this->_countCart) && $data['count_cart'] = $this->_countCart;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户订单数量信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_countPay) && $data['count_pay'] = $this->_countPay;
		! is_null($this->_countDeliver) && $data['count_deliver'] = $this->_countDeliver;
		! is_null($this->_countReceipt) && $data['count_receipt'] = $this->_countReceipt;
		! is_null($this->_countEvaluate) && $data['count_evaluate'] = $this->_countEvaluate;
		! is_null($this->_countCart) && $data['count_cart'] = $this->_countCart;
		return $this->update($data);
	}

	/**
	 * 删除用户订单数量信息表
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
	 * 获取所有用户订单数量信息表--分页
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
	 * 获取所有用户订单数量信息表
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

				$this->_countPay 	= null;

				$this->_countDeliver 	= null;

				$this->_countReceipt 	= null;

				$this->_countEvaluate 	= null;

				$this->_countCart 	= null;

			}
}
?>