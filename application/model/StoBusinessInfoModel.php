<?php
/**
* 实体商家信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:02:21Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoBusinessInfoModel {

	protected $_id 	= null;

	protected $_businessname 	= null;

	protected $_categoryid 	= null;

	protected $_categoryname 	= null;

	protected $_typeid 	= null;

	protected $_area 	= null;

	protected $_areaCode 	= null;

	protected $_lngx 	= null;

	protected $_laty 	= null;
	
	protected $_metroId = null;
	
	protected $_districtId = null;
	
	protected $_nearbyVillage = null;

	protected $_salecount 	= null;

	protected $_busstartime 	= null;

	protected $_busendtime 	= null;
	
	protected $_licenceImage = null;
	
	protected $_idnumberImage = null;

	protected $_scores 	= null;

	protected $_isparking 	= null;

	protected $_iswifi 	= null;

	protected $_isdelivery 	= null;

	protected $_enable 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoBusinessInfo');
	}

	/**
	 *
	 * 添加实体商家信息表
	 */
// 	public function add() {
// 		$this->_modelObj->_businessname  		= $this->_businessname;
// 		$this->_modelObj->_categoryid  		= $this->_categoryid;
// 		$this->_modelObj->_categoryname  		= $this->_categoryname;
// 		$this->_modelObj->_typeid  		= $this->_typeid;
// 		$this->_modelObj->_area  		= $this->_area;
// 		$this->_modelObj->_areaCode  		= $this->_areaCode;
// 		$this->_modelObj->_lngx  		= $this->_lngx;
// 		$this->_modelObj->_laty  		= $this->_laty;
// 		$this->_modelObj->_metroId        = $this->_metroId;
// 		$this->_modelObj->_districtId     = $this->_districtId;
// 		$this->_modelObj->_nearbyVillage  = $this->_nearbyVillage;
// 		$this->_modelObj->_salecount  		= $this->_salecount;
// 		$this->_modelObj->_busstartime  		= $this->_busstartime;
// 		$this->_modelObj->_busendtime  		= $this->_busendtime;
// 		$this->_modelObj->_licenceImage   = $this->_licenceImage;
// 		$this->_modelObj->_idnumberImage  = $this->_idnumberImage;
// 		$this->_modelObj->_scores  		= $this->_scores;
// 		$this->_modelObj->_isparking  		= $this->_isparking;
// 		$this->_modelObj->_iswifi  		= $this->_iswifi;
// 		$this->_modelObj->_isdelivery  		= $this->_isdelivery;
// 		$this->_modelObj->_enable  		= $this->_enable;
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
	 * 更新实体商家信息表
	 */
	public function modify($data, $where) {
// 		$this->_modelObj->_businessname  = $this->_businessname;
// 		$this->_modelObj->_categoryid  = $this->_categoryid;
// 		$this->_modelObj->_categoryname  = $this->_categoryname;
// 		$this->_modelObj->_typeid  = $this->_typeid;
// 		$this->_modelObj->_area  = $this->_area;
// 		$this->_modelObj->_areaCode  = $this->_areaCode;
// 		$this->_modelObj->_lngx  = $this->_lngx;
// 		$this->_modelObj->_laty  = $this->_laty;
// 		$this->_modelObj->_metroId        = $this->_metroId;
// 		$this->_modelObj->_districtId     = $this->_districtId;
// 		$this->_modelObj->_nearbyVillage  = $this->_nearbyVillage;
// 		$this->_modelObj->_salecount  = $this->_salecount;
// 		$this->_modelObj->_busstartime  = $this->_busstartime;
// 		$this->_modelObj->_busendtime  = $this->_busendtime;
// 		$this->_modelObj->_licenceImage   = $this->_licenceImage;
// 		$this->_modelObj->_idnumberImage  = $this->_idnumberImage;
// 		$this->_modelObj->_scores  = $this->_scores;
// 		$this->_modelObj->_isparking  = $this->_isparking;
// 		$this->_modelObj->_iswifi  = $this->_iswifi;
// 		$this->_modelObj->_isdelivery  = $this->_isdelivery;
// 		$this->_modelObj->_enable  = $this->_enable;
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
	 * 实体商家信息表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
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
	 * 获取所有实体商家信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除实体商家信息表
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
	 * 设置商家名称
	 *
	 */
	public function setBusinessname($businessname) {
		$this->_businessname = $businessname;
		return $this;
	}

	/**
	 * 设置所属分类
	 *
	 */
	public function setCategoryid($categoryid) {
		$this->_categoryid = $categoryid;
		return $this;
	}

	/**
	 * 设置分类名称
	 *
	 */
	public function setCategoryname($categoryname) {
		$this->_categoryname = $categoryname;
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
	 * 设置所在地区名称
	 *
	 */
	public function setArea($area) {
		$this->_area = $area;
		return $this;
	}

	/**
	 * 设置地区ID
	 *
	 */
	public function setAreaCode($areaCode) {
		$this->_areaCode = $areaCode;
		return $this;
	}

	/**
	 * 设置经度
	 *
	 */
	public function setLngx($lngx) {
		$this->_lngx = $lngx;
		return $this;
	}

	/**
	 * 设置纬度
	 *
	 */
	public function setLaty($laty) {
		$this->_laty = $laty;
		return $this;
	}
	
	public function setMetroId($metroId) {
	    $this->_metroId = $metroId;
	    return $this;
	}
	
	public function setDistrictId($districtId) {
	    $this->_districtId = $districtId;
	    return $this;
	}
	
	public function setNearbyVillage($nearbyVillage) {
	    $this->_nearbyVillage = $nearbyVillage;
	    return $this;
	}

	/**
	 * 设置销售量
	 *
	 */
	public function setSalecount($salecount) {
		$this->_salecount = $salecount;
		return $this;
	}

	/**
	 * 设置营业开始时间
	 *
	 */
	public function setBusstartime($busstartime) {
		$this->_busstartime = $busstartime;
		return $this;
	}

	/**
	 * 设置营业结束时间
	 *
	 */
	public function setBusendtime($busendtime) {
		$this->_busendtime = $busendtime;
		return $this;
	}
	
	public function setLicenceImage($licenceImage) {
	    $this->_licenceImage = $licenceImage;
	    return $this;
	}
	
	public function setIdnumberImage($idnumberImage) {
	    $this->_idnumberImage = $idnumberImage;
	    return $this;
	}

	/**
	 * 设置店铺评分（1-5）
	 *
	 */
	public function setScores($scores) {
		$this->_scores = $scores;
		return $this;
	}

	/**
	 * 设置是否免费停车1是0否
	 *
	 */
	public function setIsparking($isparking) {
		$this->_isparking = $isparking;
		return $this;
	}

	/**
	 * 设置是否免费wifi1是0否
	 *
	 */
	public function setIswifi($iswifi) {
		$this->_iswifi = $iswifi;
		return $this;
	}

	/**
	 * 设置是否送货上门wifi1是0否
	 *
	 */
	public function setIsdelivery($isdelivery) {
		$this->_isdelivery = $isdelivery;
		return $this;
	}

	/**
	 * 设置商家状态1为启用2为禁用
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
		return $this;
	}

	public static function getModelObj() {
		return new StoBusinessInfoDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>