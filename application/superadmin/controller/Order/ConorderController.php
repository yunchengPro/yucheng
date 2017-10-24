<?php
// +----------------------------------------------------------------------
// | 牛牛汇 [ 购物砖石记录 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年10月17日16:19:00}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Order;
use app\superadmin\ActionController;
use app\lib\Model;

class ConorderController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [orderlistAction 订单列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-17T16:20:47+0800
     * @return   [type]                   [description]
     */
    public function orderlistAction(){
    	$where = $this->searchWhere([
                "orderno"=>"=",
                "mobile"=>"=",
                "cust_name"=>"like",
                "orderstatus"=>"=",
                "addtime" => "times",
            ],$where);

        $list = Model::ins("ConOrder")->pageList($where, "*", "addtime desc, id desc");

        foreach ($list['list'] as $key => $value) {
            $list['list'][$key]['totalamount'] = DePrice($value['totalamount']);
            $list['list'][$key]['count'] = DePrice($value['count']);
        }

        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => '购买砖石记录',
        );
        
        return $this->view($viewData);
    }

    /**
     * [checkorderAction 审核订单]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-18T16:46:16+0800
     * @return   [type]                   [description]
     */
    public function checkorderAction(){
        $id = $this->params['id'];
        $status = $this->params['status'];
        $conorder_row = Model::ins("ConOrder")->getRow(['id'=>$id],'*');
        if(empty($conorder_row))
            $this->showError('订单信息不存在');


        if(!in_array($status, ['0','1','2','3']))
            $this->showError('无效的订单状态');

        if($status == 1){
            if($conorder_row['orderstatus'] == 1)
                $this->showError('订单已支付');
        }

        if($status == 0){
            if($conorder_row['orderstatus'] == 3)
                $this->showError('订单已取消');
        }

        if($conorder_row['orderstatus'] == 2)
            $this->showError('订单已完结');

        if($conorder_row['orderstatus'] == 1)
            $this->showError('订单已支付');

        $amountModel = Model::ins("AmoAmount");

        $amountModel->startTrans();

        try{

            $update = [
                'orderstatus' => $status,
                "paytime"=>date("Y-m-d H:i:s"),
                "payamount"=>$conorder_row['totalamount'],
            ];
            $ret = Model::ins("ConOrder")->update($update,['id'=>$id]);
            if($status == 1){
                $flowid = Model::new("Amount.Flow")->getFlowId($conorder_row['orderno']);

                // 增加钻石
                Model::new("Amount.Amount")->add_conamount([
                                                "userid"=>$conorder_row['customerid'],
                                                "amount"=>$conorder_row['count'],
                                                "usertype"=>"1",
                                                "orderno"=>$conorder_row['orderno'],
                                                "flowtype"=>10,
                                                "role"=>1,
                                                "tablename"=>"AmoFlowCon",
                                                "flowid"=>$flowid,
                                            ]);

                //进行分润
                Model::new("Amount.Profit")->add_con_profit([
                    "userid"=>$conorder_row['customerid'],
                    "amount"=>$conorder_row['totalamount'],
                    "orderno"=>$conorder_row['orderno'],
                    "flowid"=>$flowid,
                ]);

                // 提交事务
                $amountModel->commit();  
            }else{
                $amountModel->commit();  
            }
            if($ret > 0){
                $this->showSuccess('操作成功');
            }else{
                $this->showError('操作有误，请重新提交');
            }

        } catch (\Exception $e) {
            //print_r($e);
            // 错误日志
            // 回滚事务
            $amountModel->rollback();

            Log::add($e,__METHOD__);

            $this->showError('操作有误，请重新提交');
        }
    }

    /**
     * [onlineorderlistAction 在线支付订单]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-20T16:09:54+0800
     * @return   [type]                   [description]
     */
    public function onlineorderlistAction(){
        $where = $this->searchWhere([
                "orderno"=>"=",
                "mobile"=>"=",
                "cust_name"=>"like",
                "orderstatus"=>"=",
                "addtime" => "times",
            ],$where);
        $where['pay_voucher'] = 1;
        $list = Model::ins("ConOrder")->pageList($where, "*", "addtime desc, id desc");

        foreach ($list['list'] as $key => $value) {
            $list['list'][$key]['totalamount'] = DePrice($value['totalamount']);
            $list['list'][$key]['count'] = DePrice($value['count']);
        }

        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => '在线购买砖石记录',
        );
        
        return $this->view($viewData);
    }

    /**
     * [returnorderlistAction 转账购物记录]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-20T16:12:13+0800
     * @return   [type]                   [description]
     */
    public function returnorderlistAction(){
        $where = $this->searchWhere([
                "orderno"=>"=",
                "mobile"=>"=",
                "cust_name"=>"like",
                "orderstatus"=>"=",
                "addtime" => "times",
            ],$where);
        $where['pay_voucher'] = 2;
        $list = Model::ins("ConOrder")->pageList($where, "*", "addtime desc, id desc");

        foreach ($list['list'] as $key => $value) {
            $list['list'][$key]['totalamount'] = DePrice($value['totalamount']);
            $list['list'][$key]['count'] = DePrice($value['count']);
        }

        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "title" => '转账购买砖石记录',
        );
        
        return $this->view($viewData);
    }

}