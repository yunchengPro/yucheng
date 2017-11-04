<?php
/**
* 运费模板扩展表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:44:32Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class OrdTransportModel {

	protected $_id 	= null;

	protected $_businessId 	= null;

	protected $_transportId 	= null;

	protected $_transportTitle 	= null;

	protected $_areaId 	= null;

	protected $_topAreaId 	= null;

	protected $_areaName 	= null;

	protected $_snum 	= null;

	protected $_sprice 	= null;

	protected $_xnum 	= null;

	protected $_xprice 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('OrdTransport');
	}
	
	/*
	 负责把表单提交来的数组
	 清除掉不用的单元
	 留下与表的字段对应的单元
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
	
	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
	    return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
	}

	  /*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
    	return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
    }	

	/**
	 * 设置
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setBusinessId($businessId) {
		$this->_businessId = $businessId;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setTransportId($transportId) {
		$this->_transportId = $transportId;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setTransportTitle($transportTitle) {
		$this->_transportTitle = $transportTitle;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setAreaId($areaId) {
		$this->_areaId = $areaId;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setTopAreaId($topAreaId) {
		$this->_topAreaId = $topAreaId;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setAreaName($areaName) {
		$this->_areaName = $areaName;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setSnum($snum) {
		$this->_snum = $snum;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setSprice($sprice) {
		$this->_sprice = $sprice;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setXnum($xnum) {
		$this->_xnum = $xnum;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setXprice($xprice) {
		$this->_xprice = $xprice;
		return $this;
	}

	public static function getModelObj() {
		return new OrdTransportDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>