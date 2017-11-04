<?php
/**
* 运营中心推荐信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 11:51:03Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class RoleRecoCityModel {

	protected $_id 	= null;
	
	protected $_cusRoleId = null;

	protected $_type 	= null;

	protected $_realname 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_areaCode 	= null;

	protected $_address 	= null;

	protected $_longitude 	= null;

	protected $_latitude 	= null;

	protected $_joinCode 	= null;

	protected $_joinArea 	= null;

	protected $_companyName 	= null;

	protected $_chargeIdnumber 	= null;

	protected $_chargeName 	= null;

	protected $_chargeMobile 	= null;

	protected $_corporationName 	= null;

	protected $_corporationIdnumber 	= null;

	protected $_businessLicence 	= null;

	protected $_licenceImage 	= null;

	protected $_examinetime 	= null;
	
	protected $_remark = null;

	protected $_status 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('RoleRecoCity');
	}

	/**
	 *
	 * 添加运营中心推荐信息表
	 */
// 	public function add() {
// 		$this->_modelObj->_type  		= $this->_type;
// 		$this->_modelObj->_realname  		= $this->_realname;
// 		$this->_modelObj->_mobile  		= $this->_mobile;
// 		$this->_modelObj->_area  		= $this->_area;
// 		$this->_modelObj->_areaCode  		= $this->_areaCode;
// 		$this->_modelObj->_address  		= $this->_address;
// 		$this->_modelObj->_longitude  		= $this->_longitude;
// 		$this->_modelObj->_latitude  		= $this->_latitude;
// 		$this->_modelObj->_joinCode  		= $this->_joinCode;
// 		$this->_modelObj->_joinArea  		= $this->_joinArea;
// 		$this->_modelObj->_companyName  		= $this->_companyName;
// 		$this->_modelObj->_chargeIdnumber  		= $this->_chargeIdnumber;
// 		$this->_modelObj->_chargeName  		= $this->_chargeName;
// 		$this->_modelObj->_chargeMobile  		= $this->_chargeMobile;
// 		$this->_modelObj->_corporationName  		= $this->_corporationName;
// 		$this->_modelObj->_corporationIdnumber  		= $this->_corporationIdnumber;
// 		$this->_modelObj->_businessLicence  		= $this->_businessLicence;
// 		$this->_modelObj->_licenceImage  		= $this->_licenceImage;
// 		$this->_modelObj->_examinetime  		= $this->_examinetime;
// 		$this->_modelObj->_status  		= $this->_status;
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
	 * 更新运营中心推荐信息表
	 */
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
	
	/*
	 * 获取单条数据
	 * $where 可以是字符串 也可以是数组
	 */
	public function getRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
	}

	/**
	 *
	 * 运营中心推荐信息表列表
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
	 * 获取所有运营中心推荐信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除运营中心推荐信息表
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
	 * 设置所属类型(1为个人 2为公司)
	 *
	 */
	public function setType($type) {
		$this->_type = $type;
		return $this;
	}

	/**
	 * 设置姓名(个人)
	 *
	 */
	public function setRealname($realname) {
		$this->_realname = $realname;
		return $this;
	}

	/**
	 * 设置手机号码(个人)
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置所属省市区
	 *
	 */
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
	 * 设置详细地址
	 *
	 */
	public function setAddress($address) {
		$this->_address = $address;
		return $this;
	}

	/**
	 * 设置经度
	 *
	 */
	public function setLongitude($longitude) {
		$this->_longitude = $longitude;
		return $this;
	}

	/**
	 * 设置纬度
	 *
	 */
	public function setLatitude($latitude) {
		$this->_latitude = $latitude;
		return $this;
	}

	/**
	 * 设置加盟城市编号(,拼接)
	 *
	 */
	public function setJoinCode($joinCode) {
		$this->_joinCode = $joinCode;
		return $this;
	}

	/**
	 * 设置加盟城市(,拼接)
	 *
	 */
	public function setJoinArea($joinArea) {
		$this->_joinArea = $joinArea;
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
	 * 设置负责人身份证
	 *
	 */
	public function setChargeIdnumber($chargeIdnumber) {
		$this->_chargeIdnumber = $chargeIdnumber;
		return $this;
	}

	/**
	 * 设置负责人姓名
	 *
	 */
	public function setChargeName($chargeName) {
		$this->_chargeName = $chargeName;
		return $this;
	}

	/**
	 * 设置负责人手机号码
	 *
	 */
	public function setChargeMobile($chargeMobile) {
		$this->_chargeMobile = $chargeMobile;
		return $this;
	}

	/**
	 * 设置法人姓名
	 *
	 */
	public function setCorporationName($corporationName) {
		$this->_corporationName = $corporationName;
		return $this;
	}

	/**
	 * 设置法人身份证
	 *
	 */
	public function setCorporationIdnumber($corporationIdnumber) {
		$this->_corporationIdnumber = $corporationIdnumber;
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
	 * 设置营业执照图片(,拼接)
	 *
	 */
	public function setLicenceImage($licenceImage) {
		$this->_licenceImage = $licenceImage;
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

	/**
	 * 设置添加时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	public static function getModelObj() {
		return new RoleRecoCityDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>