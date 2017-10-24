<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 购物车信息 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\Shopingcart;
use app\api\ActionController;


use app\model\ShoppingCart\CartModel;
use app\model\User\TokenModel;
use app\model\Product\ProductModel;
use app\lib\Img;

class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [addgoodsAction 添加购物车商品]
     * @return [type] [description]
     */
    public function addgoodsAction(){
    	if(!empty($this->params['mtoken']) || !empty($this->params['_env']['imei'])){

    		$TokenModel = new TokenModel();
            $this->params['customerid'] = $TokenModel->getTokenId($this->params['mtoken'])['id'];

            $this->params['version'] = $this->getVersion();
            
            $data = CartModel::addOderCart($this->params);
            //返回json数据
            return $this->json($data['code']);
    	}else{
    		//返回json数据
	       	return $this->json('404');
    	}
    }

    /**
     * [updateSpecgoodsAction 编辑购物车]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-08T17:36:27+0800
     * @return   [type][description]
     */
    public function updateSpecgoodsAction(){
        if(!empty($this->params['mtoken'])){

            $TokenModel = new TokenModel();
            $this->params['customerid'] = $TokenModel->getTokenId($this->params['mtoken'])['id'];

            $data = CartModel::updateSpecOderCart($this->params);
            //返回json数据
            return $this->json($data['code']);
        }else{
            //返回json数据
            return $this->json('404');
        }
    }

    /**
     * [choseGoosSpec 选择商品规格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-08T14:55:08+0800
     * @return   [type]                   [description]
     */
    public function choseGoosSpecAction(){

        if(!empty($this->params['productid'])){
            //商品信息
            $proData = ProductModel::getProDetailById($this->params['productid'],"id,productname,prouctprice,bullamount,businessid,enable");
            
            if(!empty($proData)){
                
                $proData['productid'] = $proData['id'];
                 //商品规格
                $proData['prouctprice'] = DePrice($proData['prouctprice']);
                $proData['specs'] = '';
                $proData['sku'] = '';
                $proData['dspec']='';
                $spec = ProductModel::getProSpecById($proData['productid']);

                if($spec['code'] != 5015){
                    //商品sku 所有情况
                    $skus = ProductModel::getProSkuById($proData['productid']);
                    $skus = $skus['sku'];
                   
                    foreach ($skus as $key => $value) {
                         $sku_arr[] = explode(',',$value['f_productspec']);
                    }

                    foreach ($sku_arr as $key => $value) {
                        foreach ($value as $ka => $va) {
                                $string[$ka][] =  $va ;
                        }
                    }
                 
                  
                    foreach ($spec as $sk => $sv) {

                        foreach ($sv['value'] as $skv => $svv) {
                            if(!in_array($svv['id'], $string[$sk])){
                                unset($spec[$sk]['value'][$skv]);
                            }
                        }
                    }

                    foreach ($spec as $key => $value) {
                        if(empty($value['value'])){
                            unset($spec[$key]);
                        }
                        //sort($spec[$key]['value']);
                    }

                    foreach ($spec as $key => $value) {
                        sort($spec[$key]['value']);
                    }
                    $proData['spec'] = $spec;
            

                    //商品sku 所有情况
                    $sku = ProductModel::getProSkuById($proData['productid']);
                    $proData['sku'] = $sku['sku'];  

                    if(!empty($this->params['skuid'])){
                       
                        $specGoodsData = ProductModel::getRowProSpecById($this->params['skuid'],'productid,productimage,productname,prouctprice,bullamount,spec,productspec,productstorage');
                      
                            if(!empty($specGoodsData)){
                                $proData['productname'] = $specGoodsData['productname'];
                                $proData['productimage'] = Img::url($specGoodsData['productimage']);
                                $proData['prouctprice'] = DePrice($specGoodsData['prouctprice']);
                                $proData['bullamount'] = DePrice($specGoodsData['bullamount']);
                                $proData['productstorage'] = $specGoodsData['productstorage'];
                                
                                $specData = ProductModel::getProSpecById($specGoodsData['productid']);
                               

                                $spec = json_decode($specGoodsData['spec'],true);

                                //print_r($spec);
                                $productspec = json_decode($specGoodsData['productspec'],true);
                                //print_r($productspec);
                                $string = '';
                                foreach ($spec as $ka => $va) {

                                    $string .= $va['name'] . ":" . $va['value'] .";";
                                }
                                $strings =''; 
                                foreach ($productspec as $ko => $vo) {
                                    $strings .= $ko . "," ;
                                }

                                $proData['dspecid'] = rtrim($strings,',');
                                $proData['specs'] = rtrim($string,',');
                                $proData['dspec'] = $spec;
                            }
                    }
                }
               
                return $this->json('200',$proData);
            }else{
                return $this->json('5005');
            }
        }else{
            return $this->json('404');
        }
    }

    /**
     * [updateCartNum 修改购物车数量]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-04T10:47:40+0800
     * @return   [type]                   [description]
     */
    public function updateCartNumAction(){
        if(!empty($this->params['cartid']) && !empty($this->params['mtoken']) && !empty($this->params['num'])){
            
            $TokenModel = new TokenModel();
            
            $this->params['customerid'] = $TokenModel->getTokenId($this->params['mtoken'])['id'];

            $data = CartModel::updateCartNum($this->params['cartid'],$this->params['customerid'],$this->params['num']);
            //返回json数据
            return $this->json($data['code']);
        }else{
            //返回json数据
            return $this->json('404');
        }
    }
    

    /**
     * [delgoodsAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-03T17:44:14+0800
     * @return   [type]                   [description]
     */
    public function delgoodsAction(){
    	if(!empty($this->params['mtoken']) && !empty($this->params['cartid']) && !empty($this->params['businessid'])){

            $TokenModel = new TokenModel();
            $this->params['customerid'] = $TokenModel->getTokenId($this->params['mtoken'])['id'];
    	
            $data = CartModel::deletCartProduct($this->params['cartid'],$this->params['customerid'],$this->params['businessid']);
            //返回json数据
            return $this->json($data['code']);
    	}else{
    		//返回json数据
	       	return $this->json('404');
    	}
    }

    /**
     * [goodsList 购物车商品列表]
     * @return [type] [description]
     */
    public function goodsListAction(){

    	if(!empty($this->params['mtoken'])){
    		
            $TokenModel = new TokenModel();
        
            $this->params['customerid'] = $TokenModel->getTokenId($this->params['mtoken'])['id'];
            
            $data = CartModel::getCartGoodsList($this->params['customerid']);
            //print_r($data);

            if($data['code'] == 200){
                //返回json数据  
                return $this->json('200',$data['data']);
            }else{
                return $this->json($data['code']);
            }
	    	
    	}else{
    		//返回json数据
	       	return $this->json('404');
    	}

    }
}