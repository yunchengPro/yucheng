<?php
// +----------------------------------------------------------------------
// |  [ 初始化实体店脚本 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年9月20日11:20:12}}
// +----------------------------------------------------------------------
namespace app\auto\controller\StoBusiness;
use app\auto\ActionController;
use app\lib\Model;
use think\Config;
use app\lib\Log;


class IndexController extends ActionController{


	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    } 

 	/**
     * [updateStoBusinesCount 初始化商家人气销量粉丝]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-20T10:35:51+0800
     * @return   [type]                   [description]
     */
    public function updateStoBusinesCountAction(){
        $pagesize = 50;
        $page     = 1;

        while(true){
            $business_list  = Model::ins('StoBusinessInfo')->pageList([],'id,hits','id desc',0,$page,$pagesize);
            $page+=1;
            foreach ($business_list as $key => $business) {
               
                $hits = rand(100,500);
                $salecount = rand($hits*0.05,$hits*0.1);
                $fanscount = rand(100,500);
                // $stoOrderCount = Model::ins('StoOrder')->getRow(['businessid'=>$business['id']],'count(*) as count');
                // $flowCount = Model::ins('StoPayFlow')->getRow(['businessid'=>$business['id']],'count(*) as count');
                // $salecount = $stoOrderCount['count'] + $flowCount['count'];
                Model::ins('StoBusinessInfo')->update(['salecount'=>$salecount],['id'=>$business['id']]);
                Model::Mongo('StoBusinessInfo')->update(['id'=>$business['id']],['salecount'=>$salecount],['salecount'=>intval($salecount)]);
                
                

                Model::ins('StoBusinessInfo')->update(['hits'=>$hits],['id'=>$business['id']]);
                Model::Mongo('StoBusinessInfo')->update(['id'=>$business['id']],['hits'=>$hits],['hits'=>intval($hits)]);
               
                // $cus_bus = Model::ins('StoBusiness')->getRow(['id'=>$business['id']],'customerid');
                // $cus_count = Model::ins('CusRelation')->getRow(['parentid'=>$cus_bus['customerid']],'count(*) as count');
               
                Model::ins('StoBusinessInfo')->update(['fanscount'=>$fanscount],['id'=>$business['id']]);
                Model::Mongo('StoBusinessInfo')->update(['id'=>$business['id']],['fanscount'=>$fanscount],['fanscount'=>intval($fanscount)]);
                

            }
           
            if(count($business_list)==0 || count($business_list)<$pagesize)
                    break;
        }
       
       
        echo "更新完成";
    }

}