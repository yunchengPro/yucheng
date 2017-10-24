<?php
namespace app\api\controller\User;

use app\api\ActionController;
use app\model\User\RoleModel;
use app\model\User\RoleRecoModel;
use think\Config;
use app\lib\Sms;
use app\model\Sys\CommonModel;
use app\model\OrdOrderModel;
use app\model\User\UserModel;
use app\model\Order\OrderModel;
use app\lib\Model;
use app\model\Sys\CommonRoleModel;
use app\model\StoBusiness\StobusinessModel;
use app\lib\Img;
use app\model\Sys\CategoryModel;

class RoleController extends ActionController {
    const defaultRole = 1;
    // 牛人
    const bullPeoRole = 2;
    // 牛创客
    const bullenRole = 3;
    
    const stoRole = 5;
    
    const initNum = 0;
    
    const minute = 5;
    
    const minuteToSecond = 60;
    
    const minRand = 100000;
    
    const maxRand = 999999;
    
    const sendType = "reco_";
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 申请角色列表
    * @param 
    * @author jeeluo
    * @date 2017年3月11日下午3:30:56
    */
    public function applyrolelistAction() {
        $params['customerid'] = $this->userid;
        
        $roleOBJ = new RoleModel();
        
        // 列表
        $applyList = $roleOBJ->applyRoleList($params);
        
        return $this->json(200, $applyList);
    }
    
    /**
    * @user 申请页面
    * @param role
    * @author jeeluo
    * @date 2017年3月11日下午4:26:46
    */
    public function applyinfoAction() {
        if(empty($this->params['role'])) {
            return $this->json(404);
        }
        $this->params['customerid'] = $this->userid;
        
        $roleOBJ = new RoleModel();
        $rangeStatus = $roleOBJ->roleRange($this->params);
        // 合理范围内
        if($rangeStatus) {
            
            // 识别当前角色是否有此角色
            $cusRoleData = Model::ins("CusRole")->getRow(["customerid"=>$this->userid,"role"=>$this->params['role'],"enable"=>1],"id");
            if(!empty($cusRoleData)) {
//                 return $this->json(200, ["isrole"=>1]); // 有角色 跳转提示页
                return $this->json(20001);
            }
            // 获取用户的基本信息
            $this->params['version'] = $this->getVersion();
            $baseInfo = $roleOBJ->bullBaseInfo($this->params);
            
            if($this->Version("1.0.2")) {
                $baseInfo['goodsList'] = array();
            } else {
                if($this->Version("1.0.1")) {
                    // 获取商品信息
                    $baseInfo['goodsList'] = CommonRoleModel::getPresentList($this->params['role']);
                }
            }
            
            
            // 获取分享人
            if($this->Version("2.1.0")) {
                // 假如传递了引荐人用户id值
                if(!empty($this->params['parentid'])) {
                    $instroducerInfo = Model::ins("CusCustomer")->getRow(["id"=>$this->params['parentid']],"mobile");
                    $baseInfo['instroducerMobile'] = !empty($instroducerInfo['mobile']) ? $instroducerInfo['mobile'] : '';
                }
                
                // 为空时 获取牛粉引荐
                if($baseInfo['instroducerMobile'] == '') {
                    // 假如分享人手机号码为空时  获取用户牛粉身份的引荐人
                    $fansRelation = Model::ins("CusRelation")->getRow(["customerid"=>$this->userid,"role"=>self::defaultRole],"parentid");
                    if($fansRelation['parentid'] > -1) {
                        // 获取引荐人手机号码
                        $instroducerInfo = Model::ins("CusCustomer")->getRow(["id"=>$fansRelation['parentid']],"mobile");
                        $baseInfo['instroducerMobile'] = !empty($instroducerInfo['mobile']) ? $instroducerInfo['mobile'] : '';
                    }
                    $baseInfo['isrole'] = -1;
                }
                // 查询是否有修改权限
                $baseInfo['roleupdateauth'] = CommonRoleModel::getShareRoleMobile(["applyRole"=>$this->params['role'],"instoducerMobile"=>$baseInfo['instroducerMobile']]) ? 0 : 1;
                if($baseInfo['roleupdateauth'] == 1) {
                    if($this->params['role'] == 2) {
                        $baseInfo['errormsg'] = '您的分享人不具备创客身份，您可以填写任意一位创客的手机号或者不填';
                    } else if($this->params['role'] == 3) {
                        $baseInfo['errormsg'] = '您的分享人不具备牛创客身份，您可以填写任意一位牛创客的手机号或者不填';
                    } else if($this->params['role'] == 8) {
                        $baseInfo['errormsg'] = '您的分享人不具备牛达人身份，您可以填写任意一位牛达人的手机号或者不填';
                    }
                } else {
                    $baseInfo['errormsg'] = '';
                }
            } else {
                if($this->Version("1.0.4")) {
                    // 查看是否传递了引荐用户id值
                    if(!empty($this->params['parentid'])) {
                        $instroducerInfo = Model::ins("CusCustomer")->getRow(["id"=>$this->params['parentid']],"mobile");
                        $baseInfo['instroducerMobile'] = !empty($instroducerInfo['mobile']) ? $instroducerInfo['mobile'] : '';
                    }
                    $baseInfo['isrole'] = -1;
                }
            }
            
            return $this->json(200, $baseInfo);
        }
        return $this->json(1001);
    }
    
