<?php
// +----------------------------------------------------------------------
// |  [ 订单评价 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-04
// +----------------------------------------------------------------------

namespace app\api\controller\Order;
use app\api\ActionController;

use \think\Config;

use app\model\Order\EvaluateModel;
use app\model\User\UserModel;
use app\model\Product\ProductModel;
use app\model\Order\OrderModel;
use app\model\User\TokenModel;

class EvaluateorderController extends ActionController{

	/**
	 * [EvaluateOrderInfoAction 订单评价页面详情]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-06T11:39:21+0800
	 */
	public function EvaluateOrderInfoAction() {

			if(!empty($this->params['mtoken']) && !empty($this->params['orderno']) ){
				
				$TokenModel = new TokenModel();
				$this->params['customerid'] = $TokenModel->getTokenId($this->params['mtoken'])['id'];

				$OrderModel = new OrderModel();
				$orderRowData = $OrderModel->orderRowData($this->params['orderno']);


				if($orderRowData['customerid'] == $this->params['customerid']){
					$param['where'] = ['orderno'=>$this->params['orderno']];
					$param['fields'] = 'id as itemid,businessid,businessname,productid,productname,skuid,prouctprice/100 as prouctprice,bullamount,thumb,orderno,productnum';
					$data = $OrderModel->orderItemDetailList($param);

					// $data['prouctprice'] = custom_number_format($data['prouctprice']);
					//返回json数据
	       			return $this->json('200',$data);

				}else{
					//返回json数据
	       			return $this->json('1001');
				}

			}else{
				//返回json数据
	       		return $this->json('404');
			}
	}

	/**
	 * [pendingEvaluatOrder 添加订单评价]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-04T16:43:57+0800
	 * @return   [type]                   [description]
	 */
	public function addEvaluateOrderAction(){

		
		if(!empty($this->params['mtoken']) && !empty($this->params['orderno'])){
		
			$TokenModel = new TokenModel();
			$this->params['customerid'] = $TokenModel->getTokenId($this->params['mtoken'])['id'];
			
			$strLen = getStringLength($this->params['content']);

			if($strLen > 500){
				return $this->json('5012');
			}

			//是否匿名评价 默认匿名
			$this->params['isanonymous']  = !empty($this->params['isanonymous']) ? $this->params['isanonymous'] : 1;

			// if($this->Version("1.0.4")){
				$EvaluateArr = empty($this->params['EvaluateArr']) ? $this->params['evaluatearr'] : $this->params['EvaluateArr'];
			// }else{
			// 	$EvaluateArr = $this->params['EvaluateArr'];
			// }
			
			if(empty($EvaluateArr))
				return $this->json(404);

			// $adData = [
			// 	['customerid'=>1,'isanonymous'=>1,'businessid'=>1,'prouctprice'=>256800,'productid'=>1,'productname'=>'美的光波微波炉智能控制温度大白Cay100','scores'=>'4','orderno'=>'nnhpro-124','content'=>'56asdfasdf748','skuid'=>1],
			// 	['customerid'=>1,'isanonymous'=>1,'businessid'=>2,'prouctprice'=>88800,'productid'=>3,'productname'=>'JackJones杰克琼斯暗条纹纯棉合体男装春季长袖衬衫E|217105544 小码','scores'=>'5','orderno'=>'nnhpro-124','content'=>'asdfasdf56748','skuid'=>7],
			// 	['customerid'=>1,'isanonymous'=>1,'businessid'=>1,'prouctprice'=>256800,'productid'=>2,'productname'=>'Sharp/夏普 LCD-60SU465A 60英寸4K高清网络智能液晶平板电视机65','scores'=>'5','orderno'=>'nnhpro-124','content'=>'56asd7asdfasdfwerqwe48','skuid'=>4],

			// ];
			//解析json 数组
			$adData = preg_replace('/\\\/i','', $EvaluateArr);
			$adData = json_decode($adData,ture);
			
			foreach ($adData as $key => $value) {
				$adData[$key]['customerid'] =  $this->params['customerid'];
				$adData[$key]['orderno'] =   $this->params['orderno'];
			}
			
			$data = EvaluateModel::addEvaluateOrder($adData);
			
			//Model::new("Order.OrderCount")->deCount($this->params['customerid'],"count_evaluate");
			
			if($data['code'] == 200){
			
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