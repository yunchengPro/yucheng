<?php
/**
* app首页显示模块明细表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-07 14:15:46Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class SysPayLogModel {

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('SysPayLog');
	}


	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }
    
    
}
?>