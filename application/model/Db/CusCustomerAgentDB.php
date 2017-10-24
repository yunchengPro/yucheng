<?php
/**
*
* 区域代理用户信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusCustomerAgent.php 10319 2017-03-03 16:04:19Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusCustomerAgentDB extends MysqlDb {

	protected $_tableName = "cus_customer_agent";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_agentType 	= null;

	protected $_agentAmount 	= null;

	protected $_introducerid 	= null;

	protected $_introducername 	= null;

	protected $_introducerAmoun 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入区域代理用户信息表
	 */
	public function add() {
		! is_null($this->_agentType) && $data['agent_type'] = $this->_agentType;
		! is_null($this->_agentAmount) && $data['agent_amount'] = $this->_agentAmount;
		! is_null($this->_introducerid) && $data['introducerid'] = $this->_introducerid;
		! is_null($this->_introducername) && $data['introducername'] = $this->_introducername;
		! is_null($this->_introducerAmoun) && $data['introducer_amoun'] = $this->_introducerAmoun;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新区域代理用户信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_agentType) && $data['agent_type'] = $this->_agentType;
		! is_null($this->_agentAmount) && $data['agent_amount'] = $this->_agentAmount;
		! is_null($this->_introducerid) && $data['introducerid'] = $this->_introducerid;
		! is_null($this->_introducername) && $data['introducername'] = $this->_introducername;
		! is_null($this->_introducerAmoun) && $data['introducer_amoun'] = $this->_introducerAmoun;
		return $this->update($data);
	}

	/**
	 * 删除区域代理用户信息表
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
	 * 获取所有区域代理用户信息表--分页
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
	 * 获取所有区域代理用户信息表
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

				$this->_agentType 	= null;

				$this->_agentAmount 	= null;

				$this->_introducerid 	= null;

				$this->_introducername 	= null;

				$this->_introducerAmoun 	= null;

			}
}
?>