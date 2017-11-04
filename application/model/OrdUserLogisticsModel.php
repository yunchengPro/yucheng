<?php
/**
* 用户物流信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:42:54Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class OrdUserLogisticsModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_mobile 	= null;

	protected $_realname 	= null;

	protected $_cityId 	= null;

	protected $_city 	= null;

	protected $_address 	= null;

	protected $_isdefault 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('OrdUserLogistics');
	}

	/**
	 *
	 * 添加用户物流信息表
	 */
	public function add() {
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_mobile  		= $this->_mobile;
		$this->_modelObj->_realname  		= $this->_realname;
		$this->_modelObj->_cityId  		= $this->_cityId;
		$this->_modelObj->_city  		= $this->_city;
		$this->_modelObj->_address  		= $this->_address;
		$this->_modelObj->_isdefault  		= $this->_isdefault;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户物流信息表
	 */
	public function modify($id) {
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_mobile  = $this->_mobile;
		$this->_modelObj->_realname  = $this->_realname;
		$this->_modelObj->_cityId  = $this->_cityId;
		$this->_modelObj->_city  = $this->_city;
		$this->_modelObj->_address  = $this->_address;
		$this->_modelObj->_isdefault  = $this->_isdefault;
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
	* @user 根据用户id 获取收货地址信息
	* @param $customerid 用户id
	* @author jeeluo
	* @date 2017年3月3日下午8:58:27
	*/
	public function getByCustomerid($customerid = null) {
	    $this->_customerid = is_null($customerid) ? $this->_customerid : $customerid;
	    $this->_dataInfo = $this->_modelObj->getRow(array("customerid" => $this->_customerid));
	    return $this->_dataInfo;
	}

	/** 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }

	/*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
    	return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
    }

    /**
     * 获取用户的默认物流信息
     * @Author   zhuangqm
     * @DateTime 2017-03-07T11:56:06+0800
     * @param    [type]                   $customerid [description]
     * @return   [type]                               [description]
     */
    public function getUserDefaultLogistics($param){
    	
    	$customerid = $param['customerid'];

    	$logisticsid = $param['logisticsid'];

    	$logistics = [];
    	if(!empty($customerid)){
	    	$logistics = $this->getRow(["customerid"=>$customerid,"isdefault"=>1],"id as address_id,mobile,realname,city_id,city,address");

	    	if(empty($logistics)){
	    		$logistics = $this->getRow(["customerid"=>$customerid],"id as address_id,mobile,realname,city_id,city,address","id desc");
	    	}
    	}else if(!empty($logisticsid)){
    		$logistics = $this->getRow(["id"=>$logisticsid],"id as address_id,mobile,realname,city_id,city,address");
    	}
    	
    	return $logistics;
    }


     /*
    * 写入数据
    * $insertData = array() 如果ID是自增 返回生成主键ID
    */
    public function insert($insertData){
		return $this->_modelObj->insert($insertData);
    }

    /*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
    	return $this->_modelObj->update($updateData,$where,$limit);
    }	
	/**
	 * 获取所有用户物流信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}


    /*
    * 删除数据
    */
    public function delete($where,$limit=''){
    	return $this->_modelObj->delete($where,$limit);
    }

	/**
	 *
	 * 删除用户物流信息表
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
	 * 设置用户ID
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置收货人手机号码
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置收货人
	 *
	 */
	public function setRealname($realname) {
		$this->_realname = $realname;
		return $this;
	}

	/**
	 * 设置收货所在地区编号
	 *
	 */
	public function setCityId($cityId) {
		$this->_cityId = $cityId;
		return $this;
	}

	/**
	 * 设置收货所在地区名称
	 *
	 */
	public function setCity($city) {
		$this->_city = $city;
		return $this;
	}

	/**
	 * 设置收获详细地址
	 *
	 */
	public function setAddress($address) {
		$this->_address = $address;
		return $this;
	}

	/**
	 * 设置是否默认0否1是
	 *
	 */
	public function setIsdefault($isdefault) {
		$this->_isdefault = $isdefault;
		return $this;
	}

	public static function getModelObj() {
		return new OrdUserLogisticsDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>