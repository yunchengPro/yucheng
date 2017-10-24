<?php
// +----------------------------------------------------------------------
// | 牛牛汇 [ 申请成为商家、经理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{datetime}}
// +----------------------------------------------------------------------
namespace app\model\Customer;
use app\lib\Model;
use think\Config;

class ApplyModel{

	/**
	 * [ApplyBus 申请成为商家]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-10-10T15:46:14+0800
	 */
	public static function ApplyBus($param){

		if(empty($param['role']))
			return ['code'=>30005,'msg'=>'申请人角色为空'];

		if($param['role'] == 2)
			return ['code'=>30001,'msg'=>'你已经是商家了'];

		if($param['role'] >= 2)
			return ['code'=>30002,'msg'=>'不能逆向申请角色'];

		//$cus_relation = Model::ins('CusRelation')->getRow(['parentid'=>$param['customerid'],'role'=>3],'count(*) as count');

		if(empty($param['customerid']))
			return ['code'=>30003,'msg'=>'申请人不能为空'];

		

		if(empty($param['name']))
			return ['code'=>30006,'msg'=>'开通商家姓名不能为空'];

		// if(empty($param['mobile']))
		// 	return ['code'=>404,'msg'=>'开通商家手机号码不能为空'];

		if(empty($param['area_code']))
			return ['code'=>30007,'msg'=>'请选择开通商家所在市区'];

		if(empty($param['address']))
			return ['code'=>30008,'msg'=>'请填写通商家详细地址'];

		if(empty($param['join_type']))
			return ['code'=>30009,'msg'=>'请选择加盟方式'];

		$mobile_row = Model::ins('CusCustomer')->getRow(['id'=>$param['customerid']],'mobile');
		$mobile = $mobile_row['mobile'];
		if(empty($mobile))
			return ['code'=>30010,'msg'=>'开通商家手机号码不能为空'];

		$bus_row = Model::ins('RoleApplyBus')->getRow(['mobile'=>$mobile],'id,orderno');

		if(!empty($bus_row))
			return ['code'=>30011,'msg'=>'你已经申请过成为商家，不能重复申请','data'=>['orderno'=>$bus_row['orderno']]];

		$orderno = self::getABusOrderNo();
		$insert = [
			'customerid' => $param['customerid'],
			'role'       => $param['role'],
			'name'       => $param['name'],
			'mobile'     => $mobile,
			'area'       => $param['area'],
			'area_code'  => $param['area_code'],
			'address'    => $param['address'],
			'addtime'    => date('Y-m-d H:i:s'),
			'orderno'    => $orderno,
			'join_type'  => $param['join_type'],
			'status'     => 1
		];
		$ret = Model::ins('RoleApplyBus')->insert($insert);
		if($ret > 0){
			return ['code'=>200,'msg'=>"操作成功！",'data'=>['orderno'=>$orderno]];
		}else{
			return ['code'=>400,'msg'=>'操作有误，请重新提交！'];
		}
	} 

	/**
	 * 生成订单编号
	 * @Author   zhuangqm
	 * @DateTime 2017-03-03T16:30:47+0800
	 * @return   [type]                   [description]
	 */
	public static function getABusOrderNo(){
		return "ABUS".date("YmdHis").rand(100000,999999);
	}

