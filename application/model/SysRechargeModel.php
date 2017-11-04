<?php
/**
* 用户角色表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-05-03 19:21:09Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class SysRechargeModel {

	protected $_id 	= null;

	protected $_orderno = null;

	protected $_rechargeType = null;

	protected $_customerid 	= null;

	protected $_mobile 	= null;

	protected $_amount 	= null;

	protected $_payStatus 	= null;

	protected $_payMoney = null;

	protected $_payTime = null;

	protected $_addtime = null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('SysRecharge');
	}

	/**
	 *
	 * 添加用户角色表
	 */
	public function insert($data) {
		return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新用户角色表
	 */
	public function modify($data, $id) {
		return $this->_modelObj->update($data, $id);
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
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
	    return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
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