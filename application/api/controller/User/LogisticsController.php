<?php
// +----------------------------------------------------------------------
// |  [ 用户收货地址管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-06
// +----------------------------------------------------------------------
namespace app\api\controller\User;

use app\api\ActionController;
use app\model\User\LogisticsModel;
use app\model\User\TokenModel;

class LogisticsController extends ActionController {

	/**
	 * [logisticsList 收货地址列表]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-06T15:34:09+0800
	 * @return   [type]                   [description]
	 */
	public function logisticsListAction(){

	
		$this->params['customerid'] = empty($this->params['customerid']) ?  $this->userid : $this->params['customerid'];
        $data = LogisticsModel::getCustomerLogisticsByCustomerid($this->params['customerid']);
        if(empty($data)){
        	$data = [];
        }
        return $this->json('200',['data'=>$data]);
	
	}

	/**
	 * [setDefaultlogistic 设置默认收货地址]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-06T16:21:54+0800
	 */
	public function setDefaultlogisticAction(){
		$this->params['customerid'] = empty($this->params['customerid']) ?  $this->userid : $this->params['customerid'];

        $data = LogisticsModel::setDefaultlogistic($this->params['customerid'],$this->params['logisticid']);

        return $this->json($data['code'],['data'=>$data['data']]);
	
	}

	/**
	 * [addCustomerLogisticAction 添加用户收货地址]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-06T16:30:52+0800
	 */
	public function addCustomerLogisticAction(){
	
		$this->params['customerid'] = empty($this->params['customerid']) ?  $this->userid : $this->params['customerid'];
		
		
		if(phone_filter($this->params['mobile'])){
			return $this->json('20006');
		}

		
      	$addData = [
      		'customerid' => $this->params['customerid'],
      		'realname' => $this->params['realname'],
      		'city_id' => $this->params['city_id'],
      		'city' => $this->params['city'],
      		'address' => $this->params['address'],
      		'isdefault' => $this->params['isdefault'],
      		'mobile' => $this->params['mobile']
      	];
        $data = LogisticsModel::addLogistic($addData);

        return $this->json($data['code'],['data'=>$data['data']]);
	
	}

	/**
	 * [updateCustomerLogisticAction 修改收货地址信息]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-06T16:48:16+0800
	 * @return   [type]                   [description]
	 */
	public function updateCustomerLogisticAction(){

		$this->params['customerid'] = empty($this->params['customerid']) ?  $this->userid : $this->params['customerid'];

		if(phone_filter($this->params['mobile'])){
				return $this->json('20006');
		}

		
        unset($this->params['mtoken']);
        unset($this->params['_apiname']);
       	$upData = [
      		'customerid' => $this->params['customerid'],
      		'realname' => $this->params['realname'],
      		'city_id' => $this->params['city_id'],
      		'city' => $this->params['city'],
      		'address' => $this->params['address'],
      		'isdefault' => $this->params['isdefault'],
      		'mobile' => $this->params['mobile'],
      		'logisticid' => $this->params['logisticid']
      	];
        $data = LogisticsModel::updateLogistic($upData);

        return $this->json($data['code'],['data'=>$data['data']]);
	
	}

	/**
	 * [delCustomerLogisticAction description]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-06T21:05:19+0800
	 * @return   [type]                   [description]
	 */
	public function delCustomerLogisticAction(){
		if(!empty($this->params['logisticid']) ){
			$this->params['customerid'] = empty($this->params['customerid']) ?  $this->userid : $this->params['customerid'];
		
            unset($this->params['mtoken']);
            unset($this->params['_apiname']);

            $data = LogisticsModel::delCustomerLogistic($this->params);

            return $this->json($data['code'],['data'=>$data['data']]);
		}else{
			return $this->json('404');
		}
	}
}