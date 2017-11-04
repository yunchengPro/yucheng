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

class ProfitModel{

    /**
     * 购买钻石分润
     * @Author   zhuangqm
     * @Datetime 2017-10-08T22:28:37+0800
     * @param    [type]                   $param [
     *                                           
     *                                           userid     来自用户ID
     *                                           amount     现金金额 分
     *                                           orderno
     *                                           flowid
     *                                           
     *                                     ]
     */
    public function add_con_profit($param){

        /*
        按支付金额
        1 商家享受50%分润
        2 消费者享受直推金额的5%
        3 消费者享受引荐人金额的3%
        4 经理 3%
        5 总监 2%
         */
        
        /*$con_config = Config::get("conn");
        // 计算回现金金额
        $cash = $param['amount']/$con_config['con_price'];*/
        $cash = DePrice($param['amount']);

        $profit_config = Config::get("profit");


        // 获取用户所属商家 50%分润
        $bus_relation = Model::ins("CusRelationList")->getRow("customerid='".$param['userid']."' and (parentrole=2 or parentotherrole=2)","id,parentid","id desc");
        
        if($bus_relation['parentid']>0){

            // 商家获得分润
            $bus_profit = EnPrice($cash*$profit_config['add_con_bus_profit']);

            // 商家 -- 商家钱包
            $result = Model::new("Amount.Amount")->add_busamount([
                    "userid"=>$bus_relation['parentid'],
                    "amount"=>$bus_profit,
                    "usertype"=>2,
                    "tablename"=>"AmoFlowBus",
                    "flowtype"=>13,
                    "flowid"=>$param['flowid'],
                    "orderno"=>$param['orderno'],
                    "role"=>2,
                    "fromuserid"=>$param['userid'],
                ]);

            // 商家奖金扣除
            /*Model::new("Amount.Arrears")->deductible([
                "customerid"=>$param['fromuserid'],
                "role"=>2,
                "amount"=>$bus_profit,
                "flowid"=>$param['flowid'],
                "orderno"=>$param['orderno'],
            ]);*/

        }
        // 消费者分润-- 秒结
        // 获取用户关系
        $cus_relation = Model::ins("CusRelation")->getRow(["customerid"=>$param['userid']],"*");

        if(!empty($cus_relation)){

            //从新计算cash值
            
            $parent_cash = $cash;
            
            if($cus_relation['parentid']>0){

                // 获取直接上级最高购买记录
                $conorder = Model::ins("ConOrder")->getRow(["customerid"=>$cus_relation['parentid'],"orderstatus"=>1],"id,totalamount","totalamount desc");

                $conorder_amount = DePrice($conorder['totalamount']);

                // 取最小值
                $parent_cash = $parent_cash>=$conorder_amount?$conorder_amount:$parent_cash;

            }else{
                $parent_cash = 0;
            }


            // 消费者上级
            $parent_profit = EnPrice($parent_cash*$profit_config['add_con_parent_profit']);

            if($cus_relation['parentid']>0 && $parent_profit>0){

                $conamount = Model::new("Amount.Amount")->getUserAmount($cus_relation['parentid'],"conamount");

                if($conamount<$parent_profit)
                    $parent_profit = $conamount;

                if($parent_profit>0){
                    // 上级
                    $result = Model::new("Amount.Amount")->add_cashamount([
                        "userid"=>$cus_relation['parentid'],
                        "amount"=>$parent_profit,
                        "usertype"=>1,
                        "tablename"=>"AmoFlowCash",
                        "flowtype"=>14,
                        "flowid"=>$param['flowid'],
                        "orderno"=>$param['orderno'],
                        "role"=>1,
                        "fromuserid"=>$param['userid'],
                    ]);

                    // 上级
                    $result = Model::new("Amount.Amount")->pay_conamount([
                        "userid"=>$cus_relation['parentid'],
                        "amount"=>$parent_profit,
                        "usertype"=>1,
                        "tablename"=>"AmoFlowCon",
                        "flowtype"=>54,
                        "flowid"=>$param['flowid'],
                        "orderno"=>$param['orderno'],
                        "role"=>1,
                        "fromuserid"=>$param['userid'],
                    ]);

                }
            }

            // 消费者上上级
            $grandpa_profit = EnPrice($parent_cash*$profit_config['add_con_grandpa_profit']);

            if($cus_relation['grandpaid']>0 && $grandpa_profit>0){

                $conamount = Model::new("Amount.Amount")->getUserAmount($cus_relation['grandpaid'],"conamount");

                if($conamount<$grandpa_profit)
                    $grandpa_profit = $conamount;

                if($grandpa_profit>0){
                    // 上上级
                    $result = Model::new("Amount.Amount")->add_cashamount([
                        "userid"=>$cus_relation['grandpaid'],
                        "amount"=>$grandpa_profit,
                        "usertype"=>1,
                        "tablename"=>"AmoFlowCash",
                        "flowtype"=>15,
                        "flowid"=>$param['flowid'],
                        "orderno"=>$param['orderno'],
                        "role"=>1,
                        "fromuserid"=>$param['userid'],
                    ]);

                    // 上级
                    $result = Model::new("Amount.Amount")->pay_conamount([
                        "userid"=>$cus_relation['grandpaid'],
                        "amount"=>$grandpa_profit,
                        "usertype"=>1,
                        "tablename"=>"AmoFlowCon",
                        "flowtype"=>55,
                        "flowid"=>$param['flowid'],
                        "orderno"=>$param['orderno'],
                        "role"=>1,
                        "fromuserid"=>$param['userid'],
                    ]);
                }
            }

            // 消费者上上上级
            $ggrandpa_profit = EnPrice($parent_cash*$profit_config['add_con_ggrandpa_profit']);

            if($cus_relation['ggrandpaid']>0 && $ggrandpa_profit>0){

                $conamount = Model::new("Amount.Amount")->getUserAmount($cus_relation['ggrandpaid'],"conamount");

                if($conamount<$ggrandpa_profit)
                    $ggrandpa_profit = $conamount;

                if($ggrandpa_profit>0){
                    // 上上级
                    $result = Model::new("Amount.Amount")->add_cashamount([
                        "userid"=>$cus_relation['ggrandpaid'],
                        "amount"=>$ggrandpa_profit,
                        "usertype"=>1,
                        "tablename"=>"AmoFlowCash",
                        "flowtype"=>45,
                        "flowid"=>$param['flowid'],
                        "orderno"=>$param['orderno'],
                        "role"=>1,
                        "fromuserid"=>$param['userid'],
                    ]);

                    // 上上级
                    $result = Model::new("Amount.Amount")->pay_conamount([
                        "userid"=>$cus_relation['ggrandpaid'],
                        "amount"=>$ggrandpa_profit,
                        "usertype"=>1,
                        "tablename"=>"AmoFlowCon",
                        "flowtype"=>56,
                        "flowid"=>$param['flowid'],
                        "orderno"=>$param['orderno'],
                        "role"=>1,
                        "fromuserid"=>$param['userid'],
                    ]);
                }
            }
        }

        // 获取区县经理 --秒接
        $manager_relation = Model::ins("CusRelationList")->getRow(["customerid"=>$param['userid'],"parentrole"=>3],"id,parentid","id desc");

        // 区县经理
        $manager_profit = EnPrice($cash*$profit_config['add_con_manager_profit']);

        if(!empty($manager_relation) && $manager_relation["parentid"]>0 && $manager_profit>0){
            
            $result = Model::new("Amount.Amount")->add_cashamount([
                    "userid"=>$manager_relation['parentid'],
                    "amount"=>$manager_profit,
                    "usertype"=>3,
                    "tablename"=>"AmoFlowCash",
                    "flowtype"=>16,
                    "flowid"=>$param['flowid'],
                    "orderno"=>$param['orderno'],
                    "role"=>3,
                    "fromuserid"=>$param['userid'],
                ]);

            // 经理的直接上级经理得3%
            $manager_parent = Model::ins("CusRelation")->getRow(["customerid"=>$manager_relation["parentid"],"parentrole"=>3],"id,parentid");
            if(!empty($manager_parent) && $manager_parent["parentid"]>0){
                $manager_parent_profit = intval($manager_profit*$profit_config['add_con_manager_parent_profit']);
                if($manager_parent_profit>0){
                    $result = Model::new("Amount.Amount")->add_cashamount([
                            "userid"=>$manager_parent['parentid'],
                            "amount"=>$manager_parent_profit,
                            "usertype"=>3,
                            "tablename"=>"AmoFlowCash",
                            "flowtype"=>63,
                            "flowid"=>$param['flowid'],
                            "orderno"=>$param['orderno'],
                            "role"=>3,
                            "fromuserid"=>$manager_relation['parentid'],
                        ]);
                }
            }
        }




        // 市级总监 --秒接
        $chief_relation = Model::ins("CusRelationList")->getRow(["customerid"=>$param['userid'],"parentrole"=>4],"id,parentid","id desc");

        // 市级总监
        $chief_profit = EnPrice($cash*$profit_config['add_con_chief_profit']);

        if(!empty($chief_relation) && $chief_relation["parentid"]>0 && $chief_profit>0){
            
            $result = Model::new("Amount.Amount")->add_cashamount([
                    "userid"=>$chief_relation['parentid'],
                    "amount"=>$chief_profit,
                    "usertype"=>4,
                    "tablename"=>"AmoFlowCash",
                    "flowtype"=>17,
                    "flowid"=>$param['flowid'],
                    "orderno"=>$param['orderno'],
                    "role"=>4,
                    "fromuserid"=>$param['userid'],
                ]);

        }
        

        return ["code"=>"200"];
    }
}