<?php
// +----------------------------------------------------------------------
// |  [ 用户收货地址 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年10月10日20:36:33}}
// +----------------------------------------------------------------------

namespace app\sale\controller\User;
use app\sale\ActionController;


use app\lib\Model;
use app\model\User\UserLogisticsModel;

class UserlogisticsController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [logisticslistAction 收货地址列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-30T13:54:16+0800
     * @return   [type]                   [description]
     */
    public function logisticslistAction(){

    	

        $viewData = [
            'title' => "管理收货地址",
            'mtoken'=>$mtoken,

        ];
        
        return $this->view($viewData);
        
    }

    /**
     * [getlogisticsList 获取用户收货地址]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-30T14:13:59+0800
     * @return   [type]                   [description]
     */
    public function getlogisticsListAction(){
        
        if(empty($this->userid))
            return  $result = json_encode(['code'=>104,'msg'=>'请登录']);

        $result = UserLogisticsModel::logisticsList(['customerid'=>$this->userid]);
        
        return  $this->json($result['code'],$result['data'],$result['msg']);
    } 

    /**
     * [addlogisticsAction 添加收货地址]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-30T19:01:52+0800
     * @return   [type]                   [description]
     */
    public function addlogisticsAction(){
      
        $apiUrl = '/User/Userlogistics/doaddlogistics';
        $success = '添加成功';
        $viewData = [
            'title' => "添加收货地址",
            "customerid"=>$this->userid,
            'apiUrl'=>$apiUrl,
            'success'=>$success
        ];
        $this->addcheck();
        return $this->view($viewData);
    }

    /**
     * [editlogisticsAction 修改收货地址]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-30T14:57:36+0800
     * @return   [type]                   [description]
     */
    public function editlogisticsAction(){

        $address_id = $this->params['address_id'];
        $logistic = Model::ins('OrdUserLogistics')->getRow(['id'=>$address_id]);
        if($logistic['isdefault'] == 1){
            $isdefault = true;
        }else{
            $isdefault = false;
        }
        $area = Model::ins('SysArea')->getRow(['id'=>$logistic['city_id']],'id,areaname,parentid');
        $parentArea = Model::ins('SysArea')->getRow(['id'=>$area['parentid']],'id,areaname,parentid');
        $grandArea = Model::ins('SysArea')->getRow(['id'=>$parentArea['parentid']],'id,areaname,parentid');
       
        $hd_area = $grandArea['id'].','.$parentArea['id'].','.$area['id'];
       
        $hd_area_value = $grandArea['areaname'].','.$parentArea['areaname'].','.$area['areaname'];
        $apiUrl = '/User/Userlogistics/doeditlogistics';
        $success = '修改成功';
        $viewData = [
            'title' => "修改收货地址",
            "customerid"=>$this->userid,
            'logisticid'=>$address_id,
            'logistic'=>$logistic,
            'hd_area' => $hd_area,
            'hd_area_value' => $hd_area_value,
            'apiUrl'=>$apiUrl,
            'success'=>$success,
            'isdefault'=>$isdefault
        ];
      
        return $this->view($viewData);
    }



    /**
     * [addlogisticsAction 添加收货地址]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-30T19:08:03+0800
     * @return   [type]                   [description]
     */
    public function doaddlogisticsAction(){
       
        if(empty($this->userid))
            return  $result = json_encode(['code'=>104,'msg'=>'请登录']);

        $mtoken = $this->mtoken;
        $mobile = $this->params['mobile'];
        $realname = $this->params['realname'];
        $address = $this->params['address'];
        $isdefault = $this->params['isdefault'];
        $hd_area = $this->params['hd_area'];
        $area_arr = explode(',', $hd_area);
       
        $area = Model::ins('SysArea')->getRow(['id'=>$area_arr['2']],'id,areaname,parentid');
        $parent = Model::ins('SysArea')->getRow(['id'=>$area['parentid']],'id,areaname,parentid');
        $grand = Model::ins('SysArea')->getRow(['id'=>$parent['parentid']],'id,areaname,parentid');
        $city_id = $area_arr['2'];
        $city = $grand['areaname'].$parent['areaname'] . $area['areaname'];
        
        
        $param =[
            "customerid"=>$this->userid,
            "mobile"=>$mobile,
            "realname"=>$realname,
            "city_id"=>$city_id,
            "city"=>$city,
            "address"=>$address,
            "isdefault"=>$isdefault
        ];
           
      
        $result = UserLogisticsModel::addLogistic($param);
        
        return  $this->json($result['code'],$result['data'],$result['msg']);
    }

    /**
     * [doaddoreditlogisticsAction 修改收货地址]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-30T15:33:16+0800
     * @return   [type]                   [description]
     */
    public function doeditlogisticsAction(){
        
        if(empty($this->userid))
            return  $result = json_encode(['code'=>104,'msg'=>'请登录']);

        $logisticid = $this->params['logisticid'];
        $mobile = $this->params['mobile'];
        $realname = $this->params['realname'];
        $address = $this->params['address'];
        $isdefault = $this->params['isdefault'];
        $hd_area = $this->params['hd_area'];
        $area_arr = explode(',', $hd_area);
       
        $area = Model::ins('SysArea')->getRow(['id'=>$area_arr['2']],'id,areaname,parentid');
        $parent = Model::ins('SysArea')->getRow(['id'=>$area['parentid']],'id,areaname,parentid');
        $grand = Model::ins('SysArea')->getRow(['id'=>$parent['parentid']],'id,areaname,parentid');
        $city_id = $area_arr['2'];
        $city = $grand['areaname'].$parent['areaname'] . $area['areaname'];
            
        $param =[
                    "customerid"=>$this->userid,
                    "logisticid"=>$logisticid,
                    "mobile"=>$mobile,
                    "realname"=>$realname,
                    "city_id"=>$city_id,
                    "city"=>$city,
                    "address"=>$address,
                    "isdefault"=>$isdefault
                ];
     
        $result = UserLogisticsModel::updateCustomerLogistic($param);
        
        return  $this->json($result['code'],$result['data'],$result['msg']);
    }

    /**
     * [setDefaultlogisticAction 设置默认收货地址]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-30T19:27:19+0800
     */
    public function setDefaultlogisticAction(){
       
        if(empty($this->userid))
            return  $result = json_encode(['code'=>104,'msg'=>'请登录']);

       
        $logisticid = $this->params['logisticid'];
        $result = UserLogisticsModel::setDefaultlogistic(['logisticid'=>$logisticid,'customerid'=>$this->userid]);
        // $result = $this->_api([
        //         "actionname"=>"user.logistics.setDefaultlogistic",
        //         "param"=>[
        //             "customerid"=>$this->userid,
        //             "logisticid"=>$logisticid
        //         ],
        //     ]);
        
       return  $this->json($result['code'],$result['data'],$result['msg']);
    }


    /**
     * [apidelCustomerLogisticAction 删除收货地址]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-30T20:21:44+0800
     * @return   [type]                   [description]
     */
    public function apidelCustomerLogisticAction(){
       
        if(empty($this->userid))
            return  $result = json_encode(['code'=>104,'msg'=>'请登录']);
        $logisticid = $this->params['logisticid'];
        
        $result = UserLogisticsModel::delCustomerLogistic(['logisticid'=>$logisticid,'customerid'=>$this->userid]);
        
      
         return  $this->json($result['code'],$result['data'],$result['msg']);
    }


}