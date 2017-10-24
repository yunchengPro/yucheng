<?php
// +----------------------------------------------------------------------
// |  [ 分润 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Amount;

use think\Config;
use app\lib\Model;

class ArrearsModel{

    /**
     * 生成欠费记录
     * @Author   zhuangqm
     * @DateTime 2017-10-11T10:31:25+0800
     * @param    [type]                   $param [
     *                                           customerid
     *                                           role 开通的角色
     *                                     ]
     * @return   [type]                          [description]
     */
    public function arrears($param){

        $rolecost = Config::get("rolecost");
        $amount = 0;
        $amount_type = 1;
        switch ($param['role']) {
            case 1:
                $amount = 0;
                break;
            case 2:
                $amount = $rolecost['bus_cost'];
                break;
            case 3:
                $amount = $rolecost['manager_cost'];
                break;
            case 4:
                $amount = $rolecost['chief_cost'];
                break;
        }

        $row = Model::ins("AmoArrears")->getRow(["customerid"=>$param['customerid'],"arrears_type"=>$param['role']],"*");

        if(empty($row)){

            Model::ins("AmoArrears")->insert([
                "customerid"=>$param['customerid'],
                "arrears_type"=>$param['role'],
                "amount_type"=>$amount_type,
                "amount"=>$amount,
                "amount_rest"=>$amount,
            ]);

        }else{

            Model::ins("AmoArrears")->update("amount=amount+".$amount.",amount_rest=amount_rest+".$amount,["customerid"=>$param['customerid'],"arrears_type"=>$param['role']]);

        }

        return ["code"=>"200"];
    }

    /**
     * 扣减费用
     * @Author   zhuangqm
     * @DateTime 2017-10-11T11:11:50+0800
     * @param    [type]                   $param [
     *                                           customerid
     *                                           role
     *                                           amount
     *                                           flowid
     *                                           orderno
     * ]
     * @return   [type]                          [description]
     */
    public function deductible($param){

        // 抵扣费用 ，知道抵扣完
        
        $bus_arrears = Model::ins("AmoArrears")->getRow(["customerid"=>$param['customerid'],"arrears_type"=>$param['role']],"id,amount_rest");

        if(empty($bus_arrears) || $bus_arrears['amount_rest']<=0)
            return ["code"=>"200"];

        $dedcost = 0; //抵扣费用

        if($bus_arrears['amount_rest']>=$param['amount']){
            $dedcost = $param['amount'];
        }else{
            $dedcost = $bus_arrears['amount_rest'];
        }

        //开始抵扣
        Model::ins("AmoArrears")->update("amount_rest=amount_rest-".$dedcost,["customerid"=>$param['customerid'],"arrears_type"=>$param['role']]);

        // 商家奖金扣除
        if($param['role']==2){

            // 商家抵扣
            Model::new("Amount.Amount")->pay_busamount([
                "userid"=>$param['customerid'],
                "amount"=>$dedcost,
                "usertype"=>$param['role'],
                "tablename"=>"AmoFlowBus",
                "flowtype"=>22,
                "flowid"=>$param['flowid'],
                "orderno"=>$param['orderno'],
                "role"=>$param['role'],
            ]);

            // 抵扣的费用分给公司
            Model::new("Amount.Amount")->add_com_busamount([
                "amount"=>$dedcost,
                "flowid"=>$param['flowid'],
                "orderno"=>$param['orderno'],
                "flowtype"=>23,
                "tablename"=>"AmoFlowComBus",
            ]);

        }

        // 经理奖金扣除
        if($param['role']==3){

            // 经理抵扣
            Model::new("Amount.Amount")->pay_cashamount([
                "userid"=>$param['customerid'],
                "amount"=>$dedcost,
                "usertype"=>$param['role'],
                "tablename"=>"AmoFlowCash",
                "flowtype"=>24,
                "flowid"=>$param['flowid'],
                "orderno"=>$param['orderno'],
                "role"=>$param['role'],
            ]);

            // 抵扣的费用分给公司
            Model::new("Amount.Amount")->add_com_cashamount([
                "amount"=>$dedcost,
                "flowid"=>$param['flowid'],
                "orderno"=>$param['orderno'],
                "flowtype"=>25,
                "tablename"=>"AmoFlowComCash",
            ]);
        }

        // 总监奖金扣除
        if($param['role']==4){

            // 总监抵扣
            Model::new("Amount.Amount")->pay_cashamount([
                "userid"=>$param['customerid'],
                "amount"=>$dedcost,
                "usertype"=>$param['role'],
                "tablename"=>"AmoFlowCash",
                "flowtype"=>26,
                "flowid"=>$param['flowid'],
                "orderno"=>$param['orderno'],
                "role"=>$param['role'],
            ]);

            // 抵扣的费用分给公司
            Model::new("Amount.Amount")->add_com_cashamount([
                "amount"=>$dedcost,
                "flowid"=>$param['flowid'],
                "orderno"=>$param['orderno'],
                "flowtype"=>27,
                "tablename"=>"AmoFlowComCash",
            ]);
        }

        return ["code"=>"200"];

    }
}