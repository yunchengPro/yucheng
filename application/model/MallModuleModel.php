<?php
/**
* app首页显示模块表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-07 14:13:58Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class MallModuleModel {

	protected $_id 	= null;

	protected $_modulename 	= null;

	protected $_modulecode 	= null;

	protected $_parenid 	= null;

	protected $_sort 	= null;

	protected $_type 	= null;

	protected $_moduleType 	= null;

	protected $_thumb 	= null;

	protected $_isshow 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('MallModule');
	}
	
	/*
	 * 负责把表单提交来的数组
	 * 清除掉不用的单元
	 * 留下与表的字段对应的单元
	 */
	public function _facade($array = []){
	    return $this->_modelObj->_facade($array);
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
	 * 详细
	 */
	public function getById($id = null,$field="*") {
	    return $this->_modelObj->getRow(["id"=>$id],$field);
	}
	
	public function getRow($where,$field="*",$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}

	/**
	 *
	 * app首页显示模块表列表
	 */
	 public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/**
	 * 获取所有app首页显示模块表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除app首页显示模块表
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
	 * 设置类型名称
	 *
	 */
	public function setModulename($modulename) {
		$this->_modulename = $modulename;
		return $this;
	}

	/**
	 * 设置模块名称
	 *
	 */
	public function setParenid($parenid) {
		$this->_parenid = $parenid;
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
	 * 设置类型1位商城首页2现金专区3现金加牛币专区
	 *
	 */
	public function setType($type) {
		$this->_type = $type;
		return $this;
	}

	/**
	 * 设置模块布局类型
	 *
	 */
	public function setModuleType($moduleType) {
		$this->_moduleType = $moduleType;
		return $this;
	}

	/**
	 * 设置图片地址
	 *
	 */
	public function setThumb($thumb) {
		$this->_thumb = $thumb;
		return $this;
	}

	/**
	 * 设置是否在app显示
	 *
	 */
	public function setIsshow($isshow) {
		$this->_isshow = $isshow;
		return $this;
	}

	public static function getModelObj() {
		return new MallModuleDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>