<?php
// +----------------------------------------------------------------------
// |  [ 订单评价相关模型 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-04
// +----------------------------------------------------------------------
namespace app\model\Order;

use app\lib\Db;

use app\lib\Img;
use app\model\Business\BusinessModel;
use app\model\User\UserModel;
use app\model\Product\ProductModel;
use app\model\OrdOrderItemModel;
use app\model\OrdOrderModel;
use app\model\ProEvaluateModel;
use app\model\ProEvaluateImageModel;
use app\lib\Model;
class EvaluateModel{

	/**
	 * [getPendingEvaluatOrder 获取待评价订单信息]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-04T16:47:05+0800
	 * @return   [type]                   [description]
	 */
	public static function addEvaluateOrder($param){


		if(!empty($param)){
			
				//获取用户名
			$UserModel = new UserModel();
			
			$OrdOrderItemModel = new OrdOrderItemModel();
			$OrdOrder = new OrdOrderModel();
			$ProEvaluateModel = new ProEvaluateModel();
			$ProEvaluateImage = new ProEvaluateImageModel();

			$ProProductInfo = Db::Model("ProProductInfo");
			$BusBusinessInfo = Db::Model("BusBusinessInfo");
			$ProEvaluate = Db::Model('ProEvaluate');

			foreach ($param as $value) {
				
				$orderData = 	$OrdOrder->getRow(['orderno'=>$value['orderno']],'orderno,customerid');	
				
				//验证订单信息是否存在 是否为当前客户订单
				if(!empty($orderData) && $orderData['customerid'] == $value['customerid']){
					if(empty($value['skuid'])){
						$item = $OrdOrderItemModel->getRow(['orderno'=>$value['orderno'],'productid'=>$value['productid']],'id,orderno,productid');
					}else{
						$item = $OrdOrderItemModel->getRow(['orderno'=>$value['orderno'],'productid'=>$value['productid'],'skuid'=>$value['skuid']],'id,orderno,productid');
					}
					
					//评论的商品是为购买的商品
					if(!empty($item)){

						$userData = Model::ins('CusCustomerInfo')->getRow(['id'=>$value['customerid']],'nickname,headerpic');
						$mobile  = Model::ins('CusCustomer')->getRow(['id'=>$value['customerid']],'mobile')['mobile'];
						
						//是否匿名评价 默认匿名
						$value['isanonymous']  = !empty($value['isanonymous']) ? $value['isanonymous'] : 1;

						//获取商品信息
						if(!empty($value['productid']) && !empty($value['skuid'])){

							$pData = Model::ins('ProProduct')->getRow(['id'=>$value['productid']],'businessid');

							$adData['skuid'] = $value['skuid'];
							$proData = ProductModel::getRowProSpecById($value['skuid'],'productname,prouctprice');
						
						}else if(!empty($value['productid'])){

							$pData = Model::ins('ProProduct')->getRow(['id'=>$value['productid']],'businessid');
							
							$proData = ProductModel::getProDetailById($value['productid'],'productname,prouctprice');
							
						}
						
						
						
						if(!empty($proData)){
							$strLength = getStringLength($value['content']);
							if($strLength <=500){
								$proData['businessid'] = $pData['businessid'];
								$adData['frommemberid'] = $value['customerid'];
								$adData['isanonymous'] = $value['isanonymous'];
								$adData['frommembername'] = !empty($userData['nickname']) ? $userData['nickname'] : $mobile;
								$adData['headpic'] = $userData['headerpic'] ;
								$adData['businessid'] = $proData['businessid'] ;
								$adData['productprice'] = $proData['prouctprice'] ;
								$adData['productid'] = $value['productid'] ;
								$adData['productname'] =  $proData['productname'] ;
								$adData['scores'] =   $value['scores'] ;
								$adData['orderno'] = $value['orderno'] ;
								$adData['content'] =  $value['content'] ;
								$adData['addtime'] =  date('Y-m-d H:i:s');


							
								$adData['evaluateauto'] = 2;

								$data = $ProEvaluate->insert($adData);
							}else{
								
								return ['code'=>5012,'msg'=>'评论内容超出字数！'];
							}
						}else{
							return ['code'=>5005,'msg'=>'商品信息不存在'];
						}
						
						
						if($data > 0){

						
							foreach ($value['evaluate_images'] as $value) {

							 	$ProEvaluateImage->insert(['thumb'=>$value,'evaluate_id'=>$data,'addtime'=>date('Y-m-d H:i:s')]);
							 	 
							} 
							
							$OrdOrderItemModel->update(['evaluate'=>1],['orderno'=>$adData['orderno'],'id'=>$item['id']]);//订单更新为已经评价


							$orderCount = $OrdOrderItemModel->getRow(['orderno'=>$adData['orderno']],'count(id) as count');

							$orderEvaluateCount = $OrdOrderItemModel->getRow(['orderno'=>$adData['orderno'],'evaluate'=>1],'count(id) as count');

							if($orderCount['count'] == $orderEvaluateCount['count']){
								$OrdOrder->update(['evaluate'=>1],['orderno'=>$adData['orderno']]);//订单更新为已经完成评价
							}

							


							$proArr = $ProProductInfo->getRow(['id'=>$adData['productid']],"scores,evaluatecount");

							$salecount = (int) $proArr['evaluatecount'] + 1;
							
							$scores = (int) $proArr['scores'] + (int) $adData['scores'];
							
							$ProProductInfo->update(["scores"=>$scores,"evaluatecount"=>$salecount,"lastevaluateid"=>$data],["id"=>$adData['productid']]);

							$newproArr = $ProProductInfo->getRow(['id'=>$adData['productid']],"scores,evaluatecount");
							
							$busScores = round(($newproArr['scores']/$newproArr['evaluatecount']),2);
							$ProProductInfo->update(['evaluatecount'=>$newproArr['evaluatecount']],['id'=>$adData['productid']]);
							//更新商家评分
							$BusBusinessInfo->update(['scores'=>$busScores],["id"=>$proData['businessid']]);

						}else{
							return ['code'=>400,'msg'=>'操作有误'];
						}
					}else{
						return ['code'=>1001,'msg'=>'无操作权限'];
					}
				}
			}
		}
		return ['code'=>200,'data'=>$data];
		
	}
}