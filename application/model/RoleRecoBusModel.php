<?php
/**
* 牛商推荐信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 11:43:45Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class RoleRecoBusModel {

	protected $_id 	= null;
	
	protected $_cusRoleId = null;

	protected $_companyName 	= null;

	protected $_personCharge 	= null;

	protected $_mobile 	= null;

	protected $_corporation 	= null;
	
	protected $_area = null;

	protected $_areaCode 	= null;

	protected $_companyArea 	= null;

	protected $_longitude 	= null;

	protected $_latitude 	= null;

	protected $_priceType 	= null;

	protected $_idnumber 	= null;

	protected $_businessLicence 	= null;

	protected $_licenceImage 	= null;

	protected $_idnumberImage 	= null;

	protected $_companyLogo 	= null;
	
	protected $_remark = null;

	protected $_status 	= null;
	
	protected $_disable = null;

	protected $_examinetime 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('RoleRecoBus');
	}

	/**
	 *
	 * 添加牛商推荐信息表
	 */
// 	public function add() {
// 		$this->_modelObj->_companyName  		= $this->_companyName;
// 		$this->_modelObj->_personCharge  		= $this->_personCharge;
// 		$this->_modelObj->_mobile  		= $this->_mobile;
// 		$this->_modelObj->_corporation  		= $this->_corporation;
// 		$this->_modelObj->_areaCode  		= $this->_areaCode;
// 		$this->_modelObj->_companyArea  		= $this->_companyArea;
// 		$this->_modelObj->_longitude  		= $this->_longitude;
// 		$this->_modelObj->_latitude  		= $this->_latitude;
// 		$this->_modelObj->_priceType  		= $this->_priceType;
// 		$this->_modelObj->_idnumber  		= $this->_idnumber;
// 		$this->_modelObj->_businessLicence  		= $this->_businessLicence;
// 		$this->_modelObj->_licenceImage  		= $this->_licenceImage;
// 		$this->_modelObj->_idnumberImage  		= $this->_idnumberImage;
// 		$this->_modelObj->_companyLogo  		= $this->_companyLogo;
// 		$this->_modelObj->_status  		= $this->_status;
// 		$this->_modelObj->_examinetime  		= $this->_examinetime;
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
	 * 更新牛商推荐信息表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_companyName  = $this->_companyName;
// 		$this->_modelObj->_personCharge  = $this->_personCharge;
// 		$this->_modelObj->_mobile  = $this->_mobile;
// 		$this->_modelObj->_corporation  = $this->_corporation;
// 		$this->_modelObj->_areaCode  = $this->_areaCode;
// 		$this->_modelObj->_companyArea  = $this->_companyArea;
// 		$this->_modelObj->_longitude  = $this->_longitude;
// 		$this->_modelObj->_latitude  = $this->_latitude;
// 		$this->_modelObj->_priceType  = $this->_priceType;
// 		$this->_modelObj->_idnumber  = $this->_idnumber;
// 		$this->_modelObj->_businessLicence  = $this->_businessLicence;
// 		$this->_modelObj->_licenceImage  = $this->_licenceImage;
// 		$this->_modelObj->_idnumberImage  = $this->_idnumberImage;
// 		$this->_modelObj->_companyLogo  = $this->_companyLogo;
// 		$this->_modelObj->_status  = $this->_status;
// 		$this->_modelObj->_examinetime  = $this->_examinetime;
// 		$this->_modelObj->_addtime  = $this->_addtime;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
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
	
	public function getRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
	}
	
	/*
	 * 获取单条数据
	 * $where 可以是字符串 也可以是数组
	 */
	public function getInfoRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->getRow($where, $fields, $order, $otherstr);
	}

	/**
	 *
	 * 牛商推荐信息表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}
	
	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
	    return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
	}

	/**
	 * 获取所有牛商推荐信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除牛商推荐信息表
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
	
	public function setCusRoleId($cusRoleId) {
	    $this->_cusRoleId = $cusRoleId;
	    return $this;
	}

	/**
	 * 设置公司名称
	 *
	 */
	public function setCompanyName($companyName) {
		$this->_companyName = $companyName;
		return $this;
	}

	/**
	 * 设置负责人姓名
	 *
	 */
	public function setPersonCharge($personCharge) {
		$this->_personCharge = $personCharge;
		return $this;
	}

	/**
	 * 设置联系电话
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置公司法人
	 *
	 */
	public function setCorporation($corporation) {
		$this->_corporation = $corporation;
		return $this;
	}
	
	public function setArea($area) {
	    $this->_area = $area;
	    return $this;
	}

	/**
	 * 设置市区编号
	 *
	 */
	public function setAreaCode($areaCode) {
		$this->_areaCode = $areaCode;
		return $this;
	}

	/**
	 * 设置公司地址
	 *
	 */
	public function setCompanyArea($companyArea) {
		$this->_companyArea = $companyArea;
		return $this;
	}

	/**
	 * 设置公司经度
	 *
	 */
	public function setLongitude($longitude) {
		$this->_longitude = $longitude;
		return $this;
	}

	/**
	 * 设置公司纬度
	 *
	 */
	public function setLatitude($latitude) {
		$this->_latitude = $latitude;
		return $this;
	}

	/**
	 * 设置售价方式(1现金，2现金+牛豆/牛豆) 用,拼接
	 *
	 */
	public function setPriceType($priceType) {
		$this->_priceType = $priceType;
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
	 * 设置营业执照编号
	 *
	 */
	public function setBusinessLicence($businessLicence) {
		$this->_businessLicence = $businessLicence;
		return $this;
	}

	/**
	 * 设置营业执照图片地址
	 *
	 */
	public function setLicenceImage($licenceImage) {
		$this->_licenceImage = $licenceImage;
		return $this;
	}

	/**
	 * 设置身份证图片地址 用,分割
	 *
	 */
	public function setIdnumberImage($idnumberImage) {
		$this->_idnumberImage = $idnumberImage;
		return $this;
	}

	/**
	 * 设置公司logo
	 *
	 */
	public function setCompanyLogo($companyLogo) {
		$this->_companyLogo = $companyLogo;
		return $this;
	}
	
	public function setRemark($remark) {
	    $this->_remark = $remark;
	    return $this;
	}

	/**
	 * 设置审核状态(1为待审核，2为审核通过，3为审核失败)
	 *
	 */
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}
	
	public function setDisabled($disabled) {
	    $this->_disabled = $disabled;
	    return $this;
	}

	/**
	 * 设置审核时间
	 *
	 */
	public function setExaminetime($examinetime) {
		$this->_examinetime = $examinetime;
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
		return new RoleRecoBusDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>