<?php
/**
* 银行分类表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-22 09:47:41Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusBankTypeModel {

	protected $_id 	= null;

	protected $_bankName 	= null;

	protected $_sort 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusBankType');
	}

	/**
	 *
	 * 添加银行分类表
	 */
// 	public function add() {
// 		$this->_modelObj->_bankName  		= $this->_bankName;
// 		$this->_modelObj->_sort  		= $this->_sort;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新银行分类表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_bankName  = $this->_bankName;
// 		$this->_modelObj->_sort  = $this->_sort;
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
	 * 银行分类表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有银行分类表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除银行分类表
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
	 * 设置银行名称
	 *
	 */
	public function setBankName($bankName) {
		$this->_bankName = $bankName;
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
		return new CusBankTypeDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>