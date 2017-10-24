<?php
/**
 * 支付回调
 */
namespace app\sale\controller\Payment;
use app\sale\ActionController;

use app\model\Order\OrderPayModel;
use \think\Config;
use app\lib\Model;

use app\model\Sys\LogModel;

use app\lib\Pay\Allinpay\Common;


class NoityController extends ActionController
{
    public function __construct(){
        parent::__construct();
        
    }

    
    /**
     * 支付宝app异步通知回调
     * @Author   zhuangqm
     * @DateTime 2017-02-24T11:14:21+0800
     * @return   [type]                   [description]
     */
    public function alipayAppNotifyUrlAction(){
        //print_r($_POST);
        $param = $_POST;

        unset($param['_apiname']);  

        if(empty($param)){
            echo "fail";
            exit;
        }   

        //添加日志--写mongodb
        @LogModel::paylog([
                    'paytype'=>"alipay_app",
                    "orderno"=>$param['out_trade_no'], //平台订单号
                    "trade_no"=>$param['trade_no'], //第三方订单号
                    "pay_amount"=>$param['buyer_pay_amount'], //支付金额
                    "param"=>$param,
        ]);

        /*
        

        $param['payment_code'] = "alipay_app";

        //订单号
        $param['order_sn'] = $param['out_trade_no'];
        //支付单号
        $param['pay_sn'] = $param['trade_no'];
        //第三方支付帐号
        $param['f_pay_num'] = $param['buyer_logon_id'];
        //支付金额
        $param['pay_price'] = $param['total_fee'];
        //支付时间
        $paytime = !empty($param['gmt_payment']) ? $param['gmt_payment'] : $param['gmt_create'];
        $param['pay_time'] = $paytime;
        */
        //回调处理
        //
        $orderno = $param['out_trade_no'];

        $ret = OrderPayModel::aliorderPay($param);
        


        if($ret['code'] == 200){
            echo "success";
        }else{
            echo "fail";
        }
        
        exit;
    } 

    /**
     * 微信支付结果回调地址
     * @Author   zhuangqm
     * @Datetime 2017-02-24T11:14:21+0800
     * @return   [type]                   [description]
     */
    public function weixinAppNotifyUrlAction(){

        $data = file_get_contents('php://input');

        $param = $this->xmlstr_to_array($data);

        unset($param['_apiname']);
        if(empty($param)){
            $ret_arr = array("return_code"=>"FAIL", "return_msg"=>"400");
            echo $this->array_to_xml($ret_arr);
            exit;
        }
        
        //添加日志
        LogModel::paylog([
                    'paytype'=>"weixin_app",
                    "orderno"=>$param['out_trade_no'], //平台订单号
                    "trade_no"=>$param['transaction_id'], //第三方订单号
                    "pay_amount"=>$param['total_fee']/100, //支付金额
                    "param"=>$param,
        ]);

        $payment_code = "weixin_app";   

        //$this->setLogFile($param, $payment_code);

        //订单号
        $param['order_sn'] = $param['out_trade_no'];
        $param['order_sn'] = explode("_",$param['order_sn']);
        $param['order_sn']=$param['order_sn'][0];

        //支付单号
        $param['pay_sn'] = $param['transaction_id'];
        //第三方支付帐号
        $param['f_pay_num'] = $param['openid'];
        //支付金额，由于银联的支付金额的单位是分，所以需要除于100
        $param['pay_price'] = $param['total_fee'] / 100;
        //支付时间
        $param['pay_time'] = $param['time_end'];

        //回调处理
        $orderno = $param['out_trade_no'];
        
        $ret = OrderPayModel::weixinorderPay($param);
        
        if($ret['code'] != 200){
            $ret_arr = array("return_code"=>"FAIL", "return_msg"=>$ret['code']);
        }else{
            $ret_arr = array("return_code"=>"SUCCESS", "return_msg"=>"OK");
        }
       
        echo $this->array_to_xml($ret_arr);
        exit;
    }

    /**
     * 微信h5分享的回调
     * @Author   zhuangqm
     * @Datetime 2016-11-22T13:41:57+0800
     * @return   [type]                   [description]
     */
    public function weixinWebNotifyUrlAction(){
        
        $data = file_get_contents('php://input');

        $param = $this->xmlstr_to_array($data);

        $pay_code = $param['out_trade_no'];

        //print_r($param);
        if(!empty($pay_code)){

            OrderPayModel::weixinwebPay($param);

        }

        
        exit;
    }


    /**
     * 阿里wap支付回调
     * @Author   zhuangqm
     * @Datetime 2016-12-26T11:42:58+0800
     * @return   [type]                   [description]
     */
    public function aliWebNotifyUrlAction(){
        //print_r($_POST);
        $param = $_POST;

        unset($param['_apiname']);  

        if(empty($param)){
            echo "fail";
            exit;
        }   
        
        $ret = OrderPayModel::aliwapPay($param);

        echo $ret;
        
        exit;
    }

