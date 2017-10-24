<?php
// +----------------------------------------------------------------------
// | 牛牛汇 [ 分红指数设置 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年10月19日10:39:03}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Model;

class BonussettingController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [busbonussetlistAction 商家分红指数]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-19T11:43:16+0800
     * @return   [type]                   [description]
     */
    public function busbonussetlistAction(){
    	

    	$where['role'] = 2;

        $list = Model::ins("SysBonusSetting")->pageList($where, "*", "addtime desc, id desc");
        foreach ($list['list'] as $key => $value) {
            $list['list'][$key]['proportion'] = ($value['proportion']*100) .'%';
        }
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => '商家分红指数',
        );
        
        return $this->view($viewData);
    }



    /**
     * [customerbonussetlistAction 消费者分红指数]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-19T11:43:32+0800
     * @return   [type]                   [description]
     */
    public function customerbonussetlistAction(){
    	$where['role'] = 1;

        $list = Model::ins("SysBonusSetting")->pageList($where, "*", "addtime desc, id desc");
        foreach ($list['list'] as $key => $value) {
            $list['list'][$key]['proportion'] = ($value['proportion']*100).'%';
        }
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => '消费者分红指数',
        );
        
        return $this->view($viewData);
    }

    /**
     * [addbusbonusAction 添加商家分红指数]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-19T14:05:18+0800
     * @return   [type]                   [description]
     */
    public function addbusbonusAction(){
    	$viewData = array(
            "action" =>'/System/Bonussetting/doaddbusbonus',
            "title" => '添加商家分红指数'
        );
        return $this->view($viewData);
    }

    /**
     * [editbusbonusAction 修改商家分红]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-19T14:32:38+0800
     * @return   [type]                   [description]
     */
    public function editbusbonusAction(){
        $id = $this->params['id'];
        $bonus_bus = Model::ins('SysBonusSetting')->getRow(['id'=>$id]);
        $bonus_bus['proportion'] = $bonus_bus['proportion']*100;
        $viewData = array(
            "action" =>'/System/Bonussetting/doaddbusbonus',
            "title" => '修改商家分红指数',
            "bonus_bus" => $bonus_bus
        );
        return $this->view($viewData);
    }

    /**
     * [doaddbusbonusAction 添加商家分红指数]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-19T14:07:29+0800
     * @return   [type]                   [description]
     */
    public function doaddbusbonusAction(){
            $id = $this->params['id'];
    		$role = 2;
    		$adddate = $this->params['adddate'];
    		$proportion = $this->params['proportion'];
    		
    		if(empty($proportion))
    			$this->showError('请设置分红比例');

    		if(empty($adddate))
    			$this->showError('请设置分红日期');

    		$insert = [
    			'role'=>$role,
    			'adddate' => $adddate,
    			'proportion' => $proportion/100,
    		];
            if(empty($id)){
        		$setting_row = Model::ins('SysBonusSetting')->getRow(['adddate'=>$adddate,'role'=>$role],'id');
        		if(empty($setting_row)){
        			$insert['addtime'] = date('Y-m-d H:i:s');
        			$ret = Model::ins('SysBonusSetting')->insert($insert);
        		}else{
        			$ret = Model::ins('SysBonusSetting')->update($insert,['id'=>$setting_row['id']]);
        		}
            }else{
                $ret = Model::ins('SysBonusSetting')->update($insert,['id'=>$id]);
            }

    		if($ret > 0){
    			$this->showSuccess('操作成功');
    		}else{
    			$this->showError('操作有误，请重新提交');
    		}
    }

    /**
     * [addbusbonusAction 添加消费者分红指数]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-19T14:05:18+0800
     * @return   [type]                   [description]
     */
    public function addcusbonusAction(){
    	 $viewData = array(
            "action" =>'/System/Bonussetting/doaddcusbonus',
            "title" => '添加消费者分红指数'
        );
        return $this->view($viewData);
    }

     /**
     * [editbusbonusAction 修改商家分红]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-19T14:32:38+0800
     * @return   [type]                   [description]
     */
    public function editcusbonusAction(){
        $id = $this->params['id'];
        $bonus_cus = Model::ins('SysBonusSetting')->getRow(['id'=>$id]);
        $bonus_cus['proportion'] = $bonus_cus['proportion']*100;
        $viewData = array(
            "action" =>'/System/Bonussetting/doaddcusbonus',
            "title" => '修改商家分红指数',
            "bonus_cus" => $bonus_cus
        );
        return $this->view($viewData);
    }

    /**
     * [doaddbusbonusAction 添加消费者分红指数]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-19T14:07:29+0800
     * @return   [type]                   [description]
     */
    public function doaddcusbonusAction(){
            $id = $this->params['id'];
    		$role = 1;
    		$adddate = $this->params['adddate'];
    		$proportion = $this->params['proportion'];
    		
    		if(empty($proportion))
    			$this->showError('请设置分红比例');

    		if(empty($adddate))
    			$this->showError('请设置分红日期');

    		$insert = [
    			'role'=>$role,
    			'adddate' => $adddate,
    			'proportion' => $proportion/100,
    		];
            if(empty($id)){
        		$setting_row = Model::ins('SysBonusSetting')->getRow(['adddate'=>$adddate,'role'=>$role],'id');
        		if(empty($setting_row)){
        			$insert['addtime'] = date('Y-m-d H:i:s');
        			$ret = Model::ins('SysBonusSetting')->insert($insert);
        		}else{
        			$ret = Model::ins('SysBonusSetting')->update($insert,['id'=>$setting_row['id']]);
        		}
            }else{
                $ret = Model::ins('SysBonusSetting')->update($insert,['id'=>$id]);
            }
    		if($ret > 0){
    			$this->showSuccess('操作成功');
    		}else{
    			$this->showError('操作有误，请重新提交');
    		}
    }

}