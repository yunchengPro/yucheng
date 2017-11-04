<?php
// +----------------------------------------------------------------------
// | 牛牛汇 [ 用户收藏模型 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{datetime}}
// +----------------------------------------------------------------------
namespace app\model\User;

use app\lib\Model;
use app\lib\Img;
class CollectionModel{


	/**
	 * [addCollection 添加收藏]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-05-18T16:47:08+0800
	 * @param    [type]                   $params [description]
	 */
	public static function addCollection($params){

		if(!empty($params) && !empty($params['type']) && !empty($params['userid']) && !empty($params['obj_id']) )  {

			//登录后方能操作
			$customer = Model::ins('CusCustomer')->getRow(['id'=>$params['userid']],'id');
			if(empty($customer))
				return ['code'=>8009,'msg'=>'非法用户'];
			//1、先判断收藏类型 
			switch ($params['type']) {
				case 3: //收藏实体店  收藏类型 1类型收藏的商品 2收藏的供应商 3收藏的实体店
					$hasid = Model::Mongo('StoBusinessInfo')->getRow(['id'=>$params['obj_id'],'enable'=>1],'id,businessid');
					break;
				case 1: //收藏商品
					$hasid = Model::ins('ProProduct')->getRow(['id'=>$params['obj_id'],'enable'=>1],'id');
					break;
				case 2: //收藏供应商
					$hasid = Model::ins('BusBusiness')->getRow(['id'=>$params['obj_id'],'enable'=>1],'id');
					break;
				default:
					break;
			}
			//2、收藏之前判断商品或店铺是否存在
			if(empty($hasid['id']))
				return ['code'=>60009,'msg'=>'收藏或取消收藏的信息不存在'];
			// 3、已经收藏的不能再收藏
			$collectData = Model::ins('CusCollectionObj')->getRow(['type'=>$params['type'],'obj_id'=>$params['obj_id'],'customerid'=>$params['userid']],'id');

			if(!empty($collectData['id']))
				return ['code'=>60010,'msg'=>'不能重复收藏该信息'];

			//4、写入客户id 收藏关系主键 收藏类型 收藏时间
			$insert = [
				'customerid' => $params['userid'],
				'type'   => $params['type'],
				'obj_id' => $params['obj_id'],
				'addtime' => date('Y-m-d H:i:s')
 			];

 			$ret = Model::ins('CusCollectionObj')->insert($insert);
 			if($ret){

 				if($params['type'] == 2){ //5、添加商品或店铺被收藏次数 收藏类型 1类型收藏的商品 2收藏的供应商 3收藏的实体店
 					$busData = Model::ins('BusBusinessInfo')->getRow(['id'=>$params['obj_id']],'id,collectcount');
 					
 					$collectcount =  intval($busData['collectcount'])+1;
 					
 					Model::ins('BusBusinessInfo')->update(['collectcount'=>$collectcount],['id'=>$busData['id']]);
 				}

 				return ['code'=>200,'msg'=>'操作成功','data'=>$ret];
 			}else{
 				return ['code'=>400,'msg'=>'操作有误'];
 			}
		}else{
			return ['code'=>404,'msg'=>'参数错误'];
		}

	}

	/**
	 * [cancelCollection 取消收藏]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-05-18T17:30:42+0800
	 * @return   [type]                   [description]
	 */
	public static function cancelCollection($params){

		if(!empty($params) && !empty($params['type']) && !empty($params['userid']) && !empty($params['obj_id']) ){
			
			//登录后方能操作
			$customer = Model::ins('CusCustomer')->getRow(['id'=>$params['userid']],'id');
			if(empty($customer))
				return ['code'=>8009,'msg'=>'非法用户'];

				//登录后方能操作
			$customer = Model::ins('CusCustomer')->getRow(['id'=>$params['userid']],'id');
			if(empty($customer))
				return ['code'=>8009,'msg'=>'非法用户'];
			
			//1、先判断收藏类型 
			switch ($params['type']) {
				case 3: //收藏实体店  收藏类型 1类型收藏的商品 2收藏的供应商 3收藏的实体店
					$hasid = Model::Mongo('StoBusinessInfo')->getRow(['id'=>$params['obj_id'],'enable'=>1],'id,businessid');
					break;
				case 1: //收藏商品
					$hasid = Model::ins('ProProduct')->getRow(['id'=>$params['obj_id'],'enable'=>1],'id');
					break;
				case 2: //收藏供应商
					$hasid = Model::ins('BusBusiness')->getRow(['id'=>$params['obj_id'],'enable'=>1],'id');
					break;
				default:
					break;
			}
			//2、收藏之前判断商品或店铺是否存在
			if(empty($hasid['id']))
				return ['code'=>60009,'msg'=>'收藏或取消收藏的信息不存在'];

			// 3、取消收藏前判断是否收藏过商品
			$collectData = Model::ins('CusCollectionObj')->getRow(['type'=>$params['type'],'obj_id'=>$params['obj_id'],'customerid'=>$params['userid']],'id');

			if(empty($collectData))
				return ['code'=>60011,'msg'=>'没有收藏过该信息'];
			//4、删除记录
			$ret = Model::ins('CusCollectionObj')->delete(['type'=>$params['type'],'obj_id'=>$params['obj_id'],'customerid'=>$params['userid']]);

			if($ret){

				// if($params['type'] == 2){ //5、减掉商品或店铺被收藏次数 收藏类型 1类型收藏的商品 2收藏的供应商 3收藏的实体店
 			// 		$busData = Model::ins('BusBusinessInfo')->getRow(['id'=>$params['obj_id']],'id,collectcount');
 					
 			// 		$collectcount =  intval($busData['collectcount']) - 1;
 			// 		if($collectcount < 0){
 			// 			$collectcount = 0;
 			// 		}
 			// 		Model::ins('BusBusinessInfo')->update(['collectcount'=> $collectcount],['id'=>$busData['id']]);
 			// 	}

 				return ['code'=>200,'msg'=>'操作成功','data'=>$ret];
 			}else{
 				return ['code'=>400,'msg'=>'操作有误'];
 			}

		}else{
			return ['code'=>404,'msg'=>'参数错误'];
		}
	}

