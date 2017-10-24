<?php
namespace app\api\controller\User;

use app\api\ActionController;
use app\model\User\UserModel;
use app\model\CusCustomerInfoModel;
use app\model\CusCustomerModel;
use app\model\Sys\CommonModel;
use app\lib\Sms;
use app\model\CusCustomerPaypwdModel;
use app\model\StoBusiness\StobusinessModel;

use app\lib\Model;
use app\model\Sys\CommonRoleModel;

class UserController extends ActionController {
    
    // 一天最多使用次数
    // const oneMaxCount = 5;
    // 随机最小值
    const minRand = 100000;
    // 随机最大值
    const maxRand = 999999;
    // 定义常量 分钟数
    const minute = 5;
    // 分钟和秒数比例
    const minuteToSecond = 60;
    // 一天的秒数
    const oneDaySecond = 86400;
    // 注册手机操作类型前缀
    const sendType = "updateMobile_";
    // 验证手机验证类型前缀
    const valicodeType = "valicode_";
    
    const initNumber = 0;
    
    // 是否实名认证
    const isnameauth = 1;
    
    const stoRole = 5;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 个人首页(我的资料)
    * @param 
    * @author jeeluo
    * @date 2017年3月3日下午9:18:04
    */
    public function indexAction() {
        // 
        $user = new UserModel();
        $userInfo = $user->userInfo($this->userid);
//         $userInfo['role'] = 1;
//         $cusRoleLogOBJ = new CusRoleLogModel();
//         $logInfo = $cusRoleLogOBJ->getRow(array("customerid" => $this->userid));
//         if(!empty($logInfo)) {
//             $userInfo['role'] = $logInfo['role'];
//         }
        return $this->json(200, $userInfo);
    }
    
    /**
    * @user 头像暂时不做处理
    * @param 
    * @author jeeluo
    * @date 2017年3月3日下午9:31:13
    */
    public function updateInfoAction() {
        // 头像上传(暂时不写)
        
        $this->params['id'] = $this->userid;
        $cusInfo = new CusCustomerInfoModel();
        $status = $cusInfo->updateInfo($this->params);
//         if($status) {
//             return $this->json(200);
//         } 
        return $this->json(200);
    }
    
    /**
    * @user 发送验证码操作
    * @param 
    * @author jeeluo
    * @date 2017年3月27日下午4:22:53
    */
    public function sendvalidateAction() {
        $result = CommonModel::sendValidate($this->params);
        return $this->json($result['code']);
    }
    
    /**
    * @user 个人首页的(发送短信操作)
    * @param 
    * @author jeeluo
    * @date 2017年3月3日下午10:02:27
    */
    public function sendAction() {
        if(phone_filter($this->params['mobile'])) {
            // 手机验证不通过
            return $this->json(20006);
        }

        $countNumber = 0;
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

//         $isAndroid = $this->Version($this->params['version'], "A");
        
//         if($isAndroid) {
//             if(empty($this->params['privatekey'])) {
//                 // 参数不完整
//                 return $this->json(404);
//             }
//         } else {
//             if(empty($this->params['devicenumber']) || empty($this->params['privatekey'])) {
//                 // 参数不完整
//                 return $this->json(404);
//             }
//         }
        
//         if(empty($this->params['privatekey'])) {
//             return $this->json(404);
//         }
        
        $privatekey = strtoupper($this->params['privatekey']);
        $autokey = strtoupper(md5($this->params['mobile'].getConfigKey()));
        
        if($privatekey != $autokey) {
            return $this->json(400);
        }
        
        $mobile = $this->params['mobile'];
        
        // 因为手机号码唯一性问题，所以查询用户输入的手机号码是否已经被注册了
        $cus = new CusCustomerModel();
        $mobileInfo = $cus->getIdByMobile($mobile);
        
        if($mobileInfo) {
            // 手机号码已存在
            return $this->json(304);
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
//             CommonModel::setCacheNumber(self::sendType.$mobile, $randNumber, self::minute * self::minuteToSecond);
            
            // 设置手机今天发送验证的次数
            $MessageRedis->set(CommonModel::getDeviceProfix().$this->params['devicenumber'], ++$countNumber, strtotime(date('Y-m-d', time()+self::oneDaySecond))-time());
//             CommonModel::setCacheNumber(self::sendType.$this->params['devicenumber'], ++$countNumber, strtotime(date('Y-m-d', time()+self::oneDaySecond)) - time());
        }
        return $this->json(200);
    }
    
