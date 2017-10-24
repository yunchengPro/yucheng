<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 按销售方式排序商品 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\Saleway;
use app\api\ActionController;

//获取配置
use \think\Config;

use  app\model\Product\ProductModel;
use  app\model\ProCategoryModel;

use  app\model\Business\BusinessCategoryModel;

class IndexController extends ActionController{
	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    /**
     * [indexAction 售卖专区]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-01T11:05:18+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){

        $type = !empty($this->params['type']) ? $this->params['type'] : 1;
        
        if($type > 4)
             $type = 1;
        
        //售卖方式
        $saleWay = ProductModel::getSaleway(['type'=>$type]);

        //广告列表 
        $brand = ProductModel::getTypeBanner($type);
        switch ($type) {
            case 2:
                 //商品列表 现金专区
                $proData = ProductModel::ProductList(['where'=>['enable'=>1,'checksatus'=>1,"bullamount"=>['=',0],"prouctprice"=>['>',0]]]); //,'checksatus'=>1
                break;
            case 3:
                //商品列表 现金加牛豆专区
                $proData = ProductModel::ProductList(['where'=>['enable'=>1,'checksatus'=>1,"bullamount"=>['>',0],"prouctprice"=>['>',0]]]);//,'checksatus'=>1
                break;
            case 4:
                //商品列表 牛豆专区
                $proData = ProductModel::ProductList(['where'=>['enable'=>1,'checksatus'=>1,"bullamount"=>['>',0],"prouctprice"=>['>',0]]]);//,'checksatus'=>1
                break;
            // case 4:
            //     //商品列表 牛豆专区
            //     $proData = ProductModel::ProductList(['where'=>['enable'=>1,'checksatus'=>1,"bullamount"=>['>',0],"prouctprice"=>['=',0]]]);//,'checksatus'=>1
            //     break;
            default:
                $proData = ProductModel::ProductList(['where'=>['enable'=>1,'checksatus'=>1]]);//,'checksatus'=>1
                break;
        }
        

        //返回json数据
        return $this->json('200',[
                    'banner'=>$brand,
                    'category'=>$category,
                    'saleWay'=>$saleWay,
                    'proData'=>$proData
                ]);
    }

 
}