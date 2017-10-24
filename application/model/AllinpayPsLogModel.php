<?php
/**
* 
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-20 11:55:00Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class AllinpayPsLogModel {

	protected $_modelObj;


	public function __construct() {
		$this->_modelObj = Db::Table('AllinpayPsLog');
	}

	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

    public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	public function delete($where,$limit=''){
		return $this->_modelObj->delete($where,$limit);
    }

	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	public function getRow($where,$field='*',$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}

}
?>