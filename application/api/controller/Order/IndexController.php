<?php
namespace app\api\controller\Order;
use app\api\ActionController;
use app\model\Order\OrderModel;
use app\model\OrdOrderModel;
use app\model\OrdOrderItemModel;
use app\model\Sys\CommonModel;
use think\Config;
use app\model\OrdUserCountModel;

use app\lib\Model;


class IndexController extends ActionController
{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    
    /**
     * 订单列表
     * @Author   zhuangqm
     * @DateTime 2017-03-03T11:39:47+0800
     * @return   [type]                   [description]
     */
    public function orderlistAction()
    {
        $orderlisttype = $this->params['orderlisttype'];

        if(!in_array($orderlisttype, [1,2,3,4,5])) //订单列表类型1全部2待付款3待发货4待收货5待评价
            return $this->json("404");

        $OrdOrderOBJ = new OrdOrderModel();
        
        $params['customerid'] = $this->userid;
        $params['orderlisttype'] = $orderlisttype;
        $params['isAndroid'] = $this->Version($this->params['version'],"A");
        
        $orderlist = $OrdOrderOBJ->getOrderList($params);

//         $orderlist = $OrdOrderOBJ->getOrderList($this->userid,$orderlisttype);

//         if($this->Version("1.0.0")){
//             foreach($orderlist['list'] as $k=>$v){
//                 $orderlist['list'][$k]['totalamount'] = $v['totalamount']-$v['actualfreight'];
//             }
//         }

        return $this->json("200",[
                "total"=>$orderlist['total'],
                "list"=>$orderlist['list'],
            ]);
    }
    
    /**
    * @user 订单个数
    * @param 
    * @author jeeluo
    * @date 2017年4月11日下午8:14:52
    */
//     public function ordercountAction() {
//         $userOrderModel = new OrdUserCountModel();
//         $userInfo['orderCount'] = $userOrderModel->getRow(array("customerid" => $this->userid), "count_pay, count_deliver, count_receipt, count_evaluate, count_cart, count_return");
//         if(empty($userInfo['orderCount'])) {
//             $userInfo['orderCount']['count_pay'] = 0;
//             $userInfo['orderCount']['count_deliver'] = 0;
//             $userInfo['orderCount']['count_receipt'] = 0;
//             $userInfo['orderCount']['count_evaluate'] = 0;
//             $userInfo['orderCount']['count_cart'] = 0;
//             $userInfo['orderCount']['count_return'] = 0;
//         }
//         return $this->json(200, $userInfo);
//     }

    /**
     * 提交订单前的订单详情
     * @Author   zhuangqm
     * @DateTime 2017-03-07T10:04:54+0800
     * @return   [type]                   [description]
     */
    public function showorderAction(){
        $cartitemids = $this->params['cartitemids'];
        $skuid       = $this->params['skuid'];
        $productnum  = $this->params['productnum'];
        $logisticsid = $this->params['logisticsid'];

        if(empty($cartitemids) && empty($skuid))
            return $this->json("404");

        if(!empty($skuid) && empty($productnum))
            return $this->json("404");

        $OrderOBJ = new OrderModel();

        $result = $OrderOBJ->showorder([
                "customerid"=>$this->userid,
                "cartitemids"=>$cartitemids,
                "skuid"=>$skuid,
                "productnum"=>$productnum,
                "logisticsid"=>$logisticsid,
                "version"=>$this->getVersion(),
            ]);

        return $this->json($result['code'],(!empty($result['data'])?$result['data']:[]));
    }
    
    /**
    * @user 海外购添加收货人详细信息
    * @param 
    * @author jeeluo
    * @date 2017年8月28日下午7:01:17
    */
    public function addlogisticsinfoAction() {
        // 数据不能为空
        $params['user_logistics_id']    = $this->params['address_id'];
        $params['realname']             = $this->params['realname'];
        $params['idnumber']             = $this->params['idnumber'];
        $params['positiveImage']        = $this->params['positiveImage'];
        $params['oppositeImage']        = $this->params['oppositeImage'];
        
        if(empty($params['user_logistics_id']) || empty($params['realname']) || empty($params['idnumber']) || empty($params['positiveImage']) || empty($params['oppositeImage'])) {
            return $this->json(404);
        }
        
        if(!CommonModel::validation_filter_idcard($params['idnumber'])) {
            return $this->json(20003);
        }
        
        // 检验输入的姓名和收货人姓名是否一致
        $logistics = Model::ins("OrdUserLogistics")->getRow(["id"=>$params['user_logistics_id']],"customerid,realname");
        
        // user_logistics_id 和用户id是否有关系
        if($logistics['customerid'] != $this->userid) {
            return $this->json(1001);
        }
        
        if($logistics['realname'] != $params['realname']) {
            return $this->json(5014);
        }
        
        $infoData = Model::ins("OrdUserLogisticsInfo")->getRow(["id"=>$params['user_logistics_id']],"id");
        
        if(empty($infoData['id'])) {
            // 校验真实姓名
            $checkResult = Model::new("order.order")->checkUserAuthen(["realname"=>$params['realname'],"idnumber"=>$params['idnumber'],"userid"=>$this->userid]);
            if($checkResult['code'] != "200") {
                return $this->json($checkResult['code']);
            }
            // 写入到详细表中
            $insert_id = Model::ins("OrdUserLogisticsInfo")->insert($params);
            if($insert_id) {
                return $this->json(200);
            }
        } else {
            return $this->json(5017);
        }
        return $this->json(400);
    }

