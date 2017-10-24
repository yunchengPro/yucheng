<?php
/**
* 退货退款单类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:37:24Z robert zhao $
*/

namespace app\model;
use app\lib\Model;
use app\lib\Db;

class OrdOrderReturnModel {

	protected $_id 	= null;

	protected $_returnType 	= null;

	protected $_businessId 	= null;

	protected $_businessName 	= null;

	protected $_orderid 	= null;

	protected $_orderCode 	= null;

	protected $_returnno 	= null;

	protected $_starttime 	= null;
	
	protected $_actiontime = null;
	
	protected $_examinetime = null;

	protected $_endtime 	= null;

	protected $_returnreason 	= null;

	protected $_remark 	= null;

	protected $_images 	= null;

	protected $_orderstatus 	= null;

	protected $_customerid 	= null;

	protected $_customerName 	= null;

	protected $_mobile 	= null;

	protected $_isget 	= null;

	protected $_returnAddress 	= null;

	protected $_expressid 	= null;

	protected $_expressname 	= null;

	protected $_expressnumber 	= null;

	protected $_expressRemark 	= null;

	protected $_expressPic 	= null;

	protected $_returnamount 	= null;

	protected $_freight 	= null;

	protected $_actualamount 	= null;

	protected $_receiveremark 	= null;

	protected $_isdelete 	= null;

	protected $_refusereason 	= null;

	protected $_addressid 	= null;

	protected $_itemsId 	= null;

	protected $_productid 	= null;

	protected $_productname 	= null;

	protected $_productcode 	= null;

	protected $_productnum 	= null;

	protected $_categoryid 	= null;

	protected $_categoryname 	= null;

	protected $_brandid 	= null;

	protected $_brandname 	= null;

	protected $_moduleid 	= null;

	protected $_modulename 	= null;

	protected $_skuid 	= null;

	protected $_skucode 	= null;

	protected $_skudetail 	= null;

	protected $_price 	= null;

	protected $_realproductnum 	= null;

	protected $_orderMoney 	= null;

	protected $_thumb 	= null;

