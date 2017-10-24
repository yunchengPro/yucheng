<?php
namespace app\model\Amount;
use app\lib\Model;
use think\Config;

class AmoAmountModel {
    
    /**
    * @user 获取用户余额
    * @param customerid 用户id
    * @author jeeluo
    * @date 2017年10月10日上午11:20:34
    */
    public function getAmount($param) {
        // 传递过来对象id值
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }
        
        $AmoAmount = Model::ins("AmoAmount");
        
        $amoAmount = $AmoAmount->getAmount($param['customerid'],"*");
        
        $result['cashamount'] = !empty($amoAmount['cashamount']) ? DePrice($amoAmount['cashamount']) : '0.00';
        $result['busamount'] = !empty($amoAmount['busamount']) ? DePrice($amoAmount['busamount']) : '0.00';
        $result['conamount'] = !empty($amoAmount['conamount']) ? DePrice($amoAmount['conamount']) : '0.00';
        $result['intamount'] = !empty($amoAmount['intamount']) ? DePrice($amoAmount['intamount']) : '0.00';
        $result['mallamount'] = !empty($amoAmount['mallamount']) ? DePrice($amoAmount['mallamount']) : '0.00';
        $result['stoamount'] = !empty($amoAmount['stoamount']) ? DePrice($amoAmount['stoamount']) : '0.00';
        
