<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 商城信息模型层 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\api\model;

use app\lib\Db;

use app\lib\Img;
use app\model\ProductModel;

class ShoppingCartModel {

	/**
	 * [addOderCart 添加购物]
	 * @Author   ISir<673638498@qq.com>
 	 * @Date 2017-03-02 
	 */
	public static function addOderCart($param){
		
		if(!isset($param['customerid']) || empty($param['customerid'])){
			return 104;//用户未登录
			exit();
		}
		//查看商品信息
		$proData = ProductModel::getProDetailById($param['productid'],"id as productid,businessid,enable");
		if(empty($proData)){
			return ['code'=>5005,'msg'=>'商品信息不存在'];//商品信息不存在
			exit();
		}

		if($proData['enable'] != 1){
			return ["code"=>5006,"msg"=>"商品已下架"];
			exit();
		}

		if(!empty($param['skuid'])){
			$skudata = ProductModel::getRowProSpecById($param['skuid'],'id');
			if(empty($skudata)){
				return ['code'=>5009,'msg'=>'不存在的商品规格'];
				exit();
			}
		}
		$addData = [
			'customerid'=>$param['customerid'],
			'businessid'=>$proData['businessid'],
			'productid' =>$param['productid'],
			'productnum'=>$param['productnum'],
			'skuid' => $param['skuid']
		];
		$ret = $this->_modelObj->insert($addData);
		if($ret > 0){
			return ["code"=>200,"msg"=>"添加成功"];
		}else{
			return ["code"=>400,"msg"=>"操作有误"];
		}
	}

	/**
	 * [getCartProduct 查看购物车是否存在]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-02T14:04:18+0800
	 * @param    [type]                   $customerid [description]
	 * @param    [type]                   $productid  [description]
	 * @param    [type]                   $skuid      [description]
	 * @return   [type]                               [description]
	 */
	public static function getCartProduct($customerid,$productid,$skuid){
		
		if(empty($productid) || empty($customerid) || empty($skuid)){
			return ["code"=>404,"msg"=>"参数有误"];
		}
		$where = [
			'customerid' => $customerid,
			'productid'  => $productid,
			'skuid'      => $skuid
		];
		$data = $this->_modelObj->getRow($where);
		return ["code"=>200,"data"=>$data];

	}

	/**
	 * [updateCartNum 修改购物车商品数量]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-02T14:34:12+0800
	 * @return   [type]                   [description]
	 */
	public static function updateCartNum($cartid,$customerid,$num){
		
		if(empty($cartid) || empty($customerid) || empty($num)){
			return ["code"=>404,"msg"=>"参数有误"];
			exit();
		}
		
		$data = self::getCartInfoById($cartid);
		//购物车信息不存在
		if($data['code'] != 200){
			return $data;
			exit();
		}
		
		//判断购物车信息是否属于当前用户
		if($data['data']['customerid'] != $customerid){
			return ["code"=>5008,"msg"=>"你不能修改该购物车信息"];
			exit();
		}
		//修改该购物车数量
		$this->_modelObj->update(['productnum'=>$num],['customerid'=>$cartid,'id'=>$cartid]);


	}

	/**
	 * [getCartInfoById 通过id获取购物车信息]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-02T14:37:04+0800
	 * @param    [type]                   $cartid [description]
	 * @return   [type]                           [description]
	 */
	public static function getCartInfoById($cartid){
		
		if(empty($cartid)){
			return ["code"=>404,"msg"=>"参数有误"];
		}
		
		$data = $this->_modelObj->getRow(['id'=>$cartid]);
		
		if(!empty($data)){
			return ["code"=>200,"data"=>$data];
		}else{
			return ["code"=>5007,"msg"=>'购物车信息不存在'];	
		}

	}


}