<?php
/**
* 汇总牛豆流水表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-20 11:10:32Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class AmoFlowBusModel {

	protected $_id 	= null;

	protected $_flowid 	= null;

	protected $_usertype 	= null;

	protected $_userid 	= null;

	protected $_orderno 	= null;

	protected $_flowtype 	= null;

	protected $_direction 	= null;

	protected $_amount 	= null;

	protected $_finalAmount 	= null;

	protected $_beforeamount 	= null;

	protected $_afteramount 	= null;

	protected $_remark 	= null;

	protected $_flowtime 	= null;

	protected $_isshow 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('AmoFlowBus');
	}

	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

    public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/*
    * 删除数据
    */
    public function delete($where,$limit=''){
		return $this->_modelObj->delete($where,$limit);
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
	 *
	 * 汇总牛豆流水表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}
	
	public function pageList($where,$field='*',$order='',$flag=1,$page=''){
	    return $this->_modelObj->pageList($where,$field,$order,$flag,$page);
	}

	/**
	 * 获取所有汇总牛豆流水表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
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
	 * 删除汇总牛豆流水表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


}
?>