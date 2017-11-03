<?php
namespace app\sale\controller\Product;
use app\sale\ActionController;
use app\lib\Model;
use app\model\Sys\CommonModel;
use app\model\Product\ProductModel;
use app\lib\Img;
class IndexController extends ActionController{

	/**
     * 固定不变11
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [categorylistAction 分类列表]
     * @return [type] [description]
     */
    public function categorylistAction(){
    	$viewData = [
    		'title' => '分类列表'
    	];
    	return $this->view($viewData);
    }

    /**
     * [getcategorylistAction 获取分类]
     * @return [type] [description]
     */
    public function getcategorylistAction(){
        $category =  ProductModel::categorylist();
       
        return $this->json(200,$category);
    }

    /**
     * [goodsdetailAction 商品详情]
     * @return [type] [description]
     */
    public function goodsdetailAction(){
    	$viewData = [
    		'title' => '分类列表'
    	];
    	return $this->view($viewData);
    }

    /**
     * [goodslistAction 获取商品列表]
     * @return [type] [description]
     */
    public function goodslistAction(){
        $param['where'] = [
            'checksatus' =>1,
            'enable' =>1
        ];

        $product_list = ProductModel::ProductPageList($param);
        foreach ($product_list['list'] as $key => $value) {
            $product_list['list'][$key]['thumb'] = Img::url($value['thumb']);
        }
        
        return $this->json(200,$product_list);
    }


}