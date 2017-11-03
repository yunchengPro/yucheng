<?php
// +----------------------------------------------------------------------
// |  [ 提现管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年4月7日 下午3:55:31
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Amount;
use app\superadmin\ActionController;
use think\Config;

//use \think\Config;
//use app\lib\Db;
use app\lib\Model;

use app\form\CusWithdrawals\CusWithdrawalsAdd;

use app\lib\Log;

use app\lib\Pay\Allinpay\ProcessServlet;

class WithdrawalsController extends ActionController{

	var $status_arr = [
		"0"=>"待处理",
		"1"=>"到帐成功",
		"2"=>"不通过",
		"3"=>"转账中...",
		"4"=>"转账失败",
	];

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

        //$where = array("status"=>["in","0,1,2"]);     

        if($this->params['customer_mobile']!='')
        	$where['customerid'] = ["in","select id from cus_customer where mobile like '%".$this->params['customer_mobile']."%'"];

        $SysUser = Model::ins('SysUser');
            
        $where = $this->searchWhere([
            //"status" => "="
            "account_name"=>"like",
            "account_number"=>"like",
            "branch"=>"like",
            "mobile"=>"like",
            "orderno"=>"=",
            "addtime"=>"times",

        ],$where);
        
        $status_arr = $this->status_arr;
        //$list = Model::ins("CusWithdrawals")->pageList($where,'*','id desc');
        $list = Model::ins("CusWithdrawals")->pageList($where, '*', 'id desc');
        
        $withdrawals_config = Config::get("withdrawals");

