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
namespace app\auto\controller\Amount;
use app\auto\ActionController;
use app\lib\Model;
use think\Config;
use app\lib\Log;

use app\lib\Pay\Allinpay\ProcessServlet;

class WithdrawalsController extends ActionController{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    } 

    /*
    通联推荐使用下面的机制进行查询：
    超时实时交易结果的查询：
       对于某笔超时的实时交易需要查询结果，超时后3分钟内，相邻查询时间间隔不应短于20秒
       在超时后3-10分钟内，相邻查询时间间隔不应短于1分钟
       在超时后10分钟以上的，相邻查询时间间隔不应短于5分钟
       对于30分钟内通联一直返回1002的，应确认该笔交易失败，通联没有成功接收，应立刻停止继续查询。
    批量交易结果的查询
       建议至少间隔5分钟查询一次
       对于50分钟内通联一直返回1002的，应确认该笔交易失败，通联没有成功接收，应立刻停止继续查询。
    对于查询过于频繁的客户，通联会向对方提出改进的建议，坚持不改的，通联将会把该客户列入黑名单，列入黑名单的客户通过本接口进行查询交易将受到严厉的限制。
    */
    /**
     * 查询支付结果   
     * @Author   zhuangqm
     * @DateTime 2017-08-01T15:05:01+0800
     * @return   [type]                   [description]
     */
    public function withdrawalsAction(){
        
        $deftime = 300; //相差5分钟后才能进行订单结果查询
        
        $pagesize = 50;
        $page     = 1;

        
        $Withdrawals                = Model::ins("CusWithdrawals");
        $CusWithdrawalsProcessservlet                = Model::ins("CusWithdrawalsProcessservlet");
        $AllinpayPsLog              = Model::ins("AllinpayPsLog");
        $AllinpayPsresultLog         = Model::ins("AllinpayPsresultLog");
        $amountModel                = Model::ins("AmoAmount");

        $ProcessServlet             = new ProcessServlet();
        
        while(true){
            
            $list = $CusWithdrawalsProcessservlet->pageList("status=3","*","id asc",0,$page,$pagesize);
            $page+=1;
 
            if(!empty($list)){
                
                foreach($list as $k=>$info){

                    $Withdrawals_row = $Withdrawals->getRow(["id"=>$info['withdrawalsid']],"*");

                    if(!empty($Withdrawals_row) && $Withdrawals_row['status']==3 && $info['pay_time']!='' && (time()-strtotime($info['pay_time']))>=$deftime){

                        $ps_row = $AllinpayPsLog->getRow(["orderno"=>$info['orderno'],"userid"=>$info['customerid']],"id,query_sn");

                        if(!empty($ps_row) && $ps_row['query_sn']!=''){
                            $response = $ProcessServlet->getOrderResult([
                                    "orderno"=>$info['orderno'],
                                    "query_sn"=>$ps_row['query_sn'],
                                ]);

                            $doflag = false;

                            // $response['code']  201 重复提交 已经有查询结果  202处理失败
                            if($response['code']=='201'){
                                
                                if($row['retcode']=='0000'){
                                    $doflag = true;
                                }
                            }else if($response['code']=='202'){
                                $doflag = false;
                            }else if($response['code']=='200'){
                                $doflag = true;
                            }

                            if($doflag){
                                //处理成功
                                //金额返回
                                //事务处理
                                
                                $amountModel->startTrans();
                                
                                try{

                                    //$Withdrawals_row['handle_userid'] = $info['handle_userid'];
                                    $Withdrawals_row['pay_money']     = $info['amount'];
                                    $result = Model::new('User.Withdrawals')->pass($Withdrawals_row); //处理成功
       
                                    $CusWithdrawalsProcessservlet->update([
                                            "status"=>1,
                                            "pay_money"=>$info['amount'],
                                        ],["id"=>$info['id']]);

                                    //扣除手续费
                                    if($result && $Withdrawals_row['amount']>$Withdrawals_row['pay_money']){
                                        $flowid = Model::new("Amount.Flow")->getFlowId($Withdrawals_row['orderno']);
                                        //添加手续费--手续费放到公司账户中
                                        Model::new("Amount.Amount")->add_cashamount([
                                                "fromuserid"=>$Withdrawals_row['customerid'],
                                                "userid"=>"-1",
                                                "amount"=>$Withdrawals_row['amount']-$Withdrawals_row['pay_money'],
                                                "orderno"=>$Withdrawals_row['orderno'],
                                                "flowtype"=>86,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCusCash",
                                                "flowid"=>$flowid,
                                            ]);
                                    }



                                    $amountModel->commit(); 

                                    
                                } catch (\Exception $e) {
                                    
                                    // 错误日志
                                    // 回滚事务
                                    $amountModel->delRedis($info['customerid']);
                                    $amountModel->rollback();

                                    Log::add($e,__METHOD__);

                                }

                               
                                Model::new("Sys.Mq")->add([
                                    "url"=>"Order.OrderMsg.withdrawals",
                                    "param"=>[
                                        "orderno"=>$info['orderno']
                                    ],
                                ]);
                                Model::new("Sys.Mq")->submit();


                            }else{
                                // 处理失败 
                                $row = $AllinpayPsresultLog->getRow(["query_sn"=>$ps_row['query_sn']],"retcode,retmsg");
                                $CusWithdrawalsProcessservlet->update([
                                        "status"=>4,
                                        "remark"=>$row['retmsg'],
                                    ],["id"=>$info['id']]);
                                $Withdrawals->update([
                                        "status"=>4,
                                        "remark"=>$row['retmsg'],
                                    ],["id"=>$info['withdrawalsid']]);
                            }
                        }
                    }
                }

            }
            
            if(count($list)==0 || count($list)<$pagesize)
                break;

            
        }
    }

    
}