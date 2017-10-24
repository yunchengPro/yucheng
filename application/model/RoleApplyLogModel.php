<?php
/**
* 角色申请记录表(牛人 牛创客)类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 11:55:17Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class RoleApplyLogModel {

	protected $_id 	= null;
	
	protected $_customerid = null;
	
	protected $_roleType = null;

	protected $_realname 	= null;

	protected $_idnumber 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_address 	= null;

	protected $_areaCode 	= null;

	protected $_instrodcermobile 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('RoleApplyLog');
	}

	/**
	 *
	 * 添加角色申请记录表(牛人 牛创客)
	 */
// 	public function add() {
// 	    $this->_modelObj->_roleType        = $this->_roleType;
// 		$this->_modelObj->_realname  		= $this->_realname;
// 		$this->_modelObj->_idnumber  		= $this->_idnumber;
// 		$this->_modelObj->_mobile  		= $this->_mobile;
// 		$this->_modelObj->_area  		= $this->_area;
// 		$this->_modelObj->_address  		= $this->_address;
// 		$this->_modelObj->_areaCode  		= $this->_areaCode;
// 		$this->_modelObj->_instrodcermobile  		= $this->_instrodcermobile;
// 		$this->_modelObj->_addtime  		= $this->_addtime;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新角色申请记录表(牛人 牛创客)
	 */
// 	public function modify($id) {
// 	    $this->_modelObj->_roleType  = $this->_roleType;
// 		$this->_modelObj->_realname  = $this->_realname;
// 		$this->_modelObj->_idnumber  = $this->_idnumber;
// 		$this->_modelObj->_mobile  = $this->_mobile;
// 		$this->_modelObj->_area  = $this->_area;
// 		$this->_modelObj->_address  = $this->_address;
// 		$this->_modelObj->_areaCode  = $this->_areaCode;
// 		$this->_modelObj->_instrodcermobile  = $this->_instrodcermobile;
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
	
	public function getInfoRow($where, $field='*', $order='', $otherstr='') {
	    return $this->getRow($where,$field,$order,$otherstr);
	}
	
	public function getRow($where, $field='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}
	
	//开启事务
	public function startTrans(){
	    return $this->_modelObj->startTrans();
	}
	
	//提交事务
	public function commit(){
	    return $this->_modelObj->commit();
	}
	
	//事务回滚
	public function rollback(){
	    return $this->_modelObj->rollback();
	}

	/**
	 *
	 * 角色申请记录表(牛人 牛创客)列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有角色申请记录表(牛人 牛创客)
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * 删除角色申请记录表(牛人 牛创客)
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
	
	public function setCustomerid($customerid) {
	    $this->_customerid = $customerid;
	    return $this;
	}
	
	public function setRoleType($roleType) {
	    $this->_roleType = $roleType;
	    return $this;
	}

	/**
	 * 设置姓名
	 *
	 */
	public function setRealname($realname) {
		$this->_realname = $realname;
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
	 * 设置申请人手机号码
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
	 * 设置详细地址
	 *
	 */
	public function setAddress($address) {
		$this->_address = $address;
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
	 * 设置推荐人手机号码
	 *
	 */
	public function setInstrodcermobile($instrodcermobile) {
		$this->_instrodcermobile = $instrodcermobile;
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
		return new RoleApplyLogDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>