<?php
namespace app\model\Order;

use app\model\Pay\PayModel;

use think\Config;

use app\lib\Img;

use app\model\OrdUserLogisticsModel;
use app\model\ProProductModel;
use app\model\ProProductSpecModel;
use app\model\OrdOrderModel;
use app\model\OrdOrderDeleteModel;
use app\model\CusCustomerInfoModel;
use app\model\OrdOrderInfoModel;
use app\model\OrdOrderItemModel;
use app\model\OrdOrderPayModel;
use app\model\BusBusinessModel;
use app\model\OrdOrderLogisticsModel;
use app\model\OrdShoppingcartModel;
use app\model\OrdShoppingcartItemModel;
use app\model\OrdOrderReturnModel;
use app\model\Order\TransportModel;
use app\model\Amount\ProfitModel;
use app\model\Business\BusinessModel;

use app\lib\Model;

use app\lib\Log;
use app\model\Sys\CommonModel;

class OrderModel
{
    /**
     * 提价订单前的订单详情
     * @Author   zhuangqm
     * @DateTime 2017-03-07T10:21:29+0800
     * @param    [type]                   $param [
     *                                           customerid
     *                                           cartitemids
     *                                           skuids
     *                                           productnum
     *                                     ]
     * @return   [type]                          [description]
     */
    public function showorder($param){
        $customerid     = $param['customerid'];
        $cartitemids    = $param['cartitemids'];
        $skuid          = $param['skuid'];
        $productnum     = $param['productnum'];
        $logisticsid    = $param['logisticsid'];

        $proOBJ              = new ProProductModel();
        $skuOBJ              = new ProProductSpecModel();
        $ShoppingCartItemOBJ = new OrdShoppingcartItemModel();
        $businessOBJ         = new BusBusinessModel();
        $userlogisticsOBJ    = new OrdUserLogisticsModel();
        $transportOBJ        = new TransportModel();

        if(!empty($logisticsid)){
            $loginstics = $userlogisticsOBJ->getUserDefaultLogistics(['logisticsid'=>$logisticsid]);
        }else{
            //获取用户默认物流信息
            $loginstics = $userlogisticsOBJ->getUserDefaultLogistics(['customerid'=>$customerid]);
        }
        // if(empty($loginstics))
        //         return ["code"=>'6002'];

        $result = [];
        //通过skuid查询相关商品
        $idarr = [];

        if(!empty($skuid))
            $idarr = explode(",",$skuid);
        else if(!empty($cartitemids))
            $idarr = explode(",",$cartitemids);
        

        $totalamount        = 0; //总价格
        $totalbull          = 0; //总牛币
        $totalcount         = 0; //总数量
        $totalactualfreight = 0;//总运费

        $orderlist = [];
        $business = [];
        
        foreach($idarr as $id){

            if($id>0){

                if(!empty($skuid)){
                    
                    //获取sku信息
                    $skuitem = $skuOBJ->getRow(["id"=>$id],"id as skuid,sku,businessid,productid,productname,prouctprice,bullamount,productstorage,productimage,spec");

                    $productid = $skuitem['productid'];
                    $cartitem = [
                        "cartid"=>0,
                        "customerid"=>$customerid,
                        "businessid"=>$skuitem['businessid'],
                        "productid"=>$skuitem['productid'],
                        "productnum"=>$productnum,
                        "bullamount"=>DePrice($skuitem['bullamount']),
                        "skuid"=>$id,
                    ];

                }else if(!empty($cartitemids)){
                    
                    $cartitem = $ShoppingCartItemOBJ->getRow(["id"=>$id],"id as cartid,customerid,businessid,productid,productnum,skuid");

                    /*if($cartitem['customerid']!=$customerid)
                        return ['code'=>"1001"];*/

                    //$skuid = $cartitem['skuid'];
                    $productnum = $cartitem['productnum'];

                    //获取sku信息
                    $skuitem = $skuOBJ->getRow(["id"=>$cartitem['skuid']],"id as skuid,sku,businessid,productid,productname,prouctprice,bullamount,productstorage,productimage,spec");

                    $productid = $cartitem['productid'];

                    $cartitem['bullamount'] = DePrice($skuitem['bullamount']);
                }

                $skuitem['prouctprice'] = DePrice($skuitem['prouctprice']);
                $skuitem['bullamount']  = DePrice($skuitem['bullamount']);

                $productinfo = $proOBJ->getRow(["id"=>$productid],"businessid,enable,spu,categoryid,categoryname,weight,weight_gross,volume");

                if($productinfo['enable']==0)
                    return ['code'=>"5006"];

                
                if(!empty($skuitem)){

                    if($skuitem['productstorage']>=$productnum){

                        if($productnum>0){

                            $skuitem['productimage'] = Img::url($skuitem['productimage']);
                            
                            //获取商品信息
                            //$proitem = $proOBJ->getRow(["id"=>$cartitem['productid']],"spu,categoryid,categoryname,weight,weight_gross,volume");
                            $orderitem = array_merge($cartitem,$productinfo);
                           
                            //获取商家信息
                            $businessitem = $businessOBJ->getById($orderitem['businessid'],"businessname");
                            $orderitem['businessname'] = $businessitem['businessname'];
                            $business[$orderitem['businessid']] = $businessitem['businessname'];
                            $orderlist[$orderitem['businessid']]['productlist'][] = array_merge($skuitem,$orderitem);

                            $totalamount+=$skuitem['prouctprice']*$orderitem['productnum'];
                            $totalbull+=$skuitem['bullamount']*$orderitem['productnum'];
                            $totalcount+=$orderitem['productnum'];

                        }
                    }else{
                        return [
                            "code"=>6001, //库存不足
                        ];
                    }

                }
            }
        }

        
        $businessorder = [];
        if(!empty($orderlist)){

            foreach($orderlist as $businessid=>$businessvalue){
                $tmp            = [];
                $productnum     = 0; //商品总数量
                $productamount  = 0; //商品总价格
                $bullamount     = 0; //商品总牛币

                $weight         = 0; //同家店铺商品总重量
                $weight_gross   = 0; //同家店铺商品总毛重
                $volume         = 0; //同家店铺商品总体积

                $transport      = [];
                foreach($businessvalue['productlist'] as $key=>$value){
                    $productnum+=$value['productnum'];
                    $productamount+=$value['prouctprice']*$value['productnum'];
                    $bullamount+=$value['bullamount']*$value['productnum'];

                    $weight+=$value['productnum']*$value['weight'];
                    $weight_gross+=$value['productnum']*$value['weight_gross'];
                    $volume+=$value['productnum']*$value['volume'];

                    $skudetail = "";
                    if(!empty($value['spec'])){
                        $sku_spec = json_decode($value['spec'],true);
                        
                        foreach($sku_spec as $v){
                            $skudetail.=$v['name'].":".$v['value'].";";
                        }
                    }
                    $businessvalue['productlist'][$key]['skudetail'] = $skudetail;
                }

                //计算订单运费
                $actualfreight  =   $transportOBJ->getFreight([
                        "businessid"=>$businessid,
                        "city_id"=>$loginstics['city_id'],
                        "productnum"=>$productnum,
                        "weight"=>$weight,
                        "weight_gross"=>$weight_gross,
                        "volume"=>$volume,
                    ]);
                $totalactualfreight+=$actualfreight;
                
                $tmp['businessid']           = $businessid;
                $tmp['businessname']         = $business[$businessid];
                $tmp['actualfreight']        = $actualfreight;
                $tmp['productnum']           = $productnum;
                $tmp['productamount']        = ForMatPrice($productamount);
                $tmp['bullamount']           = ForMatPrice($bullamount);
                $tmp['productlist']          = $businessvalue['productlist'];

                $businessorder[] = $tmp;
            }
                
        }

        $totalamount            = ForMatPrice($totalamount);
        $totalbull              = ForMatPrice($totalbull);
        $totalactualfreight     = ForMatPrice($totalactualfreight);
        $total                  = ForMatPrice($totalamount+$totalactualfreight);
        return [
            "code"=>"200",
            "data"=>[
                "loginstics"=>$loginstics,
                "totalamount"=>$totalamount,
                "totalbull"=>$totalbull,
                "totalcount"=>$totalcount,
                "totalactualfreight"=>$totalactualfreight,
                "total"=>$total,
                "orderlist"=>$businessorder,
            ],
        ];
    }
    
