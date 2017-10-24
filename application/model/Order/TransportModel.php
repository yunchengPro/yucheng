<?php
namespace app\model\Order;

use app\model\OrdTransportModel;
use app\model\OrdTransportExtendModel;
use app\model\ProProductTransportModel;

use \think\Config;

use app\lib\Model;

class TransportModel
{
    
    /**
     * 计算商品运费
     * 商家固定一套运费模板
     * @Author   zhuangqm
     * @DateTime 2017-03-03T22:20:16+0800
     * @param    [type]                     $param   
     *                                          "businessid"=>$businessid,
                                                "city_id"=>$city_id,
                                                "productnum"=>$productnum,
                                                "weight"=>$productnum,
                                                "weight_gross"=>$weight_gross,
                                                "volume"=>$volume,
     * @return   [type]                     [description]
     */
    public function getFreight($param){

        $actualfreight=$this->getOrdTransportFreight($param);

        return $actualfreight;

    }

    /**
     * 计算商品运费
     * @Author   zhuangqm
     * @DateTime 2017-09-04T16:25:07+0800
     * @param    [type]                     $param   
     *                                          "businessid"=>$businessid,
                                                "city_id"=>$city_id,
                                                "productid"=>
                                                "prouctprice"=>
                                                "productnum"=>$productnum,
                                                "transportid"
                                                "weight"=>$productnum,
                                                "weight_gross"=>$weight_gross,
                                                "volume"=>$volume,
     * @return   [type]                          [description]
     */
    public function getProductFreight($param){

        $actualfreight = 0;

        //获取该商品是否有满多少包邮的设定
        /*if($this->checkProductFreight($param))
            return 0;*/
        
        //获取该商品是否设定的运费模板|按商家运费模板进行运费计算
        $actualfreight=$this->getOrdTransportFreight($param);
        
        return $actualfreight;

    }

    /**
     * 判断该商品是否满足：满多少包邮
     * @Author   zhuangqm
     * @DateTime 2017-09-04T16:40:57+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [
     *                                           true  满足，运费为0
     *                                           false 不满足，按原有运费
     *                                       ]
     */
    public function checkProductFreight($param){
        $productFreight = Model::ins("ProProductFreight")->getRow(["id"=>$param['productid']],"*");
        if(!empty($productFreight)){

            // 运费类型1满件包邮2满金额包邮
            if($productFreight['freight_type']==1){

                if($param['productnum']>=$productFreight['freight'])
                    return true;
                else
                    return false;
            }else{
                if(($param['productnum']*$param['prouctprice'])>=DePrice($productFreight['freight']))
                    return true;
                else
                    return false;
            }

        }else{
            return false;
        }
    }

    /**
     * 判断商家满多少包邮
     * @Author   zhuangqm
     * @DateTime 2017-09-20T09:53:41+0800
     * @param    [type]                   $param [
     *                                           businessid
     *                                           productnum 商品数量
     *                                           productamount 商品总价格
     *                                     ]
     * @return   [type]                          [description]
     */
    public function checkBusinessFreight($param){
        $businessFreight = Model::ins("BusBusinessFreight")->getRow(["id"=>$param['businessid'],"enable"=>1],"*");
        if(!empty($businessFreight)){

            // 运费类型1满件包邮2满金额包邮
            if($businessFreight['freight_type']==1){

                if($param['productnum']>=$businessFreight['freight'])
                    return true;
                else
                    return false;
            }else{
                if($param['productamount']>=DePrice($businessFreight['freight']))
                    return true;
                else
                    return false;
            }

        }else{
            return false;
        }
    }

