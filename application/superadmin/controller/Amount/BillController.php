<?php
// +----------------------------------------------------------------------
// |  [ 店铺管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-21
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Amount;
use app\superadmin\ActionController;

use app\model\User\AmountModel;
use app\lib\Model;

use think\Config;

class BillController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    

    public function indexAction() {
        
        $PayOrder = Model::ins("PayOrder");

        $where = array();    

        $app_type = [
            "1"=>"微信开放平台",
            "2"=>"微信公众平台",
        ]; 

          
        $where = $this->searchWhere([
            "date"=>"times",
            "addtime"=>"times",
            "app_type"=>"=",
        ],$where);

        //$list = Model::ins("CusWithdrawals")->pageList($where,'*','id desc');
        $list = Model::ins("PayBill")->pageList($where, '*', 'date desc');
        //var_dump($list); exit();
        foreach($list['list'] as $key => $val){
            $list['list'][$key]['count']                = $val['count'];
            $list['list'][$key]['amount']               = DePrice($val['amount']);
            $list['list'][$key]['refund_amount']        = DePrice($val['refund_amount']);
            $list['list'][$key]['ch_amount']            = DePrice($val['ch_amount']);

            $list['list'][$key]['app_type_name']        = $app_type[$val['app_type']];
            
            $payorder_row = [];
            if($val['app_type']==1){
                // 微信开放平台
                $payorder_row = $PayOrder->getRow([
                        'pay_type'=>"weixin",
                        'pay_status'=>1,
                        'pay_time'=>[
                            [">=",$val['date']." 00:00:00"],
                            ["<=",$val['date']." 23:59:59"],
                        ],
                    ],"count(*) as count,sum(amount) as amount");
            }else if($val['app_type']==2){
                // 微信公众号
                $payorder_row = $PayOrder->getRow([
                        'pay_type'=>"weixin_web",
                        'pay_status'=>1,
                        'pay_time'=>[
                            [">=",$val['date']." 00:00:00"],
                            ["<=",$val['date']." 23:59:59"],
                        ],
                    ],"count(*) as count,sum(amount) as amount");

            }

            $list['list'][$key]['sys_count']        =  $payorder_row['count'];
            $list['list'][$key]['sys_amount']       =  DePrice($payorder_row['amount']);

        }
 
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
            "app_type"=>$app_type,
        );

        return $this->view($viewData);
    }

    public function listAction(){

        $PayBillWeixin = Model::ins("PayBillWeixin");

        $fieldarr = $PayBillWeixin->getField();

        $billid = $this->params['billid'];

        $where['billid'] = $billid;
        
        $where = $this->searchWhere([
            "transaction_id"=>"like",
            "out_trade_no"=>"like",
        ], $where);
        
        $list = $PayBillWeixin->pageList($where, "*", $this->order("id","desc"));

       
        foreach ($list['list'] as $key => $val) {
            /*
            $list['list'][$key]['amount'] = DePrice($val['amount']);

            $list['list'][$key]['direction'] = $direction_arr[$val['direction']];

            $user = $CusOBJ->getRow(["id"=>$val['userid']],"mobile");

            $list['list'][$key]['user'] = $user['mobile'];

            $list['list'][$key]['flowtype'] = $flowtype[$val['flowtype']]['flowname'];*/

            $list['list'][$key]['total_fee'] = DePrice($val['total_fee']);
            $list['list'][$key]['total_fee_redpacket'] = DePrice($val['total_fee_redpacket']);
            $list['list'][$key]['refund_total_fee_redpacket'] = DePrice($val['refund_total_fee_redpacket']);
            $list['list'][$key]['service_charge'] = DePrice($val['service_charge']);

        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "billid"=>$billid,
            "fieldarr"=>$fieldarr,
        );
        
        return $this->view($viewData);
    }

}