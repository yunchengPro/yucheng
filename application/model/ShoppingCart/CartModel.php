<?php
// +----------------------------------------------------------------------
// | 牛牛汇 [ 购物车模型 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-03
// +----------------------------------------------------------------------
namespace app\model\ShoppingCart;

use app\lib\Db;
use app\lib\Model;

use app\lib\Img;
use app\model\Product\ProductModel;
use app\model\Business\BusinessModel;
use app\model\OrdShoppingcartItem;

class CartModel{

	/**
	 * [addOderCart 添加购物]
	 * @Author   ISir<673638498@qq.com>
 	 * @Date 2017-03-02 
	 */
	public static function addOderCart($param){
		
		if(!isset($param['customerid']) || empty($param['customerid'])){
			return ['code'=>104,'msg'=>'用户未登录'];//用户未登录
		}

		if(empty($param['productid']) || empty($param['productnum']) ){
			return ['code'=>404,"msg"=>'参数有误'];
		}

		//查看商品信息
		//$proData = ProductModel::getProDetailById($param['productid'],"id as productid,businessid,enable");
        $proData = Model::ins("ProProduct")->getRow(['id'=>$param['productid']],"id as productid,businessid,enable,categoryid,isabroad");
        //$proData = $proData['data'];
		if(empty($proData)){
			return ['code'=>5005,'msg'=>'商品信息不存在'];//商品信息不存在
		}

		if($proData['enable'] != 1){
			return ["code"=>5006,"msg"=>"商品已下架"];
		}

		// 20170911
		// if($proData['isabroad']==1 && $param['version'] < "2.1.0" ) {
  //           return ["code"=>"7008"];
  //       }

		if(!empty($param['skuid'])){
			$proSkuData = ProductModel::getRowProSpecById($param['skuid'],"id,productid");
			
			if(empty($proSkuData['id'])){
				return ["code"=>5005,"msg"=>"商品信息不存在"];
			}else{
				if($proSkuData['productid'] != $param['productid']){
					return ["code"=>5009,"msg"=>"不存在的商品规格"];
				}
			}
		}

		$cartData = self::getCartProduct($param['customerid'],$param['productid'],$param['skuid']);


		if($cartData['code'] == 404){
			return $cartData;
		}



	  

		if($cartData['code'] == 5007){

			$OrdShoppingcart = Db::Model('OrdShoppingcart');

			$getCartData = $OrdShoppingcart->getRow(['customerid'=>$param['customerid'],"businessid"=>$proData['businessid']]);

			if(!empty($getCartData)){
				$OrdShoppingcart->update(['addtime'=>date('Y-m-d H:i:s')],['customerid'=>$param['customerid'],"businessid"=>$proData['businessid']]);
			}else{
				$OrdShoppingcart->insert(
					["customerid"=>$param['customerid'],
					 "businessid"=>$proData['businessid'],
					 "addtime"=>date('Y-m-d H:i:s')
					]
					);
			}
			
			$OrdShoppingcartItem = Db::Model('OrdShoppingcartItem');

			$addData = [
				'customerid'=>$param['customerid'],
				'businessid'=>$proData['businessid'],
				'productid' =>$param['productid'],
				'productnum'=>$param['productnum'],
				'skuid' => $param['skuid'],
				'sku_code'=>$param['sku_code'],
				'addtime' => date('Y-m-d H:i:s')
			];
		
			$ret = $OrdShoppingcartItem->insert($addData);
			
			if($ret > 0){
				return ["code"=>200,"msg"=>"添加成功",'data'=>$ret];
			}else{
				return ["code"=>400,"msg"=>"操作有误",'data'=>$ret];
			}

		}

		if($cartData['code'] == 200){
			$num = $param['productnum'] + $cartData['data']['productnum'];
			return $data = self::updateCartNum($cartData['data']['id'],$param['customerid'],$num);
		}
		
		
	}


