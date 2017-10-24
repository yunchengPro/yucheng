<?php
namespace app\api\controller\User;

use app\api\ActionController;
use app\model\User\AmountModel;
use app\model\CusBankModel;
use app\model\User\UserModel;
use app\model\Sys\CommonModel;
use app\model\User\FlowModel;
use app\model\CusRoleLogModel;
use app\model\User\RoleModel;
use app\model\User\RoleRecoModel;
use app\model\Sys\CommonRoleModel;
use app\lib\Redis;
use app\lib\Model;

/**
* @user 余额处理
* @param 
* @author jeeluo
* @date 2017年3月21日下午4:18:59
*/
class AmountController extends ActionController {
    
    const defaultRole = 1;
    const enableSuccess = 1;
    const cashType = 1;
    const profitType = 2;
    const bullType = 3;
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 我的余额界面
    * @param 
    * @author jeeluo
    * @date 2017年3月22日上午10:32:44
    */
    public function mybalanceAction() {
        $params['customerid'] = $this->userid;
        // 获取当前用户的角色值
        $params['role'] = CommonModel::getNowCusRole($params);
        // 余额数据
        $amountOBJ = new AmountModel();
        $userInfo['amountInfo'] = $amountOBJ->getUserAmount($params);
        
        if($this->Version("1.0.4")) {
            $profit['starttime'] = CommonModel::getYesterdayTime();
            $profit['endtime'] = CommonModel::getTodayTime();
            $profit['customerid'] = $this->userid;
            $userInfo['profit'] = $amountOBJ->getUserProfit($profit);
        } else {
            // 查询用户营业收入
            $flowCash = $amountOBJ->getFlowBusStoCash($params);
            $userInfo['busCash'] = $flowCash;
            
            // 查询用户待返营业收入
            $flowFutCash = $amountOBJ->getFlowFutBusCash($params);
            $userInfo['futBusCash'] = $flowFutCash;
            
            $cusBankOBJ = new CusBankModel();
            $userInfo['bankNumber'] = $cusBankOBJ->getRow(["customerid"=>$this->userid,"enable"=>self::enableSuccess],"count(*) as count")['count'];
        }
        
        // 待返 金额
        $userInfo['futList']['cash'] = $amountOBJ->getFlowFutCash($params);
        $userInfo['futList']['profit'] = $amountOBJ->getFlowFutProfit($params);
        $userInfo['futList']['bull'] = $amountOBJ->getFlowFutBull($params);
        
        
        
//         $userOBJ = new UserModel();
//         $userBus = $userOBJ->getUserBus($params);
//         // 营业数据
//         if($userBus) {
//             $balance = $amountOBJ->getBusinessBanlance(array("businessid" => $userBus));
// //             $userInfo['busInfo']['cash'] = $balance['data']['sumProductAmount'] ?: '0.00';
// //             $userInfo['busInfo']['bull'] = $balance['data']['sumBillAmount'] ?: '0.00';
// //             $userInfo['busInfo']['cashbull'] = CommonModel::bullChangeCash($userInfo['busInfo']['bull']);
//             $cash = $balance['data']['sumProductAmount'] ?: 0;
//             $cashbull = CommonModel::bullChangeCash($balance['data']['sumBillAmount']);
            
//             $userInfo['busCash'] = DePrice($cash + $cashbull);
//         } else {
// //             $userInfo['busInfo']['cash'] = '0.00';
// //             $userInfo['busInfo']['bull'] = '0.00';
// //             $userInfo['busInfo']['cashbull'] = '0.00';
//             $userInfo['busCash'] = '0.00';
//         }

        // 返回银行卡数量
//         $cusBankOBJ = new CusBankModel();
//         $userInfo['bankNumber'] = $cusBankOBJ->getRow(["customerid"=>$this->userid,"enable"=>self::enableSuccess],"count(*) as count")['count'];
        
        return $this->json(200, $userInfo);
    }
    
    /**
    * @user 现金、收益现金 牛豆等页面
    * @param 
    * @author jeeluo
    * @date 2017年3月23日下午5:10:48
    */
    public function cashAction() {
        // type 1 现金 2 收益现金 3 牛豆
        if(empty($this->params['type'])) {
            return $this->json(404);
        }
        $params['type'] = $this->params['type'];
        
        $typeArr = CommonModel::getCashType();
        if(!in_array($params['type'], $typeArr)) {
            return $this->json(10003);
        }
        $params['customerid'] = $this->userid;
        $params['role'] = CommonModel::getNowCusRole($params);
        $amountOBJ = new AmountModel();
        $result = $amountOBJ->getCash($params);
        $result['rechargeUrl'] = CommonModel::getRechargeUrl();
        
        return $this->json(200, $result);
    }
    
