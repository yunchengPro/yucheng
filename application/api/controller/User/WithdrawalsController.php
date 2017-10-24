<?php
namespace app\api\controller\User;

use app\lib\Model;

use app\model\User\PaypwdModel;

use app\api\ActionController;

use app\lib\Log;

use app\model\Sys\CommonModel;

use think\Config;

class WithdrawalsController extends ActionController {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * [indexAction 提现申请页面]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月6日 下午4:57:12
     * @return [type]    [description]
     */
    public function indexAction() {        
        $userId = $this->userid;
        //var_dump($userId); exit();
        
        $useramount = Model::new("Amount.Amount")->getUserWithdrawalsAmount($userId);

        //总余额
        $amount = DePrice($useramount['cashamount']+$useramount['comamount']);
        //var_dump($cashamount); exit();

        if($this->Version("1.0.2") || $this->Version("1.0.3")) {
            // 查询用户是否有企业帐号
            $isUserCom = CommonModel::isUserCom($userId);
            
            // 查询用户是否已经实名认证
            $isNameAuth = CommonModel::getUserNameAuth($userId);

            // 个人余额
            $amount = DePrice($useramount['cashamount']);

            $comamount = $isUserCom['status'] == 1 ? DePrice($useramount['comamount']) : '0.00';
        }
        
        $params['customerid'] = $userId;
        $params['enable'] = 1;
        $lastWithdraw = Model::new("User.Withdrawals")->getLastWithdraw($params);
        if(!empty($lastWithdraw)) {
            $bank = Model::ins("CusBank")->getRow(array("id"=>$lastWithdraw['bankid'],"enable"=>1),"id,bank_type_name,account_type,account_number");
            if(!empty($bank)) {
                $defaultBank = array("id"=>$bank['id'],"bank_name"=>$bank['bank_type_name'],"account_type"=>$bank['account_type'],"account_number"=>CommonModel::bank_format($bank['account_number']));
            } else {
                $defaultBank = array("id"=>"","bank_name"=>"","account_type"=>"","account_number"=>"");
            }
        } else {
            $defaultBank = Model::new("User.User")->getDefaultBank($params);
        }
        //var_dump($result); exit();
        
        $issetpaypwd = PaypwdModel::issetpaypwd(["userid"=>$userId]);
        $issetpaypwd = $issetpaypwd['code']=='200' ? 1 : 0;
        
        $withdrawalsInfo = array(
            'defaultBank' => $defaultBank,
            'cashamount'  => $amount,
            'comamount' => '0.00',
            'issetpaypwd' => $issetpaypwd,
            'info'        => '单笔金额不得低于100元',
        );

        if($this->Version("1.0.2") || $this->Version("1.0.3")) {
            $withdrawalsInfo['authStatus'] = !empty($isNameAuth) ? 1 : -1;
            $withdrawalsInfo['isUserComStatus'] = $isUserCom['status'];
            $withdrawalsInfo['comamount'] = $comamount;
            
            $withdrawalsConfig = Config::get("withdrawals");
            $withdrawalsInfo['personLimit'] = DePrice($withdrawalsConfig['personLimit']);
            $withdrawalsInfo['personPro'] = $withdrawalsConfig['personPro'];
            
            $withdrawalsWhere['customerid'] = $userId;
            $withdrawalsWhere['accountType'] = -1;
            $withdrawalsWhere['status'] = 0;
            $waitWithdrawals = Model::ins("CusWithdrawals")->getRow($withdrawalsWhere,"sum(cashamount) as amount");
            
            $withdrawalsWhere['status'] = 1;
            $passWithdrawals = Model::ins("CusWithdrawals")->getRow($withdrawalsWhere,"sum(pay_money) as amount");
            
            $withdrawalsInfo['userWithdrawals'] = DePrice($waitWithdrawals['amount'] + $passWithdrawals['amount']);
            
            $withdrawalsInfo['titlestr'] = "提现说明";
            $withdrawalsInfo['content'] = "1、个人账户提现免手续费额度累积为".DePrice($withdrawalsConfig['personLimit'])."元；\n2、超过".DePrice($withdrawalsConfig['personLimit'])."元以上按提现金额".$withdrawalsConfig['personPro']."%收取手续费；\n3、手续费从提现金额中扣除；\n4、企业账户提现免手续费。";
//             $withdrawalsInfo['withdrawalsstr']['content'][0] = "1、个人账户提现免手续费额度累积为".DePrice($withdrawalsConfig['personLimit'])."元；";
//             $withdrawalsInfo['withdrawalsstr']['content'][1] = "2、超过".DePrice($withdrawalsConfig['personLimit'])."元以上按提现金额".$withdrawalsConfig['personPro']."%收取手续费；";
//             $withdrawalsInfo['withdrawalsstr']['content'][2] = "3、服务费从提现金额中扣除；";
//             $withdrawalsInfo['withdrawalsstr']['content'][3] = "4、企业账户提现免手续费。";
        }
        //var_dump($withdrawalsInfo); exit();
       
        return $this->json(200, $withdrawalsInfo);
    }
    
