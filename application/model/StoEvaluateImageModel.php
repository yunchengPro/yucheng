<?php
/**
* 实体商家评价图片表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:10:57Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoEvaluateImageModel {

	protected $_id 	= null;

	protected $_evaluateId 	= null;

	protected $_thumb 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoEvaluateImage');
	}

	/**
	 *
	 * 添加实体商家评价图片表
	 */
	public function add() {
		$this->_modelObj->_evaluateId  		= $this->_evaluateId;
		$this->_modelObj->_thumb  		= $this->_thumb;
		$this->_modelObj->_addtime  		= $this->_addtime;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新实体商家评价图片表
	 */
	public function modify($id) {
		$this->_modelObj->_evaluateId  = $this->_evaluateId;
		$this->_modelObj->_thumb  = $this->_thumb;
		$this->_modelObj->_addtime  = $this->_addtime;
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
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
        return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);

    }

    /*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }

	/**
	 * 获取所有实体商家评价图片表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除实体商家评价图片表
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
	 * 设置对应的评价id
	 *
	 */
	public function setEvaluateId($evaluateId) {
		$this->_evaluateId = $evaluateId;
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
	 * 设置添加时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	public static function getModelObj() {
		return new StoEvaluateImageDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
	
	/*插入图片*/
	public function insert($insertData) {
        return $this->_modelObj->insert($insertData);
    }
}
?>