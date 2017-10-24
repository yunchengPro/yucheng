<?php
/**
*
* 汇总现金流水表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: AmoFlowCash.php 10319 2017-03-20 11:15:54Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class BonusOrderDB extends MysqlDb {

	protected $_tableName = "bonus_order";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入汇总现金流水表
	 */
	public function add() {
		
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新汇总现金流水表
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
	 * 删除汇总现金流水表
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
	 * 获取所有汇总现金流水表--分页
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
	 * 获取所有汇总现金流水表
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

				$this->_flowid 	= null;

				$this->_usertype 	= null;

				$this->_userid 	= null;

				$this->_orderno 	= null;

				$this->_flowtype 	= null;

				$this->_direction 	= null;

				$this->_amount 	= null;

				$this->_finalAmount 	= null;

				$this->_beforeamount 	= null;

				$this->_afteramount 	= null;

				$this->_remark 	= null;

				$this->_flowtime 	= null;

				$this->_isshow 	= null;

			}
}
?>