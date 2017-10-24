<?php
// +----------------------------------------------------------------------
// |  [ 同步商品信息 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年8月16日14:10:23}}
// +----------------------------------------------------------------------
namespace app\auto\controller\Product;
use app\auto\ActionController;

use think\Config;
use app\model\Product\ProductModel;

use app\lib\Model;

class IndexController extends ActionController{

    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }  

    /**
     * [updateproductpriceAction 更新商品价格]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-12T10:14:15+0800
     * @return   [type]                   [description]
     */
    public function updateproductpriceAction(){
        $pagesize = 50;
        $page     = 1;

        while(true){
            $product_list  = Model::ins('ProProduct')->pageList([],'id,saleprice,supplyprice,saletype,checksatus','id desc',0,$page,$pagesize);
            $page+=1;
            foreach ($product_list as $key => $product) {
                
               
                // $formart_price_arr = [
                //     'supplyprice' =>$product['supplyprice'],
                //     'saleprice' =>$product['saleprice'],
                //     'settle_cycle' =>intval($product['saletype'])
                // ];     
               
                // $formart_price = ProductModel::newFormatPrice($formart_price_arr); 
               
          
                // // print_r($formart_price);
                // if($formart_price['prouctprice'] >= 0 || $formart_price['bullamount'] > 0 ){
                   
                //     var_dump(Model::ins('ProProduct')->update(['prouctprice'=>$formart_price['prouctprice'],'bullamount'=>$formart_price['bullamount']],['id'=>$product['id']]));

                //     if($product['checksatus'] != 1){
                //         $product_price['enable'] = 0;
                //     }
                //     $product_price['prouctprice'] = $formart_price['prouctprice'];
                //     $product_price['bullamount'] = $formart_price['bullamount'];
                //     Model::Es("ProProduct")->update($product_price,['id'=>$product['id']]);
                // }
               
                $spec_product = Model::ins('ProProductSpec')->getList(['productid'=>$product['id']],'id,productid,saleprice,supplyprice,saletype');
                echo "productid:".$product['id']."\n";

                $arr_up_price = [];
                $arr_price    = [];

                foreach ($spec_product as $spec_key => $spec_value) {

                    echo "spec_product:".$spec_value['id']."\n";
                    $formart_price_arr_spec = [
                        'supplyprice' =>$spec_value['supplyprice'],
                        'saleprice' =>$spec_value['saleprice'],
                        'settle_cycle' =>intval($spec_value['saletype'])
                    ]; 
                    $formart_price_spec = ProductModel::newFormatPrice($formart_price_arr_spec);

                    $arr_price[$key][$spec_value['id']]  =  $formart_price_spec['prouctprice'];
                    $arr_up_price[$key][$spec_value['id']]['prouctprice'] =  $formart_price_spec['prouctprice'];
                    $arr_up_price[$key][$spec_value['id']]['bullamount']   =  $formart_price_spec['bullamount'];
                    $arr_up_price[$key][$spec_value['id']]['saleprice']    =  $spec_value['saleprice'];
                    $arr_up_price[$key][$spec_value['id']]['supplyprice']  =  $spec_value['supplyprice'];


                    if($formart_price_spec['prouctprice'] >= 0 ){ 
                     
                       Model::ins('ProProductSpec')->update(['prouctprice'=>$formart_price_spec['prouctprice'],'bullamount'=>$formart_price_spec['bullamount']],['id'=>$spec_value['id']]); 

                    }

                    if($formart_price_spec['prouctprice'] == 0 && $formart_price_spec['bullamount'] == 0 ){

                        Model::ins('ProProduct')->update(['enable'=>-1,'checksatus'=>0],['id'=>$product['id']]); 
                        Model::Es("ProProduct")->update(['enable'=>0],['id'=>$product['id']]);

                        $insert = [
                            'productid' =>$product['id'],
                            'addtime' => date('Y-m-d H:i:s')
                        ];

                        $Update_row =  Model::ins('ProProductUpdate')->getRow(['productid'=>$product['id']],'id');

                        if(empty($Update_row))
                            Model::ins('ProProductUpdate')->insert($insert);
                    }
                }

                if(!empty($arr_price)  && !empty($arr_up_price)){
                    // print_r($arr_price);
                    // print_r($arr_up_price);
                    $pos=array_search(min($arr_price[$key]),$arr_price[$key]);
                    // print_r($pos);
                    
                    Model::Es("ProProduct")->update($arr_up_price[$key][$pos],['id'=>$product['id']]);
                    Model::ins('ProProduct')->update($arr_up_price[$key][$pos],['id'=>$product['id']]);
                }
            }
           
            if(count($product_list)==0 || count($product_list)<$pagesize)
                    break;
        }
       
       
        echo "更新完成";
    }


    
}