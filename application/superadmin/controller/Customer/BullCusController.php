<?php
// +----------------------------------------------------------------------
// |  [ 牛粉管理]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年3月28日 21:17:18
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Customer;
use app\superadmin\ActionController;
//use app\superadmin\controller\Customer\CustomerController;

//use \think\Config;
//use app\lib\Db;
use app\lib\Model;
use app\model\Sys\CommonModel;
use app\model\Sys\CommonRoleModel;
use \think\Config;
use app\lib\log;

class BullCusController extends ActionController{

    const disenable = -1;
    const cusRole = 1;
    const countyRole = 6;
    const cityRole = 7;
    const title = "牛粉管理";
    protected $_roleLookup = array('1' => '牛粉', '2' => '牛人', '3' => '牛创客', '4' => '牛商', '5' => '牛掌柜', "6" => "孵化中心", "7" => "运营中心", "8"=>"牛达人");
	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();        
    }
    
    /**
     * [layoutAction 页面tab布局]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @return [type]    [description]
     */
    public function layoutAction(){
        return $this->view(array('title' => '牛粉管理'));
    }

    public function tempAction() {
    	if(CommonRoleModel::getUserRoleGive(["customerid"=>$this->params['customerid'],"role"=>$this->params['role']])) {
            $data = "非赠送";
        } else {
            $data = "赠送";
        }
        return $this->json(200, $data);
    }

    // public function synchroBusBusinessAction() {
    //     // $where['id'] = [" not in", "select id from bus_business_info"];
    //     $where = "id not in (select id from bus_business_info)";
    //     $busData = Model::ins("BusBusiness")->getList($where,"*","id desc");

    //     $cusModel = Model::ins("CusCustomer");
    //     $busInfoModel = Model::ins("BusBusinessInfo");
    //     $count = 0;
    //     foreach ($busData as $bus) {
    //         $cus = $cusModel->getRow(["id"=>$bus['customerid']],"mobile");

    //         $busInfo = ["id"=>$bus['id'], "price_type"=>$bus['price_type'], "businessname"=>$bus['businessname'],"mobile"=>$cus['mobile'],"addtime"=>$bus['addtime']];

    //         $busInfoModel->insert($busInfo);
    //         $count++;
    //     }

    //     echo $count;
    //     echo $this->json(200);
    //     exit;
    // }
    
    public function synchroShareNoRecoAction() {
        // 获取这段时间关系为空的用户
        $noRoleList = Model::ins("CusRelation")->getList(["role"=>1,"addtime"=>[[">=","2017-09-11 20:22:40"],["<=","2017-09-13 11:00:00"]]],"customerid");
        
        $flowBonusModel = Model::ins("AmoFlowBonus");
        
        $noFlowList = array();
        foreach ($noRoleList as $v) {
            // 查看这个用户在流水表中是否已经产生收益了
            $bonusRecord = $flowBonusModel->getRow(["userid"=>$v['customerid'],"flowtype"=>100],"id");
            if(empty($bonusRecord['id'])) {
                // 假如 没有，写入列表中
                $noFlowList[] = $v;
            }
        }
        
        echo "无流水数据用户数：".count($noFlowList)."<br>";
        
        $count = 0;
        
        $cash_config = Config::get("cash_profit");
        $recoedamount = $cash_config['share_recoed_bull_return'];
        $recoedbonusamount = $cash_config['share_recoed_bonus_return'];
        
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();
        
        try {
            // 遍历空的用户流水列表
            foreach ($noFlowList as $value) {
                $orderno = "NNHNF".rand(1000,9999).time().rand(1000,9999);
                $flowid = Model::new("Amount.Flow")->getFlowId($orderno);
                // 写入流水
                $resulted = Model::new("Amount.Amount")->add_bullamount([
                    "userid"=>$value['customerid'],
                    "amount"=>$recoedamount,
                    "usertype"=>"1",
                    "tablename"=>"AmoFlowCusBull",
                    "flowtype"=>88,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "role"=>1,
                ]);
                
                //公司扣减牛豆
                Model::new("Amount.Amount")->pay_com_bullamount([
                    "amount"=>$recoedamount,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "flowtype"=>51,
                ]);
                
                $resultedBonus = Model::new("Amount.Bonus")->add_bonusamount([
                    "userid"=>$value['customerid'],
                    "amount"=>$recoedbonusamount,
                    "usertype"=>"1",
                    "tablename"=>"AmoFlowCusBonus",
                    "flowtype"=>100,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "role"=>1,
                ]);
                
                // 公司扣减奖励金
                Model::new("Amount.Bonus")->pay_com_bonusamount([
                    "amount"=>$recoedbonusamount,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "flowtype"=>102,
                ]);
                $count++;
            }
            $amountModel->commit();
            
            echo "添加流水用户数:".$count;
            return $this->json(200);
        } catch (\Exception $e) {
            $amountModel->delRedis($cus['id']);
            $amountModel->rollback();
            
            Log::add($e,__METHOD__);
            
            return $this->json("400");
        }
    }
    
    public function synchroShareRoleAction() {
//         $mobile = $this->params['mobile'];
//         $parentMobile = $this->params['parentMobile'];
        $mobile = '13222210789';
        $parentMobile = '13915535028';

        $cus = Model::ins("CusCustomer")->getRow(["mobile"=>$mobile],"id");
        $parentCus = Model::ins("CusCustomer")->getRow(["mobile"=>$parentMobile],"id");
        
        
        $userid = $parentCus['id'];
        $customerid = $cus['id'];

        $orderno = "NNHNF".rand(1000,9999).time().rand(1000,9999);
        $flowid = Model::new("Amount.Flow")->getFlowId($orderno);
        $cash_config = Config::get("cash_profit");
        
        $recoamount = $cash_config['share_reco_bull_return'];
        
        $recoedamount = $cash_config['share_recoed_bull_return'];
        
        $bullamount = $recoamount + $recoedamount;
        
        
        // 矫正牛粉关系
        $grandpa = Model::ins("CusRelation")->getRow(["customerid"=>$userid,"role"=>1,"parentrole"=>1],"parentid");
        
        $grandpa['parentid'] = !empty($grandpa['parentid']) ? $grandpa['parentid'] : -1;
        $grandpa['parentrole'] = !empty($grandpa['parentid']) ? 1 : -1;
        
        
        // 查看注册人牛粉关系
        $cusRelation = Model::ins("CusRelation")->getRow(["customerid"=>$customerid,"role"=>1,"parentrole"=>-1],"id");
        
        $amountModel = Model::ins("AmoAmount");
        $amountModel->startTrans();

        try {
            if($bullamount>0) {
                // 给分享人
                $result = Model::new("Amount.Amount")->add_bullamount([
                    "userid"=>$userid,
                    "amount"=>$recoamount,
                    "usertype"=>"1",
                    "tablename"=>"AmoFlowCusBull",
                    "flowtype"=>50,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "role"=>1,
                ]);
                
                //公司扣减牛豆
                Model::new("Amount.Amount")->pay_com_bullamount([
                    "amount"=>$recoamount,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "flowtype"=>51,
                ]);
                
//                 $resulted = Model::new("Amount.Amount")->add_bullamount([
//                     "userid"=>$customerid,
//                     "amount"=>$recoedamount,
//                     "usertype"=>"1",
//                     "tablename"=>"AmoFlowCusBull",
//                     "flowtype"=>88,
//                     "flowid"=>$flowid,
//                     "orderno"=>$orderno,
//                     "role"=>1,
//                 ]);
                
//                 //公司扣减牛豆
//                 Model::new("Amount.Amount")->pay_com_bullamount([
//                     "amount"=>$recoedamount,
//                     "flowid"=>$flowid,
//                     "orderno"=>$orderno,
//                     "flowtype"=>51,
//                 ]);
            }
            
            $recobonusamount = $cash_config['share_reco_bonus_return'];
            
            $recoedbonusamount = $cash_config['share_recoed_bonus_return'];
            
            $bonusamount = $recobonusamount + $recoedbonusamount;
            
            if($bonusamount>0) {
                // 给分享人
                $resultBonus = Model::new("Amount.Bonus")->add_bonusamount([
                    "userid"=>$userid,
                    "fromuserid"=>$customerid,
                    "amount"=>$recobonusamount,
                    "usertype"=>"1",
                    "tablename"=>"AmoFlowCusBonus",
                    "flowtype"=>101,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "role"=>1,
                ]);
            
                // 公司扣减奖励金
                Model::new("Amount.Bonus")->pay_com_bonusamount([
                    "amount"=>$recobonusamount,
                    "flowid"=>$flowid,
                    "orderno"=>$orderno,
                    "flowtype"=>102,
                ]);
            
                // 给注册者
//                 $resultedBonus = Model::new("Amount.Bonus")->add_bonusamount([
//                     "userid"=>$customerid,
//                     "amount"=>$recoedbonusamount,
//                     "usertype"=>"1",
//                     "tablename"=>"AmoFlowCusBonus",
//                     "flowtype"=>100,
//                     "flowid"=>$flowid,
//                     "orderno"=>$orderno,
//                     "role"=>1,
//                 ]);
            
//                 // 公司扣减奖励金
//                 Model::new("Amount.Bonus")->pay_com_bonusamount([
//                     "amount"=>$recoedbonusamount,
//                     "flowid"=>$flowid,
//                     "orderno"=>$orderno,
//                     "flowtype"=>102,
//                 ]);
            }
            
            // 
            if(!empty($cusRelation['id'])) {
                Model::ins("CusRelation")->update(["customerid"=>$customerid,"role"=>1,"parentrole"=>1,"parentid"=>$userid,"grandparole"=>$grandpa['parentrole'],"grandpaid"=>$grandpa['parentid']],["id"=>$cusRelation['id']]); 
            } else {
                Model::ins("CusRelation")->insert(["customerid"=>$customerid,"role"=>1,"parentrole"=>1,"parentid"=>$userid,"grandparole"=>$grandpa['parentrole'],"grandpaid"=>$grandpa['parentid']]);
            }
            
            Model::ins("RoleRelation")->update(["parent_id" => $userid, "grandpa_id"=>$grandpa['parentid']],["id"=>$customerid]);
            
            $amountModel->commit();
            return $this->json(200);
        } catch (\Exception $e) {
            $amountModel->delRedis($cus['id']);
            $amountModel->rollback();
            
            Log::add($e,__METHOD__);
            
            return $this->json("400");
        }
    }
    
    // public function userShareRoleAction() {
    //     // Model::new("Amount.Role")->tempShareRole(["userid"=>9155,"customerid"=>9866]);
    //     Model::new("Amount.Role")->tempShareRole(["userid"=>603,"customerid"=>3970]);
        
    //     echo $this->json(200);
    //     exit;
    // }
    
    // public function insertBonusDataAction() {
    //     $cusBonusWhere['direction'] = 1;
    //     $cusBonusWhere['userid'] = [">",0];
        
    //     // 收入流水类型转换成支出类型
    //     $cusBonusType=["100"=>"102","101"=>"102","104"=>"107","110"=>"113","115"=>"107","117"=>"118","119"=>"120","121"=>"122","123"=>"125","127"=>"129","131"=>"133",
    //         "137"=>"138","141"=>"142","147"=>"148","151"=>"152","153"=>"154","155"=>"156","186"=>"189"];
        
    //     $passType = ["143","144","157"];
        
    //     $cusBonusList = Model::ins("AmoFlowCusBonus")->getList($cusBonusWhere,"*","id asc");
    //     $comBonusModel = Model::ins("AmoFlowComBonus");
    //     $flowBonusModel = Model::ins("AmoFlowBonus");
    //     $comBonusCount = 0;
    //     $flowBonusCount = 0;
    //     foreach ($cusBonusList as $k => $v) {
            
    //         $cusBonusWhere['flowtype'] = $v['flowtype'];
    //         $cusBonusWhere['flowid'] = $v['flowid'];
    //         $cusBonusWhere['direction'] = 1;
    //         $cusBonusWhere['role'] = $v['role'];
    //         $cusBonusWhere['amount'] = $v['amount'];
    //         $cusBonusWhere['userid'] = $v['userid'];
    //         $cusBonusWhere['orderno'] = $v['orderno'];
    //         $cusBonusWhere['fromuserid'] = $v['fromuserid'];
    //         $cusBonusWhere['parent_userid'] = $v['parent_userid'];
        
    //         if(in_array($v['flowtype'],$passType)) {
    //             // 不处理支出类型 跳过支出操作
                
    //             // 执行总表收入写入操作
    //             $cusFlowBonusRecord = $flowBonusModel->getRow($cusBonusWhere,"id");
    //             if(empty($cusFlowBonusRecord['id'])) {
    //                 // 假如没有写入
    //                 $cusBonusData = ["flowid"=>$v['flowid'],"flowtype"=>$v['flowtype'],"direction"=>1,"amount"=>$v['amount'],"role"=>$v['role'],
    //                     "flowtime"=>$v['flowtime'],"orderno"=>$v['orderno'],"userid"=>$v['userid'],"fromuserid"=>$v['fromuserid'],"parent_userid"=>$v['parent_userid']];
    //                 $insert_id = $flowBonusModel->insert($cusBonusData);
    //                 if($insert_id) {
    //                     $flowBonusCount += 1;
    //                 }
    //             }
                
    //             continue;
    //         }
        
    //         // 支出表
    //         $comBonusWhere['flowtype'] = $cusBonusType[$v['flowtype']];
    //         $comBonusWhere['flowid'] = $v['flowid'];
    //         $comBonusWhere['direction'] = 2;
    //         $comBonusWhere['amount'] = $v['amount'];
    //         $comBonusWhere['orderno'] = $v['orderno'];
        
    //         $comBonusData = ["flowid"=>$v['flowid'],"flowtype"=>$cusBonusType[$v['flowtype']],"direction"=>2,"amount"=>$v['amount'],"flowtime"=>$v['flowtime'],
    //             "orderno"=>$v['orderno'],"role"=>1];
        
    //         $comBonusRecord = $comBonusModel->getRow($comBonusWhere,"id");
    //         if(empty($comBonusRecord['id'])) {
    //             // 假如没有写入
    //             $insert_id = $comBonusModel->insert($comBonusData);
    //             if($insert_id) {
    //                 $comBonusCount += 1;
    //             }
    //         }
        
    //         // 总表
    //         //查看是否有收入数据
    //         $cusFlowBonusRecord = $flowBonusModel->getRow($cusBonusWhere,"id");
    //         if(empty($cusFlowBonusRecord['id'])) {
    //             // 假如没有写入
    //             $cusBonusData = ["flowid"=>$v['flowid'],"flowtype"=>$v['flowtype'],"direction"=>1,"amount"=>$v['amount'],"role"=>$v['role'],
    //                 "flowtime"=>$v['flowtime'],"orderno"=>$v['orderno'],"userid"=>$v['userid'],"fromuserid"=>$v['fromuserid'],"parent_userid"=>$v['parent_userid']];
    //             $insert_id = $flowBonusModel->insert($cusBonusData);
    //             if($insert_id) {
    //                 $flowBonusCount += 1;
    //             }
    //         }
        
    //         $comFlowBonusRecord = $flowBonusModel->getRow($comBonusWhere,"id");
    //         if(empty($comFlowBonusRecord['id'])) {
    //             $insert_id = $flowBonusModel->insert($comBonusData);
    //             if($insert_id) {
    //                 $flowBonusCount += 1;
    //             }
    //         }
    //     }
    //     echo "写入comBonus表".$comBonusCount."条数据"."<br>";
    //     echo "写入flowBonus表".$flowBonusCount."条数据"."<br>";
    //     echo $this->json(200);
    //     //         print_r($cusBonusList);
    //     exit;
    // }
    
    /**
    * @user 同步牛掌柜角色点亮问题
    * @param 
    * @author jeeluo
    * @date 2017年7月27日下午3:45:25
    */
    public function synchroRoleAction() {
        $list = Model::ins("StoBusiness")->getList(["enable"=>-1],"customerid");
        
        // 回复所有的状态
        $cusRoleModel = Model::ins("CusRole");
        $cusRoleModel->modify(["enable"=>1],["role"=>5,"enable"=>-1]);
        
        // 遍历禁用
        $count = 0;
        foreach ($list as $v) {
            $cusRoleModel->modify(["enable"=>-1],["customerid"=>$v['customerid'],"role"=>5]);
            $count++;
        }
        
        echo $count;
        echo $this->json(200);
        exit;
    }
    
    /**
    * @user 同步成为牛达人归属关系
    * @param 
    * @author jeeluo
    * @date 2017年7月27日下午3:46:07
    */
    public function synchroNdRelationAction() {
        $cusRelationModel = Model::ins("CusRelation");
        $first = $cusRelationModel->getList(["role"=>8,"parentrole"=>-1],"customerid,addtime,id");
        
        $count = 0;
        foreach ($first as $firstv) {
            //
            $second = $cusRelationModel->getRow(["role"=>3,"customerid"=>$firstv['customerid'],"addtime"=>$firstv['addtime']],"customerid,parentid,parentrole");
        
            if(!empty($second)) {
                $parentNd = $cusRelationModel->getRow(["role"=>8,"customerid"=>$second['parentid'],"parentrole"=>8],"parentid,parentrole");
        
                $grandpaid = $grandparole = -1;
                if(!empty($parentNd)) {
                    $grandpaid = $parentNd['parentid'];
                    $grandparole = $parentNd['parentrole'];
                }
        
        
                $cusRelationModel->modify(["parentid"=>$second['parentid'],"parentrole"=>8,"grandpaid"=>$grandpaid,"grandparole"=>$grandparole,"addtime"=>$firstv['addtime']],["id"=>$firstv['id']]);
                $count++;
            }
        }
        echo $count;
        echo $this->json(200);
        exit;
    }

    /**
    * @user 用户列表
    * @param 
    * @author jeeluo
    * @date 2017年5月4日上午11:21:54
    */
    public function userListAction() {
        $where = array();
        $where = $this->searchWhere([
            "role" => "=",
            "mobile" => "like",
            "createtime" => "times",
        ],$where);

        
        if(!empty($where['role'])) {
            $where['id'] = ["in", "select customerid from cus_role where role = ".$where['role']];
            unset($where['role']);
        }
        
        $bullCusRoleModel = Model::new("Customer.BullCusRole");
        $list = $bullCusRoleModel->userSimpleList($where);
        
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total']+513510,
            "allRole" => $this->_roleLookup,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 分享列表页
    * @param 
    * @author jeeluo
    * @date 2017年8月9日下午3:57:52
    */
//     public function refererAction() {
//         if(!$this->getParam("id")) {
//             $this->showError("请正确操作");
//         }
        
//         $customerid = $this->getParam("id");
//         // 查询用户有多少种角色
//         $where['customerid'] = $customerid;
//         $cusRoleList = Model::ins("CusRole")->getList($where,"role","role asc");
        
//         $roleList = array();
//         foreach ($cusRoleList as $k => $v) {
//             $roleList[$k]['name'] = $this->_roleLookup[$v];
//             $roleList[$k]['url'] = "/Customer/BullCus/roleReferer?customerid=".$customerid."&role=".$v['role'];
//         }
        
//         $viewData = array(
//             "roleList" => $roleList,
//             "title" => "查看分享人",
//         );
        
//         return $this->view($viewData);
//     }
    
    /**
    * @user 用户粉丝
    * @param 
    * @author jeeluo
    * @date 2017年5月4日下午8:09:07
    */
    public function userFansAction() {
        if(!$this->getParam("role") || !$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        
        $customerid = $this->getParam("id");
        $role = $this->getParam("role");
        
//         if($role != self::cusRole) {
//             $this->showError("请正确操作");
//         }
        
//         $where['flowtype'] = array("in", CommonModel::getCashProfitType());
//         $where['direction'] = 1;
//         $where['userid'] = $customerid;
//         $amount = Model::ins("AmoFlowCusCash")->getRow($where, "SUM(amount) as amount ");
        
//         $amount['amount'] = !empty($amount['amount']) ? DePrice($amount['amount']) : '0.00';
        
        $params['selfRoleType'] = $role;
        $recoList = Model::new("User.RoleReco")->getRoleRecoType($params);
        
        $fansList = array();
        foreach($recoList as $k => $v) {
            $fansList[$k]['name'] = $this->_roleLookup[$v];
            $fansList[$k]['url'] = "/Customer/BullCus/fansList?customerid=".$customerid."&selfRoleType=".$role."&recoRoleType=".$v;
        }
        
        $viewData = array(
            "fansList" => $fansList,
            "title" => "粉丝",
        );
        
        return $this->view($viewData);
    }
    
    public function fansListAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType']) || empty($this->params['customerid'])) {
            $this->showError("请正确操作");
        }
        
        $result = Model::new("Customer.BullCusRole")->flowRecoFans($this->params);
        if($result["code"] != "200") {
            $this->showError($result["data"]);
        }
        
        $viewData = array(
            "pagelist" => $result['data']['list'],
            "total" => $result['data']['total'],
            "urlPath" => "?selfRoleType=".$this->params['selfRoleType']."&recoRoleType=".$this->params['recoRoleType']."&customerid=".$this->params['customerid']
        );
        
        return $this->view($viewData);
    }

	/**
	 * [indexAction 牛粉列表]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017-03-25T10:47:03+0800
	 * @return   [type]                   [description]
	 */
	public function indexAction(){	    	    
	    //var_dump('/Customer/BullCus/index'); exit();
	    
	    $username = strval($this->getParam('username'));
        
	    $where = array();
// 	    $where = $this->searchWhere([
// 	        "username"=>"like"
// 	    ],$where);
	    if(!empty($username)){
	        $where = array_merge($where, array('username' => $username));
	    }
	    //var_dump($where); exit();
	    
	    $bullCusRoleModel = Model::new('Customer.BullCusRole');
	    if(!empty($where)){
	        $list = $bullCusRoleModel->getWhereList($where);
	    }else{
	        $list = $bullCusRoleModel->getSimpleList();
	    }	    	    
	    //var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total']+513510, //总数
        );

        return $this->view($viewData);
	}
	
	/**
	* @user 查看用户详情
	* @param 
	* @author jeeluo
	* @date 2017年4月24日上午10:34:09
	*/
	public function editCusCustomerInfoAction() {
	    if(!$this->getParam("id")) {
	        $this->showError("请正确操作");
	    }
	    $customerid = $this->getParam("id");
	    $result = Model::new("Customer.BullCusRole")->getCusCustomerInfo($customerid);
	    
	    if($result['code'] != "200") {
	        $this->showError($result['data']);
	    }
	    
	    $action = '/Customer/BullCus/updateInfo?id='.$customerid;
	    
	    $viewData = array(
	        "title" => self::title,
	        "cusInfo" => $result['data'],
	        "actionUrl" => $action,
	    );
	    
	    return $this->view($viewData);
	}
	
	/**
	* @user 更改用户数据
	* @param 
	* @author jeeluo
	* @date 2017年4月18日下午4:44:42
	*/
	public function updateInfoAction() {
	    if(empty($this->params['id'])) {
	        $this->showError("请选择正确用户");
	    }
	    $params = array();
	    if(!empty($this->params['nickname'])) {
	        $params['nickname'] = $this->params['nickname'];
	    }
	    if(!empty($this->params['realname'])) {
	        $params['realname'] = $this->params['realname'];
	    }
	    
	    Model::ins("CusCustomerInfo")->modify($params, array("id" => $this->params["id"]));
	    
	    return $this->showSuccess("操作成功");
	}
	
	/**
	* @user 黑名单列表
	* @param 
	* @author jeeluo
	* @date 2017年4月18日上午11:26:11
	*/
	public function blacklistAction() {
	    $username = strval($this->getParam('username'));
	    
	    $where = array();
	    
	    $where['enable'] = self::disenable;
	    if(!empty($username)) {
	        $where = array_merge($where, array("username" => $username));
	    }
	    $bullCusRoleModel = Model::new("Customer.BullCusRole");
	    
	    $list = $bullCusRoleModel->getBlackList($where);
	    
	    $viewData = array(
	        "pagelist" => $list['list'],
	        "total" => $list['total'],
	    );
	    
	    return $this->view($viewData);
	}
	
	/**
	* @user 禁用操作
	* @param 
	* @author jeeluo
	* @date 2017年4月18日上午9:47:30
	*/
	public function enableAction() {
	    if(!$this->getParam("id") || !$this->getParam("enable")) {
	        $this->showError("请正确操作");
	    }
	    
	    $params['id'] = $this->getParam("id");
	    $params['enable'] = $this->getParam("enable");
	    
	    $result = Model::new("Customer.BullCusRole")->enable($params);
	    
	    if($result["code"] != 200) {
	        $this->showError($result['data']);
	    }
	    $this->showSuccess("操作成功");
	}
	
	/**
	* @user 用户角色关系
	* @param 
	* @author jeeluo
	* @date 2017年4月24日上午10:43:55
	*/
	public function userRoleRelationAction() {
	    if(!$this->getParam("id")) {
	        $this->showError("请正确操作");
	    }
	    
	    $customerid = $this->getParam("id");
	    $result = Model::new("Customer.BullCusRole")->getRoleRelation($customerid);
	    
	    if($result["code"] != 200) {
	        $this->showError($result['data']);
	    }
	    
	    $viewData = array(
	        "relation" => $result["data"],
	    );
	    
	    return $this->view($viewData);
	}
}