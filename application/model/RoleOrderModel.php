<?php
/**
* 订单表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:19:56Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\lib\Img;
use app\lib\Model;

class RoleOrderModel {

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_customerid 	= null;

	protected $_custName 	= null;

	protected $_actualfreight 	= null;

	protected $_productcount 	= null;

	protected $_productamount 	= null;

	protected $_bullamount 	= null;

	protected $_totalamount 	= null;

	protected $_addtime 	= null;

	protected $_orderstatus 	= null;

	protected $_businessid = null;

	protected $_businessname = null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('RoleOrder');
	}


	//开启事务
	public function startTrans(){
		return $this->_modelObj->startTrans();
	}

	//提交事务
	public function commit(){
		return $this->_modelObj->commit();
	}

	//事务回滚
	public function rollback(){
		return $this->_modelObj->rollback();
	}

	/**
	 * 生成订单编号
	 * @Author   zhuangqm
	 * @DateTime 2017-03-03T16:30:47+0800
	 * @return   [type]                   [description]
	 */
	public function getOrderNo(){
		return "NNHLP".date("YmdHis").rand(100000,999999);
	}

	/**
	 *
	 * 添加订单表
	 */
	public function insert($data) {
		return $this->_modelObj->insert($data);
	}

	/*
	判断订单是否已支付
	 */
	public function checkorderpay($orderno){
		$row = $this->getByNo($orderno,"orderstatus");

		if($row['orderstatus']==0)
			return false;
		else
			return true;
	}


	/**
	 *
	 * 更新订单表
	 */
	public function modify($updateData,$where) {
		/*
		$this->_modelObj->_orderno  = $this->_orderno;
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_custName  = $this->_custName;
		$this->_modelObj->_actualfreight  = $this->_actualfreight;
		$this->_modelObj->_productcount  = $this->_productcount;
		$this->_modelObj->_productamount  = $this->_productamount;
		$this->_modelObj->_bullamount  = $this->_bullamount;
		$this->_modelObj->_totalamount  = $this->_totalamount;
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_businessname  = $this->_businessname;
		return $this->_modelObj->modify($id);
		*/
		
		return $this->_modelObj->update($updateData,$where);
	}


	/**
	 *
	 * 详细
	 */
	public function getById($id = null,$field="*") {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getRow(["id"=>$id],$field);
		return $this->_dataInfo;
	}

	public function getByNo($orderno,$field="*") {
		return $this->_modelObj->getRow(["orderno"=>$orderno],$field);
	}

	 /*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
        return $this->_modelObj->getRow($where,$field,$order,$otherstr='');
    }
    /*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
        return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
    }

    /*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
        return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
    }
    
    public function getPageList($where, $fields="*", $order='') {
        return $this->_modelObj->pageList($where, $fields, $order);
    }
	
//     public function pageList($where, $fields="*", $order='') {
//         return $this->_modelObj->pageList($where, $fields, $order);
//     }

    /*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
        return $this->_modelObj->update($updateData,$where,$limit='');
    }
    /*
    * 删除数据
    */
    public function delete($where,$limit=''){
        return $this->_modelObj->delete($where,$limit);
    }
    
	/**
	 * 获取所有订单表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	/**
	 * 获取订单状态内容
	 * @Author   zhuangqm
	 * @DateTime 2017-03-07T21:43:46+0800
	 * @param    [type]                   $orderstatus [description]
	 * @return   [type]                                [description]
	 */
	public function getStatusStr($orderstatus){
	
		$orderstatusarr = [
			"0"=>"待付款",
			"1"=>"商家未发货",
			"2"=>"商家已发货",
			"3"=>"交易成功",
			"4"=>"交易完结",
			"5"=>"交易关闭",
		];

		return $orderstatusarr[$orderstatus];
	}

	/**
	 *
	 * 删除订单表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 获取订单列表
	 * @Author   zhuangqm
	 * @DateTime 2017-03-04T16:27:22+0800
	 * @param    [type]                   $customerid    [用户ID]
	 * @param    [type]                   $orderlisttype [订单列表类型1全部2待付款3待发货4待收货5待评价]
	 * @return   [type]                                  [订单列表]
	 */
	public function getOrderList($customerid,$orderlisttype){
		/*$where = ["customerid"=>$customerid];

		//订单状态订单状态0待付款1已付款待发货2已发货3确认收货4订单完结5取消
		switch($orderlisttype){
			case 1: //1全部
				//$where['orderstatus'] = 0;
				break;
			case 2: //2待付款
				$where['orderstatus'] = 0;
				break;
			case 3: //3待发货
				$where['orderstatus'] = 1;
				break;
			case 4: //4待收货
				$where['orderstatus'] = 2;
				break;
			case 5: //5待评价
				$where['orderstatus'] = ["in","3,4"];
				$where['evaluate'] = -1;
				break;
		}
		
		
		$list = $this->_modelObj->pageList($where,"id,orderno,actualfreight,productcount,productamount,bullamount,totalamount,addtime,orderstatus,businessid,businessname,evaluate","addtime desc");
		$total     = $list['total'];
		$orderlist = $list['list'];
		unset($list);

		$OrdOrderInfoOBJ = new OrdOrderInfoModel();
		$OrdOrderItemOBJ = new OrdOrderItemModel();
		$logisticsOBJ    = new OrdOrderLogisticsModel();
		//获取订单明细
		foreach($orderlist as $key=>$order){

			$orderlist[$key]['orderstatus_str'] = $this->getStatusStr($order['orderstatus']);

			$order['actualfreight'] = DePrice($order['actualfreight']);
			$order['productamount'] = DePrice($order['productamount']);
			$order['bullamount'] = DePrice($order['bullamount']);
			$order['totalamount'] = DePrice($order['totalamount']);

			//echo "--------".DePrice($order['productamount'])."-------";

			$orderinfo = $OrdOrderInfoOBJ->getOrdOrderInfo($order['id']);

			$orderlist[$key] = array_merge($order,$orderinfo);

			$orderlist[$key]['orderact'] = $this->getOrderStatus($order['orderstatus'],[
					"evaluate"=>$order['evaluate'],
					"cancelsource"=>$orderinfo['cancelsource'],
					"islongdate"=>$orderinfo['islongdate'],
					"return_status"=>$orderinfo['return_status'],
				]);

			$orderitem_list = $OrdOrderItemOBJ->getList([
					"orderid"=>$order['id'],
				],"productid,productname,productnum,thumb,prouctprice,bullamount,skudetail,skuid","id asc");

			foreach($orderitem_list as $k=>$v){
				$orderitem_list[$k]['thumb'] = Img::url($v['thumb']);

				$orderitem_list[$k]['prouctprice'] = DePrice($v['prouctprice']);
				$orderitem_list[$k]['bullamount'] = DePrice($v['bullamount']);
			}

			$orderlist[$key]['orderitem'] = $orderitem_list;

			//物流信息
			//获取订单物流
            $logistics = $logisticsOBJ->getRow(["orderno"=>$order['orderno']],"express_name,express_no");

            //收货地址信息
            $orderlist[$key]['receipt_address'] = !empty($logistics)?$logistics:[
                                                            "logisticsid"=>"",
                                                            "mobile"=>"",
                                                            "realname"=>"",
                                                            "city_id"=>"",
                                                            "city"=>"",
                                                            "address"=>"",
                                                            "express_name"=>"",
                                                            "express_no"=>"",
                                                        ];

			unset($orderlist[$key]['id']);
		}
		
		return [
			"total"=>$total,
			"list"=>$orderlist,
		];*/
	}

	/**
	* @user 获取商家订单列表
	* @param 
	* @author jeeluo
	* @date 2017年3月27日上午11:11:32
	*/
	public function getBusOrderList($businessid, $orderlisttype) {
	    /*$where = ["businessid"=>$businessid];

		//订单状态订单状态1未付款2待发货3已完成
		switch($orderlisttype){
			case 1: //1全部
				$where['orderstatus'] = 0;
				break;
			case 2: //2待付款
				$where['orderstatus'] = 1;
				break;
			case 3: //3待发货
				$where['orderstatus'] = ["in","3,4"];
				break;
// 			case 4: //4待收货
// 				$where['orderstatus'] = 2;
// 				break;
		}
		
		
		$list = $this->_modelObj->pageList($where,"id,orderno,actualfreight,productcount,productamount,bullamount,totalamount,addtime,orderstatus,businessid,businessname,evaluate","addtime desc");
		$total     = $list['total'];
		$orderlist = $list['list'];
		unset($list);

		$OrdOrderInfoOBJ = new OrdOrderInfoModel();
		$OrdOrderItemOBJ = new OrdOrderItemModel();
		$logisticsOBJ    = new OrdOrderLogisticsModel();
		//获取订单明细
		foreach($orderlist as $key=>$order){

			$orderlist[$key]['orderstatus_str'] = $this->getStatusStr($order['orderstatus']);

			$order['actualfreight'] = DePrice($order['actualfreight']);
			$order['productamount'] = DePrice($order['productamount']);
			$order['bullamount'] = DePrice($order['bullamount']);
			$order['totalamount'] = DePrice($order['totalamount']);

			//echo "--------".DePrice($order['productamount'])."-------";

			$orderinfo = $OrdOrderInfoOBJ->getOrdOrderInfo($order['id']);

			$orderlist[$key] = array_merge($order,$orderinfo);

// 			$orderlist[$key]['orderact'] = $this->getOrderStatus($order['orderstatus'],[
// 					"evaluate"=>$order['evaluate'],
// 					"cancelsource"=>$orderinfo['cancelsource'],
// 					"islongdate"=>$orderinfo['islongdate'],
// 					"return_status"=>$orderinfo['return_status'],
// 				]);
			
			$orderlist[$key]['orderact'][0] = array("act" => "", "acttype" => "", "actname" => "");

			$orderitem_list = $OrdOrderItemOBJ->getList([
					"orderid"=>$order['id'],
				],"productid,productname,productnum,thumb,prouctprice,bullamount,skudetail,skuid","id asc");

			foreach($orderitem_list as $k=>$v){
				$orderitem_list[$k]['thumb'] = Img::url($v['thumb']);

				$orderitem_list[$k]['prouctprice'] = DePrice($v['prouctprice']);
				$orderitem_list[$k]['bullamount'] = DePrice($v['bullamount']);
			}

			$orderlist[$key]['orderitem'] = $orderitem_list;

			//物流信息
			//获取订单物流
            $logistics = $logisticsOBJ->getRow(["orderno"=>$order['orderno']],"express_name,express_no");

            //收货地址信息
            $orderlist[$key]['receipt_address'] = !empty($logistics)?$logistics:[
                                                            "logisticsid"=>"",
                                                            "mobile"=>"",
                                                            "realname"=>"",
                                                            "city_id"=>"",
                                                            "city"=>"",
                                                            "address"=>"",
                                                            "express_name"=>"",
                                                            "express_no"=>"",
                                                        ];

			unset($orderlist[$key]['id']);
		}
		
		return [
			"total"=>$total,
			"list"=>$orderlist,
		];*/
	}

	/**
	 * 根据订单状态，给出订单操作按钮
	 * @Author   zhuangqm
	 * @DateTime 2017-03-06T14:39:25+0800
	 * @param    [type]                   $orderstatus [订单状态0待付款1已付款待发货2已发货3确认收货4订单完结5取消]
	 * @param    [type]                   $param [数组，附加参数
	 *                                           cancelsource 取消来源1消费者2后台管理人员
	 *                                           evaluate 评价状态
	 *                                           islongdate 延长收货
	 *                                           return_status 退款状态，0无退款1退款中2退款完成
	 * 										]
	 * @return   [type]                   [description]
	 */
	public function getOrderStatus($orderstatus,$param){
		/*
		0待付款---待收款
		1已付款待发货---待发货
		2已发货---待收货
		3已确认确认收货 --交易成功
		  evaluate-是否已评价
		4订单完结 --已完结
		5取消', ---订单关闭
		 */
		/*
		act说明：1表示操作按钮2显示文字
		acttype说明：
		1 付款
		2 取消订单
		3 提醒商家发货
		4 退款--申请退款
		5 取消退款
		6 延长收货
		7 查看物流
		8 确认收货
		9 评价
		10 售后
		11 删除订单
		 */
		
		$act = [];
		switch ($orderstatus) {
			case '0':
				$act[] = ["act"=>"1","acttype"=>"1","actname"=>"付款"];
				if($param['cancelsource']>0)
					$act[] = ["act"=>"2","acttype"=>"2","actname"=>"取消订单"];
				else
					$act[] = ["act"=>"1","acttype"=>"2","actname"=>"取消订单"];
				break;
			case '1':
				/*
				if($param['return_status']==2)
					$act[] = ["act"=>"2","acttype"=>"4","actname"=>"退款中"];
				else if($param['return_status']==1)
					$act[] = ["act"=>"1","acttype"=>"5","actname"=>"取消退款"];
				else
					$act[] = ["act"=>"1","acttype"=>"4","actname"=>"退款"];
				*/
				$act[] = ["act"=>"1","acttype"=>"3","actname"=>"提醒发货"];
				break;
			case '2':
				/*
				if($param['return_status']==2)
					$act[] = ["act"=>"2","acttype"=>"4","actname"=>"退款中"];
				else if($param['return_status']==1)
					$act[] = ["act"=>"1","acttype"=>"5","actname"=>"取消退款"];
				else
					$act[] = ["act"=>"1","acttype"=>"4","actname"=>"退款"];
				*/
				$act[] = ["act"=>"1","acttype"=>"8","actname"=>"确认收货"];
				$act[] = ["act"=>"1","acttype"=>"7","actname"=>"查看物流"];
				if($param['islongdate']>0)
					$act[] = ["act"=>"2","acttype"=>"6","actname"=>"已延长收货"];
				else
					$act[] = ["act"=>"1","acttype"=>"6","actname"=>"延长收货"];
				break;
			case '3':
				/*
				if($param['evaluate']>0)
					$act[] = ["act"=>"2","acttype"=>"9","actname"=>"已评价"];
				else
					$act[] = ["act"=>"1","acttype"=>"9","actname"=>"评价"];
				*/
				$act[] = ["act"=>"1","acttype"=>"7","actname"=>"查看物流"];
				
				//$act[] = ["act"=>"1","acttype"=>"10","actname"=>"售后"];
	
				break;
			case '4':
				/*
				if($param['evaluate']>0)
					$act[] = ["act"=>"2","acttype"=>"9","actname"=>"已评价"];
				else
					$act[] = ["act"=>"1","acttype"=>"9","actname"=>"评价"];
				*/
				$act[] = ["act"=>"1","acttype"=>"7","actname"=>"查看物流"];
				$act[] = ["act"=>"1","acttype"=>"11","actname"=>"删除订单"];
				break;
			case '5':
				/*
				if($param['evaluate']>0)
					$act[] = ["act"=>"2","acttype"=>"9","actname"=>"已评价"];
				else
					$act[] = ["act"=>"1","acttype"=>"9","actname"=>"评价"];
				*/
				$act[] = ["act"=>"1","acttype"=>"11","actname"=>"删除订单"];
				break;
		}

		return $act;

	}

	/**
     * 获取评价操作状态
     * @Author   zhuangqm
     * @DateTime 2017-03-15T15:01:21+0800
     * @param    [type]                   $evaluate_status [description]
     * @return   [type]                                    [description]
     */
    public function getEvaluateAct($evaluate_status){
        $act = [];
        if($evaluate_status==0){
            $act = ["act"=>"1","acttype"=>"9","actname"=>"评价"];
        }else{
        	$act = ["act"=>"2","acttype"=>"9","actname"=>"已评价"];
        }
        return $act;
    }

	/**
	 * 获取订单详情
	 * @Author   zhuangqm
	 * @DateTime 2017-03-06T11:28:30+0800
	 * @param    [type]                   $orderno [description]
	 * @return   [type]                            [description]
	 */
	public function getInfoByOrderNo($orderno,$field="*"){
		$item = $this->_modelObj->getRow(['orderno'=>$orderno],$field);
		return $item;
	}

	/**
	 * 获取订单状态信息
	 * @Author   zhuangqm
	 * @Editor jeeluo 添加关闭原因 2017-04-20 15:39:53
	 * @param    [type]                   $param [
	 *                                           "orderstatus" 订单状态
	 *                                           "addtime"  订单提交时间
	 *                                           "cancelreason" 关闭原因
	 * 									]
	 * @return   [type]                   [description]
	 */
	public function getOrderStatusInfo($param){
		$statusstr = $this->getStatusStr($param['orderstatus']);

		$statusinfo = '';
		//订单状态0待付款1已付款待发货2已发货3确认收货4订单完结5取消
		switch($param['orderstatus']){
			case 0:
				//2小时未付款，自动关闭订单
				$timedef = (time()-strtotime($param['addtime']));
				$timelong = 2*3600;
				if($timedef>$timelong){
					//订单取消
					$statusstr = $this->getStatusStr(5);
				}else{
					$diff 	= $timelong-$timedef;
					$hour 	= floor($diff/3600);
					$min 	= floor(($diff%3600)/60);
					$statusinfo = "剩余".$hour."小时".$min."分自动关闭";
				}
				break;
			case 1:
				break;
			case 2:
				$timedef = (time()-strtotime($param['addtime']));
				$timelong = 15*24*3600;
				
				if($timedef>$timelong){
					//订单取消
					$statusstr = $this->getStatusStr(4);
				}else{
					$diff 	= $timelong-$timedef;
					$day    = floor($diff/(24*3600));
					$hour 	= floor(($diff%(24*3600))/3600);
					$statusinfo = "剩余".$day."天".$hour."小时自动确认收货";
				}
				break;
			case 3:
				break;
			case 4:
				break;
			case 5:
// 				$statusinfo = "我不想买了";
                $statusinfo = $param['cancelreason'];
				break;
		}

		return [
			"statusstr"=>$statusstr,
			"statusinfo"=>$statusinfo,
		];
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
	 * 设置订单号
	 *
	 */
	public function setOrderno($orderno) {
		$this->_orderno = $orderno;
		return $this;
	}

	/**
	 * 设置下单用户ID
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
	public function setCustName($custName) {
		$this->_custName = $custName;
		return $this;
	}

	/**
	 * 设置实际运费
	 *
	 */
	public function setActualfreight($actualfreight) {
		$this->_actualfreight = $actualfreight;
		return $this;
	}

	/**
	 * 设置商品总数量
	 *
	 */
	public function setProductcount($productcount) {
		$this->_productcount = $productcount;
		return $this;
	}

	/**
	 * 设置商品总金额
	 *
	 */
	public function setProductamount($productamount) {
		$this->_productamount = $productamount;
		return $this;
	}

	/**
	 * 设置商品总牛币数
	 *
	 */
	public function setBullamount($bullamount) {
		$this->_bullamount = $bullamount;
		return $this;
	}

	/**
	 * 设置订单总金额=实际运费+商品总金额
	 *
	 */
	public function setTotalamount($totalamount) {
		$this->_totalamount = $totalamount;
		return $this;
	}

	/**
	 * 设置订单添加时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	/**
	 * 设置订单状态0待付款1已付款待发货2已发货3部分发货4订单完结5确认收货6取消
	 *
	 */
	public function setOrderstatus($orderstatus) {
		$this->_orderstatus = $orderstatus;
		return $this;
	}

	public static function getModelObj() {
		return new OrdOrderDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}

	
}
?>