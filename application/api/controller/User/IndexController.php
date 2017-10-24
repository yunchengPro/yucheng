<?php
namespace app\api\controller\User;

use app\api\ActionController;
use app\model\CusCustomerInfoModel;
use app\model\User\UserModel;
use app\model\OrdUserCountModel;
use app\model\CusRoleModel;
use app\model\CusRoleLogModel;
use app\model\User\RoleModel;
use app\model\User\RoleRecoModel;
use app\model\User\AmountModel;
use app\model\Sys\CommonModel;
use app\model\User\IndexModel;
use app\model\StoBusiness\StobusinessModel;
use think\Config;
use app\lib\Model;
use app\model\Order\OrderModel;
use app\model\Sys\CommonRoleModel;

class IndexController extends ActionController
{
    const defaultRole = 1;
    const orRole = 2;
    const enRole = 3;
    const busRole = 4;
    const stoRole = 5;
    const countyRole = 6;
    const cityRole = 7;
    const ndRole = 8;
    
    const initNum = 0;
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 个人首页
    * @param $mtoken 令牌
    * @author jeeluo
    * @date 2017年3月3日下午2:31:09
    */
    public function indexAction() {
        
        $data['role'] = !empty($this->params['role']) ? $this->params['role'] : self::defaultRole;
        // 确保role值在正确范围内
        $roleOBJ = new RoleModel();
        if(!$roleOBJ->roleRange($data)) {
            return $this->json(20102);
        }
        
        // 查看用户是否有此角色
        $cusRoleOBJ = new CusRoleModel();
        $userRoleList = $cusRoleOBJ->getInfoList(array("customerid" => $this->userid), "role");
        $rolelist = array();
        foreach ($userRoleList as $k => $user) {
            array_push($rolelist, $user['role']);
        }
        
        if(!in_array($data['role'], $rolelist)) {
            // 用户无此角色，没办法执行切换角色操作
            return $this->json(10003);
        }
        
        $cusRoleLogOBJ = new CusRoleLogModel();
        $logInfo = $cusRoleLogOBJ->getRow(array("customerid" => $this->userid));
        $logData = array("customerid" => $this->userid, "role" => $data['role'], "lasttime" => getFormatNow());
        if(!empty($logInfo)) {
            // 可以进行切换，记录数据
            $cusRoleLogOBJ->modify($logData, array("id" => $logInfo['id']));
        } else {
            $cusRoleLogOBJ->add($logData);
        }
        $data['version'] = $this->getVersion();
        // id值 返回简单基本信息
        $user = new UserModel();
        $userInfo['userinfo'] = $user->userInfo($this->userid);
        
        $userInfo['userinfo']['role'] = $data['role'];
        
        if($this->Version("1.0.1")) {
            $userInfo['userinfo']['businessid'] = 0;
            if($data['role'] == self::stoRole) {
                $stoInfo = Model::ins("StoBusiness")->getRow(array("customerid" => $this->userid), "id");
                if(!empty($stoInfo)) {
                    $userInfo['userinfo']['businessid'] = $stoInfo['id'];
                }
            }
        }
        $userInfo['userinfo']['rechargeStr'] = '';
        $userInfo['stocode'] = '';
        $data['customerid'] = $this->userid;
        
        if($this->Version("2.0.0")) {
            $userInfo['profitBounty'] = 0;
            $userInfo['orCommunity'] = 0;
            
            if($data['role'] == self::defaultRole) {
                // 查询牛粮奖励金
                $profitBounty = Model::ins("AmoBonus")->getRow(["id"=>$this->userid],"bonusamount");
                $userInfo['profitBounty'] = !empty($profitBounty) ? $profitBounty['bonusamount'] : 0;
            } else if($data['role'] == self::orRole || $data['role'] == self::enRole || $data['role'] == self::ndRole) {
                // 查询用户角色下 牛人的个数
                if($data['role'] == self::orRole)
                    $userInfo['orCommunity'] = Model::new("User.Relation")->getNRCommunityRelationCount($this->userid);
                else if($data['role'] == self::enRole)
                    $userInfo['orCommunity'] = Model::new("User.Relation")->getENCommunityRelationCount($this->userid);
                else
                    $userInfo['orCommunity'] = Model::new("User.Relation")->getNDCommunityRelationCount($this->userid);
            }
            
            $userInfo['profitBounty'] = !empty($userInfo['profitBounty']) ? DePrice($userInfo['profitBounty']) : '0.00';
        }
        
        // 用户余额数据
        $amountOBJ = new AmountModel();
        // 用户余额
        $userInfo['amountInfo'] = $amountOBJ->getUserAmount($data);
        
        if($this->Version("1.0.4")) {
            
            $userInfo['buskeepreco'] = $userInfo['stokeepreco'] = 1;
            
            $busCount = Model::new("User.UserAmount")->busUserCount(["userid"=>$this->userid,"role"=>$data['role']]);
            $stoCount = Model::new("User.UserAmount")->stoUserCount(["userid"=>$this->userid,"role"=>$data['role']]);
            
            if($data['role'] == self::orRole) {
                if($busCount >= CommonRoleModel::orRecoMaxBus()) {
                    $userInfo['buskeepreco'] = 0;
                }
                if($stoCount >= CommonRoleModel::orRecoMaxSto()) {
                    $userInfo['stokeepreco'] = 0;
                }
            } else if($data['role'] == self::ndRole) {
                if($busCount >= CommonRoleModel::ndRecoMaxBus()) {
                    $userInfo['buskeepreco'] = 0;
                }
                if($stoCount >= CommonRoleModel::ndRecoMaxSto()) {
                    $userInfo['stokeepreco'] = 0;
                }
            }
            
            $userInfo['futList']['cash'] = $amountOBJ->getFlowFutCash($data);
            $userInfo['futList']['profit'] = $amountOBJ->getFlowFutProfit($data);
            $userInfo['futList']['bull'] = $amountOBJ->getFlowFutBull($data);
            
            $orderOrderOBJ = new OrderModel();
            $userInfo['stoOrderCount'] = Model::new("StoBusiness.StoOrdOrder")->orderAllCountData($this->userid);
            $userInfo['busOrderCount'] = $orderOrderOBJ->orderAllCountData($this->userid);
            $userInfo['goodsCartCount'] = 0;
            
            // 牛店模块
            $userInfo['sto']['stoFlow'] = '0.00';
            $userInfo['sto']['stoFlowShare'] = '0.00';
            $userInfo['sto']['stoFlowCom'] = '0.00';
            $userInfo['sto']['storeCount'] = 0;
            if($data['role'] == self::stoRole) {
                $stoFlow = Model::new("User.UserAmount")->getStoFlowAmount($data);
                $userInfo['sto']['stoFlow'] = !empty($stoFlow) ? DePrice($stoFlow) : '0.00';
                $stoShare = Model::new("User.UserAmount")->getStoShareAmount($data);
                $userInfo['sto']['stoFlowShare'] = !empty($stoShare) ? DePrice($stoShare) : '0.00';
                $stoFlowCom = Model::new("User.UserAmount")->getStoFlowComAmount($data);
                $userInfo['sto']['stoFlowCom'] = !empty($stoFlowCom) ? DePrice($stoFlowCom) : '0.00';
                
                // 查询门店数量
                $userInfo['sto']['storeCount'] = Model::ins("StoStore")->getRow(["parentid"=>$this->userid],"count(*) as count")['count'];
                
                // 查询当前用户的实体店平台号
                $stoBaseInfo = Model::ins("StoBusinessBaseinfo")->getRow(["id"=>$userInfo['userinfo']['businessid']],"business_code");
                $userInfo['stocode'] = !empty($stoBaseInfo['business_code']) ? $stoBaseInfo['business_code'] : '';
            }
            
            // 牛商模块
            $userInfo['bus']['busCash'] = '0.00';
            $userInfo['bus']['futBusCash'] = '0.00';
            if($data['role'] == self::busRole) {
                $busCash = $amountOBJ->getFlowBusStoCash($data);
                $userInfo['bus']['busCash'] = $busCash;
                $futBusCash = $amountOBJ->getFlowFutBusCash($data);
                $userInfo['bus']['futBusCash'] = $futBusCash;
            }
            
            $userInfo['agent']['stoCount'] = 0;
            $userInfo['agent']['stoFlow'] = "0.00";
            $userInfo['agent']['stoFlowShare'] = "0.00";
            if($data['role'] == self::countyRole || $data['role'] == self::cityRole) {
                // 获取用户的个数
                if($data['role'] == self::countyRole) {
                    // 获取代理编号id
                    $agent = Model::ins("CusCustomerAgent")->getRow(["customerid"=>$this->userid,"agent_type"=>2],"id");
                    $userInfo['agent']['stoCount'] = Model::new("User.UserAmount")->fuhuaStoCount(array("id"=>$agent['id']));
                    $userInfo['agent']['stoFlow'] = Model::new("User.UserAmount")->stoUserAmount(["userid"=>$this->userid,"role"=>$data['role'],"flowtype"=>CommonModel::getStoFlowProfitType($data['role'])])['amount3'];
                    $userInfo['agent']['stoFlowShare'] = Model::new("User.UserAmount")->stoUserAmount(["userid"=>$this->userid,"role"=>$data['role'],"flowtype"=>CommonModel::getStoShareProfitType($data['role'])])['amount3'];
                } else {
                    // 获取代理编号id
                    $agent = Model::ins("CusCustomerAgent")->getRow(["customerid"=>$this->userid,"agent_type"=>1],"id");
                    $userInfo['agent']['stoCount'] = Model::new("User.UserAmount")->yunyingStoCount(array("id"=>$agent['id']));
                    $userInfo['agent']['stoFlow'] = Model::new("User.UserAmount")->stoUserAmount(["userid"=>$this->userid,"role"=>$data['role'],"flowtype"=>CommonModel::getStoFlowProfitType($data['role'])])['amount3'];
                    $userInfo['agent']['stoFlowShare'] = Model::new("User.UserAmount")->stoUserAmount(["userid"=>$this->userid,"role"=>$data['role'],"flowtype"=>CommonModel::getStoShareProfitType($data['role'])])['amount3'];
                }
                
                $userInfo['agent']['stoFlow'] = !empty($userInfo['agent']['stoFlow']) ? DePrice($userInfo['agent']['stoFlow']) : '0.00';
                $userInfo['agent']['stoFlowShare'] = !empty($userInfo['agent']['stoFlowShare']) ? DePrice($userInfo['agent']['stoFlowShare']) : '0.00';
            }
            
        } else {
            // 用户 收益
            $profit['starttime'] = CommonModel::getYesterdayTime();
            $profit['endtime'] = CommonModel::getTodayTime();
            $profit['customerid'] = $this->userid;
            $userInfo['profit'] = $amountOBJ->getUserProfit($profit);
            
            $orderOrderOBJ = new OrderModel();
            $userInfo['orderCount'] = $orderOrderOBJ->orderCountData($this->userid);
            if(empty($userInfo['orderCount'])) {
                $userInfo['orderCount']['count_pay'] = 0;
                $userInfo['orderCount']['count_deliver'] = 0;
                $userInfo['orderCount']['count_receipt'] = 0;
                $userInfo['orderCount']['count_evaluate'] = 0;
                $userInfo['orderCount']['count_cart'] = 0;
                $userInfo['orderCount']['count_return'] = 0;
            }
            
            $userInfo['busOrderCount']['unpaySum'] = self::initNum;
            $userInfo['busOrderCount']['unfilledSum'] = self::initNum;
            $userInfo['busOrderCount']['confirmSum'] = self::initNum;
            $userInfo['busOrderCount']['returnSum'] = self::initNum;
    //         假如当前角色为牛商时
            if($data['role'] == self::busRole) {
                // 查询商家订单数量
                $userBus = $user->getUserBus($data);
                if($userBus) {
                    $busOrderCount = $amountOBJ->getBusinessOrderCount(array("businessid" => $userBus));
                    $userInfo['busOrderCount']['unpaySum'] = $busOrderCount['data']['unpaySum'];
                    $userInfo['busOrderCount']['unfilledSum'] = $busOrderCount['data']['unfilledSum'];
                    $userInfo['busOrderCount']['confirmSum'] = $busOrderCount['data']['confirmSum'];
                    $userInfo['busOrderCount']['returnSum'] = $busOrderCount['data']['returnSum'];
                }
            }
        }
        // 角色推荐的收益(现金)
        $userInfo['recoCash'] = $amountOBJ->getRecoAllCash($data);
        
        $userInfo['applylist'] = $roleOBJ->applyRoleList($data)['result'];
        
        return $this->json(200, $userInfo);
    }
    
