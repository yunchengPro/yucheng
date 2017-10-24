<?php
/**
* 用户角色表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-13 15:03:26Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class BusSysRoleModel {

	protected $_id 	= null;

	protected $_name 	= null;

	protected $_remark 	= null;

	protected $_menuids 	= null;

	protected $_enable 	= null;

	protected $_createby 	= null;

	protected $_createtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('BusSysRole');
	}

	/**
	 *
	 * 添加用户角色表
	 */
	public function add() {
		$this->_modelObj->_name  		= $this->_name;
		$this->_modelObj->_remark  		= $this->_remark;
		$this->_modelObj->_menuids  		= $this->_menuids;
		$this->_modelObj->_enable  		= $this->_enable;
		$this->_modelObj->_createby  		= $this->_createby;
		$this->_modelObj->_createtime  		= $this->_createtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户角色表
	 */
	public function modify($id) {
		$this->_modelObj->_name  = $this->_name;
		$this->_modelObj->_remark  = $this->_remark;
		$this->_modelObj->_menuids  = $this->_menuids;
		$this->_modelObj->_enable  = $this->_enable;
		$this->_modelObj->_createby  = $this->_createby;
		$this->_modelObj->_createtime  = $this->_createtime;
		return $this->_modelObj->modify($id);
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id = null) {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getById($this->_id);
		return $this->_dataInfo;
	}

	/**
	 *
	 * 用户角色表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有用户角色表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户角色表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置主键id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置角色的名称
	 *
	 */
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * 设置角色的描述
	 *
	 */
	public function setRemark($remark) {
		$this->_remark = $remark;
		return $this;
	}

	/**
	 * 设置对应菜单id目前不用
	 *
	 */
	public function setMenuids($menuids) {
		$this->_menuids = $menuids;
		return $this;
	}

	/**
	 * 设置是否启用
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
		return $this;
	}

	/**
	 * 设置创建人
	 *
	 */
	public function setCreateby($createby) {
		$this->_createby = $createby;
		return $this;
	}

	/**
	 * 设置创建时间
	 *
	 */
	public function setCreatetime($createtime) {
		$this->_createtime = $createtime;
		return $this;
	}

	public static function getModelObj() {
		return new BusSysRoleDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>