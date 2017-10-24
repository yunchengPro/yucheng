<?php

namespace app\lib\Pay\Ali;

use app\lib\Pay\Ali\lib\AlipayNotify;

use \think\Config;
use AopClient;
use AlipaySignatureRsaSignRequest;
use AlipayDataDataserviceBillDownloadurlQueryRequest;

/* *
 * 功能：移动支付接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */

//require_once(RPCPAYMENT_ROOT . "Library/payment/alipay_app/lib/alipay_notify.class.php");
//
include("../application/lib/Pay/Ali/sdk/AopSdk.php");

class alipay_app
{
    protected $alipay_config = array();
    protected $param;

    /**
     * [__construct description]
     * @Author   zhuangqm
     * @Datetime 2016-11-08T16:44:03+0800
     * @param    array                    $param 
     *                                      orderid
                                            pay_price
     */ 
    public function __construct($param=array()){

        $this->param = $param;

        $this->alipay_config = Config::get("pay_ali");
    }

     /**
     * 新的接口方式
     */
    public function get_payurl(){
        /*
        $biz_content = array(
                        "body"=>"nnh_order",
                        "subject"=>"nnh_order",
                        "out_trade_no"=>$this->param['orderno'],
                        "timeout_express"=>"30m",
                        "total_amount"=>$this->param['pay_price'],
                        "product_code"=>$this->alipay_config['product_code'],
                    );
        

        $parameter = array(
                "app_id"=>$this->alipay_config['app_id'],
                "method"=>$this->alipay_config['method'],
                //"format"=>$this->alipay_config['format'],
                "charset"=>$this->alipay_config['charset'],
                "sign_type"=>$this->alipay_config['sign_type'],
                "timestamp"=>date("Y-m-d H:i:s"),
                "version"=>$this->alipay_config['version'],
                "notify_url"=>$this->alipay_config['notify_url'],
                "biz_content"=>json_encode($biz_content,JSON_UNESCAPED_UNICODE),
            );
        //print_r($parameter);
        //$para_filter = $this->paraFilter($parameter);
        $para_sort = $this->argSort($parameter);

        //$prestr = $this->createLinkstring($para_sort);

        $sign = $this->rsaSign_new($parameter);

        //$sign = urlencode($sign);

        //$ret = $prestr . "&sign=".$sign;
        $ret = $this->arg($para_sort);
        echo "================".$ret."=========================";
        echo "------------".$sign."----------------------";
        $ret.="&sign=".urlencode($sign);
        */

        $aop = new AopClient;
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = $this->alipay_config['app_id'];
        $aop->rsaPrivateKey = $this->getKey($this->alipay_config['private_key_path']);
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = $this->getKey($this->alipay_config['ali_public_key_path']);
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new \AlipayTradeAppPayRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $bizcontent = "{\"body\":\"交易订单\","
                        . "\"subject\": \"交易订单\","
                        . "\"out_trade_no\": \"".$this->param['orderno']."\","
                        . "\"timeout_express\": \"30m\","
                        . "\"total_amount\": \"".$this->param['pay_price']."\","
                        . "\"product_code\":\"".$this->alipay_config['product_code']."\""
                        . "}";

        $request->setNotifyUrl($this->alipay_config['notify_url']);
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
        //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        $ret = $response;//就是orderString 可以直接给客户端请求，无需再做处理。

        return $ret;

    }

