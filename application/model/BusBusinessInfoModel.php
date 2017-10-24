<?php
/**
* 商家基本信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:16:36Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class BusBusinessInfoModel {

	protected $_id 	= null;

	protected $_businesstype 	= null;

	protected $_businessname 	= null;

	protected $_businesslogo 	= null;

	protected $_idnumber 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_areaCode 	= null;

	protected $_address 	= null;

	protected $_servicetel 	= null;

	protected $_description 	= null;

	protected $_businessintro 	= null;

	protected $_lngx 	= null;

	protected $_laty 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('BusBusinessInfo');
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 添加商家基本信息表
	 */
	public function add() {
		$this->_modelObj->_businesstype  		= $this->_businesstype;
		$this->_modelObj->_businessname  		= $this->_businessname;
		$this->_modelObj->_businesslogo  		= $this->_businesslogo;
		$this->_modelObj->_idnumber  		= $this->_idnumber;
		$this->_modelObj->_mobile  		= $this->_mobile;
		$this->_modelObj->_area  		= $this->_area;
		$this->_modelObj->_areaCode  		= $this->_areaCode;
		$this->_modelObj->_address  		= $this->_address;
		$this->_modelObj->_servicetel  		= $this->_servicetel;
		$this->_modelObj->_description  		= $this->_description;
		$this->_modelObj->_businessintro  		= $this->_businessintro;
		$this->_modelObj->_lngx  		= $this->_lngx;
		$this->_modelObj->_laty  		= $this->_laty;
		$this->_modelObj->_addtime  		= $this->_addtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商家基本信息表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_businesstype  = $this->_businesstype;
// 		$this->_modelObj->_businessname  = $this->_businessname;
// 		$this->_modelObj->_businesslogo  = $this->_businesslogo;
// 		$this->_modelObj->_idnumber  = $this->_idnumber;
// 		$this->_modelObj->_mobile  = $this->_mobile;
// 		$this->_modelObj->_area  = $this->_area;
// 		$this->_modelObj->_areaCode  = $this->_areaCode;
// 		$this->_modelObj->_address  = $this->_address;
// 		$this->_modelObj->_servicetel  = $this->_servicetel;
// 		$this->_modelObj->_description  = $this->_description;
// 		$this->_modelObj->_businessintro  = $this->_businessintro;
// 		$this->_modelObj->_lngx  = $this->_lngx;
// 		$this->_modelObj->_laty  = $this->_laty;
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

	/**
	 * [getRow description]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-07T17:43:36+0800
	 * @param    [type]                   $where    [description]
	 * @param    string                   $field    [description]
	 * @param    string                   $order    [description]
	 * @param    string                   $otherstr [description]
	 * @return   [type]                             [description]
	 */
	public function getRow($where,$field='*',$order='',$otherstr=''){
		return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}
	
	/**
	 *
	 * 商家基本信息表列表
	 */
	// public function  getList($where,$field,$order,$limit,$offset,$otherstr){
	// 	return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	// }

	/*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/*
     * 分页列表
     * $flag = 0 表示不返回总条数
     */
    public function pageList($where,$field='*',$order='',$flag=1){
        return $this->_modelObj->pageList($where,$field,$order,$flag);
    }

	/**
	 * 获取所有商家基本信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除商家基本信息表
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
	 * 设置商家类型
	 *
	 */
	public function setBusinesstype($businesstype) {
		$this->_businesstype = $businesstype;
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
	 * 设置商家店铺logo
	 *
	 */
	public function setBusinesslogo($businesslogo) {
		$this->_businesslogo = $businesslogo;
		return $this;
	}

	/**
	 * 设置管理员身份证
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
	 * 设置所在地区名称
	 *
	 */
	public function setArea($area) {
		$this->_area = $area;
		return $this;
	}

	/**
	 * 设置地区编号
	 *
	 */
	public function setAreaCode($areaCode) {
		$this->_areaCode = $areaCode;
		return $this;
	}

	/**
	 * 设置街道地址
	 *
	 */
	public function setAddress($address) {
		$this->_address = $address;
		return $this;
	}

	/**
	 * 设置服务电话
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

	/**
	 * 设置添加时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	public static function getModelObj() {
		return new BusBusinessInfoDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
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