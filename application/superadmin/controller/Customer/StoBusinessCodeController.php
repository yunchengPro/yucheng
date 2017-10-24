<?php
// +----------------------------------------------------------------------
// |  [ 实体店平台号 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年7月4日16:04:06}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Customer;

use app\superadmin\ActionController;
use app\lib\Model;
use app\model\Sys\CommonModel;
use app\model\Sys\CommonRoleModel;
use app\model\StoBusiness\StobusinessModel;

class StoBusinessCodeController extends ActionController {

	public function __construct() {
        parent::__construct();
    }


    /**
     * [listAction 平台号列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-04T16:09:14+0800
     * @return   [type]                   [description]
     */
    public function listAction(){

        $StoBusiness = Config('Stobusiness');
    	$where = $this->searchWhere([
                "businessname"=>"like",
                "business_code"=>"=",
                "isuse" => "="
            ],$where);
        $sort = '';

        $sort_by = $params['sort_by'];
        $sort_order = $params['sort_order'];
        
        if(!empty($sort_by) && !empty($sort_order))
                $sort = $sort_by . ' ' . $sort_order;

        $sort = empty($sort) ? 'id desc' : $sort;
        
        //商品列表
        $list = Model::ins("StoBusinessCode")->pageList($where,'*',$sort);

        $viewData =[ 
                "url"=>$StoBusiness['codeurl'],
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            ];

        return $this->view($viewData);
    }

    /**
     * [choseCreatNumAction 选择生成数量]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-04T16:41:17+0800
     * @return   [type]                   [description]
     */
    public function choseCreatNumAction(){
       
        $number=$this->getParam('number');
        
        if(!empty($number)){
           
            return "<script>parent.parent.goto2('/Customer/StoBusinessCode/creatStoBusinessCode?number={$number}');parent.parent.layer.close(parent.parent.layer.getFrameIndex(parent.window.name));</script>";

        }
        $numberarr = [
            '10'=>'10份',
            '20'=>'20份',
            '30'=>'30份',
            '40'=>'40份',
            '50'=>'50份',
            '500'=>'500份',
        ];
        
        $action = '/Customer/StoBusinessCode/choseCreatNum';
        $viewData = [
            'numberarr'=>$numberarr,
            'action'=>$action,
            'title'=>'选择生成数量'
        ];
        return $this->view($viewData);
    }

    /**
     * [creatStoBusinessCode description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-04T17:25:50+0800
     * @return   [type]                   [description]
     */
    public function creatStoBusinessCodeAction(){

        //生成二维码个数
        $number = $this->getParam('number');

        $business_code = Model::ins('StoBusinessCode')->getRow([],'max(business_code) as business_code');

        $bus_business_code = Model::ins('StoBusinessBaseinfo')->getRow([],'max(business_code) as business_code');
        // echo $bus_business_code ;
        // echo "=================";
        if(!empty($business_code['business_code']) || !empty($bus_business_code['business_code']) ){

            //比较平台号大小 去最大的为起始值
            if(intval($business_code['business_code']) > intval($bus_business_code['business_code'])){
                
                $start_code =  intval($business_code['business_code']) + 1;

            }else{

                $start_code =  intval($bus_business_code['business_code']) + 1;
            }

        }else{
            //如果都没有随机生成一个
            $start_code = 31345;
        }
        // echo $start_code;
        // echo "@@@@@@@@@@@@@@@@@";
        //生成平台号
        for ($i=0; $i < $number ; $i++) { 

            $has_business_code = Model::ins('StoBusinessCode')->getRow(['business_code'=>$start_code],'id');
            $has_bus_business_code = Model::ins('StoBusinessCode')->getRow(['business_code'=>$start_code],'id');


            if(empty($has_business_code) && empty($has_bus_business_code)){
                
                Model::ins('StoBusinessCode')->insert(['business_code'=>$start_code,'addtime'=>date('Y-m-d H:i:s')]);

            }

            $start_code = $start_code + 1;
            
        }
      
        $this->showSuccess('生成成功','/Customer/StoBusinessCode/list',10);
        exit();
    } 


    /**
     * [getStoPayCodeUrl 输出付款二维码图片]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-25T10:16:46+0800
     * @return   [type]                   [description]
     */
    public function getBusinessCodeQRcodeAction(){
           
        if(empty($this->params['business_code']))
           exit('平台号不能为空');
        $codeParam['business_code'] = $this->params['business_code'];
       
        StobusinessModel::getBusinessCodeQRcode($codeParam);
    }  
}