<?php
/**
* 支付回调日志表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:39:01Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class OrdPayLogModel {

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_paytype 	= null;

	protected $_content 	= null;

	protected $_addtime 	= null;

	protected $_status 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('OrdPayLog');
	}

	/**
	 *
	 * 添加支付回调日志表
	 */
	public function add() {
		$this->_modelObj->_orderno  		= $this->_orderno;
		$this->_modelObj->_paytype  		= $this->_paytype;
		$this->_modelObj->_content  		= $this->_content;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_status  		= $this->_status;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新支付回调日志表
	 */
	public function modify($id) {
		$this->_modelObj->_orderno  = $this->_orderno;
		$this->_modelObj->_paytype  = $this->_paytype;
		$this->_modelObj->_content  = $this->_content;
		$this->_modelObj->_addtime  = $this->_addtime;
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
	 * 支付回调日志表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有支付回调日志表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除支付回调日志表
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
	 * 设置支付方式
	 *
	 */
	public function setPaytype($paytype) {
		$this->_paytype = $paytype;
		return $this;
	}

	/**
	 * 设置返回内容
	 *
	 */
	public function setContent($content) {
		$this->_content = $content;
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
	 * 设置处理状态0未处理1已处理
	 *
	 */
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}

	public static function getModelObj() {
		return new OrdPayLogDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>