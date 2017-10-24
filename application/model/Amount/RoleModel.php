<?php
// +----------------------------------------------------------------------
// |  [ 角色相关 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Amount;

use think\Config;
use app\lib\Model;
use app\model\Sys\CommonModel;
use app\model\Sys\CommonRoleModel;

class RoleModel{

    /**
     * 成为商家的 佣金处理
     * @Author   zhuangqm
     * @DateTime 2017-10-19T10:15:51+0800
     * @param    [type]                   $param [
     *                                           customerid 用户编号
     *                                           orderno
     * ]
     * @return   [type]                          [description]
     */
    public function pay_bus($param){

        $customerid = $param['customerid'];

        $orderno = $param['orderno'];

        $role_config = Config::get("role");

        $bus_cost = DePrice($role_config['bus_cost']); // 商家升级费用

        //生成流水号
        $flowid = Model::new("Amount.Flow")->getFlowId($orderno);

        /*
        佣金
        消费者A直推商家，消费者A得10%，消费者A的上级（经理或者总监）得30%
        商家A直推商家B，商家A和商家A的上级（经理或者总监）各得20%
        经理直推商家，经理得40%
        总监直推商家，总监得40%；
         */

        $CusRelationList = Model::ins("CusRelationList");
        $CusRelation = Model::ins("CusRelation");

        $total_to_bus = $role_config['total_to_bus'];
        $other_to_bus = 0;

        // 获取直接上级
        $parent = $CusRelation->getRow(["customerid"=>$customerid],"id,parentrole,parentid");

        if($parent['parentid']>0){

            $amount = 0;

            // 直接上级是 消费者
            if($parent['parentrole']==1){
                $other_to_bus = $role_config['cus_to_bus'];
                $amount = EnPrice($bus_cost*$other_to_bus);
                $flowtype = 49;
            }

            // 直接上级是 商家
            if($parent['parentrole']==2){
                $other_to_bus = $role_config['bus_to_bus'];
                $amount = EnPrice($bus_cost*$other_to_bus);
                $flowtype = 48;
            }

            // 直接上级是 经理
            if($parent['parentrole']==3){
                $other_to_bus = $role_config['manager_to_bus'];
                $amount = EnPrice($bus_cost*$other_to_bus);
                $flowtype = 47;
            }

            // 直接上级是 经理
            if($parent['parentrole']==4){
                $other_to_bus = $role_config['chief_to_bus'];
                $amount = EnPrice($bus_cost*$other_to_bus);
                $flowtype = 46;
            }

            if($amount>0){
                Model::new("Amount.Amount")->add_cashamount([
                    "userid"=>$parent['parentid'],
                    "amount"=>$amount,
                    "usertype"=>$parent['parentrole'],
                    "tablename"=>"AmoFlowCash",
                    "flowtype"=>$flowtype,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "role"=>$parent['parentrole'],
                    "fromuserid"=>$customerid,
                ]);
            }
        }

        //剩余比例佣金
        $surplus_to_bus = $total_to_bus-$other_to_bus;
        if($surplus_to_bus>0){
            
            // 找上级经理或者总监
            // 先找上级经理
            $parent_relation = $CusRelationList->getRow(["customerid"=>$parent['parentid'],"parentrole"=>3],"id,parentrole,parentid","id desc");
            if(empty($parent_relation) || $parent_relation['parentid']<=0){
                //再找总监
                $parent_relation = $CusRelationList->getRow(["customerid"=>$parent['parentid'],"parentrole"=>4],"id,parentrole,parentid","id desc");
            }

            if($parent_relation['parentid']>0){

                $amount = EnPrice($bus_cost*$surplus_to_bus);
                if($amount>0){
                    Model::new("Amount.Amount")->add_cashamount([
                        "userid"=>$parent_relation['parentid'],
                        "amount"=>$amount,
                        "usertype"=>$parent_relation['parentrole'],
                        "tablename"=>"AmoFlowCash",
                        "flowtype"=>64,
                        "flowid"=>$flowid,
                        "orderno"=>$orderno,
                        "role"=>$parent_relation['parentrole'],
                        "fromuserid"=>$parent['parentid'],
                    ]);
                }
            }
        }

        return ["code"=>"200"];
    }


