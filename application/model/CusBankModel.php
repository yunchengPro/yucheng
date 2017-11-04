<?php
/**
* 用户银行记录表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-22 09:49:01Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusBankModel {

	protected $_id 	= null;

	protected $_typeId 	= null;
	
	protected $_bankTypeName = null;

	protected $_accountType 	= null;

	protected $_accountName 	= null;

	protected $_accountNumber 	= null;

	protected $_branch 	= null;

	protected $_mobile 	= null;

	protected $_sort 	= null;

	protected $_isDefault 	= null;

	protected $_enable 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusBank');
	}

	/**
	 *
	 * 添加用户银行记录表
	 */
// 	public function add() {
// 		$this->_modelObj->_typeId  		= $this->_typeId;
// 		$this->_modelObj->_accountType  		= $this->_accountType;
// 		$this->_modelObj->_accountName  		= $this->_accountName;
// 		$this->_modelObj->_accountNumber  		= $this->_accountNumber;
// 		$this->_modelObj->_branch  		= $this->_branch;
// 		$this->_modelObj->_mobile  		= $this->_mobile;
// 		$this->_modelObj->_sort  		= $this->_sort;
// 		$this->_modelObj->_isDefault  		= $this->_isDefault;
// 		$this->_modelObj->_enable  		= $this->_enable;
// 		$this->_modelObj->_addtime  		= $this->_addtime;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新用户银行记录表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_typeId  = $this->_typeId;
// 		$this->_modelObj->_accountType  = $this->_accountType;
// 		$this->_modelObj->_accountName  = $this->_accountName;
// 		$this->_modelObj->_accountNumber  = $this->_accountNumber;
// 		$this->_modelObj->_branch  = $this->_branch;
// 		$this->_modelObj->_mobile  = $this->_mobile;
// 		$this->_modelObj->_sort  = $this->_sort;
// 		$this->_modelObj->_isDefault  = $this->_isDefault;
// 		$this->_modelObj->_enable  = $this->_enable;
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
	
	/*
	 * 获取单条数据
	 * $where 可以是字符串 也可以是数组
	 */
	public function getRow($where,$field='*',$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr='');
	}

	/**
	 *
	 * 用户银行记录表列表
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
	 * 获取所有用户银行记录表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户银行记录表
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

	/**
	 * 设置开户银行id
	 *
	 */
	public function setTypeId($typeId) {
		$this->_typeId = $typeId;
		return $this;
	}
	
	public function setBankTypeName($bankTypeName) {
	    $this->_bankTypeName = $bankTypeName;
	    return $this;
	}

	/**
	 * 设置账户类型
	 *
	 */
	public function setAccountType($accountType) {
		$this->_accountType = $accountType;
		return $this;
	}

	/**
	 * 设置银行开户名
	 *
	 */
	public function setAccountName($accountName) {
		$this->_accountName = $accountName;
		return $this;
	}

	/**
	 * 设置银行账号
	 *
	 */
	public function setAccountNumber($accountNumber) {
		$this->_accountNumber = $accountNumber;
		return $this;
	}

	/**
	 * 设置支行名称
	 *
	 */
	public function setBranch($branch) {
		$this->_branch = $branch;
		return $this;
	}

	/**
	 * 设置手机号
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置排序
	 *
	 */
	public function setSort($sort) {
		$this->_sort = $sort;
		return $this;
	}

	/**
	 * 设置是否为默认(1为是 2为否)
	 *
	 */
	public function setIsDefault($isDefault) {
		$this->_isDefault = $isDefault;
		return $this;
	}

	/**
	 * 设置是否启用(1为是 -1为否)
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
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
		return new CusBankDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>