    /**
    * @user 点击推广操作
    * @param 
    * @author jeeluo
    * @date 2017年3月3日下午4:18:55
    */
    public function pushAction() {
        // 根据用户id 角色类型获取id
//         $cusRole = new CusRoleModel();
//         $cusRoleArr = $cusRole->setCustomerid($this->userid)->setRole(self::defaultRole)->getByCusRole();
        
//         $data['userid'] = $cusRoleArr['id']; //(后续修改)
//         $data['roleid']  = self::defaultRole;
        $data['userid'] = $this->userid;
        
        $user = new UserModel();
        $userInfo = $user->userInfo($this->userid);

        $roleid = CommonModel::getNowCusRole(array("customerid" => $this->userid));
        $data['checkcode'] = md5($this->userid.getConfigKey());
        
        $myCode = '';
        if($roleid == 5) {
            $myCode = StobusinessModel::getBusinessCode(array("customerid" => $this->userid))['data'];
            
            $data['stocode'] = $myCode;
            $data['role'] = $roleid;
            $data['type'] = 3;
        }else{
            $myCode = $userInfo['nickname'];
            
            if($this->Version('1.0.4')){
                if($roleid == 1 || $roleid == 2 || $roleid == 3 || $roleid == 8) {
                    $data['role'] = $roleid;
                    if($roleid == 1)
                        $data['type'] = 3;
                    else if($roleid == 2) 
                        $data['type'] = 4;
                    else if($roleid == 3) 
                        $data['type'] = 6;
                    else if($roleid == 8) 
                        $data['type'] = 5;
                    else 
                        $data['type'] = 0;
                }
            }
        }
        
        if($this->Version("2.1.0")) {
            $data['recole'] = '';
            // 获取被分享人角色
            $recoRole = $this->params['recorole'];
            if(!empty($recoRole)) {
                if($recoRole == 2 || $recoRole == 3 || $recoRole == 8) {
                    if($recoRole == 2) {
                        if($roleid != 2 && $roleid != 3 && $roleid != 8) {
                            return $this->json(1001);
                        }
                        $data['type'] = 4;
                    } else if($recoRole == 3){
                        if($roleid != 3) {
                            return $this->json(1001);
                        }
                        $data['type'] = 6;
                    } else if($recoRole == 8) {
                        if($roleid != 8) {
                            return $this->json(1001);
                        }
                        $data['type'] = 5;
                    }
                }
                $data['recole'] = $roleid;
                $data['role'] = $recoRole;
            }
        } else {
            $data['role'] = $roleid;
            $data['recole'] = $data['role'];
        }
        
        $userModel = new UserModel();
        $result = $userModel->pushUrl($data);
        $result['myCode'] = $myCode;
        
        if($this->Version("2.1.0")) {
            $result['userid'] = $this->userid;
            $result['recole'] = $roleid;
            $result['role'] = $data['role'];
        } else {
            if($this->Version('1.0.4')){
                if($roleid == 1 || $roleid == 2 || $roleid == 3 || $roleid == 5 || $roleid == 8) {
                    $result['userid'] = $this->userid;
                    $result['role'] = $roleid;
                    if($roleid == 1)
                        $data['type'] = 3;
                    else if($roleid == 2)
                        $result['type'] = 4;
                    else if($roleid == 3)
                        $result['type'] = 6;
                    else if($roleid == 5)
                        $result['type'] = 3;
                    else if($roleid == 8)
                        $result['type'] = 5;
                    else
                        $result['type'] = 0;
                }
            }
        }

//         $role = CommonModel::getNowCusRole(array("customerid" => $this->userid));
       
        //$mtoken = $this->params['mtoken'];
        $share = Config::get('shareparma');
        $code_url = $share['code_url'];//分享二维码地址
        
        $shareUrl = '';
        // if($this->Version("2.1.0")) {
            // 注意parentrole参数一定要放前面
            $shareUrl = $code_url.'&checkcode='.$data['checkcode'].'&userid='.$this->userid."&parentrole=".$result['parentrole'].'&role='.$result['role']."&stocode=".$data['stocode']."&type=".$data['type'];
        // } else {
        //     $shareUrl = $code_url.'&checkcode='.$data['checkcode'].'&userid='.$this->userid.'&role='.$result['role']."&stocode=".$data['stocode']."&type=".$data['type'];
        // }
        
        $result['share_content'] = [
            'title'=>"分享推广链接",
            'content'=>"欢迎加入我们平台，让我们来成就你的梦想",
            'image'=> $result['url'],
            'url' => $code_url.'&checkcode='.$data['checkcode'].'&userid='.$this->userid."&recole=".$result['recole'].'&role='.$result['role']."&stocode=".$data['stocode']."&type=".$data['type']
//             'url' => $shareUrl
        ];
        
        return $this->json(200, $result);
    }

