<?php
/**
* 手续费流水表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-20 11:30:17Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class PayOrderModel {

	protected $_modelObj;

	public function __construct() {
		$this->_modelObj = Db::Table('PayOrder');
	}

	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

    public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	public function getRow($where,$field='*',$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}

	/**
	 *
	 * 手续费流水表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}
	
	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
	    return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
	}

	/**
	 *
	 * 删除手续费流水表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	
}
?>