	/**
	 * [ApplyManerger 申请成为经理]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-10-10T15:47:10+0800
	 * @param    [type]                   $param [description]
	 */
	public static function ApplyManerger($param){

		$role_config = Config::get('role');

		if($param['role'] >= 3)
			return ['code'=>30002,'msg'=>'不能逆向申请角色'];

		//查找当前用户 上级总监
		$relationlist_row = Model::ins('CusRelationList')->getRow(['customerid'=>$param['customerid'],'parentrole'=>4],'parentid');

		$manerger_count = Model::ins('CusRelationList')->getRow(['parentid'=>$relationlist_row['parentid'],'role'=>3],'count(*) as count');
		
		if($manerger_count['count'] >=$role_config['manager_count'])
			return ['code'=>30012,'msg'=>'申请经理名额已达上限'];
		
		if(empty($param['customerid']))
			return ['code'=>30003,'msg'=>'申请人不能为空'];

		if(empty($param['role']))
			return ['code'=>30005,'msg'=>'申请人角色为空'];

		if(empty($param['name']))
			return ['code'=>30013,'msg'=>'区代理姓名不能为空'];

		// if(empty($param['mobile']))
		// 	return ['code'=>404,'msg'=>'区代理手机号码不能为空'];

		if(empty($param['area_code']))
			return ['code'=>30015,'msg'=>'请选择区代理所在市区'];

		if(empty($param['address']))
			return ['code'=>30016,'msg'=>'请填写区代理详细地址'];

		if(empty($param['join_type']))
			return ['code'=>30009,'msg'=>'请选择加盟方式'];

		$mobile_row = Model::ins('CusCustomer')->getRow(['id'=>$param['customerid']],'mobile');
		$mobile = $mobile_row['mobile'];
		if(empty($mobile))
			return ['code'=>30017,'msg'=>'区代理手机号码不能为空'];

		$manager_row = Model::ins('RoleApplyManager')->getRow(['mobile'=>$mobile],'id,orderno');

		if(!empty($manager_row))
			return ['code'=>30018,'msg'=>'你已经申请过成为区代理，不能重复申请','data'=>['orderno'=>$manager_row['orderno']]];
		$orderno = self::getAMANOrderNo();
		$insert = [
			'customerid' => $param['customerid'],
			'role'       => $param['role'],
			'name'       => $param['name'],
			'mobile'     => $mobile,
			'area'       => $param['area'],
			'area_code'  => $param['area_code'],
			'address'    => $param['address'],
			'addtime'    => date('Y-m-d H:i:s'),
			'orderno'    => $orderno,
			'join_type'  => $param['join_type'],
			'status'     => 1
		];
		$ret = Model::ins('RoleApplyManager')->insert($insert);
		if($ret > 0){
			return ['code'=>200,'msg'=>"操作成功",'data'=>['orderno'=>$orderno]];
		}else{
			return ['code'=>400,'msg'=>'操作有误，请重新提交！'];
		}
	}

	/**
	 * 生成订单编号
	 * @Author   zhuangqm
	 * @DateTime 2017-03-03T16:30:47+0800
	 * @return   [type]                   [description]
	 */
	public static function getAMANOrderNo(){
		return "AMAN".date("YmdHis").rand(100000,999999);
	}

	/**
	 * [getApplyBusOrder 获取申请记录]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-10-21T14:39:22+0800
	 * @param    [type]                   $param [description]
	 * @return   [type]                          [description]
	 */
	public function getApplyBusOrder($params){
		$orderno = $params['orderno'];
		$customerid = $params['customerid'];
		if(empty($orderno) || empty($customerid))
			return ['code'=>404,'msg'=>'参数错误'];

		$apply_row = Model::ins('RoleApplyBus')->getRow(['orderno'=>$orderno]);

		if(empty($apply_row))
			return ['code'=>30023,'msg'=>'申请记录不存在'];

		if($apply_row['customerid'] != $customerid)
			return ['code'=>406,'msg'=>'无权操作'];

		return ['code'=>200,'data'=>$apply_row];
	}

	/**
	 * [getApplyManOrder 获取申请记录 代理商]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-10-21T15:37:03+0800
	 * @return   [type]                   [description]
	 */
	public function getApplyManOrder($params){
		$orderno = $params['orderno'];
		$customerid = $params['customerid'];
		if(empty($orderno) || empty($customerid))
			return ['code'=>404,'msg'=>'参数错误'];

		$apply_row = Model::ins('RoleApplyManager')->getRow(['orderno'=>$orderno]);

		if(empty($apply_row))
			return ['code'=>30023,'msg'=>'申请记录不存在'];

		if($apply_row['customerid'] != $customerid)
			return ['code'=>406,'msg'=>'无权操作'];

		return ['code'=>200,'data'=>$apply_row];
	}

}