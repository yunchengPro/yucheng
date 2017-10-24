<?php
/**
*
* 用户角色表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: BusSysRole.php 10319 2017-03-13 15:03:26Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class SysRoleDB extends MysqlDb {

	protected $_tableName = "sys_role";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_name 	= null;

	protected $_remark 	= null;

	protected $_menuids 	= null;

	protected $_enable 	= null;

	protected $_createby 	= null;

	protected $_createtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户角色表
	 */
	public function add() {
		! is_null($this->_name) && $data['name'] = $this->_name;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_menuids) && $data['menuids'] = $this->_menuids;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_createby) && $data['createby'] = $this->_createby;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户角色表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_name) && $data['name'] = $this->_name;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_menuids) && $data['menuids'] = $this->_menuids;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_createby) && $data['createby'] = $this->_createby;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
		return $this->update($data);
	}

	/**
	 * 删除用户角色表
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
	 * 获取所有用户角色表--分页
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
	 * 获取所有用户角色表
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

				$this->_name 	= null;

				$this->_remark 	= null;

				$this->_menuids 	= null;

				$this->_enable 	= null;

				$this->_createby 	= null;

				$this->_createtime 	= null;

			}
}
?>