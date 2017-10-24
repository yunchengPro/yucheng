<?php
/**
* 实体商家分类表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:07:09Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoCityModel {

	protected $_id 	= null;

	protected $_cityId 	= null;

	protected $_city 	= null;

	protected $_sort 	= null;

	protected $_businesscount 	= null;
	

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoCity');
	}

	/**
	 *
	 * 添加实体商家分类表
	 */
	public function add() {
		$this->_modelObj->_cityId  		= $this->_cityId;
		$this->_modelObj->_city  		= $this->_city;
		$this->_modelObj->_sort  		= $this->_sort;
		$this->_modelObj->_businesscount  		= $this->_businesscount;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新实体商家分类表
	 */
	public function modify($id) {
		$this->_modelObj->_cityId  = $this->_cityId;
		$this->_modelObj->_city  = $this->_city;
		$this->_modelObj->_sort  = $this->_sort;
		$this->_modelObj->_businesscount  		= $this->_businesscount;
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
	 * 实体商家分类表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr='') {
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/**
	 * 获取所有实体商家分类表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除实体商家分类表
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
	 * 设置地区编号
	 *
	 */
	public function setCityId($cityId) {
		$this->_cityId = $cityId;
		return $this;
	}

	/**
	 * 设置地区名称
	 *
	 */
	public function setCity($city) {
		$this->_city = $city;
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

	public static function getModelObj() {
		return new StoCityDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>