    /**
     * @user 用户金额流水
     * @param
     * @author jeeluo
     * @date 2017年3月25日下午5:52:26
     */
    public function flowcusAction() {
        if(empty($this->params['type'])) {
            return $this->json(404);
        }
        $this->params['customerid'] = $this->userid;
        $flowOBJ = new FlowModel();
        $this->params['version'] = $this->getVersion();
        $this->params['isAndroid'] = $this->Version($this->params['version'], "A");
        $result = $flowOBJ->flowCus($this->params);
    
        return $this->json($result['code'], $result["data"]);
    }
    
    /**
    * @user 企业账户金额流水
    * @param 
    * @author jeeluo
    * @date 2017年4月20日下午3:07:25
    */
    public function flowcomAction() {
        $params['customerid'] = $this->userid;
        $params['role'] = CommonModel::getNowCusRole($params);
        $params['version'] = $this->getVersion();
        $params['begintime'] = $this->params['begintime'];
        $params['endtime'] = $this->params['endtime'];
        $flowOBJ = new FlowModel();
        
        $result = $flowOBJ->flowCom($params);
        
        return $this->json($result['code'], $result["data"]);
    }
    
    /**
    * @user 推荐列表用户分润明细数据
    * @param 
    * @author jeeluo
    * @date 2017年3月31日下午8:29:03
    */
    public function flowrecocuscashAction() {
        if(empty($this->params['role']) || empty($this->params['customerid'])) {
            return $this->json(404);
        }
        if($this->Version("1.0.4")) {
            if(empty($this->params['selfRoleType'])) {
                return $this->json(404);
            }
        }
        $this->params['parentid'] = $this->userid;
        $this->params['version'] = $this->getVersion();
        $this->params['isAndroid'] = $this->Version($this->params['version'], "A");
        $flowOBJ = new FlowModel();
        $result = $flowOBJ->flowRecoCusCash($this->params);

        return $this->json($result['code'], $result["data"]);
    }
    
    /**
    * @user 营业流水
    * @param 
    * @author jeeluo
    * @date 2017年4月5日下午3:48:45
    */
    public function flowbusincomeAction() {
        if(empty($this->params['type'])) {
            return $this->json(404);
        }
        if(!empty($this->params['customerid'])) {
            // 识别该用户是否是你的子店
            $stoStoreInfo = Model::ins("StoStore")->getRow(["customerid"=>$this->params['customerid'],"parentid"=>$this->userid],"id");
            
            $stoRelation = Model::ins("CusRelation")->getRow(["customerid"=>$this->params['customerid'],"parentid"=>$this->userid],"id");

            if(empty($stoStoreInfo) && empty($stoRelation)) {
                return $this->json(1001);
            }
            $this->params['type'] = -1;
        } else {
            $this->params['customerid'] = $this->userid;
            $this->params['type'] = 1;
        }
        $flowOBJ = new FlowModel();
        $result = $flowOBJ->flowBusIncome($this->params);
        return $this->json($result["code"], $result['data']);
    }
    
//     public function flowbusAction() {
//         if(empty($this->params['type'])) {
//             return $this->json(404);
//         }
//         $this->params['customerid'] = $this->userid;
//         $flowOBJ = new FlowModel();
//         $result = $flowOBJ->flowBus($this->params);
        
//         return $this->json($result['code'], $result['data']);
//     }
    
    /**
     * @user 用户待返流水 (可能改变)
     * @param
     * @author jeeluo
     * @date 2017年3月25日下午5:54:58
     */
    public function flowfutcusAction() {
        if(empty($this->params['type'])) {
            return $this->json(404);
        }
        $this->params['customerid'] = $this->userid;
        $this->params['version'] = $this->getVersion();
        $this->params['isAndroid'] = $this->Version($this->params['version'], "A");
        $flowOBJ = new FlowModel();
        $result = $flowOBJ->flowFutCus($this->params);
    
        return $this->json($result['code'], $result["data"]);
    }
    
