<?php
/**
 * 支付入口
 */
namespace app\api\controller\Pay;
use app\api\ActionController;

use app\lib\Model;

use app\lib\Pay\Weixin\WeixinApp;
use app\lib\Pay\Ali\alipay_app;

use app\lib\Pay\WeixinPay\WeixinPay;

use app\lib\Pay\Allinpay\Weixin as Allinpay_weixin;
use app\lib\Pay\Allinpay\Ali as Allinpay_ali;
use app\lib\Pay\Allinpay\Quick as Allinpay_quick;
use app\lib\Pay\AliWap\AliWap;

use app\model\User\PaypwdModel;
use app\model\Order\OrderPayModel;

use think\Config;

class PayController extends ActionController
{
    public function __construct(){
        parent::__construct();
        
    }
    
    //订单支付
    public function payAction(){
        /*
        $orderno = $this->params['orderno'];

        $orderModel = Model::ins("OrdOrder");

        // ------ 先充值再扣款，扣款不成功，钱还是在钱包里面
        
        //获取订单信息
        $order_row = $orderModel->getByNo($orderno,"id,orderstatus,productamount,bullamount");

        if(empty($order_row))
            return $this->json("40002");

        //获取订单状态 判断订单是否已支付
        if($order_row['orderstatus']>0)
            return $this->json("40001"); //订单已支付


        $amountModel = Model::ins("AmoAmount");

        $amountModel->startTrans();
        
        try{
            
            //执行订单支付流程
            $result = Model::new("Amount.Pay")->pay([
                    "userid"=>$this->userid,
                    "cashamount"=>$order_row['productamount'],
                    "bullamount"=>$order_row['bullamount'],
                    "orderno"=>$orderno,
                ]);
            
            //判断是否已扣款
            if($result['code']=='200'){

                //进行分润
                Model::new("Amount.Profit")->profit([
                        "userid"=>$this->userid,
                        "orderno"=>$orderno,
                    ]);

                //设置订单状态
                $orderModel->update(["orderstatus"=>1],["orderno"=>$orderno]);
               
            }else{

                return $this->json($result['code']);
            }

            // 提交事务
            $amountModel->commit();   
            
            return $this->json("200");
        } catch (\Exception $e) {

            // 回滚事务
            $amountModel->rollback();
            
            return $this->json("30004");
        }
        */
    }

