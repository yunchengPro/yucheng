<?php
/**
* 订单详细信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:23:26Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class OrdOrderInfoModel {

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_customerid 	= null;

	protected $_cancelsource 	= null;

	protected $_cancelreason 	= null;

	protected $_auditRemark 	= null;

	protected $_islongdate 	= null;

	protected $_autoDeliveryTime 	= null;

	protected $_actualDeliveryTime 	= null;

	protected $_evaluate 	= null;

	protected $_finishTime 	= null;

	protected $_returnStatus 	= null;

	protected $_returnAmount 	= null;

	protected $_delivertime = null;
	
	protected $_returnBull = null;
	
	protected $_returnTime = null;
	
	protected $_returnSuccessTime = null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('OrdOrderInfo');
	}

	/**
	 *
	 * 添加订单详细信息表
	 */
	public function add($data) {
		/*
		$this->_modelObj->_orderno  		= $this->_orderno;
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_cancelsource  		= $this->_cancelsource;
		$this->_modelObj->_cancelreason  		= $this->_cancelreason;
		$this->_modelObj->_auditRemark  		= $this->_auditRemark;
		$this->_modelObj->_islongdate  		= $this->_islongdate;
		$this->_modelObj->_autoDeliveryTime  		= $this->_autoDeliveryTime;
		$this->_modelObj->_actualDeliveryTime  		= $this->_actualDeliveryTime;
		$this->_modelObj->_evaluate  		= $this->_evaluate;
		$this->_modelObj->_finishTime  		= $this->_finishTime;
		$this->_modelObj->_returnStatus  		= $this->_returnStatus;
		$this->_modelObj->_returnAmount  		= $this->_returnAmount;
		*/
	
		return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新订单详细信息表
	 */
	public function modify($updateData,$where) {
		/*
		$this->_modelObj->_orderno  = $this->_orderno;
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_cancelsource  = $this->_cancelsource;
		$this->_modelObj->_cancelreason  = $this->_cancelreason;
		$this->_modelObj->_auditRemark  = $this->_auditRemark;
		$this->_modelObj->_islongdate  = $this->_islongdate;
		$this->_modelObj->_autoDeliveryTime  = $this->_autoDeliveryTime;
		$this->_modelObj->_actualDeliveryTime  = $this->_actualDeliveryTime;
		$this->_modelObj->_evaluate  = $this->_evaluate;
		$this->_modelObj->_finishTime  = $this->_finishTime;
		$this->_modelObj->_returnStatus  = $this->_returnStatus;
		$this->_modelObj->_returnAmount  = $this->_returnAmount;
		return $this->_modelObj->modify($id);
		*/
		return $this->_modelObj->update($updateData,$where);
	}

	public function getInfoById($id,$field="*"){
		$orderinfo = $this->getById($id,$field);

		if($field!='*' && empty($orderinfo)){

			$fieldarr = explode(",",$field);
			foreach($fieldarr as $v){
				$orderinfo[$v] = '';
			}
		}

		return $orderinfo;
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id = null,$field="*") {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getRow(["id"=>$id],$field);
		return $this->_dataInfo;
	}

	/**
	 * 订单明细中，获取订单的信息
	 * @Author   zhuangqm
	 * @DateTime 2017-03-06T16:09:33+0800
	 * @param    [type]                   $orderid [description]
	 * @return   [type]                            [description]
	 */
	public function getOrdOrderInfo($orderid){
		return $this->getInfoById($orderid,"cancelsource,cancelreason,islongdate,return_status,auto_delivery_time,actual_delivery_time");
	}
	
    /*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getInfoRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }

	/**
	 *
	 * 订单详细信息表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/**
	 * 获取所有订单详细信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除订单详细信息表
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
	 * 设置下单用户ID
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置取消来源1消费者2后台管理人员
	 *
	 */
	public function setCancelsource($cancelsource) {
		$this->_cancelsource = $cancelsource;
		return $this;
	}

	/**
	 * 设置取消原因
	 *
	 */
	public function setCancelreason($cancelreason) {
		$this->_cancelreason = $cancelreason;
		return $this;
	}

	/**
	 * 设置取消订单审核说明
	 *
	 */
	public function setAuditRemark($auditRemark) {
		$this->_auditRemark = $auditRemark;
		return $this;
	}

	/**
	 * 设置是否延长收货时间，0未延长，1延长
	 *
	 */
	public function setIslongdate($islongdate) {
		$this->_islongdate = $islongdate;
		return $this;
	}

	/**
	 * 设置自动确认收货时间
	 *
	 */
	public function setAutoDeliveryTime($autoDeliveryTime) {
		$this->_autoDeliveryTime = $autoDeliveryTime;
		return $this;
	}

	/**
	 * 设置实际收货时间
	 *
	 */
	public function setActualDeliveryTime($actualDeliveryTime) {
		$this->_actualDeliveryTime = $actualDeliveryTime;
		return $this;
	}

	/**
	 * 设置是否评价0未评价1人工评价2自动评价
	 *
	 */
	public function setEvaluate($evaluate) {
		$this->_evaluate = $evaluate;
		return $this;
	}

	/**
	 * 设置订单完结时间
	 *
	 */
	public function setFinishTime($finishTime) {
		$this->_finishTime = $finishTime;
		return $this;
	}

	/**
	 * 设置退货状态，1有退货，0无退货
	 *
	 */
	public function setReturnStatus($returnStatus) {
		$this->_returnStatus = $returnStatus;
		return $this;
	}

	/**
	 * 设置退款金额
	 *
	 */
	public function setReturnAmount($returnAmount) {
		$this->_returnAmount = $returnAmount;
		return $this;
	}
	
	public function setDeliverTime($delivertime) {
	    $this->_delivertime = $delivertime;
	    return $this;
	}
	
	public function setReturnBull($returnBull) {
	    $this->_returnBull = $returnBull;
	    return $this;
	}
	
	public function setReturnTime($returnTime) {
	    $this->_returnTime = $returnTime;
	    return $this;
	}
	
	public function setReturnSuccessTime($returnSuccessTime) {
	    $this->_returnSuccessTime = $returnSuccessTime;
	    return $this;
	}

	public static function getModelObj() {
		return new OrdOrderInfoDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}

	/*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }

    
    /*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
    	return $this->_modelObj->update($updateData,$where,$limit);
    }
}
?>