<?php
namespace app\sale\controller\Order;

use app\sale\ActionController;
use app\lib\Model;
use think\Config;
use app\model\Sys\CommonModel;

use app\model\Order\OrderModel;

class IndexController extends ActionController {
    
    const pageNum = 20;
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 订单列表
     * @Author   zhuangqm
     * @DateTime 2017-10-23T11:29:36+0800
     * @return   [type]                   [description]
     */
    public function orderlistAction(){
        $title = "我的订单";
        
        $viewData = [
            "title" => $title
        ];
        
        return $this->view($viewData);
    }
    
    public function getorderlistdataAction() {
        $orderlisttype = $this->params['orderlisttype'];
        if(!in_array($orderlisttype, [1,2,3,4,5])) //订单列表类型1全部2待付款3待发货4待收货5待评价
            return $this->json("404");
        
        $OrdOrderModel = Model::ins("OrdOrder");
        $param['customerid'] = $this->userid;
        $param['orderlisttype'] = $orderlisttype;
        $param['isAndroid'] = '';
    
        $orderlist = $OrdOrderModel->getOrderList($param);
        
        $orderlist['maxPage'] = 0;
        if($orderlist['total']>0) {
            $orderlist['maxPage'] = ceil($orderlist['total']/self::pageNum);
        }

       
        return $this->json("200", $orderlist);
    }
        
    /**
     * [提交订单前的订单详情]
     * @Author   zhuangqm
     * @DateTime 2017-10-23T11:30:08+0800
     * @return   [type]                   [description]
     */
    public function showorderAction(){
        
        $this->addcheck();
    

        return $this->view([
            "cartitemids"=>$this->params['cartitemids'],
            "skuid"=>$this->params['skuid'],
            "productnum"=>$this->params['productnum'],
            "logisticsid"=>$this->params['logisticsid'],
            "addorderkey"=>md5($this->userid.Config::get("key.app_key")),
        ]);
    }

