<?php
// +----------------------------------------------------------------------
// |  [ 结算 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-03
// +----------------------------------------------------------------------
namespace app\model\Business;

use app\lib\Model;

use app\lib\Log;

class SettlementModel{

    /**
     * 实体店结算
     * @Author   zhuangqm
     * @DateTime 2017-04-12T20:35:38+0800
     * @return   [type]                   [description]
     */
	public function pay($param){

        $orderno = $param['orderno'];

        $order = Model::ins("OrdOrder")->getRow(['orderno'=>$orderno],"customerid,businessid,actualfreight");

        $orderitem_list     = Model::ins("OrdOrderItem")->getList([
                                        "orderno"=>$orderno,
                                    ],"skuid,productnum,supplyprice");
       
        $itemlist = [];
        foreach($orderitem_list as $k=>$v){
            $itemlist[$v['skuid']] = $v;
        }

        $orderreturn_list   = Model::ins("OrdOrderReturn")->getList([
                                        "order_code"=>$orderno,
                                        "orderstatus"=>["in","4,14"],
                                    ],"skuid,productnum");
        foreach($orderreturn_list as $k=>$v){
            if($itemlist[$v['skuid']]['productnum']>=$v['productnum'])
                $itemlist[$v['skuid']]['productnum']-=$v['productnum'];
        }

        $amount = 0;
        foreach($itemlist as $k=>$v){
            $amount+=$v['productnum']*$v['supplyprice'];
        }

        $amount+=$order['actualfreight']; // 算上运费

        if($amount>0){

            //$rowcount = Model::ins("AmoFlowBusCash")->getRow(["orderno"=>$orderno,"direction"=>1],"count(*) as count");

            if($rowcount['count']==0){
                // $Business = Model::ins("BusBusiness")->getRow(["id"=>$order['businessid']],"customerid");

                //生成流水号
                $flowid = Model::new("Amount.Flow")->getFlowId($orderno);

                // 增加营业收入
                Model::new("Amount.Amount")->add_com_cashamount([
                                                "fromuserid"=>$order['customerid'],
                                                // "userid"=>$Business['customerid'],
                                                "amount"=>$amount,
                                                "orderno"=>$orderno,
                                                "flowtype"=>68,
                                                "tablename"=>"AmoFlowComCash",
                                                "flowid"=>$flowid,
                                            ]);

                
                // Model::ins("AmoFlowBusCash")->insert([
                //     "flowid"=>$flowid,
                //     "userid"=>$Business['customerid'],
                //     "businessid"=>$order['businessid'],
                //     "fromuserid"=>$Business['customerid'],
                //     "orderno"=>$orderno,
                //     "flowtype"=>16,
                //     "direction"=>1,
                //     "flowtime"=>date("Y-m-d H:i:s"),
                //     "amount"=>$amount,
                //     "remark"=>'',
                //     //"amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                // ]);

                // 待收货款的状态处理
                // Model::ins("AmoFlowFutBusCash")->update(["futstatus"=>1],["orderno"=>$orderno]);
                // Model::ins("AmoFlowFutCusComCash")->update(["futstatus"=>1],["orderno"=>$orderno]);
            }
        }

        

        return true;
    }

    /**
     * 售后退款，用户申请退款，商家确认后，从商家货款中扣减对应金额
     * @Author   zhuangqm
     * @DateTime 2017-05-05T14:24:52+0800
     * @param    [type]                   $param [description]
     *                                          returnno 退货单号
     * @return   [type]                          [description]
     */
    public function returnpay($param){

        $returnno = $param['returnno'];

        $orderreturn   = Model::ins("OrdOrderReturn")->getRow([
                                        "returnno"=>$returnno,
                                        "orderstatus"=>["in","1,12"],
                                    ],"orderstatus,business_id,order_code,skuid,productnum");

        $orderno = $orderreturn['order_code'];

        if(!empty($orderreturn)){

            //$rowcount = Model::ins("AmoFlowBusCash")->getRow(["orderno"=>$orderno,"direction"=>2],"count(*) as count");

            // if($rowcount['count']==0){

                $orderitem     = Model::ins("OrdOrderItem")->getRow([
                                                "orderno"=>$orderreturn['order_code'],
                                                "skuid"=>$orderreturn['skuid'],
                                            ],"productnum,supplyprice");

                $amount = $orderreturn['productnum']*$orderitem['supplyprice'];
                
                // $Business = Model::ins("BusBusiness")->getRow(["id"=>$orderreturn['business_id']],"customerid");

                //生成流水号
                $flowid = Model::new("Amount.Flow")->getFlowId($orderno);
                // 扣减营业收入
                Model::new("Amount.Amount")->pay_com_cashamount([
                                                "fromuserid"=>$order['customerid'],
                                                // "userid"=>$Business['customerid'],
                                                "amount"=>$amount,
                                                "orderno"=>$orderno,
                                                "flowtype"=>69,
                                                "tablename"=>"AmoFlowComCash",
                                                "flowid"=>$flowid,
                                            ]);

                
                // Model::ins("AmoFlowBusCash")->insert([
                //     "flowid"=>$flowid,
                //     "userid"=>$Business['customerid'],
                //     "businessid"=>$orderreturn['business_id'],
                //     "fromuserid"=>$Business['customerid'],
                //     "orderno"=>$orderno,
                //     "flowtype"=>16,
                //     "direction"=>2,
                //     "flowtime"=>date("Y-m-d H:i:s"),
                //     "amount"=>$amount,
                //     "remark"=>'',
                //     //"amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
                // ]);


                // 待收货款的扣款
                // Model::ins("AmoFlowFutBusCash")->update("amount=amount-".intval($amount),["orderno"=>$orderno]);
                // Model::ins("AmoFlowFutCusComCash")->update("amount=amount-".intval($amount),["orderno"=>$orderno]);
            // }
        }
        return true;
    }

