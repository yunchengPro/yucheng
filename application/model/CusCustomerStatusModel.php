<?php
/**
* 用户状态表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:09:12Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusCustomerStatusModel {

	protected $_id 	= null;

	protected $_iscomment 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusCustomerStatus');
	}

	/**
	 *
	 * 添加用户状态表
	 */
	public function add() {
		$this->_modelObj->_iscomment  		= $this->_iscomment;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户状态表
	 */
	public function modify($id) {
		$this->_modelObj->_iscomment  = $this->_iscomment;
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
	 * 用户状态表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有用户状态表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户状态表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置用户id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置是否禁言0否1是
	 *
	 */
	public function setIscomment($iscomment) {
		$this->_iscomment = $iscomment;
		return $this;
	}

	public static function getModelObj() {
		return new CusCustomerStatusDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>