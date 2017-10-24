<?php
/**
* 商家菜单表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-13 14:59:00Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class BusSysMenuModel {

	protected $_id 	= null;

	protected $_parentid 	= null;

	protected $_menuname 	= null;

	protected $_selected 	= null;

	protected $_url 	= null;

	protected $_enable 	= null;

	protected $_sort 	= null;

	protected $_ispage 	= null;

	protected $_isshow 	= null;

	protected $_rightvalue 	= null;

	protected $_icon 	= null;

	protected $_class 	= null;

	protected $_isdelete 	= null;

	protected $_createby 	= null;

	protected $_createtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('BusSysMenu');
	}

	/**
	 *
	 * 添加商家菜单表
	 */
	public function add() {
		$this->_modelObj->_parentid  		= $this->_parentid;
		$this->_modelObj->_menuname  		= $this->_menuname;
		$this->_modelObj->_selected  		= $this->_selected;
		$this->_modelObj->_url  		= $this->_url;
		$this->_modelObj->_enable  		= $this->_enable;
		$this->_modelObj->_sort  		= $this->_sort;
		$this->_modelObj->_ispage  		= $this->_ispage;
		$this->_modelObj->_isshow  		= $this->_isshow;
		$this->_modelObj->_rightvalue  		= $this->_rightvalue;
		$this->_modelObj->_icon  		= $this->_icon;
		$this->_modelObj->_class  		= $this->_class;
		$this->_modelObj->_isdelete  		= $this->_isdelete;
		$this->_modelObj->_createby  		= $this->_createby;
		$this->_modelObj->_createtime  		= $this->_createtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商家菜单表
	 */
	public function modify($id) {
		$this->_modelObj->_parentid  = $this->_parentid;
		$this->_modelObj->_menuname  = $this->_menuname;
		$this->_modelObj->_selected  = $this->_selected;
		$this->_modelObj->_url  = $this->_url;
		$this->_modelObj->_enable  = $this->_enable;
		$this->_modelObj->_sort  = $this->_sort;
		$this->_modelObj->_ispage  = $this->_ispage;
		$this->_modelObj->_isshow  = $this->_isshow;
		$this->_modelObj->_rightvalue  = $this->_rightvalue;
		$this->_modelObj->_icon  = $this->_icon;
		$this->_modelObj->_class  = $this->_class;
		$this->_modelObj->_isdelete  = $this->_isdelete;
		$this->_modelObj->_createby  = $this->_createby;
		$this->_modelObj->_createtime  = $this->_createtime;
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

	/*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
    	return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
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
	 * 获取所有商家菜单表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除商家菜单表
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
	 * 设置上级id
	 *
	 */
	public function setParentid($parentid) {
		$this->_parentid = $parentid;
		return $this;
	}

	/**
	 * 设置菜单名称
	 *
	 */
	public function setMenuname($menuname) {
		$this->_menuname = $menuname;
		return $this;
	}

	/**
	 * 设置是否选中
	 *
	 */
	public function setSelected($selected) {
		$this->_selected = $selected;
		return $this;
	}

	/**
	 * 设置菜单链接地址
	 *
	 */
	public function setUrl($url) {
		$this->_url = $url;
		return $this;
	}

	/**
	 * 设置是否启用1启用-1不禁用
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
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
	 * 设置是否页面
	 *
	 */
	public function setIspage($ispage) {
		$this->_ispage = $ispage;
		return $this;
	}

	/**
	 * 设置是否显示在Tab
	 *
	 */
	public function setIsshow($isshow) {
		$this->_isshow = $isshow;
		return $this;
	}

	/**
	 * 设置当为页面时 填写对应的权值 系统自动生成
	 *
	 */
	public function setRightvalue($rightvalue) {
		$this->_rightvalue = $rightvalue;
		return $this;
	}

	/**
	 * 设置菜单图标
	 *
	 */
	public function setIcon($icon) {
		$this->_icon = $icon;
		return $this;
	}

	/**
	 * 设置菜单class引用图标需要
	 *
	 */
	public function setClass($class) {
		$this->_class = $class;
		return $this;
	}

	/**
	 * 设置是否删除
	 *
	 */
	public function setIsdelete($isdelete) {
		$this->_isdelete = $isdelete;
		return $this;
	}

	/**
	 * 设置创建人
	 *
	 */
	public function setCreateby($createby) {
		$this->_createby = $createby;
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
		return new BusSysMenuDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>