<?php
// +----------------------------------------------------------------------
// |  [ 资金冻结 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Amount;

use think\Config;
use app\lib\Model;

class FrozenModel{

    // 现金余额冻结
    public function FrozenCashAmount($param){

        $userid = $param['userid'];
        $amount = $param['amount'];
        $orderno = $param['orderno'];
        $flowid     = $param['flowid'];

        //用户金额扣减
        if(Model::ins("AmoAmount")->DedCashAmount($userid,$amount)){
                
                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            // "tablename"=>"AmoFlowCusCash",
                            "tablename"=>"AmoFlowCash",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$orderno,
                                // "flowtype"=>19,
                                "flowtype"=>40,
                                "direction"=>2,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                            ],
                    ]);

                return ['code'=>"200"];
        }else{
            return ["code"=>"30002"];
        }
    }

    // 公司余额余额冻结
    public function FrozenComAmount($param){

        $userid = $param['userid'];
        $amount = $param['amount'];
        $orderno = $param['orderno'];
        $flowid     = $param['flowid'];

        //用户金额扣减
        if(Model::ins("AmoAmount")->DedComAmount($userid,$amount)){
               
                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>"AmoFlowCusComCash",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$orderno,
                                "flowtype"=>19,
                                "direction"=>2,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                            ],
                    ]);

                return ['code'=>"200"];
        }else{
            return ["code"=>"30002"];
        }
    }



    // 现金余额返回
    public function returnCashAmount($param){

        $userid = $param['userid'];
        $amount = $param['amount'];
        $orderno = $param['orderno'];
        $flowid     = $param['flowid'];

        //用户金额扣减
        if(Model::ins("AmoAmount")->AddCashAmount($userid,$amount)){

                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            // "tablename"=>"AmoFlowCusCash",
                            "tablename" => "AmoFlowCash",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$orderno,
                                // "flowtype"=>45,
                                "flowtype"=>41,
                                "direction"=>1,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                            ],
                    ]);

                return ['code'=>"200"];
        }else{
            return ["code"=>"30002"];
        }
    }

    // 公司余额余额返回
    public function returnComAmount($param){

        $userid = $param['userid'];
        $amount = $param['amount'];
        $orderno = $param['orderno'];
        $flowid     = $param['flowid'];

        //用户金额扣减
        if(Model::ins("AmoAmount")->AddComCashAmount($userid,$amount)){
               
                //生成扣减流水
                Model::new("Amount.Flow")->flowpush([
                            "flowid"=>$flowid,
                            "tablename"=>"AmoFlowCusComCash",
                            "data"=>[
                                //"usertype"=>$usertype,
                                "userid"=>$userid,
                                "orderno"=>$orderno,
                                "flowtype"=>45,
                                "direction"=>1,
                                "amount"=>abs($amount),
                                "remark"=>$param['remark'],
                                "amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                            ],
                    ]);

                return ['code'=>"200"];
        }else{
            return ["code"=>"30002"];
        }
    }
}