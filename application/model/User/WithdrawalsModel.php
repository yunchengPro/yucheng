<?php
namespace app\model\User;

//use app\lib\Db;
use think\Config;
use app\lib\Model;
use app\lib\Log;

use app\model\Sys\CommonModel;

class WithdrawalsModel
{
    /**
     * [pass 通过审核]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月7日 上午11:11:11
     * @return [type]    [description]
     */
    protected $statusArr = ["0"=>"处理中","1"=>"提现成功","2"=>"提现失败","3"=>"转账中","4"=>"转账失败"];
    

    public function getNo(){
        return "";
    }

    /**
     * 提交提现请求
     * @Author   zhuangqm
     * @Datetime 2017-10-08T01:49:46+0800
     * @param    [type]                   $param [
     *                                           customerid
     *                                           amount 元
     *                                     ]
     */
    public function addWithdrawals($param){
        
        // 校验支付密码
        $validPayResult = Model::new("User.Setting")->validPayPwd($param);
        
        if($validPayResult['code'] != "200") {
            return ["code" => $validPayResult["code"]];
        }

        // 提现要求：1 整数500倍数 2 每天提现一次
        
        $amount = $param['amount'];
        $withdrawals_config = Config::get("withdrawals");

        if(($amount%$withdrawals_config['multiple'])!=0)
            return ["code"=>"1101"];

        $amount = EnPrice($amount);
        $CusWithdrawals = Model::ins('CusWithdrawals'); 

        $startime = date("Y-m-d 00:00:00", time()-((date('w')==0?7:date('w'))-1)*24*3600);
//         $endtime = date("Y-m-d 23:59:59",time()+(7-(date('w')==0?7:date('w')))*24*3600);
        $endtime = date("Y-m-d 23:59:59",time()-((date('w')==0?7:date('w'))-1)*24*3600);
        
        $nowtime = getFormatNow();
        if($startime > $nowtime || $endtime < $nowtime) {
            return ["code" => "1104"];
        }

        $row = $CusWithdrawals->getRow([
                "customerid"=>$param['customerid'],
                "status"=>["in","0,1"],
                "addtime"=>[[">=",$startime],["<=",$endtime]],
            ],"count(*) as count");

        if($row['count']>=$withdrawals_config['frequency'])
            return ["code"=>"1103"];

        // 获取用户余额
//         $user_cashamount = Model::new("Amount.Amount")->getUserAmount($param['fromuserid'],"cashamount");

        $user_cashamount = Model::new("Amount.Amount")->getUserAmount($param['customerid'],"cashamount");

        if($user_cashamount<$amount)
            return ["code"=>"1003"];
        
        $bankInfo = Model::ins("CusBank")->getRow(["id"=>$param['bankid'],"customerid"=>$param['customerid'],"enable"=>1],"*");
        if(empty($bankInfo)) {
            return ["code" => "1000"];
        }

        $orderno = Model::new("CusWithdrawals")->getOrderNo();

//         $cashamount = $user_cashamount-$amount;

        // 事务处理
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();
        
        try {

            $status = $CusWithdrawals->insert([
                    'customerid' => $param['customerid'],
                    'bankid' => $param['bankid'],
                    'bank_type_name' => $bankInfo['bank_type_name'],
                    'account_name' => $bankInfo['account_name'],
                    'account_number' => $bankInfo['account_number'],
                    'branch' => $bankInfo['branch'], // 支行名称
                    'mobile' => $bankInfo['mobile'],
    //                 'bank_type_name' => $param['bank_type_name'],
    //                 'account_name' => $param['account_name'],
    //                 'account_number' => $param['account_number'],
    //                 'branch' => $param['branch'], // 支行名称
    //                 'mobile' => $param['mobile'],
                    'amount' => $amount,
    //                 'cashamount'=> $cashamount,
                    'cashamount' => $amount,
                    'addtime' =>  date('Y-m-d H:i:s'),
                    'orderno'=>$orderno,
                ]);
            
            if($status) {
                // 现金冻结
                Model::new("Amount.Frozen")->FrozenCashAmount([
                    "userid" => $param['customerid'],
                    "amount" => $amount,
                    "orderno" => $orderno
                ]);
            }
            
            $amountModel->commit();
        } catch (\Exception $e) {
            $amountModel->rollback();
            Log::add($e,__METHOD__);
            
            return ["code" => "400"];
        }
        return ["code" => "200"];
    }

