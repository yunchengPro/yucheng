<?php
namespace app\sale\controller\User;

use think\Cookie;
use app\sale\ActionController;
use app\model\User\UserBankModel;
use app\model\Sys\CommonModel;

class SettingController extends ActionController {
    
    /**
    * @user 构造函数
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午2:57:16
    */
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 设置首页
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午2:58:21
    */
    public function indexAction() {
        $viewData = [
            'title' => "设置"
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 我的资料页面
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午3:15:11
    */
    public function myinfoAction() {
        $viewData = [
            'title' => "我的资料",
            'role' => 1
        ];
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 修改昵称页面
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午3:39:31
    */
    public function updatenicknameAction() {
        $viewData = [
            'title' => "修改昵称",
            'role' => 1
        ];
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 修改我的资料设置
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午4:05:18
    */
    public function updateInfoAction() {
        $nickname = $this->params['nickname'];
        $sex = $this->params['sex'];
        $headerpic = $this->params['headerpic'];
        
        return $this->_api(([
            "actionname"=>"user.user.updateInfo",
            "param" => [
                "nickname" => $nickname,
                "sex" => $sex,
                "headerpic" => $headerpic
            ],
        ]));
    }
    
    /**
    * @user 实名认证页面
    * @param 
    * @author jeeluo
    * @date 2017年9月4日下午8:09:50
    */
    public function authenticaAction() {
        $viewData = [
            'title' => "实名认证",
            'role' => 1
        ];
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 填写实名认证信息
    * @param 
    * @author jeeluo
    * @date 2017年9月5日上午10:43:27
    */
    public function updateAuthDataAction() {
        $realname = $this->params['realname'];
        $idnumber = $this->params['idnumber'];
        
        $result = $this->_api(([
            "actionname" => "user.user.auth",
            "param" => [
                'realname' => $realname,
                'idnumber' => $idnumber
            ],
        ]));
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

        
        if($bank_list["code"] == "200") {
            $list = $bank_list['data']['list'];
            if(!empty($list)){
                foreach ($list as $k => $v) {
                    // 处理帐号字符串问题
                    $bank_list['data']['list'][$k]['accountnumberArr'] = explode(" ",$v['account_number']);
                }
            }
        }
        
        return json_encode($bank_list);
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
        $result = CommonModel::account_bank_check($account_number);
        
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
    * @date 2017年9月2日下午6:30:39
    */
    public function safeindexAction() {
        $viewData = [
            'title' => "账户安全",
            'role' => 1
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 修改手机页面
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午6:39:33
    */
    public function updatephoneAction() {
        $viewData = [
            'title' => "修改手机"
        ];
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 发送手机短信验证码操作
    * @param 
    * @author jeeluo
    * @date 2017年9月5日下午4:11:42
    */
    public function sendvalicodeAction() {
        $mobile = $this->params['mobile'];
        $devicenumber = 'mobile-'.$mobile;
        $privatekey = md5($mobile.getConfigKey());
        
        return $this->_api(([
            "actionname"=>"user.user.send",
            "param"=>[
                'mobile' => $mobile,
                'devicenumber' => $devicenumber,
                'privatekey' => $privatekey
            ],
        ]));
    }
    
    /**
    * @user 修改手机操作
    * @param 
    * @author jeeluo
    * @date 2017年9月5日下午4:33:26
    */
    public function updatePhoneValicodeAction() {
        $mobile = $this->params['mobile'];
        $valicode = $this->params['valicode'];
        
        $encryptvalicode = md5($valicode.getConfigKey());
        
        return $this->_api(([
            "actionname"=>"user.user.updatePhone",
            "param" => [
                "mobile" => $mobile,
                "valicode" => $encryptvalicode
            ],
        ]));
    }
    
    /**
    * @user 修改支付密码操作
    * @param 
    * @author jeeluo
    * @date 2017年9月5日下午5:08:18
    */
    public function updatepayAction() {
        $result = $this->_api(([
            "actionname" => "user.index.index",
            "param" => [
                "role" => 1,
            ],
        ]));
        
        $payDec = 0;
        $mobile = '';
        $completemobile = '';
        
        $resultArr = json_decode($result, true);
        
        if($resultArr["code"] == "200") {
            $userinfo = $resultArr['data']['userinfo'];
            $payDec = $userinfo['payDec'];
            $mobile = $userinfo['mobile'];
            $completemobile = $userinfo['completemobile'];
        }
        
        $viewData = [
            'title' => $payDec == 1 ? '验证手机' : '设置支付密码',
            'payDec' => $payDec,
            'mobile' => $mobile,
            'completemobile' => $completemobile
        ];
        
        $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 修改支付密码的短信验证
    * @param 
    * @author jeeluo
    * @date 2017年9月5日下午5:49:54
    */
    public function sendPayAction() {
        $completemobile = $this->params['completemobile'];
        
        $devicenumber = 'mobile-'.$completemobile;
        
        return $this->_api(([
            "actionname" => "user.user.sendPay",
            "param" => [
                "devicenumber" => $devicenumber
            ],
        ]));
    }
    
    /**
    * @user 校验支付手机验证码
    * @param 
    * @author jeeluo
    * @date 2017年9月5日下午6:08:48
    */
    public function validPhonePayAction() {
        $valicode = $this->params['valicode'];
        
        $encryptvalicode = md5($valicode.getConfigKey());
        
        return $this->_api(([
            "actionname" => "user.user.validPhonePay",
            "param" => [
                "valicode" => $encryptvalicode
            ],
        ]));
    }
    
    /**
    * @user 设置支付密码
    * @param 
    * @author jeeluo
    * @date 2017年9月5日下午7:57:31
    */
    public function setpayAction() {
        
        $viewData = [
            'title' => "设置支付密码"
        ];
        
        $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 提交支付密码操作
    * @param 
    * @author jeeluo
    * @date 2017年9月5日下午8:26:18
    */
    public function submitPayAction() {
        $paypwd = $this->params['paypwd'];
        
        $encryptpwd = md5($paypwd);
        
        return $this->_api(([
            "actionname" => "user.user.setPay",
            "param" => [
                "paypwd" => $encryptpwd
            ],
        ]));
    }
    
    /**
    * @user 修改支付密码页面
    * @param 
    * @author jeeluo
    * @date 2017年9月8日上午10:27:55
    */
    public function updatepaynumberAction() {
        $viewData = [
            'title' => "修改支付密码"
        ];
        
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 修改支付密码操作
    * @param 
    * @author jeeluo
    * @date 2017年9月8日上午10:31:57
    */
    public function updateSubmitPayAction() {
        $paypwd = $this->params['paypwd'];
        
        $encryptpwd = md5($paypwd);
        
        return $this->_api(([
            "actionname" => "user.user.updatePayPwd",
            "param" => [
                "paypwd" => $encryptpwd
            ],
        ]));
    }

    public function loginoutAction() {
        // 删除缓存
        Cookie::set('customerid',$cus['id'],time());
        Cookie::set('mtoken',md5($cus['id'].getConfigKey()),time());

        $result['code'] = "200";
        return json_encode($result);
    }
}