    /**
     * 添加订单
     * @Author   zhuangqm
     * @DateTime 2017-03-03T12:02:13+0800
     * @param    [type]                   $param array(
     *                                           userid
     *                                           sign
     *                                           address_id
     *                                           items
     *                                     )
     * @return   [type]                          [description]
     */
    public function addorder($param){
        $userid = $param['userid'];
        unset($param['userid']);

        $sign = $param['sign'];
        unset($param['sign']);

        $address_id = $param['address_id'];
        $items = $param['items'];

        $userlogisticsOBJ   = new OrdUserLogisticsModel();
        //获取物流信息
        $loginstics = $userlogisticsOBJ->getById($address_id);

        /**
         * 先校验签名是否合法
         */
        if($this->chec_addorder_sign($sign,$param)){

            
            if(empty($loginstics))
                return ["code"=>'6002'];

            $proOBJ             = new ProProductModel();
            $skuOBJ             = new ProProductSpecModel();
            $orderOBJ           = new OrdOrderModel();
            $orderinfoOBJ       = new OrdOrderInfoModel();
            $orderitemOBJ       = new OrdOrderItemModel();
            $cusinfoOBJ         = new CusCustomerInfoModel();
            $businessOBJ        = new BusBusinessModel();
            $logisticsOBJ       = new OrdOrderLogisticsModel();
            $transportOBJ       = new TransportModel();
            $ShoppingcartOBJ    = new OrdShoppingcartItemModel();

            //用户信息
            $cusinfo    = $cusinfoOBJ->getById($userid,"nickname");

            $totalamount = 0; //总价格
            $bullamount  = 0; //总牛币
            $totalcount  = 0; //总数量


            $total_actualfreight = 0;
            $total_productnum = 0;
            $total_productamount = 0;
            $total_bullamount = 0;
            $total_totalamount = 0;

            $business = [];

            $orderlist = [];

            $cartidarr = [];
            //print_r($items);
            $items_arr = json_decode($items,true);
            //print_r($items_arr);
            $orderidstr = "";
            foreach($items_arr as $orderitem){
                /**
                    productid
                    skuid
                    productnum
                    cartid
                 */
                
                if(!empty($orderitem)){
                    //获取商品信息
                    //$proitem = $proOBJ->getById($orderitem['productid'],"businessid");

                    //获取sku信息
                    $skuitem = $skuOBJ->getRow(["id"=>$orderitem['skuid']],"id as skuid,sku,businessid,productid,productname,prouctprice,bullamount,productstorage,productimage,spec,saleprice,supplyprice");
                    
                    if(!empty($skuitem)){

                        if($skuitem['productstorage']>=$orderitem['productnum']){

                            if($orderitem['productnum']>0){
                                
                                //获取商品信息
                                $proitem = $proOBJ->getRow(["id"=>$orderitem['productid']],"spu,businessid,categoryid,categoryname,weight,weight_gross,volume");
                                $orderitem = array_merge($orderitem,$proitem);

                                //获取商家信息
                                $businessitem = $businessOBJ->getById($orderitem['businessid'],"businessname");
                                $orderitem['businessname'] = $businessitem['businessname'];

                                $business[$orderitem['businessid']] = $businessitem['businessname'];

                                $orderlist[$orderitem['businessid']][] = array_merge($skuitem,$orderitem);

                                $totalamount+=$skuitem['prouctprice']*$orderitem['productnum'];
                                $bullamount+=$orderitem['bullamount'];
                                $totalcount+=$orderitem['productnum'];
                            }
                        }else{
                            return [
                                "code"=>6001, //库存不足
                            ];
                        }

                        $cartidarr[] = $orderitem['cartid'];

                    }
                }
            
            }
            //exit;
            //
            $orderOBJ->startTrans();

            try{
                
                //按商家进行拆分订单--生成多个订单
                if(!empty($orderlist)){
                    $addtime = date("Y-m-d H:i:s");
                    
                    foreach($orderlist as $businessid=>$businessvalue){

                        $skuitem_productstorage = []; //sku库存处理

                        $orderno        = $orderOBJ->getOrderNo();
                        $productamount  = 0; //商品总价格
                        $bullamount     = 0; //商品总牛币
                        $productnum     = 0; //商品总数量

                        $weight         = 0; //同家店铺商品总重量
                        $weight_gross   = 0; //同家店铺商品总毛重
                        $volume         = 0; //同家店铺商品总体积

                        $transport      = [];
                        foreach($businessvalue as $value){
                            $productnum+=$value['productnum'];
                            $productamount+=$value['prouctprice']*$value['productnum'];
                            $bullamount+=$value['bullamount']*$value['productnum'];

                            $weight+=$value['productnum']*$value['weight'];
                            $weight_gross+=$value['productnum']*$value['weight_gross'];
                            $volume+=$value['productnum']*$value['volume'];

                            $transport[] = [
                                "businessid"=>$businessid,
                                "productid"=>$value['productid'],
                                "productnum"=>$value['productnum'],
                                "weight"=>$value['productnum']*$value['weight'],
                                "weight_gross"=>$value['productnum']*$value['weight_gross'],
                                "volume"=>$value['productnum']*$value['volume'],
                            ];
                        }
                        
                        //计算订单运费
                        $actualfreight  =   $transportOBJ->getFreight([
                                "businessid"=>$businessid,
                                "city_id"=>$loginstics['city_id'],
                                "productnum"=>$productnum,
                                "weight"=>$weight,
                                "weight_gross"=>$weight_gross,
                                "volume"=>$volume,
                            ]);
                        $actualfreight = EnPrice($actualfreight);
                        
                        $totalamount    = $productamount+$actualfreight;
                        //echo "-------1111----";
                        //生成订单
                        $orderno = $orderOBJ->getOrderNo();
                        $data = [
                            "orderno"=>$orderno,
                            "customerid"=>$userid,
                            "cust_name"=>$cusinfo['nickname'],
                            "actualfreight"=>$actualfreight,
                            "productcount"=>$productnum,
                            "productamount"=>$productamount,
                            "bullamount"=>$bullamount,
                            "totalamount"=>$totalamount,
                            "addtime"=>$addtime,
                            "orderstatus"=>"0",
                            "businessid"=>$businessid,
                            "businessname"=>$business[$businessid],
                        ];
                        
                        $orderid = $orderOBJ->insert($data);
                        $orderidstr.=$orderno.",";


                        $total_actualfreight+=$actualfreight;
                        $total_productnum+=$productnum;
                        $total_productamount+=$productamount;
                        $total_bullamount+=$bullamount;
                        $total_totalamount+=$totalamount;

                        //echo "-------$orderidstr----";
                        //生成订单详情记录
                        $orderinfoOBJ->add([
                                "id"=>$orderid,
                                "orderno"=>$orderno,
                                "customerid"=>$userid,
                            ]);

                        foreach($businessvalue as $value){
                            $skudetail = "";
                            if(!empty($value['spec'])){
                                $sku_spec = json_decode($value['spec'],true);
                                
                                foreach($sku_spec as $v){
                                    $skudetail.=$v['name'].":".$v['value'].";";
                                }
                            }
                            //生成订单明细
                            $data = [
                                "orderid"=>$orderid,
                                "orderno"=>$orderno,
                                "businessid"=>$value['businessid'],
                                "businessname"=>$value['businessname'],
                                "productid"=>$value['productid'],
                                "productname"=>$value['productname'],
                                "productcode"=>$value['spu'],
                                "productnum"=>$value['productnum'],
                                "categoryid"=>$value['categoryid'],
                                "categoryname"=>$value['categoryname'],
                                "skuid"=>$value['skuid'],
                                "skucode"=>$value['sku'],
                                "skudetail"=>($skudetail!=''?substr($skudetail,0,-1):""),
                                "thumb"=>$value['productimage'],
                                "prouctprice"=>$value['prouctprice'],
                                "bullamount"=>$value['bullamount'],
                                "addtime"=>$addtime,
                                "remark"=>$remark,
                                "supplyprice"=>$value['supplyprice'],
                                "saleprice"=>$value['saleprice'],
                            ];
                            
                            $orderitemOBJ->insert($data);
                            
                            $skuitem_productstorage[$value['skuid']] = $value['productnum'];
                        }
                      

                        //订单添加物流信息
                        $data = [
                            "orderno"=>$orderno,
                            "mobile"=>$loginstics['mobile'],
                            "realname"=>$loginstics['realname'],
                            "city_id"=>$loginstics['city_id'],
                            "city"=>$loginstics['city'],
                            "address"=>$loginstics['address'],
                        ];
                        $logisticsOBJ->add($data);

                        //扣减库存
                        foreach($skuitem_productstorage as $k=>$v){
                            $skuOBJ->update("productstorage=productstorage-".intval($v),["id"=>$k]);
                        }


                        //累加商品--待付款订单
                        // Model::new("Order.OrderCount")->addCount($userid,"count_pay");

                        
                        /*if($businessvalue['cartid']>0){
                            $ShoppingcartOBJ->del($businessvalue['cartid']);
                        }*/
                    }
                }

                $orderidstr = $orderidstr!=''?substr($orderidstr,0,-1):"";

                //删除购物车记录
                foreach($cartidarr as $cartid){
                    if($cartid>0){
                        $cartitem   = $ShoppingcartOBJ->getRow(["id"=>$cartid],"customerid,businessid");
                        $ShoppingcartOBJ->delete(["id"=>$cartid]);
                        $cartcount  = $ShoppingcartOBJ->getRow(["businessid"=>$cartitem['businessid'],"customerid"=>$cartitem['customerid']],"count(*) as count");
                        if($cartcount["count"]==0){
                            Model::ins("OrdShoppingcart")->delete(["businessid"=>$cartitem['businessid'],"customerid"=>$cartitem['customerid']]);
                        }
                    }
                }
                

                $orderarr = explode(",",$orderidstr);

                
                if(count($orderarr)>1){
                    $ordermoreOBJ = Model::ins("OrdOrderMore");

                    $orderno_more = $ordermoreOBJ->getOrderNo();
                    
                    $ordermoreOBJ->insert([
                            "orderno"=>$orderno_more,
                            "child_orderno"=>$orderidstr,
                            "customerid"=>$userid,
                            "cust_name"=>$cusinfo['nickname'],
                            "actualfreight"=>$total_actualfreight,
                            "productcount"=>$total_productnum,
                            "productamount"=>$total_productamount,
                            "bullamount"=>$total_bullamount,
                            "totalamount"=>$total_totalamount,
                            "addtime"=>date("Y-m-d H:i:s"),
                        ]);

                    $orderidstr = $orderno_more;
                }

                // 提交事务
                $orderOBJ->commit(); 

                return [
                    "code"=>'200',
                    "orderidstr"=>$orderidstr,
                    "ordercount"=>count($orderarr),
                ];

            } catch (\Exception $e) {
                //print_r($e);
                // 错误日志
                // 回滚事务
                
                $orderOBJ->rollback();

                Log::add($e,__METHOD__);

                return ["code"=>"7007"];
            }
            
        }else{
            return [
                "code"=>404,
            ];
        }

    }

