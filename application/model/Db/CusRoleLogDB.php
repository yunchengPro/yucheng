<?php
/**
*
* 角色登录切换日志表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusRoleLog.php 10319 2017-03-11 15:49:05Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusRoleLogDB extends MysqlDb {

	protected $_tableName = "cus_role_log";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_role 	= null;

	protected $_lasttime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入角色登录切换日志表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_role) && $data['role'] = $this->_role;
		! is_null($this->_lasttime) && $data['lasttime'] = $this->_lasttime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新角色登录切换日志表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_role) && $data['role'] = $this->_role;
		! is_null($this->_lasttime) && $data['lasttime'] = $this->_lasttime;
		return $this->update($data);
	}

	/**
	 * 删除角色登录切换日志表
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
	 * 获取所有角色登录切换日志表--分页
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
	 * 获取所有角色登录切换日志表
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

				$this->_role 	= null;

				$this->_lasttime 	= null;

			}
}
?>