    /**
     * 支付请求
     * @Author   zhuangqm
     * @DateTime 2017-03-20T14:30:44+0800
     * @return   [type]                   [description]
     */
    public function requestAction(){
        $pay_type = $this->params['pay_type']; //支付方式
        $orderno = $this->params['orderno']; //订单ID

        if($pay_type!='' && !in_array($pay_type, ['weixin','ali','allinpay_weixin','allinpay_ali','allinpay_quick']))
            return $this->json("404");

        if(empty($orderno))
            return $this->json("404");

        if($pay_type=='weixin')
            $pay_type='allinpay_weixin';

        if(substr($orderno,0,6)=='NNHSTO' || substr($orderno,0,6)=='NNHSTB'){
            // 实体店订单
            $orderModel = Model::ins("StoPayFlow");
            $order = $orderModel->getRow(['pay_code'=>$orderno],"*");

            $order['orderno'] = $order['pay_code'];

            // 判断是否使用了奖励金
            $bonus = Model::ins("BonusOrder")->getRow(["orderno"=>$orderno],"id,bonusamount");
            if(!empty($bonus)){
                $order['amount'] = $order['amount']-$bonus['bonusamount'];
            }

        }else if(substr($orderno,0,5)=='NNHRE'){
            // 充值
            $orderModel = Model::ins("CusRecharge");
            $order = $orderModel->getRow(['orderno'=>$orderno],"*");

            //订单已支付成功
            $order['status'] = $order['pay_status'];
            

        }else if(substr($orderno,0,5)=='NNHNR'){
            // 牛人申请
            $orderModel = Model::ins("RoleApplyLog");
            $order = $orderModel->getRow(['orderno'=>$orderno],"id,orderno,amount,pay_status,addtime");

            //订单已支付成功
            $order['status'] = $order['pay_status'];
            
        } else if(substr($orderno,0,5)=='NNHND') {
            // 牛达人申请
            $orderModel = Model::ins("RoleApplyLog");
            $order = $orderModel->getRow(['orderno'=>$orderno],"id,orderno,amount,pay_status,addtime");
            
            //订单已支付成功
            $order['status'] = $order['pay_status'];
            
        }else if(substr($orderno,0,5)=='NNHNC'){
            // 牛创客申请
            $orderModel = Model::ins("RoleApplyLog");
            $order = $orderModel->getRow(['orderno'=>$orderno],"id,orderno,amount,pay_status,addtime");

            //订单已支付成功
            $order['status'] = $order['pay_status'];
        

        }else if(substr($orderno,0,6)=='NNHTNC'){
            // 牛创客推荐
            $orderModel = Model::ins("RoleRecoEn");
            $order = $orderModel->getRow(['orderno'=>$orderno],"id,orderno,amount,pay_status,addtime");

            //订单已支付成功
            $order['status'] = $order['pay_status'];

        }else if(substr($orderno,0,6)=='NNHTNR'){
            // 牛人推荐  
            $orderModel = Model::ins("RoleRecoOr");
            $order = $orderModel->getRow(['orderno'=>$orderno],"id,orderno,amount,pay_status,addtime");

            //订单已支付成功
            $order['status'] = $order['pay_status'];
        }else if(substr($orderno,0,6)=='NNHTND') {
            // 牛达人推荐
            $orderModel = Model::ins("RoleRecoTalent");
            $order = $orderModel->getRow(['orderno'=>$orderno],"id,orderno,amount,pay_status,addtime");
            
            //订单已支付成功
            $order['status'] = $order['pay_status'];
        }else if(substr($orderno,0,6)=='NNHMOR'){
            // 多订单合并支付
            $ordermoreModel = Model::ins("OrdOrderMore");
            $order = $orderModel->getByNo($orderno,"*");

            //订单已支付成功
            $order['status'] = $order['orderstatus'];
            $order['amount'] = $order['totalamount'];
           
        }else if(substr($orderno,0,6)=='NNHOTO'){
            // 外卖订单 
            $orderModel = Model::ins("StoOrder");
            $order = $orderModel->getRow(['orderno'=>$orderno],"id,orderno,totalamount,orderstatus,addtime");

            //订单已支付成功
            $order['status'] = $order['orderstatus'];
            $order['amount'] = $order['totalamount'];

            // 判断是否使用了奖励金
            $bonus = Model::ins("BonusOrder")->getRow(["orderno"=>$orderno],"id,bonusamount");
            if(!empty($bonus)){
                $order['amount'] = $order['amount']-$bonus['bonusamount'];
            }
            
        }else{

            // 商城
            $orderModel = Model::ins("OrdOrder");
            $order = $orderModel->getByNo($orderno,"*");

            //订单已支付成功
            $order['status'] = $order['orderstatus'];
            $order['amount'] = $order['totalamount'];
        }

        //订单不存在
        if(empty($order)){
            return $this->json("6001");
        }

        //订单已支付成功
        if($order['status']>0)
            return $this->json("6002");

        $payOBJ = null;
        //微信支付
        if($pay_type=='weixin'){

            //添加支付请求记录
            $result = Model::new("Pay.Pay")->addPayOrder([
                    "orderno"=>$order['orderno'],
                    "amount"=>$order['amount'],
                    "pay_type"=>$pay_type,
                    "userid"=>$this->userid,
                ]);

            if($result['code']!='200')
                return $this->json($result['code']);
            
            $payOBJ = new WeixinApp([
                    "orderno"=>$order['orderno'],
                    "pay_price"=>DePrice($order['amount']),
                ]);
            $data = $payOBJ->getPayOrder();


            if(!empty($data))
                return $this->json("200",$data);
            else
                return $this->json("6003");
        }

        //支付宝支付
        if($pay_type=='ali'){

            //添加支付请求记录
            $result = Model::new("Pay.Pay")->addPayOrder([
                    "orderno"=>$order['orderno'],
                    "amount"=>$order['amount'],
                    "pay_type"=>$pay_type,
                    "userid"=>$this->userid,
                ]);

            if($result['code']!='200')
                return $this->json($result['code']);

            $payOBJ = new alipay_app([
                    "orderno"=>$order['orderno'],
                    "pay_price"=>DePrice($order['amount']),
                ]);

            $data = $payOBJ->get_payurl();

            if(!empty($data))
                return $this->json("200",array("param"=>$data));
            else
                return $this->json("6003");
        }

        // 通联--微信支付
        if($pay_type == 'allinpay_weixin'){

            //添加支付请求记录
            $result = Model::new("Pay.Pay")->addPayOrder([
                    "orderno"=>$order['orderno'],
                    "amount"=>$order['amount'],
                    "pay_type"=>$pay_type,
                    "userid"=>$this->userid,
                ]);

            if($result['code']!='200')
                return $this->json($result['code']);

            $payOBJ = new Allinpay_weixin();

            $data = $payOBJ->getPayOrder([
                    "userid"=>$this->userid,
                    "orderno"=>$order['orderno'],
                    "pay_price"=>$order['amount'],
                ]);

            
            /*print_r($data);
            echo "====================".$data['retcode'];*/
            
            if($data['retcode']=='SUCCESS'){
                if(!empty($data['weixinstr'])){
                    $data['weixinstr'] = json_decode($data['weixinstr'],true);
                    return $this->json("200",$data['weixinstr']);
                }
                else{
                    return $this->json("6003",[],$data['errmsg']);
                }
            }
            else{
                return $this->json("6003");
            }
        }

        // 通联--支付宝支付
        if($pay_type == 'allinpay_ali'){

            //添加支付请求记录
            $result = Model::new("Pay.Pay")->addPayOrder([
                    "orderno"=>$order['orderno'],
                    "amount"=>$order['amount'],
                    "pay_type"=>$pay_type,
                    "userid"=>$this->userid,
                ]);

            if($result['code']!='200')
                return $this->json($result['code']);

            $payOBJ = new Allinpay_ali();

            $data = $payOBJ->getPayOrder([
                    "userid"=>$this->userid,
                    "orderno"=>$order['orderno'],
                    "pay_price"=>$order['amount'],
                ]);

            /*
            // 不需要做阿里web跳转
            $ali_config = Config::get("pay_ali");

            $AliWap = new AliWap();

            $AliWap->addorder([
                    "WIDtotal_amount"=>DePrice($order['amount']), //支付金额
                    "WIDout_trade_no"=>$order['orderno'], //订单号
                    "WIDsubject"=>"交易订单支付",
                    "WIDbody"=>"交易订单支付",
                    "return_url"=>$ali_config['return_order_url'].$order['orderno'],
                    "passback_params"=>urlencode(json_encode(["orderno"=>$order['orderno']])),
                    "noityUrl"=>$ali_config['web_noityUrl'],
                ]);
            */
            
            if($data['retcode']=='SUCCESS')
                return $this->json("200",['param'=>$data]);
            else
                return $this->json("6003");
        }

        // 通联--快捷支付
        if($pay_type == 'allinpay_quick'){

            //添加支付请求记录
            $result = Model::new("Pay.Pay")->addPayOrder([
                    "orderno"=>$order['orderno'],
                    "amount"=>$order['amount'],
                    "pay_type"=>$pay_type,
                    "userid"=>$this->userid,
                ]);
            if($result['code']!='200')
                return $this->json($result['code']);

            $payOBJ = new Allinpay_quick();

            $data = $payOBJ->getPayOrder([
                    "userid"=>$this->userid,
                    "orderno"=>$order['orderno'],
                    "pay_price"=>$order['amount'],
                    "order_time"=>$order['addtime'],
                ]);

            return $this->json("200",['param'=>$data]);

        }
    }

