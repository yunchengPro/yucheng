<?php
// 快捷支付
namespace app\lib\Pay\Allinpay;

use think\Config;
use app\lib\Pay\Allinpay\Common;
use app\lib\Pay\Allinpay\Allinpay;

class Quick{

	protected $config = [];

	public function __construct() {
		
		//加载配置
		$this->config = Config::get("allinpay");

	}

	// 生成支付订单
	public function getPayOrder($param){

        //生成回调地址
        $notify_url = $this->config['quick_notify_url'];

        $allinpay_userid = Allinpay::getUserId([
                "userid"=>$param['userid'],
            ]);
        
        $productName = !empty($this->config['productName'])?$this->config['productName']:"交易订单支付";
        
        $data['inputCharset']  = '1'; // 协议字符集 1代表UTF-8、2代表GBK、3代表GB2312
        //$data['pickupUrl']     = '';
        $data['receiveUrl']    = $notify_url;   // 支付通知结果以此为准
        $data['version']       = "v1.0";
        $data['language']      = "1";
        $data['signType']      = '0';
        $data['merchantId']    = $this->config['cusid_quick'];
        $data['orderNo']       = $param['orderno'];
        $data['orderAmount']   = $param['pay_price'];
        $data['orderCurrency'] = '0'; //商户在填写该字段的时候请先确认当前使用的商户号开通的支付交易类型
        $data['orderDatetime'] = date("YmdHis",strtotime($param['order_time']));
        $data['productName']   = $productName; // 商品名称
        $data['ext1']          = "<USER>".$allinpay_userid."</USER>";
        $data['payType']       = "33";
        $s = Common::getSignQuick($data,$this->config['key_quick'],false);
        $data["signMsg"] = $s;

        // 支付日志
        Common::addPayLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "amount"=>$param['pay_price'],
                "pay_type"=>"allinpay_quick",
                "data"=>$data,
            ]);

        return $data;
	}

    /**
     * H5快捷支付
     * @Author   zhuangqm
     * @DateTime 2017-07-25T10:55:12+0800
     * @param    [type]                   $param [
     *                                           userid
     *                                           orderno
     *                                           pay_price
     *                                           order_time
     * ]
     * @return   [type]                          [description]
     */
    public function getPayOrderWeb($param){

        $pay_type = $param['pay_type']!=''?$param['pay_type']:'allinpay_quick_web';

        //生成回调地址
        $notify_url = $this->config['quick_web_notify_url'];

        $allinpay_userid = Allinpay::getUserId([
                "userid"=>$param['userid'],
            ]);
        
        $productName = !empty($this->config['productName'])?$this->config['productName']:"交易订单支付";
        
        $data['inputCharset']  = '1'; // 协议字符集 1代表UTF-8、2代表GBK、3代表GB2312
        $data['pickupUrl']     = '';
        $data['receiveUrl']    = $notify_url;   // 支付通知结果以此为准
        $data['version']       = "v1.0";
        $data['language']      = "1";
        $data['signType']      = '0';
        $data['merchantId']    = $this->config['cusid_quick'];
        $data['orderNo']       = $param['orderno'];
        $data['orderAmount']   = $param['pay_price'];
        $data['orderCurrency'] = '0'; //商户在填写该字段的时候请先确认当前使用的商户号开通的支付交易类型
        $data['orderDatetime'] = date("YmdHis",strtotime($param['order_time']));
        $data['productName']   = $productName; // 商品名称
        $data['ext1']          = "<USER>".$allinpay_userid."</USER>";
        $data['payType']       = "33";
        $s = Common::getSignQuick($data,$this->config['key_quick'],false,true);
        $data["signMsg"] = $s;

        // 支付日志
        Common::addPayLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "amount"=>$param['pay_price'],
                "pay_type"=>$pay_type,
                "data"=>$data,
            ]);

        return $data;
    }


    /**
     * 校验请求是否合法
     * @Author   zhuangqm
     * @DateTime 2017-06-23T11:06:23+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function notify_verify($param){

        $sign = $param['signMsg'];
        unset($param['signMsg']);

        //对数组键值重新排序
        $key_tmp = [
            "merchantId","version","language","signType","payType","issuerId","paymentOrderId","orderNo","orderDatetime","orderAmount","payDatetime","payAmount","ext1","ext2","payResult","errorCode","returnDatetime"
        ];

        $new_param = [];

        foreach($key_tmp as $val){
            if($param[$val]!='')
                $new_param[$val] = $param[$val];
        }

        //$s = Common::getSign($param,$this->config['api_key']);
        $s = Common::getSignQuick($new_param,$this->config['key_quick'],false);
        //echo "----$s--------$sign------";
        if($s == $sign){
            return true;
        }else{
            return false;
        }
        
    }

}