<?php
/**
*
* 退款订单日志表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdReturnLog.php 10319 2017-03-09 15:02:09Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdReturnLogDB extends MysqlDb {

	protected $_tableName = "ord_return_log";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_returnno 	= null;

	protected $_orderno 	= null;

	protected $_productid 	= null;

	protected $_skuid 	= null;

	protected $_actionsource 	= null;

	protected $_customerid 	= null;

	protected $_businessid 	= null;

	protected $_orderstatus 	= null;
	
	protected $_content = null;

	protected $_remark 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入退款订单日志表
	 */
	public function add() {
		! is_null($this->_returnno) && $data['returnno'] = $this->_returnno;
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_actionsource) && $data['actionsource'] = $this->_actionsource;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_orderstatus) && $data['orderstatus'] = $this->_orderstatus;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新退款订单日志表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_returnno) && $data['returnno'] = $this->_returnno;
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_actionsource) && $data['actionsource'] = $this->_actionsource;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_orderstatus) && $data['orderstatus'] = $this->_orderstatus;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除退款订单日志表
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
	 * 获取所有退款订单日志表--分页
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
	 * 获取所有退款订单日志表
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

				$this->_returnno 	= null;

				$this->_orderno 	= null;

				$this->_productid 	= null;

				$this->_skuid 	= null;

				$this->_actionsource 	= null;

				$this->_customerid 	= null;

				$this->_businessid 	= null;

				$this->_orderstatus 	= null;
				
				$this->_content = null;

				$this->_remark 	= null;

				$this->_addtime 	= null;

			}
}
?>