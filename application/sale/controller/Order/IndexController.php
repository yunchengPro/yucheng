<?php
namespace app\sale\controller\Order;

use app\sale\ActionController;
use app\lib\Model;
use think\Config;
use app\model\Sys\CommonModel;

use app\model\Order\OrderModel;

class IndexController extends ActionController {
    
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

        return $this->json($result['code'],[
                "orderdetail"=>$result['orderdetail'],
            ]);
    }
}