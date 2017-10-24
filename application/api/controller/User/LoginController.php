<?php
namespace app\api\controller\User;

use app\api\ActionController;
use app\lib\Sms;
use app\api\model\User\LoginFactory;
use app\model\CusCustomerModel;
use app\model\CusCustomerInfoModel;
use app\model\CusCustomerLoginModel;
use app\model\CusMtokenModel;
use app\model\Sys\CommonModel;
use app\model\User\TokenModel;
use app\model\CusRoleModel;
use app\lib\Model;
use app\model\Sys\CommonRoleModel;

/**
 * @uses 登录/注册控制器
 * @author jeeluo
 *
 */
class LoginController extends ActionController {
    
    // 定义常量 分钟数
    const minute = 5;
    // 分钟和秒数比例
    const minuteToSecond = 60;
    
    // 随机数最小值
    const minRand = 100000;
    // 随机数最大值
    const maxRand = 999999;
    // 初始化数值
    const initNumber = 0;
    // 一天的秒数
    const oneDaySecond = 86400;
    // 一天最多使用次数
    // const oneMaxCount = 5;
    // 消费者角色
    const defaultRole = 1;
    // 注册手机操作类型前缀
    const sendType = "register_";
    // 公司角色类型
    const companyType = -1;
    
    const disenable = -1;
    
    protected $thirdPartyArr = ["weixin","qq","sina"];
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @uses 发送验证码操作
     * @author jeeluo
     * @date 2017年3月2日 17:14:28
     */
    public function SendAction() {
        
        $mobile = $this->params['mobile'];
        
        if(phone_filter($mobile)) {
            // 手机验证不通过
            return $this->json(20006);
        }
        
        $countNumber = self::initNumber;
        $MessageRedis = Model::Redis("MessageValicode");
        if($MessageRedis->exists(CommonModel::getDeviceProfix().$this->params['devicenumber'])) {
            $countNumber = $MessageRedis->get(CommonModel::getDeviceProfix().$this->params['devicenumber']);
        }
        
        if($this->Version("2.0.0")) {
            if(empty($this->params['devicenumber']) || empty($this->params['privatekey'])) {
                // 参数不完整
                return $this->json(404);
            }
            
            if($countNumber >= CommonModel::getMaxDevice()) {
                return $this->json(405);
            }
        } else {
            // 因为之前版本 安卓设备可能存在着获取不到设备号
            $isAndroid = $this->Version($this->params['version'], "A");
            if($isAndroid) {
                if(empty($this->params['privatekey'])) {
                    // 参数不完整
                    return $this->json(404);
                }
            } else {
                if(empty($this->params['devicenumber']) || empty($this->params['privatekey'])) {
                    // 参数不完整
                    return $this->json(404);
                }
                
                if($countNumber >= CommonModel::getMaxDevice()) {
                    return $this->json(405);
                }
            }
        }
        
        $privatekey = strtoupper($this->params['privatekey']);
        $autokey = strtoupper(md5($this->params['mobile'].getConfigKey()));
        
        if($privatekey != $autokey) {
            return $this->json(400);
        }
        
        // 获取cache内的次数信息
//         $countNumber = CommonModel::getCacheNumber(self::sendType.$this->params['devicenumber']);
        
//         if($countNumber >= CommonModel::getMaxDevice()) {
//             return $this->json(405);
//         }
        $randNumber = getRandNumber(self::minRand, self::maxRand);
        
        $result['valicode'] = 0;
        if(Sms::send("$mobile", ["$randNumber", self::minute])) {
            return $this->json(2001);
        } else {
            // 验证码发送成功
            // 设置valicode的cache
            
            $MessageRedis->set(self::sendType.$mobile, $randNumber, self::minute*self::minuteToSecond);
//             CommonModel::setCacheNumber(self::sendType.$mobile, $result['valicode'], self::minute * self::minuteToSecond);
            
            $MessageRedis->set(CommonModel::getDeviceProfix().$this->params['devicenumber'], ++$countNumber, strtotime(date('Y-m-d', time()+self::oneDaySecond))-time());
//             // 设置手机今天发送验证的次数
//             CommonModel::setCacheNumber(self::sendType.$this->params['devicenumber'], ++$countNumber, strtotime(date('Y-m-d', time()+self::oneDaySecond)) - time());
        }
        return $this->json(200);
    }
    
