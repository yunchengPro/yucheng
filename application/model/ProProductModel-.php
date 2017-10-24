<?php
/**
* 商品类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-02 14:33:34Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class ProProductModel {

	protected $_modelObj;

	public function __construct() {
		$this->_modelObj = Db::Table('ProProduct');
	}

	/**
	 *
	 * 添加
	 */
	public function add() {
		
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新
	 */
	public function modify($id) {
		
		return $this->_modelObj->modify($id);
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

	/**
	 *
	 * 列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除
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
	 * 设置手机
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置登录名
	 *
	 */
	public function setUsername($username) {
		$this->_username = $username;
		return $this;
	}

	/**
	 * 设置密码
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
		return new CusCustomerDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>