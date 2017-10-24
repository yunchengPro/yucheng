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

use app\lib\Db;
use app\model\User\AmountModel;
use app\lib\Model;
use app\model\Sys\PayOrderTypeModel;

use think\Config;
use app\model\User\WithdrawalsModel;

class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * 财务统计
     * @Author   zhuangqm
     * @DateTime 2017-03-31T14:25:40+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){
        // 现金
        $count_pay = Model::ins("PayOrder")->getRow(["pay_status"=>[">",0]],"sum(pay_money) as pay_money");
        $pay_money = DePrice($count_pay['pay_money']);

        // 用户现金余额
        $user_amount = Model::ins("AmoAmount")->getRow([],"sum(cashamount) as cashamount");
        $cashamount = DePrice($user_amount['cashamount']);

        // 用户现金收入
        $amo_flow_cus_cash_in = Model::ins("AmoFlowCusCash")->getRow(["direction"=>1],"sum(amount) as amount");
        $user_cash_in = DePrice($amo_flow_cus_cash_in['amount']);

        // 用户现金支出
        $amo_flow_cus_cash = Model::ins("AmoFlowCusCash")->getRow(["direction"=>2],"sum(amount) as amount");
        $user_cash = DePrice($amo_flow_cus_cash['amount']);

        // 公司现金支出
        // $amo_flow_com_cash = Model::ins("AmoFlowComCash")->getRow(["direction"=>2],"sum(amount) as amount");
        // $com_cash = DePrice($amo_flow_com_cash['amount']);

        // 用户待收现金收入
        // $user_cash_flow = Model::ins("AmoFlowFutCusCash")->getRow(["direction"=>1,"futstatus"=>0],"sum(amount) as amount");
        // $futcashflowamount = DePrice($user_cash_flow['amount']);

        // 总提现
        // $cus_withdrawals = Model::ins("CusWithdrawals")->getRow(["status"=>[">",0]],"sum(pay_money) as pay_money");
        // $withdrawals = DePrice($cus_withdrawals['pay_money']);

        // 充值
        // $cus_recharge = Model::ins("CusRecharge")->getRow(["pay_status"=>[">",0]],"sum(pay_money) as pay_money");
        // $recharge = DePrice($cus_recharge['pay_money']);
        
        //-------------收益现金---------------
        // 公司收益现金余额
        // $amo_com_amount = Model::ins("AmoComAmount")->getRow(["id"=>1],"profitamount");
        // $com_profitamount = DePrice($amo_com_amount['profitamount']);

        // 公司收益现金支出
        // $amo_flow_com_profit = Model::ins("AmoFlowComProfit")->getRow(["direction"=>2],"sum(amount) as amount");
        // $com_profit = DePrice($amo_flow_com_profit['amount']);

        // 公司待收益现金支出
        // $amo_flow_fut_com_profit = Model::ins("AmoFlowFutComProfit")->getRow(["direction"=>2,"futstatus"=>0],"sum(amount) as amount");
        // $com_fut_profit = DePrice($amo_flow_fut_com_profit['amount']);

        // 用户收益现金余额
        $user_amount = Model::ins("AmoAmount")->getRow([],"sum(profitamount) as profitamount");
        $profitamount = DePrice($user_amount['profitamount']);

        // 用户收益现金支出：
        $user_profit_flow = Model::ins("AmoFlowCusProfit")->getRow(["direction"=>2],"sum(amount) as amount");
        $profitflowamount = DePrice($user_profit_flow['amount']);

        // 用户收益现金收入：
        $user_profit_flow = Model::ins("AmoFlowCusProfit")->getRow(["direction"=>1],"sum(amount) as amount");
        $profitflowamount_in = DePrice($user_profit_flow['amount']);

        // 用户待收益现金收入
        // $user_profit_flow = Model::ins("AmoFlowFutCusProfit")->getRow(["direction"=>1,"futstatus"=>0],"sum(amount) as amount");
        // $futprofitflowamount = DePrice($user_profit_flow['amount']);


        //-----------牛豆-----------
        // 公司收益现金余额
        // $amo_com_amount = Model::ins("AmoComAmount")->getRow(["id"=>1],"bullamount");
        // $com_bullamount = DePrice($amo_com_amount['bullamount']);

        // 公司收益现金支出
        // $amo_flow_com_bull = Model::ins("AmoFlowComBull")->getRow(["direction"=>2],"sum(amount) as amount");
        // $com_bull = DePrice($amo_flow_com_bull['amount']);

        // 公司待收益现金支出
        // $amo_flow_fut_com_bull = Model::ins("AmoFlowFutComBull")->getRow(["direction"=>2,"futstatus"=>0],"sum(amount) as amount");
        // $com_fut_bull = DePrice($amo_flow_fut_com_bull['amount']);

        // 用户收益现金余额
        $user_amount = Model::ins("AmoAmount")->getRow([],"sum(bullamount) as bullamount");
        $bullamount = DePrice($user_amount['bullamount']);

        // 用户收益现金支出：
        $user_bull_flow = Model::ins("AmoFlowCusBull")->getRow(["direction"=>2],"sum(amount) as amount");
        $bullflowamount = DePrice($user_bull_flow['amount']);

        // 用户收益现金收入：
        $user_bull_flow = Model::ins("AmoFlowCusBull")->getRow(["direction"=>1],"sum(amount) as amount");
        $bullflowamount_in = DePrice($user_bull_flow['amount']);

        // 用户待收益现金收入
        // $user_bull_flow = Model::ins("AmoFlowFutCusBull")->getRow(["direction"=>1,"futstatus"=>0],"sum(amount) as amount");
        // $futbullflowamount = DePrice($user_bull_flow['amount']);

        //公司总账户
        $com_amount = Model::ins("AmoComAmount")->getRow(["id"=>1],"*");
        $com_amount['cashamount'] = DePrice($com_amount['cashamount']);
        $com_amount['profitamount'] = DePrice($com_amount['profitamount']);
        $com_amount['bullamount'] = DePrice($com_amount['bullamount']);
        $com_amount['counteramount'] = DePrice($com_amount['counteramount']);
        $com_amount['charitableamount'] = DePrice($com_amount['charitableamount']);

        //公司奖励金
        $com_bonusamount = Model::ins("AmoComBonus")->getRow(["id"=>1],"bonusamount");
        $com_amount['bonusamount'] = DePrice($com_bonusamount['bonusamount']);

        //公司分润账户
        $cus_com_amount = Model::ins("AmoAmount")->getRow(["id"=>"-1"],"cashamount,profitamount,bullamount");
        $cus_com_amount['cashamount'] = DePrice($cus_com_amount['cashamount']);
        $cus_com_amount['profitamount'] = DePrice($cus_com_amount['profitamount']);
        $cus_com_amount['bullamount'] = DePrice($cus_com_amount['bullamount']);

        //公司分润账户--待收
        $fut_cus_com_amount = [];
        $fut_cus_com_row = Model::ins("AmoFlowFutCusCash")->getRow(["userid"=>"-1","futstatus"=>"0"],"sum(amount) as amount");
        $fut_cus_com_amount['cashamount'] = DePrice($fut_cus_com_row['amount']);
        $fut_cus_com_row = Model::ins("AmoFlowFutCusProfit")->getRow(["userid"=>"-1","futstatus"=>"0"],"sum(amount) as amount");
        $fut_cus_com_amount['profitamount'] = DePrice($fut_cus_com_row['amount']);
        $fut_cus_com_row = Model::ins("AmoFlowFutCusBull")->getRow(["userid"=>"-1","futstatus"=>"0"],"sum(amount) as amount");
        $fut_cus_com_amount['bullamount'] = DePrice($fut_cus_com_row['amount']);


        //公司充值记录
        $com_recharge_amount = [];
        $sys_recharge = Model::ins("SysRecharge")->getRow(["customerid"=>"-1","recharge_type"=>"1"],"sum(amount) as amount");
        $com_recharge_amount['cashamount'] = DePrice($sys_recharge['amount'])+10000;
        $sys_recharge = Model::ins("SysRecharge")->getRow(["customerid"=>"-1","recharge_type"=>"2"],"sum(amount) as amount");
        $com_recharge_amount['profitamount'] = DePrice($sys_recharge['amount'])+10000;
        $sys_recharge = Model::ins("SysRecharge")->getRow(["customerid"=>"-1","recharge_type"=>"3"],"sum(amount) as amount");
        $com_recharge_amount['bullamount'] = DePrice($sys_recharge['amount'])+10000;

        //牛豆充值卡总额
        $bull_code_row = Model::ins("BullCodeCode")->getRow([],"sum(amount) as amount");
        $bull_code_amount = DePrice($bull_code_row['amount']);

        //分润角色
        $user_com_arr = [
            "2"=>"牛店全国运营公司",
            "3"=>"牛品全国运营公司",
            "4"=>"商学院",
            "5"=>"牛人全国运营中心",
            "6"=>"文化传媒运营中心",
            "7"=>"云牛惠科技",
            "8"=>"集团",
            "9"=>"全国区运营中心",
            "10"=>"全国市运营中心",
        ];
        $user_com_amount = Model::ins("AmoComAmount")->getList("id>=2 and id<=10","*");
        foreach($user_com_amount as $k=>$v){
            $user_com_amount[$k]['cashamount'] = DePrice($v['cashamount']);
        }
        //print_r($user_com_amount);
        $viewData = [
            //"pagelist"=>$list['list'], //列表数据
            //"total"=>$list['total'], //总数
            "pay_money"=>$pay_money,
            "com_cash"=>$com_cash,
            "cashamount"=>$cashamount,
            "user_cash_in"=>$user_cash_in,
            "user_cash"=>$user_cash,
            "futcashflowamount"=>$futcashflowamount,
            //"withdrawals"=>$withdrawals,
            //"recharge"=>$recharge,

            "com_profitamount"=>$com_profitamount,
            "com_profit"=>$com_profit,
            "com_fut_profit"=>$com_fut_profit,
            "profitamount"=>$profitamount,
            "profitflowamount"=>$profitflowamount,
            "profitflowamount_in"=>$profitflowamount_in,
            "futprofitflowamount"=>$futprofitflowamount,

            "com_bullamount"=>$com_bullamount,
            "com_bull"=>$com_bull,
            "com_fut_bull"=>$com_fut_bull,
            "bullamount"=>$bullamount,
            "bullflowamount"=>$bullflowamount,
            "bullflowamount_in"=>$bullflowamount_in,
            "futbullflowamount"=>$futbullflowamount,

            "com_amount"=>$com_amount,
            "cus_com_amount"=>$cus_com_amount,

            "fut_cus_com_amount"=>$fut_cus_com_amount,

            "com_recharge_amount"=>$com_recharge_amount,

            "bull_code_amount"=>$bull_code_amount,

            "user_com_arr"=>$user_com_arr,
            "user_com_amount"=>$user_com_amount,
        ];
        return $this->view($viewData);
    }

    /**
     * 分润统计
     * @Author   zhuangqm
     * @DateTime 2017-05-15T19:58:01+0800
     * @return   [type]                   [description]
     */
    public function prifitAction(){

        $cash_profit = Config::get("cash_profit");
        
        // 供应商货款 已返+待返
        $buscash = Model::ins("AmoFlowFutBusCash")->getRow("flowtype=16 and direction=1 and futstatus in(0,1) and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount"); 
        $buscash_amount = DePrice($buscash['amount']);

        //牛品分润
        /*$busprofit = Model::ins("AmoProfit")->getRow(["flowtype"=>["in","11,12,3,4,7,8,9,10,1,2,14,15,5,6,13"]],"sum(profit_amount) as amount");
        $busprofit_amount = DePrice($busprofit['amount']);*/
        $busprofit = Model::ins("AmoFlowFutCusCash")->getRow("flowtype in(11,12,3,4,7,8,9,10,1,2,5,6) and futstatus in(0,1) and direction=1 and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount");
        $busprofit_amount = DePrice($busprofit['amount']);

        //交易手续费
        $couprofit = Model::ins("AmoFlowFutCouCash")->getRow("flowtype=14 and futstatus in(0,1) and direction=1 and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount");
        $busprofit_amount+=DePrice($couprofit['amount']);

        //慈善
        $chaprofit = Model::ins("AmoFlowFutChaCash")->getRow("flowtype=15 and futstatus in(0,1) and direction=1 and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount");
        $busprofit_amount+=DePrice($chaprofit['amount']);

        //剩余的给公司
        $comprofit = Model::ins("AmoFlowFutComCash")->getRow("flowtype=13 and futstatus in(0,1) and direction=1 and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount");
        $busprofit_amount+=DePrice($comprofit['amount']);


        // 牛品返收益现金
        /*$busprofitamount = Model::ins("AmoProfit")->getRow(["flowtype"=>["in","22"]],"sum(profit_amount) as amount");
        $busprofitamount_amount = DePrice($busprofitamount['amount']);*/
        $busprofitamount = Model::ins("AmoFlowFutCusProfit")->getRow("flowtype=22 and direction=1 and futstatus in(0,1) and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount");
        $busprofitamount_amount = DePrice($busprofitamount['amount']);

        //公司分得的收益现金
        $comprofitamount = Model::ins("AmoFlowFutComProfit")->getRow("flowtype=26 and direction=1 and futstatus in(0,1) and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount");
        $busprofitamount_amount+=DePrice($comprofitamount['amount']);


        // 牛品返牛豆 转换
        /*$busbullamount = Model::ins("AmoProfit")->getRow(["flowtype"=>["in","28"]],"sum(profit_amount) as amount");
        $busbullamount_amount = DePrice($busprofitamount['amount']);*/
        $busbullamount = Model::ins("AmoFlowFutCusBull")->getRow("flowtype=28 and direction=1 and futstatus in(0,1) and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount");
        $busbullamount_amount = DePrice($busprofitamount['amount']);

        //公司获得的牛豆
        $combullamount = Model::ins("AmoFlowFutComBull")->getRow("flowtype=34 and direction=1 and futstatus in(0,1) and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount");
        $busbullamount_amount+=DePrice($combullamount['amount']);

        $busbullamount_amount = $busbullamount_amount*($cash_profit['cashObjBullPro']/100);

        // 牛品收入 （牛品消费-退款）
        /*$buscuscash = Model::ins("AmoFlowCusCash")->getRow(["flowtype"=>20,"direction"=>2,],"sum(amount) as amount");
        $buscuscash_in = DePrice($buscuscash['amount']);*/
        $buscuscash = Model::ins("OrdOrderPay")->getRow(["pay_status"=>1,"pay_money"=>[">",0],"bullamount"=>0],"sum(pay_money) as amount");
        $buscuscash_in = DePrice($buscuscash['amount']);

        // 退款
        $busrefund = Model::ins("AmoFlowCusCash")->getRow("flowtype=48 and direction=1 and orderno in(select orderno from ord_order_pay where pay_status=1 and pay_money>0 and bullamount=0)","sum(amount) as amount");
        $buscuscash_out = DePrice($busrefund['amount']);

        $viewData = [
            "buscash_amount"=>$buscash_amount,
            "busprofit_amount"=>$busprofit_amount,
            "busprofitamount_amount"=>$busprofitamount_amount,
            "busbullamount_amount"=>$busbullamount_amount,
            "buscuscash_in"=>$buscuscash_in,
            "buscuscash_out"=>$buscuscash_out,
        ];

        return $this->view($viewData);
    }

    /**
    * @user 余额查询
    * @param 
    * @author jeeluo
    * @date 2017年4月18日下午5:25:50
    */
    public function balanceAction() {
        
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "like",
        ], $where);
        
        $list = Model::ins("CusCustomer")->pageList($where, "id,mobile", "id desc");
        
        foreach ($list['list'] as $key => $val) {
            $amount = Model::ins("AmoAmount")->getRow(array("id" => $val['id']), "id,cashamount,profitamount,bullamount,comamount", "id desc");
            
            $list['list'][$key]['cashamount'] = !empty($amount['cashamount']) ? DePrice($amount['cashamount']) : '0.00';
            $list['list'][$key]['profitamount'] = !empty($amount['profitamount']) ? DePrice($amount['profitamount']) : '0.00';
            $list['list'][$key]['bullamount'] = !empty($amount['bullamount']) ? DePrice($amount['bullamount']) : '0.00';
            $list['list'][$key]['comamount'] = !empty($amount['comamount']) ? DePrice($amount['comamount']) : '0.00';
            
            // 查询用户详情表数据
            $cusInfo = Model::ins("CusCustomerInfo")->getRow(array("id" => $val['id']), "realname, nickname");
            $list['list'][$key]['realname'] = !empty($cusInfo['realname']) ? $cusInfo['realname'] : $cusInfo['nickname'];
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 交易流水详情数据
    * @param 
    * @author jeeluo
    * @date 2017年6月21日上午9:53:58
    */
    public function flowCusAction() {
        if(empty($this->params['customerid']) || empty($this->params['type'])) {
            $this->showError("请选择正确操作");
        }
        
        $cusOBJ = Model::ins("CusCustomer");
        $cusInfoOBJ = Model::ins("CusCustomerInfo");

        $idstr = "";
        $shouru_page = 0;
        $zhichu_page = 0;
        
        if($this->params['type'] == 1 || $this->params['type'] == 2 || $this->params['type'] == 3) {
            $flowOBJ;
            if($this->params['type'] == 1)
                $flowOBJ = Model::ins("AmoFlowCash");
            if($this->params['type'] == 2)
                $flowOBJ = Model::ins("AmoFlowCusProfit");
            if($this->params['type'] == 3) 
                $flowOBJ = Model::ins("AmoFlowCusBull");

            $list = Model::new("User.Flow")->flowCus(["type"=>$this->params['type'],"customerid"=>$this->params['customerid']]);
            
            foreach ($list['data']['list'] as $k => $v) {
                $flowInfo = $flowOBJ->getRow(["orderno"=>$v['orderno']],"orderno,fromuserid");
                
                // 根据id 查询手机号码和昵称
                $list['data']['list'][$k]['fromuserInfo'] = '';
                //$list['data']['list'][$k]['orderno'] = !empty($flowInfo['orderno']) ? $flowInfo['orderno'] : $v['id'];
                if(!empty($flowInfo['fromuserid'])) {
                    $cus = $cusOBJ->getRow(["id"=>$flowInfo['fromuserid']],"mobile");
                    $cusInfo = $cusInfoOBJ->getRow(["id"=>$flowInfo['fromuserid']],"nickname");
                    $list['data']['list'][$k]['fromuserInfo'] = $cus['mobile']."(".$cusInfo['nickname'].")";
                }

                $flowtype = Config::get('flowtype');
                $flowTypeInfo = $flowtype[$v['flowtype']];
                // 对提现进行文字提示(针对)
                if($v['flowtype'] == 19) {
                    
                    // 假如是提现的话，做特殊处理
                    $flowWithdrawals = WithdrawalsModel::getOrderByWithdrawls(["id"=>$v['id']]);
                    
                    if($flowWithdrawals['code'] != "200") {
                        $list['data']['list'][$k]['flowname'] = $flowTypeInfo['flowname'];
                    } else {
                        $list['data']['list'][$k]['flowname'] = "提现-".$flowWithdrawals['data']['bank_name']."(".$flowWithdrawals['data']['account_number'].")";
                    }
                } else {
                    $list['data']['list'][$k]['flowname'] = $flowTypeInfo['flowname'];
                }

                $idstr.=$v['id'].",";
                if($v['direction']==1){
                    $shouru_page+=$v['amount'];
                }else{
                    $zhichu_page+=$v['amount'];
                }
            }
            
        } else if($this->params['type'] == 4) {
            $list = Model::new("User.Flow")->flowUserCom(["customerid"=>$this->params['customerid']]);
            $flowOBJ = Model::ins("AmoFlowCash");
            foreach ($list['data']['list'] as $k => $v) {
                $list['data']['list'][$k]['flowname'] = $v['flowid'];
                $flowInfo = $flowOBJ->getRow(["orderno"=>$v['orderno']],"orderno,fromuserid");
                
                $list['data']['list'][$k]['fromuserInfo'] = '';
                //$list['data']['list'][$k]['orderno'] = !empty($flowInfo['orderno']) ? $flowInfo['orderno'] : $v['id'];
                if(!empty($flowInfo['fromuserid'])) {
                    // 根据id 查询手机号码和昵称
                    $cus = $cusOBJ->getRow(["id"=>$flowInfo['fromuserid']],"mobile");
                    $cusInfo = $cusInfoOBJ->getRow(["id"=>$flowInfo['fromuserid']],"nickname");
                    
                    $list['data']['list'][$k]['fromuserInfo'] = $cus['mobile']."(".$cusInfo['nickname'].")";
                }

                $idstr.=$v['id'].",";
                if($v['direction']==1){
                    $shouru_page+=$v['amount'];
                }else{
                    $zhichu_page+=$v['amount'];
                }
            }
        }

        $idstr = $idstr!=''?substr($idstr,0,-1):0;

        //1、计算用户的总收入和支出
        $shouru = $flowOBJ->getRow(["userid"=>$this->params['customerid'],"direction"=>1],"sum(amount) as amount");
        $zhichu = $flowOBJ->getRow(["userid"=>$this->params['customerid'],"direction"=>2],"sum(amount) as amount");

        $viewData = array(
            "pagelist" => $list['data']['list'],
            "total" => $list['data']['total'],
            "urlPath" => "/Amount/Index/flowCus?customerid=".$this->params['customerid']."&type=".$this->params['type'],
            "shouru"=>DePrice($shouru['amount']),
            "zhichu"=>DePrice($zhichu['amount']),
            "amount_diff"=>($shouru['amount']-$zhichu['amount'])/100,
            "shouru_page"=>$shouru_page,
            "zhichu_page"=>$zhichu_page,
            "amount_diff_page"=>$shouru_page-$zhichu_page,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 交易明细页面
    * @param 
    * @author jeeluo
    * @date 2017年6月21日上午9:53:42
    */
    public function flowLayoutAction() {
        if(empty($this->getParam("id"))) {
            $this->showError("请选择正确操作");
        }
        
        $userInfo = Model::ins("CusCustomer")->getRow(["id"=>$this->getParam("id")],"id");
        if(empty($userInfo)) {
            $this->showError("数据不存在");
        }
        // 传递id值
        $viewData = array(
            "id" => $userInfo['id'],
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 收入明细
    * @param 
    * @author jeeluo
    * @date 2017年4月22日下午5:28:42
    */
    public function incomeAction() {
        
        $where = array();
        $where = $this->searchWhere([
            "addtime" => "times",
            "type" => "=",
        ], $where);
        
        $whereType = $where['type'];
        unset($where['type']);
        
        $where['pay_status'] = 1;
        
        if(!empty($whereType)) {
            $type = array();
            if($whereType == 1) {
                $type = PayOrderTypeModel::weixinType();
            } else if($whereType == 2) {
                $type = PayOrderTypeModel::aliType();
            } else if($whereType == 3) {
                // 银联暂无
                $type = PayOrderTypeModel::quickType();
            }
            
            if(!empty($type)) {
                $where['pay_type'] = array();
                foreach ($type as $k => $v) {
                    $temp = array();
                    if($k == 0) {
                        $temp = array("=", $v);
                    } else {
                        $temp = array("=", $v, "or");
                    }
                    $where['pay_type'][$k] = $temp;
                }
            }
        }
        $timeIncome = array();
        
        if(!empty($where['type']) || !empty($where['addtime'])) {
            $timeIncome = Model::ins("PayOrder")->getRow($where, "SUM(pay_money) as amount ");
            $timeIncome['amount'] = !empty($timeIncome['amount']) ? DePrice($timeIncome['amount']) : '0.00';
        }
        
        $list = Db::Table("PayOrder")->pageList($where, "id,pay_money,addtime,pay_type", $this->order("id", "desc"));

//         $list = Model::ins("PayOrder")->pageList($where, "id,pay_money,addtime,pay_type","id desc");
        
        foreach ($list['list'] as $key => $val) {
            $list['list'][$key]['pay_money'] = !empty($val['pay_money']) ? DePrice($val['pay_money']) : '0.00';
            
            // 支付宝支付
            if(in_array($val['pay_type'], PayOrderTypeModel::aliType())) {
                $list['list'][$key]['type'] = 'ali';
                continue;
            }
            
            // 微信支付
            if(in_array($val['pay_type'], PayOrderTypeModel::weixinType())) {
                $list['list'][$key]['type'] = 'weixin';
                continue;
            }
            
            // 银联支付
            if(in_array($val['pay_type'], PayOrderTypeModel::quickType())) {
                $list['list'][$key]['type'] = 'quick';
                continue;
            }
            
            // 其它支付
            $list['list'][$key]['type'] = '';
        }
        
        $amount['weixin'] = Model::new("Amount.SuperIndex")->getPayTypeAmount(PayOrderTypeModel::weixinType());
        $amount['ali'] = Model::new("Amount.SuperIndex")->getPayTypeAmount(PayOrderTypeModel::aliType());
        $amount['union'] = Model::new("Amount.SuperIndex")->getPayTypeAmount(PayOrderTypeModel::quickType());
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "timeIncome" => $timeIncome,
            "amount" => $amount,
        );
        
        return $this->view($viewData);
    }

    /**
     * 公司账户余额
     * @Author   zhuangqm
     * @DateTime 2017-04-27T19:34:51+0800
     * @return   [type]                   [description]
     */
    public function comamountAction(){

        $where = [
            "id"=>1
        ];

        $list = Model::ins("AmoComAmount")->pageList($where, "*");

        $row = Model::ins("AmoAmount")->getRow(["id"=>"-1"],"*");

        $list['list'][] = $row;
        $list['total']+=1;

        foreach($list['list'] as $key=>$value){
            if($value['id']==1)
                $list['list'][$key]['name'] = '公司总账户';
            if($value['id']=='-1')
                $list['list'][$key]['name'] = '公司余额账户';

            $list['list'][$key]['cashamount'] = DePrice($value['cashamount']);
            $list['list'][$key]['profitamount'] = DePrice($value['profitamount']);
            $list['list'][$key]['bullamount'] = DePrice($value['bullamount']);
            $list['list'][$key]['fut_cashamount'] = DePrice($value['fut_cashamount']);
            $list['list'][$key]['fut_profitamount'] = DePrice($value['fut_profitamount']);
            $list['list'][$key]['fut_bullamount'] = DePrice($value['fut_bullamount']);
            $list['list'][$key]['counteramount'] = DePrice($value['counteramount']);
            $list['list'][$key]['charitableamount'] = DePrice($value['charitableamount']);

        }

        return $this->view([
                "pagelist" => $list['list'],
                "total" => $list['total'],
            ]);
    }

    /**
     * 流水
     * @Author   zhuangqm
     * @DateTime 2017-04-27T17:27:41+0800
     * @return   [type]                   [description]
     */
    public function flowlistAction(){
        $table = $this->params['table'];
        $direction = $this->params['direction'];
        $userid = $this->params['userid'];

        $where = array();

        if(!empty($direction))
            $where['direction'] = $direction;
        if(!empty($userid))
            $where['userid'] = $userid;

        if(!empty($this->params['user'])){
            $where['userid'] = ["in","select id from cus_customer where mobile='".$this->params['user']."'"];
        }

        $where = $this->searchWhere([
            "flowid"=>"=",
            "orderno"=>"=",
            "flowtime"=>"times",
        ], $where);
        
        $list = Model::ins($table)->pageList($where, "*", $this->order("flowtime","desc"));

        $direction_arr = [
            "1"=>"收入",
            "2"=>"支出",
        ];

        $flowtype = Config::get("flowtype");

        $CusOBJ = Model::ins("CusCustomer");
        
        foreach ($list['list'] as $key => $val) {
            $list['list'][$key]['amount'] = DePrice($val['amount']);

            $list['list'][$key]['direction'] = $direction_arr[$val['direction']];

            $user = $CusOBJ->getRow(["id"=>$val['userid']],"mobile");

            $list['list'][$key]['user'] = $user['mobile'];

            $list['list'][$key]['flowtype'] = $flowtype[$val['flowtype']]['flowname'];
        }
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "table"=>$table,
            "direction"=>$direction,
            "userid"=>$userid,
        );
        
        return $this->view($viewData);
    }
}