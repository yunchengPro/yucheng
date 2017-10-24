<?php
/**
* 用户提现申请表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-04-06 10:25:23Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusWithdrawalsModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_bankid 	= null;

	protected $_accountName 	= null;

	protected $_accountNumber 	= null;

	protected $_branch 	= null;

	protected $_mobile 	= null;

	protected $_amount 	= null;

	protected $_addtime 	= null;

	protected $_status 	= null;

	protected $_payMoney 	= null;

	protected $_payTime 	= null;

	protected $_handleUser 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusWithdrawals');
	}

	/**
     * 生成订单编号
     * @Author   zhuangqm
  
     * @return   [type]                   [description]
     */
    public function getOrderNo(){
        return "NNHWI".date("YmdHis").rand(100000,999999);
    }

	/*
	 * 负责把表单提交来的数组
	 * 清除掉不用的单元
	 * 留下与表的字段对应的单元
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
	    return $this->modify($updateData,$where,$limit);
// 	    return $this->_modelObj->update($updateData,$where,$limit='');
	}
	
	public function modify($data, $where, $limit='') {
	    return $this->_modelObj->update($data,$where,$limit);
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
	
	public function getRow($where,$field="*",$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}

	/**
	 *
	 * 用户提现申请表列表
	 */
// 	public function getList($page, $pagesize) {
// 		return $this->_modelObj->getAllForPage($page, $pagesize);
// 	}

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
	 * 获取所有用户提现申请表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户提现申请表
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
	 * 设置申请用户ID
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置银行卡ID
	 *
	 */
	public function setBankid($bankid) {
		$this->_bankid = $bankid;
		return $this;
	}

	/**
	 * 设置银行开户名
	 *
	 */
	public function setAccountName($accountName) {
		$this->_accountName = $accountName;
		return $this;
	}

	/**
	 * 设置银行账号
	 *
	 */
	public function setAccountNumber($accountNumber) {
		$this->_accountNumber = $accountNumber;
		return $this;
	}

	/**
	 * 设置支行名称
	 *
	 */
	public function setBranch($branch) {
		$this->_branch = $branch;
		return $this;
	}

	/**
	 * 设置银行卡手机号
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置提现金额
	 *
	 */
	public function setAmount($amount) {
		$this->_amount = $amount;
		return $this;
	}

	/**
	 * 设置提交时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	/**
	 * 设置提现状态0处理中1到帐成功2提现失败
	 *
	 */
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}

	/**
	 * 设置实际支付金额
	 *
	 */
	public function setPayMoney($payMoney) {
		$this->_payMoney = $payMoney;
		return $this;
	}

	/**
	 * 设置支付时间
	 *
	 */
	public function setPayTime($payTime) {
		$this->_payTime = $payTime;
		return $this;
	}

	/**
	 * 设置处理人
	 *
	 */
	public function setHandleUser($handleUser) {
		$this->_handleUser = $handleUser;
		return $this;
	}

	public static function getModelObj() {
		return new CusWithdrawalsDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>