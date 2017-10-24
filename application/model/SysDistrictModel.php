<?php
/**
* 系统商圈表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-24 14:17:41Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class SysDistrictModel {

	protected $_id 	= null;

	protected $_districtName 	= null;

	protected $_areaid 	= null;

	protected $_sort 	= null;

	protected $_enable 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('SysDistrict');
	}

	/**
	 *
	 * 添加系统商圈表
	 */
// 	public function add() {
// 		$this->_modelObj->_districtName  		= $this->_districtName;
// 		$this->_modelObj->_areaid  		= $this->_areaid;
// 		$this->_modelObj->_sort  		= $this->_sort;
// 		$this->_modelObj->_enable  		= $this->_enable;
// 		return $this->_modelObj->add();
// 	}

	// public function insert($data) {
	//     return $this->_modelObj->insert($data);
	// }

	/**
	 *
	 * 更新系统商圈表
	 */
	public function modify($data, $where) {
// 		$this->_modelObj->_districtName  = $this->_districtName;
// 		$this->_modelObj->_areaid  = $this->_areaid;
// 		$this->_modelObj->_sort  = $this->_sort;
// 		$this->_modelObj->_enable  = $this->_enable;
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
	 * 系统商圈表列表
	 */
// 	public function getList($page, $pagesize) {
// 		return $this->_modelObj->getAllForPage($page, $pagesize);
// 	}
	
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
	    return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}
	
	public function pageList($where, $fields="*", $order='') {
	    return $this->_modelObj->pageList($where, $fields, $order);
	}

	/**
	 * 获取所有系统商圈表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
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
	 *
	 * 删除系统商圈表
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
	 * 设置商圈名称
	 *
	 */
	public function setDistrictName($districtName) {
		$this->_districtName = $districtName;
		return $this;
	}

	/**
	 * 设置地区id
	 *
	 */
	public function setAreaid($areaid) {
		$this->_areaid = $areaid;
		return $this;
	}

	/**
	 * 设置排序
	 *
	 */
	public function setSort($sort) {
		$this->_sort = $sort;
		return $this;
	}

	/**
	 * 设置状态(1为启用 -1禁用)
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
		return $this;
	}

	public static function getModelObj() {
		return new SysDistrictDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>