    /**
    * @user 登录操作(包含注册操作)
    * @author jeeluo
    * @date 2017年3月2日下午5:14:34
    */
    public function loginAction() {
        if(empty($this->params['mobile'])) {
            return $this->json(404);
        }
        
        if(phone_filter($this->params['mobile'])) {
            // 手机验证不通过
            return $this->json(20006);
        }
        
        $params['version'] = $this->getVersion();
        $params['stocode'] = $this->params['stocode'];
        $params['parentid'] = $this->params['parentid'];
        $params['checkcode'] = $this->params['checkcode'];
        $params['valicode'] = $this->params['valicode'];
        $params['mobile'] = $this->params['mobile'];
        $params['devicetoken'] = $this->params['devicetoken'];
        $params['devtype'] = $this->params['devtype'];
        
        $params['logintype'] = $this->params['logintype'];
        $params['openid'] = $this->params['openid'];
        $params['isios'] = $this->Version($params['version'],"I");
        $params['isonline'] = $this->params['isonline'];
        if(!empty($params['logintype']) || !empty($params['openid'])) {
            if(empty($params['logintype']) || empty($params['openid'])) {
                return $this->json(404);
            }
            if(!in_array($params['logintype'], $this->thirdPartyArr)) {
                return $this->json(1004);
            }
            
            // openid 在open表是否有数据
            $customerOpen = Model::ins("CusCustomerOpen")->getRow(["openid"=>$params['openid']],"id");
            if(empty($customerOpen['id'])) {
                return $this->json(404);
            }
        }
         
        $result = Model::new("User.Login")->login($params);
        
        $result['data']['rechargeStr'] = "";
        
        return $this->json($result['code'],$result['data']);
        
//         // 假如填写了引荐人或者引荐人加密字符串
//         if($this->Version("1.0.4")) {
//             // 假如有输入平台号值 校验该平台号和输入的用户是否关联
//             if(!empty($this->params['stocode'])) {
                
//                 //v2.0.0 实体店付款绑定关系
//                 if($this->Version("2.0.0")) {
//                     $codeInfo = Model::ins("StoBusinessCode")->getRow(["business_code"=>$this->params['stocode'],"isuse"=>1],"id,customerid");
//                     if(!empty($codeInfo['id'])) {
//                         // 校验用户是否传递过来引荐人id值
//                         if(!empty($this->params['parentid'])) {
//                             if($codeInfo['customerid'] != $this->params['parentid']) {
//                                 return $this->json(1003);
//                             }
//                         }
//                     } else {
//                         return $this->json(8010);
//                     }
//                 } else {
//                     // 假如输入了平台号  就必须输入引荐人id值
//                     if(empty($this->params['parentid'])) {
//                         return $this->json(20015);
//                     }
//                     $codeInfo = Model::ins("StoBusinessCode")->getRow(["business_code"=>$this->params['stocode'],"isuse"=>1],"customerid");
                    
//                     if(!empty($codeInfo['customerid'])) {
//                         if($codeInfo['customerid'] != $this->params['parentid']) {
//                             return $this->json(1003);
//                         }
//                     } else {
//                         return $this->json(8010);
//                     }
//                 }
//             }
            
//             if(!empty($this->params['parentid']) || !empty($this->params['checkcode'])) {
//                 // 看是否能进行匹配
//                 if(md5($this->params['parentid'].getConfigKey()) != $this->params['checkcode']) {
//                     return $this->json(20015);
//                 }
//             }
//         }
        
//         $MessageRedis = Model::Redis("MessageValicode");
// //         if(!$MessageRedis->exists(self::sendType.$this->params['mobile'])) {
// //             return $this->json(20004);
// //         }
// //         $cacheNum = CommonModel::getCacheNumber(self::sendType.$this->params['mobile']);
        
//         // 手机验证码过期
// //         if($cacheNum == 0) {
// //             return $this->json(20004);
// //         }
        
//         $this->params['valicode'] = strtoupper($this->params['valicode']);
// //         验证手机号码 假如用户已经注册 顺便返回用户id
//         $cus = new CusCustomerModel();
//         $isUser = $cus->compare($this->params, self::sendType);
//         if($isUser) {
//             $now = date('Y-m-d H:i:s', time());
//             // 验证码正确
//             $loginActioin = LoginFactory::loginRegister($isUser);
//             if($loginActioin->login($this->params)) {
//                 $customerInfo = new CusCustomerInfoModel();
//                 $returnId = self::initNumber;
//                 $result['mtoken'] = self::initNumber;
//                 $result['mobile'] = $this->params['mobile'];
//                 $result['type'] = self::initNumber;
//                 if(is_array($isUser)) {
//                     // 登录模式(号码存在，令牌不相等)
                    
//                     // 获取用户信息
//                     $cusInfo = $cus->getRow(array("id" => $isUser['id']), "enable");
//                     if($cusInfo['enable'] == self::disenable) {
//                         return $this->json(1002);
//                     }
                    
//                     // 查询登录信息
//                     $infoNumArr = $customerInfo->setId($isUser['id'])->getLoginById();
//                     $info_arr = array("lastlogintime" => $now, "loginnum" => $infoNumArr['loginnum']+1, "id" => $isUser['id']);
//                     $customerInfo->modifyUpdate($info_arr, "id = ".$isUser['id']);

//                     $result['role'] = CommonModel::getNowCusRole(array("customerid" => $isUser['id']));
//                     if(!$this->Version("1.0.3")) {
//                         $result['role'] = $result['role'] == 8 ? 1 : $result['role'];
//                     }
                    
//                     // 暂时处理(之后删除)
//                     if($isUser['id'] != 1) {
//                         $mtoken = new CusMtokenModel();
                        
//                         $result['mtoken'] = TokenModel::buildToken($returnId);     
//                         $redisData = array("mtoken"=>$result['mtoken'],"userid"=>$isUser['id']);
//                         $mtokenInfo = $mtoken->getRow(array("id" => $isUser['id']));
//                         if(empty($mtokenInfo)) {
                            
//                             // 写入redis
//                             Model::new("User.User")->insertMtokenRedis($redisData);
                            
//                             $token_arr = array("id" => $isUser['id'],"mtoken" => $result['mtoken'], "devicetoken" => $this->params['devicetoken'], "devtype" => $this->params['devtype'],
//                                 "addtime" => $now);
//                             $mtoken->add($token_arr);
//                         } else {
                            
//                             $mtokenRedis = Model::Redis("UserMtoken");
//                             $redis_key = $mtokenInfo['mtoken'];
//                             if($mtokenRedis->exists($redis_key)) {
//                                 // 删除redis
//                                 $mtokenRedis->del($redis_key);
//                             }
                            
//                             Model::new("User.User")->insertMtokenRedis($redisData);
                            
//                             // 修改用户登录令牌表
//                             $token_arr = array("mtoken" => $result['mtoken'], "devicetoken" => $this->params['devicetoken'], "devtype" => $this->params['devtype'],
//                                 "addtime" => $now);
//                             $mtoken->modifyUpdate($token_arr, "id = ".$isUser['id']);
//                         }
//                     } else {
//                         $result['mtoken'] = '1234567890000000000';
                        
//                         $redisData = array("mtoken"=>$result['mtoken'],"userid"=>$isUser['id']);
                        
//                         Model::new("User.User")->getMtokenRedis($redisData);
//                     }
                    
//                     if($this->Version("1.0.4")) {
//                         if(!empty($this->params['parentid'])) {
//                             // 引荐人存在  (可能平台号不一定输入)
//                             $stocode = '';
//                             $roleid = -1;
//                             if(!empty($this->params['stocode'])) {
                                
//                                 // 识别用户是否已经绑定实体店了
//                                 $cusRelationSto = Model::ins("CusRelation")->getRow(["customerid"=>$isUser['id'],"role"=>1,"parentrole"=>5],"id");
                                
//                                 if(!empty($cusRelationSto['id'])) {
//                                     $result['type'] = 1;
//                                 } else {
//                                     $stocode = !empty($this->params['stocode']) ? $this->params['stocode'] : '';
//                                     $roleid = 5;
                                    
//                                     $roleData = array("customerid"=>$isUser['id'],"userid"=>$this->params['parentid'],"checkcode"=>$this->params['checkcode'],"stocode"=>$stocode,"roleid"=>$roleid);
//                                     //
//                                     Model::new("User.Role")->pushCusRelation($roleData);
                                    
//                                     CommonRoleModel::roleRelationRole($roleData);
//                                     $result['type'] = 2;
//                                 }
//                             }
//                         }
//                     }
                    
//                     $returnId = $isUser['id'];
//                 } else {
//                     // 注册模式(号码不存在，令牌不存在)
//                     // 写入用户表
//                     $cus_arr = array("mobile" => $this->params['mobile'], "username" => $this->params['mobile'], "createtime" => $now);
//                     $returnId = $cus->add($cus_arr);
//                     // 写入用户详情表
//                     $cus_login_arr = array("id" => $returnId, "nickname" => "匿名", "lastlogintime" => $now, "loginnum" => self::initNumber);
//                     $customerInfo->add($cus_login_arr);
                    
//                     $parentid = $grandpaid = $parentrole = $grandparole = self::companyType;
//                     $roleData = array();
//                     if($this->Version("1.0.4")) {
//                         if(!empty($this->params['parentid'])) {
//                             // 引荐人存在  (可能平台号不一定输入)
//                             $stocode = '';
//                             $roleid = -1;
//                             if(!empty($this->params['stocode'])) {
//                                 $stocode = !empty($this->params['stocode']) ? $this->params['stocode'] : '';
//                                 $roleid = 5;
                                
//                                 $result['type'] = 2;
//                             }
                            
//                             $roleData = array("customerid"=>$returnId,"userid"=>$this->params['parentid'],"checkcode"=>$this->params['checkcode'],"stocode"=>$stocode,"roleid"=>$roleid);
                            
//                             // 
//                             Model::new("User.Role")->pushCusRelation($roleData);
//                         } else {
//                             // 无传递引荐人
//                             $stocode = '';
//                             $roleid = -1;
//                             $parentid = -1;
//                             $checkcode = '';
                            
//                             if(!empty($this->params['stocode'])) {
//                                 $stocode = !empty($this->params['stocode']) ? $this->params['stocode'] : '';
//                                 $roleid = 5;
                                
//                                 // 根据平台号，查询用户id值
//                                 $codeInfo = Model::ins("StoBusinessCode")->getRow(["business_code"=>$this->params['stocode'],"isuse"=>1],"customerid");
                                
//                                 $parentid = !empty($codeInfo['customerid']) ? $codeInfo['customerid'] : $parentid;
                                
//                                 $checkcode = $parentid != -1 ? md5($parentid.getConfigKey()) : $checkcode;
                                
//                                 // 因为是去付款页面，所以不能有提示页面
//                                 $result['type'] = 0;
//                             }
                            
//                             $roleData = array("customerid"=>$returnId,"userid"=>$parentid,"checkcode"=>$checkcode,"stocode"=>$stocode,"roleid"=>$roleid);

//                             Model::new("User.Role")->pushCusRelation($roleData);
//                         }
//                     } 
                    
//                     if(empty($roleData)) {
//                         // 写入消费者关系表
//                         $cus_relation_nf_arr = array("customerid" => $returnId, "parentid" => $parentid, "grandpaid" => $grandpaid, "addtime" => getFormatNow());
//                         $cus_relation_arr = array("customerid" => $returnId, "parentid" => $parentid, "grandpaid" => $grandpaid, "parentrole" => $parentrole, "grandparole" => $grandparole, "addtime" => getFormatNow(), "role" => self::defaultRole);
                        
//                         Model::ins("CusRelationNf")->insert($cus_relation_nf_arr);
//                         Model::ins("CusRelation")->insert($cus_relation_arr);
                        
//                         Model::new("Amount.Role")->share_role(["userid"=>$parentid,"customerid"=>$returnId]);
//                     }
//                     // 写入用户关系表
//                     $cus_role_arr = array("customerid" => $returnId, "role" => self::defaultRole);
//                     $cus_role = new CusRoleModel();
//                     $cus_role->add($cus_role_arr);
                    
//                     if(empty($roleData)) {
//                         $roleData = array("userid" => -1, "roleid" => self::defaultRole, "customerid" => $returnId);
//                     }
//                     $role_status = CommonRoleModel::roleRelationRole($roleData);
//                     if($role_status["code"] != 200) {
//                         return $this->json($role_status["code"]);
//                     }
                    
//                     $result['mtoken'] = TokenModel::buildToken($returnId);
//                     $result['role'] = self::defaultRole;
                    
//                     // 设置redis
//                     $redisData = array("mtoken"=>$result['mtoken'],"userid"=>$returnId);
//                     $mtokenRedis = Model::Redis("UserMtoken");
//                     $redis_key = $result['mtoken'];
//                     if($mtokenRedis->exists($redis_key)) {
//                         // 删除redis
//                         $mtokenRedis->del($redis_key);
//                     }
                    
//                     Model::new("User.User")->insertMtokenRedis($redisData);
                    
//                     // 写入用户登录令牌表
//                     $token_add = array("id" => $returnId, "mtoken" => $result['mtoken'], "devicetoken" => $this->params['devicetoken'], "devtype" => $this->params['devtype'],
//                         "addtime" => $now);
//                     $mtoken = new CusMtokenModel();
//                     $mtoken->add($token_add);
                    
                    
//                     Model::new("Sys.Mq")->add([
//                         "url"=>"Msg.SendMsg.NewUserMsg",
//                         "param"=>[
//                             "userid"=>$returnId,
//                         ],
//                     ]);
//                 }
//                 Model::new("Sys.Mq")->submit();
//                 // 修改令牌登录
//                 $token_login = array("customerid" => $returnId, "mtoken" => $result['mtoken'], "devicetoken" => $this->params['devicetoken'], "devtype" => $this->params['devtype'],
//                     "logintime" => $now);
//                 $customerLogin = new CusCustomerLoginModel();
//                 $customerLogin->add($token_login);
                
//                 return $this->json(200, $result);
//             } else {
//                 return $this->json(400);
//             }
//         }
//         // 验证码不正确，返回json数据
//         return $this->json(20005);
    }
    
