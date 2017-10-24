<?php
/**
* 用户角色表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-13 15:03:26Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class SysRoleModel {

	protected $_id 	= null;

	protected $_name 	= null;

	protected $_remark 	= null;

	protected $_menuids 	= null;

	protected $_enable 	= null;

	protected $_createby 	= null;

	protected $_createtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('SysRole');
	}

	/**
	 *
	 * 添加用户角色表
	 */
	public function add() {
		$this->_modelObj->_name  		= $this->_name;
		$this->_modelObj->_remark  		= $this->_remark;
		$this->_modelObj->_menuids  		= $this->_menuids;
		$this->_modelObj->_enable  		= $this->_enable;
		$this->_modelObj->_createby  		= $this->_createby;
		$this->_modelObj->_createtime  		= $this->_createtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户角色表
	 */
	public function modify($id) {
		$this->_modelObj->_name  = $this->_name;
		$this->_modelObj->_remark  = $this->_remark;
		$this->_modelObj->_menuids  = $this->_menuids;
		$this->_modelObj->_enable  = $this->_enable;
		$this->_modelObj->_createby  = $this->_createby;
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


	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
			return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}
	
	/*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }
	/**
	 * 获取所有实体店banner
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	 /*
     * 写入数据
     * $insertData = array() 如果ID是自增 返回生成主键ID
     */
    public function insert($insertData) {
        return $this->_modelObj->insert($insertData);
    }
    
    /*
     * 更新数据
     * $updateData = array()
     */
    public function update($updateData,$where,$limit=''){
        return $this->_modelObj->update($updateData,$where,$limit='');
    }
    
    /*
     * 删除数据
     */
    public function delete($where,$limit=''){
        return $this->_modelObj->delete($where,$limit);
    }


	/**
	 *
	 * 删除用户角色表
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
	 * 设置角色的名称
	 *
	 */
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * 设置角色的描述
	 *
	 */
	public function setRemark($remark) {
		$this->_remark = $remark;
		return $this;
	}

	/**
	 * 设置对应菜单id目前不用
	 *
	 */
	public function setMenuids($menuids) {
		$this->_menuids = $menuids;
		return $this;
	}

	/**
	 * 设置是否启用
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
		return $this;
	}

	/**
	 * 设置创建人
	 *
	 */
	public function setCreateby($createby) {
		$this->_createby = $createby;
		return $this;
	}

	/**
	 * 设置创建时间
	 *
	 */
	public function setCreatetime($createtime) {
		$this->_createtime = $createtime;
		return $this;
	}

	public static function getModelObj() {
		return new BusSysRoleDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>