<?php
/**
* 商家用户表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-13 14:56:36Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class BusSysAccountModel {

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_businessRoleid 	= null;

	protected $_username 	= null;

	protected $_userpwd 	= null;

	protected $_enable 	= null;

	protected $_createtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('BusSysAccount');
	}

	/**
	 *
	 * 添加商家用户表
	 */
	public function add() {
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_businessRoleid  		= $this->_businessRoleid;
		$this->_modelObj->_username  		= $this-> $_username;
		$this->_modelObj->_userpwd  		= $this->_userpwd;
		$this->_modelObj->_enable  		= $this->_enable;
		$this->_modelObj->_createtime  		= $this->_createtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商家用户表
	 */
	public function modify($id) {
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_businessRoleid  = $this->_businessRoleid;
		$this->_modelObj->_username  = $this->_username;
		$this->_modelObj->_userpwd  = $this->_userpwd;
		$this->_modelObj->_enable  = $this->_enable;
		$this->_modelObj->_createtime  = $this->_createtime;
		return $this->_modelObj->modify($id);
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
	 * 商家用户表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/**
	 * 获取所有商家用户表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除商家用户表
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
	 * 设置商家id
	 *
	 */
	public function setBusinessid($businessid) {
		$this->_businessid = $businessid;
		return $this;
	}

	/**
	 * 设置商家角色id对应角色表
	 *
	 */
	public function setBusinessRoleid($businessRoleid) {
		$this->_businessRoleid = $businessRoleid;
		return $this;
	}

	/**
	 * 设置商家账号
	 *
	 */
	public function setUsername($username) {
		$this->_username = $username;
		return $this;
	}

	/**
	 * 设置商家密码
	 *
	 */
	public function setUserpwd($userpwd) {
		$this->_userpwd = $userpwd;
		return $this;
	}

	/**
	 * 设置账号状态-1禁用1启用
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
	public function setCreatetime($createtime) {
		$this->_createtime = $createtime;
		return $this;
	}

	public static function getModelObj() {
		return new BusSysAccountDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>