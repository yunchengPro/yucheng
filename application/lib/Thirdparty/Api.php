<?php
namespace app\lib\Thirdparty;
/**
 * 	蜂鸟配送-接口
 * 	date:2017-09-18
 * 	author:zhuangqm
 */

use \think\Config;

use app\lib\Model;
use app\lib\Thirdparty\Tool\AnubisApiHelper;
use app\lib\Thirdparty\Tool\HttpClient;


class Api{

	private $api_url 		= '';
	private $app_id  		= '';
	private $secret_key 	= '';
	private $notify_url     = '';
	private $token 			= '';

	// 构造函数
	public function __construct(){
		$config = Config::get("thirdparty");

		$this->api_url 		= $config['api_url'];
		$this->app_id 		= $config['app_id'];
		$this->secret_key 	= $config['secret_key'];
		$this->notify_url 	= $config['notify_url'];
	}

	// 获取token 保证24小时
	public function getToken() {

		$ThirdpartyRedis = Model::Redis("Thirdparty");

		if(empty($this->token)){

			$key = "token";

			if(!$ThirdpartyRedis->exists($key)){
				
				$this->token = $this->requestToken();

				$ThirdpartyRedis->set($key,$this->token,24*3600);

			}else{
				$this->token = $ThirdpartyRedis->get($key);

				if(empty($this->token)){
					$this->token = $this->requestToken();

					$ThirdpartyRedis->set($key,$this->token,24*3600);
				}

			}

		}

		return $this->token;
	}

	private function requestToken(){
		$salt = AnubisApiHelper::getSalt();
	    // 获取签名
	    $sig = AnubisApiHelper::generateSign($this->app_id, $salt, $this->secret_key);
	    $url = $this->api_url . '/get_access_token';
	    $tokenStr = HttpClient::doGet($url, array('app_id' => $this->app_id, 'salt' => $salt, 'signature' => $sig));
	    /*echo "获取token";
	    echo $tokenStr.PHP_EOL;*/
	    // 获取token
	    return json_decode($tokenStr, true)['data']['access_token'];
	}


	/**
	 * 请求
	 * @Author   zhuangqm
	 * @DateTime 2017-09-18T18:01:59+0800
	 * @param    [type]                   $param [
	 *                                           	'url'=>''
	 *                                           	'data'
	 *                                           	'request_count'=> 重复请求次数 只允许重复取3次
	 *                                           ]
	 * @return   [type]                          [description]
	 */
	protected function request($param){
		// 执行开始时间
		$_phpstarttime = microtime(true);

		$salt = AnubisApiHelper::getSalt();

	    $dataJson =  json_encode($param['data'], JSON_UNESCAPED_UNICODE);
	    // echo 'data json is ' . $dataJson . PHP_EOL;

	    // $urlencodeData = urlencode($dataJson);
	    $urlencodeData = urlencode($dataJson);

	    // echo 'urlencode data is ' . $urlencodeData . PHP_EOL;
	    $sig = AnubisApiHelper::generateBusinessSign($this->app_id, $this->getToken(), $urlencodeData, $salt);   //生成签名
	    $requestJson = json_encode(array(
	      'app_id' => $this->app_id,
	      'salt' => $salt,
	      'data' => $urlencodeData,
	      'signature' => $sig
	    ));
	    // echo $requestJson . PHP_EOL;

	    // $this->url = 'http://127.0.0.1:8080/anubis-webapi/order';
	    
	    $url = $param['url'];

	    $result_json =  HttpClient::doPost($url, $requestJson);
	    //var_dump($result_json);

	    $result = json_decode($result_json,true);

	    // 添加请求日志
	    Model::new("Thirdparty.Thirdparty")->addRequestLog([
            "orderno"=>$param['data']['partner_order_code'],
			"request_type"=>str_replace($this->api_url, '', $url),
			"userid"=>$param['userid'],
			"request_content"=>$dataJson,
			"retcode"=>$result['code'],
			"retmsg"=>$result['msg'],
			"return_content"=>$result_json,
			"request_time"=>microtime(true)-$_phpstarttime,
        ]);

	    // token不正确或token已失效
	    if($result['code']=='40004' && intval($param['request_count'])<3){
	    	$this->token = $this->requestToken();
	    	$param['request_count'] = intval($param['request_count'])+1;
	    	return $this->request($param);
	    }

	    return $result;
	}

	/**
	 * 	添加门店
	 * @Author   zhuangqm
	 * @DateTime 2017-09-18T11:59:26+0800
	 * @param    [type]                   $param [
	 *                                           name 门店名称
	 *                                           contactPhone 门店联系信息(手机号或座机)
	 * 										     address 门店地址(64个汉字长度，支持汉字、符号、字母的组合)
	 * 										     longitude  门店经度
	 * 										     latitude  门店纬度
	 * 										]
	 * @return   [type]                          [description]
	 */
	public function chain_store($param){

	    return $this->request([
	    	"url"=>$this->api_url . '/v2/chain_store',
	    	"data"=>$param,
	    ]);
	}