    /**
     * 支付请求--实体店
     * @Author   zhuangqm
     * @DateTime 2017-03-20T14:30:44+0800
     * @return   [type]                   [description]
     * @2016-04-05 ISir修改 报错 orderno 改成pay_code
     */
    public function storequestAction(){
        
        return $this->requestAction();
    }

    /**
     * 充值支付请求
     * @Author   zhuangqm
     * @DateTime 2017-03-20T14:30:44+0800
     * @return   [type]                   [description]
     */
    public function rerequestAction(){

        return $this->requestAction();
    }

    //牛人申请
    public function nrrequestAction(){

        return $this->requestAction();
    }

    //牛创客申请
    public function ncrequestAction(){

        return $this->requestAction();
    }

    //牛创客推荐
    public function tncrequestAction(){

        return $this->requestAction();
    }

    //牛人推荐
    public function tnrrequestAction(){

        return $this->requestAction();
    }
    
    // 牛达人申请
    public function ndrequestAction(){
    
        return $this->requestAction();
    }
    
    // 牛达人推荐
    public function tndrequestAction(){
    
        return $this->requestAction();
    }

    /**
     * 支付结果
     * @Author   zhuangqm
     * @DateTime 2017-04-05T15:44:07+0800
     * @return   [type]                   [description]
     */
    public function paystatusAction(){
        $orderno = $this->params['orderno'];

        $result = Model::new("Pay.Pay")->getPayOrder([
                    "orderno"=>$orderno,
                ]);

        return $this->json($result['code'],$result['data']);
    }


