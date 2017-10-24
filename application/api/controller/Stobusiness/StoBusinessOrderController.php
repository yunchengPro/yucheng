<?php
// +----------------------------------------------------------------------
// |  [ 实体店商家订单 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年6月30日17:09:34}}
// +----------------------------------------------------------------------
namespace app\api\controller\Stobusiness;
use app\api\ActionController;
use app\lib\Model;
use app\model\StoBusiness\StoOrdOrderModel;
use app\model\StoBusiness\StobusinessModel;


class StoBusinessOrderController extends ActionController{
	
		/**
         * 初始化父级构造函数
        */
        public function __construct() {
            parent::__construct();
        }

        /**
	     * 订单列表
	     * @Author   zhuangqm
	     * @DateTime 2017-03-03T11:39:47+0800
	     * @return   [type]                   [description]
	     */
	    public function orderlistAction(){
	        $orderlisttype = $this->params['orderlisttype'];

	        if(!in_array($orderlisttype, [1,2,3,4,5])) //订单列表类型1全部2待评价3退款售后
	            return $this->json("404");

	        $StoOrderOBJ = new StoOrdOrderModel();
	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }
	        $businessid = $businessData['data'];
	        if($this->Version('2.2.0')){
	        	$newVersion = 1;
	    		$orderlist = $StoOrderOBJ->getBusinessOrderList($businessid,$orderlisttype,$newVersion);
	    	}else{
	    		
	    		$orderlist = $StoOrderOBJ->getBusinessOrderList($businessid,$orderlisttype);
	    	}
	      

	        return $this->json("200",[
	                "total"=>$orderlist['total'],
	                "list"=>$orderlist['list'],
	            ]);
	    }

	    /**
	     * [refuseorderAction 拒单]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-06-30T20:22:13+0800
	     * @return   [type]                   [description]
	     */
	    public function refuseorderAction(){

	    	$orderno          = $this->params['orderno'];
	        $refusereason     = $this->params['refusereason'];

	        if(empty($orderno) )
	            return $this->json("404");

	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }

	        $businessid = $businessData['data'];

	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->businessrefuseorder([
	                "businessid"=>$businessid,
	                "orderno"=>$orderno,
	                "refusereason"=>$refusereason,
	            ]);

	        return $this->json($result['code']);
	    } 

	    /**
	     * [takingorderAction 接单]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-07-01T09:53:36+0800
	     * @return   [type]                   [description]
	     */
	    public function takingorderAction(){

	    	$orderno          = $this->params['orderno'];

	        if(empty($orderno))
	            return $this->json("404");

	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }

	        $businessid = $businessData['data'];

	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->takingorder([
	                "businessid"=>$businessid,
	                "orderno"=>$orderno
	            ]);

	        return $this->json($result['code']);
	    } 
  
	    /**
	     * [deliveryorderAction 配送订单]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-07-01T10:33:40+0800
	     * @return   [type]                   [description]
	     */
	    public function  deliveryorderAction(){

	    	$orderno          = $this->params['orderno'];

	        if(empty($orderno))
	            return $this->json("404");

	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }

	        $businessid = $businessData['data'];

	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->actualfreightorder([
	                "businessid"=>$businessid,
	                "orderno"=>$orderno
	            ]);

	        return $this->json($result['code']);
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

	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }

	        $businessid = $businessData['data'];

	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->deleteorder([
	                "businessid"=>$businessid,
	                "orderno"=>$orderno
	            ]);

	        return $this->json($result['code']);
	    }

	    /**
	     * [refuserreturnorderAction 拒绝退款]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-07-01T10:59:42+0800
	     * @return   [type]                   [description]
	     */
	    public function refusereturnorderAction(){

	    	$orderno          = $this->params['orderno'];
	    	$remark           = $this->params['remark'];
	        if(empty($orderno))
	            return $this->json("404");

	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }

	        $businessid = $businessData['data'];

	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->refusereturnorder([
	                "businessid"=>$businessid,
	                "orderno"=>$orderno,
	                "remark"=>$remark
	            ]);

	        return $this->json($result['code']);
	    }

	    /**
	     * [agreetorefundorderACtion 同意退款]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-07-01T14:27:05+0800
	     * @return   [type]                   [description]
	     */
	    public function agreetorefundorderAction(){
	    	$orderno          = $this->params['orderno'];

	        if(empty($orderno))
	            return $this->json("404");

	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }

	        $businessid = $businessData['data'];

	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->agreetorefundorder([
	                "businessid"=>$businessid,
	                "orderno"=>$orderno
	            ]);

	        return $this->json($result['code']);
	    }

	    /**
	     * [businessorderdetailAction 商家订单详情]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-07-01T15:46:07+0800
	     * @return   [type]                   [description]
	     */
	    public function businessorderdetailAction(){

	       $orderno          = $this->params['orderno'];

	        if(empty($orderno))
	            return $this->json("404");

	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }

	        $businessid = $businessData['data'];


	        $StoOrderOBJ = new StoOrdOrderModel();

	        $result = $StoOrderOBJ->businessorderdetail([
	                "businessid"=>$businessid,
	                "orderno"=>$orderno
	            ]);

	        return $this->json($result['code'],[
	                "orderdetail"=>$result['orderdetail'],
	            ]);
	    }

	    /**
	     * [preparationOrderAction 商家备菜]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-09-21T18:35:54+0800
	     * @return   [type]                   [description]
	     */
	    public function preparationOrderAction(){

	    	if(!$this->Version('2.2.0')){
	    		return $this->json('60038');
	    	}

	    	$orderno          = $this->params['orderno'];

	        if(empty($orderno))
	            return $this->json("404");

	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }

	        $businessid = $businessData['data'];

	        $StoOrderOBJ = new StoOrdOrderModel();

	        $result = $StoOrderOBJ->preparationOrder([
	                "businessid"=>$businessid,
	                "orderno"=>$orderno
	            ]);
	        return $this->json($result['code']);
	    }

	    /**
	     * [refundorderAction 退款]
	     * @Author   ISir<673638498@qq.com>
	     * @DateTime 2017-09-23T10:51:51+0800
	     * @return   [type]                   [description]
	     */
	    public function businessrefundorderAction(){

	    	if(!$this->Version('2.2.0')){
	    		return $this->json('60038');
	    	}
	    	$orderno          = $this->params['orderno'];
	    	$paypwd           = $this->params['paypwd'];
	        if(empty($orderno) || empty($paypwd))
	            return $this->json("404");

	        $businessData = StobusinessModel::getBusinesInfoBuyCustomerid(['customerid'=>$this->userid]);
	        if($businessData['code'] != 200){
	        	return $this->json($businessData['code']);
	        }

	        $businessid = $businessData['data'];

	        $StoOrderOBJ = new StoOrdOrderModel();
	        $result = $StoOrderOBJ->businessrefundorder([
	                "businessid"=>$businessid,
	                "orderno"=>$orderno,
	                'customerid'=>$this->userid,
	                'paypwd'   => $paypwd
	            ]);

	        return $this->json($result['code']);
	    }

}