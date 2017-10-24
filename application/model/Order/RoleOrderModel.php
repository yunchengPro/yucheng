<?php
namespace app\model\Order;

use app\model\Pay\PayModel;

use think\Config;

use app\lib\Img;

use app\lib\Model;

use app\lib\Log;
use app\model\Sys\CommonModel;

class RoleOrderModel
{
    
    
    /**
     * 提交订单
     * @Author   zhuangqm
     * @DateTime 2017-05-08T17:33:40+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function addorder($param){
        $userid     = $param['userid'];
        $productid  = $param['productid'];

        $sign = $param['sign'];
       
        /**
         * 先校验签名是否合法
         */
        if($this->chec_addorder_sign($sign,$param)){

            $cusinfoOBJ         = Model::ins("CusCustomerInfo");
            $orderOBJ           = Model::ins("RoleOrder");
            $orderitemOBJ       = Model::ins("RoleOrderItem");
            $productOBJ         = Model::ins("RoleProduct");
            $logisticsOBJ       = Model::ins("RoleOrderLogistics");

            //用户信息
//             $cusinfo    = $cusinfoOBJ->getById($userid,"nickname");

            $productinfo = $productOBJ->getRow(["id"=>$productid],"*");

            $orderOBJ->startTrans();

            try{

                $orderno        = $orderOBJ->getOrderNo();

                $actualfreight  = 0;
                $productnum     = 1;
                $productamount  = $productnum*$productinfo['prouctprice'];
                $totalamount    = $productamount+$actualfreight;
                //echo "-------1111----";
                //生成订单
                $orderno = $orderOBJ->getOrderNo();
                $addtime = date("Y-m-d H:i:s");

                $data = [
                    "role_orderno" => $param['orderno'],
                    "orderno"=>$orderno,
                    "customerid"=>$userid,
//                     "cust_name"=>$cusinfo['nickname'],
                    "cust_name"=>$param['realname'],
                    "actualfreight"=>$actualfreight,
                    "productcount"=>$productnum,
                    "productamount"=>$productamount,
                    "totalamount"=>$totalamount,
                    "addtime"=>$addtime,
                    "orderstatus"=>"0",
                    "businessid"=>$productinfo['businessid'],
                    "businessname"=>$productinfo['businessname'],
                ];
                
                $orderid = $orderOBJ->insert($data);
                
                //生成订单明细
                $data = [
                    "orderid"=>$orderid,
                    "orderno"=>$orderno,
                    "businessid"=>$productinfo['businessid'],
                    "businessname"=>$productinfo['businessname'],
                    "productid"=>$productid,
                    "productname"=>$productinfo['productname'],
                    "productnum"=>$productnum,
                    "thumb"=>$productinfo['thumb'],
                    "prouctprice"=>$productinfo['prouctprice'],
                    "addtime"=>$addtime,
                ];
                
                $orderitemOBJ->insert($data);
                    
                
                //订单添加物流信息
//                 $data = [
//                     "orderno"=>$orderno,
//                     "mobile"=>$loginstics['mobile'],
//                     "realname"=>$loginstics['realname'],
//                     "city_id"=>$loginstics['city_id'],
//                     "city"=>$loginstics['city'],
//                     "address"=>$loginstics['address'],
//                 ];
                $data = [
                    "orderid"=>$orderid,
                    "orderno"=>$orderno,
                    "mobile"=>$param['logisticsMobile'],
                    "realname"=>$param['logisticsName'],
                    "city_id"=>$param['logisticsAreaCode'],
                    "city"=>$param['logisticsArea'],
                    "address"=>$param['logisticsAddress'],
                ];
                
                
                $logisticsOBJ->insert($data);

                // 提交事务
                $orderOBJ->commit(); 

                return [
                    "code"=>'200',
                    "orderidstr"=>$orderno,
                    "ordercount"=>1,
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
        
        return true;


        
    }

    

}