	protected $_auditRemark 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('OrdOrderReturn');
	}

	/**
	 *
	 * 添加退货退款单
	 */

	public function add($data) {
	    return $this->insert($data);
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新退货退款单
	 */

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}
	
	public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id = null) {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getById($this->_id);
		return $this->_dataInfo;
	}
	
    /*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }
	
	public function getOrderReturnList($where, $field='*', $order='', $otherstr='') {
	    return $this->_modelObj->getList($where, $field, $order, $otherstr);
	}

    /**
	 *
	 * 订单详细信息表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}
    
    public function getReturnPageList($where, $fields="*", $order='') {
        return $this->_modelObj->pageList($where, $fields, $order);
    }
    
    public function pageList($where, $fields="*", $order='', $flag=1, $page='', $pagesize='') {
        return $this->_modelObj->pageList($where, $fields, $order, $flag);
    }

	/**
	 * 获取所有退货退款单
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	/**
	 * 获取订单详情中退款操作状态
	 * @Author   zhuangqm
	 * @DateTime 2017-03-07T20:59:07+0800
	 * @param 							$param
	 *               						orderstatus
	 *               						orderid
	 *               						skuid
	 *               						productnum 商品数量
	 * @return   [type]                   [description]
	 */
	public function getOrderReturnAct($param){
		$orderact = [];
		$return_item = $this->getRow(["orderid"=>$param['orderid'],"skuid"=>$param['skuid']],"id,return_type,orderstatus,productnum", "id desc");
		
		$return_list = $this->getList(["orderid"=>$param['orderid'],"skuid"=>$param['skuid']],"id,return_type,orderstatus,productnum", "id asc");

		$itemInfo = Model::ins("OrdOrderItem")->getRow(array("orderid" => $param['orderid'], "skuid" => $param['skuid']), "productnum, enable");
		
		$return_count = 0;
		$return_productnum = 0; // 申请退款的数量
		$finish_productnum = 0; // 申请退款成功的数量
		$lastReturn_productnum = 0; //最后一次退货的数量
		$examFailStatus    = 0;
		
		foreach($return_list as $k=>$v){

			if($v['orderstatus']!=3 && $v['orderstatus']!=13){
				$return_count+=1;
				$return_productnum+=$v['productnum'];
				$lastReturn_productnum = $v['productnum'];
				// if($v['orderstatus']==2 || $v['orderstatus']==4 || $v['orderstatus']==12 || $v['orderstatus']==14){
				if($v['orderstatus']==4 || $v['orderstatus']==14){
					$finish_productnum+=$v['productnum'];
				}
			} else {
			    $examFailStatus = 1;
			}
		}

		if($itemInfo['enable'] == 1) {
			$itemInfo['productnum'] += $finish_productnum;
		} else {
			$itemInfo['productnum'] = $finish_productnum;
		}

		// echo $return_productnum;
		// echo $lastReturn_productnum;
		// echo $finish_productnum;
		// print_r($param);

		//退货单状态：1 退款等待审核 2退款审核通过 3退款审核不通过 4退款成功 11 退货退款等待审核 12 退款审核通过 13 退款不通过 14退款成功 20取消退货/退款 31 退款中 32 退款成功
        if($return_count>0){
            
        	if($return_item['return_type']==1){
        		switch($return_item['orderstatus']){
        			case 1: //1 退款等待审核
        				$orderact = [
	        							"act"=>"1",
	        							"acttype"=>"15",
	        							"actname"=>"退款中"
        							];
        				break;
        			case 2: //2退款审核通过
        				$orderact = [
	        							"act"=>"1",
	        							"acttype"=>"15",
	        							"actname"=>"退款中"
        							];
        				break;
        			case 3: //3退款审核不通过
        				$orderact = [
	        							"act"=>"1",
	        							"acttype"=>"15",
//         				                "acttype"=>"15",
	        							"actname" => "退款中"
        							];
        				break;
        			case 4: // 4退款成功
        				$orderact = [
	        							"act"=>"1",
	        							"acttype"=>"16",
	        							"actname"=>"已退款"
        							];
        				break;
        			case 20: //用户取消
        				$orderact = $param['orderstatus']==1?["act"=> "1","acttype" => "4","actname" => "退款"]:["act" 	  => "1","acttype" => "10","actname" => "退款"];
        				break;
        		}
        	}else{
//         	    if($lastReturn_productnum < $param['productnum']) {
        	    // if($lastReturn_productnum < $param['productnum']) {
        		if($return_productnum == $finish_productnum && $return_productnum < $itemInfo['productnum'])
        		  {
        	        $orderact = [
        	            "act" => "1",
        	            "acttype" => "10",
        	            "actname" => "退款"
        	        ];
        	    } else {
        	        if($return_item['orderstatus'] == 11) {
        	            $orderact = [
        	                "act" => "1",
        	                "acttype" => "15",
        	                "actname" => "退款中"
        	            ];
        	        } else if($return_item['orderstatus'] == 12) {
        	            $orderact = [
        	                "act" => "2",
        	                "acttype" => "15",
        	                "actname" => "退款中"
        	            ];
        	        } else if($return_item['orderstatus'] == 14) {
        	            $orderact = [
        	                "act" => "1",
        	                "acttype" => "16",
        	                "actname" => "已退款"
        	            ];
        	        }
        	    }
        	    
//         		if($return_productnum<$param['productnum']){
//                 if($return_item['productnum'] < $param['productnum']) {
//         			$orderact = [
// 							"act"=>"1",
// 							"acttype"=>"10",
// 							"actname"=>"申请售后"
// 						];

//         		}else{
       //  			if($finish_productnum<$param['productnum'])
       //  				$orderact = [
							// 	"act"=>"1",
    			// 				"acttype"=>"15",
    			// 				"actname"=>"退款中"
							// ];
       //  			else
		     //    		$orderact = [
							// 	"act"=>"2",
							// 	"acttype"=>"16",
							// 	"actname"=>"已退款"
							// ];
//         			if($return_item['orderstatus'] == 11) {
//         				$orderact = [
// 	        							"act"=>"1",
// 	        							"acttype"=>"15",
// 	        							"actname"=>"退款中"
//         							];
//         			} else if($return_item['orderstatus'] == 12) {
//         				$orderact = [
// 	        							"act"=>"2",
// 	        							"acttype"=>"15",
// 	        							"actname"=>"退款中"
//         							];
//         			} else if($return_item['orderstatus'] == 13) {
//         				$orderact = [
// 	        							"act"=>"1",
// 	        							"acttype"=>"10",
// 	        							"actname"=>"申请售后"
//         							];
// 					} else if($return_item['orderstatus'] == 14) {
// 						if($finish_productnum < $param['productnum']) {
// 							$orderact = [
// 	        							"act"=>"1",
// 	        							"acttype"=>"15",
// 	        							"actname"=>"退款中"
//         							];
// 						} else {
// 							$orderact = [
// 	        							"act"=>"1",
// 	        							"acttype"=>"16",
// 	        							"actname"=>"已退款"
//         							];
// 						}
//         			}
//         		}
        	}

        	/*
        	if($return_item['orderstatus']>=1 && $return_item['orderstatus']<=12)
        		$orderact = $param['orderstatus']==1?["act"=>"1","acttype"=>"15","actname"=>"退款中"]:["act"=>"1","acttype" =>"15","actname" =>"退款中"];
		    else if($return_item['orderstatus']==13){
		    	$orderact = $param['orderstatus']==1?["act" 	  => "1","acttype" => "16","actname" => "已退款"]:["act" 	  => "1","acttype" => "16","actname" => "已退款"];
		    }else{
		    	$orderact = $param['orderstatus']==1?["act" 	  => "1","acttype" => "4","actname" => "退款"]:["act" 	  => "1","acttype" => "10","actname" => "申请售后"];
		    }    	
            */
        }else{
            if($examFailStatus == 0) {
        	   $orderact = $param['orderstatus']==1?["act"=> "1","acttype" => "4","actname" => "退款"]:["act" 	  => "1","acttype" => "10","actname" => "退款"];
            } else {
                $orderact = $param['orderstatus']==1?["act"=> "1","acttype" => "15","actname" => "退款中"]:["act" 	  => "1","acttype" => "15","actname" => "退款"];
            }
        }

        if(empty($orderact))
        	$orderact = $param['orderstatus']==1?["act"=> "1","acttype" => "4","actname" => "退款"]:["act" 	  => "1","acttype" => "10","actname" => "退款"];

        return $orderact;
	}
	
	public function getOrderReturnObj($params) {
	    return $this->_modelObj->getList(array("order_code" => $params['orderno'], "skuid" => $params['skuid'], "productid" => $params['productid']), "id");
	}



	/**
	 *
	 * 删除退货退款单
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置主键id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置1退货，2退款
	 *
	 */
	public function setReturnType($returnType) {
		$this->_returnType = $returnType;
		return $this;
	}

	/**
	 * 设置商家ID
	 *
	 */
	public function setBusinessId($businessId) {
		$this->_businessId = $businessId;
		return $this;
	}

	/**
	 * 设置商家名称
	 *
	 */
	public function setBusinessName($businessName) {
		$this->_businessName = $businessName;
		return $this;
	}

	/**
	 * 设置订单ID
	 *
	 */
	public function setOrderid($orderid) {
		$this->_orderid = $orderid;
		return $this;
	}

	/**
	 * 设置订单号
	 *
	 */
	public function setOrderCode($orderCode) {
		$this->_orderCode = $orderCode;
		return $this;
	}

	/**
	 * 设置退货单号
	 *
	 */
	public function setReturnno($returnno) {
		$this->_returnno = $returnno;
		return $this;
	}

	/**
	 * 设置申请时间
	 *
	 */
	public function setStarttime($starttime) {
		$this->_starttime = $starttime;
		return $this;
	}
	
	public function setActiontime($actiontime) {
	    $this->_actiontime = $actiontime;
	    return $this;
	}
	
	public function setExaminetime($examinetime) {
	    $this->_examinetime = $examinetime;
	    return $this;
	}

	/**
	 * 设置订单结束时间
	 *
	 */
	public function setEndtime($endtime) {
		$this->_endtime = $endtime;
		return $this;
	}

	/**
	 * 设置退货原因
	 *
	 */
	public function setReturnreason($returnreason) {
		$this->_returnreason = $returnreason;
		return $this;
	}

	/**
	 * 设置退货备注
	 *
	 */
	public function setRemark($remark) {
		$this->_remark = $remark;
		return $this;
	}

	/**
	 * 设置退货图片
	 *
	 */
	public function setImages($images) {
		$this->_images = $images;
		return $this;
	}

	/**
	 * 设置退货单状态
	 *
	 */
	public function setOrderstatus($orderstatus) {
		$this->_orderstatus = $orderstatus;
		return $this;
	}

	/**
	 * 设置顾客ID
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置用户名
	 *
	 */
	public function setCustomerName($customerName) {
		$this->_customerName = $customerName;
		return $this;
	}

	/**
	 * 设置手机号
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置是否收到货，0未收到，1已收到
	 *
	 */
	public function setIsget($isget) {
		$this->_isget = $isget;
		return $this;
	}

	/**
	 * 设置退货地址
	 *
	 */
	public function setReturnAddress($returnAddress) {
		$this->_returnAddress = $returnAddress;
		return $this;
	}

	/**
	 * 设置快递ID
	 *
	 */
	public function setExpressid($expressid) {
		$this->_expressid = $expressid;
		return $this;
	}

	/**
	 * 设置快递名称
	 *
	 */
	public function setExpressname($expressname) {
		$this->_expressname = $expressname;
		return $this;
	}

	/**
	 * 设置快递单号
	 *
	 */
	public function setExpressnumber($expressnumber) {
		$this->_expressnumber = $expressnumber;
		return $this;
	}

	/**
	 * 设置快递备注
	 *
	 */
	public function setExpressRemark($expressRemark) {
		$this->_expressRemark = $expressRemark;
		return $this;
	}

	/**
	 * 设置快递图片
	 *
	 */
	public function setExpressPic($expressPic) {
		$this->_expressPic = $expressPic;
		return $this;
	}

	/**
	 * 设置退还金额
	 *
	 */
	public function setReturnamount($returnamount) {
		$this->_returnamount = $returnamount;
		return $this;
	}

	/**
	 * 设置退回的运费
	 *
	 */
	public function setFreight($freight) {
		$this->_freight = $freight;
		return $this;
	}

	/**
	 * 设置实退金额
	 *
	 */
	public function setActualamount($actualamount) {
		$this->_actualamount = $actualamount;
		return $this;
	}

	/**
	 * 设置收货备注
	 *
	 */
	public function setReceiveremark($receiveremark) {
		$this->_receiveremark = $receiveremark;
		return $this;
	}

	/**
	 * 设置是否删除
	 *
	 */
	public function setIsdelete($isdelete) {
		$this->_isdelete = $isdelete;
		return $this;
	}

	/**
	 * 设置拒绝理由
	 *
	 */
	public function setRefusereason($refusereason) {
		$this->_refusereason = $refusereason;
		return $this;
	}

	/**
	 * 设置退货地址ID
	 *
	 */
	public function setAddressid($addressid) {
		$this->_addressid = $addressid;
		return $this;
	}

	/**
	 * 设置订单明细ID
	 *
	 */
	public function setItemsId($itemsId) {
		$this->_itemsId = $itemsId;
		return $this;
	}

	/**
	 * 设置商品ID
	 *
	 */
	public function setProductid($productid) {
		$this->_productid = $productid;
		return $this;
	}

	/**
	 * 设置商品名称
	 *
	 */
	public function setProductname($productname) {
		$this->_productname = $productname;
		return $this;
	}

	/**
	 * 设置货号
	 *
	 */
	public function setProductcode($productcode) {
		$this->_productcode = $productcode;
		return $this;
	}

	/**
	 * 设置商品退货数量
	 *
	 */
	public function setProductnum($productnum) {
		$this->_productnum = $productnum;
		return $this;
	}

	/**
	 * 设置分类ID
	 *
	 */
	public function setCategoryid($categoryid) {
		$this->_categoryid = $categoryid;
		return $this;
	}

	/**
	 * 设置分类名称
	 *
	 */
	public function setCategoryname($categoryname) {
		$this->_categoryname = $categoryname;
		return $this;
	}

	/**
	 * 设置品牌ID
	 *
	 */
	public function setBrandid($brandid) {
		$this->_brandid = $brandid;
		return $this;
	}

	/**
	 * 设置品牌名称
	 *
	 */
	public function setBrandname($brandname) {
		$this->_brandname = $brandname;
		return $this;
	}

	/**
	 * 设置类型ID
	 *
	 */
	public function setModuleid($moduleid) {
		$this->_moduleid = $moduleid;
		return $this;
	}

	/**
	 * 设置类型名称
	 *
	 */
	public function setModulename($modulename) {
		$this->_modulename = $modulename;
		return $this;
	}

	/**
	 * 设置Sku的ID
	 *
	 */
	public function setSkuid($skuid) {
		$this->_skuid = $skuid;
		return $this;
	}

	/**
	 * 设置Sku码
	 *
	 */
	public function setSkucode($skucode) {
		$this->_skucode = $skucode;
		return $this;
	}

	/**
	 * 设置sku详情
	 *
	 */
	public function setSkudetail($skudetail) {
		$this->_skudetail = $skudetail;
		return $this;
	}

	/**
	 * 设置价格
	 *
	 */
	public function setPrice($price) {
		$this->_price = $price;
		return $this;
	}

	/**
	 * 设置购买数量
	 *
	 */
	public function setRealproductnum($realproductnum) {
		$this->_realproductnum = $realproductnum;
		return $this;
	}

	/**
	 * 设置订单交易金额
	 *
	 */
	public function setOrderMoney($orderMoney) {
		$this->_orderMoney = $orderMoney;
		return $this;
	}

	/**
	 * 设置商品或者是服务商家的图片
	 *
	 */
	public function setThumb($thumb) {
		$this->_thumb = $thumb;
		return $this;
	}

	/**
	 * 设置审核备注
	 *
	 */
	public function setAuditRemark($auditRemark) {
		$this->_auditRemark = $auditRemark;
		return $this;
	}

	public static function getModelObj() {
		return new OrdOrderReturnDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>