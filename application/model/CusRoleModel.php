<?php
/**
* 用户角色关系表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:15:42Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusRoleModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_role 	= null;
	
	protected $_area   = null;
	
	protected $_address = null;
	
	protected $_areaCode = null;

	protected $_weight 	= null;
	
	protected $_addtime = null;
	
	protected $_enable = null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusRole');
	}

	/**
	 *
	 * 添加用户角色关系表
	 */
// 	public function add() {
// 		$this->_modelObj->_customerid  		= $this->_customerid;
// 		$this->_modelObj->_role  		= $this->_role;
// 		$this->_modelObj->_weight  		= $this->_weight;
// 		return $this->_modelObj->add();
// 	}

	/**
	* @user 写入用户关系表
	* @param 
	* @author jeeluo
	* @date 2017年3月11日上午11:09:31
	*/
	public function add($data) {
	    return $this->insert($data);
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新用户角色关系表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_customerid  = $this->_customerid;
// 		$this->_modelObj->_role  = $this->_role;
// 		$this->_modelObj->_weight  = $this->_weight;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * 详细
	 */
// 	public function getById($id = null) {
// 		$this->_id = is_null($id)? $this->_id : $id;
// 		$this->_dataInfo = $this->_modelObj->getById($this->_id);
// 		return $this->_dataInfo;
// 	}
	/*
	 * 获取单条数据
	 * $where 可以是字符串 也可以是数组
	 */
	public function getInfoRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->getRow($where, $fields='*', $order='', $otherstr='');
	}
	
	public function getRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
	}
	
	/*
	 * 查找该用户 角色是否被注册
	 */
	public function isFindByCustomeridRole($params) {
	    $roleInfo = $this->_modelObj->getRow(array("customerid" => $params['customerid'], "role" => $params['role']), "id");
	    if(empty($roleInfo)) {
	        return true;
	    }
	    return false;
	}
	
	/*
	 * 获取多条数据
	 * $where 可以是字符串 也可以是数组
	 */
	public function getInfoList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr='') {
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
	* @user 根据用户id 用户角色获取数据
	* @param $data
	* @author jeeluo
	* @date 2017年3月3日下午8:13:59
	*/
	public function getByCusRole($data) {
	    $this->_customerid = $data['customerid'];
	    $this->_role = $data['role'];
	    $this->_dataInfo = $this->_modelObj->getRow(array("customerid" => $this->id, "role" => $this->_role));
	    return $this->_dataInfo;
	}

	/*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/**
	 * 获取所有用户角色关系表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户角色关系表
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
	 * 设置提交用户ID
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置用户角色1普通消费者2创业者3供应商4实体店5地市代理6区县代理
	 *
	 */
	public function setRole($role) {
		$this->_role = $role;
		return $this;
	}
	
	public function setArea($area) {
	    $this->_area = $area;
	    return $this;
	}
	
	public function setAddress($address) {
	    $this->_address = $address;
	    return $this;
	}
	
	public function setAreaCode($areaCode) {
	    $this->_areaCode = $areaCode;
	    return $this;
	}

	/**
	 * 设置权重
	 *
	 */
	public function setWeight($weight) {
		$this->_weight = $weight;
		return $this;
	}
	
	public function setAddtime($addtime) {
	    $this->_addtime = $addtime;
	    return $this;
	}
	
	public function setEnable($enable) {
	    $this->_enable = $enable;
	    return $this;
	}

	public static function getModelObj() {
		return new CusRoleDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>