    /**
    * @user 发送申请操作(支付前)
    * @param 
    * @author jeeluo
    * @date 2017年3月13日下午2:12:04
    */
    public function beforesendapplyAction() {
        if(empty($this->params['area']) || empty($this->params['area_code']) || empty($this->params['address']) || empty($this->params['role_type'])
            || empty($this->params['realname']) || empty($this->params['idnumber']) || empty($this->params['mobile'])) {
            return $this->json(404);
        }
        
//         if($this->Version("1.0.1") || $this->Version("1.0.2")) {
//             if(empty($this->params['productid']) || empty($this->params['logisticsName']) || empty($this->params['logisticsMobile']) || empty($this->params['logisticsArea']) 
//                 || empty($this->params['logisticsAreaCode']) || empty($this->params['logisticsAddress'])) {
//                 return $this->json(404);
//             }
//         }
        
        $this->params['customerid'] = $this->userid;
        $roleOBJ = new RoleModel();
        $this->params['version'] = $this->getVersion();
        $isAndroid = $this->Version($this->params['version'], "A");
        
        if($this->Version("2.1.0")) {
            // 不检测分享人手机号码
        } else {
            if($this->Version("2.0.0")) {
                if($isAndroid) {
                    if(empty($this->params['introducermobile'])) {
                        return $this->json(404);
                    }
                }
            } else {
                if(empty($this->params['introducermobile'])) {
                    return $this->json(404);
                }
            }
        }
        
        // 查看用户是否有对应角色
        $roleRecord = Model::ins("CusRole")->getRow(["customerid"=>$this->userid,"role"=>$this->params['role_type']], "id");
        if(!empty($roleRecord['id'])) {
            return $this->json(20001);
        }
        
        $this->params['isAndroid'] = $isAndroid;
        $result = $roleOBJ->beforeApplyExam($this->params);
        
        return $this->json($result['code'], $result['result']);
    }
    
    /**
    * @user 发送申请操作(支付后)
    * @param 
    * @author jeeluo
    * @date 2017年3月14日下午2:37:20
    */
    public function aftersendapplyAction() {
        if(empty($this->params['id'])) {
            return $this->json(404);
        }
        
        $roleOBJ = new RoleModel();
        $result = $roleOBJ->afterApplyExam($this->params);
        
        return $this->json($result['code']);
    }
    