        return ["code" => "200", "data" => $result];
    }
    
    /**
    * @user 现金钱包流水统计(只统计收入  不包括提现)
    * @param cusotmerid 用户id
    * @param role 用户角色
    * @author jeeluo
    * @date 2017年10月10日下午2:26:24
    */
    public function getCashFlowAmount($param) {
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }
        $where['userid'] = $param['customerid'];
        $where['direction'] = 1;
        if($param['role'] == 1) {
            $where['flowtype'] = ["in", "14,15"];
        } else if($param['role'] == 2) {
//             $where['flowtype'] = 1;
        } else if($param['role'] == 3) {
            $where['flowtype'] = 16;
        } else if($param['role'] == 4) {
            $where['flowtype'] = 17;
        }
        
        $AmoFlowCash = Model::ins("AmoFlowCash");
        // 累计分红
        $totalBonus = $AmoFlowCash->getRow($where, "sum(amount) as amount");
        
        // 昨日分红
        $where['flowtime'] = [[">=",date("Y-m-d", strtotime("-1 day"))],["<",date("Y-m-d", time())]];
        $yesBonus = $AmoFlowCash->getRow($where, "sum(amount) as amount");
        
        // 今日分红
        $where['flowtime'] = [[">=",date("Y-m-d", time())],["<", date("Y-m-d", strtotime("+1 day"))]];
        $todayBonus = $AmoFlowCash->getRow($where, "sum(amount) as amount");
        
        
        $totalCommiss['amount'] = 0;
        $yesCommiss['amount'] = 0;
        $todayCommiss['amount'] = 0;
        
        if($param['role'] == 1) {
            
            $where['flowtype'] = 18;
            
            unset($where['flowtime']);
            // 累计提成
            $totalCommiss = $AmoFlowCash->getRow($where,"sum(amount) as amount");
            
            // 昨日提成
            $where['flowtime'] = [[">=",date("Y-m-d", strtotime("-1 day"))],["<",date("Y-m-d", time())]];
            $yesCommiss = $AmoFlowCash->getRow($where,"sum(amount) as amount");
            
            // 今日提成
            $where['flowtime'] = [[">=",date("Y-m-d", time())],["<", date("Y-m-d", strtotime("+1 day"))]];
            $todayCommiss = $AmoFlowCash->getRow($where, "sum(amount) as amount");
        }
        
        $result['yesBonus'] = !empty($yesBonus['amount']) ? DePrice($yesBonus['amount']) : '0.00';
        $result['todayBonus'] = !empty($todayBonus['amount']) ? DePrice($todayBonus['amount']) : '0.00';
        $result['totalBonus'] = !empty($totalBonus['amount']) ? DePrice($totalBonus['amount']) : '0.00';
        $result['yesCommiss'] = !empty($yesCommiss['amount']) ? DePrice($yesCommiss['amount']) : '0.00';
        $result['todayCommiss'] = !empty($todayCommiss['amount']) ? DePrice($todayCommiss['amount']) : '0.00';
        $result['totalCommiss'] = !empty($totalCommiss['amount']) ? DePrice($totalCommiss['amount']) : '0.00';
        
        return ["code" => "200", "data" => $result];
    }
    
    /**
    * @user 消费钱包流水统计(只计算收入的，不包括消费)
    * @param customerid 用户id
    * @author jeeluo
    * @date 2017年10月10日上午11:38:16
    */
    public function getConFlowAmount($param) {
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }
        
        $where['userid'] = $param['customerid'];
        $where['direction'] = 1;
        
        $AmoFlowCon = Model::ins("AmoFlowCon");
        // 累计
        $totalAmount = $AmoFlowCon->getRow($where,"sum(amount) as amount");
        // 昨日
        $where['flowtime'] = [[">=",date("Y-m-d", strtotime("-1 day"))],["<",date("Y-m-d", time())]];
        $yesAmount = $AmoFlowCon->getRow($where,"sum(amount) as amount");
        
        // 今日
        $where['flowtime'] = [[">=",date("Y-m-d", time())],["<", date("Y-m-d", strtotime("+1 day"))]];
        $todayAmount = $AmoFlowCon->getRow($where,"sum(amount) as amount");
        
        $result['yesAmount'] = !empty($yesAmount['amount']) ? DePrice($yesAmount['amount']) : '0.00';
        $result['todayAmount'] = !empty($todayAmount['amount']) ? DePrice($todayAmount['amount']) : '0.00';
        $result['totalAmount'] = !empty($totalAmount['amount']) ? DePrice($totalAmount['amount']) : '0.00';
        
        return ["code" => "200", "data" => $result];
    }
    
    /**
    * @user 获取消费类型余额
    * @param 
    * @author jeeluo
    * @date 2017年10月17日下午3:10:47
    */
    public function getConTypeAmount($param){
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }
        
        $where['userid'] = $param['customerid'];
        if(!empty($param['direction'])) {
            $where['direction'] = $param['direction'];
        }
        if(!empty($param['begintime'])) {
//             $where['begintime'] = $param['begintime'];
            $where['flowtime'] = [[">=",date("Y-m-d", $param['begintime'])],["<",date("Y-m-d", strtotime($param['begintime']+3600*24))]];
        }
        
        $AmoFlowCon = Model::ins("AmoFlowCon");
        
        $amount = $AmoFlowCon->getRow($where,"sum(amount) as amount");
        
        $result['amount'] = !empty($amount['amount']) ? DePrice($amount['amount']) : '0.00';
        
        return ["code" => "200", "data" => $result];
    }
    
    /**
    * @user 获取商家业绩流水统计
    * @param customerid 用户id
    * @author jeeluo
    * @date 2017年10月10日下午2:33:01
    */
    public function getBusFlowAmount($param) {
        if($param['customerid'] == '') {
            return ["code" => "404"];
        }
        
        $where['userid'] = $param['customerid'];
        $where['direction'] = 1;
        
        $AmoFlowBus = Model::ins("AmoFlowBus");
        // 累计
        $totalAmount = $AmoFlowBus->getRow($where,"sum(amount) as amount");
        
        // 昨日
        $where['flowtime'] = [[">=",date("Y-m-d", strtotime("-1 day"))],["<",date("Y-m-d", time())]];
        $yesAmount = $AmoFlowBus->getRow($where,"sum(amount) as amount");
        
        // 今日
        $where['flowtime'] = [[">=",date("Y-m-d", time())],["<", date("Y-m-d", strtotime("+1 day"))]];
        $todayAmount = $AmoFlowBus->getRow($where,"sum(amount) as amount");
        
        $result['yesAmount'] = !empty($yesAmount['amount']) ? DePrice($yesAmount['amount']) : '0.00';
        $result['todayAmount'] = !empty($todayAmount['amount']) ? DePrice($todayAmount['amount']) : '0.00';
        $result['totalAmount'] = !empty($totalAmount['amount']) ? DePrice($totalAmount['amount']) : '0.00';
        
        return ["code" => "200", "data" => $result];
    }
    
    /**
     * @user 获取提现手续费
     * @param
     * @author jeeluo
     * @date 2017年6月22日上午11:08:06
     */
    public function getWithdrawalsPoundage($params) {
        $customerid = $params['customerid'];
        $accountType = $params['accountType'];
        $type = $params['type'];        // type 1时 申请提现时  2 审核提现时
        $totalWithdrawals = array();
        if($type == 2) {
            $where['status'] = 1;
            
            $totalWithdrawals = Model::ins("CusWithdrawals")->getRow($where,"sum(cashamount) as amount");
        }
        
        $poundage = 0;
        if(!empty($totalWithdrawals)) {
            
            $withdrawals_config = Config::get("withdrawals");
            $poundage = $params['amount'] * $withdrawals_config['service_proportion'];
        }
        
        
        return $poundage;
        
    
//         $poundage = 0;
//         // 个人账户
//         if($accountType == -1) {
//             // 查看用户提现中或者提现成功的额度
//             $where['customerid'] = $customerid;
//             $where['accountType'] = $accountType;
//             $totalWithdrawals = array();
//             if($type == 1) {
//                 $where['status'] = ["<", 2];
//                 $totalWithdrawals = Model::ins("CusWithdrawals")->getRow($where, "sum(cashamount) as amount");
                 
//             } else if($type == 2) {
//                 $where['status'] = 1;
    
//                 $totalWithdrawals = Model::ins("CusWithdrawals")->getRow($where,"sum(cashamount) as amount");
//             }
//             $withdrawalsConfig = Config::get("withdrawals");
//             $amount = $params['amount'];
//             if($totalWithdrawals['amount'] > $withdrawalsConfig['personLimit']) {
//                 // 已经提现的金额大于限额，收取手续费
//                 $poundage = $amount * $withdrawalsConfig['personPro'] / $withdrawalsConfig['allPro'];
//             } else {
//                 // 已经提现的金额小于限额
//                 if($totalWithdrawals['amount'] + $amount > $withdrawalsConfig['personLimit']) {
//                     // 可能刚好在临界点，产生手续费
//                     $outRange = $totalWithdrawals['amount'] + $amount - $withdrawalsConfig['personLimit'];
    
//                     $poundage = $outRange * $withdrawalsConfig['personPro'] / $withdrawalsConfig['allPro'];
//                 }
//             }
//         }
//         return $poundage;
    }
}