    public function thirdpartyAction() {
        if(empty($this->params['open_type']) || empty($this->params['openid']) || empty($this->params['openencrypt'])) {
            return $this->json(404);
        }
        
        // 获取标识(是否要绑定手机号码)
//         $isbindMobile = $this->params['isbindMobile'];
//         if($isbindMobile == 1) {
//             // 执行绑定手机操作时
//             if(empty($this->params['mobile']) || empty($this->params['valicode'])) {
//                 return $this->json(404);
//             }
//         }
        
        $params['open_type'] = $this->params['open_type'];
        $params['openid'] = $this->params['openid'];
        
        if(!in_array($params['open_type'], $this->thirdPartyArr)) {
            return $this->json(1004);
        }
        
        $openencrypt = strtoupper(md5($params['openid'].getConfigKey()));
        if($openencrypt != strtoupper($this->params['openencrypt'])) {
            return $this->json(404);
        }
        
        // 查询第三方登录数据 在数据库表中 是否存在
        $customerOpenInfo = Model::ins("CusCustomerOpen")->getRow($params,"id,customerid,openid,open_type");
        
        $result = array();
        
        $isbindMobile = 0;
        if(empty($customerOpenInfo['id'])) {
            // 新的第三方登录信息
//             if($isbindMobile == 1) {
//                 return $this->json(20107);
//             }
            
            // 写入记录到数据库中
            $params['nickname'] = $this->params['nickname']?:'';
            $params['sex'] = $this->params['sex'] ?: 0;
            $params['province'] = $this->params['province'];
            $params['city'] = $this->params['city'];
            $params['country'] = $this->params['country'];
            $params['headimgurl'] = $this->params['headimgurl'];
            $params['privilege'] = $this->params['privilege'];
            $params['unionid'] = $this->params['unionid'];
            $params['addtime'] = getFormatNow();
            
            Model::ins("CusCustomerOpen")->insert($params);
            $isbindMobile = 1;
        } else {
            // 已经用此微信登录过了
//             if($isbindMobile == 0) {
//                 return $this->json(200, ["isbindMobile"=>1]);
//             }
            // 判断是否已经绑定用户
            if(!empty($customerOpenInfo['customerid'])) {
                // 用户已经绑定了 还在执行绑定操作，抛出异常
                $isbindMobile = 0;
            } else {
                
                // 更新第三方数据
                $params['nickname'] = $this->params['nickname']?:'';
                $params['sex'] = $this->params['sex'] ?: 0;
                $params['province'] = $this->params['province'];
                $params['city'] = $this->params['city'];
                $params['country'] = $this->params['country'];
                $params['headimgurl'] = $this->params['headimgurl'];
                $params['privilege'] = $this->params['privilege'];
                $params['unionid'] = $this->params['unionid'];
                
                Model::ins("CusCustomerOpen")->update($params,["id"=>$customerOpenInfo['id']]);
                
                $isbindMobile = 1;
//                 if($isbindMobile == 0) {
//                     return $this->json(200, ["isbindMobile"=>1]);
//                 }
            }
            
//             if($isbindMobile == 0) {
//                 // 已执行绑定，根据用户id获取基本信息
//                 $cus = Model::ins("CusCustomer")->getRow(["id"=>$customerOpenInfo['customerid']],"mobile");
//                 $params['version'] = $this->getVersion();
//                 $params['devtype'] = $this->params['devtype'];
//                 $params['mobile'] = $cus['mobile'];
//                 $params['valicode'] = md5(CommonModel::passValicode().getConfigKey());
//             } else {
//                 // 没绑定执行登录 绑定操作
//                 $params['version'] = $this->getVersion();
//                 $params['stocode'] = $this->params['stocode'];
//                 $params['parentid'] = $this->params['parentid'];
//                 $params['checkcode'] = $this->params['checkcode'];
//                 $params['valicode'] = $this->params['valicode'];
//                 $params['mobile'] = $this->params['mobile'];
//                 $params['devicetoken'] = $this->params['devicetoken'];
//                 $params['devtype'] = $this->params['devtype'];
//             }
            
//             $result = Model::new("User.Login")->login($params);
//             if($result["code"] == "200") {
//                 // 说明登录成功，获取用户id值, 更新open表
//                 Model::ins("CusCustomerOpen")->update(["customerid"=>$result['data']['customerid']],["id"=>$customerOpenInfo['id']]);
//                 $isbindMobile = 0;
//             } else {
//                 return $this->json($result["code"]);
//             }
        }
        
        // 判断是否要绑定个
//         return $this->json(200, ["isBindMobile"=>$isbindMobile]);
        if($isbindMobile == 0) {
            
            $params['nickname'] = $this->params['nickname']?:'';
            $params['sex'] = $this->params['sex'] ?: 0;
            $params['province'] = $this->params['province'];
            $params['city'] = $this->params['city'];
            $params['country'] = $this->params['country'];
            $params['headimgurl'] = $this->params['headimgurl'];
            $params['privilege'] = $this->params['privilege'];
            $params['unionid'] = $this->params['unionid'];
            Model::ins("CusCustomerOpen")->update($params,["id"=>$customerOpenInfo['id']]);
            
            $cus = Model::ins("CusCustomer")->getRow(["id"=>$customerOpenInfo['customerid']],"mobile");
            $params['version'] = $this->getVersion();
            $params['devtype'] = $this->params['devtype'];
            $params['mobile'] = $cus['mobile'];
            $params['valicode'] = md5(CommonModel::passValicode().getConfigKey());
            $params['parentid'] = $this->params['parentid'];
            $params['checkcode'] = $this->params['checkcode'];
            $params['stocode'] = $this->params['stocode'];
            $params['logintype'] = $customerOpenInfo['open_type'];
            $params['openid'] = $customerOpenInfo['openid'];
            
            $result = Model::new("User.Login")->login($params);
            if($result["code"] == "200") {
                // 说明登录成功，获取用户id值, 更新open表
//                 Model::ins("CusCustomerOpen")->update(["customerid"=>$result['data']['customerid']],["id"=>$customerOpenInfo['id']]);
                $result['data']['isBindMobile'] = 0;
                $result['data']['rechargeStr'] = "";
                return $this->json($result["code"],$result['data']);
            } 
            
            return $this->json($result["code"]);
            
        } else {
            // 没绑定的 返回状态给app端
            return $this->json(200, ["isBindMobile"=>1]);
        }
    }
    
    /**
    * @user 退出
    * @param 
    * @author jeeluo
    * @date 2017年3月4日下午2:32:05
    */
    public function signoutAction() {
        if(empty($this->params['mtoken'])) {
            // 参数不完整
            return $this->json(404);
        }
//         $login = new LoginModel();
//         if($login->logOut($this->params['mtoken'])) {
            // 退出成功
            return $this->json(200);
//         }
//         return $this->json(403);
    }
}