    /**
     * 通过审核
     * @Author   zhuangqm
     * @Datetime 2017-10-08T01:49:08+0800
     * @param    [type]                   $withdrawalsInfo [description]
     * @return   [type]                                    [description]
     */
    public function pass($withdrawalsInfo)
    {
        //var_dump('WithdrawalsModel pass'); exit(); 
        //
        $withdrawalsInfo['pay_money'] = EnPrice($withdrawalsInfo['pay_money']); 
        
        $CusWithdrawals = Model::ins('CusWithdrawals'); 

        $Withdrawals = $CusWithdrawals->getRow(['id' => $withdrawalsInfo['id']],"*");

        
        //TODO 扣除提现金额
        
        //用户名        
        //$handleUser   = Model::ins("CusCustomerInfo")->getRow(["id"=>$withdrawalsInfo['handle_userid']],"realname,nickname");
        //var_dump($handleUser); exit();
        
        //保存提现数据
        $passTime = date('Y-m-d H:i:s');
        $data = array(
            //'handle_userid' => $withdrawalsInfo['handle_userid'],
            //'handle_user'   => $handleUser['realname'],
            'status'        => 1,
            'pay_time'      => $passTime,            
            'pay_money'     => $withdrawalsInfo['pay_money'], 
        );
//         $result = $CusWithdrawals->update($data, ['id' => $withdrawalsInfo['id']]);

        // 20%转积分 70%支付提现 10%手续费(10.10)
        
//         print_r($Withdrawals);
//         exit;
        
        $withdrawals_config = Config::get("withdrawals");
        
        // 10% online 10% sto 70% pay 10% proportion
        
        // 10% online 10% sto 事务
        
        // 事务处理
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();
        try {
            // 修改状态
            $result = $CusWithdrawals->update($data, ['id' => $withdrawalsInfo['id']]);

            // 先转换值
            $Withdrawals['amount'] = DePrice($Withdrawals['amount']);

            $flowid = Model::new("Amount.Flow")->getFlowId($Withdrawals['orderno']);

            // 10% online
            Model::new("Amount.Amount")->add_mallamount([
                "tablename" => "AmoFlowMall",
                "userid" => $Withdrawals['customerid'],
                "amount" => EnPrice($Withdrawals['amount'] * $withdrawals_config['mall_proportion']),
                "usertype" => 1,
                "orderno" => $Withdrawals['orderno'],
                "flowtype" => 43,
                "flowid" => $flowid
            ]);

            // 10% sto
            Model::new("Amount.Amount")->add_stoamount([
                "tablename" => "AmoFlowMall",
                "userid" => $Withdrawals['customerid'],
                "amount" => EnPrice($Withdrawals['amount'] * $withdrawals_config['sto_proportion']),
                "usertype" => 1,
                "orderno" => $Withdrawals['orderno'],
                "flowtype" => 44,
                "flowid" => $flowid
            ]);
            
            // 公司企业账户 添加手续费流水
            $poundage = EnPrice($Withdrawals['amount'] * $withdrawals_config['service_proportion']);
            
            // 10% 手续费
            Model::new("Amount.Amount")->add_com_cashamount([
                "tablename" => "AmoFlowComCash",
                "userid" => -1,
                "fromuserid" => $Withdrawals['customerid'],
                "amount" => $poundage,
                "usertype" => 2,
                "orderno" => $Withdrawals['orderno'],
                "flowtype" => 42,
                "flowid" => $flowid
            ]);
            
            $amountModel->commit();
            
        } catch (\Exception $e) {
            $amountModel->rollback();
            Log::add($e,__METHOD__);
            
            return ["code" => "400"];
        }
        
        return ["code" => "200"];

//         $intamount = intval($withdrawalsInfo['pay_money']*$withdrawals_config['integral_proportion']);

//         $flowid = Model::new("Amount.Flow")->getFlowId($Withdrawals['orderno']);

//         // 增加积分
//         $result = Model::new("Amount.Amount")->add_intamount([
//                 "userid"=>$Withdrawals['customerid'],
//                 "amount"=>$intamount,
//                 "usertype"=>1,
//                 "tablename"=>"AmoFlowInt",
//                 "flowid"=>$flowid,
//                 "orderno"=>$Withdrawals['orderno'],
//                 "role"=>1,
//             ]);
        
        
        return $result;
    }

    
    /**
     * [noPass 不通过审核]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月7日 下午1:57:52
     * @return [type]    [description]
     */
    public function noPass($withdrawalsInfo)
    {
        //var_dump('WithdrawalsModel noPass'); exit();
    
        $model = Model::ins('CusWithdrawals');  
        
        //用户名
        $handleUser   = Model::ins("CusCustomerInfo")->getById($withdrawalsInfo['handle_userid']);
        
        $noPassTime = date('Y-m-d H:i:s');
        $data = array(           
            'remark'        => $withdrawalsInfo['remark'],
            'handle_userid' => $withdrawalsInfo['handle_userid'],
            'handle_user'   => $handleUser['realname'],
            'status'        => 2,
            'pay_time'      => $noPassTime,         
        );
        $result = $model->update($data, ['id' => $withdrawalsInfo['id']]);
        
        return $result;
    }
    
