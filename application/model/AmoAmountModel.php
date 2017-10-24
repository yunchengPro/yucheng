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

use app\lib\model\RedisModel;

// class AmoAmountModel extends RedisModel{
class AmoAmountModel {

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	protected $_modelname = "AmoAmount";

	public function __construct() {
		$this->_modelObj = Db::Table($this->_modelname);
	}

	//开启事务
	public function startTrans(){
		return $this->_modelObj->startTrans();
	}

	//提交事务
	public function commit(){
		return $this->_modelObj->commit();
	}

	//事务回滚
	public function rollback(){
		return $this->_modelObj->rollback();
	}

	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }
    
    public function modify($data, $where) {
        return $this->_modelObj->update($data, $where);
    }

 //    public function update($data, $where) {
	//     return $this->_modelObj->update($data, $where);
	// }
    
    /*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
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

	/*
	获取用户余额
	获取用户余额
    cashamount
    profitamount
    bullamount
	 */
	public function getAmount($userid,$field="*"){
		if(!empty($userid)){
			$row = $this->getRow(["id"=>$userid],$field);
			if(empty($row)){
				$this->insert(["id"=>$userid]);
				$row = $this->getRow(["id"=>$userid],$field);
			}

			return $row;
		}else{
			return [];
		}
	}

	/*
	获取消费余额
	 */
	public function getConAmount($userid){
		$row = $this->getAmount($userid,"conamount");
		return intval($row['conamount']);
	}

	/*
	获取现金余额
	 */
	public function getCashAmount($userid){
		$row = $this->getAmount($userid,"cashamount");
		return intval($row['cashamount']);
	}

	/*
	获取商家余额
	 */
	public function getBusAmount($userid){
		$row = $this->getAmount($userid,"busamount");
		return intval($row['busamount']);
	}

	/*
	获取积分余额
	 */
	public function getIntAmount($userid){
		$row = $this->getAmount($userid,"intamount");
		return intval($row['intamount']);
	}

	public function getSaleAmount($userid){
		$row = $this->getAmount($userid,"saleamount");
		return intval($row['saleamount']);
	}

	// 判断记录是否存在
	public function checkAmount($userid){
		$row = $this->_modelObj->getRow(["id"=>$userid],"count(*) as count");
		if($row['count']==0)
			$this->insert(["id"=>$userid]);
	}

	/*
	扣减消费余额
	 */
	public function DedConAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("conamount=conamount-".intval($amount),["id"=>$userid]);

		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["conamount"=>"-".$amount]);

		return $result;
	}

	/*
	扣减现金余额
	 */
	public function DedCashAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("cashamount=cashamount-".intval($amount),["id"=>$userid]);

		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["cashamount"=>"-".$amount]);

		return $result;
	}

	/*
	扣减商家余额
	 */
	public function DedBusAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("busamount=busamount-".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["profitamount"=>"-".$amount]);

		return $result;
	}

	/*
	扣减积分余额
	 */
	public function DedIntAmount($userid,$amount){
		$this->checkAmount($userid);
		$result =  $this->_modelObj->update("intamount=intamount-".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["bullamount"=>"-".$amount]);

		return $result;
	}

	/*
	扣减商城消费余额
	 */
	public function DedMallAmount($userid,$amount){
		$this->checkAmount($userid);
		$result =  $this->_modelObj->update("mallamount=mallamount-".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["bullamount"=>"-".$amount]);

		return $result;
	}

	/*
	扣减线下消费余额
	 */
	public function DedStoAmount($userid,$amount){
		$this->checkAmount($userid);
		$result =  $this->_modelObj->update("stoamount=stoamount-".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["bullamount"=>"-".$amount]);

		return $result;
	}

	/*
	销售额
	 */
	public function DedSaleAmount($userid,$amount){
		$this->checkAmount($userid);
		$result =  $this->_modelObj->update("saleamount=saleamount-".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["bullamount"=>"-".$amount]);

		return $result;
	}

	/*
	增加消费余额
	 */
	public function AddConAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("conamount=conamount+".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["cashamount"=>$amount]);

		return $result;
	}

	/*
	增加现金余额
	 */
	public function AddCashAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("cashamount=cashamount+".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["cashamount"=>$amount]);

		return $result;
	}

	/*
	增加商家余额
	 */
	public function AddBusAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("busamount=busamount+".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["busamount"=>$amount]);

		return $result;
	}

	/*
	增加积分余额
	 */
	public function AddIntAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("intamount=intamount+".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["intamount"=>$amount]);

		return $result;
	}

	/*
	增加商城消费余额
	 */
	public function AddMallAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("mallamount=mallamount+".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["intamount"=>$amount]);

		return $result;
	}

	/*
	增加线下消费余额
	 */
	public function AddStoAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("stoamount=stoamount+".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["intamount"=>$amount]);

		return $result;
	}

	/*
	销售额
	 */
	public function AddSaleAmount($userid,$amount){
		$this->checkAmount($userid);
		$result = $this->_modelObj->update("saleamount=saleamount+".intval($amount),["id"=>$userid]);
		//更新redis
		// if($result)
		// 	$this->hincrbyRedis($userid,["intamount"=>$amount]);

		return $result;
	}
}
?>