<?php
// +----------------------------------------------------------------------
// |  [ 评价管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-5-6 17:39:37}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Evaluate;
use app\superadmin\ActionController;

use \think\Config;

use app\lib\Db;
use app\lib\Img;
use app\lib\Model;


class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [proevalistAction 商品评价列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T17:40:52+0800
     * @return   [type]                   [description]
     */
	public function proevalistAction(){


        $businessname = $this->getParam('businessname');
        
        if(!empty($businessname)){

            $businessData = Model::ins('BusBusiness')->getList(['businessname'=>['like','%'.$businessname.'%']],'id');
           
            if(!empty($businessData)){

                $bids = '';
                foreach ($businessData as $key => $value) {
                   $bids.= $value['id'].',';
                }
                $bids = rtrim($bids,',');
                if(!empty($bids)){
                    $where['businessid'] = ['in',$bids];
                }
            }
        }
        

		$where = $this->searchWhere([
                "orderno"=>"=",
                "frommembername"=>"=",
                "productname" => "like",
                "content"=>"like",
                "addtime" => "times",
            ],$where);

      
        
        //商品列表
        $list = Model::ins("ProEvaluate")->pageList($where,'*','id desc');

      	$viewData =[ 
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
        ];

        return $this->view($viewData);
	}

    /**
     * [delproevaAction 删除商品评价]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T18:23:01+0800
     * @return   [type]                   [description]
     */
    public function delproevaAction(){

        $ids = $this->getParam('ids');
        $ids = explode(',', $ids);

        foreach ($ids as $key => $value) {
         
            Model::ins('ProEvaluate')->delete(['id'=>$value]);
        } 
        $this->showSuccess('成功删除');
    }


    /**
     * [stoevalistAction 实体店评价内容]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T18:08:20+0800
     * @return   [type]                   [description]
     */
    public function stoevalistAction(){

        $businessname = $this->getParam('businessname');

        if(!empty($businessname)){

            $businessData = Model::ins('StoBusinessInfo')->getList(['businessname'=>['like','%'.$businessname.'%']],'id');
           
            if(!empty($businessData)){

                $bids = '';
                foreach ($businessData as $key => $value) {
                   $bids.= $value['id'].',';
                }
                $bids = rtrim($bids,',');
                if(!empty($bids)){
                    $where['businessid'] = ['in',$bids];
                }
            }
        }

        $where = $this->searchWhere([
                "orderno"=>"=",
                "frommembername"=>"=",
                "content"=>"like",
                "addtime" => "times",
            ],$where);

      
        
        //商品列表
        $list = Model::ins("StoEvaluate")->pageList($where,'*','id desc');

        $viewData =[ 
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
        ];

        return $this->view($viewData);

    }

    /**
     * [delproevaAction 删除商品评价]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T18:23:01+0800
     * @return   [type]                   [description]
     */
    public function delstoevaAction(){

        $ids = $this->getParam('ids');
        $ids = explode(',', $ids);

        foreach ($ids as $key => $value) {
         
            Model::ins('StoEvaluate')->delete(['id'=>$value]);
        } 
        $this->showSuccess('成功删除');
    }

    /**
     * [stoposalistAction 实体店投诉建议]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T18:13:06+0800
     * @return   [type]                   [description]
     */
    public function stoposalistAction(){

        $businessname = $this->getParam('businessname');
        
        if(!empty($businessname)){

            $businessData = Model::ins('StoBusinessInfo')->getList(['businessname'=>['like','%'.$businessname.'%']],'id');
           
            if(!empty($businessData)){

                $bids = '';
                foreach ($businessData as $key => $value) {
                   $bids.= $value['id'].',';
                }
                $bids = rtrim($bids,',');
                if(!empty($bids)){
                    $where['businessid'] = ['in',$bids];
                }
            }
        }
        
        $where = $this->searchWhere([
                
                "addtime" => "times"
            ],$where);
      
        
        //商品列表
        $list = Model::ins("StoProposal")->pageList($where,'*','id desc');

        $viewData =[ 
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
        ];

        return $this->view($viewData);

    }

    /**
     * [delproevaAction 删除实体店投诉建议]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-06T18:23:01+0800
     * @return   [type]                   [description]
     */
    public function delposaAction(){

        $ids = $this->getParam('ids');
        $ids = explode(',', $ids);

        foreach ($ids as $key => $value) {
         
            Model::ins('StoProposal')->delete(['id'=>$value]);
        } 
        $this->showSuccess('成功删除');
    }
}