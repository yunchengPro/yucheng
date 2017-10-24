<?php

namespace app\lib\Pay\Allinpay;

use think\Config;
use app\lib\Pay\Allinpay\Common;

class Weixin{

	protected $config = [];

	public function __construct() {
		
		//加载配置
		$this->config = Config::get("allinpay");

	}

	// 生成支付订单
	public function getPayOrder($param){

		$url = $this->config['weixin_pay_url'];

        //生成回调地址
        $notify_url = $this->config['weixin_notify_url'];
        $productName = !empty($this->config['productName'])?$this->config['productName']:"交易订单支付";

        $data['cusid'] 			= $this->config['cusid_weixin']; // 商户号
        $data['appid'] 			= $this->config['appid_weixin']; // 应用ID
        //$data['version'] 		= $this->config['version']; // 接口版本号
        $data['trxamt'] 		= $param['pay_price']; // 交易金额 单位为分
        $data['reqsn'] 			= $param['orderno']; // 商户的交易订单号
        $data['paytype'] 		= '2';
        $data['randomstr']      = Common::getRandChar(32);
        $data["body"] 			=  $productName;

        $data['sub_appid']      = $this->config['sub_appid']; // 微信appid
        $data['sub_mchid']      = $this->config['sub_mchid']; // 微信子商户
        $data["notify_url"] 	= $notify_url;
        $data['limit_pay']      = "no_credit"; // 指定支付方式
        
        //print_r($data);

        $s = Common::getSign($data,$this->config['key_weixin']);
        $data["sign"] = $s;
        //echo "======".$data["sign"]."=======";
        //print_r($data);
        Common::addPayLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "amount"=>$param['pay_price'],
                "pay_type"=>"allinpay_weixin",
                "data"=>$data,
            ]);

        $response = Common::curl([
        		"url"=>$url,
        		"data"=>$data,
        	]);
        $response = json_decode($response,true);
        /*echo "------";
        print_r($response);
        echo "========";*/
    	Common::returnPayLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "retcode"=>$response['retcode'],
                "retmsg"=>$response['retmsg'],
                "data"=>$response,
            ]);

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

        $s = Common::getSign($param,$this->config['key_weixin']);

        if($s == $sign){
            return true;
        }else{
            return false;
        }
        
    }
    

}