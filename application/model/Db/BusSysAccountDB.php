<?php
/**
*
* 商家用户表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: BusSysAccount.php 10319 2017-03-13 14:56:36Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class BusSysAccountDB extends MysqlDb {

	protected $_tableName = "bus_sys_account";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_businessRoleid 	= null;

	protected $_username 	= null;

	protected $_userpwd 	= null;

	protected $_enable 	= null;

	protected $_createtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商家用户表
	 */
	public function add() {
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessRoleid) && $data['business_roleid'] = $this->_businessRoleid;
		! is_null($this->_username) && $data['username'] = $this->_username;
		! is_null($this->_userpwd) && $data['userpwd'] = $this->_userpwd;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商家用户表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessRoleid) && $data['business_roleid'] = $this->_businessRoleid;
		! is_null($this->_username) && $data['username'] = $this->_username;
		! is_null($this->_userpwd) && $data['userpwd'] = $this->_userpwd;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
		return $this->update($data);
	}

	/**
	 * 删除商家用户表
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
	 * 获取所有商家用户表--分页
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
	 * 获取所有商家用户表
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

				$this->_businessid 	= null;

				$this->_businessRoleid 	= null;

				$this->_username 	= null;

				$this->_userpwd 	= null;

				$this->_enable 	= null;

				$this->_createtime 	= null;

			}
}
?>