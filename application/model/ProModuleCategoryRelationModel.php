<?php
/**
* 产品类型分类关系表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 21:34:19Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class ProModuleCategoryRelationModel {

	protected $_id 	= null;

	protected $_moduleId 	= null;

	protected $_categoryId 	= null;

	protected $_sort 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('ProModuleCategoryRelation');
		$this->_modelObj->_fields = ['module_id','category_id','sort'];
	}

	/**
	 *
	 * 添加产品类型分类关系表
	 */
	public function add() {
		$this->_modelObj->_moduleId  		= $this->_moduleId;
		$this->_modelObj->_categoryId  		= $this->_categoryId;
		$this->_modelObj->_sort  		= $this->_sort;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新产品类型分类关系表
	 */
	public function modify($id) {
		$this->_modelObj->_moduleId  = $this->_moduleId;
		$this->_modelObj->_categoryId  = $this->_categoryId;
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
	 * 获取所有产品类型分类关系表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除产品类型分类关系表
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
	 * 设置类型id pro_module主键
	 *
	 */
	public function setModuleId($moduleId) {
		$this->_moduleId = $moduleId;
		return $this;
	}

	/**
	 * 设置分类id pro_category主键
	 *
	 */
	public function setCategoryId($categoryId) {
		$this->_categoryId = $categoryId;
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
		return new ProModuleCategoryRelationDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>