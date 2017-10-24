<?php
namespace app\sale\controller\User;

use app\sale\ActionController;
use app\lib\Model;
use think\Config;
use think\Cookie;
use app\model\Sys\CommonModel;

class IndexController extends ActionController {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function indexAction() {
        
//         echo md5(uniqid(rand(),TRUE));
//         exit;
        $role = Model::new("User.User")->getUserRoleID(["customerid"=>$this->userid]);
        
        $title = "个人中心";
        
        $viewData = [
            'title' => $title,
            'customerid' => $this->userid,
            'role' => $role
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 返回首页数据
    * @param 
    * @author jeeluo
    * @date 2017年10月10日下午2:54:54
    */
    public function getIndexDataAction() {
        $customerid = $this->params['customerid'];
        
        // 用户基本信息
        $UserInfoModel = Model::new("User.UserInfo");
        $userInfo = $UserInfoModel->userBaseInfo(["customerid"=>$customerid]);
        
        if($userInfo["code"] != "200") {
            return $this->json($userInfo["code"]);
        }
        
        $role = $userInfo['data']['role'];
        
        // 用户余额
        $AmoAmountModel = Model::new("Amount.AmoAmount");
        $userAmount = $AmoAmountModel->getAmount(["customerid"=>$customerid]);
        
//         // 返回用户消费流水统计
//         $conFlowAmount = $AmoAmountModel->getConFlowAmount(["customerid"=>$customerid]);
//         // 返回用户现金流水统计
//         $cashFlowAmount = $AmoAmountModel->getCashFlowAmount(["customerid"=>$customerid, "role"=>$role]);
//         // 返回商家业绩流水统计
//         $busFlowAmount = $AmoAmountModel->getBusFlowAmount(["customerid"=>$customerid]);

        // 获取消费余额
        $yestoday = date("Y-m-d", strtotime("-1 day"));
        $incomeConAmount = $AmoAmountModel->getConTypeAmount(["customerid"=>$customerid,"direction"=>1,"begintime"=>$yestoday]);
        $expendConAmount = $AmoAmountModel->getConTypeAmount(["customerid"=>$customerid,"direction"=>2,"begintime"=>$yestoday]);
        
        // 返回商家业绩流水统计
        $busFlowAmount = $AmoAmountModel->getBusFlowAmount(["customerid"=>$customerid]);
        
        // 获取中部关联数据
        $childRelation = Model::new("User.UserRelation")->getChildRelation(["customerid"=>$customerid]);
        
        if($childRelation["code"] != "200") {
            return $this->json($childRelation["code"]);
        }
        
        // 指数
//         $profit = Config::get("Profit");
        $bonus = Model::new("User.UserBonus")->getUserBonus();

        // 公司电话
        $companyMobile = CommonModel::getCompanyPhone();
        
        // 获取中部关联数据
//         $childRelation = Model::new("User.UserRelation")->getChildRelation(["customerid"=>$customerid,"role"=>$role]);
//         if($childRelation["code"] != "200") {
//             return $this->json($childRelation["code"]);
//         }
//         // 获取分享点亮状态
//         $result['shareStatus'] = 0;
//         if($role == 1) {
//             // 识别是否已经购买钻石
//             $shareStatus = Model::new("Conn.Order")->checkUserOrder(["customerid"=>$customerid]);
//             if($shareStatus["code"] != "200") {
//                 return $this->json($shareStatus["code"]);
//             }
            
//             $result['shareStatus'] = $shareStatus["data"] > 0 ? 1 : 0;
//         } else {
//             $result['shareStatus'] = 1;
//         }
        
        $result['userInfo'] = $userInfo['data'];
        $result['userAmount'] = $userAmount['data'];
        $result['incomeConAmount'] = $incomeConAmount['data'];
        $result['expendConAmount'] = $expendConAmount['data'];
//         $result['conFlowAmount'] = $conFlowAmount['data'];
//         $result['cashFlowAmount'] = $cashFlowAmount['data'];
        $result['busFlowAmount'] = $busFlowAmount['data'];
        $result['childRelation'] = $childRelation["data"];
        $result['bonus'] = ForMatPrice($bonus*100);
        $result['companyMobile'] = $companyMobile[0];
        
        return $this->json("200", $result);
    }
    
    /**
    * @user 设置页面
    * @param 
    * @author jeeluo
    * @date 2017年10月19日上午11:52:06
    */
    public function configAction() {
        $title = "设置";
        
        $viewData = [
            'title' => $title,
            // 'customerid'=>$this->userid
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 退出登录操作
    * @param 
    * @author jeeluo
    * @date 2017年10月19日下午2:12:37
    */
    public function loginoutAction() {
        // $customerid = $this->params['customerid'];
        // 删除缓存
        Cookie::set('customerid',"",time());
        Cookie::set('mtoken',"",time());

        $result["code"] = "200";
        return json_encode($result);
    }
    
    /**
    * @user 
    * @param 
    * @author jeeluo
    * @date 2017年10月12日下午3:09:29
    */
    public function pushAction() {
        $title = "分享二维码";
        
        $viewData = [
            'title' => $title,
            'customerid' => $this->userid
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 生成图片
    * @param 
    * @author jeeluo
    * @date 2017年10月12日下午4:29:53
    */
    public function mycodeAction() {
        $param['recommendid'] = $this->params['recommendid'];
        $param['checkcode'] = $this->params['checkcode'];
        
        Model::new("User.User")->myCode($param);
    }
    
    /**
    * @user 获取二维码地址
    * @param 
    * @author jeeluo
    * @date 2017年10月12日下午4:40:44
    */
    public function getMyCodeAction() {
        $recommendid = $this->params['recommendid'];
        $checkcode = md5($recommendid.getConfigKey());
        $result = Model::new("User.User")->pushUrl(["recommendid"=>$recommendid,"checkcode"=>$checkcode]);
        
        if($result["code"] != "200") {
            return $this->json($result["code"]);
        }
        
        return $this->json($result["code"],$result["data"]);
    }
}