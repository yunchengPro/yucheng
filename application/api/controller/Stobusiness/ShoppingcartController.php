<?php
// +----------------------------------------------------------------------
// |  [ 实体店购物车接口 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-6-27 20:32:23}}
// +----------------------------------------------------------------------
namespace app\api\controller\Stobusiness;
use app\api\ActionController;

use app\model\StoBusiness\ShoppingcartModel;

class ShoppingcartController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    /**
     * [goodsListAction 购物车列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-27T20:33:24+0800
     * @return   [type]                   [description]
     */
    public function goodsListAction(){

        $this->params['customerid'] = $this->userid;
        $data =  ShoppingcartModel::shoppingcartGoodsList($this->params);
        if($data['code']==200){
            return $this->json(200,$data['data']);
        }else{
            return $this->json($data['code']);
        }

    }

    /**
     * [addgoodsAction 添加购物车]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-28T09:46:21+0800
     * @return   [type]                   [description]
     */
    public function addgoodsAction(){
        $this->params['customerid'] = $this->userid;
        $data = ShoppingcartModel::addShoppingcartGoods($this->params);
        if($data['code']==200){
            return $this->json(200,$data['data']);
        }else{
            return $this->json($data['code']);
        }
    }

    /**
     * [updateGoodsAction 修改购物车]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-28T20:16:39+0800
     * @return   [type]                   [description]
     */
    public function updategoodsAction(){
        $this->params['customerid'] = $this->userid;
        $data = ShoppingcartModel::updateShoppingcartGoods($this->params);
        if($data['code']==200){
            return $this->json(200,$data['data']);
        }else{
            return $this->json($data['code']);
        }
    }

    /**
     * [deletegoodsAction 删除购物车]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-28T20:42:23+0800
     * @return   [type]                   [description]
     */
    public function deletegoodsAction(){
        $this->params['customerid'] = $this->userid;
        $data = ShoppingcartModel::deleteShoppingcartGoods($this->params);
        if($data['code']==200){
            return $this->json(200,$data['data']);
        }else{
            return $this->json($data['code']);
        }
    }

    /**
     * [deleteallgoodsAction 清空购物车]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-03T11:29:37+0800
     * @return   [type]                   [description]
     */
    public function deleteallgoodsAction(){
        $this->params['customerid'] = $this->userid;
        $data = ShoppingcartModel::deleteAllShoppingcartGoods($this->params);
        if($data['code']==200){
            return $this->json(200,$data['data']);
        }else{
            return $this->json($data['code']);
        }
    }
}