    public function getshoworderAction(){

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
     * 添加订单
     * @Author   zhuangqm
     * @DateTime 2017-10-23T11:32:59+0800
     * @return   [type]                   [description]
     */
    public function addorderAction(){

        $this->checktokenHandle();

        $sign           = $this->params['sign']; //md5(按业务字段排序(address_id+items))
        $sign           = strtoupper($sign);
        $address_id     = $this->params['address_id'];
        $items          = $this->params['items'];
        $items = preg_replace('/\\\/i','',$items);

        // h5页面才有的参赛
        $addorderkey    = $this->params['addorderkey'];


        if(empty($sign) || empty($address_id) || empty($items) || empty($addorderkey))
            return $this->json("4041");

        if($addorderkey!=md5($this->userid.Config::get("key.app_key")))
            return $this->json("4042");

        $orderModelOBJ = new OrderModel();
        $result = $orderModelOBJ->addorder([
                "userid"=>$this->userid,
                "sign"=>$sign,
                "address_id"=>$address_id,
                "items"=>$items,
                "version"=>$this->getVersion(),
                "isabroad"=>$this->params['isabroad'],
                "qianggou"=>$this->params['qianggouid'],
                "addorderkey"=>$this->params['addorderkey'],
            ]);
        
        return $this->json($result['code'],[
                "orderids"=>$result['orderidstr'],
                "ordercount"=>intval($result['ordercount']),
            ]);
    }

    /**
     * 获取加密串
     * @Author   zhuangqm
     * @DateTime 2017-10-23T17:16:10+0800
     * @return   [type]                   [description]
     */
    public function getsignAction(){

        if($this->params['addorderkey']=='')
            return $this->json("404");

        if($this->params['addorderkey']!=md5($this->userid.Config::get("key.app_key")))
            return $this->json("404");

        $this->params['items'] = preg_replace('/\\\/i','',$this->params['items']);

        return $this->json("200",[
            "sign"=>Model::new("Order.Order")->getSign([
                "address_id"=>$this->params['address_id'],
                "items"=>$this->params['items'],
                "addorderkey"=>$this->params['addorderkey'],
            ]),
        ]);
    }

    /**
     * 订单详情
     * @Author   zhuangqm
     * @DateTime 2017-11-01T10:26:19+0800
     * @return   [type]                   [description]
     */
    public function orderdetailAction(){

        $orderno     = $this->params['orderno'];

        return $this->view([
            "orderno"=>$orderno,
        ]);
    }

    /**
     * [getorderdetailAction description]
     * @Author   zhuangqm
     * @DateTime 2017-11-01T10:38:39+0800
     * @return   [type]                   [description]
     */
    public function getorderdetailAction(){
        $orderno     = $this->params['orderno'];

        if(empty($orderno))
            return $this->json("404");

        $orderModelOBJ = new OrderModel();

        $result = $orderModelOBJ->orderdetail([
                "customerid"=>$this->userid,
                "orderno"=>$orderno,
                "version"=>$this->getVersion(),
            ]);
        //print_r($result['orderdetail']);
        // 对订单操作按钮做处理
        /*
        -----------订单操作说明--------
        orderact 订单操作按钮字段
        orderact:act说明：1表示操作按钮2显示文字
        orderact:actname:按钮值
        orderact:acttype说明：
        1 付款
        2 取消订单
        3 提醒商家发货
        4 退款--申请退款
        5 订单详情-取消退款
        6 延长收货
        7 查看物流
        8 确认收货
        9 评价
        10 售后
        11 删除订单
        12 退款详情-取消退款
        13 退款详情-修改申请
        14 退款详情-撤销申请
        15 订单详情-退款中
        16 订单详情-已退款
        17 退单列表-填写物流单号
        18 退单列表-查看物流(和7重复了，去除)
        19 退款详情-提交物流
         */
        
        $acturl = [
            "1"=>"/Sys/Pay/paymethod?orderno=".$result['orderdetail']['orderno'],
            "2"=>"ACT:cancelorder",
            "3"=>"ACT:remindshipping",
            "4"=>"",
            "5"=>"",
            "6"=>"ACT:extendedreceipt",
            "7"=>"ACT:", // 物流
            "8"=>"ACT:confirmorder",
            "9"=>"/Order/Evaluateorder/addEvaluateOrder?orderno=".$result['orderdetail']['orderno'],
            "10"=>"",
            "11"=>"ACT:deleteorder",
            "12"=>"",
            "13"=>"",
            "14"=>"",
            "15"=>"",
            "16"=>"",
            "17"=>"",
            "18"=>"",
            "19"=>"",
        ];

        foreach($result['orderdetail']['orderact'] as $key=>$value){

            if($value['act']==1){
                if(!empty($acturl[$value['acttype']]))
                    $result['orderdetail']['orderact'][$key]['acturl'] = $acturl[$value['acttype']];
                else
                    unset($result['orderdetail']['orderact'][$key]);
            }else if($value['act']==2){
                $result['orderdetail']['orderact'][$key]['acturl'] = $acturl[$value['acttype']];
            }
        }
        //print_r($result);
        return $this->json($result['code'],[
                "orderdetail"=>$result['orderdetail'],
            ]);
    }
    
    /**
    * @user 延长收货
    * @param 
    * @author jeeluo
    * @date 2017年11月6日下午6:14:19
    */
    public function extendedreceiptAction() {
        $orderno = $this->params['orderno'];
        
        if(empty($orderno))
            return $this->json("404");
        
        $result = Model::new("Order.Order")->extendedreceipt([
            "customerid"=>$this->userid,
            "orderno"=>$orderno,
        ]);
        
        return $this->json($result['code']);
    }
    
    /**
    * @user 提醒发货
    * @param 
    * @author jeeluo
    * @date 2017年11月7日上午10:41:56
    */
    public function remindshippingAction() {
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
    
//         $orderModelOBJ = new OrderModel();
        $result = Model::new("Order.Order")->remindshipping($orderno, $this->userid);
    
        $ActLimitOBJ->update("remindshipping".$orderno,86400); //冻结一天
    
        return $this->json(200);
    }
    
    /**
    * @user 确认收货
    * @param 
    * @author jeeluo
    * @date 2017年11月7日上午11:20:48
    */
    public function confirmorderAction() {
        $orderno = $this->params['orderno'];
        
        if(empty($orderno))
            return $this->json("404");
        
        $result = Model::new("Order.Order")->confirmOrder([
            "customerid" => $this->userid,
            "orderno" => $orderno
        ]);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 订单交易完成页面
    * @param 
    * @author jeeluo
    * @date 2017年11月7日上午11:38:22
    */
    public function goodsdealsuccessAction() {
        $title = "交易成功";
        
        $orderno = $this->params['orderno'];
        $viewData = [
            "title" => $title,
            "orderno" => $orderno
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 跳转到去评价页面
    * @param 
    * @author jeeluo
    * @date 2017年11月7日下午2:12:12
    */
    public function gogoodscommentAction() {
        $title = "发表评价";
        
        $orderno = $this->params['orderno'];
        $customerid = $this->userid;
        
        $viewData = [
            "title" => $title,
            "orderno" => $orderno,
            "customerid" => $customerid
        ];
        
        $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 删除订单操作
    * @param 
    * @author jeeluo
    * @date 2017年11月7日下午5:32:41
    */
    public function deleteorderAction() {
        $orderno = $this->params['orderno'];
        
        if(empty($orderno))
            return $this->json("404");
        
        $orderModel = Model::new("Order.Order");
        
        $result = $orderModel->deleteorder([
            "customerid" => $this->userid,
            "orderno" => $orderno 
        ]);
        
        return $this->json($result["code"]);
    }

    /**
     * [cancelsOrderAction 取消订单]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-11-09T18:13:41+0800
     * @return   [type]                   [description]
     */
    public function cancelsorderAction(){
        
        $orderno = $this->params['orderno'];
        $cancelreason = $this->params['cancelreason'];

        if(empty($orderno))
            return $this->json("404");
        
        $orderModel = Model::new("Order.Order");
        
        $result = $orderModel->cancelsOrder([
            "customerid" => $this->userid,
            "orderno" => $orderno,
            'cancelreason'=>$cancelreason
        ]);
        
        return $this->json($result["code"]);
    }

    /**
     * [choselogisticsAction 选择收货地址]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-11-10T09:40:55+0800
     * @return   [type]                   [description]
     */
    public function choselogisticsAction(){
        $productnum = $this->params['productnum'];
        $skuid = $this->params['skuid'];
        $cartitemids = $this->params['cartitemids'];
        $viewData = [
            'title' => '选择收货地址',
            'productnum' =>$productnum,
            'skuid' => $skuid,
            'cartitemids' => $cartitemids
        ];
        return $this->view($viewData);
    }
}