    /**
     * 判断用户余额是否足够
     * @Author   zhuangqm
     * @DateTime 2017-03-22T15:37:54+0800
     * @return   [type]                   [description]
     */
    public function checkbalanceAction(){

        $orderno        = $this->params['orderno'];

        if(empty($orderno))
            return $this->json("404");

        $result = Model::new("Pay.Pay")->checkbalance([
                "orderno"=>$orderno,
                "userid"=>$this->userid,
            ]);

        return $this->json($result['code'],$result['data']);

        /*
        
        // 转移到接口里面
        if(substr($orderno,0,6)=='NNHSTO'){
            $order = Model::ins("StoPayFlow")->getRow(['pay_code'=>$orderno],"*");
        }else if(substr($orderno,0,5)=='NNHRE'){
            // 充值
        }else if(substr($orderno,0,5)=='NNHNR'){
            // 牛人申请
            $order = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");
        }else if(substr($orderno,0,5)=='NNHND'){
            $order = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");
        }else if(substr($orderno,0,5)=='NNHNC'){
            // 牛创客申请
            $order = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");
        }else if(substr($orderno,0,6)=='NNHTNC'){
            // 牛创客推荐
            $order = Model::ins("RoleRecoEn")->getRow(['orderno'=>$orderno],"*");
            $order['customerid'] = $order['instroducerid'];
        }else if(substr($orderno,0,6)=='NNHTND'){
            // 牛创客推荐
            $order = Model::ins("RoleRecoTalent")->getRow(['orderno'=>$orderno],"*");
            $order['customerid'] = $order['instroducerid'];
        }else if(substr($orderno,0,6)=='NNHTNR'){
            // 牛人推荐  
            $order = Model::ins("RoleRecoOr")->getRow(['orderno'=>$orderno],"*");
            $order['customerid'] = $order['instroducerid'];
        }else if(substr($orderno,0,6)=='NNHMOR'){
            // 多订单合并支付  
            $order = Model::ins("OrdOrderMore")->getByNo($orderno,"id,customerid,bullamount,totalamount");
        }else if(substr($orderno,0,6)=='NNHOTO'){
            // 外卖订单
            $order = Model::ins("StoOrder")->getRow(["orderno"=>$orderno],"id,customerid,totalamount");
        }else{
            // 商城
            $order = Model::ins("OrdOrder")->getByNo($orderno,"id,customerid,bullamount,totalamount");
        }




        if(!empty($order)){
            if($order['customerid'] == $this->userid){
                
                if(substr($orderno,0,6)=='NNHSTO'){
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "profitamount"=>$order['amount'],
                    ]);
                }else if(substr($orderno,0,5)=='NNHRE'){
                    // 充值
                }else if(substr($orderno,0,5)=='NNHNR'){
                    // 牛人申请
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "cashamount"=>$order['amount'],
                    ]);
                }else if(substr($orderno,0,5)=='NNHND'){
                    // 牛达人申请
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "cashamount"=>$order['amount'],
                    ]);
                }else if(substr($orderno,0,5)=='NNHNC'){
                    // 牛创客申请
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "cashamount"=>$order['amount'],
                    ]);
                }else if(substr($orderno,0,6)=='NNHTNC'){
                    // 牛创客推荐
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "cashamount"=>$order['amount'],
                    ]);
                }else if(substr($orderno,0,6)=='NNHTNR'){
                    // 牛人推荐  
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "cashamount"=>$order['amount'],
                    ]);
                }else if(substr($orderno,0,6)=='NNHTND'){
                    // 牛达人推荐
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "cashamount"=>$order['amount'],
                    ]);
                }else if(substr($orderno,0,6)=='NNHMOR'){
                    // 多订单合并支付  
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "cashamount"=>$order['totalamount'],
                        "bullamount"=>$order['bullamount'],
                    ]);
                }else if(substr($orderno,0,6)=='NNHOTO'){
                    // 外卖订单
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "profitamount"=>$order['totalamount'],
                    ]);
                }else{
                    // 商城
                    $result = Model::new("Amount.Amount")->checkamountbalance([
                        "userid"=>$this->userid,
                        "cashamount"=>$order['totalamount'],
                        "bullamount"=>$order['bullamount'],
                    ]);
                }

                //判断是否设置了支付密码
                $check_result = PaypwdModel::issetpaypwd(["userid"=>$this->userid]);
                

                return $this->json("200",[
                        "balance"=>$result,
                        "issetpaypwd"=>$check_result['code']=='200'?1:0,
                    ]);
            }else{
                return $this->json("1001");
            }
        }else{
            return $this->json("40002");
        }*/

    }


