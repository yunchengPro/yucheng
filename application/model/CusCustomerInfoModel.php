<?php
/**
* 用户信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:06:18Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\lib\Model;

class CusCustomerInfoModel {

	protected $_id 	= null;

	protected $_customerCode 	= null;

	protected $_fromtype 	= null;

	protected $_realname 	= null;

	protected $_nickname 	= null;

	protected $_sex 	= null;

	protected $_headerpic 	= null;

	protected $_email 	= null;

	protected $_idnumber 	= null;

	protected $_borndate 	= null;

	protected $_area 	= null;
	
	protected $_address    = null;

	protected $_areaCode 	= null;

	protected $_lastlogintime 	= null;

	protected $_loginnum 	= null;

	protected $_isnameauth 	= null;

	protected $_payamount 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusCustomerInfo');
	}

	/**
	 *
	 * 添加用户信息表
	 */
// 	public function add() {
// 		$this->_modelObj->_customerCode  		= $this->_customerCode;
// 		$this->_modelObj->_fromtype  		= $this->_fromtype;
// 		$this->_modelObj->_realname  		= $this->_realname;
// 		$this->_modelObj->_nickname  		= $this->_nickname;
// 		$this->_modelObj->_sex  		= $this->_sex;
// 		$this->_modelObj->_headerpic  		= $this->_headerpic;
// 		$this->_modelObj->_email  		= $this->_email;
// 		$this->_modelObj->_idnumber  		= $this->_idnumber;
// 		$this->_modelObj->_borndate  		= $this->_borndate;
// 		$this->_modelObj->_area  		= $this->_area;
// 		$this->_modelObj->_areaCode  		= $this->_areaCode;
// 		$this->_modelObj->_lastlogintime  		= $this->_lastlogintime;
// 		$this->_modelObj->_loginnum  		= $this->_loginnum;
// 		$this->_modelObj->_isnameauth  		= $this->_isnameauth;
// 		$this->_modelObj->_payamount  		= $this->_payamount;
// 		return $this->_modelObj->add();
// 	}

	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	public function add($data) {
// 	    $this->_modelObj->_customerCode  		= $data['customerCode'];
// 	    $this->_modelObj->_fromtype  		= $data['fromtype'];
// 	    $this->_modelObj->_realname  		= $data['realname'];
// 	    $this->_modelObj->_nickname  		= $data['nickname'];
// 	    $this->_modelObj->_sex  		= $data['sex'];
// 	    $this->_modelObj->_headerpic  		= $data['headerpic'];
// 	    $this->_modelObj->_email  		= $data['email'];
// 	    $this->_modelObj->_idnumber  		= $data['idnumber'];
// 	    $this->_modelObj->_borndate  		= $data['borndate'];
// 	    $this->_modelObj->_area         	= $data['area'];
// 	    $this->_modelObj->_address         = $data['address'];
// 	    $this->_modelObj->_areaCode        = $data['area_code'];
// 	    $this->_modelObj->_lastlogintime  		= $data['lastlogintime'];
// 	    $this->_modelObj->_loginnum  		= $data['loginnum'];
// 	    $this->_modelObj->_isnameauth  		= $data['isnameauth'];
// 	    $this->_modelObj->_payamount  		= $data['payamount'];
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户信息表
	 */

// 	public function modify($data) {
// 	    $this->_modelObj->_customerCode  = $data['customerCode'];
// 	    $this->_modelObj->_fromtype  = $data['fromtype'];
// 	    $this->_modelObj->_realname  = $data['realname'];
// 	    $this->_modelObj->_nickname  = $data['nickname'];
// 	    $this->_modelObj->_sex  = $data['sex'];
// 	    $this->_modelObj->_headerpic  = $data['headerpic'];
// 	    $this->_modelObj->_email  = $data['email'];
// 	    $this->_modelObj->_idnumber  = $data['idnumber'];
// 	    $this->_modelObj->_borndate  = $data['borndate'];
// 	    $this->_modelObj->_area         	= $data['area'];
// 	    $this->_modelObj->_address         = $data['address'];
// 	    $this->_modelObj->_areaCode        = $data['area_code'];
// 	    $this->_modelObj->_lastlogintime  = $data['lastlogintime'];
// 	    $this->_modelObj->_loginnum  = $data['loginnum'];
// 	    $this->_modelObj->_isnameauth  = $data['isnameauth'];
// 	    $this->_modelObj->_payamount  = $data['payamount'];
// 	    return $this->_modelObj->modify($data['id']);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}
	
	public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}
	
    /**
    * @user 
    * @param 
    * @author jeeluo
    * @date 2017年3月4日下午3:11:30
    */
	public function modifyUpdate($data, $where) {
	    return $this->modify($data, $where);
	}
	
	/**
	* @user 个人首页(修改个人资料)
	* @param $data 
	* @author jeeluo
	* @date 2017年3月4日上午11:01:34
	*/
	public function updateInfo($data) {
		/*
	    $this->_modelObj->nickname = $data['nickname'];
	    $this->_modelObj->sex = $data['sex'];
	    $this->_modelObj->_headerpic = $data['headerpic'];
	    return $this->_modelObj->modify($data['id']);
	    */
	    $update = [];
	    $updateRedis = [];
	    if(!empty($data['nickname']))
	   		$update['nickname'] = $updateRedis['nickname'] = $data['nickname'];
	   	if(!empty($data['sex']))
	   		$update['sex'] = $data['sex'];
	   	if(!empty($data['headerpic']))
	   		$update['headerpic'] = $updateRedis['headerpic'] = $data['headerpic'];
	   	
	    // redis数据
	    if(!empty($updateRedis)) {
	        $updateRedis['mtoken'] = $data['mtoken'];
	        // 更新redis数据
	        Model::new("User.User")->updateMtokenRedis($updateRedis);
	    }

	    return $this->_modelObj->update($update,["id"=>$data['id']]);
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
	 * @user 通过用户id 查询登录信息
	 * @param $id 用户id
	 * @author jeeluo
	 * @date 2017年3月3日上午9:55:52
	 */
	public function getLoginById($id = null) {
	    $this->_id = is_null($id) ? $this->_id : $id;
	    $this->_dataInfo = $this->_modelObj->getRow(array("id" => $this->_id), "loginnum");
	    return $this->_dataInfo;
	}
	
	/**
	* @user 返回简单的个人信息
	* @param $id 用户id
	* @author jeeluo
	* @date 2017年3月3日下午2:15:10
	*/
	public function getSimpleById($id = null) {
	    $this->_id = is_null($id) ? $this->_id : $id;
	    $this->_dataInfo = $this->_modelObj->getRow(array("id" => $this->_id), "nickname, headerpic");
	    return $this->_dataInfo;
	}
	
	public function getInfoRow($where, $field='*', $order='', $otherstr='') {
	    return $this->getRow($where, $field, $order, $otherstr);
	}
	
	public function getRow($where, $field='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $field, $order, $otherstr);
	}
	
	/**
	* @user 根据用户id 获取平台号
	* @param $id 用户id
	* @author jeeluo
	* @date 2017年3月3日下午3:46:00
	*/
	public function getCustomerCodeById($id = null) {
	    $this->_id = is_null($id) ? $this->_id : $id;
	    $this->_dataInfo = $this->_modelObj->getRow(array("id" => $this->_id), "customer_code");
	    return $this->_dataInfo;
	}

	/**
	 *
	 * 用户信息表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}
	
	/*
	 * 获取多行记录
	 */
	public function getList_02($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
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
	 * 获取所有用户信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户信息表
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
	 * 设置平台号
	 *
	 */
	public function setCustomerCode($customerCode) {
		$this->_customerCode = $customerCode;
		return $this;
	}

	/**
	 * 设置注册来源
	 *
	 */
	public function setFromtype($fromtype) {
		$this->_fromtype = $fromtype;
		return $this;
	}

	/**
	 * 设置真实姓名
	 *
	 */
	public function setRealname($realname) {
		$this->_realname = $realname;
		return $this;
	}

	/**
	 * 设置昵称
	 *
	 */
	public function setNickname($nickname) {
		$this->_nickname = $nickname;
		return $this;
	}

	/**
	 * 设置性别1男2女
	 *
	 */
	public function setSex($sex) {
		$this->_sex = $sex;
		return $this;
	}

	/**
	 * 设置头像
	 *
	 */
	public function setHeaderpic($headerpic) {
		$this->_headerpic = $headerpic;
		return $this;
	}

	/**
	 * 设置邮箱地址
	 *
	 */
	public function setEmail($email) {
		$this->_email = $email;
		return $this;
	}

	/**
	 * 设置身份证号
	 *
	 */
	public function setIdnumber($idnumber) {
		$this->_idnumber = $idnumber;
		return $this;
	}

	/**
	 * 设置出生日期(格式1900-01-01)
	 *
	 */
	public function setBorndate($borndate) {
		$this->_borndate = $borndate;
		return $this;
	}
	
	public function setArea($area) {
	    $this->_area = $area;
	    return $this;
	}
	
	public function setAddress($address) {
	    $this->_address = $address;
	    return $this;
	}
	
	public function setAreaCode($areaCode) {
	    $this->_areaCode = $areaCode;
	    return $this;
	}

	/**
	 * 设置最近一次登陆时间
	 *
	 */
	public function setLastlogintime($lastlogintime) {
		$this->_lastlogintime = $lastlogintime;
		return $this;
	}

	/**
	 * 设置登录次数
	 *
	 */
	public function setLoginnum($loginnum) {
		$this->_loginnum = $loginnum;
		return $this;
	}

	/**
	 * 设置是否实名认证0否1是
	 *
	 */
	public function setIsnameauth($isnameauth) {
		$this->_isnameauth = $isnameauth;
		return $this;
	}

	/**
	 * 设置支付总额
	 *
	 */
	public function setPayamount($payamount) {
		$this->_payamount = $payamount;
		return $this;
	}

	public static function getModelObj() {
		return new CusCustomerInfoDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>