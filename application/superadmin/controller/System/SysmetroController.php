<?php
// +----------------------------------------------------------------------
// |  [ 地铁管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-5-4 20:43:05}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;
use app\model\Sys\AreaModel;

class SysMetroController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


    public function indexAction(){
        
        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }


        $where = $this->searchWhere([
                "linename"=>"like",
                "areaid" => "="
            ],$where);
        
        //print_r($where);
        $list  = Model::ins('SysMetro')->pageList($where);

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                "city" => $city_arr
            ];

        return $this->view($viewData);
    }

    /**
     * [addMetroAction 添加商圈]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-04T20:18:52+0800
     */
    public function addMetroAction(){

        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }
       

        $action = '/System/Sysmetro/doAddorEditMetro';
        //form验证token
        $formtoken = $this->Btoken('System-SysMetro-addMetro');
        $viewData = array(
                "title"=>"添加广告",
                'formtoken'=>$formtoken,
                'city' => $city_arr,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [editMetroAction 编辑商圈]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-04T20:20:23+0800
     * @return   [type]                   [description]
     */
    public function editMetroAction(){
        $id = $this->getParam('id');

        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }
       
        $Metro = Model::ins('SysMetro')->getRow(['id'=>$id]);

        $action = '/System/Sysmetro/doAddorEditMetro';
        //form验证token
        $formtoken = $this->Btoken('System-SysMetro-editMetro');
        $viewData = array(
                "title"=>"添加广告",
                'formtoken'=>$formtoken,
                'city' => $city_arr,
                "action"=>$action,
                'Metro' => $Metro
            );
        return $this->view($viewData);
    }

    /**
     * [doAddorEditMetroAction 添加或编辑操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-04T20:19:58+0800
     * @return   [type]                   [description]
     */
    public function doAddorEditMetroAction(){
        $post = $this->params;
       	
       	$areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }

        $id = $post['id'];
        $insert = [
            'areaname' => $city_arr[$post['areaid']],
            'areaid' => $post['areaid'],
            'sort' => $post['sort'],
            'linename' => $post['linename'],
            'pinyin' => $post['pinyin'],
            'metroname' => $post['metroname'],
        ];
        
        if(empty($id)){
            $data = Model::ins('SysMetro')->getRow(['metroname'=>$post['metroname']],'id');
            if(empty($data['id'])){
                Model::ins('SysMetro')->insert($insert);
            }else{
                $this->showError('地铁名称不能重复');
            }
        }else{
            $data = Model::ins('SysMetro')->getRow(['metroname'=>$post['metroname'],'id'=>['<>',$id]],'id');
            if(empty($data['id'])){
                Model::ins('SysMetro')->update($insert,['id'=>$id]);
            }else{
                $this->showError('地铁名称不能重复');
            }
        }
        $this->showSuccess("操作成功");
    }


     /**
     * [setenableAction 启用停用]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-03T17:49:18+0800
     * @return   [type]                   [description]
     */
    public function setenableAction(){
        $enable = $this->getParam('enable');
        $id = $this->getParam('id');
        Model::ins('SysMetro')->update(['enable'=>$enable],['id'=>$id]);
        $this->showSuccess('设置成功');
    }

    /**
     * [delMetroAction 删除]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-04T20:36:03+0800
     * @return   [type]                   [description]
     */
    public function delMetroAction(){
        $ids = $this->getParam('ids');

        if(empty($ids)){
            $this->showError('请选择需要删除商圈');
        }

        $ids = explode(',', $ids);
        //批量删除用户
        foreach ($ids as $value) {
            $data = Model::ins('SysMetro')->delete(['id'=>$value]);
        }

        $this->showSuccess('成功删除');
    }


}