    /**
    * @user 修改手机请求操作
    * @param 
    * @author jeeluo
    * @date 2017年3月4日下午12:02:37
    */
    public function updatePhoneAction() {
        if(empty($this->params['mobile']) || empty($this->params['valicode'])) {
            return $this->json(404);
        }
        if(phone_filter($this->params['mobile'])) {
            return $this->json(403);
        }
        $cus = new CusCustomerModel();
        $mobileInfo = $cus->getIdByMobile($this->params['mobile']);
        
        if($mobileInfo) {
            // 手机号码已存在
            return $this->json(20011);
        }
//         // 手机验证码过期
//         if(!CommonModel::getCacheNumber(self::sendType.$this->params['mobile'])) {
//             return $this->json(403);
//         }
        
        $this->params['valicode'] = strtoupper($this->params['valicode']);
        
        // 验证手机号码
        $isUser = $cus->compare($this->params, self::sendType);
        if($isUser) {
            // 验证码正确
            $phoneArr = array("mobile" => $this->params['mobile'], "username" => $this->params['mobile']);
            // 修改
            $status = $cus->modifyUpdate($phoneArr, "id = ".$this->userid);
            if($status) {
                return $this->json(200);
            } else {
                return $this->json(400);
            }
        }
        return $this->json(1001);
    }
    
    /**
    * @user 支付密码操作
    * @param 
    * @author jeeluo
    * @date 2017年3月4日下午2:23:35
    */
    public function setPayAction() {
        if(empty($this->params['paypwd'])) {
            $this->json(404);
        }
        // 设置加密支付密码
        $data['paypwd'] = md5($this->params['paypwd'].getConfigKey());
        
        $data['id'] = $this->userid;
        // 写入数据
        $cusOBJ = new CusCustomerPaypwdModel();
        $cuspwd = $cusOBJ->setId($this->userid)->getById($this->userid);
        
        if(empty($cuspwd)) {
            $status = $cusOBJ->add($data);
            if($status) {
                return $this->json(200);
            }
            return $this->json(400);
//         } else {
//             $status = $cusOBJ->modify(array("paypwd" => $data['paypwd']), array("id" => $data['id']));
//             return $this->json(200);
        }
        return $this->json(10002);
    }
    