    /**
     * 判断添加订单签名
     * @Author   zhuangqm
     * @DateTime 2017-03-03T14:01:09+0800
     * @param    [type]                   $sign  [签名信息]
     * @param    [type]                   $param [业务数据]
     * @return   [type]                          [true|false]
     */
    protected function chec_addorder_sign($sign,$param){
        
        //按业务字段排序
        ksort($param);

        //签名=md5(按业务字段排序(address_id+items)+私钥)
        $str = '';
        foreach($param as $v){
            $str.=$v;
        }
        $key = Config::get("key.app_key");
        // echo $str.$key."----";
        $check = md5($str.$key);
        // echo $sign."---";
        // echo $check."---";
        // exit;
        if(strtoupper($check)==$sign)
            return true;
        else
            return false;
    }

    /**
     * 订单明细
     * @Author   zhuangqm
     * @DateTime 2017-03-06T11:10:24+0800
     * @param    [type]                   $param
     *                                       customerid [用户ID]
     *                                       orderno [订单编号]
     * @return   [type]                            [description]
     */
    public function orderdetail($param){
        $orderno = $param['orderno'];
        $customerid = $param['customerid'];

        if(!empty($orderno)){
            $orderOBJ           = new OrdOrderModel();
            $OrdOrderInfoOBJ    = new OrdOrderInfoModel();
            $orderitemOBJ       = new OrdOrderItemModel();
            $orderpayOBJ        = new OrdOrderPayModel();
            $logisticsOBJ       = new OrdOrderLogisticsModel();
            $orderreturnOBJ     = new OrdOrderReturnModel();

            $order = $orderOBJ->getInfoByOrderNo($orderno);

            //判断订单是否存在
            if(!empty($order)){

                //判断订单和操作人是否一致
                if($order['customerid']==$customerid){

                    $order['orderstatus_str'] = $orderOBJ->getStatusStr($order['orderstatus']);
                    
                    $order['actualfreight'] = DePrice($order['actualfreight']);
                    $order['productamount'] = DePrice($order['productamount']);
                    $order['bullamount']    = DePrice($order['bullamount']);
                    $order['totalamount']   = DePrice($order['totalamount']);

                    $orderinfo = $OrdOrderInfoOBJ->getOrdOrderInfo($order['id']);
           
                    $order = array_merge($order,$orderinfo);

                    $orderpay = $orderpayOBJ->getRow(["orderno"=>$order['orderno']],"paytime");

                    $order['paytime'] = $orderpay['paytime'];
                    
                    $order['delivertime'] = CommonModel::judgeDefaultTime($order['delivertime']) ? '' : $order['delivertime'];

                    //获取订单的操作
                    $order['orderact'] = $orderOBJ->getOrderStatus($order['orderstatus'],[
                        "evaluate"=>$order['evaluate'],
                        "cancelsource"=>$orderinfo['cancelsource'],
                        "islongdate"=>$orderinfo['islongdate'],
                        "return_status"=>$orderinfo['return_status'],
                    ]);

                    //确认收货时间
                    $delivery_time = !empty($order['actual_delivery_time'])?$order['actual_delivery_time']:$order['auto_delivery_time'];

                    $orderitem_list = $orderitemOBJ->getList([
                                        "orderno"=>$orderno,
                                    ],"skuid,productid,productname,productnum,thumb,prouctprice,bullamount,skudetail,evaluate","id asc");
                    
                    
                    foreach($orderitem_list as $k=>$v){

                        $orderitem_list[$k]['prouctprice']  = DePrice($v['prouctprice']);
                        $orderitem_list[$k]['bullamount']  = DePrice($v['bullamount']);

                        $orderitem_list[$k]['thumb'] = Img::url($v['thumb']);

                        $orderitem_list[$k]['orderact'] = [];

                        if($order['orderstatus']==1 || $order['orderstatus']==2 || ($order['orderstatus']==3 && $delivery_time>=date("Y-m-d H:i:s",strtotime("-7 day")))){
                            //申请退款
                            $orderact = $orderreturnOBJ->getOrderReturnAct([
                                "orderstatus"=>$order['orderstatus'],
                                "orderid"=>$order['id'],
                                "skuid"=>$v['skuid'],
                                "productnum"=>$v['productnum'],
                                ]);
                            $orderitem_list[$k]['orderact'][] = $orderact;
                        }

                        //评价操作
                        if($order['orderstatus']==3 || $order['orderstatus']==4)
                            $orderitem_list[$k]['orderact'][] = $orderOBJ->getEvaluateAct($v['evaluate']);
                    }
                    $order['orderitem'] = $orderitem_list;

                    //获取订单物流
                    $logistics = $logisticsOBJ->getRow(["orderno"=>$orderno],"id as logisticsid,mobile,realname,city_id,city,address,express_name,express_no");

                    //收货地址信息
                    $order['receipt_address'] = !empty($logistics)?$logistics:[
                                                                    "logisticsid"=>"",
                                                                    "mobile"=>"",
                                                                    "realname"=>"",
                                                                    "city_id"=>"",
                                                                    "city"=>"",
                                                                    "address"=>"",
                                                                    "express_name"=>"",
                                                                    "express_no"=>"",
                                                                ];

                    //快递信息
                    $order['logistics'] = [
                        "logisticsid"=>"",
                    ];

                    //获取订单状态
                    $order['orderstatusstr'] = $orderOBJ->getOrderStatusInfo([
                            "orderstatus"=>$order['orderstatus'],
                            "addtime"=>$order['addtime'],
                            "cancelreason"=>$orderinfo["cancelreason"],
                        ]);

                    // 获取供应商电话号码
                    $order['business_tel'] = BusinessModel::getBusinessTel($order['businessid']);

                    $order['finish_time'] = !empty($order['auto_delivery_time'])?$order['auto_delivery_time']:$order['actual_delivery_time'];

                    //订单支付信息
                    $orderpay = Model::ins("OrdOrderPay")->getRow(["orderno"=>$order['orderno']],"paytime");
                    $order['paytime'] = $orderpay['paytime'];

                    if($param['version']=='1.0.0'){
                        $order['totalamount'] = $order['totalamount']-$order['actualfreight'];
                    }

                    return [
                        "code"=>"200",
                        "orderdetail"=>$order,
                    ];

                }else{

                    return [
                        "code"=>"1001",
                        "orderdetail"=>[],
                    ];
                }
            }else{
                return [
                    "code"=>"1000",
                    "orderdetail"=>[],
                ];
            }

        }else{
            return [
                "code"=>"404",
                "orderdetail"=>[],
            ];
        }
    }

