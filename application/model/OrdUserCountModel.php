<?php
/**
* 用户订单数量信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:41:15Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

use app\lib\model\RedisModel;

class OrdUserCountModel extends RedisModel{

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_countPay 	= null;

	protected $_countDeliver 	= null;

	protected $_countReceipt 	= null;

	protected $_countEvaluate 	= null;

	protected $_countCart 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	protected $_modelname = "OrdUserCount";

	public function __construct() {
		$this->_modelObj = Db::Table($this->_modelname);
	}

	/**
	 *
	 * 添加用户订单数量信息表
	 */
	public function add() {
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_countPay  		= $this->_countPay;
		$this->_modelObj->_countDeliver  		= $this->_countDeliver;
		$this->_modelObj->_countReceipt  		= $this->_countReceipt;
		$this->_modelObj->_countEvaluate  		= $this->_countEvaluate;
		$this->_modelObj->_countCart  		= $this->_countCart;
		return $this->_modelObj->add();
	}
	
	// public function insert($data) {
	//     return $this->_modelObj->insert($data);
	// }

	/**
	 *
	 * 更新用户订单数量信息表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_customerid  = $this->_customerid;
// 		$this->_modelObj->_countPay  = $this->_countPay;
// 		$this->_modelObj->_countDeliver  = $this->_countDeliver;
// 		$this->_modelObj->_countReceipt  = $this->_countReceipt;
// 		$this->_modelObj->_countEvaluate  = $this->_countEvaluate;
// 		$this->_modelObj->_countCart  = $this->_countCart;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id = null,$field="*") {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getRow(["id"=>$id],$field);
		return $this->_dataInfo;
	}
	
	/*
	 * 获取单条数据
	 * $where 可以是字符串 也可以是数组
	 */
	// public function getRow($where,$field='*',$order='',$otherstr=''){
	//     return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	// }
	
	/**
	* @user 根据用户id 查询用户订单个数
	* @param $customerid 用户id
	* @author jeeluo
	* @date 2017年3月3日下午7:19:32
	*/
	public function getByCustomerid($customerid = null) {
	    $this->_customerid = is_null($customerid) ? $this->_customerid : $customerid;
	    $this->_dataInfo = $this->_modelObj->getRow(array("customerid" => $customerid));
	    return $this->_dataInfo;
	}

	/**
	 *
	 * 用户订单数量信息表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有用户订单数量信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户订单数量信息表
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
	 * 设置待付款数
	 *
	 */
	public function setCountPay($countPay) {
		$this->_countPay = $countPay;
		return $this;
	}

	/**
	 * 设置待发货数
	 *
	 */
	public function setCountDeliver($countDeliver) {
		$this->_countDeliver = $countDeliver;
		return $this;
	}

	/**
	 * 设置待收货数
	 *
	 */
	public function setCountReceipt($countReceipt) {
		$this->_countReceipt = $countReceipt;
		return $this;
	}

	/**
	 * 设置待评价数
	 *
	 */
	public function setCountEvaluate($countEvaluate) {
		$this->_countEvaluate = $countEvaluate;
		return $this;
	}

	/**
	 * 设置购物车数
	 *
	 */
	public function setCountCart($countCart) {
		$this->_countCart = $countCart;
		return $this;
	}

	public static function getModelObj() {
		return new OrdUserCountDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>