    /**
     * 添加订单
     * @Author   zhuangqm
     * @DateTime 2017-03-03T11:39:58+0800
     * @return   [type]                   [description]
     */
    public function addorderAction(){

        $sign           = $this->params['sign']; //md5(按业务字段排序(address_id+items))
        $sign           = strtoupper($sign);
        $address_id     = $this->params['address_id'];
        $items          = $this->params['items'];
        $items = preg_replace('/\\\/i','',$items);


        if(empty($sign) || empty($address_id) || empty($items))
            return $this->json("404");

        $orderModelOBJ = new OrderModel();
        $result = $orderModelOBJ->addorder([
                "userid"=>$this->userid,
                "sign"=>$sign,
                "address_id"=>$address_id,
                "items"=>$items,
                "version"=>$this->getVersion(),
                "isabroad"=>$this->params['isabroad'],
                "qianggou"=>$this->params['qianggouid'],
            ]);

        return $this->json($result['code'],[
                "orderids"=>$result['orderidstr'],
                "ordercount"=>intval($result['ordercount']),
            ]);
    }

    /**
     * 订单详情
     * @Author   zhuangqm
     * @DateTime 2017-03-06T10:56:56+0800
     * @return   [type]                   [description]
     */
    public function orderdetailAction(){

        $orderno     = $this->params['orderno'];

        if(empty($orderno))
            return $this->json("404");

        $orderModelOBJ = new OrderModel();

        $result = $orderModelOBJ->orderdetail([
                "customerid"=>$this->userid,
                "orderno"=>$orderno,
                "version"=>$this->getVersion(),
            ]);

        return $this->json($result['code'],[
                "orderdetail"=>$result['orderdetail'],
            ]);
    }

    /**
     * 取消订单
     * @Author   zhuangqm
     * @DateTime 2017-03-06T16:55:10+0800
     * @return   [type]                   [description]
     */
    public function cancelsorderAction(){
        
        $orderno          = $this->params['orderno'];
        $cancelreason     = $this->params['cancelreason'];

        if(empty($orderno) || empty($cancelreason))
            return $this->json("404");


        $orderModelOBJ = new OrderModel();
        $result = $orderModelOBJ->cancelsOrder([
                "customerid"=>$this->userid,
                "orderno"=>$orderno,
                "cancelreason"=>$cancelreason,
            ]);

        return $this->json($result['code']);
    }

    /**
     * 确认收货
     * @Author   zhuangqm
     * @DateTime 2017-03-06T19:19:08+0800
     * @return   [type]                   [description]
     */
    public function confirmorderAction(){
        $orderno          = $this->params['orderno'];
       
        if(empty($orderno))
            return $this->json("404");

        $orderModelOBJ = new OrderModel();
        $result = $orderModelOBJ->confirmOrder([
                "customerid"=>$this->userid,
                "orderno"=>$orderno,
            ]);

        return $this->json($result['code']);
    }

    /**
     * 申请退款
     * @Author   zhuangqm
     * @DateTime 2017-03-06T19:36:39+0800
     * @return   [type]                   [description]
     */
    public function refundAction(){
        $orderno          = $this->params['orderno'];
        $returnreason     = $this->params['returnreason'];
        $returnamount     = $this->params['returnamount'];
        $returnbull       = $this->params['returnbull'];
       
        if(empty($orderno) || empty($returnreason) || ($returnamount==0 && $returnbull==0))
            return $this->json("404");

        $orderModelOBJ = new OrderModel();
        $result = $orderModelOBJ->refund([
                "customerid"=>$this->userid,
                "param"=>$this->params,
            ]);

        return $this->json($result['code']);
    }

    /**
     * [remindshippingAction 提醒卖家发货]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-09T11:33:02+0800
     * @return   [type]                   [description]
     */
    public function remindshippingAction(){

        $orderno = $this->params['orderno'];
        
        if(empty($orderno))
            return $this->json("404");

        $remindshipping_limit = 3;
        $ActLimitOBJ = Model::new("Sys.ActLimit");
        //一天只提醒3次，就提示给用户
        $check_actlimit = $ActLimitOBJ->check("remindshipping".$orderno,$remindshipping_limit);
        if(!$check_actlimit['check']){
            return $this->json("7006");
        }

        $orderModelOBJ = new OrderModel();
        $result = $orderModelOBJ->remindshipping($orderno, $this->userid);

        $ActLimitOBJ->update("remindshipping".$orderno,86400); //冻结一天

        return $this->json(200);
    }

    /**
     * [extendedreceiptAction 延长收货]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-09T11:46:39+0800
     * @return   [type]                   [description]
     */
    public function extendedreceiptAction(){
      
        $orderno = $this->params['orderno'];
        
        if(empty($orderno))
            return $this->json("404");

        $orderModelOBJ = new OrderModel();
        $result = $orderModelOBJ->extendedreceipt([
                "customerid"=>$this->userid,
                "param"=>$this->params,
            ]);

        return $this->json($result['code']);
    }

    /**
     * [deleteorder description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-09T14:51:22+0800
     * @return   [type]                   [description]
     */
    public function deleteorderAction(){

        $orderno = $this->params['orderno'];
        
        if(empty($orderno))
            return $this->json("404");

        $orderModelOBJ = new OrderModel();
        
        $result = $orderModelOBJ->deleteorder([
                "customerid"=>$this->userid,
                "param"=>$this->params,
            ]);

        return $this->json($result['code']);
    }
}
