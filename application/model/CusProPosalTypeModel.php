<?php
/**
* 投诉类型表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:12:46Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusProPosalTypeModel {

	protected $_id 	= null;

	protected $_name 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusProPosalType');
	}

	/**
	 *
	 * 添加投诉类型表
	 */
	public function add() {
		$this->_modelObj->_name  		= $this->_name;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新投诉类型表
	 */
	public function modify($id) {
		$this->_modelObj->_name  = $this->_name;
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
	 * 投诉类型表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有投诉类型表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除投诉类型表
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
	 * 设置投诉类型名称
	 *
	 */
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	public static function getModelObj() {
		return new CusProPosalTypeDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>