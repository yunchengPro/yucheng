<?php
// +----------------------------------------------------------------------
// |  [ 热门城市管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-5-5 18:37:11}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\System;
use app\superadmin\ActionController;
use app\lib\Db;
use app\lib\Model;
use app\model\Sys\AreaModel;

class StohotcityController extends ActionController{


	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    
	public function indexAction(){
        
        // $areaOBJ =  new AreaModel();
        // $city = $areaOBJ->getCity();
        // $city_arr = [];
        // foreach ($city as $key => $value) {
        //     $city_arr[$key] = $value['areaname'];
        // }


        $where = $this->searchWhere([
                "area"=>"like",
                "area_code" => "="
            ],$where);
        
        //print_r($where);
        $list  = Model::ins('StoHotCity')->pageList($where);

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                // "city" => $city_arr
            ];

        return $this->view($viewData);
    }

    /**
     * [choseCityAction 选择热门城市]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T10:22:37+0800
     * @return   [type]                   [description]
     */
    public function choseCityAction(){
       
        $where['level'] = 2;
        $where = $this->searchWhere([
                "areaname"=>"like",
                "id" => "="
            ],$where);

        $list  = Model::ins('SysArea')->pageList($where);

        $viewData =[ 
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
                // "city" => $city_arr
            ];

        return $this->view($viewData);

    }

    /**
     * [addHotcityAction 添加热门城市]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T10:31:35+0800
     */
    public function addHotcityAction(){
        $ids = $this->getParam('ids');
        $ids = explode(',', $ids);

        foreach ($ids as $key => $value) {
            $hotData = Model::ins('StoHotCity')->getRow(['area_code'=>$value],'area');
            if(!empty($hotData)){
                return $this->json('14568',[],$hotData['area'].'已存在');
            }
            $areaData = Model::ins('SysArea')->getRow(['id'=>$value],'id,areaname');
            if(is_array($areaData) && $areaData['areaname']!=''){
                $insert = [
                    'area' => $areaData['areaname'],
                    'area_code' => $areaData['id'],
                ];
                Model::ins('StoHotCity')->insert($insert);
            }
        } 
        return $this->json('200');
    }

    /**
     * [delHotcityAction 删除热门城市]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T11:58:46+0800
     * @return   [type]                   [description]
     */
    public function delHotcityAction(){
        $ids = $this->getParam('ids');
        $ids = explode(',', $ids);

        foreach ($ids as $key => $value) {
         
            Model::ins('StoHotCity')->delete(['id'=>$value]);
        } 
        $this->showSuccess('成功删除');
    }

    /**
     * [sortAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T13:08:26+0800
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

           Model::ins('StoHotCity')->update(['sort'=> (int) $sort_arr[$key]],['id'=>$value]);
        
        }
        
        $this->showSuccess('成功排序');
    }

}