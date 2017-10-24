<?php
/**
* 角色登录切换日志表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-11 15:49:05Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusRoleLogModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_role 	= null;

	protected $_lasttime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusRoleLog');
	}

	/**
	 *
	 * 添加角色登录切换日志表
	 */
// 	public function add() {
// 		$this->_modelObj->_customerid  		= $this->_customerid;
// 		$this->_modelObj->_role  		= $this->_role;
// 		$this->_modelObj->_lasttime  		= $this->_lasttime;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新角色登录切换日志表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_customerid  = $this->_customerid;
// 		$this->_modelObj->_role  = $this->_role;
// 		$this->_modelObj->_lasttime  = $this->_lasttime;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
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
	
    /*
	 * 获取单条数据
	 * $where 可以是字符串 也可以是数组
	 */
	public function getRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
	}

	/**
	 *
	 * 角色登录切换日志表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有角色登录切换日志表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除角色登录切换日志表
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
	 * 设置用户id
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置用户角色1普通消费者2创业者3供应商4实体店5地市代理6区县代理
	 *
	 */
	public function setRole($role) {
		$this->_role = $role;
		return $this;
	}

	/**
	 * 设置最后操作时间
	 *
	 */
	public function setLasttime($lasttime) {
		$this->_lasttime = $lasttime;
		return $this;
	}

	public static function getModelObj() {
		return new CusRoleLogDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>