    /**
     * 通联微信支付回调
     * @Author   zhuangqm
     * @DateTime 2017-06-23T09:57:03+0800
     * @return   [type]                   [description]
     */
    public function allinpayweixinAppNotifyUrlAction(){

        $param = $_POST;

        unset($param['_apiname']);
        if(empty($param)){
            echo "fail";
            exit;
        }

        $payment_code = "allinpay_weixin";   
        
        //添加日志
        LogModel::paylog([
                    'paytype'=>$payment_code,
                    "orderno"=>$param['cusorderid'], //平台订单号
                    "trade_no"=>$param['srctrxid'], //第三方订单号
                    "pay_amount"=>$param['trxamt'], //支付金额
                    "param"=>$param,
        ]);
        
        $ret = OrderPayModel::allinpayweixinorderPay($param);
        
        if($ret['code'] != 200){
            echo "fail";
        }else{
            echo "success";
        }
       
       
        exit;
    }

    /**
     * 通联支付宝支付回调
     * @Author   zhuangqm
     * @DateTime 2017-06-23T10:10:10+0800
     * @return   [type]                   [description]
     */
    public function allinpayaliAppNotifyUrlAction(){
        $param = $_POST;

        unset($param['_apiname']);
        if(empty($param)){
            echo "fail";
            exit;
        }

        $payment_code = "allinpay_ali";   
        
        //添加日志
        LogModel::paylog([
                    'paytype'=>$payment_code,
                    "orderno"=>$param['cusorderid'], //平台订单号
                    "trade_no"=>$param['srctrxid'], //第三方订单号
                    "pay_amount"=>$param['trxamt'], //支付金额
                    "param"=>$param,
        ]);
        
        $ret = OrderPayModel::allinpayaliorderPay($param);
        
        if($ret['code'] != 200){
            echo "fail";
        }else{
            echo "success";
        }
       
       
        exit;
    }

    /**
     * 通联快捷支付
     * @Author   zhuangqm
     * @DateTime 2017-06-23T10:10:46+0800
     * @return   [type]                   [description]
     */
    public function allinpayquickAppNotifyUrlAction(){
        $param = $_POST;

        //print_r($param);

        unset($param['_apiname']);

        if(empty($param)){
            echo "fail";
            exit;
        }

        $payment_code = "allinpay_quick";   
        
        //添加日志
        LogModel::paylog([
                    'paytype'=>$payment_code,
                    "orderno"=>$param['orderNo'], //平台订单号
                    "trade_no"=>$param['paymentOrderId'], //第三方订单号
                    "pay_amount"=>$param['payAmount'], //支付金额
                    "param"=>$param,
        ]);
        
        $param['payment_code'] = $payment_code;

        $ret = OrderPayModel::allinpayquickorderPay($param);
        
        if($ret['code'] != 200){
            echo "fail";
        }else{
            echo "success";
        }
       
       
        exit;
    }

    /**
     * 通联快捷支付 -- H5支付
     * @Author   zhuangqm
     * @DateTime 2017-06-23T10:10:46+0800
     * @return   [type]                   [description]
     */
    public function allinpayquickWebNotifyUrlAction(){
        $param = $_POST;

        unset($param['_apiname']);

        if(empty($param)){
            echo "fail";
            exit;
        }

        $payment_code = "allinpay_quick_web";   
        
        //添加日志
        LogModel::paylog([
                    'paytype'=>$payment_code,
                    "orderno"=>$param['orderNo'], //平台订单号
                    "trade_no"=>$param['paymentOrderId'], //第三方订单号
                    "pay_amount"=>$param['payAmount'], //支付金额
                    "param"=>$param,
        ]);
        $param['payment_code'] = $payment_code;
        $ret = OrderPayModel::allinpayquickorderPay($param);
        
        $Url = Config::get("webview");
        
        $mobileUrl = $Url['webviewUrl'];
        
        $orderNo = $param['orderNo'];
        $payOrder = Model::ins("PayOrder")->getRow(["orderno"=>$orderNo],"userid");
        
        if($ret["code"] != 200 && $ret['code'] != 6002) {
            // 支付失败
            $viewResult = OrderPayModel::allinpayquickOrderReturnView(["orderno"=>$orderNo,"paystatus"=>2,"customerid"=>$payOrder['userid']]);
        } else {
            // 支付成功
            $viewResult = OrderPayModel::allinpayquickOrderReturnView(["orderno"=>$orderNo,"paystatus"=>1,"customerid"=>$payOrder['userid']]);
        }
        
        header('Location:'.$mobileUrl.$viewResult);
        echo "<script type='text/javascript'>window.location.href='.$mobileUrl.$viewResult.';</script>";
        exit;
    }


    /*
        将数组转换成xml格式
    */
    protected function array_to_xml($array){
        
        if(!is_array($array))
            return '';

        $xml = "<xml>";

        foreach($array as $key=>$val){

            if(is_numeric($val)){
         
                $xml.="<".$key.">".$val."</".$key.">";
            }else{

                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }

        }
        $xml .= "</xml>";
        return $xml;

    }
    /**
    xml转成数组
    */
    protected function xmlstr_to_array($xmlstr) {
        $doc = new \DOMDocument();
        $doc->loadXML($xmlstr);
        
        return $this->domnode_to_array($doc->documentElement);
    }
    
    protected function domnode_to_array($node) {
        $output = array();
        
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domnode_to_array($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                        //print_r($output);
                        //array_push($output[$t],$v);
                    }elseif($v) {

                        $output = (string) $v;
                    }
                }
                
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }
}