    public function setRow($info)
    {       
        $result = array();
        $result['id']             = $info['id'];
        $result['amount']         = DePrice($info['amount']);
        $result['bank_name']      = $info['bank_type_name'];
        $result['account_number'] = CommonModel::bank_format($info['account_number']);
		$result['account_name']   = $info['account_name'];
        $result['status']         = $info['status'];
        $result['statusStr']    = $info['statusStr'];
        $result['addtime']        = $info['addtime'];
        $result['due_pay_time']   = date('Y-m-d H:i:s', strtotime($info['addtime']) + (3600*24));
        $result['pay_time']       = $info['pay_time'];
        $result['orderno']        = $info['orderno'];
        
        return $result;
    }
    
    public function setRows($list)
    {
        foreach($list as $key => $val){
            $list[$key] = $this->setRow($val);
        }
        
        return $list;
    }
    
    /**
     * [getInfo 获取提现信息]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月7日 下午9:07:48
     * @return [type]    [description]
     */
    public function getInfo($id, $customerid)
    {   
        $result = array();
        
        $model = Model::ins('CusWithdrawals');
        //$info  = $model->getById($id); 
        $where = array('id' => $id);
        if(!empty($customerid)){
            $where['customerid'] = $customerid;
        }
        $info  = $model->getRow($where);
        
        if(!empty($info)){
            $result = $this->setRow($info);
        }
        
        return $result;        
    }

    public function getInfoByOrderno($orderno, $customerid)
    {   
        $result = array();
        
        $model = Model::ins('CusWithdrawals');
        //$info  = $model->getById($id); 
        $where = array('orderno' => $orderno);
        if(!empty($customerid)){
            $where['customerid'] = $customerid;
        }
        $info  = $model->getRow($where);
        $statusStr = $this->statusArr[$info['status']];
        $info['statusStr'] = !empty($statusStr) ? $statusStr : '';
        
        if(!empty($info)){
            $result = $this->setRow($info);
        }
        
        return $result;        
    }
    
    /**
     * [getInfo 获取提现列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月7日 下午9:07:48
     * @return [type]    [description]
     */
    public function getList($params)
    {
        $where = array('customerid' => $params['customerid']);
        if(!empty($params['begintime'])) {
            $monthBegin = strtotime("".$params['begintime']."");
            $monthEnd = strtotime("+1 month", $monthBegin);

            $where['addtime'] = array(array(">=", $params['begintime']), array("<", date("Y-m-d H:i:s",$monthEnd)));
        }
        $list  = Model::ins('CusWithdrawals')->pageList($where, '*', 'addtime desc, id desc');
        foreach ($list['list'] as $k => $v) {
            $statusStr = $this->statusArr[$v['status']];
            $list['list'][$k]['statusStr'] = !empty($statusStr) ? $statusStr : '';
        }
        $list['list'] = $this->setRows($list['list']);
        
        return $list;
    }

    /**
    * @user 获取用户最后一次提现记录信息
    * @param $customerid 用户id值
    * @author jeeluo
    * @date 2017-05-27 14:18:07
    */
    public function getLastWithdraw($params) {
        $withdrawInfo = Model::ins("CusWithdrawals")->getRow(array("customerid"=>$params['customerid']),"bankid","id desc");
        if(empty($withdrawInfo)) {
            $withdrawInfo = array();
        }
        return $withdrawInfo;
    }
    
    /**
    * @user 根据流水号 查询提现信息
    * @param 
    * @author jeeluo
    * @date 2017年7月26日下午7:38:18
    */
    public static function getOrderByWithdrawls($params) {
        // 根据流水id 查询订单数据
        $flow = Model::ins("AmoFlowCash")->getRow(["id"=>$params['id']],"orderno");
        if(empty($flow['orderno'])) {
            return ["code"=>"1000"];
        }
    
        // 根据订单号 获取提现信息
        $withdrawals = Model::ins("CusWithdrawals")->getRow(["orderno"=>$flow['orderno']],"id,bankid,account_number,status");
        if(empty($withdrawals['id'])) {
            return ["code"=>"1000"];
        }
        
        // 根据银行id值  获取银行卡信息
        $bankInfo = Model::ins("CusBank")->getRow(["id"=>$withdrawals['bankid']],"bank_type_name");
    
//         if($withdrawals['status'] != 1) {
//             return ["code"=>"1001"];
//         }
        
        $returnData['bank_name'] = $bankInfo['bank_type_name'];
        $returnData['account_number'] = CommonModel::last4_bank($withdrawals['account_number']);
        return ["code"=>"200", "data"=>$returnData];
    }
}