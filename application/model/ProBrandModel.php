<?php
/**
* 品牌表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-15 10:02:27Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class ProBrandModel {

	protected $_id 	= null;

	protected $_brandname 	= null;

	protected $_brandlogo 	= null;

	protected $_remark 	= null;

	protected $_country 	= null;

	protected $_company 	= null;

	protected $_sort 	= null;

	protected $_isdelete 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('ProBrand');
		$this->_modelObj->_fields = ['brandname','brandlogo','remark','country','company','sort','businessid','isdelete'];
	}


	/*
        负责把表单提交来的数组
        清除掉不用的单元
        留下与表的字段对应的单元
    */
	public function _facade($array = []){
		return $this->_modelObj->_facade($array);
	}

	/**
	 *
	 * 添加品牌表
	 */
	public function add() {
		$this->_modelObj->_brandname  		= $this->_brandname;
		$this->_modelObj->_brandlogo  		= $this->_brandlogo;
		$this->_modelObj->_remark  		= $this->_remark;
		$this->_modelObj->_country  		= $this->_country;
		$this->_modelObj->_company  		= $this->_company;
		$this->_modelObj->_sort  		= $this->_sort;
		$this->_modelObj->_isdelete  		= $this->_isdelete;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新品牌表
	 */
	public function modify($id) {
		$this->_modelObj->_brandname  = $this->_brandname;
		$this->_modelObj->_brandlogo  = $this->_brandlogo;
		$this->_modelObj->_remark  = $this->_remark;
		$this->_modelObj->_country  = $this->_country;
		$this->_modelObj->_company  = $this->_company;
		$this->_modelObj->_sort  = $this->_sort;
		$this->_modelObj->_isdelete  = $this->_isdelete;
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
    public function pageList($where,$field='*',$order='',$flag=1){
    	return $this->_modelObj->pageList($where,$field,$order,$flag);
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
	 * 获取所有品牌表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除品牌表
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
	 * 设置品牌名称
	 *
	 */
	public function setBrandname($brandname) {
		$this->_brandname = $brandname;
		return $this;
	}

	/**
	 * 设置品牌logo
	 *
	 */
	public function setBrandlogo($brandlogo) {
		$this->_brandlogo = $brandlogo;
		return $this;
	}

	/**
	 * 设置描述
	 *
	 */
	public function setRemark($remark) {
		$this->_remark = $remark;
		return $this;
	}

	/**
	 * 设置国家编号采用标准的国际编号
	 *
	 */
	public function setCountry($country) {
		$this->_country = $country;
		return $this;
	}

	/**
	 * 设置品牌所属公司
	 *
	 */
	public function setCompany($company) {
		$this->_company = $company;
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
	 * 设置是否删除
	 *
	 */
	public function setIsdelete($isdelete) {
		$this->_isdelete = $isdelete;
		return $this;
	}

	public static function getModelObj() {
		return new ProBrandDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>