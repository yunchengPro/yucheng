<?php
/**
* 实体商家表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 15:59:03Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoBusinessBaseinfoModel {

	protected $_id 	= null;

	protected $_businessCode 	= null;

	protected $_businessname 	= null;

	protected $_idnumber 	= null;

	protected $_mobile 	= null;

	protected $_address 	= null;

	protected $_servicetel 	= null;

	protected $_description 	= null;

	protected $_businessintro 	= null;

	protected $_typeid 	= null;

	protected $_typename 	= null;

	protected $_discount 	= null;

	protected $_scorescount 	= null;

	protected $_delivery 	= null;

	protected $_productcount 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoBusinessBaseinfo');
	}

	/**
	 * [getBusinessCode 生成商家平台号]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-10T17:57:54+0800
	 * @return   [type]                   [description]
	 */
	public function getBusinessCode(){
		return "NNHST".date("YmdHis").rand(100000,999999);
	}

	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 添加实体商家表
	 */
	public function add() {
		$this->_modelObj->_businessCode  		= $this->_businessCode;
		$this->_modelObj->_businessname  		= $this->_businessname;
		$this->_modelObj->_idnumber  		= $this->_idnumber;
		$this->_modelObj->_mobile  		= $this->_mobile;
		$this->_modelObj->_address  		= $this->_address;
		$this->_modelObj->_servicetel  		= $this->_servicetel;
		$this->_modelObj->_description  		= $this->_description;
		$this->_modelObj->_businessintro  		= $this->_businessintro;
		$this->_modelObj->_typeid  		= $this->_typeid;
		$this->_modelObj->_typename  		= $this->_typename;
		$this->_modelObj->_discount  		= $this->_discount;
		$this->_modelObj->_scorescount  		= $this->_scorescount;
		$this->_modelObj->_delivery  		= $this->_delivery;
		$this->_modelObj->_productcount  		= $this->_productcount;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新实体商家表
	 */
	public function modify($data, $where) {
// 		$this->_modelObj->_businessCode  = $this->_businessCode;
// 		$this->_modelObj->_businessname  = $this->_businessname;
// 		$this->_modelObj->_idnumber  = $this->_idnumber;
// 		$this->_modelObj->_mobile  = $this->_mobile;
// 		$this->_modelObj->_address  = $this->_address;
// 		$this->_modelObj->_servicetel  = $this->_servicetel;
// 		$this->_modelObj->_description  = $this->_description;
// 		$this->_modelObj->_businessintro  = $this->_businessintro;
// 		$this->_modelObj->_typeid  = $this->_typeid;
// 		$this->_modelObj->_typename  = $this->_typename;
// 		$this->_modelObj->_discount  = $this->_discount;
// 		$this->_modelObj->_scorescount  = $this->_scorescount;
// 		$this->_modelObj->_delivery  = $this->_delivery;
// 		$this->_modelObj->_productcount  = $this->_productcount;
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

	/*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }
    
	/**
	 *
	 * 实体商家表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr='') {
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageList($where,$field='',$order='',$flag=1,$page='',$pagesize=''){
    	return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
    }

    /*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
    	return $this->_modelObj->update($updateData,$where,$limit);
    }

	/**
	 * 获取所有实体商家表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除实体商家表
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
	 * 设置商家平台号
	 *
	 */
	public function setBusinessCode($businessCode) {
		$this->_businessCode = $businessCode;
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
	 * 设置身份证号码
	 *
	 */
	public function setIdnumber($idnumber) {
		$this->_idnumber = $idnumber;
		return $this;
	}

	/**
	 * 设置联系手机
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置店铺地址
	 *
	 */
	public function setAddress($address) {
		$this->_address = $address;
		return $this;
	}

	/**
	 * 设置服务电话,多个电话用“,”分隔
	 *
	 */
	public function setServicetel($servicetel) {
		$this->_servicetel = $servicetel;
		return $this;
	}

	/**
	 * 设置商家详细介绍
	 *
	 */
	public function setDescription($description) {
		$this->_description = $description;
		return $this;
	}

	/**
	 * 设置简单描述
	 *
	 */
	public function setBusinessintro($businessintro) {
		$this->_businessintro = $businessintro;
		return $this;
	}

	/**
	 * 设置所属类型ID
	 *
	 */
	public function setTypeid($typeid) {
		$this->_typeid = $typeid;
		return $this;
	}

	/**
	 * 设置所属类型
	 *
	 */
	public function setTypename($typename) {
		$this->_typename = $typename;
		return $this;
	}

	/**
	 * 设置折扣，“%”单位
	 *
	 */
	public function setDiscount($discount) {
		$this->_discount = $discount;
		return $this;
	}

	/**
	 * 设置评分次数
	 *
	 */
	public function setScorescount($scorescount) {
		$this->_scorescount = $scorescount;
		return $this;
	}

	/**
	 * 设置起送费
	 *
	 */
	public function setDelivery($delivery) {
		$this->_delivery = $delivery;
		return $this;
	}

	/**
	 * 设置商品数量
	 *
	 */
	public function setProductcount($productcount) {
		$this->_productcount = $productcount;
		return $this;
	}

	public static function getModelObj() {
		return new StoBusinessBaseinfoDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>