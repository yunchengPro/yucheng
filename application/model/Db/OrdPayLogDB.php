<?php
/**
*
* 支付回调日志表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdPayLog.php 10319 2017-03-03 16:39:01Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdPayLogDB extends MysqlDb {

	protected $_tableName = "ord_pay_log";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_paytype 	= null;

	protected $_content 	= null;

	protected $_addtime 	= null;

	protected $_status 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入支付回调日志表
	 */
	public function add() {
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_paytype) && $data['paytype'] = $this->_paytype;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_status) && $data['status'] = $this->_status;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新支付回调日志表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_paytype) && $data['paytype'] = $this->_paytype;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_status) && $data['status'] = $this->_status;
		return $this->update($data);
	}

	/**
	 * 删除支付回调日志表
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
	 * 获取所有支付回调日志表--分页
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
	 * 获取所有支付回调日志表
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

				$this->_paytype 	= null;

				$this->_content 	= null;

				$this->_addtime 	= null;

				$this->_status 	= null;

			}
}
?>