    /**
    * @user 推荐列表用户流水
    * @param 
    * @author jeeluo
    * @date 2017年3月30日上午9:57:02
    */
    public function flowrecocusAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType']) || empty($this->params['status'])) {
            return $this->json(404);
        }
        
        $reco_status = array(1, 2, 3);
        if(!in_array($this->params['status'], $reco_status)) {
            return $this->json(1001);
        }
        
        // 确保role值在正确范围内
        $roleOBJ = new RoleModel();
        if(!$roleOBJ->roleRange(array("role" => $this->params['selfRoleType']))) {
            return $this->json(20102);
        }
        
        $roleRecoOBJ = new RoleRecoModel();
        $recolist = $roleRecoOBJ->getRoleRecoType($this->params);
        if(empty($recolist)) {
            // 用户角色错误
            return $this->json(1001);
        }
        if(!in_array($this->params['recoRoleType'], $recolist)) {
            // 推荐角色和用户角色所能推荐的范围不匹配
            return $this->json(1001);
        }
        
        $this->params['customerid'] = $this->userid;
        $this->params['version'] = $this->getVersion();
        $flowOBJ = new FlowModel();
        $result = $flowOBJ->flowRecoCus($this->params);
        return $this->json($result["code"], $result['data']);
    }
    
    /**
    * @user 推荐列表公共信息部分
    * @param 
    * @author jeeluo
    * @date 2017年3月31日下午7:37:59
    */
    public function flowrecocuspublicAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType'])) {
            return $this->json(404);
        }
        
        // 确保role值在正确范围内
        $roleOBJ = new RoleModel();
        if(!$roleOBJ->roleRange(array("role" => $this->params['selfRoleType']))) {
            return $this->json(20102);
        }
        
        $roleRecoOBJ = new RoleRecoModel();
        if($this->Version("1.0.3")) {
            $recolist = $roleRecoOBJ->getNewRoleRecoType($this->params);
        } else {
            $recolist = $roleRecoOBJ->getRoleRecoType($this->params);
        }
        if(empty($recolist)) {
            // 用户角色错误
            return $this->json(1001);
        }
        if(!in_array($this->params['recoRoleType'], $recolist)) {
            // 推荐角色和用户角色所能推荐的范围不匹配
            return $this->json(1001);
        }
        
        $this->params['customerid'] = $this->userid;
        $this->params['version'] = $this->getVersion();
        $flowOBJ = new FlowModel();
        $result = $flowOBJ->flowRecoCusPublic($this->params);
        return $this->json($result["code"], $result['data']);
    }
    
    public function flowrecocuscashpublicAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType'])) {
            return $this->json(404);
        }
        // 确保role值在正确范围内
        $roleOBJ = new RoleModel();
        if(!$roleOBJ->roleRange(["role"=>$this->params['selfRoleType']])) {
            return $this->json(20102);
        }
        
        $roleRecoOBJ = new RoleRecoModel();
        if($this->Version("1.0.3")) {
            $recolist = $roleRecoOBJ->getNewRoleRecoType($this->params);
        } else {
            $recolist = $roleRecoOBJ->getRoleRecoType($this->params);
        }
        
        if($this->params['selfRoleType'] != 6 && $this->params['selfRoleType'] != 7) {
            if(empty($recolist)) {
                // 用户角色错误
                return $this->json(1001);
            }
            if(!in_array($this->params['recoRoleType'], $recolist)) {
                // 推荐角色和用户角色所能推荐的范围不匹配
                return $this->json(1001);
            }
        }
        $this->params['version'] = $this->getVersion();
        $this->params['parentid'] = $this->userid;
        $flowOBJ = new FlowModel();
        $result = $flowOBJ->flowRecoCusCashPublic($this->params);
        return $this->json($result["code"], $result['data']);
    }
    
    /**
    * @user 交易流水奖励
    * @param 
    * @author jeeluo
    * @date 2017年7月1日上午11:08:45
    */
    public function getstoflowAction() {
        if(empty($this->params['type']) || empty($this->params['selfRoleType'])) {
            return $this->json(404);
        }
        if(!empty($this->params['customerid'])) {
            // 识别该用户是否是你的子店
            $stoStoreInfo = Model::ins("StoStore")->getRow(["customerid"=>$this->params['customerid'],"parentid"=>$this->userid],"id");
            
            $stoRelation = Model::ins("CusRelation")->getRow(["customerid"=>$this->params['customerid'],"parentid"=>$this->userid],"id");

            if(empty($stoStoreInfo) && empty($stoRelation)) {
                return $this->json(1001);
            }
        } else {
            $this->params['customerid'] = $this->userid;
        }
        $stoflowtype = !empty($this->params['stoflowtype']) ? $this->params['stoflowtype'] : 1;
        $this->params['version'] = $this->getVersion();
        $this->params['isAndroid'] = $this->Version($this->params['version'], "A");
        if($stoflowtype == 1) {
            $this->params['flowtype'] = ["in", CommonModel::getStoFlowProfitType($this->params['selfRoleType'])];
        } else if($stoflowtype == 2) {
            $this->params['flowtype'] = ["in", CommonModel::getStoShareProfitType($this->params['selfRoleType'])];
        }
        $flowOBJ = new FlowModel();
        
        $result = $flowOBJ->getStoFlow($this->params);
        return $this->json($result['code'],$result['data']);
    }
    
    /**
    * @user 消费分享奖励
    * @param 
    * @author jeeluo
    * @date 2017年7月1日上午11:34:35
    */
    public function getstoflowshareAction() {
//         if(!empty($this->params['storeid'])) {
//             // 识别该用户是否是你的子店
//             $stoStoreInfo = Model::ins("StoStore")->getRow(["id"=>$this->params['storeid'],"parentid"=>$this->userid],"id,customerid");
//             if(empty($stoStoreInfo)) {
//                 return $this->json(1001);
//             } else {
//                 $this->params['customerid'] = $stoStoreInfo['customerid'];
//             }
//         } else {
//             $this->params['customerid'] = $this->userid;
//         }
        
        if(!empty($this->params['customerid'])) {
            // 识别该用户是否是你的子店
            $stoStoreInfo = Model::ins("StoStore")->getRow(["customerid"=>$this->params['customerid'],"parentid"=>$this->userid],"id");
        
            $stoRelation = Model::ins("CusRelation")->getRow(["customerid"=>$this->params['customerid'],"parentid"=>$this->userid],"id");
        
            if(empty($stoStoreInfo) && empty($stoRelation)) {
                return $this->json(1001);
            }
        } else {
            $this->params['customerid'] = $this->userid;
        }
        $this->params['type'] = 1;
        $this->params['version'] = $this->getVersion();
        $this->params['isAndroid'] = $this->Version($this->params['version'], "A");
        $this->params['flowtype'] = ["in", CommonModel::getStoShareProfitType(-1)];
        $flowOBJ = new FlowModel();
        $result = $flowOBJ->getStoFlow($this->params);
        return $this->json($result['code'],$result['data']);
    }
    
    /**
    * @user 本地牛店
    * @param 
    * @author jeeluo
    * @date 2017年7月5日下午7:37:57
    */
    public function localStoAction() {
        if(empty($this->params['role'])) {
            return $this->json(404);
        }
        // 确保role值在正确范围内
        $roleOBJ = new RoleModel();
        if(!$roleOBJ->roleRange(array("role" => $this->params['role']))) {
            return $this->json(20102);
        }
        
        if($this->params['role'] != 6 && $this->params['role'] != 7) {
            return $this->json(1001);
        }
        
        $this->params['customerid'] = $this->userid;
        $this->params['version'] = $this->getVersion();
        $roleOBJ = new RoleModel();
        $result = $roleOBJ->localStoAmount($this->params);
        return $this->json($result["code"], $result['data']);
    }
    
    /**
    * @user 牛店粉丝数据
    * @param 
    * @author jeeluo
    * @date 2017年7月5日下午8:00:32
    */
    public function localStoFansAction() {
        if(empty($this->params['customerid']) || empty($this->params['role'])) {
            return $this->json(404);
        }
        
        // 确保role值在正确范围内
        $roleOBJ = new RoleModel();
        if(!$roleOBJ->roleRange(array("role" => $this->params['role']))) {
            return $this->json(20102);
        }
        
        if($this->params['role'] != 6 && $this->params['role'] != 7) {
            return $this->json(1001);
        }
        
        // 查询是否实体店
        $sto = Model::ins("StoBusiness")->getRow(["customerid"=>$this->params['customerid']],"id");
        if(empty($sto)) {
            return $this->json(8011);
        }
        
        // 实体店信息
        $sto_info = Model::ins("StoBusinessInfo")->getRow(["id"=>$sto['id']],"area_code");
        
        if(empty($sto_info['area_code'])) {
            return $this->json(8011);
        }
        
        // 代理信息
        if($this->params['role'] == 6) {
            $agent = Model::ins("CusCustomerAgent")->getRow(["customerid"=>$this->userid, "agent_type"=>2,"enable"=>1],"join_code");
            $agent_arr = array_filter(explode(",", $agent['join_code']));
            if(!in_array($sto_info['area_code'],$agent_arr)) {
                return $this->json(1001);
            }
        } else {
            $agent = Model::ins("CusCustomerAgent")->getRow(["customerid"=>$this->userid, "agent_type"=>1,"enable"=>1], "join_code");
            $agent_arr = array_filter(explode(",", $agent['join_code']));
            $codeStr = '';
            foreach ($agent_arr as $agent) {
                $childArea = CommonModel::getChildArea($agent);
                foreach ($childArea as $area) {
                    $codeStr .= $area['id'].",";
                }
            }
            $code_arr = array_filter(explode(",", $codeStr));
            if(!in_array($sto_info['area_code'],$code_arr)) {
                return $this->json(1001);
            }
        }
        
        $this->params['userid'] = $this->userid;
        $roleOBJ = new RoleModel();
        $result = $roleOBJ->localStoFansAmount($this->params);
        return $this->json($result["code"], $result['data']);
    }
}