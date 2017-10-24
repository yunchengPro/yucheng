<?php
namespace app\api\controller\User;

use app\api\ActionController;

use app\lib\Model;

use think\Config;

use app\lib\Log;

class RechargeController extends ActionController {
    
    public function __construct() {
        parent::__construct();
    }
    
    // 发起充值请求
    public function addrechargeAction() {
        $amount = $this->params['amount'];

        $orderno = Model::ins("CusRecharge")->getOrderNo();
        Model::ins("CusRecharge")->insert([
                "orderno"=>$orderno,
                "customerid"=>$this->userid,
                "amount"=>Enprice($amount),
                "addtime"=>date("Y-m-d H:i:s"),
            ]);

        return $this->json("200",['orderno'=>$orderno]);
    }

    // 牛豆充值
    public function rechargebullAction(){
        $recharge_code = $this->params['recharge_code'];
        $checkcode     = $this->params['checkcode'];

        if(empty($recharge_code) || empty($checkcode)){
            return $this->json("404");
        }

        $rechargebull_error_limit = 5;
        $ActLimitOBJ = Model::new("Sys.ActLimit");
        //支付密码错误3次，就提示给用户
        $check_actlimit = $ActLimitOBJ->check("rechargebull".$this->userid,$rechargebull_error_limit);
        if(!$check_actlimit['check']){
            return $this->json("60000");
        }

        $app_config = Config::get("key");

        $this_code = md5($recharge_code.$app_config['app_key']);


        if($this_code==$checkcode){

            $cusbull = Model::ins("CusRechargeBull");
            $bullcodecode = Model::ins("BullCodeCode");

            //判断redis是否存在
            $bullcodeRedis = Model::Redis("BullCode");
            if($bullcodeRedis->exists($this_code)){

                $ActLimitOBJ->update("rechargebull".$this->userid,900); //冻结15分

                return $this->json("60001",[
                    "amount"=>0,
                ],"输入错误，您还可以输入".($rechargebull_error_limit>$check_actlimit['limitcount']?$rechargebull_error_limit-$check_actlimit['limitcount']:0)."次");
            }

            //判断该牛豆号，是否存在
            $bullitem = $bullcodecode->getRow(["bull_code"=>$this_code],"*");

            if(!empty($bullitem)){

                //判断该号是否已被使用
                $checkrow = $cusbull->getRow(["bull_code"=>$this_code],"count(*) as count");

                if($checkrow["count"]==0){

                    //设置到redis中
                    $bullcodeRedis->set($this_code,'1',3600);

                    $amountModel = Model::ins("AmoAmount");
                    $amountModel->startTrans();
                    try{

                        $orderno = $cusbull->getOrderNo();
                        // 添加兑换记录
                        $cusbull->insert([
                                "bull_code"=>$bullitem['bull_code'],
                                "addtime"=>date("Y-m-d H:i:s"),
                                "amount"=>$bullitem['amount'],
                                "customerid"=>$this->userid,
                                "orderno"=>$orderno,
                            ]);


                        $flowid = Model::new("Amount.Flow")->getFlowId($orderno);
                        //给用户添加牛豆
                        $result = Model::new("Amount.Amount")->add_bullamount([
                            "userid"=>$this->userid,
                            "amount"=>$bullitem['amount'],
                            "usertype"=>"2",
                            "tablename"=>"AmoFlowCusBull",
                            "flowtype"=>32,
                            "flowid"=>$flowid,
                            "orderno"=>$orderno,
                        ]);

                        //提交事务
                        $amountModel->commit();   

                        return $this->json("200",[
                                "amount"=>DePrice($bullitem['amount']),
                            ]);

                    } catch (\Exception $e) {
                        //print_r($e);
                        // 错误日志
                        // 回滚事务
                        
                        $amountModel->rollback();

                        $bullcodeRedis->del($this_code);

                        Log::add($e,__METHOD__);

                        return $this->json("404",[
                            "amount"=>0,
                        ]);
                    }

                }else{

                    $ActLimitOBJ->update("rechargebull".$this->userid,900); //冻结15分

                    return $this->json("60001",[
                        "amount"=>0,
                    ],"输入错误，您还可以输入".($rechargebull_error_limit>$check_actlimit['limitcount']?$rechargebull_error_limit-$check_actlimit['limitcount']:0)."次");
                }

            }else{
                $ActLimitOBJ->update("rechargebull".$this->userid,900); //冻结15分

                return $this->json("60001",[
                    "amount"=>0,
                ],"输入错误，您还可以输入".($rechargebull_error_limit>$check_actlimit['limitcount']?$rechargebull_error_limit-$check_actlimit['limitcount']:0)."次");
            }

            return $this->json("200",[
                            "amount"=>0,
                        ]);
        }else{
            return $this->json("404",[
                            "amount"=>0,
                        ]);
        }
    }
}