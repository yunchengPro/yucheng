<?php
/**
* 订单表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:19:56Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\lib\Img;


class OrdOrderPayModel {


	public function __construct() {
		$this->_modelObj = Db::Table('OrdOrderPay');
	}

	
	/**
	 *
	 * 添加订单表
	 */
	public function add($data) {
// 		$this->_modelObj->_orderno  		= $data['orderno'];
// 		$this->_modelObj->_customerid  		= $data['customerid'];
// 		$this->_modelObj->_custName  		= $data['custName'];
// 		$this->_modelObj->_actualfreight  	= $data['actualfreight'];
// 		$this->_modelObj->_productcount  	= $data['productcount'];
// 		$this->_modelObj->_productamount  	= $data['productamount'];
// 		$this->_modelObj->_bullamount  		= $data['bullamount'];
// 		$this->_modelObj->_totalamount  	= $data['totalamount'];
// 		$this->_modelObj->_addtime  		= $data['addtime'];
// 		$this->_modelObj->_orderstatus  	= $data['orderstatus'];
// 		$this->_modelObj->_businessid  		= $data['businessid'];
// 		$this->_modelObj->_businessname  	= $data['businessname'];
		return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新订单表
	 */
	public function modify($updateData,$where) {
		/*
		$this->_modelObj->_orderno  = $this->_orderno;
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_custName  = $this->_custName;
		$this->_modelObj->_actualfreight  = $this->_actualfreight;
		$this->_modelObj->_productcount  = $this->_productcount;
		$this->_modelObj->_productamount  = $this->_productamount;
		$this->_modelObj->_bullamount  = $this->_bullamount;
		$this->_modelObj->_totalamount  = $this->_totalamount;
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_businessname  = $this->_businessname;
		return $this->_modelObj->modify($id);
		*/
		
		return $this->_modelObj->update($updateData,$where);
	}


	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }


	/**
	 *
	 * 详细
	 */
	public function getById($id = null,$field="*") {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getRow(["id"=>$id],$field);
		return $this->_dataInfo;
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
	 * 订单详细信息表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}
	

}
?>