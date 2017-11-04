<?php
namespace app\sale\controller\User;

use app\sale\ActionController;
use app\lib\Model;
use app\model\Sys\CommonModel;
use app\model\User\UserBankModel;

class SettingController extends ActionController {
    
    const updatePhoneType = 'update_phone_';
    const updatePayType = 'update_pay_';
    const updateLoginPwdType = 'update_loginpwd_';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 个人资料
    * @param 
    * @author jeeluo
    * @date 2017年10月27日上午11:50:12
    */
    public function myinfoAction() {
        $viewData = [
            "title" => "我的资料",
            "customerid" => $this->userid
        ];
        
//         $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 修改昵称页面
    * @param 
    * @author jeeluo
    * @date 2017年10月27日上午11:51:13
    */
    public function updatenicknameAction() {
        $viewData = [
            "title" => "修改昵称",
//             "customerid" => $this->userid
        ];
        
//         $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 修改昵称操作
    * @param 
    * @author jeeluo
    * @date 2017年10月27日上午11:54:58
    */
    public function updateinfoAction() {
//         $this->checktokenHandle();
        $param['nickname'] = $this->params['nickname'];
        $param['sex'] = $this->params['sex'];
        $param['headerpic'] = $this->params['headerpic'];
        
        $param['customerid'] = $this->userid;
        
        $result = Model::new("User.Setting")->updateInfo($param);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 实名认证页面
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午2:46:47
    */
    public function authenticaAction() {
        $viewData = [
            "title" => "实名认证",
        ];
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 检测实名认证
    * @param 
    * @author jeeluo
    * @date 2017年10月30日下午4:01:27
    */
    public function checkauthAction() {
        $customerid = $this->userid;
        
        $authInfo = Model::ins("CusCustomerInfo")->getRow(['id'=>$customerid],"id,isnameauth");
        
        if(!empty($authInfo)) {
            return $this->json("200",["isnameauth"=>$authInfo['isnameauth']]);
        }
        return $this->json("10006");
    }
    
    /**
    * @user 检测支付密码是否设置
    * @param 
    * @author jeeluo
    * @date 2017年10月30日下午4:35:20
    */
    public function checkpaypwdAction() {
        $customerid = $this->userid;
        
        $paypwdInfo = Model::ins("CusCustomerPaypwd")->getRow(["id"=>$customerid],"id");
        
        if(!empty($paypwdInfo["id"])) {
            return $this->json("200");
        }
        return $this->json("10006");
    }
    
    /**
    * @user 校验支付密码
    * @param 
    * @author jeeluo
    * @date 2017年10月30日下午5:56:39
    */
    public function validpaypwdAction() {
        $param['customerid'] = $this->userid;
        
        $paypwd = $this->params['paypwd'];
        
        $param['paypwd'] = md5($paypwd);
        
        $result = Model::new("User.Setting")->validPayPwd($param);
        
        return $this->json($result['code']);
    }
    /**
    * @user 实名认证操作
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午3:54:12
    */
    public function updateauthdataAction() {
        $this->checktokenHandle();
        
        $param['customerid'] = $this->userid;
        $param['realname'] = $this->params['realname'];
        $param['idnumber'] = $this->params['idnumber'];

        $result = Model::new("User.Setting")->userAuth($param);
        
        return $this->json($result["code"]);
    }

    /**
    * @user 我的银行卡列表
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午4:09:56
    */
    public function mybanklistAction() {
        $viewData = [
            'title' => "我的银行卡"
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 获取银行卡列表数据
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午4:18:32
    */
    public function getBankListDataAction() {
       
        $userbank_obj = new UserBankModel();
        
        $bank_list = $userbank_obj->getUserBankList(['customerid'=>$this->userid]);

        if($bank_list["code"] != "200") {
            return $this->json($bank_list["code"]);
        }
        return $this->json($bank_list["code"], $bank_list['data']);
    }
    
    /**
    * @user 添加银行卡页面
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午4:58:31
    */
    public function addbankAction() {
        $viewData = [
            'title' => "添加银行卡"
        ];
        $this->addcheck();
       
        return $this->view($viewData);
    }
    
    /**
    * @user 解绑银行卡操作
    * @param 
    * @author jeeluo
    * @date 2017年9月20日下午7:08:57
    */
    public function unbindBankAction() {
        $bank_id = $this->params['bank_id'];
        $userbank_obj = new UserBankModel();
        $result = $userbank_obj->unbindBank(
            [
                "bank_id" => $bank_id,
                "customerid" => $this->userid
            ]
        );
        return $this->json($result['code'],$result['data'],$result['msg']);
    }
    
    /**
    * @user 根据银行卡号码识别对应银行
    * @param 
    * @author jeeluo
    * @date 2017年9月7日下午7:37:13
    */
    public function checkBankNumberAction() {
        $account_number = $this->params['account_number'];
        $result = CommonModel::account_bank_validate($account_number);
        
        return $this->json($result['code'], $result['data']);
    }
    
    /**
    * @user 添加银行卡号码操作
    * @param 
    * @author jeeluo
    * @date 2017年9月7日下午8:39:41
    */
    public function addBankNumberAction() {
        $customerid = $this->userid;
        $account_type = $this->params['account_type'];
        $account_name = $this->params['account_name'];
        $account_number = $this->params['account_number'];
        $bank_type_name = $this->params['bank_type_name'];
        $branch = $this->params['branch'];
        $mobile = $this->params['mobile'];
        
        $userbank_obj = new UserBankModel();
        $result = $userbank_obj->addBankNumber(
            ['account_type'=>$account_type,
            'account_name'=>$account_name,
            'account_number'=>$account_number,
            'bank_type_name'=>$bank_type_name,
            'branch'=>$branch,
            'mobile'=>$mobile,
            'customerid'=>$customerid]
        );
        return $this->json($result['code'],$result['data'],$result['msg']);
    }
    
    /**
    * @user 账户安全页面
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午4:13:36
    */
    public function safeindexAction() {
        $viewData = [
            "title" => "账户安全",
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 修改手机页面
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午4:20:49
    */
    public function updatephoneAction() {
        $viewData = [
            'title' => "修改手机",
        ];
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 发送短信验证码
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午4:31:56
    */
    public function sendvalicodeAction() {
        if(empty($this->params['mobile'])) {
            return $this->json("404");
        }
        
        $param['mobile'] = $this->params['mobile'];
        $param['privatekey'] = md5($param['mobile'].getConfigKey());
        $param['sendType'] = self::updatePhoneType;
        $param['devicenumber'] = 'deveice'.$param['mobile'];
        
        $result = CommonModel::sendValidate($param);
        return $this->json($result["code"]);
    }
    
    /**
    * @user 发送短信验证码
    * @param 
    * @author jeeluo
    * @date 2017年11月2日上午10:24:21
    */
    public function sendvalicodeloginpwdAction() {
        if(empty($this->params['mobile'])) {
            return $this->json("404");
        }
        
        $param['mobile'] = $this->params['mobile'];
        $param['privatekey'] = md5($param['mobile'].getConfigKey());
        $param['sendType'] = self::updateLoginPwdType;
        $param['devicenumber'] = 'deveice'.$param['mobile'];
        
        $result = CommonModel::sendValidate($param);
        return $this->json($result["code"]);
    }
    
    /**
    * @user 修改手机号码操作
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午5:04:16
    */
    public function updatephonevalicodeAction() {
        if(empty($this->params['mobile']) || empty($this->params['valicode'])) {
            return $this->json("404");
        }
        
        $param['mobile'] = $this->params['mobile'];
        $valicode = $this->params['valicode'];
        $encryptvalicode = md5($valicode.getConfigKey());
        
        $param['valicode'] = $encryptvalicode;
        $param['customerid'] = $this->userid;
        
        $result = Model::new("User.Setting")->updatePhone($param);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 设置支付密码
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午6:03:19
    */
    public function setpayAction() {
        
        $viewData = [
            "title" => "设置支付密码",
        ];
        
        $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 提交支付密码设置
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午6:25:09
    */
    public function submitpayAction() {
        $paypwd = $this->params['paypwd'];
        
        if(!CommonModel::validate_filter_paypwd($paypwd)) {
            return $this->json("2008");
        }
        
        $param['paypwd'] = md5($paypwd);
        $param['customerid'] = $this->userid;
        
        $result = Model::new("User.Setting")->setPay($param);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 修改支付密码页面
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午6:54:52
    */
    public function updatepayAction() {
        
        $customerid = $this->userid;
        $UserModel = Model::new("User.User");
        $userInfo = $UserModel->userInfo($customerid);
        
        $payDec = $userInfo['payDec'];
        $mobile = $userInfo['mobile'];
        $completemobile = $userInfo['completemobile'];
        
        $viewData = [
            "title" => $payDec == 1 ? "验证手机" : "设置支付密码",
            "payDec" => $payDec,
            "mobile" => $mobile,
            "completemobile" => $completemobile
        ];
        
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 发送修改支付密码短信
    * @param 
    * @author jeeluo
    * @date 2017年10月27日下午7:06:07
    */
    public function sendpayAction() {
        $completemobile = $this->params['completemobile'];
        
        $param['mobile'] = $completemobile;
        $param['devicenumber'] = 'device'.$completemobile;
        $param['privatekey'] = md5($param['mobile'].getConfigKey());
        $param['sendType'] = self::updatePayType;
        
        $result = CommonModel::sendValidate($param);
        return $this->json($result["code"]);
    }
    
    /**
    * @user 修改支付密码 手机验证
    * @param 
    * @author jeeluo
    * @date 2017年10月30日上午10:22:56
    */
    public function validphonepayAction() {
        $this->checktokenHandle();
        $valicode = $this->params['valicode'];
        
        $param['customerid'] = $this->userid;
//         $param['mobile'] = $this->params['mobile'];
        
        $param['valicode'] = strtoupper(md5($valicode.getConfigKey()));
        
        $result = Model::new("User.Setting")->validPhonePay($param);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 修改支付密码页面
    * @param 
    * @author jeeluo
    * @date 2017年10月30日上午10:38:21
    */
    public function updatepaynumberAction() {
        
        $viewData = [
            "title" => "修改支付密码"
        ];
        
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 修改支付密码操作
    * @param 
    * @author jeeluo
    * @date 2017年10月30日上午11:41:14
    */
    public function updatesubmitpayAction() {
        $paypwd = $this->params['paypwd'];
        
        if(!CommonModel::validate_filter_paypwd($paypwd)) {
            return $this->json("2008");
        }
        
        $param['paypwd'] = md5($paypwd);
        $param['customerid'] = $this->userid;
        
        $result = Model::new("User.Setting")->updatePayPwd($param);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 设置登录密码
    * @param 
    * @author jeeluo
    * @date 2017年11月1日下午6:18:41
    */
    public function loginpwdAction() {
        $title = "设置登录密码";
        
        $viewData = [
            "title" => $title
        ];
        
        $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 设置登录密码操作
    * @param 
    * @author jeeluo
    * @date 2017年11月1日下午6:36:42
    */
    public function setloginpwdAction() {
        $this->checktokenHandle();
        
        // 校验格式
        if(!CommonModel::validate_filter_loginpwd($this->params['loginpwd'])) {
            return $this->json("2007");
        }
        
        $param['loginpwd'] = strtoupper(md5($this->params['loginpwd']));
        $param['confirmpwd'] = strtoupper(md5($this->params['confirmpwd']));
        $param['customerid'] = $this->userid;
        
        $result = Model::new("User.User")->setLoginPwd($param);
        
        return $this->json($result["code"]);
    }
    
    /**
    * @user 验证手机页面
    * @param 
    * @author jeeluo
    * @date 2017年11月2日上午9:58:54
    */
    public function validloginpwdAction() {
        
        $title = "验证手机";
        $UserModel = Model::new("User.User");
        $customerid = $this->userid;
        $userInfo = $UserModel->userInfo($customerid);
        
        $mobile = $userInfo['mobile'];
        $completemobile = $userInfo['completemobile'];
        $viewData = [
            "title" => $title,
            "mobile" => $mobile,
            "completemobile" => $completemobile
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 校验手机验证码
    * @param 
    * @author jeeluo
    * @date 2017年11月2日上午10:30:10
    */
    public function validateloginpwdAction() {
        
        $param['mobile'] = $this->params['mobile'];
        $param['valicode'] = strtoupper(md5($this->params['valicode'].getConfigKey()));
        
        // 校验
        $result = Model::new("User.Setting")->validLoginPwd($param);
        
        return $this->json($result["code"], $result["data"]);
    }
    
    /**
    * @user 设置登录密码页面
    * @param 
    * @author jeeluo
    * @date 2017年11月2日上午11:02:21
    */
    public function updatepwdnumberAction() {
        $title = "设置登录密码";
        
        $viewData = [
            "title" => $title
        ];
        
        $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 修改登录密码操作
    * @param 
    * @author jeeluo
    * @date 2017年11月2日上午11:29:34
    */
    public function updateloginpwdAction() {
        $this->checktokenHandle();
        
        // 校验格式
        if(!CommonModel::validate_filter_loginpwd($this->params['loginpwd'])) {
            return $this->json("2007");
        }
        
        $UserModel = Model::new("User.User");
        $customerid = $this->userid;
        $userInfo = $UserModel->userInfo($customerid);
        
        $param['mobile'] = $userInfo['completemobile'];
        $param['encrypt'] = md5($param['mobile'].getConfigKey());
        $param['loginpwd'] = strtoupper(md5($this->params['loginpwd']));
        $param['confirmpwd'] = strtoupper(md5($this->params['confirmpwd']));
        
        // 操作
        $result = Model::new("User.Setting")->updateLoginPwd($param);
        
        return $this->json($result["code"]);
    }
}