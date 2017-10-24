<?php

namespace app\lib\Pay\Allinpay;

use think\Config;
use app\lib\Pay\Allinpay\Common;
use app\lib\Pay\Allinpay\Quick;

use app\lib\Model;

class Allinpay{

	public function __construct() {
		
	}

    /**
     * 获取通联用户ID
     * @Author   zhuangqm
     * @DateTime 2017-06-22T15:19:20+0800
     * @param    [type]                   $param [
     *                                           userid 用户ID
     * ]
     * @return   [type]                          [userid 通联的用户ID]
     */
    public static function getUserId($param){

        $config = Config::get("allinpay");

        $userid = $param['userid'];

        $userOBJ = Model::ins("AllinpayUserid");

        $row = $userOBJ->getRow(["customerid"=>$userid],"userid");

        $allinpay_userid = '';

        if(empty($row['userid'])){

            /*
            注意：这里的字段要按顺序
             */
            $data['signType']       = 0; // 签名类型
            $data['merchantId']     = $config['cusid_quick']; // 商户号
            $data['partnerUserId']  = $userid; // 合作商户的用户编号

            $s = Common::getSignQuick($data,$config['key_quick'],2);
            $data["signMsg"] = $s;
            $response = Common::curl([
                    "url"=>$config['get_userid_url'],
                    "data"=>$data,
                ]);
            $response = json_decode($response,true);
            
            /**
             * 返回
             *  merchantId 商户号 30 必填 数字串，与提交时的商户号保持一致
                signType 签名类型 2 必填 固定选择值：0，MD5 签名
                userId 通联用户编号 50 必填 数字串
                resultCode 结果代码 10 必填 返回处理结果代码
                returnDatetime
                结果返回时间 14 必填 系统返回结果的时间，日期格式：yyyyMMDDhhmmss
                signMsg 签名字符串 1024 必填 以上所有非空参数按上述顺序与密钥 key 组合，并经
                MD5 签名后生成该值。
             */
            if($response['resultCode']=='0000' || $response['resultCode']=='0006'){
                $allinpay_userid = $response['userId'];

                $userOBJ->insert([
                        "customerid"=>$userid,
                        "userid"=>$allinpay_userid,
                        "addtime"=>date("Y-m-d H:i:s"),
                    ]);
            }
        }else{
            $allinpay_userid = $row['userid'];
        }

        return $allinpay_userid;
    }

    /**
     * 快捷支付h5支付
     * @Author   zhuangqm
     * @DateTime 2017-07-07T14:13:10+0800
     * @param    [type]                   $param [
     *                                           userid,
     *                                           orderno,
     *                                           pay_price
     *                                           order_time
     *                             ]
     */
    public static function QuickWeb($param){

        $config = Config::get("allinpay");

        $pay_type = 'allinpay_quick_web';

        //添加支付请求记录
        $result = Model::new("Pay.Pay")->addPayOrder([
                "orderno"=>$param['orderno'],
                "amount"=>$param['pay_price'],
                "pay_type"=>$pay_type,
                "userid"=>$param['userid'],
            ]);
        if($result['code']!='200')
            return $this->json($result['code']);


        $Quick = new Quick();

        $order_data = $Quick->getPayOrderWeb([
                "userid"=>$param['userid'],
                "orderno"=>$param['orderno'],
                "pay_price"=>$param['pay_price'],
                "order_time"=>$param['order_time'],
                "pay_type"=>$pay_type,
            ]);

        $html = Common::buildRequestForm([
                "gatewayUrl"=>$config['quick_web_url'],
                "para_temp"=>$order_data,
            ]);
        
        print_r($html);
        exit;

        return $html;
    }

    /**
     * 下载对账单
     * @Author   zhuangqm
     * @DateTime 2017-07-12T11:35:54+0800
     * @return   [type]                   [description]
     */
    public static function downbill($param){
        $bill_date = $param['bill_date'];

        $cusid = $param['cusid'];
        $appid = $param['appid'];

        if(!empty($bill_date) && !empty($cusid) && !empty($appid)){

            $config = Config::get("allinpay");

            $url = $this->config['bill_url'];


            $data['cusid']          = $cusid; // 商户号
            $data['appid']          = $appid; // 应用ID
            $data['date']           = $bill_date; // 交易日期
            $data['randomstr']      = Common::getRandChar(32);
            
            //print_r($data);

            $s = Common::getSign($data,$this->config['key_weixin']);
            $data["sign"] = $s;
            //echo "======".$data["sign"]."=======";
            //print_r($data);

            $response = Common::curl([
                    "url"=>$url,
                    "data"=>$data,
                ]);
            
            $response = json_decode($response,true);
            echo "------";
            print_r($response);
            echo "========";
            exit;
            if($response['retcode']=="SUCCESS" && !empty($response['url'])){
                return ["code"=>"200","bill_url"=>$response['url']];
            }else{
                return ["code"=>"400","bill_url"=>"","msg"=>$response['retmsg']];
            }

        }
    }
}