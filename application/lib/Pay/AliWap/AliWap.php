<?php
namespace App\Lib\Pay\AliWap;



use AlipayTradeWapPayContentBuilder;
use AlipayTradeService;

use \think\Config;

use AopClient;
use AlipayTradeWapPayRequest;

include("../application/lib/Pay/AliWap/AopSdk.php");

class AliWap{


	/**
	 * 支付下单
	 * @Author   zhuangqm
	 * @Datetime 2016-12-26T11:01:41+0800
	 * @return   [type]                   [description]
	 */
	public function addorder($param){

		//  require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'config.php';
		//  require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'wappay/service/AlipayTradeService.php';
		//  require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
		$alipay_config = Config::get("pay_ali");

		/*$c = new \AopClient;
		$c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
		$c->appId = $alipay_config['app_id'];
		$c->rsaPrivateKey = $this->getKey($alipay_config['private_key_path']);
		$c->format = "json";
		$c->charset= "UTF-8";
		$c->signType= "RSA2";
		$c->alipayrsaPublicKey = $this->getKey($alipay_config['ali_public_key_path']);
		//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.open.public.template.message.industry.modify
		$request = new \AlipayOpenPublicTemplateMessageIndustryModifyRequest();
		//SDK已经封装掉了公共参数，这里只需要传入业务参数
		//此次只是参数展示，未进行字符串转义，实际情况下请转义
		$request->setBizContent("{" +
		"    \"primary_industry_name\":\"IT科技/IT软件与服务\"," +
		"    \"primary_industry_code\":\"10001/20102\"," +
		"    \"secondary_industry_code\":\"10001/20102\"," +
		"    \"secondary_industry_name\":\"IT科技/IT软件与服务\"" +
		" }");
		$response= $c->execute($request);*/

		$aop = new AopClient ();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = $alipay_config['app_id'];
		$aop->rsaPrivateKey = $this->getKey($alipay_config['private_key_path']);
		$aop->alipayrsaPublicKey=$this->getKey($alipay_config['ali_public_key_path']);
		$aop->apiVersion = '1.0';
		$aop->postCharset="UTF-8";
		$aop->format='json';
		$aop->signType='RSA2';
		$request = new \AlipayTradeWapPayRequest();
		$request->setBizContent("{" .
		"    \"body\":\"".$param['WIDbody']."\"," .
		"    \"subject\":\"".$param['WIDsubject']."\"," .
		"    \"out_trade_no\":\"".$param['WIDout_trade_no']."\"," .
		"    \"timeout_express\":\"90m\"," .
		"    \"total_amount\":".$param['WIDtotal_amount']."," .
		"    \"product_code\":\"QUICK_WAP_PAY\"" .
		"  }");
		$request->setNotifyUrl($param['noityUrl']);
		$request->setReturnUrl($param['return_url']);
		$result = $aop->pageExecute( $request); 
		echo $result;

		/*
		//商户订单号，商户网站订单系统中唯一订单号，必填
	    $out_trade_no = $param['WIDout_trade_no'];

	    //订单名称，必填
	    $subject = $param['WIDsubject'];

	    //付款金额，必填
	    $total_amount = $param['WIDtotal_amount'];

	    //商品描述，可空
	    $body = $param['WIDbody'];

	    //公共回传参数
	    $passback_params = $param['passback_params'];

	    //超时时间
	    $timeout_express="1m";

	    $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
	    $payRequestBuilder->setBody($body);
	    $payRequestBuilder->setSubject($subject);
	    $payRequestBuilder->setOutTradeNo($out_trade_no);
	    $payRequestBuilder->setTotalAmount($total_amount);
	    $payRequestBuilder->setTimeExpress($timeout_express);
	    //$payRequestBuilder->setPassbackParams($passback_params);

	    $payResponse = new AlipayTradeService($config);
	    $result=$payResponse->wapPay($payRequestBuilder,$param['return_url'],$config['notify_url']);
*/
	    return ;
	}

	/**
	 * 支付结束回调
	 * @Author   zhuangqm
	 * @Datetime 2016-12-26T11:27:47+0800
	 * @return   [type]                   [description]
	 */
	public function notify_verify($param){
		if(strtoupper($param['trade_status'])!='TRADE_SUCCESS' && strtoupper($param['trade_status'])!='TRADE_FINISHED')
            return false;

		return $this->rsaCheck($param);

		/*require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'config.php';
		require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'wappay/service/AlipayTradeService.php';

		$arr=$param;

		$alipaySevice = new AlipayTradeService($config); 
		$result = $alipaySevice->check($arr);

		if($result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代

			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//商户订单号

			//$out_trade_no = $_POST['out_trade_no'];

			//支付宝交易号

			//$trade_no = $_POST['trade_no'];

			//交易状态
			$trade_status = $_POST['trade_status'];

		    if($_POST['trade_status'] == 'TRADE_FINISHED') {

				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
					//如果有做过处理，不执行商户的业务程序
						
				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
				//
				return 1;
		    }
		    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
					//如果有做过处理，不执行商户的业务程序			
				//注意：
				//付款完成后，支付宝系统发送该交易状态通知
				
				//支付成功

		    	return 2;
		    }
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
		        
			//echo "success";		//请不要修改或删除

		}else {
		    //验证失败
		    //echo "fail";	//请不要修改或删除

		    return 3;
		}*/
	}

	public function getKey($path){
        return file_get_contents($path);
    }

    /**
     * 验签
     * @Author   zhuangqm
     * @Datetime 2016-11-16T17:05:29+0800
     * @param    [type]                   $data [description]
     * @return   [type]                         [description]
     */
    public function rsaCheck($data){
        $alipay_config = Config::get("pay_ali");
 
        /*$c = new AopClient;
        //$c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = $alipay_config['app_id'];
        //请填写开发者私钥去头去尾去回车，一行字符串
        //$privateKey = $this->getKey($this->alipay_config['private_key_path']);
        //$c->rsaPrivateKey = $privateKey;//$privateKey;
        //$c->rsaPrivateKeyFilePath = $this->alipay_config['private_key_path'];
        //$c->format = "json";
        //$c->charset= "utf-8";
        //请填写支付宝公钥，一行字符串
        $ali_public_key = $this->getKey($alipay_config['ali_public_key_path']);
        $c->alipayrsaPublicKey = $ali_public_key;
        //boolean AlipaySignature.rsaCheckV2(Map<String, String> params, String publicKey, String charset)
        return $c->rsaCheckV1($_POST,$alipay_config['ali_public_key_path']);*/
        
        $aop = new AopClient;
        //$aop->alipayrsaPublicKey = '请填写支付宝公钥，一行字符串';
        $aop->alipayrsaPublicKey = $this->getKey($alipay_config['ali_public_key_path']);
        return $aop->rsaCheckV1($_POST, NULL, "RSA2");
    }
}