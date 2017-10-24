<?php
/**
* 商品分类类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 18:07:41Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class ProCategotyModel {

	protected $_id 	= null;

	protected $_parentId 	= null;

	protected $_name 	= null;

	protected $_status 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('ProCategoty');
	}

	/**
	 *
	 * 添加商品分类
	 */
	public function add() {
		$this->_modelObj->_parentId  		= $this->_parentId;
		$this->_modelObj->_name  		= $this->_name;
		$this->_modelObj->_status  		= $this->_status;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商品分类
	 */
	public function modify($id) {
		$this->_modelObj->_parentId  = $this->_parentId;
		$this->_modelObj->_name  = $this->_name;
		$this->_modelObj->_status  = $this->_status;
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
	 * 商品分类列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有商品分类
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除商品分类
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
	 * 设置上级分类id
	 *
	 */
	public function setParentId($parentId) {
		$this->_parentId = $parentId;
		return $this;
	}

	/**
	 * 设置分类名称
	 *
	 */
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * 设置状态
	 *
	 */
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}

	public static function getModelObj() {
		return new ProCategotyDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>