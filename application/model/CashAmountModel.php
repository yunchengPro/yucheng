<?php
/**
* 用户余额表表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:51:36Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CashAmountModel {

	protected $_id 	= null;

	protected $_cash 	= null;

	protected $_bindcash 	= null;

	protected $_bullcash 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CashAmount');
	}

	/**
	 *
	 * 添加用户余额表表
	 */
	public function add() {
		$this->_modelObj->_cash  		= $this->_cash;
		$this->_modelObj->_bindcash  		= $this->_bindcash;
		$this->_modelObj->_bullcash  		= $this->_bullcash;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户余额表表
	 */
	public function modify($id) {
		$this->_modelObj->_cash  = $this->_cash;
		$this->_modelObj->_bindcash  = $this->_bindcash;
		$this->_modelObj->_bullcash  = $this->_bullcash;
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
	 * 用户余额表表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有用户余额表表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户余额表表
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
	 * 设置现金余额
	 *
	 */
	public function setCash($cash) {
		$this->_cash = $cash;
		return $this;
	}

	/**
	 * 设置绑定现金余额
	 *
	 */
	public function setBindcash($bindcash) {
		$this->_bindcash = $bindcash;
		return $this;
	}

	/**
	 * 设置牛币数
	 *
	 */
	public function setBullcash($bullcash) {
		$this->_bullcash = $bullcash;
		return $this;
	}

	public static function getModelObj() {
		return new CashAmountDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>