<?php
/**
* 实体商家分类表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:03:26Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoPayFlowProductModel {

	protected $_id 	= null;

	protected $_productid 	= null;

	protected $_productname 	= null;

	protected $_prouctprice 	= null;

	protected $_productnum 	= null;

	protected $_productamount 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoPayFlowProduct');
	}

	/**
	 *
	 * 添加实体商家分类表
	 */
// 	public function add() {
// 		$this->_modelObj->_parentid  		= $this->_parentid;
// 		$this->_modelObj->_categoryname  		= $this->_categoryname;
// 		$this->_modelObj->_businesscount  		= $this->_businesscount;
// 		$this->_modelObj->_sort  		= $this->_sort;
// 		return $this->_modelObj->add();
// 	}

	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新实体商家分类表
	 */
	public function modify($data, $where) {
// 		$this->_modelObj->_parentid  = $this->_parentid;
// 		$this->_modelObj->_categoryname  = $this->_categoryname;
// 		$this->_modelObj->_businesscount  = $this->_businesscount;
// 		$this->_modelObj->_sort  = $this->_sort;
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

	/*
    * 删除数据
    */
    public function delete($where,$limit=''){
    	return $this->_modelObj->delete($where,$limit);
    }
	
	/*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
    	return $this->_modelObj->update($updateData,$where,$limit);
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
	 * 设置上级id
	 *
	 */
	public function setParentid($parentid) {
		$this->_parentid = $parentid;
		return $this;
	}

	/**
	 * 设置分类名称
	 *
	 */
	public function setCategoryname($categoryname) {
		$this->_categoryname = $categoryname;
		return $this;
	}

	/**
	 * 设置店铺数量
	 *
	 */
	public function setBusinesscount($businesscount) {
		$this->_businesscount = $businesscount;
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
		return new StoCategoryDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>