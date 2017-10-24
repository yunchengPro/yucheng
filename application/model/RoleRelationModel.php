<?php
/**
* 角色关系值表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:30:52Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class RoleRelationModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_parentid 	= null;

	protected $_grandpaid 	= null;

	protected $_bindbusinessid 	= null;

	protected $_citycode 	= null;

	protected $_countycode 	= null;

	protected $_parentagentid 	= null;

	protected $_introducerid 	= null;

	protected $_introducername 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('RoleRelation');
	}

	/**
	 *
	 * 添加角色关系值表
	 */
// 	public function add() {
// 		$this->_modelObj->_customerid  		= $this->_customerid;
// 		$this->_modelObj->_parentid  		= $this->_parentid;
// 		$this->_modelObj->_grandpaid  		= $this->_grandpaid;
// 		$this->_modelObj->_bindbusinessid  		= $this->_bindbusinessid;
// 		$this->_modelObj->_citycode  		= $this->_citycode;
// 		$this->_modelObj->_countycode  		= $this->_countycode;
// 		$this->_modelObj->_parentagentid  		= $this->_parentagentid;
// 		$this->_modelObj->_introducerid  		= $this->_introducerid;
// 		$this->_modelObj->_introducername  		= $this->_introducername;
// 		$this->_modelObj->_addtime  		= $this->_addtime;
// 		return $this->_modelObj->add();
// 	}

	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新角色关系值表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_customerid  = $this->_customerid;
// 		$this->_modelObj->_parentid  = $this->_parentid;
// 		$this->_modelObj->_grandpaid  = $this->_grandpaid;
// 		$this->_modelObj->_bindbusinessid  = $this->_bindbusinessid;
// 		$this->_modelObj->_citycode  = $this->_citycode;
// 		$this->_modelObj->_countycode  = $this->_countycode;
// 		$this->_modelObj->_parentagentid  = $this->_parentagentid;
// 		$this->_modelObj->_introducerid  = $this->_introducerid;
// 		$this->_modelObj->_introducername  = $this->_introducername;
// 		$this->_modelObj->_addtime  = $this->_addtime;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}
	
	public function update($data,$where) {
	    return $this->_modelObj->update($data,$where);
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id,$field="*") {
		
		return $this->_modelObj->getRow(['id'=>$id],$field);

	}
	
	public function getRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
	}


	/**
	 *
	 * 角色关系值表列表
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
	 * 获取所有角色关系值表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除角色关系值表
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
	 * 设置客户id
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
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
	 * 设置上上级id(customer)
	 *
	 */
	public function setGrandpaid($grandpaid) {
		$this->_grandpaid = $grandpaid;
		return $this;
	}

	/**
	 * 设置绑定的实体店
	 *
	 */
	public function setBindbusinessid($bindbusinessid) {
		$this->_bindbusinessid = $bindbusinessid;
		return $this;
	}

	/**
	 * 设置所在地市编号
	 *
	 */
	public function setCitycode($citycode) {
		$this->_citycode = $citycode;
		return $this;
	}

	/**
	 * 设置所在区县编号
	 *
	 */
	public function setCountycode($countycode) {
		$this->_countycode = $countycode;
		return $this;
	}

	/**
	 * 设置上级代理id(customer)
	 *
	 */
	public function setParentagentid($parentagentid) {
		$this->_parentagentid = $parentagentid;
		return $this;
	}

	/**
	 * 设置推荐者id(customer)
	 *
	 */
	public function setIntroducerid($introducerid) {
		$this->_introducerid = $introducerid;
		return $this;
	}

	/**
	 * 设置推荐者名称(customer)
	 *
	 */
	public function setIntroducername($introducername) {
		$this->_introducername = $introducername;
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
		return new RoleRelationDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>