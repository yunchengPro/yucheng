<?php
/**
* 店铺收款流水表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:14:37Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoPayFlowModel {

	protected $_id 	= null;

	protected $_payCode 	= null;

	protected $_businessid 	= null;

	protected $_addcustomerid 	= null;

	protected $_addcustomername 	= null;

	protected $_status 	= null;

	protected $_amount 	= null;

	protected $_noinvamount 	= null;

	protected $_addtime 	= null;

	protected $_paytime 	= null;

	protected $_payamount 	= null;

	protected $_paycustomerid 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoPayFlow');
	}

	/**
	 * [getBusinessCode 交易流水号]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-10T17:57:54+0800
	 * @return   [type]                   [description]
	 */
	public function getPayCode(){
		return "NNHSTO".date("YmdHis").rand(100000,999999);
	}

	public function getCostPayCode(){
		return "NNHSTB".date("YmdHis").rand(100000,999999);
	}

	/**
	 *
	 * 添加店铺收款流水表
	 */
	public function add() {
		$this->_modelObj->_payCode  		= $this->_payCode;
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_addcustomerid  		= $this->_addcustomerid;
		$this->_modelObj->_addcustomername  		= $this->_addcustomername;
		$this->_modelObj->_status  		= $this->_status;
		$this->_modelObj->_amount  		= $this->_amount;
		$this->_modelObj->_noinvamount  		= $this->_noinvamount;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_paytime  		= $this->_paytime;
		$this->_modelObj->_payamount  		= $this->_payamount;
		$this->_modelObj->_paycustomerid  		= $this->_paycustomerid;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新店铺收款流水表
	 */
	public function modify($id) {
		$this->_modelObj->_payCode  = $this->_payCode;
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_addcustomerid  = $this->_addcustomerid;
		$this->_modelObj->_addcustomername  = $this->_addcustomername;
		$this->_modelObj->_status  = $this->_status;
		$this->_modelObj->_amount  = $this->_amount;
		$this->_modelObj->_noinvamount  = $this->_noinvamount;
		$this->_modelObj->_addtime  = $this->_addtime;
		$this->_modelObj->_paytime  = $this->_paytime;
		$this->_modelObj->_payamount  = $this->_payamount;
		$this->_modelObj->_paycustomerid  = $this->_paycustomerid;
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
        return $this->_modelObj->getRow($where,$field,$order,$otherstr='');
    }
    /*
    * 获取多行记录
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
	 * 获取所有店铺收款流水表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除店铺收款流水表
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
	 * 设置交易流水号
	 *
	 */
	public function setPayCode($payCode) {
		$this->_payCode = $payCode;
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
	 * 设置操作用户ID
	 *
	 */
	public function setAddcustomerid($addcustomerid) {
		$this->_addcustomerid = $addcustomerid;
		return $this;
	}

	/**
	 * 设置操作用户名称
	 *
	 */
	public function setAddcustomername($addcustomername) {
		$this->_addcustomername = $addcustomername;
		return $this;
	}

	/**
	 * 设置状态0未支付1已支付2支付异常
	 *
	 */
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}

	/**
	 * 设置消费总金额
	 *
	 */
	public function setAmount($amount) {
		$this->_amount = $amount;
		return $this;
	}

	/**
	 * 设置不参与消费金额
	 *
	 */
	public function setNoinvamount($noinvamount) {
		$this->_noinvamount = $noinvamount;
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

	/**
	 * 设置支付时间
	 *
	 */
	public function setPaytime($paytime) {
		$this->_paytime = $paytime;
		return $this;
	}

	/**
	 * 设置实际支付金额
	 *
	 */
	public function setPayamount($payamount) {
		$this->_payamount = $payamount;
		return $this;
	}

	/**
	 * 设置支付用户ID
	 *
	 */
	public function setPaycustomerid($paycustomerid) {
		$this->_paycustomerid = $paycustomerid;
		return $this;
	}

	public static function getModelObj() {
		return new StoPayFlowDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>