	/**
	 * [addOderCart 更新spec购物车]
	 * @Author   ISir<673638498@qq.com>
 	 * @Date 2017-03-02 
	 */
	public static function updateSpecOderCart($param){

	

		$OrdShoppingcartItem = Db::Model('OrdShoppingcartItem');

		if(!isset($param['customerid']) || empty($param['customerid'])){
			return ['code'=>104,'msg'=>'用户未登录'];//用户未登录
		}

		if(empty($param['productid']) || empty($param['productnum']) || empty($param['productnum'])){
			return ['code'=>404,"msg"=>'参数有误'];
		}

		//查看商品信息
		$proData = ProductModel::getProDetailById($param['productid'],"id as productid,businessid,enable");
		$proData = $proData['data'];
		if(empty($proData)){
			return ['code'=>5005,'msg'=>'商品信息不存在'];//商品信息不存在
		}
		
		if($proData['enable'] != 1){
			return ["code"=>5006,"msg"=>"商品已下架"];
		}

		if(!empty($param['skuid'])){
			$proSkuData = ProductModel::getRowProSpecById($param['skuid'],"id,productid");
			
			if(empty($proSkuData['id'])){
				return ["code"=>5005,"msg"=>"商品信息不存在"];
			}else{
				if($proSkuData['productid'] != $param['productid']){
					return ["code"=>5009,"msg"=>"不存在的商品规格"];
				}
			}
		}
	
		$cartData = self::getCartProduct($param['customerid'],$param['productid'],$param['skuid']);
		

		if($cartData['code'] == 404){
			return $cartData;
		}

		if($cartData['code'] == 5007){

			$OrdShoppingcart = Db::Model('OrdShoppingcart');

			$getCartData = $OrdShoppingcart->getRow(['customerid'=>$param['customerid'],"businessid"=>$proData['businessid']]);
		
			if(!empty($getCartData)){
				
				$OrdShoppingcart->update(['addtime'=>date('Y-m-d H:i:s')],['customerid'=>$param['customerid'],"businessid"=>$param['businessid']]);

			}else{
				$OrdShoppingcart->insert(
					["customerid"=>$param['customerid'],
					 "businessid"=>$proData['businessid'],
					 "addtime"=>date('Y-m-d H:i:s')
					]
					);
			}
			
			
			$addData = [
				'customerid'=>$param['customerid'],
				'businessid'=>$proData['businessid'],
				'productid' =>$param['productid'],
				'productnum'=>$param['productnum'],
				'skuid' => $param['skuid'],
				'sku_code'=>$param['sku_code'],
				'addtime' => date('Y-m-d H:i:s')
			];

			//$ret = $OrdShoppingcartItem->insert($addData);
			
			$OrdShoppingcartItem->update($addData,['id'=>$param['oldcartid']]);
			//$OrdShoppingcartItem->delete(['id'=>$param['oldcartid']]);
			return ["code"=>200,"msg"=>"添加成功",'data'=>$param['oldcartid']];
			

		}

		if($cartData['code'] == 200){
			//$num = $param['productnum'] + $cartData['data']['productnum'];
			$num = $param['productnum'] ;
			$data = self::updateCartNum($cartData['data']['id'],$param['customerid'],$num);
			
			// if($data['code'] == 200)
			// 	$OrdShoppingcartItem->delete(['id'=>$param['oldcartid']]);
			return $data;
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
	
		if(empty($productid) || empty($customerid)){
		
			return ["code"=>404,"msg"=>"参数有误"];
		}

		$OrdShoppingcartItem = Db::Model('OrdShoppingcartItem');

		if(!empty($skuid)){
			$where = [
				'customerid' => $customerid,
				'productid'  => $productid,
				'skuid'      => $skuid
			];
			$data = $OrdShoppingcartItem->getRow($where);
		}else{
			$where = [
				'customerid' => $customerid,
				'productid'  => $productid
			];
			$data = $OrdShoppingcartItem->getRow($where);
		}

		if(!empty($data)){
			return ["code"=>200,"data"=>$data];
		}else{
			return ["code"=>5007,"msg"=>"购物车信息不存在"];
		}
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
		}
		
		$data = self::getCartInfoById($cartid);
		//购物车信息不存在
		if($data['code'] != 200){
			return $data;
		}

	
		//判断购物车信息是否属于当前用户
		if($data['data']['customerid'] != $customerid){
			return ["code"=>5008,"msg"=>"你不能修改该购物车信息"];
		}

	

		$OrdShoppingcartItem = Db::Model('OrdShoppingcartItem');
		//修改该购物车数量
		$ret = $OrdShoppingcartItem->update(['productnum'=>$num],['customerid'=>$customerid,'id'=>$cartid]);
		
		if($ret > 0){
			return ["code"=>200,"data"=>$ret];
		}else{
			return ["code"=>400,"msg"=>"操作有误"];
		}

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

		$OrdShoppingcartItem = Db::Model('OrdShoppingcartItem');
		$data = $OrdShoppingcartItem->getRow(['id'=>$cartid]);
		
		if(!empty($data)){
			return ["code"=>200,"data"=>$data];
		}else{
			return ["code"=>5007,"msg"=>'购物车信息不存在'];	
		}
	}

	/**
	 * [deletCartProduct 删除购物车商品]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-02T20:11:43+0800
	 * @return   [type]                   [description]
	 */
	public static function deletCartProduct($cartid,$customerid,$businessid){

		if(empty($cartid) || empty($customerid)){
			return ["code"=>404,"msg"=>"参数有误"];
		}
		$OrdShoppingcartItem = Db::Model('OrdShoppingcartItem');
		$OrdShoppingcart = Db::Model('OrdShoppingcart');

		$data = self::getCartInfoById($cartid);

		if(!empty($data['data'])){
			if($data['data']['customerid'] == $customerid){
				$OrdShoppingcartItem->delete(['id'=>$cartid]);
			}
		}

		$itemData = $OrdShoppingcartItem->getRow(['businessid'=>$businessid,"customerid"=>$customerid]);
		if(empty($itemData))
				$OrdShoppingcart->delete(['businessid'=>$businessid,"customerid"=>$customerid]);

		return ["code"=>200,"msg"=>'成功删除'];
	}

