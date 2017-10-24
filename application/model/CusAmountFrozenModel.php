<?php
/**
* 用户资金流失冻结表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:01:36Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusAmountFrozenModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_cashType 	= null;

	protected $_frozenType 	= null;

	protected $_frozenObject 	= null;

	protected $_cashCount 	= null;

	protected $_status 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusAmountFrozen');
	}

	/**
	 *
	 * 添加用户资金流失冻结表
	 */
	public function add() {
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_cashType  		= $this->_cashType;
		$this->_modelObj->_frozenType  		= $this->_frozenType;
		$this->_modelObj->_frozenObject  		= $this->_frozenObject;
		$this->_modelObj->_cashCount  		= $this->_cashCount;
		$this->_modelObj->_status  		= $this->_status;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户资金流失冻结表
	 */
	public function modify($id) {
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_cashType  = $this->_cashType;
		$this->_modelObj->_frozenType  = $this->_frozenType;
		$this->_modelObj->_frozenObject  = $this->_frozenObject;
		$this->_modelObj->_cashCount  = $this->_cashCount;
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
	 * 用户资金流失冻结表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有用户资金流失冻结表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户资金流失冻结表
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
	 * 设置客户id
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置资金类型1现金2绑定现金3牛币
	 *
	 */
	public function setCashType($cashType) {
		$this->_cashType = $cashType;
		return $this;
	}

	/**
	 * 设置资金冻结类型1订单支付
	 *
	 */
	public function setFrozenType($frozenType) {
		$this->_frozenType = $frozenType;
		return $this;
	}

	/**
	 * 设置资金冻结来源如订单编号
	 *
	 */
	public function setFrozenObject($frozenObject) {
		$this->_frozenObject = $frozenObject;
		return $this;
	}

	/**
	 * 设置冻结金额|数量
	 *
	 */
	public function setCashCount($cashCount) {
		$this->_cashCount = $cashCount;
		return $this;
	}

	/**
	 * 设置冻结状态1冻结中2已使用3已返还
	 *
	 */
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}

	public static function getModelObj() {
		return new CusAmountFrozenDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>