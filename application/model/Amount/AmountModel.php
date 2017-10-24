<?php
// +----------------------------------------------------------------------
// |  [ 用户余额 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Amount;

use app\lib\Model;

class AmountModel{


    /**
     * 入口
     * @Author   zhuangqm
     * @DateTime 2017-03-16T10:06:08+0800
     * @return   [type]                   [description]
     */
    public function entrance($param){

    }

    public function getAmount($userid,$field){
        return Model::ins("AmoAmount")->getAmount($userid,$field);
    }

    /*
    获取用户余额
    cashamount
    profitamount
    bullamount
     */
    public function getUserAmount($userid,$field){
    	$AmoAmountObj = Model::ins("AmoAmount");
    	if($field=='conamount')
    		return $AmoAmountObj->getConAmount($userid);
    	if($field=='cashamount')
    		return $AmoAmountObj->getCashAmount($userid);
    	if($field=='busamount')
    		return $AmoAmountObj->getBusAmount($userid);
        if($field=='intamount')
            return $AmoAmountObj->getIntAmount($userid);
        if($field=='saleamount')
            return $AmoAmountObj->getSaleAmount($userid);
    }

    /**
     * 获取用户提现金额 -- 返回单位为分的金额
     * @Author   zhuangqm
     * @DateTime 2017-04-10T14:22:43+0800
     * @param    [type]                   $userid [description]
     * @return   [type]                           [description]
     */
    public function getUserWithdrawalsAmount($userid){
        $amount_item = $this->getAmount($userid,'cashamount');
        $cashamount = $amount_item['cashamount'];
        return [
            "cashamount"=>$cashamount,
        ];
    }

    /**
     * 判断用户余额
     * @Author   zhuangqm
     * @DateTime 2017-03-22T15:48:54+0800
     * @return   [type]                   $param [
     *                                           userid
     *                                           cashamount
     *                                           profitamount
     *                                           bullamount
     * ]
     */
    public function checkamountbalance($param){
        $AmoAmountObj = Model::ins("AmoAmount");
        $amount = $AmoAmountObj->getAmount($param['userid'],"conamount,cashamount,busamount,intamount");

        $result = [];

        // $result['cashamount']   = $amount['cashamount']>=$param['cashamount']?1:0;

        
        // $result['profitamount'] = $amount['profitamount']>=$param['profitamount']?1:0;

        
        // $result['bullamount']   = $amount['bullamount']>=$param['bullamount']?1:0;
        // 
        $cashamount     = intval($param['cashamount']);
        $profitamount   = intval($param['profitamount']);
        $bullamount     = intval($param['bullamount']);


        $balance = 0;
        $balanceinfo = "";

        // 企业余额 也可以支付
        $amount['cashamount']+=$amount['comamount'];

        //三种状态
        if($cashamount>0 && $bullamount>0){
            if($amount['cashamount']>=$cashamount && $amount['bullamount']>=$bullamount){
                $balance = 1;
            }else{
                if($amount['cashamount']<$cashamount)
                    $balanceinfo = "牛票不足";
                if($amount['bullamount']<$bullamount)
                    $balanceinfo = "牛豆不足";
                if($amount['cashamount']<$cashamount && $amount['bullamount']<$bullamount)
                    $balanceinfo = "余额不足";
            }
            
        }

        if($cashamount>0 && $bullamount==0){
            if($amount['cashamount']>=$cashamount)
                $balance = 1;
            else
                $balanceinfo = "牛票不足";
        }

        if($cashamount==0 && $bullamount>0){
            if($amount['bullamount']>=$bullamount)
                $balance = 1;
            else
                $balanceinfo = "牛豆不足";
        }

        if($profitamount>0){
            if(($amount['cashamount']+$amount['profitamount'])>=$profitamount)
                $balance = 1;
            else
                $balanceinfo = "牛票不足";
        }

        return [
            "balance"=>$balance,
            "balanceinfo"=>$balanceinfo,
        ];
        
    }

