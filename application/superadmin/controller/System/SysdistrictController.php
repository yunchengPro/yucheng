<?php
// +----------------------------------------------------------------------
// |  [ 商圈管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-5-4 20:01:23}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;
use app\model\Sys\AreaModel;

class SysdistrictController extends ActionController{

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
                "district_name"=>"like",
                "areaid" => "="
            ],$where);
        
        //print_r($where);
        $list  = Model::ins('SysDistrict')->pageList($where);

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                "city" => $city_arr
            ];

        return $this->view($viewData);
    }

    /**
     * [addDistrictAction 添加商圈]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-04T20:18:52+0800
     */
    public function addDistrictAction(){

        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }
       

        $action = '/System/Sysdistrict/doAddorEditDistrict';
        //form验证token
        $formtoken = $this->Btoken('System-Sysdistrict-addDistrict');
        $viewData = array(
                "title"=>"添加广告",
                'formtoken'=>$formtoken,
                'city' => $city_arr,
                "action"=>$action
            );
        return $this->view($viewData);
    }

    /**
     * [editDistrictAction 编辑商圈]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-04T20:20:23+0800
     * @return   [type]                   [description]
     */
    public function editDistrictAction(){
        $id = $this->getParam('id');

        $areaOBJ =  new AreaModel();
        $city = $areaOBJ->getCity();
        $city_arr = [];
        foreach ($city as $key => $value) {
            $city_arr[$key] = $value['areaname'];
        }
       
        $District = Model::ins('SysDistrict')->getRow(['id'=>$id]);

        $action = '/System/Sysdistrict/doAddorEditDistrict';
        //form验证token
        $formtoken = $this->Btoken('System-Sysdistrict-editDistrict');
        $viewData = array(
                "title"=>"添加广告",
                'formtoken'=>$formtoken,
                'city' => $city_arr,
                "action"=>$action,
                'District' => $District
            );
        return $this->view($viewData);
    }

    /**
     * [doAddorEditDistrictAction 添加或编辑操作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-04T20:19:58+0800
     * @return   [type]                   [description]
     */
    public function doAddorEditDistrictAction(){
        $post = $this->params;
       
        $id = $post['id'];
        $insert = [
            'district_name' => $post['district_name'],
            'areaid' => $post['areaid'],
            'sort' => $post['sort'],
            'enable' => $post['enable'],
        ];
        
        if(empty($id)){
            
            $data = Model::ins('SysDistrict')->getRow(['district_name'=>$post['district_name']],'id');
            if(empty($data['id'])){
                Model::ins('SysDistrict')->insert($insert);
            }else{
                $this->showError('商圈名称不能重复');
            }
        }else{
            $data = Model::ins('SysDistrict')->getRow(['district_name'=>$post['district_name'],'id'=>['<>',$id]],'id');
            if(empty($data['id'])){
                Model::ins('SysDistrict')->update($insert,['id'=>$id]);
            }else{
                $this->showError('商圈名称不能重复');
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
        Model::ins('SysDistrict')->update(['enable'=>$enable],['id'=>$id]);
        $this->showSuccess('设置成功');
    }

    /**
     * [delDistrictAction 删除]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-04T20:36:03+0800
     * @return   [type]                   [description]
     */
    public function delDistrictAction(){
        $ids = $this->getParam('ids');

        if(empty($ids)){
            $this->showError('请选择需要删除商圈');
        }

        $ids = explode(',', $ids);
        //批量删除用户
        foreach ($ids as $value) {
            $data = Model::ins('SysDistrict')->delete(['id'=>$value]);
        }

        $this->showSuccess('成功删除');
    }

    /**
     * [choseareaAction 选择地区]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-05T10:45:59+0800
     * @return   [type]                   [description]
     */
    public function choseareaAction(){
     
        
        $where['level'] = 2;

        $where = $this->searchWhere([
                "areaname"=>"like"
            ],$where);
        
        //print_r($where);
        $list  = Model::ins('SysArea')->pageList($where);

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
               
            ];

        return $this->view($viewData);

    }

}