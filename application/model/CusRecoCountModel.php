<?php
/**
* 用户信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:03:01Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\lib\Model;
use think\Cache;
use app\model\Sys\CommonModel;

class CusRecoCountModel {

	protected $_id 	= null;

	protected $_mobile 	= null;

	protected $_username 	= null;

	protected $_userpwd 	= null;

	protected $_enable 	= null;

	protected $_createtime 	= null;

	public $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;
	// 初始值
	const initNumber = 0;

	public function __construct() {
		$this->_modelObj = Db::Table('CusRecoCount');
	}

	/**
	 *
	 * 添加用户信息表
	 */
// 	public function add() {
// 		$this->_modelObj->_mobile  		= $this->_mobile;
// 		$this->_modelObj->_username  		= $this->_username;
// 		$this->_modelObj->_userpwd  		= $this->_userpwd;
// 		$this->_modelObj->_enable  		= $this->_enable;
// 		$this->_modelObj->_createtime  		= $this->_createtime;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
        return $this->insert($data);
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新用户信息表
	 */
	public function modifyUpdate($data, $where) {
	    return $this->modify($data, $where);
	}
	
	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * 详细
	 */

	public function getById($id = null) {
	    $this->_id = is_null($id)? $this->_id : $id;
	    $this->_dataInfo = $this->_modelObj->getRow(array("id" => $this->_id));
	    return $this->_dataInfo;
	}
	
	/**
	* @user 通过手机号码查询用户id
	* @param $mobile 手机号码
	* @author jeeluo
	* @date 2017年3月2日下午5:09:26
	*/
	public function getIdByMobile($mobile = null) {
	    //$this->_mobile = is_null($mobile) ? $this->_mobile : $mobile;
	    $this->_dataInfo = $this->_modelObj->getRow(array("mobile" => $mobile), "id");
	    return $this->_dataInfo;
	}

	
	/*
    * 获取单条数据
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
	
	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1){
	    return $this->_modelObj->pageList($where,$field,$order,$flag);
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
	 * 设置联系手机
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置用户名账号
	 *
	 */
	public function setUsername($username) {
		$this->_username = $username;
		return $this;
	}

	/**
	 * 设置登录密码
	 *
	 */
	public function setUserpwd($userpwd) {
		$this->_userpwd = $userpwd;
		return $this;
	}

	/**
	 * 设置状态1启用2禁用
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
		return $this;
	}

	/**
	 * 设置创建时间
	 *
	 */
	public function setCreatetime($createtime) {
		$this->_createtime = $createtime;
		return $this;
	}

	public static function getModelObj() {
		return new CusRecoCountDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}

	public function close() {
	    return $this->_modelObj->close();
	}
}
?>