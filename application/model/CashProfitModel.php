<?php
/**
* 分润表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:46:56Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CashProfitModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_orderno 	= null;

	protected $_productid 	= null;

	protected $_parentid 	= null;

	protected $_parentprofit 	= null;

	protected $_grandpaid 	= null;

	protected $_grandpaprofit 	= null;

	protected $_bindbusinessid 	= null;

	protected $_bindbusinessprofit 	= null;

	protected $_businessSelfEntrepreid 	= null;

	protected $_businessSelfEntrepreprofit 	= null;

	protected $_businessParentEntrepreid 	= null;

	protected $_businessParentEntrepreprofit 	= null;

	protected $_businessGrandpaEntrepreid 	= null;

	protected $_businessGrandpaEntrepreprofit 	= null;

	protected $_factoryentrepreid 	= null;

	protected $_factoryentrepreprofit 	= null;

	protected $_factoryparententrepreid 	= null;

	protected $_factoryparententrepreprofit 	= null;

	protected $_companyprofit 	= null;

	protected $_procedure 	= null;

	protected $_charitable 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CashProfit');
	}

	/**
	 *
	 * 添加分润表
	 */
	public function add() {
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_orderno  		= $this->_orderno;
		$this->_modelObj->_productid  		= $this->_productid;
		$this->_modelObj->_parentid  		= $this->_parentid;
		$this->_modelObj->_parentprofit  		= $this->_parentprofit;
		$this->_modelObj->_grandpaid  		= $this->_grandpaid;
		$this->_modelObj->_grandpaprofit  		= $this->_grandpaprofit;
		$this->_modelObj->_bindbusinessid  		= $this->_bindbusinessid;
		$this->_modelObj->_bindbusinessprofit  		= $this->_bindbusinessprofit;
		$this->_modelObj->_businessSelfEntrepreid  		= $this->_businessSelfEntrepreid;
		$this->_modelObj->_businessSelfEntrepreprofit  		= $this->_businessSelfEntrepreprofit;
		$this->_modelObj->_businessParentEntrepreid  		= $this->_businessParentEntrepreid;
		$this->_modelObj->_businessParentEntrepreprofit  		= $this->_businessParentEntrepreprofit;
		$this->_modelObj->_businessGrandpaEntrepreid  		= $this->_businessGrandpaEntrepreid;
		$this->_modelObj->_businessGrandpaEntrepreprofit  		= $this->_businessGrandpaEntrepreprofit;
		$this->_modelObj->_factoryentrepreid  		= $this->_factoryentrepreid;
		$this->_modelObj->_factoryentrepreprofit  		= $this->_factoryentrepreprofit;
		$this->_modelObj->_factoryparententrepreid  		= $this->_factoryparententrepreid;
		$this->_modelObj->_factoryparententrepreprofit  		= $this->_factoryparententrepreprofit;
		$this->_modelObj->_companyprofit  		= $this->_companyprofit;
		$this->_modelObj->_procedure  		= $this->_procedure;
		$this->_modelObj->_charitable  		= $this->_charitable;
		$this->_modelObj->_addtime  		= $this->_addtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新分润表
	 */
	public function modify($id) {
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_orderno  = $this->_orderno;
		$this->_modelObj->_productid  = $this->_productid;
		$this->_modelObj->_parentid  = $this->_parentid;
		$this->_modelObj->_parentprofit  = $this->_parentprofit;
		$this->_modelObj->_grandpaid  = $this->_grandpaid;
		$this->_modelObj->_grandpaprofit  = $this->_grandpaprofit;
		$this->_modelObj->_bindbusinessid  = $this->_bindbusinessid;
		$this->_modelObj->_bindbusinessprofit  = $this->_bindbusinessprofit;
		$this->_modelObj->_businessSelfEntrepreid  = $this->_businessSelfEntrepreid;
		$this->_modelObj->_businessSelfEntrepreprofit  = $this->_businessSelfEntrepreprofit;
		$this->_modelObj->_businessParentEntrepreid  = $this->_businessParentEntrepreid;
		$this->_modelObj->_businessParentEntrepreprofit  = $this->_businessParentEntrepreprofit;
		$this->_modelObj->_businessGrandpaEntrepreid  = $this->_businessGrandpaEntrepreid;
		$this->_modelObj->_businessGrandpaEntrepreprofit  = $this->_businessGrandpaEntrepreprofit;
		$this->_modelObj->_factoryentrepreid  = $this->_factoryentrepreid;
		$this->_modelObj->_factoryentrepreprofit  = $this->_factoryentrepreprofit;
		$this->_modelObj->_factoryparententrepreid  = $this->_factoryparententrepreid;
		$this->_modelObj->_factoryparententrepreprofit  = $this->_factoryparententrepreprofit;
		$this->_modelObj->_companyprofit  = $this->_companyprofit;
		$this->_modelObj->_procedure  = $this->_procedure;
		$this->_modelObj->_charitable  = $this->_charitable;
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
	 * 分润表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有分润表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除分润表
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
	 * 设置上级id
	 *
	 */
	public function setParentid($parentid) {
		$this->_parentid = $parentid;
		return $this;
	}

	/**
	 * 设置买家上级收益
	 *
	 */
	public function setParentprofit($parentprofit) {
		$this->_parentprofit = $parentprofit;
		return $this;
	}

	/**
	 * 设置买家上上级id
	 *
	 */
	public function setGrandpaid($grandpaid) {
		$this->_grandpaid = $grandpaid;
		return $this;
	}

	/**
	 * 设置买家上上级收益
	 *
	 */
	public function setGrandpaprofit($grandpaprofit) {
		$this->_grandpaprofit = $grandpaprofit;
		return $this;
	}

	/**
	 * 设置买家绑定的实体店id
	 *
	 */
	public function setBindbusinessid($bindbusinessid) {
		$this->_bindbusinessid = $bindbusinessid;
		return $this;
	}

	/**
	 * 设置买家绑定店收益
	 *
	 */
	public function setBindbusinessprofit($bindbusinessprofit) {
		$this->_bindbusinessprofit = $bindbusinessprofit;
		return $this;
	}

	/**
	 * 设置买家绑定店本体创业者id
	 *
	 */
	public function setBusinessSelfEntrepreid($businessSelfEntrepreid) {
		$this->_businessSelfEntrepreid = $businessSelfEntrepreid;
		return $this;
	}

	/**
	 * 设置买家绑定店本体创业者收益
	 *
	 */
	public function setBusinessSelfEntrepreprofit($businessSelfEntrepreprofit) {
		$this->_businessSelfEntrepreprofit = $businessSelfEntrepreprofit;
		return $this;
	}

	/**
	 * 设置买家供应商上级创业者id
	 *
	 */
	public function setBusinessParentEntrepreid($businessParentEntrepreid) {
		$this->_businessParentEntrepreid = $businessParentEntrepreid;
		return $this;
	}

	/**
	 * 设置买家绑定店本体上级创业者收益
	 *
	 */
	public function setBusinessParentEntrepreprofit($businessParentEntrepreprofit) {
		$this->_businessParentEntrepreprofit = $businessParentEntrepreprofit;
		return $this;
	}

	/**
	 * 设置买家绑定店本体上上级创业者id
	 *
	 */
	public function setBusinessGrandpaEntrepreid($businessGrandpaEntrepreid) {
		$this->_businessGrandpaEntrepreid = $businessGrandpaEntrepreid;
		return $this;
	}

	/**
	 * 设置买家绑定店本体上上级创业者收益
	 *
	 */
	public function setBusinessGrandpaEntrepreprofit($businessGrandpaEntrepreprofit) {
		$this->_businessGrandpaEntrepreprofit = $businessGrandpaEntrepreprofit;
		return $this;
	}

	/**
	 * 设置买家供应商上级创业者id
	 *
	 */
	public function setFactoryentrepreid($factoryentrepreid) {
		$this->_factoryentrepreid = $factoryentrepreid;
		return $this;
	}

	/**
	 * 设置买家供应商上级创业者收益
	 *
	 */
	public function setFactoryentrepreprofit($factoryentrepreprofit) {
		$this->_factoryentrepreprofit = $factoryentrepreprofit;
		return $this;
	}

	/**
	 * 设置买家供应商上上级创业者id
	 *
	 */
	public function setFactoryparententrepreid($factoryparententrepreid) {
		$this->_factoryparententrepreid = $factoryparententrepreid;
		return $this;
	}

	/**
	 * 设置买家供应商上上级创业者收益
	 *
	 */
	public function setFactoryparententrepreprofit($factoryparententrepreprofit) {
		$this->_factoryparententrepreprofit = $factoryparententrepreprofit;
		return $this;
	}

	/**
	 * 设置公司收益
	 *
	 */
	public function setCompanyprofit($companyprofit) {
		$this->_companyprofit = $companyprofit;
		return $this;
	}

	/**
	 * 设置手续费用
	 *
	 */
	public function setProcedure($procedure) {
		$this->_procedure = $procedure;
		return $this;
	}

	/**
	 * 设置慈善费用
	 *
	 */
	public function setCharitable($charitable) {
		$this->_charitable = $charitable;
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
		return new CashProfitDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>