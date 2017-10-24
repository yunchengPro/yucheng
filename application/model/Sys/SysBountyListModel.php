<?php
namespace app\model\Sys;
use app\lib\Model;

class SysBountyListModel {

	// 牛品鼓励金
	public function addOrderBounty($param){
		$orderno = $param['orderno'];
		$customerid = $param['customerid'];

        // 用户获得牛粮
        $user_profit = Model::ins("AmoProfit")->getRow(["orderno"=>$orderno,"userid"=>$customerid,"flowtype"=>"22"],"profit_amount");
        $profitamount = intval($user_profit['profit_amount']);


        $flowtype = 28;
        // 用户获得牛豆
        $user_profit = Model::ins("AmoProfit")->getRow(["orderno"=>$orderno,"userid"=>$customerid,"flowtype"=>$flowtype],"profit_amount");
        $bullamount = intval($user_profit['profit_amount']);
        if($profitamount>0 || $bullamount>0){
            //用户获得奖励金
            Model::ins("SysBountyList")->insert([
                    "customerid"=>$customerid,
                    "cashamount"=>0,
                    "profitamount"=>$profitamount,
                    "bullamount"=>$bullamount,
                    "getbountydate"=>date("Y-m-d"),
                    "addtime"=>date("Y-m-d H:i:s"),
                    "orderno"=>$orderno,
                ]);
        }
	}

}