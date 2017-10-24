<?php
/**
* 慈善流水表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-20 11:17:27Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class AmoFlowCharitableModel {

	protected $_id 	= null;

	protected $_flowid 	= null;

	protected $_flowtype 	= null;

	protected $_direction 	= null;

	protected $_amount 	= null;

	protected $_finalAmount 	= null;

	protected $_beforeamount 	= null;

	protected $_afteramount 	= null;

	protected $_remark 	= null;

	protected $_flowtime 	= null;

	protected $_userid 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('AmoFlowCharitable');
	}

	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

    public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	public function delete($where,$limit=''){
		return $this->_modelObj->delete($where,$limit);
    }

	/**
	 *
	 * 添加慈善流水表
	 */
	public function add() {
		$this->_modelObj->_flowid  		= $this->_flowid;
		$this->_modelObj->_flowtype  		= $this->_flowtype;
		$this->_modelObj->_direction  		= $this->_direction;
		$this->_modelObj->_amount  		= $this->_amount;
		$this->_modelObj->_finalAmount  		= $this->_finalAmount;
		$this->_modelObj->_beforeamount  		= $this->_beforeamount;
		$this->_modelObj->_afteramount  		= $this->_afteramount;
		$this->_modelObj->_remark  		= $this->_remark;
		$this->_modelObj->_flowtime  		= $this->_flowtime;
		$this->_modelObj->_userid  		= $this->_userid;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新慈善流水表
	 */
	public function modify($id) {
		$this->_modelObj->_flowid  = $this->_flowid;
		$this->_modelObj->_flowtype  = $this->_flowtype;
		$this->_modelObj->_direction  = $this->_direction;
		$this->_modelObj->_amount  = $this->_amount;
		$this->_modelObj->_finalAmount  = $this->_finalAmount;
		$this->_modelObj->_beforeamount  = $this->_beforeamount;
		$this->_modelObj->_afteramount  = $this->_afteramount;
		$this->_modelObj->_remark  = $this->_remark;
		$this->_modelObj->_flowtime  = $this->_flowtime;
		$this->_modelObj->_userid  = $this->_userid;
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
	 * 慈善流水表列表
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

	/*
	 * 获取单条数据
	 * $where 可以是字符串 也可以是数组
	 */
	public function getRow($where,$field='*',$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}

	/**
	 * 获取所有慈善流水表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除慈善流水表
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
	 * 设置流水号
	 *
	 */
	public function setFlowid($flowid) {
		$this->_flowid = $flowid;
		return $this;
	}

	/**
	 * 设置流水类型1订单支付2购买获取3收益分润4代理分享费用5充值
	 *
	 */
	public function setFlowtype($flowtype) {
		$this->_flowtype = $flowtype;
		return $this;
	}

	/**
	 * 设置1收入2支出
	 *
	 */
	public function setDirection($direction) {
		$this->_direction = $direction;
		return $this;
	}

	/**
	 * 设置金额|数量
	 *
	 */
	public function setAmount($amount) {
		$this->_amount = $amount;
		return $this;
	}

	/**
	 * 设置最终金额|数量
	 *
	 */
	public function setFinalAmount($finalAmount) {
		$this->_finalAmount = $finalAmount;
		return $this;
	}

	/**
	 * 设置操作前金额
	 *
	 */
	public function setBeforeamount($beforeamount) {
		$this->_beforeamount = $beforeamount;
		return $this;
	}

	/**
	 * 设置操作后金额
	 *
	 */
	public function setAfteramount($afteramount) {
		$this->_afteramount = $afteramount;
		return $this;
	}

	/**
	 * 设置描述
	 *
	 */
	public function setRemark($remark) {
		$this->_remark = $remark;
		return $this;
	}

	/**
	 * 设置流水时间
	 *
	 */
	public function setFlowtime($flowtime) {
		$this->_flowtime = $flowtime;
		return $this;
	}

	/**
	 * 设置对象ID
	 *
	 */
	public function setUserid($userid) {
		$this->_userid = $userid;
		return $this;
	}

	public static function getModelObj() {
		return new AmoFlowCharitableDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>