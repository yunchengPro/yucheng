<?php
// +----------------------------------------------------------------------
// |  [ 实体店外卖订单接口 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年6月29日14:28:47}}
// +----------------------------------------------------------------------
namespace app\api\controller\Stobusiness;
use app\api\ActionController;
use app\lib\Model;
use app\model\StoBusiness\StoOrdOrderModel;

class StoUserOrderController extends ActionController{

		/**
         * 初始化父级构造函数
        */
        public function __construct() {
            parent::__construct();
        }

        /**
	     * 订单列表
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-06-30T09:38:03+0800
	     * @return   [type]                   [description]
	     */
	    public function orderlistAction(){
	        $orderlisttype = $this->params['orderlisttype'];

	        if(!in_array($orderlisttype, [1,2,3])) //订单列表类型1全部2待评价3退款售后
	            return $this->json("404");

	        $StoOrderOBJ = new StoOrdOrderModel();

	        if($this->Version('2.2.0')){
	        	$newVersion = 1;
	        	$orderlist = $StoOrderOBJ->getOrderList($this->userid,$orderlisttype,$newVersion);
	        }else{
	        	$orderlist = $StoOrderOBJ->getOrderList($this->userid,$orderlisttype);
	    	}
	      

	        return $this->json("200",[
	                "total"=>$orderlist['total'],
	                "list"=>$orderlist['list'],
	            ]);
	    }

		/**
		 * [showorderAction 提交订单前操作]
		 * @Author   ISir<673638498@qq.com>
		 * @DateTime 2017-06-30T09:38:03+0800
		 * @return   [type]                   [description]
		 */
	    public function showorderAction(){
	        $cartitemids = $this->params['cartitemids'];
	        $productarr      = $this->params['productarr'];
	        $logisticsid = $this->params['logisticsid'];
	        $cusdelivertime = $this->params['cusdelivertime'];
	       	//$bonusamount = $this->params['bonusamount'];

	        if(empty($cartitemids) && empty($productarr))
	            return $this->json("404");
	       

	        $StoOrderOBJ = new StoOrdOrderModel();
	      
	        $result = $StoOrderOBJ->showorder([
	                "customerid"=>$this->userid,
	                "cartitemids"=>$cartitemids,
	                "productarr"=>$productarr,
	                "logisticsid"=>$logisticsid,
	                "cusdelivertime"=>$cusdelivertime
	                //'bonusamount'=>$bonusamount
	            ]);

	        return $this->json($result['code'],(!empty($result['data'])?$result['data']:[]));
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
	        $remark = $this->params['remark'];
	        $items = preg_replace('/\\\/i','',$items);
	        $bonusamount = $this->params['bonusamount'];
	        $cusdelivertime = $this->params['cusdelivertime'];

	        if(empty($sign) || empty($address_id) || empty($items))
	            return $this->json("404");

	        $StoOrdOrderModelOBJ = new StoOrdOrderModel();
	        if($this->Version('2.2.0')){
	        	$result = $StoOrdOrderModelOBJ->addorder([
		                "userid"=>$this->userid,
		                "sign"=>$sign,
		                "address_id"=>$address_id,
		                "items"=>$items,
		                "remark"=>$remark,
		                "bonusamount"=>$bonusamount,
		                'cusdelivertime' => $cusdelivertime,
		                'newVersion' => 1
		            ]);
	        }else{
		        $result = $StoOrdOrderModelOBJ->addorder([
		                "userid"=>$this->userid,
		                "sign"=>$sign,
		                "address_id"=>$address_id,
		                "items"=>$items,
		                "remark"=>$remark,
		                "bonusamount"=>$bonusamount,
		                'cusdelivertime' => $cusdelivertime
		            ]);
	    	}

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

	        $StoOrderOBJ = new StoOrdOrderModel();
	        if($this->Version('2.2.0')){
	        	$newVersion = 1;
	        	$result = $StoOrderOBJ->orderdetail([
		                "customerid"=>$this->userid,
		                "orderno"=>$orderno,
		                "newVersion"=>$newVersion
		            ]);
	        }else{
		        $result = $StoOrderOBJ->orderdetail([
		                "customerid"=>$this->userid,
		                "orderno"=>$orderno
		            ]);
	    	}
	        return $this->json($result['code'],[
	                "orderdetail"=>$result['orderdetail'],
	            ]);
	    }

		/**
		 * [cancelsorderAction 取消订单]
		 * @Author   ISir<673638498@qq.com>
		 * @DateTime 2017-06-30T14:50:47+0800
		 * @return   [type]                   [description]
		 */
	    public function cancelsorderAction(){
	        
	        $orderno          = $this->params['orderno'];
	        //$cancelreason     = $this->params['cancelreason'];

	        if(empty($orderno) )
	            return $this->json("404");


	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->cancelsOrder([
	                "customerid"=>$this->userid,
	                "orderno"=>$orderno,
	                "cancelreason"=>$cancelreason,
	            ]);

	        return $this->json($result['code']);
	    }


	    /**
	     * [refundorderAction 申请退款]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-07-03T14:40:41+0800
	     * @return   [type]                   [description]
	     */
	    public function refundorderAction(){
	    	$orderno          = $this->params['orderno'];
	        $cancelreason     = $this->params['cancelreason'];

	        if(empty($orderno) || empty($cancelreason))
	            return $this->json("404");


	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->refundOrder([
	                "customerid"=>$this->userid,
	                "orderno"=>$orderno,
	                "cancelreason"=>$cancelreason,
	            ]);

	        return $this->json($result['code']);
	    }
	  	
	  	/**
	  	 * [cancelrefundorderAction 取消退款]
	  	 * @Author   ISir<673638498@qq.com>
	  	 * @DateTime 2017-07-03T15:11:15+0800
	  	 * @return   [type]                   [description]
	  	 */
	    public function cancelrefundorderAction(){
	    	$orderno          = $this->params['orderno'];
	        $cancelreason     = $this->params['cancelreason'];

	        if(empty($orderno))
	            return $this->json("404");


	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->cancelrefundorder([
	                "customerid"=>$this->userid,
	                "orderno"=>$orderno,
	                "cancelreason"=>$cancelreason,
	            ]);

	        return $this->json($result['code']);
	    }

	    /**
	     * [confirmationorderAction description]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-07-06T20:20:15+0800
	     * @return   [type]                   [description]
	     */
	    public function confirmationorderAction(){

	    	$orderno          = $this->params['orderno'];
	      

	        if(empty($orderno) )
	            return $this->json("404");


	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->confirmationorder([
	                "customerid"=>$this->userid,
	                "orderno"=>$orderno
	            ]);

	        return $this->json($result['code']);
	    }

	    /**
	     * [onemoreorderAction 再来一单]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-07-06T20:29:51+0800
	     * @return   [type]                   [description]
	     */
	    public function onemoreorderAction(){
	    	$orderno          = $this->params['orderno'];
	        

	        if(empty($orderno) )
	            return $this->json("404");


	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->onemoreorder([
	                "customerid"=>$this->userid,
	                "orderno"=>$orderno
	            ]);

	        return $this->json($result['code'],(!empty($result['data'])?$result['data']:[]));
	    }


	    /**
	     * [deleteorderAction 删除订单]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-07-01T10:59:09+0800
	     * @return   [type]                   [description]
	     */
	    public function deleteorderAction(){

	    	$orderno          = $this->params['orderno'];

	        if(empty($orderno))
	            return $this->json("404");

	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->deleteorder([
	                "customerid"=>$this->userid,
	                "orderno"=>$orderno
	            ]);

	        return $this->json($result['code']);
	    }
        
}