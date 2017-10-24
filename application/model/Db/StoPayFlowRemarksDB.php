<?php
/**
*
* 店铺收款流水备注表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoPayFlowRemarks.php 10319 2017-03-09 16:15:38Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoPayFlowRemarksDB extends MysqlDb {

	protected $_tableName = "sto_pay_flow_remarks";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_remarks 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入店铺收款流水备注表
	 */
	public function add() {
		! is_null($this->_remarks) && $data['remarks'] = $this->_remarks;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新店铺收款流水备注表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_remarks) && $data['remarks'] = $this->_remarks;
		return $this->update($data);
	}

	/**
	 * 删除店铺收款流水备注表
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
	 * 获取所有店铺收款流水备注表--分页
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
	 * 获取所有店铺收款流水备注表
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

				$this->_remarks 	= null;

			}
}
?>