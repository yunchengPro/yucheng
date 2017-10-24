<?php
/**
* 用户表API登录令牌类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:07:39Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusCustomerLoginModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_mtoken 	= null;

	protected $_devicetoken 	= null;

	protected $_logintime 	= null;

	protected $_devtype 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusCustomerLogin');
	}

	/**
	 *
	 * 添加用户表API登录令牌
	 */
// 	public function add() {
// 		$this->_modelObj->_customerid  		= $this->_customerid;
// 		$this->_modelObj->_mtoken  		= $this->_mtoken;
// 		$this->_modelObj->_devicetoken  		= $this->_devicetoken;
// 		$this->_modelObj->_logintime  		= $this->_logintime;
// 		$this->_modelObj->_devtype  		= $this->_devtype;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    $this->_modelObj->_customerid  		= $data['customerid'];
	    $this->_modelObj->_mtoken  		= $data['mtoken'];
	    $this->_modelObj->_devicetoken  		= $data['devicetoken'];
	    $this->_modelObj->_logintime  		= $data['logintime'];
	    $this->_modelObj->_devtype  		= $data['devtype'];
	    return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户表API登录令牌
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_customerid  = $this->_customerid;
// 		$this->_modelObj->_mtoken  = $this->_mtoken;
// 		$this->_modelObj->_devicetoken  = $this->_devicetoken;
// 		$this->_modelObj->_logintime  = $this->_logintime;
// 		$this->_modelObj->_devtype  = $this->_devtype;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modifyUpdate($data, $where) {
	    $this->_modelObj->_customerid  = $data['customerid'];
	    $this->_modelObj->_mtoken  = $data['mtoken'];
	    $this->_modelObj->_devicetoken  = $data['devicetoken'];
	    $this->_modelObj->_logintime  = $data['logintime'];
	    $this->_modelObj->_devtype  = $data['devtype'];
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
    public function pageList($where,$field='*',$order='',$flag=1){
    	return $this->_modelObj->pageList($where,$field,$order,$flag);
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
	 * 获取所有用户表API登录令牌
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户表API登录令牌
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
	 * 设置登录token
	 *
	 */
	public function setMtoken($mtoken) {
		$this->_mtoken = $mtoken;
		return $this;
	}

	/**
	 * 设置设备token--消息推送
	 *
	 */
	public function setDevicetoken($devicetoken) {
		$this->_devicetoken = $devicetoken;
		return $this;
	}

	/**
	 * 设置登陆时间
	 *
	 */
	public function setLogintime($logintime) {
		$this->_logintime = $logintime;
		return $this;
	}

	/**
	 * 设置设备类型
	 *
	 */
	public function setDevtype($devtype) {
		$this->_devtype = $devtype;
		return $this;
	}

	public static function getModelObj() {
		return new CusCustomerLoginDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>