    /**
     * 根据商品计算物流费用
     * @Author   zhuangqm
     * @DateTime 2017-03-04T14:35:57+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    // public function getTransportFreight($param,$city_id){

    //     $protransportOBJ = new ProProductTransportModel();

    //     $protransport_item = $protransportOBJ->getById($param['productid']);

    //     if($protransport_item['freight']>0){
    //         return $protransport_item['freight'];
    //     }
    //     else if($protransport_item['transportid']>0){
    //         return $this->getOrdTransportFreight($param,$city_id);
    //     }else{
    //         return 0;
    //     }
    // }

    /**
     * 按商家运费模板进行运费计算
     * @Author   zhuangqm
     * @DateTime 2017-03-04T14:47:10+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function getOrdTransportFreight($param){

        if($param['transportid']>0){
            $TransportInfo = $this->getProductTransport($param);
        }else{
            $TransportInfo = $this->getAreaTransport($param['businessid'],$param['city_id']);
        }

        $actualfreight = 0;
        switch($this->getValuationType($param['businessid'],$param['transportid'])){ //获取商家的计价方式  计价方式，1按件数，2按重量，3按体积
            case 1:
                $actualfreight = $this->getActualFreightCount($TransportInfo,$param['productnum']);
                break;
            case 2:
                $actualfreight = $this->getActualFreightCount($TransportInfo,$param['weight']);
                break;
            case 3;
                $actualfreight = $this->getActualFreightCount($TransportInfo,$param['volume']);
                break;
        }

        return $actualfreight;
    }

    /**
     * 获取商家的计费方式
     * @Author   zhuangqm
     * @DateTime 2017-03-04T15:48:49+0800
     * @param    [type]                   $businessid [description]
     * @return   [type]                               [description]
     */
    public function getValuationType($businessid,$transportid=''){
        $OrdTransportOBJ = new OrdTransportModel();
        if(!empty($transportid))
            $OrdTransport_item = $OrdTransportOBJ->getRow(["id"=>$transportid],"valuation_type");
        else
            $OrdTransport_item = $OrdTransportOBJ->getRow(["business_id"=>$businessid,"transport_type"=>1],"valuation_type");
        return $OrdTransport_item['valuation_type'];
    }

    /**
     * 获取地区运费
     * @Author   zhuangqm
     * @DateTime 2017-03-04T14:59:54+0800
     * @return   [type]                   [description]
     */
    public function getAreaTransport($businessid,$city_id){
        $OrdTransportExtendOBJ = new OrdTransportExtendModel();
        $city_id = $city_id!=''?substr($city_id,0,4)."00":"000000";
        $TransportInfo = $OrdTransportExtendOBJ->getRow ( " business_id = '".$businessid."' and transport_type=1 and find_in_set(".$city_id.", area_id) "   ,"snum,sprice,xnum,xprice");
        if ( empty( $TransportInfo ) ){
            $TransportInfo = $OrdTransportExtendOBJ->getRow ( " business_id = '".$businessid."' and transport_type=1 and is_default = 1 " ,"snum,sprice,xnum,xprice");
        }

        $TransportInfo['sprice']  = DePrice($TransportInfo['sprice']);
        $TransportInfo['xprice']  = DePrice($TransportInfo['xprice']);

        return $TransportInfo;
    }

    /**
     * 获取商品运费
     * @Author   zhuangqm
     * @DateTime 2017-09-04T17:32:15+0800
     * @return   [type]                   [description]
     */
    public function getProductTransport($param){
        $OrdTransportExtendOBJ = new OrdTransportExtendModel();
        $param['city_id'] = $param['city_id']!=''?substr($param['city_id'],0,4)."00":"000000";
        $TransportInfo = $OrdTransportExtendOBJ->getRow ( " business_id = '".$param['businessid']."' and transport_id = '".$param['transportid']."' and find_in_set(".$param['city_id'].", area_id) " ,"snum,sprice,xnum,xprice");
        if ( empty( $TransportInfo ) ){
            $TransportInfo = $OrdTransportExtendOBJ->getRow ( " business_id = '".$param['businessid']."' and transport_id = '".$param['transportid']."' and is_default = 1 " ,"snum,sprice,xnum,xprice");
        }

        $TransportInfo['sprice']  = DePrice($TransportInfo['sprice']);
        $TransportInfo['xprice']  = DePrice($TransportInfo['xprice']);

        return $TransportInfo;
    }

    /**
     * 按件数|重量|体积进行运费计算
     * @Author   zhuangqm
     * @DateTime 2017-03-04T15:38:57+0800
     * @param    [type]                   $count [description]
     * @return   [type]                          [description]
     */
    public function getActualFreightCount($TransportInfo,$count){
        $actualfreight = 0;
        if($TransportInfo['snum']>=$count){
            $actualfreight = $TransportInfo['sprice'];
        }else{
            $actualfreight+=$TransportInfo['sprice'];
            $actualfreight+=$TransportInfo['xnum']>0?(ceil(($count-$TransportInfo['snum'])/$TransportInfo['xnum'])*$TransportInfo['xprice']):0;
        }
        return $actualfreight;
    }
    
}
