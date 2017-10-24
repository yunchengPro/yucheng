<?php
/**
* 分润记录表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-20 12:06:49Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class AmoProfitModel {

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_orderAmount 	= null;

	protected $_totalAmount 	= null;

	protected $_profitAmount 	= null;

	protected $_formula 	= null;

	protected $_profitobj 	= null;

	protected $_profitobjid 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('AmoProfit');
	}

	public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * 添加分润记录表
	 */
	public function add() {
		$this->_modelObj->_orderno  		= $this->_orderno;
		$this->_modelObj->_orderAmount  		= $this->_orderAmount;
		$this->_modelObj->_totalAmount  		= $this->_totalAmount;
		$this->_modelObj->_profitAmount  		= $this->_profitAmount;
		$this->_modelObj->_formula  		= $this->_formula;
		$this->_modelObj->_profitobj  		= $this->_profitobj;
		$this->_modelObj->_profitobjid  		= $this->_profitobjid;
		$this->_modelObj->_addtime  		= $this->_addtime;
		return $this->_modelObj->add();
	}

	public function insert($insertData) {
		$insertData['userid'] = $insertData['userid']!=''?$insertData['userid']:0;
    	return $this->_modelObj->insert($insertData);
    }

	/**
	 *
	 * 更新分润记录表
	 */
	public function modify($id) {
		$this->_modelObj->_orderno  = $this->_orderno;
		$this->_modelObj->_orderAmount  = $this->_orderAmount;
		$this->_modelObj->_totalAmount  = $this->_totalAmount;
		$this->_modelObj->_profitAmount  = $this->_profitAmount;
		$this->_modelObj->_formula  = $this->_formula;
		$this->_modelObj->_profitobj  = $this->_profitobj;
		$this->_modelObj->_profitobjid  = $this->_profitobjid;
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
	 * 分润记录表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/**
	 * 获取所有分润记录表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	public function getRow($where,$field='*',$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}



	/**
	 *
	 * 删除分润记录表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}

	public function delete($where,$limit=''){
		return $this->_modelObj->delete($where,$limit);
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
	 * 设置订单金额
	 *
	 */
	public function setOrderAmount($orderAmount) {
		$this->_orderAmount = $orderAmount;
		return $this;
	}

	/**
	 * 设置分润总金额
	 *
	 */
	public function setTotalAmount($totalAmount) {
		$this->_totalAmount = $totalAmount;
		return $this;
	}

	/**
	 * 设置分润金额
	 *
	 */
	public function setProfitAmount($profitAmount) {
		$this->_profitAmount = $profitAmount;
		return $this;
	}

	/**
	 * 设置分润公式
	 *
	 */
	public function setFormula($formula) {
		$this->_formula = $formula;
		return $this;
	}

	/**
	 * 设置分润对象买家上级，买家上上级，买家绑定实体店...
	 *
	 */
	public function setProfitobj($profitobj) {
		$this->_profitobj = $profitobj;
		return $this;
	}

	/**
	 * 设置分润对象ID
	 *
	 */
	public function setProfitobjid($profitobjid) {
		$this->_profitobjid = $profitobjid;
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
		return new AmoProfitDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>