    /**
     * [addAction 添加提现申请]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月6日 下午4:57:12
     * @return [type]    [description]
     */
    public function addAction() {     



        $paypwd = $this->getParam('paypwd');
        $bankId = $this->getParam('bankid');   
        //$bankId = 12; 
        $amount = $this->getParam('amount');
        //$amount = 1999999999;
        //$amount = 1;
        $userId = $this->userid;
        //var_dump($userId); exit();
        
        if(empty($paypwd) || empty($bankId) || empty($amount) || (!is_numeric($amount))){
            return $this->json(404);
        }
        
        if($this->Version("1.0.2")) {
            if(empty($this->params['accountType'])) {
                return $this->json(404);
            }
        }

        if($amount<100){
            return $this->json('404',[],'单笔金额不得低于100元');
        }

        $paypwd_error_limit = 3;
        $ActLimitOBJ = Model::new("Sys.ActLimit");
        //支付密码错误3次，就提示给用户
        $check_actlimit = $ActLimitOBJ->check("paypwd".$this->userid,$paypwd_error_limit);
        if(!$check_actlimit['check']){
            return $this->json("50000");
        }
        
        $check_result = PaypwdModel::checkpaypwd($userId,$paypwd);
        if($check_result['code'] != '200'){
            //return $this->json($check_result['code']);
            if($check_result['code']=='50002'){

                $ActLimitOBJ->update("paypwd".$this->userid,3600); //冻结一小时

                return $this->json($check_result['code'],[],"密码输入错误，您还可以输入".($paypwd_error_limit>$check_actlimit['limitcount']?$paypwd_error_limit-$check_actlimit['limitcount']:0)."次"); 

            }else{
                return $this->json($check_result['code']);
            }
        }
        
        $bank = Model::ins('CusBank')->getRow(['id' => $bankId, 'customerid' => $userId]);
        //var_dump($bank); exit();
        if(empty($bank)){
            return $this->json(20012);
        }
        
        $amount     = EnPrice($amount);
        //总余额
        $useramount = Model::new("Amount.Amount")->getUserWithdrawalsAmount($userId);  // 返回单位为分的金额  
        $cashamount = EnPrice($useramount['cashamount']+$useramount['comamount']); 
        if($this->Version("1.0.2")) {
            if($this->params['accountType'] == 1) {
                $cashamount = $useramount['comamount'];
            } else {
                $cashamount = $useramount['cashamount'];
            }
        }
        //var_dump($cashamount); exit();
        if($amount > $cashamount){
            return $this->json(30001);
        }
        //exit();
        //
        
        //事务处理
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();
        
        try{

            $cashamount = 0;
            $comamount  = 0;
            $poundage   = 0;

            if($useramount['comamount']>0)
                $comamount = $amount>$useramount['comamount']?$useramount['comamount']:$amount;

            if($amount>$useramount['comamount'] && $useramount['cashamount']>0 && $amount>$comamount)
                $cashamount = $amount-$comamount;
            
            if($this->Version("1.0.2")) {
                if($this->params['accountType'] == 1) {
                    $cashamount = 0;
                    $comamount = $amount;
                } else {
                    $poundage = Model::new("User.Amount")->getWithdrawalsPoundage(["customerid"=>$userId,"accountType"=>$this->params['accountType'],"type"=>1,"amount"=>$amount]);
                    
                    $cashamount = $amount;
                    $comamount = 0;
                }
            }

            //TODO 冻结提现现金
            //Model::ins("AmoAmount")->DedProfitAmount($userId,$amount);
            $CusWithdrawalsOBJ = Model::ins('CusWithdrawals');

            $orderno = $CusWithdrawalsOBJ->getOrderNo();
            $withdrawalsData = array(
                'customerid' => $userId,
                'bankid' => $bankId,
                'bank_type_name' => $bank['bank_type_name'],
                'account_name' => $bank['account_name'],
                'account_number' => $bank['account_number'],
                'branch' => $bank['branch'],
                'mobile' => $bank['mobile'],
                'amount' => $amount,
                'cashamount'=> $cashamount,
                'comamount'=> $comamount,
                'addtime' =>  date('Y-m-d H:i:s'),
                'orderno'=>$orderno,
            );
            
            if($this->Version("1.0.2")) {
                $withdrawalsData['accountType'] = $this->params['accountType'];
            }
            
            $id = $CusWithdrawalsOBJ->insert($withdrawalsData);

            $frozenOBJ = Model::new("Amount.Frozen");

            //资金冻结
            if($cashamount>0)
                $frozenOBJ->FrozenCashAmount([
                        "userid"=>$userId,
                        "amount"=>$cashamount,
                        "orderno"=>$orderno,
                    ]);

            //资金冻结
            if($comamount>0)
                $frozenOBJ->FrozenComAmount([
                        "userid"=>$userId,
                        "amount"=>$comamount,
                        "orderno"=>$orderno,
                    ]);
                
            // 提现申请
            Model::new("Sys.Mq")->add([
                "url" => "Order.OrderMsg.withdrawals",
                "param" => [
                    "orderno" => $orderno,
                ],
            ]);

            $amountModel->commit(); 
            
            if($this->Version("1.0.2")) {
                // 计算提现手续费
                return $this->json("200",["id"=>$orderno,"poundage"=>!empty($poundage) ? DePrice($poundage) : '0.00']);
            }
            
            return $this->json("200",['id' => $orderno]);

        } catch (\Exception $e) {
            //print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->delRedis($userId);
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            return ["code"=>"30004"];
        }
        
        return $this->json(400);
    }
    
