<?php
namespace app\model\User;
use app\lib\Model;

class UserLogisticsModel{


	public function __construct(){
    }

    /**
     * [logisticsList 用户收货地址列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T20:46:12+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static  function logisticsList($param){

    	$customerid = $param['customerid'];

    	$logistics_list = Model::ins('OrdUserLogistics')->getList(['customerid'=>$customerid],'*','isdefault desc,id desc');
    	foreach ($logistics_list as $key => $value) {

    		$logistics_list[$key]['address_id'] = $value['id'];
    		unset($logistics_list[$key]['id']);
    	}
    	return ['code'=>200,'data'=>$logistics_list]; 
    }


    /**
	 * [addLogistic 添加用户收货地址]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-06T16:31:57+0800
	 * @param    [type]                   $param [description]
	 */
	public static function addLogistic($param){

		if(empty($param['realname']))
			return ['code'=>404,'data'=>'','msg'=>'收货人姓名不能为空'];

		if(empty($param['mobile']))
			return ['code'=>404,'data'=>'','msg'=>'收货人电话不能为空'];

		if(empty($param['city_id']))
			return ['code'=>404,'data'=>'','msg'=>'请选择收货人所在地区'];

		if(empty($param['address']))
			return ['code'=>404,'data'=>'','msg'=>'请填写收货人详细地址'];

		$OrdUserLogistics = Model::ins('OrdUserLogistics');
		$param['isdefault'] = !empty($param['isdefault']) ? $param['isdefault'] : -1;
		$LogisticData =  $OrdUserLogistics->getRow(['customerid'=>$param['customerid']],'id');

		if(empty($LogisticData['id'])){
			$param['isdefault'] = 1;
		}

		if($param['isdefault'] == 1){
			$oldData =  $OrdUserLogistics->getRow(['customerid'=>$param['customerid'],'isdefault'=>1],'id');
			//print_r($oldData);
			//去掉原来的默认地址
			if(!empty($oldData['id'])){
				 $OrdUserLogistics->update(['isdefault'=>-1],['id'=>$oldData['id']]);
			}
		}

		$data =  $OrdUserLogistics->insert($param);
		return ['code'=>200,'data'=>$data];
	}


    /**
     * [updateCustomerLogistic 修改收货地址]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-11T10:34:15+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public static function updateCustomerLogistic($param){

    	$logisticid =  $param['logisticid'];

    	if(empty($logisticid))
    		return ['code'=>404,'data'=>'','msg'=>'请选择要修改的收货地址信息'];

    	if(empty($param['realname']))
			return ['code'=>404,'data'=>'','msg'=>'收货人姓名不能为空'];

		if(empty($param['mobile']))
			return ['code'=>404,'data'=>'','msg'=>'收货人电话不能为空'];

		if(empty($param['city_id']))
			return ['code'=>404,'data'=>'','msg'=>'请选择收货人所在地区'];

		if(empty($param['address']))
			return ['code'=>404,'data'=>'','msg'=>'请填写收货人详细地址'];


    	

		$OrdUserLogistics = Model::ins('OrdUserLogistics');
		$LogisticData =  $OrdUserLogistics->getRow(['id'=>$param['logisticid']],'id,customerid');
		
		$param['isdefault'] = !empty($param['isdefault']) ? $param['isdefault'] : -1;

		if($LogisticData['customerid'] != $param['customerid']){
			return ['code'=>1001,'msg'=>"无操作权限"];
		}

		if($param['isdefault'] == 1){
			$oldData =  $OrdUserLogistics->getRow(['customerid'=>$param['customerid'],'isdefault'=>1],'id');
			//去掉原来的默认地址
			if(!empty($oldData['id'])){
				 $OrdUserLogistics->update(['isdefault'=>-1],['id'=>$oldData['id']]);
			}
		}
		
		unset($param['logisticid']);
		
		$data = $OrdUserLogistics->update($param,['id'=>$logisticid]);
		if($data > 0 ){
			return ['code'=>200,'data'=>$data];
		}else{
			return ['code'=>400,'msg'=>'操作有误'];
		}

    }

    	/**
	 * [setefaultlogistic 设置默认收货地址]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-06T16:07:17+0800
	 * @return   [type]                   [description]
	 */
	public static function setDefaultlogistic($param){

		$customerid =$param['customerid'];
		$id = $param['logisticid'];

		if(empty($id))
			return ['code'=>404,'msg'=>'参数错误'];

		$OrdUserLogistics =  Model::ins('OrdUserLogistics');

		$LogisticData =  $OrdUserLogistics->getRow(['id'=>$id]);

		if($LogisticData['customerid'] != $customerid){
			return ['code'=>1001,'msg'=>"无操作权限"];
		}

		$oldData =  $OrdUserLogistics->getRow(['customerid'=>$customerid,'isdefault'=>1],'id');

		//去掉原来的默认地址
		if(!empty($oldData['id'])){
			 $OrdUserLogistics->update(['isdefault'=>-1],['id'=>$oldData['id']]);
		}

		$data = $OrdUserLogistics->update(['isdefault'=>1],['id'=>$id]);
		return ['code'=>200,'data'=>$data];
	}

	/**
	 * [delCustomerLogistic 删除用户收货地址]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-06T21:06:37+0800
	 * @param    [type]                   $param [description]
	 * @return   [type]                          [description]
	 */
	public static function delCustomerLogistic($param){

		if(empty($param['logisticid']))
    		return ['code'=>404,'data'=>'','msg'=>'请选择要删除的收货地址信息'];

		$OrdUserLogistics =  Model::ins('OrdUserLogistics');
		$data = $OrdUserLogistics->getRow(['customerid'=>$param['customerid'],'id'=>$param['logisticid']],'id');
		//print_r($data);
		if(!empty($data)){
			$OrdUserLogistics->delete(['id'=>$data['id']]);
			return ['code'=>200,'msg'=>'成功删除'];
		}else{
			return ['code'=>5013,'msg'=>'不存在的收货地址'];
		}
	
	}
}