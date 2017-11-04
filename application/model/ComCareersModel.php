<?php
/**
* 商品分类类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 18:07:41Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\lib\Img;
class ComCareersModel {

	protected $_id 	= null;

	protected $_position 	= null;

	protected $_position_en 	= null;

	protected $_responsibility 	= null;

	protected $_requirement	= null;

	protected $_isdelete 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('ComCareers');
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
	 * 添加商品分类
	 */
	public function add() {
		$this->_modelObj->_position  		= $this->_position;
		$this->_modelObj->_position_en  		= $this->_position_en;
		$this->_modelObj->_responsibility  		= $this->_responsibility;
		$this->_modelObj->_requirement  		= $this->_requirement;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商品分类
	 */
	public function modify($id) {
		$this->_modelObj->_position  		= $this->_position;
		$this->_modelObj->_position_en  		= $this->_position_en;
		$this->_modelObj->_responsibility  		= $this->_responsibility;
		$this->_modelObj->_requirement  		= $this->_requirement;
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
	 * 获取所有商品分类
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除商品分类
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
	 * 设置上级分类id
	 *
	 */
	public function setParentId($parentId) {
		$this->_parentId = $parentId;
		return $this;
	}

	/**
	 * 设置分类名称
	 *
	 */
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * 设置状态
	 *
	 */
	public function setStatus($status) {
		$this->_status = $status;
		return $this;
	}

	public static function getModelObj() {
		return new ProCategotyDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>