        //var_dump($list); exit();
        foreach($list['list'] as $key => $val){
            $list['list'][$key]['amount']    = DePrice($val['amount']);
            $list['list'][$key]['cashamount']    = DePrice($val['cashamount']);
            $list['list'][$key]['comamount']    = DePrice($val['comamount']);
            $list['list'][$key]['pay_money'] = DePrice($val['pay_money']);
            $list['list'][$key]['poundage'] = DePrice($val['amount'] * $withdrawals_config['service_proportion']);
            $list['list'][$key]['need_money'] = DePrice($val['amount'] * $withdrawals_config['withdrawals_proportion']);
            
            $customerId = $val['customerid'];
            $customer   = Model::ins("CusCustomer")->getById($customerId);
            $list['list'][$key]['customer_mobile'] = $customer['mobile'];

            $list['list'][$key]['status_str'] = $status_arr[$val['status']];

            if($val['handle_userid']>0){
            	$userrow = $SysUser->getRow(["id"=>$val['handle_userid']],"username");
            	$list['list'][$key]['handle_user'] = $userrow['username'];
            }
        }
        
//         var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
            "status_arr"=>$status_arr,
        );
        //var_dump($viewData); exit();

        return $this->view($viewData);
	}

	/**
     * [indexAction 提现列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月5日 下午2:40:44
     * @return [type]    [description]
     */
	public function listAction(){	            
        //提现列表
        
        $where = array("status"=>1);   

        if($this->params['customer_mobile']!='')
        	$where['customerid'] = ["in","select id from cus_customer where mobile like '%".$this->params['customer_mobile']."%'"];

        if($this->params['query_sn']!='')
            $where['orderno'] = ["in","select orderno from allinpay_ps_log where query_sn like '%".$this->params['query_sn']."%'"];

        $where = $this->searchWhere([
            //"status" => "="
            "account_name"=>"like",
            "account_number"=>"like",
            "branch"=>"like",
            "mobile"=>"like",
            "orderno"=>"=",
            "addtime"=>"times",
        ],$where);
        //$list = Model::ins("CusWithdrawals")->pageList($where,'*','id desc');
        $list = Model::ins("CusWithdrawals")->pageList($where, '*', 'id desc');

        $SysUser = Model::ins('SysUser');

        //$AllinpayPsLog = Model::ins("AllinpayPsLog");

        //提现总额
        $total_row = Model::ins("CusWithdrawals")->getRow(array("status"=>1),"sum(pay_money) as total_amount");
        $total_amount = DePrice($total_row['total_amount']);

        $search_row = Model::ins("CusWithdrawals")->getRow($where,"sum(pay_money) as total_amount");
        $search_amount = DePrice($search_row['total_amount']);

        //var_dump($list); exit();
        foreach($list['list'] as $key => $val){
            $list['list'][$key]['amount']    = DePrice($val['amount']);
            $list['list'][$key]['cashamount']    = DePrice($val['cashamount']);
            $list['list'][$key]['comamount']    = DePrice($val['comamount']);
            $list['list'][$key]['pay_money'] = DePrice($val['pay_money']);
            
            $customerId = $val['customerid'];
            $customer   = Model::ins("CusCustomer")->getById($customerId);
            $list['list'][$key]['customer_mobile'] = $customer['mobile'];

            if($val['handle_userid']>0){
            	$userrow = $SysUser->getRow(["id"=>$val['handle_userid']],"username");
            	$list['list'][$key]['handle_user'] = $userrow['username'];
            }

            // 查询交易流水
            //$pslog = $AllinpayPsLog->getRow(["orderno"=>$val['orderno']],"query_sn");
            //$list['list'][$key]['query_sn'] = $pslog['query_sn'];

        }
//         var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
            "total_amount"=>$total_amount,
            "search_amount"=>$search_amount,
        );
        //var_dump($viewData); exit();

        return $this->view($viewData);
	}

    // 导出
    public function exportlistAction(){

        $offset     = $this->getParam("offset",0);
        $count      = 30; //每次导出的数据条数 可以设更高都可以

        $where = array("status"=>1);   

        if($this->params['customer_mobile']!='')
            $where['customerid'] = ["in","select id from cus_customer where mobile like '%".$this->params['customer_mobile']."%'"];

        if($this->params['query_sn']!='')
            $where['orderno'] = ["in","select orderno from allinpay_ps_log where query_sn like '%".$this->params['query_sn']."%'"];

        $where = $this->searchWhere([
            //"status" => "="
            "account_name"=>"like",
            "account_number"=>"like",
            "branch"=>"like",
            "mobile"=>"like",
            "orderno"=>"=",
            "addtime"=>"times",
        ],$where);
        //$list = Model::ins("CusWithdrawals")->pageList($where,'*','id desc');
        //$list = Model::ins("CusWithdrawals")->getList($where, "'' as customer_mobile,bank_type_name,account_name,account_number,branch,mobile,addtime,status,remark,amount,cashamount,comamount,pay_money,pay_time,handle_user,'' as query_sn", 'id desc',$count,$offset*$count);

        $list = Model::ins("CusWithdrawals")->getList($where, "*", 'id desc',$count,$offset*$count);

        $SysUser = Model::ins('SysUser');

        $AllinpayPsLog = Model::ins("AllinpayPsLog");

        $status_arr = ['0' => '处理中', '1'=>'到帐成功', '2'=>'提现失败'];

        //var_dump($list); exit();
        foreach($list as $key => $val){
            $list[$key]['amount']    = DePrice($val['amount']);
            $list[$key]['cashamount']    = DePrice($val['cashamount']);
            $list[$key]['comamount']    = DePrice($val['comamount']);
            $list[$key]['pay_money'] = DePrice($val['pay_money']);

            $list[$key]['status'] = $status_arr[$val['status']];
            
            $customerId = $val['customerid'];
            $customer   = Model::ins("CusCustomer")->getById($customerId);
            $list[$key]['customer_mobile'] = $customer['mobile'];

            if($val['handle_userid']>0){
                $userrow = $SysUser->getRow(["id"=>$val['handle_userid']],"username");
                $list[$key]['handle_user'] = $userrow['username'];
            }

            // 查询交易流水
            $pslog = $AllinpayPsLog->getRow(["orderno"=>$val['orderno']],"query_sn");
            $list[$key]['query_sn'] = $pslog['query_sn'];

        }
        
        echo $this->iniExcelData(array(
                            "filename"=>"提现记录-".date("YmdHis"),
                            //"head"=>array("用户手机","银行名称","银行开户名","银行账号","支行名称","银行卡手机号","提交时间","提现状态","未通过原因","提现总金额","现金金额","企业金额","实际支付金额","支付时间","处理人","交易流水号",), //excel表头
                            "field"=>[
                                "customer_mobile"=>"用户手机",
                                "bank_type_name"=>"银行名称",
                                "account_name"=>"银行开户名",
                                "account_number"=>"银行账号",
                                "branch"=>"支行名称",
                                "mobile"=>"银行卡手机号",
                                "addtime"=>"提交时间",
                                "status"=>"提现状态",
                                "remark"=>"未通过原因",
                                "amount"=>"提现总金额",
                                "cashamount"=>"现金金额",
                                "comamount"=>"企业金额",
                                "pay_money"=>"实际支付金额",
                                "pay_time"=>"支付时间",
                                "handle_user"=>"处理人",
                                "query_sn"=>"交易流水号",
                            ],
                            "list"=>$list,
                            "offset"=>$offset,
                        ));
        exit;
    }

	/**
	 * 代付处理页面
	 * @Author   zhuangqm
	 * @DateTime 2017-08-02T14:37:50+0800
	 * @return   [type]                   [description]
	 */
	public function processservletAction(){
		//提现列表

        //$where = array("status"=>["in","select withdrawalsid from cus_withdrawals_processservlet"]);     

        if($this->params['customer_mobile']!='')
        	$where['customerid'] = ["in","select id from cus_customer where mobile like '%".$this->params['customer_mobile']."%'"];

            
        $where = $this->searchWhere([
            //"status" => "="
            "account_name"=>"like",
            "account_number"=>"like",
            "branch"=>"like",
            "mobile"=>"like",
            "orderno"=>"=",
            "addtime"=>"times",

        ],$where);
        
        $status_arr = $this->status_arr;
        //$list = Model::ins("CusWithdrawals")->pageList($where,'*','id desc');
        $list = Model::ins("CusWithdrawalsProcessservlet")->pageList($where, '*', 'id desc');
        //var_dump($list); exit();
        
        $CusWithdrawals = Model::ins("CusWithdrawals");
        $SysUser = Model::ins('SysUser');

        foreach($list['list'] as $key => $val){

        	$withdrawals = $CusWithdrawals->getRow(["id"=>$val['withdrawalsid']],"*");

        	$list['list'][$key]['bankid'] = $withdrawals['bankid'];
        	$list['list'][$key]['account_name'] = $withdrawals['account_name'];
        	$list['list'][$key]['account_number'] = $withdrawals['account_number'];
        	$list['list'][$key]['branch'] = $withdrawals['branch'];
        	$list['list'][$key]['mobile'] = $withdrawals['mobile'];
        	$list['list'][$key]['bank_type_name'] = $withdrawals['bank_type_name'];

            $list['list'][$key]['amount']    = DePrice($val['amount']);
            $list['list'][$key]['cashamount']    = DePrice($val['cashamount']);
            $list['list'][$key]['comamount']    = DePrice($val['comamount']);
            $list['list'][$key]['pay_money'] = DePrice($val['pay_money']);
            
            $customerId = $val['customerid'];
            $customer   = Model::ins("CusCustomer")->getById($customerId);
            $list['list'][$key]['customer_mobile'] = $customer['mobile'];

            $list['list'][$key]['status_str'] = $status_arr[$val['status']];

            if($val['handle_userid']>0){
            	$userrow = $SysUser->getRow(["id"=>$val['handle_userid']],"username");
            	$list['list'][$key]['handle_user'] = $userrow['username'];
            }
        }
        
//         var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
            "status_arr"=>$status_arr,
        );
        //var_dump($viewData); exit();

        return $this->view($viewData);
	}
	
	/**
	 * [passAction 不通过审核表单]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月7日 上午11:45:32
	 * @return [type]    [description]
	 */
	public function passAction()
	{
	    //var_dump('/Amount/Withdrawals/pass'); exit();
	
	    $action = '/Amount/Withdrawals/savePass';
	
	    $id = $this->getParam('id');
	
	    if(empty($id)){
	        $this->showError('请选择提现申请');
	    }
	
	    $model = Model::ins('CusWithdrawals');
	    $info  = $model->getById($id);
	    //var_dump($info); exit();
	    if(empty($info)){
	        $this->showError('不存在该提现申请');
	    }elseif($info['status'] != 0){
	        $this->showError('非法操作');
	    }
	
	    //form验证token
// 	    $formtoken      = $this->Btoken('Amount-Withdrawals-pass');
// 	    $poundage = Model::new("Amount.AmoAmount")->getWithdrawalsPoundage(["customerid"=>$info['customerid'],"accountType"=>$info['accountType'],"type"=>2,"amount"=>$info['amount']]);
// 	    $info['poundage'] = $poundage;
// 	    $info['pay_money'] = DePrice($info['amount']-$poundage);
// 	    $info['amount'] = DePrice($info['amount']);

	    $withdrawals_config = Config::get("withdrawals");
	    $info['poundage'] = $info['amount'] * $withdrawals_config['service_proportion'];
	    
	    $info['pay_money'] = DePrice($info['amount'] * $withdrawals_config['withdrawals_proportion']);
	    
	    
	    // 查询用户已经提现的金额
	    $totalWithdrawals = Model::ins("CusWithdrawals")->getRow(['customerid'=>$info['customerid'],"status"=>1],"sum(cashamount) as amount");
	    $info['total'] = !empty($totalWithdrawals['amount']) ? DePrice($totalWithdrawals['amount']) : '0.00';
	    
	    $viewData = array(
	        "title"=>"实际支付金额",
	        "info"=>$info,
// 	        'formtoken'=>$formtoken,
	        "action"=>$action
	    );
	
	    return $this->view($viewData);
	}
	
	/**
	 * [savePassAction 通过审核]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月7日 上午11:10:22
	 * @return [type]    [description]
	 */
	public function savePassAction()
	{
	    //var_dump('/Amount/Withdrawals/pass'); exit();
	
// 	    if($this->Ctoken()){
        //if(1){    	    
    	    $id = $this->getParam('id');
    	    $post = $this->params;
    	    
    	    if(empty($id)){
    	        $this->showError('请选择提现申请');
    	    }
    	    
    	    $model = Model::ins('CusWithdrawals');
    	    $post = $model->_facade($post);
    	    
    	    //var_dump($post); exit();
    	    
    	    //自动验证表单 需要修改form对应表名
//     	    $CusWithdrawalsAdd = new CusWithdrawalsAdd();
//     	    if(!$CusWithdrawalsAdd->isValid($post)){//验证是否正确
//     	        $this->showError($CusWithdrawalsAdd->getErr());//提示报错信息
//     	    }else{
        	    $model = Model::ins('CusWithdrawals');
        	    $info  = $model->getRow(["id"=>$id],"*");
        	    //var_dump($roleRecoOr); exit();
        	    if(empty($info)){
        	        $this->showError('不存在该提现申请');
        	    }elseif($info['status'] != 0){
        	        $this->showError('非法操作');
        	    }else{
//         	        $post['pay_money'] = EnPrice($post['pay_money']);
        	        if($post['pay_money'] > $info['amount']){
        	            $this->showError('实际金额不能大于申请金额，非法操作');
        	        }
        	    }
        		
        	    //金额返回
	            //事务处理
		        $amountModel = Model::ins("AmoAmount");
		        $amountModel->startTrans();
		        
		        try{

		        	$info['pay_money']     = $post['pay_money'];
        	    	$info['handle_userid'] = $this->customerid;
        	    	$result = Model::new('User.Withdrawals')->pass($info);
        	    	/*
		        	//扣除手续费
	        	    if($result && $info['amount']>$post['pay_money']){
	        	    	$flowid = Model::new("Amount.Flow")->getFlowId($info['orderno']);
	        	    	//添加手续费
	        	    	Model::new("Amount.Amount")->add_cashamount([
                                "fromuserid"=>$info['customerid'],
                                "userid"=>"-1",
                                "amount"=>$info['amount']-$post['pay_money'],
                                "orderno"=>$info['orderno'],
                                "flowtype"=>86,
                                "role"=>1,
                                "tablename"=>"AmoFlowCusCash",
                                "flowid"=>$flowid,
                            ]);
	        	    }

	        	    //实时转账
	        	    if($info['orderno']=='NNHWI20170729170105377925'){
	        	    	$ProcessServlet = new ProcessServlet();

				        $response = $ProcessServlet->getPayOrder([
				                "userid"=>$info['customerid'],
				                "orderno"=>$info['orderno'],
				                "pay_price"=>$info['amount'],
				                "account_no"=>$info['account_number'],
				                "account_name"=>$info['account_name'],
				                "summary"=>"提现转账",
				            ]);
				        
				        if($response['code']!='200'){
				            $amountModel->delRedis($info['customerid']);
		            		$amountModel->rollback();
		            		$this->showError('操作失败1-'.$response['msg']);
				        }
	        	    }*/


        	    	// 提交支付申请
        	    	/*$ProcessServlet = new ProcessServlet();

			        $response = $ProcessServlet->getPayOrder([
			                "userid"=>$info['customerid'],
			                "orderno"=>$info['orderno'],
			                "pay_price"=>$info['amount'],
			                "account_no"=>$info['account_number'],
			                "account_name"=>$info['account_name'],
			                "summary"=>"提现转账-".$info['orderno'],
			            ]);
			        
			        if($response['code']=='200'){
			           
			        	$info['pay_money']     = $post['pay_money'];
	        	    	$info['handle_userid'] = $this->customerid;
        	    		$result = Model::new('User.Withdrawals')->passin($info); // 处理中

			        }else{
			        	$amountModel->delRedis($info['customerid']);
	            		// $amountModel->rollback(); //--不需要做rollback
	            		$this->showError('操作失败1-'.$response['msg']);
			        }*/

			        //if($response['code']=='200'){
			           
// 			        	$info['pay_money']     = $post['pay_money'];
// 	        	    	$info['handle_userid'] = $this->customerid;
//         	    		$result = Model::new('User.Withdrawals')->passin($info); // 处理中

			        /*}else{
			        	$amountModel->delRedis($info['customerid']);
	            		// $amountModel->rollback(); //--不需要做rollback
	            		$this->showError('操作失败1-'.$response['msg']);
			        }*/

		        	$amountModel->commit(); 

		        	
		        } catch (\Exception $e) {
		            
		            // 错误日志
		            // 回滚事务
		            $amountModel->delRedis($info['customerid']);
		            $amountModel->rollback();

		            Log::add($e,__METHOD__);

		            $this->showError('操作失败2');
		        }

        	    //var_dump($result); exit();
        	    /*Model::new("Sys.Mq")->add([
        	        // "url"=>"Msg.SendMsg.withdrawals",
        	        "url"=>"Order.OrderMsg.withdrawals",
        	        "param"=>[
        	            "orderno"=>$info['orderno']
        	        ],
        	    ]);
        	    Model::new("Sys.Mq")->submit();*/
        	
        	    $this->showSuccess('成功通过审核');
//     	    }
// 	    }else{
// 	        $this->showError('token错误，禁止操作');
// 	    }
	}


	public function passprocessservletAction(){
		//var_dump('/Amount/Withdrawals/pass'); exit();
	
	    
        //if(1){    	    
    	    $id = $this->getParam('id');
    	    
    	    
    	    if(empty($id)){
    	        $this->showError('请选择提现申请');
    	    }
    	    
    	    //$model = Model::ins('CusWithdrawalsProcessservlet');
    	    //$post = $model->_facade($post);
    	    
    	    //var_dump($post); exit();
    	    
    	    //自动验证表单 需要修改form对应表名
    	    //$CusWithdrawalsAdd = new CusWithdrawalsAdd();
    	    //if(!$CusWithdrawalsAdd->isValid($post)){//验证是否正确
    	        //$this->showError($CusWithdrawalsAdd->getErr());//提示报错信息
    	    //}else{
        	    $model = Model::ins('CusWithdrawalsProcessservlet');
        	    $info  = $model->getRow(["id"=>$id],"*");
        	    $Withdrawals = Model::ins('CusWithdrawals')->getRow(["id"=>$info['withdrawalsid']],"*");
        	    //var_dump($roleRecoOr); exit();
        	    if(empty($info) || empty($Withdrawals)){
        	        $this->showError('不存在该提现申请');
        	    }elseif($info['status'] != 0){
        	        $this->showError('非法操作');
        	    }


        	    //金额返回
	            //事务处理
		        $amountModel = Model::ins("AmoAmount");
		        $amountModel->startTrans();
		        
		        try{

		        	
        	    	// 提交支付申请
        	    	$ProcessServlet = new ProcessServlet();

			        $response = $ProcessServlet->getPayOrder([
			                "userid"=>$info['customerid'],
			                "orderno"=>$info['orderno'],
			                "pay_price"=>$info['amount'],
			                "account_no"=>$Withdrawals['account_number'],
			                "account_name"=>$Withdrawals['account_name'],
			                "summary"=>"提现转账-".$info['orderno'],
			            ]);
			        
			        if($response['code']=='200'){
			           
	        	    	$info['handle_userid'] = $this->customerid;
        	    		$result = Model::new('User.Withdrawals')->passprocessservlet($info); // 处理中

			        }else{
			        	$amountModel->delRedis($info['customerid']);
	            		// $amountModel->rollback(); //--不需要做rollback
	            		$this->showError('操作失败1-'.$response['msg']);
			        }

		        	$amountModel->commit(); 

		        	
		        } catch (\Exception $e) {
		            
		            // 错误日志
		            // 回滚事务
		            $amountModel->delRedis($info['customerid']);
		            $amountModel->rollback();

		            Log::add($e,__METHOD__);

		            $this->showError('操作失败2');
		        }

        	    //var_dump($result); exit();
        	    /*Model::new("Sys.Mq")->add([
        	        // "url"=>"Msg.SendMsg.withdrawals",
        	        "url"=>"Order.OrderMsg.withdrawals",
        	        "param"=>[
        	            "orderno"=>$info['orderno']
        	        ],
        	    ]);
        	    Model::new("Sys.Mq")->submit();*/
        	
        	    $this->showSuccess('操作成功');
    	    //}
	    
	}
	
	/**
	 * [noPassAction 不通过审核表单]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月7日 上午11:45:32
	 * @return [type]    [description]
	 */
	public function noPassAction()
	{
	    //var_dump('/Amount/Withdrawals/noPass'); exit();
	
	    $action = '/Amount/Withdrawals/saveNoPass';
	
	    $id = $this->getParam('id');
	
	    if(empty($id)){
	        $this->showError('请选择提现申请');
	    }
	
	    $model = Model::ins('CusWithdrawals');
	    $info  = $model->getById($id);
	    //var_dump($info); exit();
	    if(empty($info)){
	        $this->showError('不存在该提现申请');
	    }elseif($info['status'] != 0 && $info['status'] != 4 ){
	        $this->showError('非法操作');
	    }
	
	    //form验证token
// 	    $formtoken = $this->Btoken('Amount-Withdrawals-noPass');
	    $viewData = array(
	        "title"=>"未通过原因",
	        "info"=>$info,
// 	        'formtoken'=>$formtoken,
	        "action"=>$action
	    );
	
	    return $this->view($viewData);
	}
	
	/**
	 * [saveNoPassAction 保存不通过审核]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年4月7日 上午11:53:00
	 * @return [type]    [description]
	 */
	public function saveNoPassAction()
	{
	    //var_dump('/Amount/Withdrawals/saveNoPass'); exit();
	
// 	    if($this->Ctoken()){
	    //if(1){
	        $id = $this->getParam('id');
	        $post = $this->params;
	
	        if(empty($id)){
	            $this->showError('请选择提现申请');
	        }
	
	        $model = Model::ins('CusWithdrawals');
	        $post = $model->_facade($post);
	        //var_dump($post); exit();
	
	        //自动验证表单 需要修改form对应表名
// 	        $CusWithdrawalsAdd = new CusWithdrawalsAdd();
// 	        if(!$CusWithdrawalsAdd->isValid($post)){//验证是否正确
// 	            $this->showError($CusWithdrawalsAdd->getErr());//提示报错信息
// 	        }else{
	            $info = $model->getById($id);
	            if(empty($info)){
	                $this->showError('不存在该提现申请');
	            }elseif($info['status'] != 0 && $info['status'] != 4){
        	        $this->showError('非法操作');
        	    }
	
	            $info['remark'] = $post['remark'];
	            $info['handle_userid'] = $this->customerid;

	            $result = Model::new('User.Withdrawals')->noPass($info);
	            //var_dump($result); exit();
	            
	            //金额返回
	             //事务处理
		        $amountModel = Model::ins("AmoAmount");
		        $amountModel->startTrans();
		        
		        try{

		        	$flowid = Model::new("Amount.Flow")->getFlowId($info['orderno']);

		        	$frozenOBJ = Model::new("Amount.Frozen");
		        	//资金返回
		            if($info['cashamount']>0)
		                $frozenOBJ->returnCashAmount([
		                        "userid"=>$info['customerid'],
		                        "amount"=>$info['cashamount'],
		                        "orderno"=>$info['orderno'],
		                        "flowid"=>$flowid,
		                    ]);

		            //资金返回
// 		            if($info['comamount']>0)
// 		                $frozenOBJ->returnComAmount([
// 		                        "userid"=>$info['customerid'],
// 		                        "amount"=>$info['comamount'],
// 		                        "orderno"=>$info['orderno'],
// 		                        "flowid"=>$flowid,
// 		                    ]);

		                // Model::new("Msg.SendMsg")->withdrawals(array("userid"=>$info['customerid'],"orderno"=>$info['orderno']));
		                
// 	                Model::new("Sys.Mq")->add([
// 	                    // "url"=>"Msg.SendMsg.withdrawals",
// 	                    "url"=>"Order.OrderMsg.withdrawals",
// 	                    "param"=>[
// 	                        "orderno"=>$info['orderno'],
// 	                    ],
// 	                ]);
// 	                Model::new("Sys.Mq")->submit();
		        	$amountModel->commit(); 

		        	$this->showSuccess('操作成功');
		        } catch (\Exception $e) {
		            //print_r($e);
		            // 错误日志
		            // 回滚事务
		            $amountModel->delRedis($info['customerid']);
		            $amountModel->rollback();

		            Log::add($e,__METHOD__);

		            $this->showError('操作失败');
		        }
	
	            $this->showSuccess('操作成功');
// 	        }
// 	    }else{
// 	        $this->showError('token错误，禁止操作');
// 	    }
	}
}