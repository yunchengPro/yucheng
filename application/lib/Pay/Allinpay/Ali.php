<?php

namespace app\lib\Pay\Allinpay;

use think\Config;
use app\lib\Pay\Allinpay\Common;

class Ali{

	protected $config = [];

	public function __construct() {
		
		//加载配置
		$this->config = Config::get("allinpay");

	}

	// 生成支付订单
	public function getPayOrder($param){

		$url = $this->config['ali_pay_url'];

        //生成回调地址
        $notify_url = $this->config['ali_notify_url'];
        $productName = !empty($this->config['productName'])?$this->config['productName']:"交易订单支付";

        $data['cusid'] 			= $this->config['cusid_ali']; // 商户号
        $data['appid'] 			= $this->config['appid_ali']; // 应用ID
        $data['version'] 		= "11"; // 接口版本号
        $data['trxamt'] 		= $param['pay_price']; // 交易金额 单位为分
        $data['reqsn'] 			= $param['orderno']; // 商户的交易订单号
        $data['paytype'] 		= 'A01'; // A01：支付宝扫码支付  W01：微信扫码支付
        $data['randomstr']      = Common::getRandChar(32);
        $data["body"] 			=  $productName;

        $data["notify_url"] 	= $notify_url;
        //$data['limit_pay']      = "no_credit"; // 指定支付方式
        
        //print_r($data);
        

        $s = Common::getSign($data,$this->config['key_ali']);
        $data["sign"] = $s;
        //echo "======".$data["sign"]."=======";
        //print_r($data);
        Common::addPayLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "amount"=>$param['pay_price'],
                "pay_type"=>"allinpay_ali",
                "data"=>$data,
            ]);

        $response = Common::curl([
        		"url"=>$url,
        		"data"=>$data,
        	]);

        $response = json_decode($response,true);

    	Common::returnPayLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "retcode"=>$response['retcode'],
                "retmsg"=>$response['retmsg'],
                "data"=>$response,
            ]);

        //返回json数组

        return $response;
	}

    /**
     * 校验请求是否合法
     * @Author   zhuangqm
     * @DateTime 2017-06-23T11:06:23+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function notify_verify($param){

        $sign = $param['sign'];
        unset($param['sign']);

        $s = Common::getSign($param,$this->config['key_ali']);
        //echo "===$s======$sign====";
        if($s == $sign){
            return true;
        }else{
            return false;
        }
        
    }

}