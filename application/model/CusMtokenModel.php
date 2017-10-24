<?php
/**
* 用户mtoken表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:10:47Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class CusMtokenModel {

	protected $_id 	= null;

	protected $_mtoken 	= null;

	protected $_devicetoken 	= null;

	protected $_devtype 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusMtoken');
	}

	/**
	 *
	 * 添加用户mtoken表
	 */
// 	public function add() {
// 		$this->_modelObj->_mtoken  		= $this->_mtoken;
// 		$this->_modelObj->_devicetoken  		= $this->_devicetoken;
// 		$this->_modelObj->_devtype  		= $this->_devtype;
// 		$this->_modelObj->_addtime  		= $this->_addtime;
// 		return $this->_modelObj->add();
// 	}

// 	public function add($data) {
// 	    $this->_modelObj->_id          = $data['id'];
// 	    $this->_modelObj->_mtoken  		= $data['mtoken'];
// 	    $this->_modelObj->_devicetoken  		= $data['devicetoken'];
// 	    $this->_modelObj->_devtype  		= $data['devtype'];
// 	    $this->_modelObj->_addtime  		= $data['addtime'];
// 	    return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->_modelObj->insert($data);
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新用户mtoken表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_mtoken  = $this->_mtoken;
// 		$this->_modelObj->_devicetoken  = $this->_devicetoken;
// 		$this->_modelObj->_devtype  = $this->_devtype;
// 		$this->_modelObj->_addtime  = $this->_addtime;
// 		return $this->_modelObj->modify($id);
// 	}
	public function modifyUpdate($data, $where) {
// 	    $this->_modelObj->_mtoken  = $data['mtoken'];
// 	    $this->_modelObj->_devicetoken  = $data['devicetoken'];
// 	    $this->_modelObj->_devtype  = $data['devtype'];
// 	    $this->_modelObj->_addtime  = $data['addtime'];
	    return $this->_modelObj->update($data, $where);
	}
	
	public function update($data, $where) {
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
	public function getRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
	}

	/**
	 *
	 * 用户mtoken表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}
	
	

	/**
	 * 获取所有用户mtoken表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除用户mtoken表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}
	
	public function delete($where) {
	    return $this->_modelObj->delete($where);
	}


	/**
	 * 设置用户ID
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置登录校验码
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
	 * 设置设备类型A,I
	 *
	 */
	public function setDevtype($devtype) {
		$this->_devtype = $devtype;
		return $this;
	}

	/**
	 * 设置登录时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	public static function getModelObj() {
		return new CusMtokenDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>