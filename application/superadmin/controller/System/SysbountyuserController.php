<?php
// +----------------------------------------------------------------------
// |  [ 用户奖励金 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年7月26日19:02:38}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;

class SysbountyuserController extends ActionController{

    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


    /**
     * [userbountyListAction 用户领取奖励金列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-26T19:04:03+0800
     * @return   [type]                   [description]
     */
    public function userbountyListAction(){
        $params = $this->params;
        $where = $this->searchWhere([
                "mobile"=>"=",
            ],$where);
        //商品列表
        $list = Model::ins("SysBountyUser")->pageList($where,'*','id desc');

        foreach ($list['list'] as $key => $value) {
            $list['list'][$key]['amount'] = '￥'.DePrice($value['amount']);
            $list['list'][$key]['minamount'] = '￥'.DePrice($value['minamount']);

            if(empty($value['customerid']))
                $list['list'][$key]['customerid'] = '未注册';

            if($value['isget'] == 1){
                $list['list'][$key]['isget'] = '已领取';
            }else{
                $list['list'][$key]['isget'] = '未领取';
            }

            if(empty($value['gettime'])){
                $list['list'][$key]['gettime'] = '未领取';
            }

        }

        $viewData =[ 
                "title" => '用户领取奖励金列表',
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            ];

        return $this->view($viewData);
    }

    /**
     * [mallbountyAction 平台奖励金]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-26T19:23:55+0800
     * @return   [type]                   [description]
     */
    public function mallbountyAction(){
        $where = ['id'=>1];
        //商品列表
        $list = Model::ins("SysBountyMall")->pageList($where,'*','id desc');

        foreach ($list['list'] as $key => $bountymall) {
            $list['list'][$key]['amount'] = DePrice($bountymall['amount'] );
            $list['list'][$key]['hasamount'] = DePrice($bountymall['hasamount'] );
            $list['list'][$key]['disamount'] = DePrice($bountymall['disamount'] );
        }
        $viewData =[ 
                "title" => '用户领取奖励金列表',
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            ];

        return $this->view($viewData);
    }

    /**
     * [addmallbountyAction 设置平台奖励金]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-26T19:30:36+0800
     * @return   [type]                   [description]
     */
    public function addmallbountyAction(){

        $action = '/System/Sysbountyuser/doaddmallbounty';
        //form验证token
        $formtoken = $this->Btoken('System-Sysbountyuser-addmallbounty');
        $viewData = array(
                "title"=>"设置平台奖励金",
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [editmallbountyAction 修改奖励金]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-26T19:49:42+0800
     * @return   [type]                   [description]
     */
    public function editmallbountyAction(){
        $action = '/System/Sysbountyuser/doaddmallbounty';
        //form验证token
        $formtoken = $this->Btoken('System-Sysbountyuser-editmallbounty');
        $bountymall = Model::ins('SysBountyMall')->getRow(['id'=>1],'amount,hasamount,disamount');
        $bountymall['amount'] = DePrice($bountymall['amount'] );
        $bountymall['hasamount'] = DePrice($bountymall['hasamount'] );
        $bountymall['disamount'] = DePrice($bountymall['disamount'] );
        $viewData = array(
                "title"=>"设置平台奖励金",
                'formtoken'=>$formtoken,
                "action"=>$action,
                'bountymall' => $bountymall
            );
        return $this->view($viewData);
    }

    /**
     * [doaddmallbountyAction 添加奖励金]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-26T19:42:18+0800
     * @return   [type]                   [description]
     */
    public function doaddmallbountyAction(){
        
        if($this->Ctoken()){
            $id = $this->getParam('id');
            $amount = $this->getParam('amount');
            if(!is_numeric($amount))
                $this->showError('奖励金必须为数字');
            
            if($amount <= 0)
            
                $this->showError('奖励金不能小于0');

            $bountymall = Model::ins('SysBountyMall')->getRow(['id'=>1],'id');
            
            // if(!empty($bountymall))
            //     $this->showError('奖励金已设置');
            
            $BMData = Model::ins('SysBountyMall')->getRow(['id'=>1],'id');

            $insert = [
                'id'=>1,
                'amount' => EnPrice($amount),
                'addtime' => date('Y-m-d')
            ];

            if(empty($id) || empty($BMData)){
                $ret = Model::ins('SysBountyMall')->insert($insert);
            }else{
                $update = [
                    'amount' => EnPrice($amount),
                    'disamount' => EnPrice($this->getParam('disamount')),
                    'hasamount' => EnPrice($this->getParam('hasamount'))
                ];
                $ret = Model::ins('SysBountyMall')->update($update,['id'=>1]);
            }

            if($ret > 0){
                $this->showSuccess('操作成功');
            }else{
                $this->showSuccess('操作错误');
            }

        }else{
            $this->showError('token错误，禁止操作');
        }
    }

}