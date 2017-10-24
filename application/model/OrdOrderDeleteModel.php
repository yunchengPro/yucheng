<?php
/**
* 退款订单日志表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 15:03:13Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class OrdOrderDeleteModel {

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_customerid 	= null;

	protected $_custName 	= null;

	protected $_actualfreight 	= null;

	protected $_productcount 	= null;

	protected $_productamount 	= null;

	protected $_bullamount 	= null;

	protected $_totalamount 	= null;

	protected $_addtime 	= null;

	protected $_orderstatus 	= null;

	protected $_businessid 	= null;

	protected $_businessname 	= null;

	protected $_evaluate 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('OrdOrderDelete');
	}

	/**
	 *
	 * 添加退款订单日志表
	 */
	public function add() {
		$this->_modelObj->_orderno  		= $this->_orderno;
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_custName  		= $this->_custName;
		$this->_modelObj->_actualfreight  		= $this->_actualfreight;
		$this->_modelObj->_productcount  		= $this->_productcount;
		$this->_modelObj->_productamount  		= $this->_productamount;
		$this->_modelObj->_bullamount  		= $this->_bullamount;
		$this->_modelObj->_totalamount  		= $this->_totalamount;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_orderstatus  		= $this->_orderstatus;
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_businessname  		= $this->_businessname;
		$this->_modelObj->_evaluate  		= $this->_evaluate;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新退款订单日志表
	 */
	public function modify($id) {
		$this->_modelObj->_orderno  = $this->_orderno;
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_custName  = $this->_custName;
		$this->_modelObj->_actualfreight  = $this->_actualfreight;
		$this->_modelObj->_productcount  = $this->_productcount;
		$this->_modelObj->_productamount  = $this->_productamount;
		$this->_modelObj->_bullamount  = $this->_bullamount;
		$this->_modelObj->_totalamount  = $this->_totalamount;
		$this->_modelObj->_addtime  = $this->_addtime;
		$this->_modelObj->_orderstatus  = $this->_orderstatus;
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_businessname  = $this->_businessname;
		$this->_modelObj->_evaluate  = $this->_evaluate;
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
	 * 退款订单日志表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
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
	 * 设置用户名
	 *
	 */
	public function setCustName($custName) {
		$this->_custName = $custName;
		return $this;
	}

	/**
	 * 设置实际运费
	 *
	 */
	public function setActualfreight($actualfreight) {
		$this->_actualfreight = $actualfreight;
		return $this;
	}

	/**
	 * 设置商品总数量
	 *
	 */
	public function setProductcount($productcount) {
		$this->_productcount = $productcount;
		return $this;
	}

	/**
	 * 设置商品总金额
	 *
	 */
	public function setProductamount($productamount) {
		$this->_productamount = $productamount;
		return $this;
	}

	/**
	 * 设置商品牛币积分
	 *
	 */
	public function setBullamount($bullamount) {
		$this->_bullamount = $bullamount;
		return $this;
	}

	/**
	 * 设置订单总金额=实际运费+商品总金额
	 *
	 */
	public function setTotalamount($totalamount) {
		$this->_totalamount = $totalamount;
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
	 * 设置退货编号状态(1 审核中, 2 审核失败 3 审核成功 4退款/退货成功 20 用户取消)
	 *
	 */
	public function setOrderstatus($orderstatus) {
		$this->_orderstatus = $orderstatus;
		return $this;
	}

	/**
	 * 设置商家id
	 *
	 */
	public function setBusinessid($businessid) {
		$this->_businessid = $businessid;
		return $this;
	}

	/**
	 * 设置商家名称
	 *
	 */
	public function setBusinessname($businessname) {
		$this->_businessname = $businessname;
		return $this;
	}

	/**
	 * 设置是否评价0未评价1个人评价2自动评价
	 *
	 */
	public function setEvaluate($evaluate) {
		$this->_evaluate = $evaluate;
		return $this;
	}

	public static function getModelObj() {
		return new OrdOrderDeleteDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}

	/*
    * 写入数据
    * $insertData = array() 如果ID是自增 返回生成主键ID
    */
	public function insert($insertData){
		return $this->_modelObj->insert($insertData);
	}
}
?>