    /**
     * [orderItemDetail 单条订单明细]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-06T12:02:49+0800
     * @return   [type]                   [description]
     */
    public function orderItemDetail($param){
       
        $where = $param['where'];
        $fields = $param['fields'];
        $fields = !empty($fields) ? $fields : '*';
        $orderitemOBJ  = new OrdOrderItemModel();
        return $orderitem = $orderitemOBJ->getRow($where,$fields);
         
    }

    /**
     * [orderItemDetailList description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-10T16:22:28+0800
     * @return   [type]                   [description]
     */
    public function orderItemDetailList($param){

        $where = $param['where'];
        $fields = $param['fields'];
        $fields = !empty($fields) ? $fields : '*';
        $orderitemOBJ  = new OrdOrderItemModel();
        return $orderitemlist = $orderitemOBJ->getList($where,$fields);
    }

    /**
     * [orderRowData 订单主表单条信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-06T13:46:45+0800
     * @param    [type]                   $orderno [description]
     * @return   [type]                            [description]
     */
    public function orderRowData($orderno){
        $orderOBJ     = new OrdOrderModel();
        return  $order = $orderOBJ->getRow(['orderno'=>$orderno]);
    }
    
    /**
    * @user 获取个人中心 订单数
    * @param 
    * @author jeeluo
    * @date 2017年4月17日下午5:49:35
    */
    public function orderCountData($customerid) {
        $where['customerid'] = $customerid;
        $where['orderstatus'] = 0;
        
        $ordOrderOBJ = new OrdOrderModel();
        // 待付款个数
        $orderCount['count_pay'] = $ordOrderOBJ->getRow($where, "COUNT(*) as count ")['count'];
        
        // 待发货个数
        $where['orderstatus'] = 1;
        $orderCount['count_deliver'] = $ordOrderOBJ->getRow($where, "COUNT(*) as count ")['count'];
        
        // 待收货个数
        $where['orderstatus'] = 2;
        $orderCount['count_receipt'] = $ordOrderOBJ->getRow($where, "COUNT(*) as count ")['count'];
        
        // 待评论个数
        $where['orderstatus'] = ["in", "3,4"];
        $where['evaluate'] = -1;
        $orderCount['count_evaluate'] = $ordOrderOBJ->getRow($where, "COUNT(*) as count ")['count'];
        
        $orderCount['count_cart'] = 0;
        //　退单数量可能挺多的 暂时为0
        $orderCount['count_return'] = 0;
        
        return $orderCount;
    }