    /**
     * 下载对账单
     * @Author   zhuangqm
     * @DateTime 2017-07-04T11:17:59+0800
     * @return   [type]                   [description]
     */
    public function downbill($param){

        $bill_date = $param['bill_date'];

        if(!empty($bill_date)){

            $aop = new AopClient ();
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
            $aop->appId = $this->alipay_config['app_id'];
            $aop->rsaPrivateKey = $this->getKey($this->alipay_config['private_key_path']);
            $aop->alipayrsaPublicKey = $this->getKey($this->alipay_config['ali_public_key_path']);
            //$aop->apiVersion = '1.0';
            //$aop->postCharset='utf-8';
            $aop->format = "json";
            $aop->charset = "UTF-8";
            $aop->signType = "RSA2";

            $request = new \AlipayDataDataserviceBillDownloadurlQueryRequest();
            $request->setBizContent("{" .
            "\"bill_type\":\"trade\"," .
            "\"bill_date\":\"".$bill_date."\"" .
            "  }");
            $result = $aop->execute ( $request); 
            //print_r($result);
            $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
            $resultCode = $result->$responseNode->code;
            $bill_download_url = $result->$responseNode->bill_download_url;
            if(!empty($resultCode)&&$resultCode == 10000){
                return ["code"=>"200","bill_url"=>$bill_download_url];
            } else {
                return ["code"=>$resultCode,"bill_url"=>""];
            }

        }
    }

    public function arg($data){
     
        $str = "";
        foreach($data as $k=>$v){
            $v = is_array($v)?json_encode($v,JSON_UNESCAPED_UNICODE):$v;
            $str.=$k."=".urlencode($v)."&";
        }
        return substr($str,0,-1);
    }


