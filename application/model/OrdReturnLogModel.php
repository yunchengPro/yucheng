<?php
/**
* 退款订单日志表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 15:02:09Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class OrdReturnLogModel {

	protected $_id 	= null;

	protected $_returnno 	= null;

	protected $_orderno 	= null;

	protected $_productid 	= null;

	protected $_skuid 	= null;

	protected $_actionsource 	= null;

	protected $_customerid 	= null;

	protected $_businessid 	= null;

	protected $_orderstatus 	= null;
	
	protected $_content = null;

	protected $_remark 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('OrdReturnLog');
	}

	/**
	 *
	 * 添加退款订单日志表
	 */
// 	public function add() {
// 		$this->_modelObj->_returnno  		= $this->_returnno;
// 		$this->_modelObj->_orderno  		= $this->_orderno;
// 		$this->_modelObj->_productid  		= $this->_productid;
// 		$this->_modelObj->_skuid  		= $this->_skuid;
// 		$this->_modelObj->_actionsource  		= $this->_actionsource;
// 		$this->_modelObj->_customerid  		= $this->_customerid;
// 		$this->_modelObj->_businessid  		= $this->_businessid;
// 		$this->_modelObj->_orderstatus  		= $this->_orderstatus;
// 		$this->_modelObj->_remark  		= $this->_remark;
// 		$this->_modelObj->_addtime  		= $this->_addtime;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->insert($data);
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新退款订单日志表
	 */
	public function modify($updateData, $where) {
// 		$this->_modelObj->_returnno  = $this->_returnno;
// 		$this->_modelObj->_orderno  = $this->_orderno;
// 		$this->_modelObj->_productid  = $this->_productid;
// 		$this->_modelObj->_skuid  = $this->_skuid;
// 		$this->_modelObj->_actionsource  = $this->_actionsource;
// 		$this->_modelObj->_customerid  = $this->_customerid;
// 		$this->_modelObj->_businessid  = $this->_businessid;
// 		$this->_modelObj->_orderstatus  = $this->_orderstatus;
// 		$this->_modelObj->_remark  = $this->_remark;
// 		$this->_modelObj->_addtime  = $this->_addtime;
// 		return $this->_modelObj->modify($id);
	    return $this->_modelObj->update($updateData, $where);
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
	 * 退款订单日志表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}
	
	public function getLogList($where, $field='*', $order='', $otherstr='') {
	    return $this->_modelObj->getList($where, $field, $order, $otherstr);
	}

	/**
	 * 获取所有退款订单日志表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除退款订单日志表
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
	 * 设置退货编号
	 *
	 */
	public function setReturnno($returnno) {
		$this->_returnno = $returnno;
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
	 * 设置商品id
	 *
	 */
	public function setProductid($productid) {
		$this->_productid = $productid;
		return $this;
	}

	/**
	 * 设置商品sku的id
	 *
	 */
	public function setSkuid($skuid) {
		$this->_skuid = $skuid;
		return $this;
	}

	/**
	 * 设置操作来源 1消费者2商家3管理者(待定)
	 *
	 */
	public function setActionsource($actionsource) {
		$this->_actionsource = $actionsource;
		return $this;
	}

	/**
	 * 设置用户操作时 操作id
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置商家操作时 商家id
	 *
	 */
	public function setBusinessid($businessid) {
		$this->_businessid = $businessid;
		return $this;
	}

	/**
	 * 设置退货编号状态(1 审核中, 2 审核失败 3 审核成功 4退款/退货成功 20 用户取消)
	 *
	 */
	public function setOrderstatus($orderstatus) {
		$this->_orderstatus = $orderstatus;
		return $this;
	}
	
	public function setContent($content) {
	    $this->_content = $content;
	    return $this;
	}

	/**
	 * 设置描述
	 *
	 */
	public function setRemark($remark) {
		$this->_remark = $remark;
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
		return new OrdReturnLogDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>