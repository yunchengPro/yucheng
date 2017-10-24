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

class StoModuleModel {

	protected $_id 	= null;

	protected $_modulename 	= null;

	protected $_modulecode 	= null;

	protected $_parenid 	= null;

	protected $_sort 	= null;

	protected $_type 	= null;

	protected $_moduleType 	= null;

	protected $_thumb 	= null;

	protected $_isshow 	= null;

	protected $_city_id 	= null;

	protected $_city 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoModule');
	}

	/**
	 *
	 * 添加app首页显示模块表
	 */
	public function add() {
		$this->_modelObj->_modulename  		= $this->_modulename;
		$this->_modelObj->_modulecode  		= $this->_modulecode;
		$this->_modelObj->_parenid  		= $this->_parenid;
		$this->_modelObj->_sort  		= $this->_sort;
		$this->_modelObj->_type  		= $this->_type;
		$this->_modelObj->_moduleType  		= $this->_moduleType;
		$this->_modelObj->_thumb  		= $this->_thumb;
		$this->_modelObj->_isshow  		= $this->_isshow;
		$this->_modelObj->_city_id  		= $this->_city_id;
		$this->_modelObj->_city  		= $this->_city;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新app首页显示模块表
	 */
	public function modify($id) {
		$this->_modelObj->_modulename  = $this->_modulename;
		$this->_modelObj->_modulecode  = $this->_modulecode;
		$this->_modelObj->_parenid  = $this->_parenid;
		$this->_modelObj->_sort  = $this->_sort;
		$this->_modelObj->_type  = $this->_type;
		$this->_modelObj->_moduleType  = $this->_moduleType;
		$this->_modelObj->_thumb  = $this->_thumb;
		$this->_modelObj->_isshow  = $this->_isshow;
		$this->_modelObj->_city_id  		= $this->_city_id;
		$this->_modelObj->_city  		= $this->_city;
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