<?php
/**
* 用户表API登录令牌类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:08:24Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusCustomerPaypwdModel {

	protected $_id 	= null;

	protected $_paypwd 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusCustomerPaypwd');
	}

	/**
	 *
	 * 添加用户表API登录令牌
	 */
// 	public function add() {
// 		$this->_modelObj->_paypwd  		= $this->_paypwd;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    $this->_modelObj->_id = $data['id'];
	    $this->_modelObj->_paypwd = $data['paypwd'];
	    return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新用户表API登录令牌
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_paypwd  = $this->_paypwd;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	public function updatemodify($data, $where) {
	    $this->_modelObj->_paypwd = $data['paypwd'];
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

	public function getRow($where,$field='*',$order='',$otherstr=''){
		return $this->_modelObj->getRow($where,$field,$order,$otherstr='');
	}

	/**
	 *
	 * 用户表API登录令牌列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
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
	 * 设置用户id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置支付密码
	 *
	 */
	public function setPaypwd($paypwd) {
		$this->_paypwd = $paypwd;
		return $this;
	}

	public static function getModelObj() {
		return new CusCustomerPaypwdDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>