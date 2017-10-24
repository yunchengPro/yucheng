<?php
// +----------------------------------------------------------------------
// |  [ 订单处理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-04
// +----------------------------------------------------------------------
// 
namespace app\auto\controller\Bonus;
use app\auto\ActionController;
use app\lib\Model;
use think\Config;
use app\lib\Log;

class BonusController extends ActionController{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    } 

    /**
     * 消费者分红流程
     * @Author   zhuangqm
     * @DateTime 2017-10-09T19:53:01+0800
     */
    public function CusBonusAction(){
        $bonus_config = Config("bonus");

        $profit_config = Config("profit");

        $bonus_proportion = $bonus_config['cus_bonus'];
        
        $pagesize = 50;
        $page     = 1;

        $role =1;

        $User            =  Model::new("User.User");
        
        $AmoAmount       = Model::ins("AmoAmount");

        $CusRelation     = Model::ins("CusRelation");
        
        $CusBonusLog     = Model::ins("CusBonusLog");
        $SysBonusLog     = Model::ins("SysBonusLog");

        $amountOBJ       = Model::new("Amount.Amount");
        
        $date = date("Y-m-d",strtotime("-1 day"));

        // 开始执行
        $SysBonusLog_row = $SysBonusLog->getRow(["adddate"=>$date,"role"=>$role],"*");
        if(!empty($SysBonusLog_row))
            exit("操作中...");

        // 查询当天的分红比例
        $bonus_setting = Model::ins("SysBonusSetting")->getRow(["role"=>1,"adddate"=>$date],"proportion");

        // 分红比例
        $bonus_proportion = !empty($bonus_setting['proportion'])?$bonus_setting['proportion']:$bonus_proportion;

        $orderno = "BONC".date("YmdHis").rand(100000,999999);
        $flowid = Model::new("Amount.Flow")->getFlowId($orderno);

        // 生成操作日志
        $SysBonusLog->insert([
            "role"=>$role,
            "adddate"=>$date,
            "orderno"=>$orderno,
            "status"=>1,
            "proportion"=>$bonus_proportion,
        ]);

        $totalamount = 0;
        $count = 0;

        

        while(true){

            $list = $AmoAmount->pageList("conamount>0","id,conamount,saleamount","id asc",0,$page,$pagesize);
            $page+=1;
            //print_r($list);
            if(!empty($list)){
                
                foreach($list as $k=>$v){

                    if($v['conamount']<=0)
                        continue;

                    // $userrole = $User->getUserRoleID(["customerid"=>$v['id']]);

                    // if($userrole==2)
                    //     continue;

                    $AmoAmount->startTrans();

                    try{
                        //计算分红
                        $bonus_amount = intval($v['saleamount']*$bonus_proportion);

                        // if($v['conamount']>0 && $bonus_amount==0)
                        //     $bonus_amount = $v['conamount'];
                        // 回购，基数是基于购买金额，然后从购物券钱包进行扣减，如果购物券余额小于回购金额，则回购购物券余额
                        if($v['conamount']<$bonus_amount || $bonus_amount==0)
                            $bonus_amount = $v['conamount'];

                        if($bonus_amount>0){

                            // 分红支出
                            Model::new("Amount.Amount")->pay_conamount([
                                "userid"=>$v['id'],
                                "amount"=>$bonus_amount,
                                "usertype"=>$role,
                                "tablename"=>"AmoFlowCon",
                                "flowtype"=>18,
                                "flowid"=>$flowid,
                                "orderno"=>$orderno,
                                "role"=>$role,
                            ]);

                            // 分红收入
                            Model::new("Amount.Amount")->add_cashamount([
                                "userid"=>$v['id'],
                                "amount"=>$bonus_amount,
                                "usertype"=>$role,
                                "tablename"=>"AmoFlowCash",
                                "flowtype"=>19,
                                "flowid"=>$flowid,
                                "orderno"=>$orderno,
                                "role"=>$role,
                            ]);

                            // 回购金额直推 分润释放
                            // 查询上级关系
                            $relation = $CusRelation->getRow(["customerid"=>$v['id']],"*");

                            // 直接上级
                            $parent_id = $relation['parentid'];
                            $parent_role = $relation['parentrole'];
                            $parent_profit = $profit_config['add_bonus_parent_profit'];
                            if($parent_id>0){
                                // 分红余额
                                $parent_bonus_amount = intval($bonus_amount*$parent_profit);
                                if($parent_bonus_amount>0){
                                    // 查询余额
                                    $con_amount = $amountOBJ->getUserAmount($parent_id,"conamount");
                                    if($con_amount<$parent_bonus_amount)
                                        $parent_bonus_amount = $con_amount;
                                    if($parent_bonus_amount>0){
                                        // 分红支出
                                        Model::new("Amount.Amount")->pay_conamount([
                                            "userid"=>$parent_id,
                                            "amount"=>$parent_bonus_amount,
                                            "usertype"=>$parent_role,
                                            "tablename"=>"AmoFlowCon",
                                            "flowtype"=>57,
                                            "flowid"=>$flowid,
                                            "orderno"=>$orderno,
                                            "role"=>$parent_role,
                                        ]);

                                        // 分红收入
                                        Model::new("Amount.Amount")->add_cashamount([
                                            "userid"=>$parent_id,
                                            "amount"=>$parent_bonus_amount,
                                            "usertype"=>$parent_role,
                                            "tablename"=>"AmoFlowCash",
                                            "flowtype"=>58,
                                            "flowid"=>$flowid,
                                            "orderno"=>$orderno,
                                            "role"=>$parent_role,
                                        ]);
                                    }
                                }
                            }


                            // 查询间接上级
                            $parent_id = $relation['grandpaid'];
                            $parent_role = $relation['grandparole'];
                            $parent_profit = $profit_config['add_bonus_grandpa_profit'];
                            if($parent_id>0){
                                // 分红余额
                                $parent_bonus_amount = intval($bonus_amount*$parent_profit);
                                if($parent_bonus_amount>0){
                                    // 查询余额
                                    $con_amount = $amountOBJ->getUserAmount($parent_id,"conamount");
                                    if($con_amount<$parent_bonus_amount)
                                        $parent_bonus_amount = $con_amount;
                                    if($parent_bonus_amount>0){
                                        // 分红支出
                                        Model::new("Amount.Amount")->pay_conamount([
                                            "userid"=>$parent_id,
                                            "amount"=>$parent_bonus_amount,
                                            "usertype"=>$parent_role,
                                            "tablename"=>"AmoFlowCon",
                                            "flowtype"=>59,
                                            "flowid"=>$flowid,
                                            "orderno"=>$orderno,
                                            "role"=>$parent_role,
                                        ]);

                                        // 分红收入
                                        Model::new("Amount.Amount")->add_cashamount([
                                            "userid"=>$parent_id,
                                            "amount"=>$parent_bonus_amount,
                                            "usertype"=>$parent_role,
                                            "tablename"=>"AmoFlowCash",
                                            "flowtype"=>60,
                                            "flowid"=>$flowid,
                                            "orderno"=>$orderno,
                                            "role"=>$parent_role,
                                        ]);
                                    }
                                }
                            }


                            // 查询间接上级
                            $parent_id = $relation['ggrandpaid'];
                            $parent_role = $relation['ggrandparole'];
                            $parent_profit = $profit_config['add_bonus_ggrandpa_profit'];
                            if($parent_id>0){
                                // 分红余额
                                $parent_bonus_amount = intval($bonus_amount*$parent_profit);
                                if($parent_bonus_amount>0){
                                    // 查询余额
                                    $con_amount = $amountOBJ->getUserAmount($parent_id,"conamount");
                                    if($con_amount<$parent_bonus_amount)
                                        $parent_bonus_amount = $con_amount;
                                    if($parent_bonus_amount>0){
                                        // 分红支出
                                        Model::new("Amount.Amount")->pay_conamount([
                                            "userid"=>$parent_id,
                                            "amount"=>$parent_bonus_amount,
                                            "usertype"=>$parent_role,
                                            "tablename"=>"AmoFlowCon",
                                            "flowtype"=>61,
                                            "flowid"=>$flowid,
                                            "orderno"=>$orderno,
                                            "role"=>$parent_role,
                                        ]);

                                        // 分红收入
                                        Model::new("Amount.Amount")->add_cashamount([
                                            "userid"=>$parent_id,
                                            "amount"=>$parent_bonus_amount,
                                            "usertype"=>$parent_role,
                                            "tablename"=>"AmoFlowCash",
                                            "flowtype"=>62,
                                            "flowid"=>$flowid,
                                            "orderno"=>$orderno,
                                            "role"=>$parent_role,
                                        ]);
                                    }
                                }
                            }
                        }

                        $CusBonusLog->insert([
                            "role"=>$role,
                            "customerid"=>$v['id'],
                            "ratio"=>$bonus_config['cus_bonus'],
                            "amount"=>$bonus_amount,
                            "addtime"=>date("Y-m-d H:i:s"),
                            "balance"=>$v['conamount']-$bonus_amount,
                            "orderno"=>$orderno,
                        ]);

                        $totalamount+=$bonus_amount;
                        $count++;

                        // 提交事务
                        $AmoAmount->commit(); 
                        
                    } catch (\Exception $e) {
                        $AmoAmount->rollback();
                        Log::add($e,__METHOD__);
                    }
                    
                    
                }
            }
            
            if(count($list)==0 || count($list)<$pagesize)
                break;

        }

        //更新最后操作状态
        $SysBonusLog->update([
            "amount"=>$totalamount,
            "count"=>$count,
            "status"=>2,
        ],["role"=>$role,"adddate"=>$date]);

        echo "OK";
        exit;
    }

    /**
     * 商家分红流程
     * @Author   zhuangqm
     * @DateTime 2017-10-09T19:53:34+0800
     */
    public function BusBonusAction(){
        $bonus_config = Config("bonus");

        $bonus_proportion = $bonus_config['bus_bonus'];
        
        $pagesize = 50;
        $page     = 1;

        $role =2;

        
        $AmoAmount       = Model::ins("AmoAmount");
        
        $CusBonusLog     = Model::ins("CusBonusLog");
        $SysBonusLog     = Model::ins("SysBonusLog");
        
        $date = date("Y-m-d",strtotime("-1 day"));

        // 开始执行
        $SysBonusLog_row = $SysBonusLog->getRow(["adddate"=>$date,"role"=>$role],"*");
        if(!empty($SysBonusLog_row))
            exit("操作中...");

        // 查询当天的分红比例
        $bonus_setting = Model::ins("SysBonusSetting")->getRow(["role"=>2,"adddate"=>$date],"proportion");

        // 分红比例
        $bonus_proportion = !empty($bonus_setting['proportion'])?$bonus_setting['proportion']:$bonus_proportion;

        $orderno = "BONB".date("YmdHis").rand(100000,999999);
        $flowid = Model::new("Amount.Flow")->getFlowId($orderno);

        // 生成操作日志
        $SysBonusLog->insert([
            "role"=>$role,
            "adddate"=>$date,
            "orderno"=>$orderno,
            "status"=>1,
            "proportion"=>$bonus_proportion,
        ]);

        $totalamount = 0;
        $count = 0;

        while(true){

            $list = $AmoAmount->pageList("busamount>0","id,busamount","id asc",0,$page,$pagesize);
            $page+=1;
            //print_r($list);
            if(!empty($list)){
                
                // 取消订单
                foreach($list as $k=>$v){

                    $AmoAmount->startTrans();

                    try{
                        //计算分红
                        $bonus_amount = intval($v['busamount']*$bonus_proportion);

                        if($v['busamount']<$bonus_amount || $bonus_amount==0)
                            $bonus_amount = $v['busamount'];

                        if($bonus_amount>0){

                            // 分红支出
                            Model::new("Amount.Amount")->pay_conamount([
                                "userid"=>$v['id'],
                                "amount"=>$bonus_amount,
                                "usertype"=>$role,
                                "tablename"=>"AmoFlowBus",
                                "flowtype"=>20,
                                "flowid"=>$flowid,
                                "orderno"=>$orderno,
                                "role"=>$role,
                            ]);

                            // 分红收入
                            Model::new("Amount.Amount")->add_cashamount([
                                "userid"=>$v['id'],
                                "amount"=>$bonus_amount,
                                "usertype"=>$role,
                                "tablename"=>"AmoFlowCash",
                                "flowtype"=>21,
                                "flowid"=>$flowid,
                                "orderno"=>$orderno,
                                "role"=>$role,
                            ]);


                        }

                        $CusBonusLog->insert([
                            "role"=>$role,
                            "customerid"=>$v['id'],
                            "ratio"=>$bonus_config['bus_bonus'],
                            "amount"=>$bonus_amount,
                            "addtime"=>date("Y-m-d H:i:s"),
                            "balance"=>$v['busamount']-$bonus_amount,
                            "orderno"=>$orderno,
                        ]);

                        $totalamount+=$bonus_amount;
                        $count++;

                        // 提交事务
                        $AmoAmount->commit(); 
                        
                    } catch (\Exception $e) {
                        $AmoAmount->rollback();
                        Log::add($e,__METHOD__);
                    }
                    
                    
                }
            }
            
            if(count($list)==0 || count($list)<$pagesize)
                break;

        }

        //更新最后操作状态
        $SysBonusLog->update([
            "amount"=>$totalamount,
            "count"=>$count,
            "status"=>2,
        ],["role"=>$role,"adddate"=>$date]);

        echo "OK";
        exit;

    }

    

    

}