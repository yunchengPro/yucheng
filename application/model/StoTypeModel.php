<?php
/**
* 实体店类型表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:23:15Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoTypeModel {

	protected $_id 	= null;

	protected $_typename 	= null;

	protected $_sort 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoType');
	}

	/**
	 *
	 * 添加实体店类型表
	 */
	public function add() {
		$this->_modelObj->_typename  		= $this->_typename;
		$this->_modelObj->_sort  		= $this->_sort;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新实体店类型表
	 */
	public function modify($id) {
		$this->_modelObj->_typename  = $this->_typename;
		$this->_modelObj->_sort  = $this->_sort;
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
	 * 实体店类型表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有实体店类型表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除实体店类型表
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
	 * 设置类型名称
	 *
	 */
	public function setTypename($typename) {
		$this->_typename = $typename;
		return $this;
	}

	/**
	 * 设置排序
	 *
	 */
	public function setSort($sort) {
		$this->_sort = $sort;
		return $this;
	}

	public static function getModelObj() {
		return new StoTypeDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>