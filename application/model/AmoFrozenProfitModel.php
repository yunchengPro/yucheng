<?php
/**
* 用户收益现金余额冻结表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-20 11:57:28Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class AmoFrozenProfitModel {

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_customerid 	= null;

	protected $_amount 	= null;

	protected $_status 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('AmoFrozenProfit');
	}

	public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * 添加用户收益现金余额冻结表
	 */
	public function add() {
		$this->_modelObj->_orderno  		= $this->_orderno;
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_amount  		= $this->_amount;
		$this->_modelObj->_status  		= $this->_status;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户收益现金余额冻结表
	 */
	public function modify($id) {
		$this->_modelObj->_orderno  = $this->_orderno;
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_amount  = $this->_amount;
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
	 * 用户收益现金余额冻结表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有用户收益现金余额冻结表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户收益现金余额冻结表
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
	 * 设置订单号
	 *
	 */
	public function setOrderno($orderno) {
		$this->_orderno = $orderno;
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
	 * 设置金额|数量
	 *
	 */
	public function setAmount($amount) {
		$this->_amount = $amount;
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
		return new AmoFrozenProfitDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>