    /**
    * @user 生成二维码
    * @param 
    * @author jeeluo
    * @date 2017年3月3日下午4:20:07
    */
    public function myCodeAction() {
        
        $codeParam['userid'] = $this->params['userid'];
        $codeParam['stocode'] = $this->params['stocode'];
        
//         if($codeParam['roleid'] == 5) {
//             // 判断实体店是否正常
//             $stoBusiness = Model::ins("StoBusiness")->getRow(array("customerid" => $codeParam['userid']), "enable");
            
//             if($stoBusiness['enable'] == -1) {
//                 // 禁用  执行什么操作(??)
//             }
//         }

        
        $codeParam['checkcode'] = $this->params['checkcode'];
        
        $version = $this->getVersion();
        $isios = $this->Version($version,"I");
        $isonline = $this->params['isonline'];
        
        $codeParam['type'] = !empty($this->params['type']) ? $this->params['type'] : 1;//二维码类型 1位推广二维码
        
        if(!empty($isios)) {
            if($version >= "2.0.0" && !empty($isonline)) {
                $codeParam['type'] = 1;
            }
        }
        if(!empty($this->params['recole'])) {
            $codeParam['recole'] = $this->params['recole'];
        }
        $codeParam['role'] = $this->params['role'];
//         $codeParam['userid'] = $this->params['userid'];
        $userModel = new UserModel();
        $userModel->myCode($codeParam);
        
    }
    