    /**
    * @user 分享申请角色
    * @param 
    * @author jeeluo
    * @date 2017年7月10日下午6:11:25
    */
    public function applyShareAction() {
        if(empty($this->params['userid']) || empty($this->params['introRoleType'])) {
            return $this->json(404);
        }
        
        if(!$this->Version("2.1.0")) {
            // 查看引荐人是否有对应的角色
            $introRoleData = Model::ins("CusRole")->getRow(["customerid"=>$this->params['userid'],"role"=>$this->params['introRoleType'],"enable"=>1],"id");
            if(empty($introRoleData)) {
//                 return $this->json(1001);
                // 提示去升级
                return $this->json(9900);
            }
        }

        $data['type'] = 1;
        // 查看申请人是否已经有此用户(当是牛店分享牛粉时，查看的是关联关系表数据)
        $cusRoleData = array();
        if($this->params['introRoleType'] == self::stoRole) {
            $cusRelationData = Model::ins("CusRelation")->getRow(["customerid"=>$this->userid,"role"=>1,"parentrole"=>$this->params['introRoleType']],"id");

            if(!empty($cusRelationData['id'])) {
                // 已经绑定  返回错误提示页
                $data['type'] = 1;
            } else {
                // 获取平台号
                $codeInfo = Model::ins("StoBusinessCode")->getRow(["customerid"=>$this->params['userid'],"isuse"=>1],"business_code");
                if(empty($codeInfo['business_code'])) {
                    return $this->json(8010);
                }
                // 执行绑定操作
                $roleData = array("customerid"=>$this->userid,"userid"=>$this->params['userid'],"checkcode"=>$codeInfo['business_code'],"stocode"=>$codeInfo['business_code'],"roleid"=>5);
                Model::new("User.Role")->pushCusRelation($roleData);
                Model::new("Sys.Mq")->submit();
                CommonRoleModel::roleRelationRole($roleData);
                $data['type'] = 2;
            }
        } else {
            $cusRoleData = Model::ins("CusRole")->getRow(["customerid"=>$this->userid,"role"=>$this->params['introRoleType'],"enable"=>1],"id");
            
            if(!empty($cusRoleData['id'])) {
                $data['type'] = 1;
            } else {
                // 无此用户角色(返回当前用户信息 和 引荐人信息)
                $data['type'] = -1;
            }
            
            if($this->params['introRoleType'] == 2 || $this->params['introRoleType'] == 3 || $this->params['introRoleType'] == 8) {
                $relaData = array("customerid"=>$this->userid,"userid"=>$this->params['userid'],"checkcode"=>md5($this->params['userid'].getConfigKey()));
                Model::new("User.Role")->updateCusRelation($relaData);
                CommonRoleModel::roleRelationRole($roleData);
            }
        }
        return $this->json(200, $data);
    }
    
    /**
    * @user 我的推荐 请求操作验证
    * @param 
    * @author jeeluo
    * @date 2017年3月13日下午4:12:41
    */
    public function recommendinfoAction() {
        // 检测角色类型和被推荐的角色类型 是否存在推荐关系
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType'])) {
            return $this->json(404);
        }
        
        $roleRecoOBJ = new RoleRecoModel();
        if($this->Version("2.0.0")) {
            $recolist = $roleRecoOBJ->getSecondVersionRoleRecoType($this->params);
        } else if($this->Version("1.0.3")) {
            $recolist = $roleRecoOBJ->getNewRoleRecoType($this->params);
        } else {
            $recolist = $roleRecoOBJ->getRoleRecoType($this->params);
        }
        if(empty($recolist)) {
            // 用户角色错误
            return $this->json(1000);
        }
        if(!in_array($this->params['recoRoleType'], $recolist)) {
            // 推荐角色和用户角色所能推荐的范围不匹配
            return $this->json(1001);
        }
        $roleConfig = Config::get('role_money');
        
        $data['money'] = self::initNum;
        $data['selfRoleType'] = $this->params['selfRoleType'];
        $data['recoRoleType'] = $this->params['recoRoleType'];
        
        $cash_profit_config = Config::get("cash_profit");//返回比率
        
