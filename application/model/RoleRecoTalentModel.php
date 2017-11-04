<?php
/**
* 牛人推荐信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 11:22:45Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class RoleRecoTalentModel {

	protected $_id 	= null;

	protected $_realname 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_address 	= null;

	protected $_areaCode 	= null;

	protected $_instroducerid 	= null;

	protected $_payStatus 	= null;

	protected $_status 	= null;

	protected $_remark 	= null;

	protected $_examinetime 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('RoleRecoTalent');
	}

	/*
	 负责把表单提交来的数组
	 清除掉不用的单元
	 留下与表的字段对应的单元
	 */
	public function _facade($array = []){
	    return $this->_modelObj->_facade($array);
	}
	
	/**
	 *
	 * 添加牛人推荐信息表
	 */
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}
	
	public function add($data) {
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新牛人推荐信息表
	 */
	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}
	
	public function update($data, $where) {
	    $this->modify($data,$where);
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
	
	public function getRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
	}
	
	/**
	 * @user 角色过滤
	 * @param
	 * @author jeeluo
	 * @date 2017年3月14日下午7:37:25
	 */
	public function filterReco($params) {
	    $recoInfo = $this->_modelObj->getRow(array("mobile" => $params['mobile'], "instroducerid" => $params['instroducerid']), 'pay_status, status');
	    if(!empty($recoInfo)) {
	        if($recoInfo['pay_status'] == 1 && ($recoInfo['status'] == 1 || $recoInfo['status'] == 2)) {
	            return false;
	        }
	    }
	    return true;
	}
	
	//开启事务
	public function startTrans(){
	    return $this->_modelObj->startTrans();
	}
	
	//提交事务
	public function commit(){
	    return $this->_modelObj->commit();
	}
	
	//事务回滚
	public function rollback(){
	    return $this->_modelObj->rollback();
	}

	/**
	 *
	 * 牛人推荐信息表列表
	 */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
	    return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
	    return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
	}
	
	/**
	 * 获取所有牛人推荐信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	/**
	 *
	 * 删除牛人推荐信息表
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
	 * 设置被推荐者姓名
	 *
	 */
	public function setRealname($realname) {
		$this->_realname = $realname;
		return $this;
	}

	/**
	 * 设置被推荐者手机号码
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置所属省市区
	 *
	 */
	public function setArea($area) {
		$this->_area = $area;
		return $this;
	}

	/**
	 * 设置详细地址
	 *
	 */
	public function setAddress($address) {
		$this->_address = $address;
		return $this;
	}

	/**
	 * 设置市区编号
	 *
	 */
	public function setAreaCode($areaCode) {
		$this->_areaCode = $areaCode;
		return $this;
	}

	/**
	 * 设置引荐人id
	 *
	 */
	public function setInstroducerid($instroducerid) {
		$this->_instroducerid = $instroducerid;
		return $this;
	}

	/**
	 * 设置支付状态(1为未支付，2为已支付)
	 *
	 */
	public function setPayStatus($payStatus) {
		$this->_payStatus = $payStatus;
		return $this;
	}

	/**
	 * 设置审核状态(1为待审核，2为审核通过，3为审核失败)
	 *
	 */
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}

	/**
	 * 设置审核失败后 返回的描述
	 *
	 */
	public function setRemark($remark) {
		$this->_remark = $remark;
		return $this;
	}

	/**
	 * 设置审核时间
	 *
	 */
	public function setExaminetime($examinetime) {
		$this->_examinetime = $examinetime;
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
		return new RoleRecoTalentDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>