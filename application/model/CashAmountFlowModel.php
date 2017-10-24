<?php
/**
* 用户现金流水记录表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:53:35Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CashAmountFlowModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_flowtype 	= null;

	protected $_amounttype 	= null;

	protected $_direction 	= null;

	protected $_balance 	= null;

	protected $_amount 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CashAmountFlow');
	}

	/**
	 *
	 * 添加用户现金流水记录表
	 */
	public function add() {
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_flowtype  		= $this->_flowtype;
		$this->_modelObj->_amounttype  		= $this->_amounttype;
		$this->_modelObj->_direction  		= $this->_direction;
		$this->_modelObj->_balance  		= $this->_balance;
		$this->_modelObj->_amount  		= $this->_amount;
		$this->_modelObj->_addtime  		= $this->_addtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户现金流水记录表
	 */
	public function modify($id) {
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_flowtype  = $this->_flowtype;
		$this->_modelObj->_amounttype  = $this->_amounttype;
		$this->_modelObj->_direction  = $this->_direction;
		$this->_modelObj->_balance  = $this->_balance;
		$this->_modelObj->_amount  = $this->_amount;
		$this->_modelObj->_addtime  = $this->_addtime;
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
	 * 用户现金流水记录表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有用户现金流水记录表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户现金流水记录表
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
	 * 设置流水类型1订单支付2购买获取3收益分润4代理分享费用5充值
	 *
	 */
	public function setFlowtype($flowtype) {
		$this->_flowtype = $flowtype;
		return $this;
	}

	/**
	 * 设置现金类型1现金2绑定现金3牛币
	 *
	 */
	public function setAmounttype($amounttype) {
		$this->_amounttype = $amounttype;
		return $this;
	}

	/**
	 * 设置1收入2支出
	 *
	 */
	public function setDirection($direction) {
		$this->_direction = $direction;
		return $this;
	}

	/**
	 * 设置当前余额
	 *
	 */
	public function setBalance($balance) {
		$this->_balance = $balance;
		return $this;
	}

	/**
	 * 设置金额|数量
	 *
	 */
	public function setAmount($amount) {
		$this->_amount = $amount;
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

	public static function getModelObj() {
		return new CashAmountFlowDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>