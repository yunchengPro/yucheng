<?php
/**
* 分润记录表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-20 12:06:49Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class SysSqlLogModel {

	protected $_modelObj;


	public function __construct() {
		$this->_modelObj = Db::Table('SysSqlLog');
	}

	public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

	
	/**
	 *
	 * 分润记录表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/*
	 * 获取单条数据
	 * $where 可以是字符串 也可以是数组
	 */
	public function getRow($where,$field='*',$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}

}
?>