        if($data['selfRoleType'] == $roleConfig['bullPeoRole']) {
//             $data['money'] = DePrice($roleConfig['bullPeoMoney']);
            if($this->Version("2.0.0")) {
                $data['returnbull'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_parent_bull']));
                $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_parent_cash']));
                $data['returnprofit'] = "0";
                $data['returnbonus'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_parent_bonus']));
            } else if($this->Version("1.0.3")) {
                $data['returnbull'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_parent_bull']));
                $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_parent_cash']));
                $data['returnprofit'] = "0";
            } else {
                $data['returnbull'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_parent_bull']));
            }
            // if($this->Version("1.0.3")) {
            //     $data['returnbull'] = "0";
            //     $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_parent_cash']));
            //     $data['returnprofit'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_parent_profit']));
            // } else {
            //     $data['returnbull'] = SubstrPrice(DePrice($cash_profit_config['apply_nr_return']));
            // }
        } else if($data['selfRoleType'] == $roleConfig['bullenRole']) {
//             $data['money'] = DePrice($roleConfig['bullenMoney']);

            if($this->Version("2.0.0")) {
                if($data['recoRoleType'] == $roleConfig['bullPeoRole']) {
                    // 牛创客 推荐牛人
                    $data['returnbull'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_nc_bull']));
                    $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_nc_cash']));
                    $data['returnprofit'] = "0";
                    $data['returnbonus'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_nc_bonus']));
                } else {
                    // 牛创客 推荐牛创客
                    $data['returnbull'] = "0";
                    $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nc_parent_cash']));
                    $data['returnprofit'] = "0";
                    $data['returnbonus'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nc_parent_bonus']));
                }
            } else if($this->Version("1.0.3")) {
                $data['returnbull'] = "0";
                $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nc_parent_cash']));
                $data['returnprofit'] = "0";
            } else {
                $data['returnbull'] = "0";
            }
            // if($this->Version("1.0.3")) {
            //     $data['returnbull'] = "0";
            //     $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nc_parent_cash']));
            //     $data['returnprofit'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nc_parent_profit']));
            // } else {
            //     $data['returnbull'] = DePrice($cash_profit_config['apply_nc_return']);
            // }
        } else if($data['selfRoleType'] == $roleConfig['bullTalentRole']) {
//             $data['money'] = DePrice($roleConfig['bullTalentMoney']);

            if($this->Version("2.0.0")) {
                if($data['recoRoleType'] == $roleConfig['bullPeoRole']) {
                    // 牛达人 推荐牛人
                    $data['returnbull'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_nd_bull']));
                    $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_nd_cash']));
                    $data['returnprofit'] = "0";
                    $data['returnbonus'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nr_nd_bonus']));
                } else {
                    // 牛达人 推荐牛达人
                    $data['returnbull'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nd_parent_bull']));
                    $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nd_parent_cash']));
                    $data['returnprofit'] = "0";
                    $data['returnbonus'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nd_parent_bonus']));
                }
            } else if($this->Version("1.0.3")) {
                $data['returnbull'] = "0";
                $data['returncash'] = SubstrPrice(DePrice($cash_profit_config['role_reco_nd_parent_cash']));
                $data['returnprofit'] = "0";
            } else {
                return $this->json(1001);
            }
        }
        
        if($data['recoRoleType'] == $roleConfig['bullPeoRole']) {
            $data['money'] = DePrice($roleConfig['bullPeoMoney']);
        } else if($data['recoRoleType'] == $roleConfig['bullenRole']) {
            $data['money'] = DePrice($roleConfig['bullenMoney']);
        } else if($data['recoRoleType'] == $roleConfig['bullTalentRole']){
            $data['money'] = DePrice($roleConfig['bullTalentMoney']);
        }
        
        if($data['recoRoleType'] == self::stoRole) {
            // 判断用户是否是赠送的
            if(!CommonRoleModel::getUserRoleGive(["customerid"=>$this->userid,"role"=>$data['selfRoleType']])) {
                $data['returncash'] = "0";
            }
        }
        
        if($this->Version("1.0.1")) {
            $data['goodsList'] = CommonRoleModel::getPresentList($this->params['recoRoleType']);
            // $data['goodsList'] = array();
        }
        
        return $this->json(200, $data);
    }
    
    /**
    * @user 步骤推荐请求操作页面
    * @param 
    * @author jeeluo
    * @date 2017年6月23日下午4:26:23
    */
    public function recommendStepInfoAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType'])) {
            return $this->json(404);
        }
        $roleRecoOBJ = new RoleRecoModel();
        $recolist = $roleRecoOBJ->getNewRoleRecoType($this->params);
        if(empty($recolist)) {
            // 用户角色错误
            return $this->json(1000);
        }
        if(!in_array($this->params['recoRoleType'], $recolist)) {
            // 推荐角色和用户角色所能推荐的范围不匹配
            return $this->json(1001);
        }
        if($this->params['recoRoleType'] == 5) {
            // 根据用户cus_role_id 查询是否有数据步骤
            $data = Model::new("User.RoleReco")->getStepStoInfo(array("customerid"=>$this->userid,"selfRoleType"=>$this->params['selfRoleType'],"version"=>$this->getVersion()));
            
            return $this->json(200, $data);
        }
        return $this->json(1001);
    }
    
    /**
    * @user 重新分享
    * @param 
    * @author jeeluo
    * @date 2017年6月29日下午9:02:42
    */
    public function afreshShareAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType'])) {
            return $this->json(404);
        }
        $roleRecoOBJ = new RoleRecoModel();
        $recolist = $roleRecoOBJ->getNewRoleRecoType($this->params);
        if(empty($recolist)) {
            // 用户角色错误
            return $this->json(1000);
        }
        if(!in_array($this->params['recoRoleType'], $recolist)) {
            // 推荐角色和用户角色所能推荐的范围不匹配
            return $this->json(1001);
        }
        if($this->params['recoRoleType'] == 5) {
            // 根据用户cus_role_id 查询是否有数据步骤
            $cusRole = Model::ins("CusRole")->getRow(["customerid"=>$this->userid,"role"=>$this->params['selfRoleType']],"id");
            
            $step_sto = Model::ins("RoleRecoStepSto")->getRow(["cus_role_id"=>$cusRole['id'],"status"=>1],"id");
            
            if(!empty($step_sto)) {
                $updateData['status'] = 3;
                $updateData['updatetime'] = getFormatNow();
                Model::ins("RoleRecoStepSto")->modify($updateData, ["id"=>$step_sto['id']]);
            }
            $data = Model::new("User.RoleReco")->getStepStoInfo(array("customerid"=>$this->userid,"selfRoleType"=>$this->params['selfRoleType']));
            
            return $this->json(200,$data);
        }
        return $this->json(1001);
    }
    
    /**
    * @user 支付前的请求操作
    * @param 
    * @author jeeluo
    * @date 2017年3月14日下午4:34:04
    */
    public function beforesendrecoAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType'])) {
            return $this->json(404);
        }
        
        $roleRecoOBJ = new RoleRecoModel();
        if($this->Version("2.0.0")) {
            $recolist = $roleRecoOBJ->getSecondVersionRoleRecoType($this->params);
        } else {
            $recolist = $roleRecoOBJ->getNewRoleRecoType($this->params);
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
        
        $result = $roleRecoOBJ->recommendRole($this->params);

        return $this->json($result['code'], $result['result']);
    }
    
    public function sendrecostepAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType']) || empty($this->params['step']) || empty($this->params['submitAction'])) {
            return $this->json(404);
        }
        
        $roleRecoOBJ = new RoleRecoModel();
        $recolist = $roleRecoOBJ->getNewRoleRecoType($this->params);
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
        $result = $roleRecoOBJ->recostepRole($this->params);
        return $this->json($result['code'], $result['data']);
    }
    
    /**
    * @user 牛商推荐入口
    * @param 
    * @author jeeluo
    * @date 2017年3月15日下午5:35:41
    */
    public function sendrecobusAction() {
        // 根据推荐类型的不同 进行分发处理
        $roleConfig = Config::get('role_money');
        if($this->params['recoRoleType'] != $roleConfig['bullBusRole']) {
            return $this->json(404);
        }
        return self::beforesendrecoAction();
    }
    
    /**
    * @user 牛掌柜推荐入口
    * @param 
    * @author jeeluo
    * @date 2017年3月15日下午5:38:50
    */
    public function sendrecostoAction() {
        // 根据推荐类型的不同 进行分发处理
        $roleConfig = Config::get('role_money');
        if($this->params['recoRoleType'] != $roleConfig['bullStoRole']) {
            return $this->json(404);
        }
        return self::beforesendrecoAction();
    }
    
    /**
    * @user 分步分享牛掌柜
    * @param 
    * @author jeeluo
    * @date 2017年6月23日上午10:37:05
    */
    public function sendrecostepstoAction() {
        // 根据推荐类型的不同，进行分发处理
        $roleConfig = Config::get("role_money");
        if($this->params['recoRoleType'] != $roleConfig['bullStoRole']) {
            return $this->json(404);
        }
        return self::sendrecostepAction();
    }
    
    /**
    * @user 孵化中心推荐入口
    * @param 
    * @author jeeluo
    * @date 2017年3月16日上午10:02:13
    */
    public function sendrecocountyAction() {
        // 根据推荐类型的不同 进行分发处理
        $roleConfig = Config::get('role_money');
        if($this->params['recoRoleType'] != $roleConfig['bullCountyRole']) {
            return $this->json(404);
        }
        return self::beforesendrecoAction();
    }
    
    /**
    * @user 运营中心推荐入口
    * @param 
    * @author jeeluo
    * @date 2017年3月16日上午10:22:16
    */
    public function sendrecocityAction() {
        // 根据推荐类型的不同 进行分发处理
        $roleConfig = Config::get('role_money');
        if($this->params['recoRoleType'] != $roleConfig['bullCityRole']) {
            return $this->json(404);
        }
        return self::beforesendrecoAction();
    }
    
    /**
    * @user 代付的后续操作
    * @param 
    * @author jeeluo
    * @date 2017年3月15日上午9:21:32
    */
    public function aftersendrecoAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['recoRoleType']) || empty($this->params['reco_id'])) {
            return $this->json(404);
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
        
        $result = $roleRecoOBJ->recommendPayRole($this->params);
        
        return $this->json($result['code']);
    }
    
    /**
    * @user 发送验证码信息
    * @param 
    * @author jeeluo
    * @date 2017年3月15日上午11:15:02
    */
    public function sendvalidateAction() {
        $mobile = $this->params['mobile'];
        if(empty($mobile) || empty($this->params['privatekey'])) {
            return $this->json(404);
        }
        if(phone_filter($mobile)) {
            return $this->json(20006);
        }
        
        $privatekey = strtoupper($this->params['privatekey']);
        $autokey = strtoupper(md5($mobile.getConfigKey()));
        
        if($privatekey != $autokey) {
            return $this->json(400);
        }
        
        $randNumber = getRandNumber(self::minRand, self::maxRand);
        
        if(Sms::send("$mobile", ["$randNumber", self::minute])) {
            return $this->json(2001);
        } else {
            // 验证码发送成功
            $MessageRedis = Model::Redis("MessageValicode");
            $MessageRedis->set(self::sendType.$mobile, $randNumber, self::minute*self::minuteToSecond);
//             CommonModel::setCacheNumber(self::sendType.$mobile, $randNumber, self::minute * self::minuteToSecond);
        }
        return $this->json(200);
    }
    
    /**
    * @user 获取地铁信息
    * @param 
    * @author jeeluo
    * @date 2017年3月15日下午8:25:40
    */
    public function getsysinfoAction() {
        if(empty($this->params['area_code'])) {
            return $this->json(404);
        }
        
        // 处理市区和县区编号问题
        $area_code = CommonModel::updateCityCode($this->params['area_code']);
        
        $roleOBJ = new RoleRecoModel();
        $result['metro'] = $roleOBJ->getMetroInfo($area_code);
        
        $result['district'] = $roleOBJ->getDistrictInfo($area_code);
        
        return $this->json(200, $result);
    }
    
    /**
    * @user 商家角色订单列表
    * @param 
    * @author jeeluo
    * @date 2017年3月27日上午11:24:44
    * 2016-04-01 ISir@673638498@qq.com 修改 
    */
    public function orderlistAction() {
        $orderlisttype = $this->params['orderlisttype'];
        
        if(!in_array($orderlisttype, [1, 2, 3,4])) {  // 商家订单列表类型 1 未付款 2代发货 3 已完成  4 退款/售后
            return $this->json(404);
        }
        
        $userOBJ = new UserModel();

        $useInfo = $userOBJ->userInfo($this->userid);

        //用户信息不存在
        if(empty($useInfo))
            return $this->json(20103);

        $roleArr = $userOBJ->getRole($this->userid);
        
        if(!in_array(4, $roleArr))
            return $this->json(20102);

        $busId = $userOBJ->getUserBus(array("customerid" => $this->userid));
        if(!$busId) {
            return $this->json(1001);
        }
        
        $OrdOrderOBJ = new OrdOrderModel();
        $orderOBJ = new OrderModel();

        if( $orderlisttype > 0 &&  $orderlisttype < 4){
            $orderlist = $OrdOrderOBJ->getBusOrderList($busId,$orderlisttype);
        }else if($orderlisttype == 4){
            $orderlist = $orderOBJ->getReturnOderList(['business_id'=>$busId]);
        }else{
            return $this->json(404);
        }
        
        return $this->json("200",[
            "total"=>$orderlist['total'],
            "list"=>$orderlist['list'],
        ]);
    }
    
    /**
    * @user 审核失败 删除操作
    * @param 
    * @author jeeluo
    * @date 2017年3月30日下午2:12:25
    */
    public function examfaildelAction() {
        if(empty($this->params['recoRoleType']) || empty($this->params['mobile']) || empty($this->params['selfRoleType'])) {
            return $this->json(404);
        }
        // 确保role值在正确范围内
        $role_arr = array(4, 5);
        if(!in_array($this->params['recoRoleType'], $role_arr)) {
            return $this->json(1001);
        }
        $this->params['customerid'] = $this->userid;
        
        $roleRecoOBJ = new RoleRecoModel();
        $result = $roleRecoOBJ->recoExamDisabled($this->params);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 校验加盟地区是否已经存在
    * @param 
    * @author jeeluo
    * @date 2017年5月22日下午3:41:17
    */
    public function agentFindCodeAction() {
        if(empty($this->params["join_code"])) {
            return $this->json(404);
        }
        
        $agent_type = !empty($this->params['agent_type']) ? $this->params['agent_type'] : 0;
        
        if($agent_type == 1) {
            $this->params['join_code'] = CommonModel::updateCityCode($this->params['join_code']);
        }
        
        // 校验数据
        $result = CommonModel::isFindCode($this->params["join_code"]);
        $data['status'] = -1;
        if($result["code"] == "200") {
            $data['status'] = 1;
        }
        return $this->json(200,$data);
    }
    
    /**
    * @user 检测是否有分享的权限
    * @param 
    * @author jeeluo
    * @date 2017年9月4日下午3:37:28
    */
    public function checkrecoroleAction() {
        if(empty($this->params['selfRoleType'])) {
            return $this->json(404);
        }
        $params['selfRoleType'] = $this->params['selfRoleType'];
        $params['mobile'] = $this->params['mobile'];
        
        $data['roleupdateauth'] = 1;
        $data['errormsg'] = '';
        if(!empty($params['mobile'])) {
            $result = CommonRoleModel::getShareRoleMobile(["applyRole"=>$params['selfRoleType'],"instoducerMobile"=>$params['mobile']]);
            $data['roleupdateauth'] = $result ? 0 : 1;
        }
        
        if($data['roleupdateauth'] == 1) {
            if($params['selfRoleType'] == 2) {
                $data['errormsg'] = '您的分享人不具备创客身份，您可以填写任意一位创客的手机号或者不填';
            } else if($params['selfRoleType'] == 3) {
                $data['errormsg'] = '您的分享人不具备牛创客身份，您可以填写任意一位牛创客的手机号或者不填';
            } else if($params['selfRoleType'] == 8) {
                $data['errormsg'] = '您的分享人不具备牛达人身份，您可以填写任意一位牛达人的手机号或者不填';
            }
        }
        return $this->json(200,$data);
    }
}