    /**
     * 取消订单
     * @Author   zhuangqm
     * @DateTime 2017-03-06T17:03:33+0800
     * @return   [type]                   [description]
     */
    public function cancelsOrder($param){
        $orderno = $param['orderno'];
        $customerid = $param['customerid'];

        if(!empty($orderno)){
            $orderOBJ           = new OrdOrderModel();
            $OrdOrderInfoOBJ    = new OrdOrderInfoModel();
            $OrdOrderItemOBJ    = new OrdOrderItemModel();
            $skuOBJ             = new ProProductSpecModel();

            $order = $orderOBJ->getInfoByOrderNo($orderno,"id,orderstatus,customerid");

            //判断订单是否存在
            if(!empty($order)){

                //判断订单和操作人是否一致
                if($order['customerid']==$customerid){

                    /*
                    只能在未付款的情况下才能取消订单
                     */
                    if($order['orderstatus']==0){

                        if($orderOBJ->modify(["orderstatus"=>5],["id"=>$order['id']])){

                            $OrdOrderInfoOBJ->modify(["cancelsource"=>1,"cancelreason"=>$param['cancelreason']],["id"=>$order['id']]);

                            //恢复库存
                            $orderitem_list = $OrdOrderItemOBJ->getList(["orderid"=>$order['id']],"id,productnum,skuid");

                            foreach($orderitem_list as $k=>$v){
                                $skuOBJ->update("productstorage=productstorage+".intval($v['productnum']),["id"=>$v['skuid']]);
                            }

                            return ["code"=>"200"];
                        }else{
                            return ["code"=>"400"];
                        }
                    }else{
                        return ["code"=>"7002"];
                    }

                }else{
                    return [
                        "code"=>"1001",
                    ];
                }
            }else{
                return [
                    "code"=>"1000",
                ];
            }

        }else{
            return [
                "code"=>"404",
            ];
        }
    }