    /**
     * @uses 修改支付密码的发送短信操作
     * @author jeeluo
     * @date 2017年3月7日 09:59:20
     */
    public function sendPayAction() {
//         if(empty($this->params['devicenumber'])) {
//             // 参数不完整
//             return $this->json(404);
//         }

//         $isAndroid = $this->Version($this->params['version'], "A");
        
//         if(!$isAndroid) {
//             if(empty($this->params['devicenumber'])) {
//                 // 参数不完整
//                 return $this->json(404);
//             }
//         }
        $cus = new CusCustomerModel();
        $user = $cus->setId($this->userid)->getById();
        
        $mobile = $user['mobile'];
        
        
        $countNumber = self::initNumber;
        $MessageRedis = Model::Redis("MessageValicode");
        if($MessageRedis->exists(CommonModel::getDeviceProfix().$this->params['devicenumber'])) {
            $countNumber = $MessageRedis->get(CommonModel::getDeviceProfix().$this->params['devicenumber']);
        }
        
        if($this->Version("2.0.0")) {
            if(empty($this->params['devicenumber'])) {
                // 参数不完整
                return $this->json(404);
            }
            
            if($countNumber >= CommonModel::getMaxDevice()) {
                return $this->json(405);
            }
        } else {
            $isAndroid = $this->Version($this->params['version'], "A");
            if(!$isAndroid) {
                if(empty($this->params['devicenumber'])) {
                    // 参数不完整
                    return $this->json(404);
                }
                
                if($countNumber >= CommonModel::getMaxDevice()) {
                    return $this->json(405);
                }
            }
        }
        
        // 获取cache内的次数信息
        
//         $countNumber = CommonModel::getCacheNumber(self::valicodeType.$this->params['devicenumber']);

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
//             CommonModel::setCacheNumber(self::valicodeType.$mobile, $randNumber, self::minute * self::minuteToSecond);
        
            // 设置手机今天发送验证的次数
            $MessageRedis->set(CommonModel::getDeviceProfix().$this->params['devicenumber'], ++$countNumber, strtotime(date('Y-m-d', time()+self::oneDaySecond))-time());
//             CommonModel::setCacheNumber(self::valicodeType.$this->params['devicenumber'], ++$countNumber, strtotime(date('Y-m-d', time()+self::oneDaySecond)) - time());
        }
        return $this->json(200);
    }
    
    /**
    * @user 校验支付手机验证码
    * @param 
    * @author jeeluo
    * @date 2017年3月7日上午10:07:23
    */
    public function validPhonePayAction() {
        if(empty($this->params['valicode'])) {
            return $this->json(404);
        }
        $cus = new CusCustomerModel();
        $user = $cus->setId($this->userid)->getById();
        
        $mobile = $user['mobile'];
        // 手机验证码过期
//         if(!CommonModel::getCacheNumber(self::valicodeType.$mobile)) {
//             return $this->json(403);
//         }
        $this->params['mobile'] = $mobile;
        
        $this->params['valicode'] = strtoupper($this->params['valicode']);
        
        // 验证手机号码
        $isUser = $cus->compare($this->params, self::sendType);
        if($isUser) {
            // 验证码正确
            return $this->json(200);
        } else {
            return $this->json(20005);
        }
    }
    /**
    * @user 修改支付密码
    * @param 
    * @author jeeluo
    * @date 2017年3月7日上午10:31:04
    */
    public function updatePayPwdAction() {
        if(empty($this->params['paypwd'])) {
            $this->json(404);
        }
        $cusPwd = new CusCustomerPaypwdModel();
        $oldArr = $cusPwd->setId($this->userid)->getById($this->userid);
        
        if(empty($oldArr)) {
            return $this->json(10003);
        }
        
        $data['paypwd'] = md5($this->params['paypwd'].getConfigKey());
        if($data['paypwd'] != $oldArr['paypwd']) {
            // 操作成功，进行修改数据
            $status = $cusPwd->updatemodify($data, array("id" => $this->userid));
            if($status) {

                //清空支付密码的限制
                Model::new("Sys.ActLimit")->del("paypwd".$this->userid);

                return $this->json(200);
            }
            return $this->json(400);
        }
        return $this->json(10001);
    }
    
    /**
    * @user 实名认证操作
    * @param 
    * @author jeeluo
    * @date 2017年4月6日下午5:53:31
    */
    public function authAction() {
        if(empty($this->params['realname']) || empty($this->params['idnumber'])) {
            return $this->json(404);
        }
        // 检验身份证
        if(!CommonModel::validation_filter_idcard($this->params['idnumber'])) {
            return $this->json(20003);
        }
        $data['realname'] = $this->params['realname'];
        $data['idnumber'] = $this->params['idnumber'];
        $data['isnameauth'] = self::isnameauth;
        
        $cusInfoOBJ = new CusCustomerInfoModel();
        $cusInfoOBJ->modify($data, array("id" => $this->userid));
        
        return $this->json(200);
    }    
    /**
    * @user 银行卡列表
    * @param 
    * @author jeeluo
    * @date 2017年3月22日上午10:41:52
    */
    public function banklistAction() {
        $params['customerid'] = $this->userid;
        $params['enable'] = 1;
        $userOBJ = new UserModel();
        $result = $userOBJ->getBankList($params);
        
        if($this->Version("1.0.2")) {
            // 获取用户是否已经实名认证
            $nameAuthInfo = CommonModel::getUserNameAuth($params['customerid']);
            
            $result['status'] = -1;
            $result['account_name'] = '';
            
            if(!empty($nameAuthInfo)) {
                $result['account_name'] = $nameAuthInfo['realname'];
                $result['status'] = 1;
            }
        }
        
        return $this->json(200, $result);
    }
    
    /**
    * @user 检查银行卡号码识别对应银行
    * @param 
    * @author jeeluo
    * @date 2017年7月11日下午4:08:24
    */
    public function checkBankNumberAction() {
        if(empty($this->params['account_number'])) {
            return $this->json(400);
        }
        
        $result = CommonModel::account_bank_check($this->params['account_number']);
        
        return $this->json($result['code'], $result['data']);
    }
    
    /**
    * @user 获取添加银行卡信息
    * @param 
    * @author jeeluo
    * @date 2017年5月22日上午10:42:08
    */
