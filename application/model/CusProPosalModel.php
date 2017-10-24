<?php
/**
* 投诉建议表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:11:58Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusProPosalModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_content 	= null;

	protected $_proposalId 	= null;

	protected $_proposalName 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusProPosal');
	}

	/**
	 *
	 * 添加投诉建议表
	 */
	public function add() {
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_content  		= $this->_content;
		$this->_modelObj->_proposalId  		= $this->_proposalId;
		$this->_modelObj->_proposalName  		= $this->_proposalName;
		$this->_modelObj->_addtime  		= $this->_addtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新投诉建议表
	 */
	public function modify($id) {
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
	 * 投诉建议表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有投诉建议表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除投诉建议表
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
		return new CusProPosalDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>