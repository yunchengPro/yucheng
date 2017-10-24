<?php
/**
 * 支付请求
 */
namespace app\api\controller\Pay;
use app\api\ActionController;

use app\model\Pay\PayModel;

class RequestController extends ActionController
{
    public function __construct(){
        parent::__construct();
        
    }
    
    /**
     * 申请支付请求
     * @Author   zhuangqm
     * @DateTime 2017-02-24T10:33:27+0800
     * @return   [type]                   [返还支付需要的相关信息]
     */
 	public function getRequestAction(){

 		$pay_type = $this->params['pay_type']; //支付方式
        $orderid = $this->params['orderid']; //订单ID

        if($pay_type!='' && !in_array($pay_type, ['weixin','ali']))
            return $this->json("404");

        //获取订单信息
        $order = [];
        
        //订单不存在
        if(empty($order)){
            return $this->json("6001");
        }


        //判断订单是否已支付成功
        if($order['pay_status']==10)
            return $this->json("6002");

        $payOBJ = null;
        //微信支付
        if($pay_type=='weixin'){

        	$data = PayModel::weixinPayRequest([
        			"orderid"=>$order['orderid'],
        			"pay_price"=>$order['price'],
        			"orderBody"=>$order['productname'],
        		]);

        	if(!empty($data))
                return $this->json("200",$data);
            else
                return $this->json("6003");
            
        }

        //支付宝支付
        if($pay_type=='ali'){
        	$data = PayModel::weixinPayRequest([
        			"orderid"=>$order['orderid'],
        			"pay_price"=>$order['price'],
        		]);

        	if(!empty($data))
                return $this->json("200",array("param"=>$data));
            else
                return $this->json("6003");

        }
 	}   

}
