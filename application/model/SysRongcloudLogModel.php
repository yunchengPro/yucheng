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

class SysRongcloudLogModel {

	protected $_modelObj;


	public function __construct() {
		$this->_modelObj = Db::Table('SysRongcloudLog');
	}

	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

    public function update($updateData,$where) {
    	return $this->_modelObj->update($updateData,$where);
    }

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
}
?>