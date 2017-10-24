<?php
// +----------------------------------------------------------------------
// |  [ 抢购 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author zhuangqm
// | @DateTime 2017-09-21
// +----------------------------------------------------------------------
namespace app\model\Product;

use app\lib\Model;
//获取配置
use \think\Config;

class ProductBuyModel{

    /**
     * 获取抢购活动信息
     * @Author   zhuangqm
     * @DateTime 2017-09-21T15:21:14+0800
     * @param    [type]                   $param [description]
     */
    public function ProductBuy($param){
        
        $productid = $param['productid'];
        $userid    = $param['userid'];

        if(empty($productid))
            return [
                "product_storage_flag"=>false,
            ];

        //查redis
        $ProductBuyRedis = Model::Redis("ProProductBuy");

        $row = $ProductBuyRedis->getRow($productid);

        //
        if(!empty($row)){

            if($row['enable']=='1'){

                $time = time();
                $starttime = $row['starttime']!=''?strtotime($row['starttime']):'';
                $endtime   = $row['endtime']!=''?strtotime($row['endtime']):'';

                // 提前3天预告
                if(strtotime($row['starttime']." -3 day")>time()){
                    return [
                        "product_storage_flag"=>false,
                    ];
                }


                $res = [
                    "product_storage_flag"=>true,
                    "productstorage"=>$row['productstorage_buy'],
                    "product_buy"=>$row,
                ];

                if($starttime>$time){
                    // 抢购未开始
                    $res['product_buy_status'] = 1;
                }else if($starttime<=$time && $time<=$endtime){
                    // 抢购开始
                    $res['product_buy_status'] = 2;

                    if($row['productstorage_buy']>0){

                        // -1表示不限购
                        if($row['limitbuy']>0){

                            if($userid>0){
                                // 判断是否已经购买过该商品的数量
                                $orderbuy = Model::ins("OrdOrderBuy")->getRow(["customerid"=>$userid,"product_buy_id"=>$row['id'],"productid"=>$productid,"status"=>[">=","0"]],"sum(productcount) as productcount");

                                if($orderbuy['productcount']>0){
                                    $res['product_buy']['limitbuy'] = $orderbuy['productcount']<$row['limitbuy']?intval($row['limitbuy']-$orderbuy['productcount']):0;

                                }

                                // 限购数不能大于剩余购买数
                                if($res['product_buy']['limitbuy']>0 && $res['product_buy']['limitbuy']>$row['productstorage_buy'])
                                    $res['product_buy']['limitbuy'] = $row['productstorage_buy'];
                            }
                        }

                    }else{
                        // 抢购结束 抢购完毕
                        //$res['product_buy_status'] = 2;
                        $res['product_buy']['limitbuy'] = 0;
                    }

                }else{
                    // 抢购结束
                    $res['product_buy_status'] = 0;
                    $res['product_buy']['limitbuy'] = 0;
                    $res['product_storage_flag'] = false;
                }

                return $res;

            }else{
                return [
                    "product_storage_flag"=>false,
                ];
            }

        }else{
            return [
                "product_storage_flag"=>false,
            ];
        }
    }

    /**
     * 更新活动redis
     * @Author   zhuangqm
     * @DateTime 2017-09-22T11:34:04+0800
     * @return   [type]                   [description]
     */
    public function updateProductBuyRedis($param){

        $productid  = $param['productid'];
        $id         = $param['id'];
        $updateData = $param['updateData'];

        $ProProductBuyModel = Model::ins("ProProductBuy");

        $ProductBuyRedis = Model::Redis("ProProductBuy");
        
        $ascEndActivity = Model::ins("ProProductBuy")->getRow(["endtime"=>[">=",getFormatNow()],"productid"=>$productid],"id","endtime asc");
        if($ascEndActivity['id'] != $id) {
            return true;
        }

        if(!empty($updateData)){

            if($ProductBuyRedis->exists($productid)){
                $ProductBuyRedis->update($productid,$updateData);
            }else{
                $ProductBuyRedis->insert($productid,$ProProductBuyModel->getRow(["id"=>$id],"*"));
            }

        }else{
            $ProductBuyRedis->del($productid); // 以productid作为主键
            $ProductBuyRedis->insert($productid,$ProProductBuyModel->getRow(["id"=>$id],"*"));
        }

        return true;
    }

