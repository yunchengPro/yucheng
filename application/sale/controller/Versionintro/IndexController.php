<?php
// +----------------------------------------------------------------------
// |  [ 版本介绍 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-6-14 14:28:22}}
// +----------------------------------------------------------------------
namespace app\mobile\controller\Versionintro;
use app\mobile\ActionController;
use think\Config;
use app\lib\Model;

class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [IndexAction 功能介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-14T14:29:46+0800
     */
    public function indexAction(){
        $type = $this->getParam('type');
        if($type == 'ios'){
            $version = Model::ins('SysVersionIntro')->pageList(['type'=>1],'id,title,version,addtime','sort desc',1,1,10);
            $types = 1;
        }else if($type == 'android'){
            $version = Model::ins('SysVersionIntro')->pageList(['type'=>2],'id,title,version,addtime','sort desc',1,1,10);
            $types = 2;
        }else{
            $version = Model::ins('SysVersionIntro')->pageList([],'id,title,version,addtime','sort desc',1,1,10);
            $types = '';
        }

    	$viewData = [
            'title' => "功能介绍",
            'version' => $version['list'],
            'type' => $types
        ];
        return $this->view($viewData);
    }

    /**
     * [detailAction 功能介绍详情]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-14T14:31:39+0800
     * @return   [type]                   [description]
     */
    public function detailAction(){
    	$id = $this->getParam('id');
        $version = Model::ins('SysVersionIntro')->getRow(['id'=>$id]);
    	$viewData = [
            'title' => "功能介绍详情",
            'version'=>$version
        ];
        return $this->view($viewData);
    }

    /**
     * [ajaxloadAction 加载内容]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-16T15:39:27+0800
     * @return   [type]                   [description]
     */
    public function ajaxloadAction(){
        $page = $this->params['page'];
        $type = $this->params['type'];
        if(!empty($type)){
            $where = ['type'=>$type];
        }else{
            $where = [];
        }

        $version =  Model::ins("SysVersionIntro")->pageList($where,"id,title,version,addtime","sort desc,id desc",1,$page,10);

        echo json_encode($version['list']);
    }

}