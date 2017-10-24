<?php
/**
* 店铺关系表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:18:44Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class BusBusinessRelationModel {

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_businesstype 	= null;

	protected $_introducerid 	= null;

	protected $_introducername 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('BusBusinessRelation');
	}

	/**
	 *
	 * 添加店铺关系表
	 */
	public function add() {
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_businesstype  		= $this->_businesstype;
		$this->_modelObj->_introducerid  		= $this->_introducerid;
		$this->_modelObj->_introducername  		= $this->_introducername;
		$this->_modelObj->_addtime  		= $this->_addtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新店铺关系表
	 */
	public function modify($id) {
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_businesstype  = $this->_businesstype;
		$this->_modelObj->_introducerid  = $this->_introducerid;
		$this->_modelObj->_introducername  = $this->_introducername;
		$this->_modelObj->_addtime  = $this->_addtime;
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

	public function  getRow($where,$field='*',$order='',$otherstr=''){
        return $this->_modelObj->getRow($where,$field,$order,$otherstr); 
    }

	/**
	 *
	 * 店铺关系表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有店铺关系表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除店铺关系表
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
	 * 设置商家ID
	 *
	 */
	public function setBusinessid($businessid) {
		$this->_businessid = $businessid;
		return $this;
	}

	/**
	 * 设置商家类型
	 *
	 */
	public function setBusinesstype($businesstype) {
		$this->_businesstype = $businesstype;
		return $this;
	}

	/**
	 * 设置推荐者用户id(customer)-创业者
	 *
	 */
	public function setIntroducerid($introducerid) {
		$this->_introducerid = $introducerid;
		return $this;
	}

	/**
	 * 设置推荐者用户名称
	 *
	 */
	public function setIntroducername($introducername) {
		$this->_introducername = $introducername;
		return $this;
	}

	/**
	 * 设置推荐时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	public static function getModelObj() {
		return new BusBusinessRelationDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>