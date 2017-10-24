<?php
/**
* 地铁信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 11:57:07Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class SysMetroModel {

	protected $_id 	= null;

	protected $_areaname 	= null;

	protected $_areaid 	= null;

	protected $_linename 	= null;

	protected $_pinyin 	= null;

	protected $_metroname 	= null;

	protected $_sort 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('SysMetro');
	}

	/**
	 *
	 * 添加地铁信息表
	 */
// 	public function add() {
// 		$this->_modelObj->_areaname  		= $this->_areaname;
// 		$this->_modelObj->_areaid  		= $this->_areaid;
// 		$this->_modelObj->_linename  		= $this->_linename;
// 		$this->_modelObj->_pinyin  		= $this->_pinyin;
// 		$this->_modelObj->_metroname  		= $this->_metroname;
// 		$this->_modelObj->_sort  		= $this->_sort;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新地铁信息表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_areaname  = $this->_areaname;
// 		$this->_modelObj->_areaid  = $this->_areaid;
// 		$this->_modelObj->_linename  = $this->_linename;
// 		$this->_modelObj->_pinyin  = $this->_pinyin;
// 		$this->_modelObj->_metroname  = $this->_metroname;
// 		$this->_modelObj->_sort  = $this->_sort;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
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
	public function getInfoRow($where, $fields='*', $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $fields, $order, $otherstr);
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
	 * 地铁信息表列表
	 */
// 	public function getList($page, $pagesize) {
// 		return $this->_modelObj->getAllForPage($page, $pagesize);
// 	}

	/**
	 *
	 * 订单详细信息表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
	    return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}
	
	public function getPageList($where, $fields="*", $order='') {
	    return $this->_modelObj->pageList($where, $fields, $order);
	}

	public function pageList($where, $fields="*", $order='') {
	    return $this->_modelObj->pageList($where, $fields, $order);
	}

	/**
	 * 获取所有地铁信息表
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
	 * 删除地铁信息表
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
	 * 设置地区名称
	 *
	 */
	public function setAreaname($areaname) {
		$this->_areaname = $areaname;
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
	 * 设置线路名称
	 *
	 */
	public function setLinename($linename) {
		$this->_linename = $linename;
		return $this;
	}

	/**
	 * 设置地铁名称 拼音首字母大写
	 *
	 */
	public function setPinyin($pinyin) {
		$this->_pinyin = $pinyin;
		return $this;
	}

	/**
	 * 设置地铁名称
	 *
	 */
	public function setMetroname($metroname) {
		$this->_metroname = $metroname;
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

	public static function getModelObj() {
		return new SysMetroDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>