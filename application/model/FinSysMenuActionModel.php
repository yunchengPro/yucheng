<?php
/**
* 菜单操作按钮类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-13 15:00:24Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class FinSysMenuActionModel {

	protected $_id 	= null;

	protected $_menuid 	= null;

	protected $_actionname 	= null;

	protected $_rightvalue 	= null;

	protected $_createby 	= null;

	protected $_createtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('FinSysMenuAction');
	}

	/**
	 *
	 * 添加菜单操作按钮
	 */
	public function add() {
		$this->_modelObj->_menuid  		= $this->_menuid;
		$this->_modelObj->_actionname  		= $this->_actionname;
		$this->_modelObj->_rightvalue  		= $this->_rightvalue;
		$this->_modelObj->_createby  		= $this->_createby;
		$this->_modelObj->_createtime  		= $this->_createtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新菜单操作按钮
	 */
	public function modify($id) {
		$this->_modelObj->_menuid  = $this->_menuid;
		$this->_modelObj->_actionname  = $this->_actionname;
		$this->_modelObj->_rightvalue  = $this->_rightvalue;
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
	 * 菜单操作按钮列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有菜单操作按钮
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除菜单操作按钮
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
	 * 设置对应菜单id
	 *
	 */
	public function setMenuid($menuid) {
		$this->_menuid = $menuid;
		return $this;
	}

	/**
	 * 设置操作名称
	 *
	 */
	public function setActionname($actionname) {
		$this->_actionname = $actionname;
		return $this;
	}

	/**
	 * 设置权值
	 *
	 */
	public function setRightvalue($rightvalue) {
		$this->_rightvalue = $rightvalue;
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
		return new BusSysMenuActionDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>