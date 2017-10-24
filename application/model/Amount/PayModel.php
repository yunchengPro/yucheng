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

        $flowid           = $param['flowid'];

        $flowtype_cash    = !empty($param['flowtype_cash'])?$param['flowtype_cash']:5;
        $flowtype_bus  = !empty($param['flowtype_bus'])?$param['flowtype_bus']:6;
        $flowtype_con    = !empty($param['flowtype_con'])?$param['flowtype_con']:7;
        $flowtype_int = !empty($param['flowtype_int'])?$param['flowtype_int']:8;

        // 判断重复支付
        $check_result = $this->checkpay([
                "userid"=>$userid,
                "cashamount"=>$cashamount,
                "busamount"=>$busamount,
                "conamount"=>$conamount,
                "intamount"=>$intamount,
                "flowtype_cash"=>$flowtype_cash,
                "flowtype_bus"=>$flowtype_bus,
                "flowtype_con"=>$flowtype_con,
                "flowtype_int"=>$flowtype_int,
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

        //收益现金余额支付
        if($busamount>0){
            $result = Model::new("Amount.Amount")->pay_profitamount([
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

        //牛豆余额支付
        if($conamount>0){
            $result = Model::new("Amount.Amount")->pay_bullamount([
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

        //企业账户余额支付
        if($intamount>0){
            $result = Model::new("Amount.Amount")->pay_comcashamount([
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
            $result = Model::new("Amount.Amount")->check_pay_profitamount([
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
            $result = Model::new("Amount.Amount")->check_pay_bullamount([
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
            $result = Model::new("Amount.Amount")->check_pay_comcashamount([
                                                                "userid"=>$param['userid'],
                                                                "amount"=>$param['intamount'],
                                                                "orderno"=>$param['orderno'],
                                                                "flowtype"=>$param['flowtype_int'],
                                                            ]);

            if($result['code']!='200')
                return $result;
        }
        
        return ["code"=>"200"];
    }
}