    /*
        消费余额支付 --支出
        $param
     */
    public function pay_conamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        //$cashamount = $this->getUserAmount($userid,"cashamount");

        //if($cashamount>=$amount){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //扣减
            if(Model::ins("AmoAmount")->DedConAmount($userid,$amount)){
               
                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>"",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>2,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  //1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "fromuserid"=>$param['fromuserid'],
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30002"];
            }
        //}else{
            //return ["code"=>"30001"];
        //}
    }

    // 判断是否支付
    public function check_pay_conamount($param){
        if($param['orderno']!=''){
            $row = Model::ins("AmoFlowCon")->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>2,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];
        }else{

            return ['code'=>"200"];
        }
    }

    /*
    	现金余额支付 --支出
    	$param
     */
    public function pay_cashamount($param){

    	$userid 		= $param['userid'];
    	$amount 		= $param['amount'];
    	$usertype 		= $param['usertype'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

    	//$cashamount = $this->getUserAmount($userid,"cashamount");

    	//if($cashamount>=$amount){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//扣减
    		if(Model::ins("AmoAmount")->DedCashAmount($userid,$amount)){
               
    			//生成扣减流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>"",
    						"data"=>[
    							//"usertype"=>$usertype,
    							"userid"=>$userid,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>2,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>2, //1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "fromuserid"=>$param['fromuserid'],
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30002"];
    		}
    	//}else{
    		//return ["code"=>"30001"];
    	//}
    }

    // 判断是否支付
    public function check_pay_cashamount($param){
        if($param['orderno']!=''){
            $row = Model::ins("AmoFlowCash")->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>2,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];
        }else{

            return ['code'=>"200"];
        }
    }

    /*
    	收益现金余额支付--支出
     */
    public function pay_busamount($param){

    	$userid 		= $param['userid'];
    	$amount 		= $param['amount'];
    	$usertype 		= $param['usertype'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

    	//$profitamount = $this->getUserAmount($userid,"profitamount");

    	//if($profitamount>=$amount){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//扣减
    		if(Model::ins("AmoAmount")->DedBusAmount($userid,$amount)){
                
    			//生成扣减流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>"",
    						"data"=>[
    							//"usertype"=>$usertype,
    							"userid"=>$userid,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>2,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>3, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "fromuserid"=>$param['fromuserid'],
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30002"];
    		}
    	//}else{
    		return ["code"=>"30001"];
    	//}
    }

    // 判断是否支付
    public function check_pay_busamount($param){
        if($param['orderno']!=''){
            $row = Model::ins("AmoFlowBus")->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>2,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];
        }else{
            return ['code'=>"200"];
        }
    }

    /*
    	收益现金余额支付--支出
     */
    public function pay_intamount($param){

    	$userid 		= $param['userid'];
    	$amount 		= $param['amount'];
    	$usertype 		= $param['usertype'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

    	//$bullamount = $this->getUserAmount($userid,"bullamount");

    	//if($bullamount>=$amount){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//扣减
    		if(Model::ins("AmoAmount")->DedIntAmount($userid,$amount)){
    			//生成扣减流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>"",
    						"data"=>[
    							//"usertype"=>$usertype,
    							"userid"=>$userid,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>2,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>4, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "fromuserid"=>$param['fromuserid'],
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30002"];
    		}
    	//}else{
    		//return ["code"=>"30001"];
    	//}
    }

    /*
        商城消费余额支付--支出
     */
    public function pay_mallamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        //$bullamount = $this->getUserAmount($userid,"bullamount");

        //if($bullamount>=$amount){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //扣减
            if(Model::ins("AmoAmount")->DedMallAmount($userid,$amount)){
                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>"",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>2,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>5, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "fromuserid"=>$param['fromuserid'],
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30002"];
            }
        //}else{
            //return ["code"=>"30001"];
        //}
    }

    /*
        线下消费余额支付--支出
     */
    public function pay_stoamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        //$bullamount = $this->getUserAmount($userid,"bullamount");

        //if($bullamount>=$amount){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //扣减
            if(Model::ins("AmoAmount")->DedStoAmount($userid,$amount)){
                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>"",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>2,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>6, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "fromuserid"=>$param['fromuserid'],
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30002"];
            }
        //}else{
            //return ["code"=>"30001"];
        //}
    }

    /**
    * @user 扣减牛粮金额，不足写入记录表
    * @param 
    * @author jeeluo
    * @date 2017年8月21日下午2:31:54
    */
    public function no_pay_conamount($param) {
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $cashamount     = $param['cashamount'];
        $profitamount   = $param['profitamount'];
        $bullamount     = $param['bullamount'];
        $bonusamount    = $param['bonusamount'];
        $orderno        = $param['orderno'];
        $flowid         = $param['flowid'];
        $flowtime       = $param['flowtime'];
        $type           = $param['type'];
        
        // 生成流水
        Model::ins("LogSharestode")->insert([
                "userid"=>$userid,
                "cashamount"=>0,
                "profitamount"=>$amount,
                "bullamount"=>0,
                "bonusamount"=>0,
                "user_cashamount"=>$cashamount,
                "user_profitamount"=>$profitamount,
                "user_bullamount"=>$bullamount,
                "user_bonusamount"=>$bonusamount,
                "status"=>0,
                "flowtime"=>$flowtime,
                "orderno"=>$orderno,
                "type"=>$type,
            ]);
        return ["code" => "200"];
    }

    /**
     * @user 扣减牛票金额，不足写入记录表
     * @param
     * @author jeeluo
     * @date 2017年8月21日下午2:31:54
     */
    public function no_pay_cashamount($param) {
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $cashamount     = $param['cashamount'];
        $profitamount   = $param['profitamount'];
        $bullamount     = $param['bullamount'];
        $bonusamount    = $param['bonusamount'];
        $orderno        = $param['orderno'];
        $flowid         = $param['flowid'];
        $flowtime       = $param['flowtime'];
        $type           = $param['type'];
        
        // 生成流水
        Model::ins("LogSharestode")->insert([
                "userid"=>$userid,
                "cashamount"=>$amount,
                "profitamount"=>0,
                "bullamount"=>0,
                "bonusamount"=>0,
                "user_cashamount"=>$cashamount,
                "user_profitamount"=>$profitamount,
                "user_bullamount"=>$bullamount,
                "user_bonusamount"=>$bonusamount,
                "status"=>0,
                "flowtime"=>$flowtime,
                "orderno"=>$orderno,
                "type"=>$type,
            ]);
        return ["code" => "200"];
    }
    
    /**
     * @user 扣减牛豆金额，不足写入记录表
     * @param
     * @author jeeluo
     * @date 2017年8月21日下午2:31:54
     */
    public function no_pay_busamount($param) {
        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $cashamount     = $param['cashamount'];
        $profitamount   = $param['profitamount'];
        $bullamount     = $param['bullamount'];
        $bonusamount    = $param['bonusamount'];
        $orderno        = $param['orderno'];
        $flowid         = $param['flowid'];
        $flowtime       = $param['flowtime'];
        $type           = $param['type'];
    
        // 生成流水
        Model::ins("LogSharestode")->insert([
            "userid"=>$userid,
            "cashamount"=>0,
            "profitamount"=>0,
            "bullamount"=>$amount,
            "bonusamount"=>0,
            "user_cashamount"=>$cashamount,
            "user_profitamount"=>$profitamount,
            "user_bullamount"=>$bullamount,
            "user_bonusamount"=>$bonusamount,
            "status"=>0,
            "flowtime"=>$flowtime,
            "orderno"=>$orderno,
            "type"=>$type,
        ]);
        return ["code" => "200"];
    }

    // 判断是否支付
    public function check_pay_intamount($param){
        if($param['orderno']!=''){
            $row = Model::ins("AmoFlowInt")->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>2,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];
        }else{
            return ['code'=>"200"];
        }
    }

    /*
        消费余额 --收入
     */
    public function add_conamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid  = $param['parent_userid'];
        $flowtime       = $param['flowtime'];

        if($amount>0){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //收入
            if(Model::ins("AmoAmount")->AddConAmount($userid,$amount)){
                //生成收入流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>$tablename,
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>1,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "flowtime"=>$flowtime,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30003"];
            }
        }

        return ['code'=>"200"];
    }

    // 判断是否添加
    public function check_add_conamount($param){

        if($param['orderno']!=''){
            $tablename      = $param['tablename']!=''?$param['tablename']:"AmoFlowCon";

            $row = Model::ins($tablename)->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>1,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];

        }else{
            return ['code'=>"200"];
        }
    }

    /*
    	现金余额支付 --收入
     */
    public function add_cashamount($param){

    	$userid 		= $param['userid'];
    	$amount 		= $param['amount'];
    	$usertype 		= $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid  = $param['parent_userid'];
        $flowtime       = $param['flowtime'];

    	if($amount>0){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//收入
    		if(Model::ins("AmoAmount")->AddCashAmount($userid,$amount)){
    			//生成收入流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>$tablename,
    						"data"=>[
    							//"usertype"=>$usertype,
    							"userid"=>$userid,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>1,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>2, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "flowtime"=>$flowtime,
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30003"];
    		}
    	}

    	return ['code'=>"200"];
    }

    // 判断是否添加
    public function check_add_cashamount($param){

        if($param['orderno']!=''){
            $tablename      = $param['tablename']!=''?$param['tablename']:"AmoFlowCash";

            $row = Model::ins($tablename)->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>1,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];

        }else{
            return ['code'=>"200"];
        }
    }

    /*
    	收益现金余额支付 --收入
     */
    public function add_busamount($param){

    	$userid 		= $param['userid'];
    	$amount 		= $param['amount'];
    	$usertype 		= $param['usertype'];
    	$tablename		= $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid  = $param['parent_userid'];
        $flowtime       = $param['flowtime'];

    	if($amount>0){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//收入
    		if(Model::ins("AmoAmount")->AddBusAmount($userid,$amount)){

    			//生成收入流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>$tablename,
    						"data"=>[
    							//"usertype"=>$usertype,
    							"userid"=>$userid,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>1,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>3, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "flowtime"=>$flowtime,
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30003"];
    		}
    	}

    	return ['code'=>"200"];
    }

    // 判断是否添加
    public function check_add_busamount($param){

        if($param['orderno']!=''){
            $tablename      = $param['tablename']!=''?$param['tablename']:"AmoFlowBus";

            $row = Model::ins($tablename)->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>1,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];

        }else{
            return ['code'=>"200"];
        }
    }

    /*
    	现金余额支付 --收入
     */
    public function add_intamount($param){

    	$userid 		= $param['userid'];
    	$amount 		= $param['amount'];
    	$usertype 		= $param['usertype'];
    	$tablename		= $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid  = $param['parent_userid'];
        $flowtime       = $param['flowtime'];

    	if($amount>0){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//收入
    		if(Model::ins("AmoAmount")->AddIntAmount($userid,$amount)){

    			//生成收入流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>$tablename,
    						"data"=>[
    							//"usertype"=>$usertype,
    							"userid"=>$userid,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>1,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>4, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "flowtime"=>$flowtime,
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30003"];
    		}
    	}

    	return ['code'=>"200"];
    }


    /*
        商城消费余额支付 --收入
     */
    public function add_mallamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid  = $param['parent_userid'];
        $flowtime       = $param['flowtime'];

        if($amount>0){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //收入
            if(Model::ins("AmoAmount")->AddMallAmount($userid,$amount)){

                //生成收入流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>$tablename,
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>1,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>5, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "flowtime"=>$flowtime,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30003"];
            }
        }

        return ['code'=>"200"];
    }


    /*
        线下消费余额支付 --收入
     */
    public function add_stoamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid  = $param['parent_userid'];
        $flowtime       = $param['flowtime'];

        if($amount>0){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //收入
            if(Model::ins("AmoAmount")->AddStoAmount($userid,$amount)){

                //生成收入流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>$tablename,
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>1,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>6, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                                "flowtime"=>$flowtime,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30003"];
            }
        }

        return ['code'=>"200"];
    }

    // 判断是否添加
    public function check_add_intamount($param){

        if($param['orderno']!=''){
            $tablename      = $param['tablename']!=''?$param['tablename']:"AmoFlowInt";

            $row = Model::ins($tablename)->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>1,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];

        }else{
            return ['code'=>"200"];
        }
    }

    /*
        现金余额支付 --收入
     */
    public function add_comcashamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //收入
            if(Model::ins("AmoAmount")->AddComCashAmount($userid,$amount)){
                //生成收入流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>$tablename,
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>1,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30003"];
            }
        }

        return ['code'=>"200"];
    }


    // 判断是否添加
    public function check_add_comcashamount($param){

        if($param['orderno']!=''){
            $tablename      = $param['tablename']!=''?$param['tablename']:"AmoFlowCusComCash";

            $row = Model::ins($tablename)->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>1,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];

        }else{
            return ['code'=>"200"];
        }
    }

    /*
        现金余额支付 --支出
     */
    public function pay_comcashamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //收入
            if(Model::ins("AmoAmount")->DedComAmount($userid,$amount)){
                //生成收入流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>"AmoFlowCusComCash",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>2,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30003"];
            }
        }

        return ['code'=>"200"];
    }

    // 判断是否支付
    public function check_pay_comcashamount($param){
        if($param['orderno']!=''){
            $row = Model::ins("AmoFlowCusComCash")->getRow([
                    "userid"=>$param['userid'],
                    "orderno"=>$param['orderno'],
                    "flowtype"=>$param['flowtype'],
                    "direction"=>2,
                    "amount"=>abs($param['amount']),
                ],"count(*) as count");

            if($row['count']>0)
                return ["code"=>"30005"];
            else
                return ['code'=>"200"];
        }else{

            return ['code'=>"200"];
        }
    }

    /*
    	公司 现金余额支付 --收入
     */
    public function add_com_cashamount($param){

    	
    	$amount 		= $param['amount'];
    	$tablename		= $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

    	if($amount>0){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//收入
    		if(Model::ins("AmoComAmount")->AddCashAmount($amount)){

    			//生成收入流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>$tablename,
    						"data"=>[
    							//"usertype"=>$usertype,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>1,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>2, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30003"];
    		}
    	}

    	return ['code'=>"200"];
    }


    /*
        公司 现金余额支付 --收入
     */
    public function add_com_cashamount_user($param){

        $userid         = $param['userid']!=''?$param['userid']:0;
        $amount         = $param['amount'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //收入
            if(Model::ins("AmoComAmount")->AddCashAmountUser($amount,$userid)){

                //生成收入流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>$tablename,
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>1,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30003"];
            }
        }

        return ['code'=>"200"];
    }

    /*
    	公司 收益现金余额支付 --收入
     */
    public function add_com_busamount($param){

    	$amount 		= $param['amount'];
    	$tablename		= $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

    	if($amount>0){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//收入
    		if(Model::ins("AmoComAmount")->AddBusAmount($amount)){
    			//生成收入流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>$tablename,
    						"data"=>[
    							//"usertype"=>$usertype,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>1,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>3, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
    						],
    				]);
    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30003"];
    		}
    	}

    	return ['code'=>"200"];
    }

    /*
    	公司 现金余额支付 --收入
     */
    public function add_com_conamount($param){

    	$amount 		= $param['amount'];
    	$tablename		= $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

    	if($amount>0){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//收入
    		if(Model::ins("AmoComAmount")->AddConAmount($amount)){

    			//生成收入流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>$tablename,
    						"data"=>[
    							//"usertype"=>$usertype,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>1,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>3, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30003"];
    		}
    	}

    	return ['code'=>"200"];
    }

    /*
        公司 现金余额支付 --收入
     */
    public function add_com_intamount($param){

        $amount         = $param['amount'];
        $tablename      = $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        if($amount>0){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //收入
            if(Model::ins("AmoComAmount")->AddIntAmount($amount)){

                //生成收入流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>$tablename,
                            "data"=>[
                                //"usertype"=>$usertype,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>1,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>4, // amounttype  1消费 2现金 3商家 4积分 5商城消费 6线下消费
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30003"];
            }
        }

        return ['code'=>"200"];
    }

    /*
    	公司 增加手续费余额 --收入
     */
    public function add_com_counteramount($param){

    	$amount 		= $param['amount'];
    	$tablename		= $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

    	if($amount>0){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//收入
    		if(Model::ins("AmoComAmount")->AddCounterAmount($amount)){

    			//生成收入流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>$tablename,
    						"data"=>[
    							//"usertype"=>$usertype,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>1,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30003"];
    		}
    	}

    	return ['code'=>"200"];
    }

    /*
    	公司 增加慈善余额 --收入
     */
    public function add_com_charitableamount($param){

    	$amount 		= $param['amount'];
    	$tablename		= $param['tablename'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

    	if($amount>0){
    		//$AmoAmountObj = Model::ins("AmoAmount");
    		//收入
    		if(Model::ins("AmoComAmount")->AddCharitableAmount($amount)){

    			//生成收入流水
    			Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
    						"tablename"=>$tablename,
    						"data"=>[
    							//"usertype"=>$usertype,
    							"orderno"=>$param['orderno'],
    							"flowtype"=>$param['flowtype'],
    							"direction"=>1,
    							"amount"=>abs($amount),
    							"remark"=>$param['remark'],
    							"amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                                "fromuserid"=>$param['fromuserid'],
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
    						],
    				]);

    			return ['code'=>"200"];
    		}else{
    			return ["code"=>"30003"];
    		}
    	}

    	return ['code'=>"200"];
    }





    /*
        公司 现金余额支付 --支出
        $param
     */
    public function pay_com_cashamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        //$cashamount = $this->getUserAmount($userid,"cashamount");

        //if($cashamount>=$amount){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //扣减
            if(Model::ins("AmoComAmount")->DedCashAmount($amount)){
               
                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>"AmoFlowComCash",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>2,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30002"];
            }
        //}else{
            //return ["code"=>"30001"];
        //}
    }

    /*
        收益现金余额支付--支出
     */
    public function pay_com_profitamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        //$profitamount = $this->getUserAmount($userid,"profitamount");

        //if($profitamount>=$amount){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //扣减
            if(Model::ins("AmoComAmount")->DedProfitAmount($amount)){
                
                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>"AmoFlowComProfit",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>2,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>2, // amounttype  1现金 2收益现金 3牛币
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30002"];
            }
        //}else{
            return ["code"=>"30001"];
        //}
    }

    /*
        收益现金余额支付--支出
     */
    public function pay_com_bullamount($param){

        $userid         = $param['userid'];
        $amount         = $param['amount'];
        $usertype       = $param['usertype'];
        $flowid         = $param['flowid'];
        $role           = $param['role'];
        $profit_role    = $param['profit_role'];
        $parent_userid    = $param['parent_userid'];

        //$bullamount = $this->getUserAmount($userid,"bullamount");

        //if($bullamount>=$amount){
            //$AmoAmountObj = Model::ins("AmoAmount");
            //扣减
            if(Model::ins("AmoComAmount")->DedBullAmount($amount)){
                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>"AmoFlowComBull",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$param['orderno'],
                                "flowtype"=>$param['flowtype'],
                                "direction"=>2,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>3, // amounttype  1现金 2收益现金 3牛币
                                "role"=>$role,
                                "profit_role"=>$profit_role,
                                "parent_userid"=>$parent_userid,
                            ],
                    ]);

                return ['code'=>"200"];
            }else{
                return ["code"=>"30002"];
            }
        //}else{
            //return ["code"=>"30001"];
        //}
    }
}