    /**
     * 确认订单
     * @Author   zhuangqm
     * @DateTime 2017-03-06T19:20:53+0800
     * @param    [type]                   $param [
     *                                           customerid
     *                                           orderno
     *                                           auto_delivery_flag 自动确认收货标识 1表示自动确认收货
     *                                     ]
     * @return   [type]                          [description]
     */
    public function confirmOrder($param){

        $orderno = $param['orderno'];
        $customerid = $param['customerid'];

        $auto_delivery_flag = $param['auto_delivery_flag']; // 自动确认收货标识

        if(!empty($orderno)){
            $orderOBJ           = new OrdOrderModel();
            $OrdOrderInfoOBJ = new OrdOrderInfoModel();

            $order = $orderOBJ->getInfoByOrderNo($orderno,"id,orderstatus,customerid");

            //判断订单是否存在
            if(!empty($order)){

                //判断订单和操作人是否一致
                if($order['customerid']==$customerid){

                    $confirm_time = date("Y-m-d H:i:s"); // 确认收货时间
                    /*
                    订单状态为2时，才能确认收货
                     */
                    if($order['orderstatus']==2){

                        if($orderOBJ->modify(["orderstatus"=>3,"confirm_time"=>$confirm_time],["id"=>$order['id']])){

                            if($auto_delivery_flag==1){
                                $updateData = ["auto_delivery_time"=>$confirm_time];
                            }else{
                                $updateData = ["actual_delivery_time"=>$confirm_time];
                            }
                            
                            $OrdOrderInfoOBJ->modify($updateData,["id"=>$order['id']]);

                            //分润的钱都归还到用户账户余额中
                            // ProfitModel::returnprofit([
                            //         "userid"=>$order['customerid'],
                            //         "orderno"=>$orderno,
                            //     ]);

                            // Model::new("Order.OrderCount")->deCount($order['customerid'],"count_receipt");
                            // Model::new("Order.OrderCount")->addCount($userid,"count_evaluate");

                            return ["code"=>"200"];
                        }else{
                            return ["code"=>"400"];
                        }
                    }else{
                        return ["code"=>"7001"];
                    }

                }else{
                    return [
                        "code"=>"1001",
                    ];
                }
            }else{
                return [
                    "code"=>"1000",
                ];
            }

        }else{
            return [
                "code"=>"404",
            ];
        }
    }

