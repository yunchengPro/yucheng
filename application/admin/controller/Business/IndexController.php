<?php
// +----------------------------------------------------------------------
// |  [ 店铺管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-21
// +----------------------------------------------------------------------
namespace app\admin\controller\Business;
use app\admin\ActionController;

use app\lib\Db;
use app\model\User\AmountModel;
use app\lib\Model;

class IndexController extends ActionController{

    const busRole = 4;
	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * [indexAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-21T21:48:27+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){

        $AmountModel = new AmountModel();
        $params = ['businessid'=>$this->businessid];

        $data =  $AmountModel->getBusinessBanlance($params);
       
        $datas = $AmountModel->getBusinessOrderCount($params);
        
//         $datass = $AmountModel->getUserAmount(['customerid'=>$this->customerid]);
        
        // 公司
        $amountParams['customerid'] = $this->customerid;
        $amountParams['type'] = 1;
        $amountParams['role'] = self::busRole;
        
        $amount = Model::new("User.Amount")->getCash($amountParams);
        
        
        $payGoods = Model::new("User.Amount")->getFlowBusStoCash(array("customerid"=>$this->customerid, "role" => self::busRole));
        
        $waitPayGoods = Model::new("User.Amount")->getFlowFutBusCash(array("customerid" => $this->customerid, "role" => self::busRole));
       
        //查询
        
        $where['businessid'] = $this->businessid;
        $where["orderstatus"] = array(array(">=", 1), array("<", 5));
       
        
        $where = $this->searchWhere([
                "orderno"=>"=",
                "addtime" => "times",
            ],$where);

      
        
        //品牌列表
        $list = Model::ins("OrdOrder")->pageList($where,'*','id desc');
        

        $viewData = [
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
//             'cashamount' => $datass['cashamount'],
//             'bullamount' => $datass['bullamount'],
//             'sumProductAmount'=> $data['data']['sumProductAmount'],
//             'sumWillProductAmount'=> $data['data']['sumWillProductAmount'],
            "cashamount" => $amount['comamount'],
            "sumProductAmount" => $payGoods,
            "sumWillProductAmount" => $waitPayGoods,
            'sumBullAmount'=> $data['data']['sumBullAmount'],
            'sumWillBullAmount'=> $data['data']['sumWillBullAmount'],
            'datas'=>$datas
        ];
        return $this->view($viewData);
    }

    /**
     *  
     * [setTransportAction 设置运费模板]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-23T17:27:19+0800
     */
    public function setTransportAction(){

        $OrdTransportExtend = Model::ins('OrdTransportExtend');
        $this->businessid;
        $list = $OrdTransportExtend->pageList(['business_id'=>$this->businessid]);

        //print_r($list);

        $viewData = [
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
        ];
        return $this->view($viewData);

    }
}