    /**
     * 添加抢购记录
     * @Author   zhuangqm
     * @DateTime 2017-09-22T16:49:31+0800
     * @param    [type]                   $param [
     *                                          "product_buy_id"=>
     *                                          "productid"
                                                "orderno"=>
                                                "customerid"=>
                                                "productcount"=>
     *                                     ]
     */
    public function addOrderBuy($param){

        $ProProductBuyRedis = Model::Redis("ProProductBuy");

        $ProProductBuyRow = $ProProductBuyRedis->getRow($param['productid'],"productstorage_buy");

        if($ProProductBuyRow['productstorage_buy']<=0 || $ProProductBuyRow['productstorage_buy']<$param['productcount']){
            return ["code"=>"6013"]; 
        }

        // 开启事务 
        //$ProProductBuyRedis->watch($param['productid']);
        //$ProProductBuyRedis->multi();

        $ProProductBuyRedis->hincrby($param['productid'],["productstorage_buy"=>"-".$param['productcount']]);

        //$ProProductBuyRedis->unwatch();
        /*$OrdOrderBuy = Model::ins("OrdOrderBuy");

        $row = $OrdOrderBuy->getRow([
            "orderno"=>$param['orderno'],
            "customerid"=>$param['customerid'],
        ],"count(*) as count");

        if($row['count']>0){
            $ProProductBuyRedis->hincrby($param['productid'],["productstorage_buy"=>$param['productcount']]);
            return ["code"=>"6011"];
        }*/

        $ProProductBuyRedis->exec();

        $OrdOrderBuy = Model::ins("OrdOrderBuy");

        // 扣减库存
        $result = Model::ins("ProProductBuy")->update("productstorage_buy=productstorage_buy-".$param['productcount'],"id='".param['product_buy_id']."' and productstorage_buy>='".$param['productcount']."'");

        $OrdOrderBuy->insert([
            "orderno"=>$param['orderno'],
            "customerid"=>$param['customerid'],
            "product_buy_id"=>$param['product_buy_id'],
            "productid"=>$param['productid'],
            "productcount"=>$param['productcount'],
            "addtime"=>date("Y-m-d H:i:s"),
            "buytime"=>microtime(true)*10000,
            "status"=>0,
        ]);
        // redis扣减
        /*if($result)
            $ProProductBuyRedis->hincrby($param['productid'],["productstorage_buy"=>"-".$param['productcount']]);*/

        return ["code"=>"200"];
    }

    /**
     * 取消抢购活动
     * @Author   zhuangqm
     * @DateTime 2017-09-26T18:39:31+0800
     * @return   [type]                   [description]
     */
    public function cancelOrderBuy($param){

        $customerid = $param['customerid'];
        $orderno    = $param['orderno'];

        $OrdOrderBuy = Model::ins("OrdOrderBuy");

        //判断是否抢购活动
        $orderbuy = $OrdOrderBuy->getRow(["customerid"=>$customerid,"orderno"=>$orderno],"*");

        if(!empty($orderbuy) && $orderbuy['status']==0){
            
            $OrdOrderBuy->update([
                "status"=>-1,
            ],["orderno"=>$orderbuy['orderno']]);

            //还原库存
            $result = Model::ins("ProProductBuy")->update("productstorage_buy=productstorage_buy+".$orderbuy['productcount'],["id"=>$orderbuy['product_buy_id']]);
            // redis扣减
            if($result)
                Model::Redis("ProProductBuy")->hincrby($orderbuy['productid'],["productstorage_buy"=>$orderbuy['productcount']]);
        }

        return ["code"=>"200"];
    }

    /**
     * 获取商品活动价格
     * @Author   zhuangqm
     * @DateTime 2017-09-30T10:31:29+0800
     * @param    [type]                   $param [
     *                                           prouctprice
     *                                           bullamount
     *                                     ]
     * @return   [type]                          [description]
     */
    public function getProductPrice($param){

        $prouctprice = $param['prouctprice'];
        $bullamount  = $param['bullamount'];
        $supplyprice = $param['supplyprice'];

        $ProductBuyRedis = Model::Redis("ProProductBuy");

        $row = $ProductBuyRedis->getRow($productid);

        if(!empty($row)){

            if($row['enable']=='1'){

                $time = time();
                $starttime = $row['starttime']!=''?strtotime($row['starttime']):'';
                $endtime   = $row['endtime']!=''?strtotime($row['endtime']):'';

                if($starttime<=$time && $time<=$endtime){
                    
                    $prouctprice = DePrice($row['prouctprice']);
                    $bullamount  = DePrice($row['bullamount']);
                    $supplyprice = DePrice($row['supplyprice']);
                }


            }

        }


        return [
            "prouctprice"=>$prouctprice,
            "bullamount"=>$bullamount,
            "supplyprice"=>$supplyprice,
        ];
    }
}