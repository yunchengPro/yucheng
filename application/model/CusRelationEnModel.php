<?php
/**
* 创业者关系表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:13:58Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusRelationEnModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_parentid 	= null;

	protected $_grandpaid 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusRelationEn');
	}

	/**
	 *
	 * 添加创业者关系表
	 */
// 	public function add() {
// 		$this->_modelObj->_customerid  		= $this->_customerid;
// 		$this->_modelObj->_parentid  		= $this->_parentid;
// 		$this->_modelObj->_grandpaid  		= $this->_grandpaid;
// 		$this->_modelObj->_addtime  		= $this->_addtime;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->insert($data);
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新创业者关系表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_customerid  = $this->_customerid;
// 		$this->_modelObj->_parentid  = $this->_parentid;
// 		$this->_modelObj->_grandpaid  = $this->_grandpaid;
// 		$this->_modelObj->_addtime  = $this->_addtime;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}
	
    public function getInfoRow($where, $field="*", $order='', $otherstr='') {
	    return $this->getRow($where, $field, $order, $otherstr);
	}
	
	public function getRow($where, $field="*", $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $field, $order, $otherstr);
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
	 * 创业者关系表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}
	
	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1){
	    return $this->_modelObj->pageList($where,$field,$order,$flag);
	}

	/**
	 * 获取所有创业者关系表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除创业者关系表
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
	 * 设置用户id
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置上级创业者id(customer)
	 *
	 */
	public function setParentid($parentid) {
		$this->_parentid = $parentid;
		return $this;
	}

	/**
	 * 设置上上级创业者id(customer)
	 *
	 */
	public function setGrandpaid($grandpaid) {
		$this->_grandpaid = $grandpaid;
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
		return new CusRelationEnDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>