    /**
     * [infoAction 获取提现信息]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月7日 下午7:28:30
     * @return [type]    [description]
     */
    public function infoAction()
    {
        $orderno = $this->getParam('id');
        $userId = $this->userid;
        //var_dump($userId); exit();
        
        if(empty($orderno)){
            return $this->json(404);
        }
        
        $info = Model::new('User.Withdrawals')->getInfoByOrderno($orderno, $userId);
        //var_dump($info); exit();
        if(empty($info)){
            return $this->json(404);
        }        
        
        return $this->json(200, $info);
    }
    
    /**
     * [listAction 获取提现列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月7日 下午9:00:32
     * @return [type]    [description]
     */
    public function listAction()
    {
//         $userId = $this->userid;
//         //var_dump($userId);
//         $list   = Model::new('User.Withdrawals')->getList($userId);

        $params['customerid'] = $this->userid;
        
        if($this->Version("1.0.2") || $this->Version("1.0.3")) {
            if(!empty($this->params['begintime'])) {
                // 不能选择未来时间
                $begintime = strtotime($this->params['begintime']);
                if($begintime > time()) {
                    // ??
                    return $this->json(27001);
                }
            }
            if(preg_match('#(\d+)年(\d+)月.*#u',$this->params['begintime'],$b)){
                $this->params['begintime'] = $b[1].'-'.$b[2];
            }
            $params['begintime'] = !empty($this->params['begintime']) ? $this->params['begintime'] : '';
        }
        
        $list = Model::new('User.Withdrawals')->getList($params);
        
        return $this->json(200, $list);
    }
}