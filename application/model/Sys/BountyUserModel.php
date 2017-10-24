<?php
namespace app\model\Sys;
use app\lib\Model;

class BountyUserModel {

	/**
	 * [userGetBounty 用户领取奖励金]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-07-24T11:58:18+0800
	 * @param    [type]                   $param [customerid ] array("customerid"=>$customerid) ; 用户id
	 * @return   [type]                          [description]
	 */
	public static function userGetBounty($param){

		
		if(substr($param['orderno'], 0,6) !='NNHSTB')
			return ['code'=>404];
		//var_dump($param);
		$order_row = Model::ins('StoPayFlow')->getRow(['pay_code'=>$param['orderno']],'customerid,status');
		//var_dump($order_row);
		if(empty($order_row))
			return ['code'=>404];
		
		// if($order_row['status']!=1)
		// 	return false;
		
		$customerid = $order_row['customerid'];

		$data = Model::ins('SysBountyUser')->getRow(['customerid'=>$customerid],'id,isget,amount,minamount,payamount');

		
		if(empty($data))
			return ['code'=>404];

		if($data['isget'] == 1)
			return ['code'=>404];

		if(DePrice($data['payamount']) <= 2){
			$costamount = $data['minamount'];
		}else{
			$costamount = $data['amount'];
		}
		
		//更新用户已领取
		$ret = Model::ins('SysBountyUser')->update(['isget'=>1,'gettime'=>date('Y-m-d H:i:s')],['id'=>$data['id']]);

		if($ret > 0){
			//更新领取记录
			$bountMallData = Model::ins('SysBountyMall')->getRow(['id'=>1],'id,amount,disamount,hasamount,receivetime');
			
			$disamount = $bountMallData['disamount'] + $costamount;
			$hasamount = $bountMallData['hasamount'] - $costamount;
			$receivetime = $bountMallData['receivetime'] + 1;
			Model::ins('SysBountyMall')->update(['disamount'=>$disamount,'hasamount'=>$hasamount,'receivetime'=>$receivetime],['id'=>$bountMallData['id']]);
			return ['code'=>200,'amount'=>$costamount];
		}else{
			return ['code'=>400];
		}
	}

}