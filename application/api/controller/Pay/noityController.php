<?php
/**
 * 支付回调 ----已取消
 */
namespace app\api\controller\Pay;
use app\api\ActionController;

use app\api\model\OrderModel;

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

        //添加日志
        //ModelLog::addLog(array_merge($param,['paytype'=>"alipay_app"]));
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

        $ret = OrderModel::aliorderPay($param);

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

        //添加日志
        //ModelLog::addLog(array_merge($param,['paytype'=>"weixin_app"]));

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
        $ret = OrderModel::weixinorderPay($param);

        if($ret['code'] != 200){
            $ret_arr = array("return_code"=>"FAIL", "return_msg"=>$ret['code']);
        }else{
            $ret_arr = array("return_code"=>"SUCCESS", "return_msg"=>"OK");
        }
        //print_r($ret_arr);
        echo $this->array_to_xml($ret_arr);
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
        $doc = new DOMDocument();
        $doc->loadXML($xmlstr);
        echo "--2-";
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