    /**
     * 订单完结
     * @Author   zhuangqm
     * @DateTime 2017-04-11T22:08:34+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function closeorder($param){
        $orderno = $param['orderno'];
        $customerid = $param['customerid'];

        if(!empty($orderno)){
            $orderOBJ           = new OrdOrderModel();
            $OrdOrderInfoOBJ = new OrdOrderInfoModel();

            $order = $orderOBJ->getInfoByOrderNo($orderno,"id,orderstatus,customerid");

            //判断订单是否存在
            if(!empty($order)){

                //判断订单和操作人是否一致
                if($order['customerid']==$customerid){

                    /*
                    订单状态为2时，才能确认收货
                     */
                    if($order['orderstatus']==3){

                        $amountModel = Model::ins("AmoAmount");
                        $amountModel->startTrans();
                        try{

                            if($orderOBJ->update(["orderstatus"=>4],["id"=>$order['id']])){

                                $updateData = [
                                    "finish_time"=>date("Y-m-d H:i:s"),
                                ];
                                
                                $OrdOrderInfoOBJ->modify($updateData,["id"=>$order['id']]);

                                

                                //分润的钱都归还到用户账户余额中
                                ProfitModel::returnprofit([
                                        "userid"=>$order['customerid'],
                                        "orderno"=>$orderno,
                                    ]);

                                //商家结算
                                /*Model::new("Business.Settlement")->pay([
                                        "orderno"=>$orderno,
                                    ]);*/

                                // Model::new("Order.OrderCount")->deCount($order['customerid'],"count_receipt");
                                // Model::new("Order.OrderCount")->addCount($userid,"count_evaluate");
                                $amountModel->commit();   
                                return ["code"=>"200"];
                            }else{
                                return ["code"=>"400"];
                            }

                        } catch (\Exception $e) {
                            // print_r($e);
                            // 错误日志
                            // 回滚事务
                            $amountModel->rollback();

                            Log::add($e,__METHOD__);

                            return ["code"=>"400"];
                        }

                    }else{
                        return ["code"=>"7001"];
                    }

                }else{
                    return [
                        "code"=>"1001",
                    ];
                }
            }else{
                return [
                    "code"=>"1000",
                ];
            }

        }else{
            return [
                "code"=>"404",
            ];
        }
    }


    /**
     * 订单退款
     * @Author   zhuangqm
     * @DateTime 2017-03-06T19:20:53+0800
     * @param    [type]                   $param [
     *                                           customerid
     *                                           param
     *                                     ]
     * @return   [type]                          [description]
     */
    public function refund($param){

        $orderno = $param['param']['orderno'];
        $customerid = $param['customerid'];

        if(!empty($orderno)){
            $orderOBJ           = new OrdOrderModel();
            $OrdOrderInfoOBJ = new OrdOrderInfoModel();

            $order = $orderOBJ->getInfoByOrderNo($orderno,"id,orderstatus,customerid");

            //判断订单是否存在
            if(!empty($order)){

                //判断订单和操作人是否一致
                if($order['customerid']==$customerid){

                    /*
                   
                     */
                    if($order['orderstatus']==2){

                        if($orderOBJ->modify(["orderstatus"=>3],["id"=>$order['id']])){

                            $OrdOrderInfoOBJ->modify(["actual_delivery_time"=>date("Y-m-d H:i:s"),],["id"=>$order['id']]);

                            return ["code"=>"200"];
                        }else{
                            return ["code"=>"400"];
                        }
                    }else{
                        return ["code"=>"7001"];
                    }

                }else{
                    return [
                        "code"=>"1001",
                    ];
                }
            }else{
                return [
                    "code"=>"1000",
                ];
            }

        }else{
            return [
                "code"=>"404",
            ];
        }
    }

    /**
     * [remindshipping 提醒卖家发货]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-09T11:34:38+0800
     * @return   [type]                   [description]
     */
    public function remindshipping($orderno,$customerid){
       
        $orderOBJ           = new OrdOrderModel();
        $order = $orderOBJ->getInfoByOrderNo($orderno,"id,orderstatus,customerid");

            //判断订单是否存在
        if(!empty($order)){
                //判断订单和操作人是否一致
                if($order['customerid']==$customerid){
                    return ["code"=>200,'data'=>$orderno];
                }
        }else{
            return [
                "code"=>"1000",
            ];
        }
    }

    /**
     * [extendedreceipt 订单延长收货]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-09T11:52:19+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function extendedreceipt($param){

        $orderno = $param['param']['orderno'];
        $customerid = $param['customerid'];
        //订单号是否存在
        if(!empty($orderno)){
            $orderOBJ           = new OrdOrderModel();
            $OrdOrderInfoOBJ = new OrdOrderInfoModel();
            //查询订单信息
            $order = $orderOBJ->getInfoByOrderNo($orderno,"id,orderstatus,customerid,delivertime");
            //echo $order['customerid']."----".$customerid;
            if($order['customerid'] == $customerid){
                //订单明细
                $orderInfo = $OrdOrderInfoOBJ->getRow(['orderno'=>$orderno],"orderno,islongdate,customerid");
                
                if($orderInfo['islongdate'] != 1){//是否已经延长收货
                  
                    //发货七天后时间
                    $time = date("Y-m-d H:i:s",strtotime($order['delivertime'] ."+8 days"));
                    $now = date('Y-m-d H:i:s');
                    //订单剩余7天内方可延长收货
                    if($time <= $now ){
                        
                        //$auto_delivery_time =  date("Y-m-d H:i:s",strtotime("+7 days",strtotime($orderInfo['auto_delivery_time'])));
                        $orderOBJ->update(['islongdate'=>1],['orderno'=>$orderno]);
                        $data = $OrdOrderInfoOBJ->update(['islongdate'=>1],['orderno'=>$orderno]);
                                
                        if($data >0){
                            return ['code'=>200];
                        }else{
                            return ['code'=>400];
                        }

                    }else{
                        return ['code'=>7005];
                    }

                }else{
                    return ['code'=>7003];
                }
            }else{
                return ['code'=>1001];
            }
           
        }else{
            return ['code'=>404];
        }
    }

    /**
     * [deleteorder 删除订单]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-09T14:53:03+0800
     * @Editor jeeluo 2017-04-20 11:53:56
     * @return   [type]                   [description]
     */
    public function deleteorder($param){
        $orderno = $param['param']['orderno'];
        $customerid = $param['customerid'];
        //订单号是否存在
        if(!empty($orderno)){
            $orderOBJ           = new OrdOrderModel();
            $orderdeleteOBJ = new OrdOrderDeleteModel();
             //查询订单信息
            $order = $orderOBJ->getInfoByOrderNo($orderno,"*");
            
            if($order['customerid'] == $customerid){ 
                //插入被删除订单信息
                $data = $orderdeleteOBJ->insert($order);

                if($data > 0){
                    $orderOBJ->delete(['orderno'=>$orderno]);
                    return ['code'=>200];
                }else{
                    return ['code'=>400];
                }
            }else{
                return ['code'=>1001];
            }

        }else{
            return ['code'=>404];
        }
    }

    /**
     * [gerReturnOderList 获取退货订单列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-01T14:30:33+0800
     * @return   [type]                   [description]
     */
    public function getReturnOderList($param){

        if(!empty($param) && is_array($param)){

            if(!empty($param['business_id']))
                $where = ['business_id'=>$param['business_id'],"items_id"=>['<>',0]];

                $orderReturnOBJ = new OrdOrderReturnModel();
                $orderItemDetail = new OrdShoppingcartModel();

                $orderList = $orderReturnOBJ->getReturnPageList($where,'order_code as orderno,returnno,items_id,skuid,productnum,realproductnum,price,order_money,thumb,return_type,orderstatus,business_id,returnamount,returnbull');
                

                $OrdOrderInfoOBJ = new OrdOrderInfoModel();
                $OrdOrderItemOBJ = new OrdOrderItemModel();
                $logisticsOBJ    = new OrdOrderLogisticsModel();
                //获取订单明细
                foreach($orderList['list'] as $key=>$order){
                     
                    $orderList['list'][$key]['actualfreight'] = "";
                    $orderList['list'][$key]['productcount'] = "";
                    $orderList['list'][$key]['productamount'] = "";
                    $orderList['list'][$key]['bullamount'] = "";
                    $orderList['list'][$key]['addtime'] = "";
                    $orderList['list'][$key]['businessid'] = $order['business_id'];
                    $orderList['list'][$key]['businessname'] = "";
                    $orderList['list'][$key]['cancelsource'] = "";
                    $orderList['list'][$key]['return_status'] = $order['return_type'];
                    $orderList['list'][$key]['islongdate'] = "";
                    $orderList['list'][$key]['delivertime'] = "";
                    $orderList['list'][$key]['orderstatus_str'] = $this->getStatusStr($order['orderstatus']);
                   

                    //echo "--------".DePrice($order['productamount'])."-------";

                    $orderinfo = $OrdOrderInfoOBJ->getOrdOrderInfo($order['id']);

                    $orderList[$key] = array_merge($order,$orderinfo);

        //          $orderlist[$key]['orderact'] = $this->getOrderStatus($order['orderstatus'],[
        //                  "evaluate"=>$order['evaluate'],
        //                  "cancelsource"=>$orderinfo['cancelsource'],
        //                  "islongdate"=>$orderinfo['islongdate'],
        //                  "return_status"=>$orderinfo['return_status'],
        //              ]);

                    $orderList['list'][$key]['orderact'][0] = array("act" => "", "acttype" => "", "actname" => "");

                    $orderitem_list = $OrdOrderItemOBJ->getList([
                            "orderid"=>$order['items_id'],
                        ],"productid,productname,productnum,thumb,prouctprice,bullamount,skudetail,skuid","id asc");

                    foreach($orderitem_list as $k=>$v){
                        $orderitem_list[$k]['thumb'] = Img::url($v['thumb']);
                        $orderitem_list[$k]['returnamount'] = DePrice($order['returnamount']);
                         $orderitem_list[$k]['returnbull'] = DePrice($order['returnbull']);
                        $orderitem_list[$k]['prouctprice'] = DePrice($v['prouctprice']);
                    }

                    $orderList['list'][$key]['orderitem'] = $orderitem_list;

                    //物流信息
                    //获取订单物流
                    $logistics = $logisticsOBJ->getRow(["orderno"=>$order['orderno']],"express_name,express_no");

                    //收货地址信息
                    $orderList['list'][$key]['receipt_address'] = !empty($logistics)?$logistics:[
                                                                    "logisticsid"=>"",
                                                                    "mobile"=>"",
                                                                    "realname"=>"",
                                                                    "city_id"=>"",
                                                                    "city"=>"",
                                                                    "address"=>"",
                                                                    "express_name"=>"",
                                                                    "express_no"=>"",
                                                                ];

                    unset($orderList['list'][$key]['id']);
                    unset($orderList['list'][$key]['return_type']);
                }
                return $orderList;

        }else{
            return ['code'=>404];
        }
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
    * @user 获取退单运费
    * @param 
    * @author jeeluo
    * @date 2017年5月26日下午7:32:52
    */
    public function getReturnFreight($params) {
        $order = Model::ins("OrdOrder")->getRow(array("orderno"=>$params['orderno']),"actualfreight,businessid");
        // 查询订单其它商品
        $orderItem = Model::ins("OrdOrderItem")->getList(array("orderno"=>$params['orderno']),"productid,skuid,productnum");
        if(count($orderItem) > 1) {
            $transportOBJ = new TransportModel();
            
            $loginstics = Model::ins("OrdOrderLogistics")->getRow(array("orderno"=>$params['orderno']),"city_id");
            
            $selfItem = Model::ins("OrdOrderItem")->getRow(array("orderno"=>$params['orderno'],"productid"=>$params['productid'],"skuid"=>$params['skuid']),"productnum");
            $selfItemSpec = Model::ins('ProProductSpec')->getRow(array("productid"=>$params['productid'],"id"=>$params['skuid']),"weight,weight_gross,volume");
            $weight = $returnWeight = 0;
            $weight_gross = $returnWeightGross = 0;
            $volume = $returnVolume = 0;
            $productnum = $returnProductnum = 0;
            $returnFreight = 0;
            
            // 多商品订单(计算订单下所有商品)
            foreach ($orderItem as $item) {
                $pro_product_spec = Model::ins("ProProductSpec")->getRow(array("productid"=>$item['productid'],"id"=>$item['skuid']),"weight,weight_gross,volume");
            
                $productnum += $item['productnum'];
                $weight += $pro_product_spec['weight'];
                $weight_gross += $pro_product_spec['weight_gross'];
                $volume += $pro_product_spec['volume'];
            }
            
<<<<<<< .mine
            // 查看订单下 成功的退单数据
            $returnList = Model::ins("OrdOrderReturn")->getList(array("order_code"=>$params['orderno'],"return_type"=>1,"orderstatus"=>["in","1,2,4"]),"productid,skuid,productnum,freight");
||||||| .r181
            // 查看订单下 成功的退单数据
            $returnList = Model::ins("OrdOrderReturn")->getList(array("order_code"=>$params['orderno'],"orderstatus"=>["in","1,2,4"]),"productid,skuid,productnum,freight");
=======
            // 查看订单下 的退单数据
            $returnList = Model::ins("OrdOrderReturn")->getList(array("order_code"=>$params['orderno'],"return_type"=>1,"orderstatus"=>["in","1,2,4"]),"productid,skuid,productnum,freight");
>>>>>>> .r183
            if(!empty($returnList)) {
                foreach ($returnList as $return) {
                    $pro_product_spec = Model::ins("ProProductSpec")->getRow(array("productid"=>$return['productid'],"id"=>$return['skuid']),"weight,weight_gross,volume");
                    
                    $returnProductnum += $return['productnum'];
                    $returnWeight += $return['productnum']*$pro_product_spec['weight'];
                    $returnWeightGross += $return['productnum']*$pro_product_spec['weight_gross'];
                    $returnVolume += $return['productnum']*$pro_product_spec['volume'];
                    $returnFreight += $return['freight'];
                }
            }
            
            $actual['productnum'] = $productnum-$returnProductnum-$selfItem['productnum'];
            $actual['weight'] = $weight-$returnWeight-$selfItem['productnum']*$selfItemSpec['weight'];
            $actual['weight_gross'] = $weight_gross-$returnWeightGross-$selfItem['productnum']*$selfItemSpec['weight_gross'];
            $actual['volume'] = $volume-$returnVolume-$selfItem['productnum']*$selfItemSpec['volume'];
            
            // 计算订单运费
            $actualfreight = $transportOBJ->getFreight([
                "businessid"=>$order['businessid'],
                "city_id"=>$loginstics['city_id'],
                "productnum"=> $actual['productnum']>0 ? $actual['productnum'] : 0,
                "weight"=>$actual['weight']>0 ? $actual['weight'] : 0,
                "weight_gross"=>$actual['weight_gross']>0 ? $actual['weight_gross'] : 0,
                "volume"=>$actual['volume']>0 ? $actual['volume'] : 0,
            ]);
            $actualfreight = EnPrice($actualfreight);
            
            if(($returnProductnum + $selfItem['productnum']) < $productnum) {
                $result['freight'] = ($order['actualfreight']-$actualfreight-$returnFreight) > 0 ? $order['actualfreight']-$actualfreight-$returnFreight : 0;
            } else {
                $result['freight'] = ($order['actualfreight'] - $returnFreight) > 0 ? $order['actualfreight'] - $returnFreight : 0;
            }
        } else {
            // 说明单商品订单
            $result['freight'] = $order['actualfreight'];
        }
        
        return $result;
    }
}
