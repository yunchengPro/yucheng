<?php
// +----------------------------------------------------------------------
// |  [ 支付 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-03-015
// +----------------------------------------------------------------------
namespace app\model\Amount;

use app\lib\Model;

class PayModel{

    /**
     * 支付入口
     * @Author   zhuangqm
     * @DateTime 2017-03-16T14:06:38+0800
     * @param    [type]                   $param [
     *                                           userid          用户ID
     *                                           cashamount      现金余额
     *                                           busamount       收益现金余额
     *                                           conamount       牛豆
     *                                           intamount       牛豆
     *                                           扩展其他的支付渠道
     * ]
     * @return   [type]                          [description]
     */
	public function pay($param){
        $userid           = $param['userid'];
        $cashamount       = intval($param['cashamount']);
        $busamount        = intval($param['busamount']);
        $conamount        = intval($param['conamount']);
        $intamount        = intval($param['intamount']);
        $mallamount       = intval($param['mallamount']);
        $stoamount        = intval($param['stoamount']);
        $recamount        = intval($param['recamount']);

        $flowid           = $param['flowid'];

        $flowtype_cash    = !empty($param['flowtype_cash'])?$param['flowtype_cash']:5;
        $flowtype_bus  = !empty($param['flowtype_bus'])?$param['flowtype_bus']:6;
        $flowtype_con    = !empty($param['flowtype_con'])?$param['flowtype_con']:7;
        $flowtype_int = !empty($param['flowtype_int'])?$param['flowtype_int']:8;
        $flowtype_mall = !empty($param['flowtype_mall'])?$param['flowtype_mall']:4;
        $flowtype_sto = !empty($param['flowtype_sto'])?$param['flowtype_sto']:3;
        $flowtype_rec = !empty($param['flowtype_rec'])?$param['flowtype_rec']:70;

        // 判断重复支付
        $check_result = $this->checkpay([
                "userid"=>$userid,
                "cashamount"=>$cashamount,
                "busamount"=>$busamount,
                "conamount"=>$conamount,
                "intamount"=>$intamount,
                "mallamount"=>$mallamount,
                "stoamount"=>$stoamount,
                "recamount"=>$recamount,
                "flowtype_cash"=>$flowtype_cash,
                "flowtype_bus"=>$flowtype_bus,
                "flowtype_con"=>$flowtype_con,
                "flowtype_int"=>$flowtype_int,
                "flowtype_mall"=>$flowtype_mall,
                "flowtype_sto"=>$flowtype_sto,
                "flowtype_rec"=>$flowtype_rec,
                "orderno"=>$param['orderno'],
            ]);

        if($check_result['code']!='200')
            return $check_result;

        //现金余额支付
        if($cashamount>0){
            $result = Model::new("Amount.Amount")->pay_cashamount([
                                                                "userid"=>$userid,
                                                                "amount"=>$cashamount,
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$flowtype_cash,
                                                                "role"=>1,
                                                                "remark"=>$param['remark'],
                                                                "flowid"=>$flowid,
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        //商家营业额余额支付
        if($busamount>0){
            $result = Model::new("Amount.Amount")->pay_busamount([
                                                                "userid"=>$userid,
                                                                "amount"=>$busamount,
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$flowtype_bus,
                                                                "role"=>1,
                                                                "remark"=>$param['remark'],
                                                                "flowid"=>$flowid,
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        //消费券余额支付
        if($conamount>0){
            $result = Model::new("Amount.Amount")->pay_conamount([
                                                                "userid"=>$userid,
                                                                "amount"=>$conamount,
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$flowtype_con,
                                                                "role"=>1,
                                                                "remark"=>$param['remark'],
                                                                "flowid"=>$flowid,
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        //积分余额支付
        if($intamount>0){
            $result = Model::new("Amount.Amount")->pay_intamount([
                                                                "userid"=>$userid,
                                                                "amount"=>$intamount,
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$flowtype_int,
                                                                "role"=>1,
                                                                "remark"=>$param['remark'],
                                                                "flowid"=>$flowid,
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        //商城消费余额支付
        if($mallamount>0){
            $result = Model::new("Amount.Amount")->pay_mallamount([
                                                                "userid"=>$userid,
                                                                "amount"=>$mallamount,
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$flowtype_mall,
                                                                "role"=>1,
                                                                "remark"=>$param['remark'],
                                                                "flowid"=>$flowid,
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        //实体店消费余额支付
        if($stoamount>0){
            $result = Model::new("Amount.Amount")->pay_stoamount([
                                                                "userid"=>$userid,
                                                                "amount"=>$stoamount,
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$flowtype_sto,
                                                                "role"=>1,
                                                                "remark"=>$param['remark'],
                                                                "flowid"=>$flowid,
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        if($recamount>0){
            $result = Model::new("Amount.Amount")->pay_recamount([
                                                                "userid"=>$userid,
                                                                "amount"=>$recamount,
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$flowtype_rec,
                                                                "role"=>1,
                                                                "remark"=>$param['remark'],
                                                                "flowid"=>$flowid,
                                                            ]);

            if($result['code']!='200')
                return $result;
        }
        
        return ["code"=>"200"];
    }

    // 判断订单是否已支付
    public function checkpay($param){

        //现金余额支付
        if($param['cashamount']>0){
            $result = Model::new("Amount.Amount")->check_pay_cashamount([
                                                                "userid"=>$param['userid'],
                                                                "amount"=>$param['cashamount'],
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$param['flowtype_cash'],
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        //收益现金余额支付
        if($param['busamount']>0){
            $result = Model::new("Amount.Amount")->check_pay_busamount([
                                                                "userid"=>$param['userid'],
                                                                "amount"=>$param['busamount'],
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$param['flowtype_bus'],
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        //牛豆余额支付
        if($param['conamount']>0){
            $result = Model::new("Amount.Amount")->check_pay_conamount([
                                                                "userid"=>$param['userid'],
                                                                "amount"=>$param['conamount'],
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$param['flowtype_con'],
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        //企业账户余额支付
        if($param['intamount']>0){
            $result = Model::new("Amount.Amount")->check_pay_intamount([
                                                                "userid"=>$param['userid'],
                                                                "amount"=>$param['intamount'],
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$param['flowtype_int'],
                                                            ]);

            if($result['code']!='200')
                return $result;
        }

        
        if($param['mallamount']>0){
            $result = Model::new("Amount.Amount")->check_pay_mallamount([
                                                                "userid"=>$param['userid'],
                                                                "amount"=>$param['mallamount'],
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$param['flowtype_mall'],
                                                            ]);

            if($result['code']!='200')
                return $result;
        }


        if($param['stoamount']>0){
            $result = Model::new("Amount.Amount")->check_pay_stoamount([
                                                                "userid"=>$param['userid'],
                                                                "amount"=>$param['stoamount'],
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$param['flowtype_sto'],
                                                            ]);

            if($result['code']!='200')
                return $result;
        }
        
        return ["code"=>"200"];
    }
}