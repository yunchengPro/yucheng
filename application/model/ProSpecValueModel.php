<?php
/**
* 商品规格值表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:09:33Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class ProSpecValueModel {

	protected $_id 	= null;

	protected $_specValueName 	= null;

	protected $_specId 	= null;

	protected $_categoryId 	= null;

	protected $_storeId 	= null;

	protected $_specValueColor 	= null;

	protected $_specValueSort 	= null;

	protected $_isdelete 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('ProSpecValue');
		 $this->_modelObj->_fields = ['spec_value_name','spec_id','category_id','store_id','spec_value_color','spec_value_sort','isdelete'];
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
	 * 添加商品规格值表
	 */
	public function add() {
		$this->_modelObj->_specValueName  		= $this->_specValueName;
		$this->_modelObj->_specId  		= $this->_specId;
		$this->_modelObj->_categoryId  		= $this->_categoryId;
		$this->_modelObj->_storeId  		= $this->_storeId;
		$this->_modelObj->_specValueColor  		= $this->_specValueColor;
		$this->_modelObj->_specValueSort  		= $this->_specValueSort;
		$this->_modelObj->_isdelete  		= $this->_isdelete;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商品规格值表
	 */
	public function modify($id) {
		$this->_modelObj->_specValueName  = $this->_specValueName;
		$this->_modelObj->_specId  = $this->_specId;
		$this->_modelObj->_categoryId  = $this->_categoryId;
		$this->_modelObj->_storeId  = $this->_storeId;
		$this->_modelObj->_specValueColor  = $this->_specValueColor;
		$this->_modelObj->_specValueSort  = $this->_specValueSort;
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
	 * 获取所有商品规格值表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除商品规格值表
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
	 * 设置规格值名称
	 *
	 */
	public function setSpecValueName($specValueName) {
		$this->_specValueName = $specValueName;
		return $this;
	}

	/**
	 * 设置所属规格id
	 *
	 */
	public function setSpecId($specId) {
		$this->_specId = $specId;
		return $this;
	}

	/**
	 * 设置分类id
	 *
	 */
	public function setCategoryId($categoryId) {
		$this->_categoryId = $categoryId;
		return $this;
	}

	/**
	 * 设置店铺id
	 *
	 */
	public function setStoreId($storeId) {
		$this->_storeId = $storeId;
		return $this;
	}

	/**
	 * 设置规格颜色
	 *
	 */
	public function setSpecValueColor($specValueColor) {
		$this->_specValueColor = $specValueColor;
		return $this;
	}

	/**
	 * 设置排序
	 *
	 */
	public function setSpecValueSort($specValueSort) {
		$this->_specValueSort = $specValueSort;
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
		return new ProSpecValueDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>