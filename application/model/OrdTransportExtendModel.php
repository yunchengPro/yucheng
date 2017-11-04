<?php
/**
* 运费模板扩展表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:46:13Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class OrdTransportExtendModel {

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
		$this->_modelObj = Db::Table('OrdTransportExtend');
	}

	/**
	 *
	 * 添加运费模板扩展表
	 */
	public function add() {
		$this->_modelObj->_businessId  		= $this->_businessId;
		$this->_modelObj->_transportId  		= $this->_transportId;
		$this->_modelObj->_transportTitle  		= $this->_transportTitle;
		$this->_modelObj->_areaId  		= $this->_areaId;
		$this->_modelObj->_topAreaId  		= $this->_topAreaId;
		$this->_modelObj->_areaName  		= $this->_areaName;
		$this->_modelObj->_snum  		= $this->_snum;
		$this->_modelObj->_sprice  		= $this->_sprice;
		$this->_modelObj->_xnum  		= $this->_xnum;
		$this->_modelObj->_xprice  		= $this->_xprice;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新运费模板扩展表
	 */
	public function modify($id) {
		$this->_modelObj->_businessId  = $this->_businessId;
		$this->_modelObj->_transportId  = $this->_transportId;
		$this->_modelObj->_transportTitle  = $this->_transportTitle;
		$this->_modelObj->_areaId  = $this->_areaId;
		$this->_modelObj->_topAreaId  = $this->_topAreaId;
		$this->_modelObj->_areaName  = $this->_areaName;
		$this->_modelObj->_snum  = $this->_snum;
		$this->_modelObj->_sprice  = $this->_sprice;
		$this->_modelObj->_xnum  = $this->_xnum;
		$this->_modelObj->_xprice  = $this->_xprice;
		return $this->_modelObj->modify($id);
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
	public function getById($id = null) {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getById($this->_id);
		return $this->_dataInfo;
	}

	/**
	 *
	 * 运费模板扩展表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr='') {
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	public function getRow($where,$field='*',$order='',$otherstr=''){
		return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}
 	
 	/*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageList($where,$field='',$order='',$flag=1){
    	return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
    }
	/**
	 * 获取所有运费模板扩展表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}
  


	/**
	 *
	 * 删除运费模板扩展表
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
	public function setBusinessId($businessId) {
		$this->_businessId = $businessId;
		return $this;
	}

	/**
	 * 设置运费模板ID
	 *
	 */
	public function setTransportId($transportId) {
		$this->_transportId = $transportId;
		return $this;
	}

	/**
	 * 设置运费模板
	 *
	 */
	public function setTransportTitle($transportTitle) {
		$this->_transportTitle = $transportTitle;
		return $this;
	}

	/**
	 * 设置市级地区ID组成的串，以，隔开，两端也有
	 *
	 */
	public function setAreaId($areaId) {
		$this->_areaId = $areaId;
		return $this;
	}

	/**
	 * 设置省级地区ID组成的串，以，隔开，两端也有
	 *
	 */
	public function setTopAreaId($topAreaId) {
		$this->_topAreaId = $topAreaId;
		return $this;
	}

	/**
	 * 设置地区name组成的串，以，隔开
	 *
	 */
	public function setAreaName($areaName) {
		$this->_areaName = $areaName;
		return $this;
	}

	/**
	 * 设置首件数量
	 *
	 */
	public function setSnum($snum) {
		$this->_snum = $snum;
		return $this;
	}

	/**
	 * 设置首件运费
	 *
	 */
	public function setSprice($sprice) {
		$this->_sprice = $sprice;
		return $this;
	}

	/**
	 * 设置续件数量
	 *
	 */
	public function setXnum($xnum) {
		$this->_xnum = $xnum;
		return $this;
	}

	/**
	 * 设置续件运费
	 *
	 */
	public function setXprice($xprice) {
		$this->_xprice = $xprice;
		return $this;
	}

	public static function getModelObj() {
		return new OrdTransportExtendDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>