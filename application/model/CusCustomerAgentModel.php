<?php
/**
* 区域代理用户信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:04:19Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusCustomerAgentModel {

	protected $_id 	= null;

	protected $_agentType 	= null;

	protected $_agentAmount 	= null;

	protected $_introducerid 	= null;

	protected $_introducername 	= null;

	protected $_introducerAmoun 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusCustomerAgent');
	}

	/**
	 *
	 * 添加区域代理用户信息表
	 */
// 	public function add() {
// 		$this->_modelObj->_agentType  		= $this->_agentType;
// 		$this->_modelObj->_agentAmount  		= $this->_agentAmount;
// 		$this->_modelObj->_introducerid  		= $this->_introducerid;
// 		$this->_modelObj->_introducername  		= $this->_introducername;
// 		$this->_modelObj->_introducerAmoun  		= $this->_introducerAmoun;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->_modelObj->insert($data);
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新区域代理用户信息表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_agentType  = $this->_agentType;
// 		$this->_modelObj->_agentAmount  = $this->_agentAmount;
// 		$this->_modelObj->_introducerid  = $this->_introducerid;
// 		$this->_modelObj->_introducername  = $this->_introducername;
// 		$this->_modelObj->_introducerAmoun  = $this->_introducerAmoun;
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
	public function getInfoRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->getRow($where, $fields, $order, $otherstr);
	}
	
	public function getRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
	}
	
	/**
	* @user 识别加盟编号是否已经存在
	* @param 
	* @author jeeluo
	* @date 2017年3月15日下午9:14:42
	*/
	public function isFindCode($join_code) {
	    return $this->_modelObj->getRow(array("join_code" => ['like','%'.$join_code.'%']), "id, area");
	}

	/**
	 *
	 * 区域代理用户信息表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}
	
	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1){
	    return $this->_modelObj->pageList($where,$field,$order,$flag);
	}

	/**
	 * 获取所有区域代理用户信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除区域代理用户信息表
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
	 * 设置代理商类型
	 *
	 */
	public function setAgentType($agentType) {
		$this->_agentType = $agentType;
		return $this;
	}

	/**
	 * 设置已交代理费用
	 *
	 */
	public function setAgentAmount($agentAmount) {
		$this->_agentAmount = $agentAmount;
		return $this;
	}

	/**
	 * 设置推荐者id
	 *
	 */
	public function setIntroducerid($introducerid) {
		$this->_introducerid = $introducerid;
		return $this;
	}

	/**
	 * 设置推荐者名称
	 *
	 */
	public function setIntroducername($introducername) {
		$this->_introducername = $introducername;
		return $this;
	}

	/**
	 * 设置推荐者获得代理费用
	 *
	 */
	public function setIntroducerAmoun($introducerAmoun) {
		$this->_introducerAmoun = $introducerAmoun;
		return $this;
	}

	public static function getModelObj() {
		return new CusCustomerAgentDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>