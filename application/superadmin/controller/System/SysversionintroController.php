<?php
// +----------------------------------------------------------------------
// |  [ 版本升级说明 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-6-16 11:25:55}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;


class SysversionintroController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


    public function indexAction(){

        $where = $this->searchWhere([
                "title"=>"like",
                "type"=>"=",
                "version"=>"="
            ],$where);
        
        //print_r($where);
        $list  = Model::ins('SysVersionIntro')->pageList($where);

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
               
            ];

        return $this->view($viewData);
    }

    /**
     * [addversionAction 添加介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-16T11:52:10+0800
     * @return   [type]                   [description]
     */
    public function addversionAction(){
    	$action = "/System/Sysversionintro/doaddversion";
    	$formtoken = $this->Btoken('System-Sysversionintro-addversion');
    	$viewData = [
    		'title' => '添加介绍',
    		'action'=>$action,
    		'formtoken' => $formtoken
    	];
    	return $this->view($viewData);
    }

    /**
     * [editversionAction 编辑介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-16T14:31:07+0800
     * @return   [type]                   [description]
     */
    public function editversionAction(){

    	$id = $this->getParam('id');
    	$version = Model::ins('SysVersionIntro')->getRow(['id'=>$id]);
    	$action = "/System/Sysversionintro/doaddversion";
    	$formtoken = $this->Btoken('System-Sysversionintro-editversion');
    	$viewData = [
    		'title' => '添加介绍',
    		'action' => $action,
    		'formtoken' => $formtoken,
    		'version' => $version
    	];
    	return $this->view($viewData);
    }

    /**
     * [doaddversionAction 添加版本介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-16T14:02:57+0800
     * @return   [type]                   [description]
     */
    public function doaddversionAction(){

    	if($this->Ctoken()){

	    	$post = $this->params;
	    	$id = $post['id'];

	    	//过滤字段 去掉没有的字段（字段填充）
	    	$add_arr = Model::ins('SysVersionIntro')->_facade($post);
	    	$add_arr['addtime'] =  date('Y-m-d H:i:s');

	    	if(empty($add_arr['title'])){
	    		$this->showError('介绍标题不能为空');
	    	}
	    	if(empty($add_arr['content'])){
	    		$this->showError('介绍内容不能为空');
	    	}
	    	if(empty($add_arr['type'])){
	    		$this->showError('请选择类型');
	    	}
	    	if(empty($add_arr['version'])){
	    		$this->showError('请填写版本号');
	    	}
	    	
	    	if(empty($id)){
	    		//版本号不能重复
	    		$vData = Model::ins('SysVersionIntro')->getRow(['version'=>$add_arr['version'],'type'=>$add_arr['type']],'id');
	    		if(!empty($vData)){
	    			$this->showError('该版本介绍已添加');
	    		}
	    		//添加内容
	    		$ret = Model::ins('SysVersionIntro')->insert($add_arr);
	    	}else{
	    		$vData = Model::ins('SysVersionIntro')->getRow(['version'=>$add_arr['version'],'type'=>$add_arr['type'],'id'=>['<>',$id]],'id');
	    		if(!empty($vData)){
	    			$this->showError('该版本介绍已添加');
	    		}
	    		//更新内容
	    		$ret = Model::ins('SysVersionIntro')->update($add_arr,['id'=>$id]);
	    	}
    		
    		if($ret > 0){
                $this->showSuccess('操作成功');
            }else{
                $this->showError('操作错误，请联系管理员');
            }

		}else{
			$this->showError('token错误，禁止操作');
		}
    }

    /**
     * [sortAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-16T16:25:09+0800
     * @return   [type]                   [description]
     */
    public function sortAction(){
        $ids = $this->getParam('id');
        $sort = $this->getParam('sort');
        $id_arr = explode(',', $ids);
        //print_r($id_arr);
        $sort_arr = explode(',', $sort);
        //print_r($sort_arr);
        foreach ($id_arr as $key => $value) {

           Model::ins('SysVersionIntro')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
    }

}