	/**
	 * 提交配送订单
	 * @Author   zhuangqm
	 * @DateTime 2017-09-18T15:45:10+0800
	 * @param    [type]                   $param [description]
	 */
	/*
	$dataArray = array(
		  'transport_info' => array(
		    'transport_name' => '饿了么Bod上海普陀1站',
		    'transport_address' => '上海市普陀区近铁城市广场5楼',
		    'transport_longitude' => 121.5156496362,
		    'transport_latitude' => 31.2331643501,
		    'position_source' => 1,
		    'transport_tel' => '13900000000',
		    'transport_remark' => '备注'
		  ),
		  'receiver_info' => array(
		    'receiver_name' => 'jiabuchong',
		    'receiver_primary_phone' => '13900000000',
		    'receiver_second_phone' => '13911111111',
		    'receiver_address' => '太阳',
		    'receiver_longitude' => 121.5156496362,
		    'position_source' => 3,
		    'receiver_latitude' => 31.2331643501
		  ),
		  'items_json' => array(
		    array(
		      'item_name' => '苹果',
		      'item_quantity'=> 5,
		      'item_price' => 9.50,
		      'item_actual_price' => 10.00,
		      'is_need_package' => 1,
		      'is_agent_purchase' => 0
		    ),
		    array(
		      'item_name' => '香蕉',
		      'item_quantity'=> 20,
		      'item_price' => 100.00,
		      'item_actual_price' => 300.59,
		      'is_need_package' => 1,
		      'is_agent_purchase' => 0
		    )
		  ),
		  'partner_remark' => '天下萨拉',
		  'partner_order_code' => '1234567890xx124',     // 第三方订单号, 需唯一
		  //'notify_url' => 'http://vpcb-anubis-web-base-2.vm.elenet.me:5000',     //第三方回调 url地址
		  'order_type' => 1,
		  'order_total_amount' => 50.00,
		  'order_actual_amount' => 48.00,
		  'order_weight'=> 1.0,
		  'is_invoiced' => 1,
		  'invoice' => '饿了么',
		  'order_payment_status' => 1,
		  'order_payment_method' => 1,
		  'require_payment_pay' => 50.00,
		  'goods_count' => 4,
		  'is_agent_payment' => 1,
		  'require_receive_time' => strtotime('+1 day') * 1000  //注意这是毫秒数
	);
	 */
	public function addOrder($param){

		$param['notify_url'] = $this->notify_url;

	    $result = $this->request([
	    	"url"=>$this->api_url . '/v2/order',
	    	"data"=>$param,
	    ]);

	    /*
	    // 注意没有运力的情况
	    {
		    "code":500070,
		    "msg":"没有运力覆盖",
		    "data":{}
		}
	     */

	    return $result;
	}

	/**
	 * 取消订单
	 * @Author   zhuangqm
	 * @DateTime 2017-09-18T17:29:47+0800
	 * @param    [type]                   $param [
	 * 											"partner_order_code" => $partner_order_code,
										        "order_cancel_reason_code" => 2,
										        "order_cancel_code"=>订单取消编码（0:其他, 1:联系不上商户, 2:商品已经售完, 3:用户申请取消, 4:运力告知不配送 让取消订单, 5:订单长时间未分配, 6:接单后骑手未取件）
										        "order_cancel_description" => "货品不新鲜",
										        "order_cancel_time" => time() * 1000
	 * 										]
	 * @return   [type]                          [description]
	 */
	public function cancelOrder($param){

	    return $this->request([
	    	"url"=>$this->api_url . '/v2/order/cancel',
	    	"data"=>$param,
	    ]);
	}

	/**
	 * 订单查询
	 * @Author   zhuangqm
	 * @DateTime 2017-09-18T17:54:41+0800
	 * @param    [type]                   $param [
	 *                                           "partner_order_code": "1383837732" 商户查询的订单号
	 * 										]
	 */
	public function QueryOrder($param){

	    return $this->request([
	    	"url"=>$this->api_url . '/v2/order/query',
	    	"data"=>$param,
	    ]);
	}

	/**
	 * 订单骑手位置
	 * @Author   zhuangqm
	 * @DateTime 2017-09-18T18:00:46+0800
	 * @param    [type]                   $param [
	 *                                           "partner_order_code": "1383837732" 商户查询的订单号
	 * 										]
	 * @return   [type]                          [description]
	 */
	public function carrier($param){

	    return $this->request([
	    	"url"=>$this->api_url . '/v2/order/carrier',
	    	"data"=>$param,
	    ]);
	}

	/**
	 * 订单投诉
	 * @Author   zhuangqm
	 * @DateTime 2017-09-18T18:30:14+0800
	 * @param    [type]                   $param [
	 *                                          "partner_order_code": "BG32141",
        										"order_complaint_code": 150,
										        "order_complaint_desc": "未保持餐品完整",
										        "order_complaint_time": 1452570728594
	 * 										]
	 * @return   [type]                          [description]
	 */
	public function complaintOrder($param){

		return $this->request([
	    	"url"=>$this->api_url . '/v2/order/complaint',
	    	"data"=>$param,
	    ]);
	}
}