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

class RoleApplyChiefModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_role 	= null;

	protected $_name 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_area_code 	= null;

	protected $_address 	= null;

	protected $_addtime 	= null;

	protected $_orderno 	= null;

	protected $_join_type 	= null;

	protected $_status 	= null;

	protected $_remark 	= null;

	protected $_examinetime = null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('RoleApplyChief');
		 $this->_modelObj->_fields = ['customerid','role','name','mobile','area','address','addtime','orderno','join_type','status','remark','examinetime'];
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
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_role  		= $this->_role;
		$this->_modelObj->_name  		= $this->_name;
		$this->_modelObj->_mobile  		= $this->_mobile;
		$this->_modelObj->_area  		= $this->_area;
		$this->_modelObj->_area_code  		= $this->_area_code;
		$this->_modelObj->_address  		= $this->_address;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_orderno  		= $this->_orderno;
		$this->_modelObj->_join_type  		= $this->_join_type;
		$this->_modelObj->_status  		= $this->_status;
		$this->_modelObj->_remark  		= $this->_remark;
		$this->_modelObj->_examinetime  		= $this->_examinetime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商品规格值表
	 */
	public function modify($id) {
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_role  		= $this->_role;
		$this->_modelObj->_name  		= $this->_name;
		$this->_modelObj->_mobile  		= $this->_mobile;
		$this->_modelObj->_area  		= $this->_area;
		$this->_modelObj->_area_code  		= $this->_area_code;
		$this->_modelObj->_address  		= $this->_address;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_orderno  		= $this->_orderno;
		$this->_modelObj->_join_type  		= $this->_join_type;
		$this->_modelObj->_status  		= $this->_status;
		$this->_modelObj->_remark  		= $this->_remark;
		$this->_modelObj->_examinetime  		= $this->_examinetime;
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