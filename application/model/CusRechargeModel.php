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

class CusRechargeModel {

	protected $_modelObj;

	public function __construct() {
		$this->_modelObj = Db::Table('CusRecharge');
	}

	/**
	 * 生成订单号
	 * @Author   zhuangqm
	 * @DateTime 2017-04-01T16:02:24+0800
	 * @return   [type]                   [description]
	 */
	public function getOrderNo(){
		return "NNHRE".date("YmdHis").rand(100000,999999);
	}


	public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}


	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

    public function getRow($where,$field='*',$order='',$otherstr=''){
        return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }

	/**
	 *
	 * app首页显示模块表列表
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
}
?>