<?php
// +----------------------------------------------------------------------
// | 牛牛汇 [ 申请商家 申请经理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年10月10日15:35:24}}
// +----------------------------------------------------------------------

namespace app\sale\controller\User;
use app\sale\ActionController;

use app\lib\Model;
use app\model\Customer\ApplyModel;

class ApplyController extends ActionController{


	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    public function applyToBusAction(){

    	$viewData = [
            'title' => '申请商家'
        ];
        return $this->view($viewData);
    }

    /**
     * [doapplyToBusAction 申请成为商家]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T15:37:26+0800
     * @return   [type]                   [description]
     */
    public function doapplyToBusAction(){

        //获取地区编码 所在地区
        $hd_area = $this->params['hd_area'];
        $area_arr = explode(',', $hd_area);
       
        $area = Model::ins('SysArea')->getRow(['id'=>$area_arr['2']],'id,areaname,parentid');
        $parent = Model::ins('SysArea')->getRow(['id'=>$area['parentid']],'id,areaname,parentid');
        $grand = Model::ins('SysArea')->getRow(['id'=>$parent['parentid']],'id,areaname,parentid');
        $city_id = $area_arr['2'];
        $city = $grand['areaname'].$parent['areaname'] . $area['areaname'];
        $role = Model::new("User.User")->getUserRoleID(["customerid"=>$this->userid]);
    	$param = [
    		'customerid' => $this->userid,
    		'name' => $this->getParam('name'),
    		'mobile' => $this->getParam('mobile'),
    		'area' => $city,
    		'area_code' => $city_id,
    		'address' => $this->getParam('address'),
    		'orderno' => $this->getParam('orderno'),
    		'join_type' => 2,//$this->getParam('join_type'),
    		'role' => $role //经理开通商家
    	];

    	$result = ApplyModel::ApplyBus($param);

        return  $this->json($result['code'],$result['data'],$result['msg']);

    }

    /**
     * [applybusprogressAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-21T14:25:56+0800
     * @return   [type]                   [description]
     */
    public function applybusprogressAction(){
        $orderno = $this->params['orderno'];
        $viewData = [
            'title' => '申请成功',
            'orderno' => $orderno
        ];
        return $this->view($viewData);
    }

    /**
     * [getbusorderAction 获取订单信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-21T14:51:50+0800
     * @return   [type]                   [description]
     */
    public function getbusorderAction(){
        $orderno = $this->params['orderno'];
        $customerid = $this->userid;
        $order_row = ApplyModel::getApplyBusOrder(['orderno'=>$orderno,'customerid'=>$customerid]);
       
        return $this->json($order_row['code'],$order_row['data'],$order_row['msg']);
        
    }

    public function applyToManagerAction(){
    	$viewData = [
            'title' => '申请成为区代理'
        ];
        return $this->view($viewData);
    }

    /**
     * [doapplyToManagerAction 申请成为经理]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T16:07:08+0800
     * @return   [type]                   [description]
     */
    public function doapplyToManagerAction(){

        //获取地区编码 所在地区
        $hd_area = $this->params['hd_area'];
        $area_arr = explode(',', $hd_area);
       
        $area = Model::ins('SysArea')->getRow(['id'=>$area_arr['2']],'id,areaname,parentid');
        $parent = Model::ins('SysArea')->getRow(['id'=>$area['parentid']],'id,areaname,parentid');
        $grand = Model::ins('SysArea')->getRow(['id'=>$parent['parentid']],'id,areaname,parentid');
        $city_id = $area_arr['2'];
        $city = $grand['areaname'].$parent['areaname'] . $area['areaname'];
        $role = Model::new("User.User")->getUserRoleID(["customerid"=>$this->userid]);
    	$param = [
    		'customerid' => $this->userid,
    		'name' => $this->getParam('name'),
    		'mobile' => $this->getParam('mobile'),
    		'area' => $city,
            'area_code' => $city_id,
    		'address' => $this->getParam('address'),
    		'orderno' => $this->getParam('orderno'),
    		'join_type' => 2,//$this->getParam('join_type'),
    		'role' => $role //总监开通经理
    	];
    	$result = ApplyModel::ApplyManerger($param);
    	
        return  $this->json($result['code'],$result['data'],$result['msg']);
    }

    /**
     * [applymanprogressAction 申请成功页面 区代理]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-21T15:34:36+0800
     * @return   [type]                   [description]
     */
    public function applymanprogressAction(){
        $orderno = $this->params['orderno'];
        $viewData = [
            'title' => '申请成功',
            'orderno' => $orderno
        ];
        return $this->view($viewData);
    }

    /**
     * [getmanorderAction 获取订单详情 申请代理商]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-21T15:36:16+0800
     * @return   [type]                   [description]
     */
    public function getmanorderAction(){
        $orderno = $this->params['orderno'];
        $customerid = $this->userid;
        $order_row = ApplyModel::getApplyManOrder(['orderno'=>$orderno,'customerid'=>$customerid]);
       
        return $this->json($order_row['code'],$order_row['data'],$order_row['msg']);
    }
    
}