//     public function getAddBankAction() {
//         $customerid = $this->userid;
//         // 获取用户是否已经实名认证
//         $nameAuthInfo = CommonModel::getUserNameAuth($customerid);
        
//         $result['status'] = -1;
//         $result['account_name'] = '';
        
//         if(!empty($nameAuthInfo)) {
//             $result['account_name'] = $nameAuthInfo['realname'];
//             $result['status'] = 1;
//         }
//         return $this->json(200, $result);
//     }
    
    /**
    * @user 添加银行卡操作
    * @param 
    * @author jeeluo
    * @date 2017年3月22日下午2:12:13
    */
    public function addbankAction() {
        if(empty($this->params['account_type']) || empty($this->params['account_name']) || empty($this->params['account_number']) || empty($this->params['bank_type_name']) || 
            empty($this->params['branch']) || empty($this->params['mobile'])) {
                return $this->json(404);
            }
        
        if(phone_filter($this->params['mobile'])) {
            return $this->json(20006);
        }
        // 当是个人帐号时才去校验银行卡号(对公帐号规则 太杂了。没标准)；
        if($this->params['account_type'] == 1) {
            if(!CommonModel::account_bank_validate($this->params['account_number'])) {
                return $this->json(20012);
            }
        }
        $this->params['customerid'] = $this->userid;

        // 查询数据库该卡号是否已经存在(已激活的)
        $cusBankInfo = Model::ins("CusBank")->getRow(array("customerid" => $this->params['customerid'],"account_number" => $this->params['account_number'], "enable" => 1), "id");
        if(!empty($cusBankInfo)) {
            return $this->json("20013");
        }

        $userOBJ = new UserModel();
        
        // 校验银行卡和用户名是否一致--通过api接口进行校验
        $checkbankresult = $userOBJ->checkbankInfo([
                "userid"=>$this->userid,
                "account_name"=>$this->params['account_name'],
                "account_number"=>$this->params['account_number'],
            ]);
        if($checkbankresult["code"]!='200')
            return $this->json($checkbankresult["code"]);
        
        $result = $userOBJ->addBank($this->params);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 解绑操作
    * @param 
    * @author jeeluo
    * @date 2017年3月22日下午2:34:30
    */
    public function unbandAction() {
        if(empty($this->params['bank_id'])) {
            return $this->json(404);
        }
        $params['id'] = $this->params['bank_id'];
        $params['customerid'] = $this->userid;
        
        $userOBJ = new UserModel();
        $result = $userOBJ->unbandBank($params);
        
        return $this->json($result['code']);
    }
    
    /**
    * @user 获取商圈的信息
    * @param 
    * @author jeeluo
    * @date 2017年3月24日下午2:49:26
    */
    public function shopAction() {
        $params['customerid'] = $this->userid;
        if(!empty($this->params['customerid'])) {
            if($params['customerid'] != $this->params['customerid']) {
                // 假如传递了实体店用户id值(得判断输入的用户值和当前用户是否有推荐关系)
                $parantid = $this->userid;
                $stoRelation = Model::ins("CusRelation")->getRow(["customerid"=>$this->params['customerid'],"role"=>self::stoRole,"parentid"=>$parantid],"id");
                
                if(empty($stoRelation['id'])) {
                    return $this->json(1001);
                }
                $params['customerid'] = $this->params['customerid'];
            }
        }
        $userOBJ = new UserModel();
        $stoId = $userOBJ->getUserSto($params);
        if(empty($stoId)) {
//             return $this->json(1001);
            return $this->json(60008);
        }
        
        $params['stoId'] = $stoId;
        $params['version'] = $this->getVersion();
        $stoInfo = StobusinessModel::getStoApiInfo($params);
        
        if($this->Version("1.0.1") || $this->Version("1.0.2") || $this->Version("1.0.3")) {
            $stoBusInfo = Model::ins("StoBusiness")->getRow(array("id" => $stoId), "isvip");
            $stoInfo['isvip'] = $stoBusInfo['isvip'];
            $stoInfo['businessid'] = $stoId;
        }
        
        return $this->json(200, $stoInfo);
    }
    
    /**
    * @user 修改商铺信息
    * @param 
    * @author jeeluo
    * @date 2017年3月25日下午1:55:43
    */
    public function updateshopAction() {
        $params['customerid'] = $this->userid;
        if(!empty($this->params['customerid'])) {
            if($params['customerid'] != $this->params['customerid']) {
                // 假如传递了实体店用户id值(得判断输入的用户值和当前用户是否有推荐关系)
                $parantid = $this->userid;
                $stoRelation = Model::ins("CusRelation")->getRow(["customerid"=>$this->params['customerid'],"role"=>self::stoRole,"parentid"=>$parantid],"id");
                
                if(empty($stoRelation['id'])) {
                    return $this->json(1001);
                }
                $params['customerid'] = $this->params['customerid'];
            }

            $userOBJ = new UserModel();
            $stoId = $userOBJ->getUserSto($params);
            if(empty($stoId)) {
                return $this->json(60008);
            }
        }
        if(empty($this->params['sto_name']) || empty($this->params['discount']) || empty($this->params['sto_hour_begin']) || empty($this->params['sto_hour_end'])
            || empty($this->params['sto_mobile']) || empty($this->params['description'])) {
                return $this->json(404);
            }
            
        if($this->Version("1.0.4")) {
            // 检测商家分类id值
            $sto = Model::ins("StoBusiness")->getRow(["customerid"=>$params['customerid']],"id");
            
            // 查看商家是否有分类
            $sto_info = Model::ins("StoBusinessInfo")->getRow(["id"=>$sto['id']],"categoryid");
            
            if(!empty($sto_info['categoryid'])) {
                if(!empty($this->params['sto_type_id'])) {
                    if($sto_info['categoryid'] != $this->params['sto_type_id']) {
                        return $this->json(1001);
                    }
                }
            } else {
                if(empty($this->params['sto_type_id'])) {
                    return $this->json(404);
                }
            }
            $params['sto_type_id'] = $this->params['sto_type_id'];
        }

        
        $params['sto_name'] = $this->params['sto_name'];
        $params['discount'] = $this->params['discount'];
        $params['sto_hour_begin'] = $this->params['sto_hour_begin'];
        $params['sto_hour_end'] = $this->params['sto_hour_end'];
        $params['service_type'] = $this->params['service_type'];
//         $params['delivery'] = $this->params['delivery'];
        $params['delivery'] = "5.5";
        $params['dispatch'] = $this->params['dispatch'];
        $params['area'] = $this->params['area'];
        $params['area_code'] = $this->params['area_code'];
        $params['address'] = $this->params['address'];
        $params['nearby_village'] = $this->params['nearby_village'];
        $params['metro_id'] = $this->params['metro_id'];
        $params['district_id'] = $this->params['district_id'];
        $params['main_image'] = $this->params['main_image'];
        $params['sto_image'] = $this->params['sto_image'];
        $params['sto_mobile'] = $this->params['sto_mobile'];
        $params['description'] = $this->params['description'];
        $params['album_image'] = $this->params['album_image'];
        $params['isAndroid'] = $this->Version($this->params['version'],'A');
        $params['version'] = $this->getVersion();
        $result = StobusinessModel::cusUpdateStoInfo($params);
        
        return $this->json($result['code']);
    }


}