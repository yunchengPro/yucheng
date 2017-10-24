<?php
/**
* 店铺投诉建议表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:21:00Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoProposalModel {

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_businessname 	= null;

	protected $_productname 	= null;

	protected $_customerid 	= null;

	protected $_content 	= null;

	protected $_proposalId 	= null;

	protected $_proposalName 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoProposal');
	}

	/**
	 *
	 * 添加店铺投诉建议表
	 */
	public function add() {
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_businessname  		= $this->_businessname;
		$this->_modelObj->_productname  		= $this->_productname;
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_content  		= $this->_content;
		$this->_modelObj->_proposalId  		= $this->_proposalId;
		$this->_modelObj->_proposalName  		= $this->_proposalName;
		$this->_modelObj->_addtime  		= $this->_addtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新店铺投诉建议表
	 */
	public function modify($id) {
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_businessname  = $this->_businessname;
		$this->_modelObj->_productname  = $this->_productname;
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_content  = $this->_content;
		$this->_modelObj->_proposalId  = $this->_proposalId;
		$this->_modelObj->_proposalName  = $this->_proposalName;
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

	/**
	 *
	 * 店铺投诉建议表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}


	public function pageList($where,$field='',$order='',$flag=1,$page='',$pagesize=''){
        return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
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
	 * 获取所有店铺投诉建议表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除店铺投诉建议表
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
	 * 设置商品名称
	 *
	 */
	public function setProductname($productname) {
		$this->_productname = $productname;
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
	 * 设置评价内容
	 *
	 */
	public function setContent($content) {
		$this->_content = $content;
		return $this;
	}

	/**
	 * 设置投诉类型ID
	 *
	 */
	public function setProposalId($proposalId) {
		$this->_proposalId = $proposalId;
		return $this;
	}

	/**
	 * 设置投诉类型名称
	 *
	 */
	public function setProposalName($proposalName) {
		$this->_proposalName = $proposalName;
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
		return new StoProposalDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
    
    
    
    /*
    * 写入数据
    * $insertData = array() 如果ID是自增 返回生成主键ID
    */
    public function insert($insertData) {
        return $this->_modelObj->insert($insertData);
    }
}
?>