	/**
	 * [collectionList 收藏列表]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-05-18T18:01:04+0800
	 * @return   [type]                   [description]
	 */
	public static function collectionList($params){

		if(!empty($params) && !empty($params['type'] && !empty($params['userid']))){
			
			$dataList = [];
			$dataList['list'] = [];
			
			//登录后方能操作
			$customer = Model::ins('CusCustomer')->getRow(['id'=>$params['userid']],'id');
			if(empty($customer))
				return ['code'=>8009,'msg'=>'非法用户'];

			
			$hasid = Model::ins('CusCollectionObj')->pageList(['customerid'=>$params['userid'],'type'=>$params['type']],'type,obj_id','addtime desc');
			//print_r($hasid['total']);

			if($params['type'] == 3){  //收藏类型 1类型收藏的商品 2收藏的供应商 3收藏的实体店
				foreach ($hasid['list'] as $key => $value) {
					
					$datarRow = Model::Mongo('StoBusinessInfo')->getRow(['id'=>$value['obj_id']],'id,businesslogo,businessname,scores,isdelivery');
					if(!empty($datarRow)){

						unset($datarRow['_id']);

						$datarRow['delivery'] =  Model::ins('StoBusinessBaseinfo')->getRow(['id'=>$datarRow['id']],'delivery')['delivery'];
						$datarRow['businesslogo'] = Img::url($datarRow['businesslogo']);
						$datarRow['delivery'] = DePrice($datarRow['delivery']);
						if($datarRow['scores'] <= 0){
							$datarRow['scores'] = '5.0';
						}else{
							$datarRow['scores'] =  scoresFormat($datarRow['scores']);
						}

						$datarRow['thumb'] = '';
						$datarRow['productname'] = '';
						$datarRow['bullamount'] = '';
						$datarRow['prouctprice'] = '';

						
					
						$datarRow['collectcount'] = '';
						

						$dataList['list'][] = $datarRow;
						$total ++ ;
					}
				}
			}else if($params['type'] == 1){
				foreach ($hasid['list'] as $key => $value) {
				
					$datarRow = Model::ins('ProProduct')->getRow(['id'=>$value['obj_id'],'enable'=>1],'id,thumb,productname,bullamount,prouctprice');
					if(!empty($datarRow)){
						$datarRow['thumb'] = Img::url($datarRow['thumb']);
						$datarRow['bullamount'] = DePrice($datarRow['bullamount']);
						$datarRow['prouctprice'] =  DePrice($datarRow['prouctprice']);

						$datarRow['businessname'] = '';
						$datarRow['businesslogo'] = '';
						$datarRow['collectcount'] = '';
						$datarRow['scores'] = '';

						$datarRow['delivery'] = '';
						$datarRow['businessid'] = '';
						$datarRow['isdelivery'] = '';

						$dataList['list'][] = $datarRow;
						$total ++ ;
					}
				}
			}else if($params['type'] == 2){
				foreach ($hasid['list'] as $key => $value) {
					$datarRow = Model::ins('BusBusinessInfo')->getRow(['id'=>$value['obj_id']],'id,businessname,businesslogo,scores,collectcount');
					if(!empty($datarRow)){
						if($datarRow['scores'] <= 0){
							$datarRow['scores'] = '5.0';
						}else{
							$datarRow['scores'] =  scoresFormat($datarRow['scores']);
						}
						
						$datarRow['businesslogo'] = Img::url($datarRow['businesslogo']);
						$datarRow['collectcount'] = ceil($datarRow['collectcount']);

						$datarRow['thumb'] = '';
						$datarRow['productname'] = '';
						$datarRow['bullamount'] = '';
						$datarRow['prouctprice'] = '';

						$datarRow['delivery'] = '';
						$datarRow['isdelivery'] = '';

						$dataList['list'][] = $datarRow;
						$total ++ ;
					}
				}
			}else{
				return ['code'=>60012,'msg'=>'不存在的收藏类型'];
			}
			
			
			$dataList['total'] = $hasid['total'];

			return ['code'=>200,'data'=>$dataList];
		}else{
			return ['code'=>404];
		}
	}

	/**
	 * [checkCollectcount 检查是否收藏]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-05-19T12:31:10+0800
	 * @return   [type]                   [description]
	 */
	public static function checkCollectcount($params){

		$collectData = Model::ins('CusCollectionObj')->getRow(['type'=>$params['type'],'obj_id'=>$params['obj_id'],'customerid'=>$params['userid']],'id');

		if(empty($collectData)){
			return false;
		}else{
			return true;
		}
	}
}