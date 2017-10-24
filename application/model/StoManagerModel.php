<?php
/**
* 实体商家铺收款用户关系表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:12:48Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoManagerModel {

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_businessname 	= null;

	protected $_businessCode 	= null;

	protected $_customerid 	= null;

	protected $_customername 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoManager');
	}

	/**
	 *
	 * 添加实体商家铺收款用户关系表
	 */
	public function add() {
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_businessname  		= $this->_businessname;
		$this->_modelObj->_businessCode  		= $this->_businessCode;
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_customername  		= $this->_customername;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新实体商家铺收款用户关系表
	 */
	public function modify($id) {
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_businessname  = $this->_businessname;
		$this->_modelObj->_businessCode  = $this->_businessCode;
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_customername  = $this->_customername;
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
	 * 实体商家铺收款用户关系表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	  /*
    * 写入数据
    * $insertData = array() 如果ID是自增 返回生成主键ID
    */
    public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

	/**
	 * 获取所有实体商家铺收款用户关系表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除实体商家铺收款用户关系表
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
	 * 设置商家id
	 *
	 */
	public function setBusinessid($businessid) {
		$this->_businessid = $businessid;
		return $this;
	}

	/**
	 * 设置商家名称
	 *
	 */
	public function setBusinessname($businessname) {
		$this->_businessname = $businessname;
		return $this;
	}

	/**
	 * 设置商家平台号
	 *
	 */
	public function setBusinessCode($businessCode) {
		$this->_businessCode = $businessCode;
		return $this;
	}

	/**
	 * 设置用户ID
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置用户名称
	 *
	 */
	public function setCustomername($customername) {
		$this->_customername = $customername;
		return $this;
	}

	public static function getModelObj() {
		return new StoManagerDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>