    /**
    * @user 评价管理列表
    * @param 
    * @author jeeluo
    * @date 2017年4月5日下午3:07:47
    */
    public function myevaluateAction() {
        if(empty($this->params['type'])) {
            return $this->json(404);
        }
        $params['type'] = $this->params['type'];
        $params['customerid'] = $this->userid;
        
        $IndexOBJ = new IndexModel();
        $result = $IndexOBJ->evaluateList($params);
        
        return $this->json($result['code'], $result["data"]);
    }
    
    /**
    * @user 删除评价操作
    * @param 
    * @author jeeluo
    * @date 2017年4月5日下午3:10:31
    */
    public function delevaluateAction() {
        if(empty($this->params['type']) || empty($this->params['evaluate_id'])) {
            return $this->json(404);
        }
        $params['type'] = $this->params['type'];
        $params['evaluate_id'] = $this->params['evaluate_id'];
        $params['customerid'] = $this->userid;
        
        $IndexOBJ = new IndexModel();
        $result = $IndexOBJ->delEvaluate($params);
        
        return $this->json($result['code']);
    }

    /**
     * [IntroductionAction 注册协议等h5协议链接]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-06T21:02:16+0800
     */
    public function IntroductionAction(){
        $data = Config::get('Introduction');
        $domain = Config::get('webview.webviewUrl');

        foreach ($data  as $key => $value) {
           $data[$key] = $domain.$value;
        }
        return $this->json(200,$data);
    }
}