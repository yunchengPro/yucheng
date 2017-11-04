<?php
/**
* 商家分类表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:13:49Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class BusBusinessCategoryModel {

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_categoryName 	= null;

	protected $_parentId 	= null;

	protected $_sort 	= null;

	protected $_isDelete 	= null;

	protected $_categoryIcon 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('BusBusinessCategory');
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
	 * [getCateData 获取分类数据]
	 * @Author   ISir<673638498@qq.com>
     * @Date 2017-03-01 
	 * @return [type] [description]
	 */
	public  function getCateData($businessid){
		
		$topCate =   $this->_modelObj->getList(['parent_id'=>0,'businessid'=>$businessid,'is_delete'=>0],'*','sort desc');

		foreach ($topCate as $key => $value) {
			$sonCate = $this->_modelObj->getList(['parent_id'=>$value['id'],'is_delete'=>0]);
			$topCate[$key]['sonCate'] = $sonCate;
		}
		
		return $topCate;
	}

	/**
	 *
	 * 添加商家分类表
	 */
	public function add() {
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_categoryName  		= $this->_categoryName;
		$this->_modelObj->_parentId  		= $this->_parentId;
		$this->_modelObj->_sort  		= $this->_sort;
		$this->_modelObj->_isDelete  		= $this->_isDelete;
		$this->_modelObj->_categoryIcon  		= $this->_categoryIcon;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商家分类表
	 */
	public function modify($id) {
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_categoryName  = $this->_categoryName;
		$this->_modelObj->_parentId  = $this->_parentId;
		$this->_modelObj->_sort  = $this->_sort;
		$this->_modelObj->_isDelete  = $this->_isDelete;
		$this->_modelObj->_categoryIcon  = $this->_categoryIcon;
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
	 * 获取所有商家分类表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除商家分类表
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
	 * 设置商家id
	 *
	 */
	public function setBusinessid($businessid) {
		$this->_businessid = $businessid;
		return $this;
	}

	/**
	 * 设置分类名称
	 *
	 */
	public function setCategoryName($categoryName) {
		$this->_categoryName = $categoryName;
		return $this;
	}

	/**
	 * 设置上级分类id
	 *
	 */
	public function setParentId($parentId) {
		$this->_parentId = $parentId;
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
	 * 设置是删除
	 *
	 */
	public function setIsDelete($isDelete) {
		$this->_isDelete = $isDelete;
		return $this;
	}

	/**
	 * 设置分类图标
	 *
	 */
	public function setCategoryIcon($categoryIcon) {
		$this->_categoryIcon = $categoryIcon;
		return $this;
	}

	public static function getModelObj() {
		return new BusBusinessCategoryDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>