	/**
	 * [getCartGoodsList 购物车商品列表]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-03T21:00:17+0800
	 * @return   [type]                   [description]
	 */
	public static function getCartGoodsList($customerid){
		$data = [
			"cardata"=>[
				'cargoodsgount'=>"",
				'pricecount'=>"",
				'bullamountcount'=>"",
			],
			"goodsList"=>[
			]
		];
		if(empty($customerid)){
			return ["code"=>200,"data"=>$data];
		}

		$ShoppingCart = Db::Model('ShoppingCart');
		$cartData = $ShoppingCart->getList(['customerid'=>$customerid],'*','addtime desc');
		
		$OrdShoppingcartItem = Db::Model('OrdShoppingcartItem');
		$ProProduct = Model::ins("ProProduct");
        
		//print_r($cartData);

		if(!empty($cartData)){
			$carGoodsCount = 0;
			$priceCount = 0;
			$bullamountCount = 0;

			foreach ($cartData as $key => $value) {
		
					//echo $cartData[$i]['businessid'];
					$busData = Model::ins('BusBusinessInfo')->getRow(['id'=>$value['businessid']],'businessname');// BusinessModel::getBusinessInfoById($value['businessid'],'businessname');

					if(!empty($busData))
						$cartData[$key] = array_merge($value,$busData);

					$itemData = $OrdShoppingcartItem->getList(['businessid'=>$value['businessid'],'customerid'=>$value['customerid']],"id as cartid,productid,productnum,skuid");

					// $count = $OrdShoppingcartItem->getRow(['businessid'=>$value['businessid'],'customerid'=>$value['customerid']],"count(*) as count");
					// print_r($count);
					// $carGoodsCount += $count['count'];
					//print_r($itemData);
					
					foreach ($itemData as $k => $v) {
						// print_r($v);
						//$goodsData = ProductModel::getProDetailById($v['productid'],'id as productid,thumb,productname,prouctprice,bullamount,enable,businessid,productstorage',$customerid);
						$goodsData = $ProProduct->getRow(['id'=>$v['productid']],'id as productid,thumb,productname,prouctprice,bullamount,enable,businessid,productstorage');
						if(!empty($goodsData)){
							$itemData[$k]['productname'] = $goodsData['productname'];
							$itemData[$k]['productimage'] = Img::url($goodsData['thumb']);
							$itemData[$k]['prouctprice'] = DePrice($goodsData['prouctprice']);
							$itemData[$k]['bullamount'] = DePrice($goodsData['bullamount']);
							$itemData[$k]['spec']  = "";
							$itemData[$k]['businessid'] = $goodsData['businessid'];
							$itemData[$k]['enable'] = $goodsData['enable'];
							$itemData[$k]['productstorage'] = $goodsData['productstorage'];
							
							$specGoodsData = ProductModel::getRowProSpecById($v['skuid'],'productimage,productname,prouctprice,bullamount,spec,productstorage');
							
							if(!empty($specGoodsData)){
								$itemData[$k]['productname'] = $specGoodsData['productname'];
								
								if(!empty($specGoodsData['productimage'])){
									$itemData[$k]['productimage'] =Img::url($specGoodsData['productimage']);
								}else{
									$itemData[$k]['productimage'] =Img::url($goodsData['thumb']);
								}


								$itemData[$k]['prouctprice'] = DePrice($specGoodsData['prouctprice']);
								$itemData[$k]['bullamount'] = DePrice($specGoodsData['bullamount']);
								$itemData[$k]['productstorage'] = $specGoodsData['productstorage'];
								$spec = json_decode($specGoodsData['spec'],true);
								
								$string = '';
								foreach ($spec as $ka => $va) {
									$string .= $va['name'] . ":" . $va['value'] .";";
								}
								$itemData[$k]['spec'] = rtrim($string,',');
							}
							$carGoodsCount++;
							
						

						}else{
						 	
							unset($itemData[$k]);
						}

						$priceCount += ($itemData[$k]['prouctprice']*$v['productnum']);
						$bullamountCount += ($itemData[$k]['bullamount']*$v['productnum']);
					}
					//print_r($itemData);
					//sort($itemData);
					$tmp_item = [];
					foreach ($itemData as $itemkey => $itemvalue) {
						$tmp_item[] = $itemvalue;
					}

					if(!empty($itemData)){
						$cartData[$key]['goodsData'] = $tmp_item;
					}else{
						unset($cartData[$key]);
					}
					
			}
			//print_r($cartData);
			$tmp_car = [];
			foreach ($cartData as $key => $value) {
				$tmp_car[] = $value;
			}
			$data['cardata'] = ['cargoodsgount'=>$carGoodsCount,"pricecount"=>$priceCount,"bullamountcount"=>$bullamountCount];
			//print_r($cartData);
			$data['goodsList'] = $tmp_car;
			
			return ['code'=>'200','data'=>$data];

		}else{

			return ["code"=>200,"msg"=>'该用户购物车信息为空','data'=>$data];	
		}
	}	

}