    /**
     * 未发货状态下的用户退款处理
     * @Author   zhuangqm
     * @DateTime 2017-05-08T19:54:00+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function returnfutpay($param){
        return true;
        // $returnno = $param['returnno'];

        // $orderreturn   = Model::ins("OrdOrderReturn")->getRow([
        //                                 "returnno"=>$returnno,
        //                                 "orderstatus"=>["in","1,12"],
        //                             ],"orderstatus,business_id,order_code,skuid,productnum,freight");

        // $orderno = $orderreturn['order_code'];

        // if(!empty($orderreturn)){

        //     $orderitem     = Model::ins("OrdOrderItem")->getRow([
        //                                     "orderno"=>$orderreturn['order_code'],
        //                                     "skuid"=>$orderreturn['skuid'],
        //                                 ],"productnum,supplyprice");

        //     $amount = $orderreturn['productnum']*$orderitem['supplyprice']+$orderreturn['freight'];

        //     Model::ins("AmoFlowFutBusCash")->update("amount=amount-".intval($amount),["orderno"=>$orderno]);
        //     Model::ins("AmoFlowFutCusComCash")->update("amount=amount-".intval($amount),["orderno"=>$orderno]);
        // }
        // return true;
    }

    /**
     * 未发货，订单取消
     * @Author   zhuangqm
     * @DateTime 2017-05-09T11:33:47+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function returnallfutpay($param){
        return true;
        // $orderno = $param['orderno'];

        // if(!empty($orderno)){

        //     Model::ins("AmoFlowFutBusCash")->update(["futstatus"=>2],["orderno"=>$orderno]);
        //     Model::ins("AmoFlowFutCusComCash")->update(["futstatus"=>2],["orderno"=>$orderno]);

        // }
        // return true;
    }

    /**
     * 商家待收货款
     * @Author   zhuangqm
     * @DateTime 2017-05-05T17:57:43+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function futpay($param){
        return true;
        // $orderno = $param['orderno'];

        // $order = Model::ins("OrdOrder")->getRow(['orderno'=>$orderno],"customerid,businessid,actualfreight");

        // $orderitem_list     = Model::ins("OrdOrderItem")->getList([
        //                                 "orderno"=>$orderno,
        //                             ],"skuid,productnum,supplyprice");
       
        // $itemlist = [];
        // foreach($orderitem_list as $k=>$v){
        //     $itemlist[$v['skuid']] = $v;
        // }

        // $amount = 0;
        // foreach($itemlist as $k=>$v){
        //     $amount+=$v['productnum']*$v['supplyprice'];
        // }

        // $amount+=$order['actualfreight']; // 算上运费


        // $rowcount = Model::ins("AmoFlowFutBusCash")->getRow(["orderno"=>$orderno,"direction"=>1],"count(*) as count");

        // if($rowcount['count']==0){
            
        //     $Business = Model::ins("BusBusiness")->getRow(["id"=>$order['businessid']],"customerid");
        //     //生成流水号
        //     $flowid = Model::new("Amount.Flow")->getFlowId($orderno);
        //     // 待营业收入
        //     Model::new("Amount.Fut")->add_fut_comcashamount([
        //                                             "fromuserid"=>$Business['customerid'],
        //                                             "userid"=>$Business['customerid'],
        //                                             "amount"=>$amount,
        //                                             "orderno"=>$orderno,
        //                                             "flowtype"=>16,
        //                                             "tablename"=>"AmoFlowFutCusComCash",
        //                                             "flowid"=>$flowid,
        //                                         ]);

        //     Model::ins("AmoFlowFutBusCash")->insert([
        //         "flowid"=>$flowid,
        //         "userid"=>$Business['customerid'],
        //         "businessid"=>$order['businessid'],
        //         "fromuserid"=>$Business['customerid'],
        //         "orderno"=>$orderno,
        //         "flowtype"=>16,
        //         "direction"=>1,
        //         "flowtime"=>date("Y-m-d H:i:s"),
        //         "amount"=>$amount,
        //         //"amounttype"=>1, // amounttype  1现金 2收益现金 3牛币
        //     ]);

        // }
    }


}