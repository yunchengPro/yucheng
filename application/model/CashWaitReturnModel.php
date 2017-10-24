<?php
/**
* 用户余额使用明细表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:48:53Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CashWaitReturnModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_orderid 	= null;

	protected $_cashnumber 	= null;

	protected $_bindnumber 	= null;

	protected $_bullnumber 	= null;

	protected $_addtime 	= null;

	protected $_finishtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CashWaitReturn');
	}

	/**
	 *
	 * 添加用户余额使用明细表
	 */
	public function add() {
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_orderid  		= $this->_orderid;
		$this->_modelObj->_cashnumber  		= $this->_cashnumber;
		$this->_modelObj->_bindnumber  		= $this->_bindnumber;
		$this->_modelObj->_bullnumber  		= $this->_bullnumber;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_finishtime  		= $this->_finishtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户余额使用明细表
	 */
	public function modify($id) {
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_orderid  = $this->_orderid;
		$this->_modelObj->_cashnumber  = $this->_cashnumber;
		$this->_modelObj->_bindnumber  = $this->_bindnumber;
		$this->_modelObj->_bullnumber  = $this->_bullnumber;
		$this->_modelObj->_addtime  = $this->_addtime;
		$this->_modelObj->_finishtime  = $this->_finishtime;
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
	 * 用户余额使用明细表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有用户余额使用明细表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户余额使用明细表
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
	 * 设置订单号
	 *
	 */
	public function setOrderid($orderid) {
		$this->_orderid = $orderid;
		return $this;
	}

	/**
	 * 设置消耗的金额数量
	 *
	 */
	public function setCashnumber($cashnumber) {
		$this->_cashnumber = $cashnumber;
		return $this;
	}

	/**
	 * 设置消耗的绑金数量
	 *
	 */
	public function setBindnumber($bindnumber) {
		$this->_bindnumber = $bindnumber;
		return $this;
	}

	/**
	 * 设置消耗的牛币数量
	 *
	 */
	public function setBullnumber($bullnumber) {
		$this->_bullnumber = $bullnumber;
		return $this;
	}

	/**
	 * 设置添加时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	/**
	 * 设置完成时间
	 *
	 */
	public function setFinishtime($finishtime) {
		$this->_finishtime = $finishtime;
		return $this;
	}

	public static function getModelObj() {
		return new CashWaitReturnDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>