    /**
     * 成为经理的 佣金处理
     * @Author   zhuangqm
     * @DateTime 2017-10-19T10:15:51+0800
     * @param    [type]                   $param [
     *                                           customerid 用户编号
     *                                           orderno
     * ]
     * @return   [type]                          [description]
     */
    public function pay_manager($param){

        $customerid = $param['customerid'];

        $orderno = $param['orderno'];

        $role_config = Config::get("role");

        $manager_cost = DePrice($role_config['manager_cost']); // 经理升级费用

        //生成流水号
        $flowid = Model::new("Amount.Flow")->getFlowId($orderno);

        /*
        佣金 总比例 40%
        
        消费者A直推经理，消费者A得10%，消费者A的上级（总监）得30%
        商家A直推经理，商家A和商家A的上级（总监）各得20%
        经理A直推经理B，经理A和经理A的上级（总监）各得20%
        总监直推经理，总监得40%；
         */

        $CusRelationList = Model::ins("CusRelationList");
        $CusRelation = Model::ins("CusRelation");

        $total_to_manager = $role_config['total_to_manager'];
        $other_to_manager = 0;

        // 获取直接上级
        $parent = $CusRelation->getRow(["customerid"=>$customerid],"id,parentrole,parentid");

        if($parent['parentid']>0){

            $amount = 0;

            // 直接上级是 消费者
            if($parent['parentrole']==1){
                $other_to_manager = $role_config['cus_to_manager'];
                $amount = EnPrice($manager_cost*$other_to_manager);
                $flowtype = 53;
            }

            // 直接上级是商家
            if($parent['parentrole']==2){
                $other_to_manager = $role_config['bus_to_manager'];
                $amount = EnPrice($manager_cost*$other_to_manager);
                $flowtype = 52;
            }

            // 直接上级是 经理
            if($parent['parentrole']==3){
                $other_to_manager = $role_config['manager_to_manager'];
                $amount = EnPrice($manager_cost*$other_to_manager);
                $flowtype = 51;
            }

            // 直接上级是 总监
            if($parent['parentrole']==4){
                $other_to_manager = $role_config['chief_to_manager'];
                $amount = EnPrice($manager_cost*$other_to_manager);
                $flowtype = 50;
            }

            if($amount>0){
                Model::new("Amount.Amount")->add_cashamount([
                    "userid"=>$parent['parentid'],
                    "amount"=>$amount,
                    "usertype"=>$parent['parentrole'],
                    "tablename"=>"AmoFlowCash",
                    "flowtype"=>$flowtype,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "role"=>$parent['parentrole'],
                    "fromuserid"=>$customerid,
                ]);
            }
        }

        //剩余比例佣金
        $surplus_to_manager = $total_to_manager-$other_to_manager;
        if($surplus_to_manager>0){

            // 找上级总监
            $parent_relation = $CusRelationList->getRow(["customerid"=>$parent['parentid'],"parentrole"=>4],"id,parentrole,parentid","id desc");

            if($parent_relation['parentid']>0){

                $amount = EnPrice($manager_cost*$surplus_to_manager);
                if($amount>0){
                    Model::new("Amount.Amount")->add_cashamount([
                        "userid"=>$parent_relation['parentid'],
                        "amount"=>$amount,
                        "usertype"=>$parent_relation['parentrole'],
                        "tablename"=>"AmoFlowCash",
                        "flowtype"=>65,
                        "flowid"=>$flowid,
                        "orderno"=>$orderno,
                        "role"=>$parent_relation['parentrole'],
                        "fromuserid"=>$parent['parentid'],
                    ]);
                }
            }

        }

        return ["code"=>"200"];
    }
}