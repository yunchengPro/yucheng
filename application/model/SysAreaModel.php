<?php
/**
* 地区信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:34:45Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class SysAreaModel {

	protected $_id 	= null;

	protected $_areaname 	= null;

	protected $_parentid 	= null;

	protected $_shortname 	= null;

	protected $_areacode 	= null;

	protected $_zipcode 	= null;

	protected $_pinyin 	= null;

	protected $_lng 	= null;

	protected $_lat 	= null;

	protected $_level 	= null;

	protected $_position 	= null;

	protected $_sort 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('SysArea');
	}

	/**
	 *
	 * 添加地区信息表
	 */
	public function add() {
		$this->_modelObj->_areaname  		= $this->_areaname;
		$this->_modelObj->_parentid  		= $this->_parentid;
		$this->_modelObj->_shortname  		= $this->_shortname;
		$this->_modelObj->_areacode  		= $this->_areacode;
		$this->_modelObj->_zipcode  		= $this->_zipcode;
		$this->_modelObj->_pinyin  		= $this->_pinyin;
		$this->_modelObj->_lng  		= $this->_lng;
		$this->_modelObj->_lat  		= $this->_lat;
		$this->_modelObj->_level  		= $this->_level;
		$this->_modelObj->_position  		= $this->_position;
		$this->_modelObj->_sort  		= $this->_sort;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新地区信息表
	 */
	public function modify($id) {
		$this->_modelObj->_areaname  = $this->_areaname;
		$this->_modelObj->_parentid  = $this->_parentid;
		$this->_modelObj->_shortname  = $this->_shortname;
		$this->_modelObj->_areacode  = $this->_areacode;
		$this->_modelObj->_zipcode  = $this->_zipcode;
		$this->_modelObj->_pinyin  = $this->_pinyin;
		$this->_modelObj->_lng  = $this->_lng;
		$this->_modelObj->_lat  = $this->_lat;
		$this->_modelObj->_level  = $this->_level;
		$this->_modelObj->_position  = $this->_position;
		$this->_modelObj->_sort  = $this->_sort;
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

	  /*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }
	/**
	 *
	 * 地区信息表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr='') {
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
    	return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
    }

	/**
	 * 获取所有地区信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除地区信息表
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
	 * 设置上级id
	 *
	 */
	public function setParentid($parentid) {
		$this->_parentid = $parentid;
		return $this;
	}

	/**
	 * 设置短名称
	 *
	 */
	public function setShortname($shortname) {
		$this->_shortname = $shortname;
		return $this;
	}

	/**
	 * 设置地区编号
	 *
	 */
	public function setAreacode($areacode) {
		$this->_areacode = $areacode;
		return $this;
	}

	/**
	 * 设置
	 *
	 */
	public function setZipcode($zipcode) {
		$this->_zipcode = $zipcode;
		return $this;
	}

	/**
	 * 设置地区拼音
	 *
	 */
	public function setPinyin($pinyin) {
		$this->_pinyin = $pinyin;
		return $this;
	}

	/**
	 * 设置经度
	 *
	 */
	public function setLng($lng) {
		$this->_lng = $lng;
		return $this;
	}

	/**
	 * 设置纬度
	 *
	 */
	public function setLat($lat) {
		$this->_lat = $lat;
		return $this;
	}

	/**
	 * 设置级别
	 *
	 */
	public function setLevel($level) {
		$this->_level = $level;
		return $this;
	}

	/**
	 * 设置描述
	 *
	 */
	public function setPosition($position) {
		$this->_position = $position;
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
		return new SysAreaDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>