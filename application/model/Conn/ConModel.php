<?php
namespace app\model\Con;

use think\Config;

use app\lib\Model;

use app\lib\Log;

class ConModel
{
    
    /**
     * 增加con
     * @Author   zhuangqm
     * @Datetime 2017-10-08T00:09:40+0800
     * @param    [type]                   $param [description]
     */
    public function addCon($param){

        $usertype = !empty($param['usertype'])?$param['usertype']:1;

        $result = Model::new("Amount.Amount")->add_conamount([
                "userid"=>$param['userid'],
                "amount"=>$param['amount'],
                "usertype"=>$usertype,
                "tablename"=>"AmoFlowCon",
                "flowtype"=>$param['flowtype'],
                "flowid"=>$param['flowid'],
                "orderno"=>$param['orderno'],
                "role"=>$usertype,
                "fromuserid"=>$param['fromuserid'],
            ]);

        return $result;
    }

    /**
     * 消费金额扣减
     * @Author   zhuangqm
     * @Datetime 2017-10-08T00:29:34+0800
     * @param    [type]                   $param [description]
     */
    public function DedCon($param){

        $usertype = !empty($param['usertype'])?$param['usertype']:1;

        $result = Model::new("Amount.Amount")->pay_conamount([
                "userid"=>$param['userid'],
                "amount"=>$param['amount'],
                "usertype"=>$usertype,
                "tablename"=>"AmoFlowCon",
                "flowid"=>$param['flowid'],
                "orderno"=>$param['orderno'],
                "role"=>$usertype,
                "fromuserid"=>$param['fromuserid'],
            ]);

        return $result;
    }

    public function getNo(){
        return "TRA".date("YmdHis").rand(100000,999999);
    }

    /**
     * 转移钻石
     * @Author   zhuangqm
     * @Datetime 2017-10-08T00:37:33+0800
     * @param    [type]                   $param [
     *                                           fromuserid 转账用户ID
     *                                           touserid 接收用户ID
     *                                           amount 转账数量 单位元
     *                                     ]
     * @return   [type]                          [description]
     */
    public function transferCon($param){

        // 一个用户转给另一用户
        
        $amount = EnPrice($param['amount']);
        
        $orderno = $this->getNo();
        
        $flowid = Model::new("Amount.Flow")->getFlowId($orderno);

        // 获取用户余额
        $user_conamount = Model::new("Amount.Amount")->getUserAmount($param['fromuserid'],"conamount");

        if($user_conamount<$amount)
            return ["code"=>"1003"]; // 余额不足

        $cash = 0;

        // 事务
        $AmoAmount       = Model::ins("AmoAmount");

        $AmoAmount->startTrans();

        try{
        
            // 先扣减
            $result = $this->DedCon([
                    "userid"=>$param['fromuserid'],
                    "amount"=>$amount,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "flowtype"=>11,
                ]);

            // 增加钻石
            if($result['code']=='200'){
                // 增加砖石数量
                $result = $this->addCon([
                        "userid"=>$param['touserid'],
                        "amount"=>$amount,
                        "flowid"=>$flowid,
                        "orderno"=>$orderno,
                        "flowtype"=>12,
                        "fromuserid"=>$param['fromuserid'],
                    ]);

                // 分润
                if($result['code']=='200'){

                    // 计算钻石的金额
                    $con_config = Config::get("conn");
                    // 计算回现金金额
                    $cash = $amount/$con_config['con_price'];

                    Model::new("Amount.Profit")->add_con_profit([
                        "userid"=>$param['touserid'],
                        "amount"=>$cash,
                        "orderno"=>$orderno,
                        "flowid"=>$flowid,
                    ]);

                }
            }

            //生成订单记录
            Model::ins("ConTransfer")->insert([
                "orderno"=>$orderno,
                "fromuserid"=>$param['fromuserid'],
                "touserid"=>$param['touserid'],
                "amount"=>$cash,
                "addtime"=>date("Y-m-d H:i:s"),
                "status"=>1,
            ]);

            // 提交事务
            $AmoAmount->commit(); 

        } catch (\Exception $e) {
            $AmoAmount->rollback();
            Log::add($e,__METHOD__);
        }

        return $result;
    }
}
