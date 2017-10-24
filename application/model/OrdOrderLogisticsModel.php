<?php
/**
* 订单物流信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:30:09Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class OrdOrderLogisticsModel {

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_mobile 	= null;

	protected $_realname 	= null;

	protected $_cityId 	= null;

	protected $_city 	= null;

	protected $_address 	= null;

	protected $_leavemessage 	= null;

	protected $_expressType 	= null;

	protected $_expressid 	= null;

	protected $_expressName 	= null;

	protected $_expressNo 	= null;

	protected $_deliveryTime 	= null;

	protected $_orderremark 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('OrdOrderLogistics');
	}

	/**
	 *
	 * 添加订单物流信息表
	 */
// 	public function add($data) {
		
// 		$this->setData($data);
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->_modelObj->insert($data);
	}

	public function setData($data){
		foreach($data as $k=>$v){
			$this->_modelObj->{"_".$k} = $v;
		}
	}

	/**
	 *
	 * 更新订单物流信息表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_orderno  = $this->_orderno;
// 		$this->_modelObj->_mobile  = $this->_mobile;
// 		$this->_modelObj->_realname  = $this->_realname;
// 		$this->_modelObj->_cityId  = $this->_cityId;
// 		$this->_modelObj->_city  = $this->_city;
// 		$this->_modelObj->_address  = $this->_address;
// 		$this->_modelObj->_leavemessage  = $this->_leavemessage;
// 		$this->_modelObj->_expressType  = $this->_expressType;
// 		$this->_modelObj->_expressid  = $this->_expressid;
// 		$this->_modelObj->_expressName  = $this->_expressName;
// 		$this->_modelObj->_expressNo  = $this->_expressNo;
// 		$this->_modelObj->_deliveryTime  = $this->_deliveryTime;
// 		$this->_modelObj->_orderremark  = $this->_orderremark;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}
	
	public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
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

	/** 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }

	/**
	 *
	 * 订单物流信息表列表
	 */
// 	public function getList($page, $pagesize) {
// 		return $this->_modelObj->getAllForPage($page, $pagesize);
// 	}

    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
        return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
    }

	/**
	 * 获取所有订单物流信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除订单物流信息表
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
	 * 设置收货人手机号码
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置收货人真实姓名
	 *
	 */
	public function setRealname($realname) {
		$this->_realname = $realname;
		return $this;
	}

	/**
	 * 设置收货所在地区编号
	 *
	 */
	public function setCityId($cityId) {
		$this->_cityId = $cityId;
		return $this;
	}

	/**
	 * 设置收货所在地区名称
	 *
	 */
	public function setCity($city) {
		$this->_city = $city;
		return $this;
	}

	/**
	 * 设置收货详细地址
	 *
	 */
	public function setAddress($address) {
		$this->_address = $address;
		return $this;
	}

	/**
	 * 设置用户留言
	 *
	 */
	public function setLeavemessage($leavemessage) {
		$this->_leavemessage = $leavemessage;
		return $this;
	}

	/**
	 * 设置快递方式，1快递，2平邮，3EMS
	 *
	 */
	public function setExpressType($expressType) {
		$this->_expressType = $expressType;
		return $this;
	}

	/**
	 * 设置快递公司ID
	 *
	 */
	public function setExpressid($expressid) {
		$this->_expressid = $expressid;
		return $this;
	}

	/**
	 * 设置快递公司名称
	 *
	 */
	public function setExpressName($expressName) {
		$this->_expressName = $expressName;
		return $this;
	}

	/**
	 * 设置快递单号
	 *
	 */
	public function setExpressNo($expressNo) {
		$this->_expressNo = $expressNo;
		return $this;
	}

	/**
	 * 设置发货时间
	 *
	 */
	public function setDeliveryTime($deliveryTime) {
		$this->_deliveryTime = $deliveryTime;
		return $this;
	}

	/**
	 * 设置订单说明
	 *
	 */
	public function setOrderremark($orderremark) {
		$this->_orderremark = $orderremark;
		return $this;
	}

	public static function getModelObj() {
		return new OrdOrderLogisticsDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>