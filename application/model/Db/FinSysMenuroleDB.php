<?php
/**
*
* 菜单与角色关系表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: BusSysMenurole.php 10319 2017-03-13 15:01:46Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class FinSysMenuroleDB extends MysqlDb {

	protected $_tableName = "fin_sys_menurole";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_menuid 	= null;

	protected $_roleid 	= null;

	protected $_isdelete 	= null;

	protected $_createby 	= null;

	protected $_createtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入菜单与角色关系表
	 */
	public function add() {
		! is_null($this->_menuid) && $data['menuid'] = $this->_menuid;
		! is_null($this->_roleid) && $data['roleid'] = $this->_roleid;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
		! is_null($this->_createby) && $data['createby'] = $this->_createby;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新菜单与角色关系表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_menuid) && $data['menuid'] = $this->_menuid;
		! is_null($this->_roleid) && $data['roleid'] = $this->_roleid;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
		! is_null($this->_createby) && $data['createby'] = $this->_createby;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
		return $this->update($data);
	}

	/**
	 * 删除菜单与角色关系表
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
	 * 获取所有菜单与角色关系表--分页
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
	 * 获取所有菜单与角色关系表
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

				$this->_menuid 	= null;

				$this->_roleid 	= null;

				$this->_isdelete 	= null;

				$this->_createby 	= null;

				$this->_createtime 	= null;

			}
}
?>