    //申请支付
    public function get_payurl_old()
    {
        //print_r($this->payment);print_r($this->order);
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = $this->alipay_config['noityUrl'];
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = $this->alipay_config['return_url'];
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //商户订单号
        $out_trade_no = $this->param['orderno'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = "订单编号：".$this->param['orderno'];
        //必填

        //付款金额
        $total_fee = $this->param['pay_price'];
        //必填

        //商品展示地址
        $show_url = "www.niuniuhuiapp.net";
        //必填，需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

        //订单描述
        $body = "交易订单";
        //选填

        //超时时间
        $it_b_pay = "30m";
        //选填

        //钱包token
        $extern_token = $this->order['pay_sn'];
        //选填


        /************************************************************/

        //构造要请求的参数数组，无需改动
        $parameter = array(
    		"service" => trim($this->alipay_config['service']),
    		"partner" => trim($this->alipay_config['partner']),
    		"seller_id" => trim($this->alipay_config['seller_id']),
    		"payment_type"	=> $payment_type,
    		"notify_url"	=> $notify_url,
    		"out_trade_no"	=> $out_trade_no,
    		"subject"	=> $subject,
    		"total_fee"	=> $total_fee,
    		"body"	=> $body,
    		"it_b_pay"	=> $it_b_pay,
    		"_input_charset"	=> trim(strtolower($this->alipay_config['input_charset']))
        );

        $para_filter = $this->paraFilter($parameter);

        //print_r($para_filter);

        $para_sort = $this->argSort($para_filter);

        $prestr = $this->createLinkstring($para_sort);

        $sign = $this->rsaSign($prestr, $this->alipay_config['private_key_path']);
        
        $sign = urlencode($sign);

        $ret = $prestr . "&sign=\"" . $sign . "\"&sign_type=\"" . $this->alipay_config['sign_type']."\"";
        
        return $ret;

    }

    //校验签名是否正确
    public function notify_verify($param){

        if(strtoupper($param['trade_status'])!='TRADE_SUCCESS' && strtoupper($param['trade_status'])!='TRADE_FINISHED')
            return false;
        /*
        //旧的做法，用新的做法
        unset($param['uid']);
        unset($param['order_sn']);
        unset($param['payment_code']);
        unset($param['pay_price']);
        unset($param['pay_sn']);
        unset($param['pay_time']);
        unset($param['f_pay_num']);
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyNotify($param);
        */
        
        return $this->rsaCheck($param);
        
    }

    //校验签名是否正确
    public function notify_verify_old($param){

        if(strtoupper($param['trade_status'])!='TRADE_SUCCESS' && strtoupper($param['trade_status'])!='TRADE_FINISHED')
            return false;

        $c = new AopClient;
        //$c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = $this->alipay_config['app_id'];
        //请填写开发者私钥去头去尾去回车，一行字符串
        $privateKey = $this->getKey($this->alipay_config['private_key_path']);
        $c->rsaPrivateKey = $privateKey;//$privateKey;
        //$c->rsaPrivateKeyFilePath = $this->alipay_config['private_key_path'];
        //$c->format = "json";
        //$c->charset= "utf-8";
        //请填写支付宝公钥，一行字符串
        //$ali_public_key = $this->getKey($this->alipay_config['ali_public_key_path']);
        //$c->alipayrsaPublicKey = $ali_public_key;

        return $c->rsaSign($data);
        
    }


    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    public function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $val == "")continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }


    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    public function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    public function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $val = is_array($val)?json_encode($val,JSON_UNESCAPED_UNICODE):$val;
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,-1);
        
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        
        return $arg;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
     */
    public function createLinkstringUrlencode($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".urlencode($val)."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        
        return $arg;
    }


    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key_path 商户私钥文件路径
     * return 签名结果
     */
    public function rsaSign($data, $private_key_path) {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 新的生成签名方式
     * @Author   zhuangqm
     * @Datetime 2016-11-12T17:55:05+0800
     * @param    [type]                   $data [description]
     * @return   [type]                         [description]
     */
    public function rsaSign_new($data){
        $c = new AopClient;
        //$c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = $this->alipay_config['app_id'];
        //请填写开发者私钥去头去尾去回车，一行字符串
        $privateKey = $this->getKey($this->alipay_config['private_key_path']);
        $c->rsaPrivateKey = $privateKey;//$privateKey;
        //$c->rsaPrivateKeyFilePath = $this->alipay_config['private_key_path'];
        //$c->format = "json";
        //$c->charset= "utf-8";
        //请填写支付宝公钥，一行字符串
        //$ali_public_key = $this->getKey($this->alipay_config['ali_public_key_path']);
        //$c->alipayrsaPublicKey = $ali_public_key;

        return $c->rsaSign($data);
        /**
        @param params 参数列表 key-参数名称 value-参数值
        @param privateKey 加签私钥
        @param charset 加签字符集
        **/
        //return $c->AlipaySignatureRsaSignRequest($data, $privateKey, "utf-8");
    }

    public function rsaSign_nnh(){
        
    }

    /**
     * 验签
     * @Author   zhuangqm
     * @Datetime 2016-11-16T17:05:29+0800
     * @param    [type]                   $data [description]
     * @return   [type]                         [description]
     */
    public function rsaCheck($data){
        /*
        $c = new AopClient;
        //$c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = $this->alipay_config['app_id'];
        //请填写开发者私钥去头去尾去回车，一行字符串
        //$privateKey = $this->getKey($this->alipay_config['private_key_path']);
        //$c->rsaPrivateKey = $privateKey;//$privateKey;
        //$c->rsaPrivateKeyFilePath = $this->alipay_config['private_key_path'];
        //$c->format = "json";
        //$c->charset= "utf-8";
        //请填写支付宝公钥，一行字符串
        $ali_public_key = $this->getKey($this->alipay_config['ali_public_key_path']);
        $c->alipayrsaPublicKey = $ali_public_key;
        //boolean AlipaySignature.rsaCheckV2(Map<String, String> params, String publicKey, String charset)
        $result = $c->rsaCheckV1($data,$this->alipay_config['ali_public_key_path']);
        */
        $aop = new AopClient;
        //$aop->alipayrsaPublicKey = '请填写支付宝公钥，一行字符串';
        $aop->alipayrsaPublicKey = $this->getKey($this->alipay_config['ali_public_key_path']);
        return $aop->rsaCheckV1($_POST, NULL, "RSA2");
    }

    public function getKey($path){
        return file_get_contents($path);
    }
    /**
     * RSA验签
     * @param $data 待签名数据
     * @param $ali_public_key_path 支付宝的公钥文件路径
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    public function rsaVerify($data, $ali_public_key_path, $sign)  {
        $pubKey = file_get_contents($ali_public_key_path);
        $res = openssl_get_publickey($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);    
        return $result;
    }

}