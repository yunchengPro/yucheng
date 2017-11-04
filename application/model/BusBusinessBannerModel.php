<?php
/**
* app首页显示模块明细表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-07 14:15:46Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class BusBusinessBannerModel {

	protected $_id 	= null;

	protected $_businessid	= null;

	protected $_bname 	= null;

	protected $_thumb 	= null;

	protected $_urltype 	= null;

	protected $_url 	= null;

	protected $_sort 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('BusBusinessBanner');
	}

	/*
     * 负责把表单提交来的数组
     * 清除掉不用的单元
     * 留下与表的字段对应的单元
     */
    public function _facade($array = []){
        return $this->_modelObj->_facade($array);
    }

	/**
	 *
	 * 添加app首页显示模块明细表
	 */
	public function add() {
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_bname  		= $this->_bname;
		$this->_modelObj->_thumb  		= $this->_thumb;
		$this->_modelObj->_urltype  		= $this->_urltype;
		$this->_modelObj->_url  		= $this->_url;
		$this->_modelObj->_sort  		= $this->_sort;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新app首页显示模块明细表
	 */
	public function modify($id) {
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_bname  = $this->_bname;
		$this->_modelObj->_thumb  = $this->_thumb;
		$this->_modelObj->_urltype  = $this->_urltype;
		$this->_modelObj->_url  = $this->_url;
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

	/**
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
    public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
    	return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
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
	 * 获取所有app首页显示模块明细表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除app首页显示模块明细表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置主键ID
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置模块id
	 *
	 */
	public function setModuleid($moduleid) {
		$this->_moduleid = $moduleid;
		return $this;
	}

	/**
	 * 设置banner名称
	 *
	 */
	public function setBname($bname) {
		$this->_bname = $bname;
		return $this;
	}

	/**
	 * 设置图片地址
	 *
	 */
	public function setThumb($thumb) {
		$this->_thumb = $thumb;
		return $this;
	}

	/**
	 * 设置跳转类型1为跳原生app2为跳转H5等
	 *
	 */
	public function setUrltype($urltype) {
		$this->_urltype = $urltype;
		return $this;
	}

	/**
	 * 设置跳转链接有可能是H5页面url也有可能是id具体看app跳转方式
	 *
	 */
	public function setUrl($url) {
		$this->_url = $url;
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
		return new MallModuleBannerDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>