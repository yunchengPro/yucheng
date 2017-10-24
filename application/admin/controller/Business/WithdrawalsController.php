<?php
// +----------------------------------------------------------------------
// |  [ 提现记录 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-04-10
// +----------------------------------------------------------------------
namespace app\admin\controller\Business;
use app\admin\ActionController;

use app\lib\Model;
class WithdrawalsController extends ActionController{


		/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

	   /**
     * [indexAction 提现列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午2:40:44
     * @return [type]    [description]
     */
	public function indexAction(){	            
        //提现列表
        
        $where = ['customerid'=>$this->customerid];      
        $where = $this->searchWhere([
            "status" => "="
        ],$where);
        //$list = Model::ins("CusWithdrawals")->pageList($where,'*','id desc');
        $list = Model::ins("CusWithdrawals")->pageList($where, '*', 'id desc');
        //var_dump($list); exit();
        foreach($list['list'] as $key => $val){
            $list['list'][$key]['amount']    = DePrice($val['amount']);
            $list['list'][$key]['cashamount']    = DePrice($val['cashamount']);
            $list['list'][$key]['comamount']    = DePrice($val['comamount']);
            $list['list'][$key]['pay_money'] = DePrice($val['pay_money']);
            
            $customerId = $val['customerid'];
            $customer   = Model::ins("CusCustomer")->getById($customerId);
            $list['list'][$key]['customer_mobile'] = $customer['mobile'];
        }
        //var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
        );
        //var_dump($viewData); exit();

        return $this->view($viewData);
	}

}