    /**
     * 余额支付
     * @Author   zhuangqm
     * @DateTime 2017-03-20T20:26:21+0800
     * @return   [type]                   [description]
     */
    public function balancepayAction(){
        $paypwd         = $this->params['paypwd'];
        $orderno        = $this->params['orderno'];

        if(empty($paypwd) || empty($orderno))
            return $this->json("404");
        
        $result = Model::new("Pay.Pay")->balancepay([
            "orderno"=>$orderno,
            "paypwd"=>$paypwd,
            "userid"=>$this->userid,
        ]);
        
        return $this->json($result['code'],$result['data'],$result['msg']);

//         $paypwd_error_limit = 3;
//         $ActLimitOBJ = Model::new("Sys.ActLimit");
//         //支付密码错误3次，就提示给用户
//         $check_actlimit = $ActLimitOBJ->check("paypwd".$this->userid,$paypwd_error_limit);
//         if(!$check_actlimit['check']){
//             return $this->json("50000");
//         }

//         //判断支付密码
//         $check_result = PaypwdModel::checkpaypwd($this->userid,$paypwd);

//         if($check_result['code']=='200'){
//             //校验成功
            
//             $flowid = Model::new("Amount.Flow")->getFlowId($orderno);

            
//             if(substr($orderno,0,6)=='NNHSTO'){
//                 $result = OrderPayModel::orderpay_sto([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);

//             }else if(substr($orderno,0,5)=='NNHRE'){
//                 // 充值
//                 $result = OrderPayModel::orderpay_re([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);
                

//             }else if(substr($orderno,0,5)=='NNHNR'){
//                 // 牛人申请
//                 $result = OrderPayModel::orderpay_nr([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);
                
//             }else if(substr($orderno,0,5)=='NNHND'){
//                 // 牛达人申请
//                 $result = OrderPayModel::orderpay_nd([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);
//             }else if(substr($orderno,0,5)=='NNHNC'){
//                 // 牛创客申请
//                 $result = OrderPayModel::orderpay_nc([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);
            

//             }else if(substr($orderno,0,6)=='NNHTNC'){
//                 // 牛创客推荐
//                 $result = OrderPayModel::orderpay_tnc([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);

//             }else if(substr($orderno,0,6)=='NNHTNR'){
//                 // 牛人推荐  
//                 $result = OrderPayModel::orderpay_tnr([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);
//             }else if(substr($orderno,0,6)=='NNHTND'){
//                 // 牛达人推荐
//                 $result = OrderPayModel::orderpay_tnd([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);
//             }else if(substr($orderno,0,6)=='NNHMOR'){
//                 // 多订单合并操作  
//                 $result = OrderPayModel::orderpay_more([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);
               
//             }else if(substr($orderno,0,6)=='NNHOTO'){
//                 // 多订单合并操作  
//                 $result = OrderPayModel::orderpay_oto([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);
               
//             }else{

//                 // 商城
//                 $result = OrderPayModel::orderpay([
//                     "orderno"=>$orderno,
//                     "userid"=>$this->userid,
//                     "flowid"=>$flowid,
//                     "balancepay"=>1,
//                 ]);
//             }
//             if($result['code']!='200')
//                 return $this->json($result['code']);


//             return $this->json("200");

//         }else{
//             if($check_result['code']=='50002'){

//                 $ActLimitOBJ->update("paypwd".$this->userid,3600); //冻结一小时

//                 return $this->json($check_result['code'],[],"密码输入错误，您还可以输入".($paypwd_error_limit>$check_actlimit['limitcount']?$paypwd_error_limit-$check_actlimit['limitcount']:0)."次"); 

//             }else{
//                 return $this->json($check_result['code']);
//             }
//         }
    }
}
