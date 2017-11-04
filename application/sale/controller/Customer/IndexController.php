<?php
namespace app\mobile\controller\Customer;
use app\mobile\ActionController;
use app\lib\Model;
use \think\Config;
use think\Session;
use \think\Cookie; 

use app\lib\Pay\AliWap\AliWap;
use app\lib\Pay\WeixinWeb\WeixinWeb;
use app\model\CusCustomerModel;
use app\model\CusCustomerPaypwdModel;
use app\lib\Pay\Allinpay\Allinpay;
use think\captcha\Captcha;
use app\model\Sys\CommonRoleModel;
use app\model\Sys\CommonModel;
use app\model\User\UserModel;


class IndexController extends ActionController{

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
    //     const oneMaxCount = 5;

     // 消费者角色
    const defaultRole = 1;
    
    const companyType = -1;
    
    const disenable = -1;

    // 注册手机操作类型前缀
    const sendType = "wap_password_";
    
    const sendRegisterType = "wap_register_";

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    
    /**
    * @user 邀请牛人前置引导页
    * @param 
    * @author jeeluo
    * @date 2017年7月28日下午2:16:57
    */
    public function inviteOrAction() {
        $params['role'] = $this->params['role'];
        $params['userid'] = $this->params['userid'];
        $params['checkcode'] = $this->params['checkcode'];
        
        $viewData = ["title"=>'邀请注册',
            "inviteData"=>$params
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 邀请牛创客前置引导页
    * @param 
    * @author jeeluo
    * @date 2017年7月28日下午2:17:11
    */
    public function inviteEnAction() {
        $params['role'] = $this->params['role'];
        $params['userid'] = $this->params['userid'];
        $params['checkcode'] = $this->params['checkcode'];
        
        $viewData = ["title"=>'邀请注册',
            "inviteData"=>$params
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 邀请牛达人前置引导页
    * @param 
    * @author jeeluo
    * @date 2017年7月28日下午2:17:39
    */
    public function inviteNdAction() {
        $params['role'] = $this->params['role'];
        $params['userid'] = $this->params['userid'];
        $params['checkcode'] = $this->params['checkcode'];
        
        $viewData = ["title"=>'邀请注册',
            "inviteData"=>$params
        ];
        
        return $this->view($viewData);
    }
    

    public function becomeTarentAction(){

        $is_weixin = is_weixin();
        
        $customerid = $this->params['customerid'];
        $role = $this->params['role'];
        $userid = $this->params['userid'];
        $checkcode = $this->params['checkcode'];
        $isweixin = $this->params['isweixin'];
        //var_dump($isweixin);
        $code = $this->params['code'];
        
        if($is_weixin){
            
            $weixin_config = Config::get("weixin");
            ///Customer/Index/becomeTarent?role=2&checkcode=60f3579b020f6f414046c71b587a7622&customercode=5e895b1e94ca3b1833fcfddfedfd57be&userid=903
            $webview = Config::get('webview');
            
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$weixin_config['appid']."&redirect_uri=".urlencode($webview['become_url']."role=".$role."&checkcode=".$checkcode."&userid=".$userid.'&customerid='.$customerid."&customercode=".$this->params['customercode']."&isweixin=1")."&response_type=code&scope=snsapi_base#wechat_redirect";
           
           


            

            $openData = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$weixin_config['appid'].'&secret='.$weixin_config['appsecret'].'&code='.$code.'&grant_type=authorization_code');

           

            //var_dump($openData);
            $openData = json_decode($openData,true);
            //print_r($openData);

            $openid = $openData['openid'];

            if(empty($openid)){
                header('Location:'.$url);
               // echo "<script type='text/javascript'>window.location.href='".$url."';</script>";
                exit;
            }

        }
        
        if(empty($role) || empty($userid)) {
            $content = '参数有误，请重新扫描';
            // 数据异常
            header('Location: /Customer/Index/error?content='.$content);
            echo "<script type='text/javascript'>window.location.href='/Customer/Index/error?content='.$content.';</script>";
            exit;
        }
        if(!in_array($role, [2,3,8])){
            $content = '参数有误，请重新扫描';
            // 数据异常
            header('Location: /Customer/Index/error?content='.$content);
            echo "<script type='text/javascript'>window.location.href='/Customer/Index/error?content='.$content.';</script>";
            exit;
        }
        // 确保引荐人数据正确
        if(md5($userid.getConfigKey()) != $this->params['checkcode']) {
            $content = '引荐人数据有异，请重新扫描';
            header('Location: /Customer/Index/error?content='.$content);
            echo "<script type='text/javascript'>window.location.href='/Customer/Index/error?content='.$content.';</script>";
            exit;
        }
        
        $parentrole = $this->params['recole'] ?: '';
        $parentrolestatus = 1;
        if($parentrole != "") {
            // 和role校验
            if($role == 2) {
                if($parentrole != 2 && $parentrole != 3 && $parentrole != 8) {
                    $parentrolestatus = 0;
                }
            } else if($role == 3) {
                if($parentrole != 3) {
                    $parentrolestatus = 0;
                }
            } else if($role == 8) {
                if($parentrole != 8) {
                    $parentrolestatus = 0;
                }
            } else {
                $parentrolestatus = 0;
            }
        } else {
            // 老版本此处为空 不处理
        }
        if($parentrolestatus == 0) {
            $content = '引荐人数据有异，请重新扫描';
            header('Location: /Customer/Index/error?content='.$content);
            echo "<script type='text/javascript'>window.location.href='/Customer/Index/error?content='.$content.';</script>";
            exit;
        }
        
        
//         if(empty($customerid)){
//             header('Location: /Index/Index/login?url=/Customer/Index/becomeTarent?role='.$role."&userid=".$userid."&checkcode=".$checkcode);
//             echo "<script type='text/javascript'>window.location.href='/Index/Index/login?url=/Customer/Index/becomeTarent?role=".$role."&userid=".$userid."&checkcode=".$checkcode."';</script>";
//             exit;
//         }

       
//         // 匹配用户
//         if(md5($customerid.getConfigKey()) != $this->params['customercode']) {
//             // 用户数据异常
//             $content = '参数有误，用户数据有异';
//             // 数据异常
//             header('Location: /Customer/Index/error?content='.$content);
//             echo "<script type='text/javascript'>window.location.href='/Customer/Index/error?content='.$content.';</script>";
//             exit;
//         }
        
        // 查询登录用户是否已经有此角色值
        // $cusRole = Model::ins("CusRole")->getRow(["customerid"=>$customerid,"role"=>$role]);
        
        // if(!empty($cusRole)) {
        //     // 跳转到提示页面
        //     header('Location: /Customer/Index/roleBuildedTip?role='.$role);
        //     echo "<script type='text/javascript'>window.location.href='/Customer/Index/roleBuildedTip?role='.$role.';</script>";
        //     exit;
        // }


        
        $title = '';
        $role_config = Config::get('role_money');
        $role_money = 0;
        $serviceStr = '';
        $serviceUrl = '';
        $backUrl = '';
        if($role == 2) {
            $title = '我要成为牛人';
            $role_money = $role_config['bullPeoMoney'];
            $editionStr = '《大数据云铺》基础版';
            $editionUrl = '/Introduction/Cloudshop/foundationEdition';
            $serviceStr = '《牛人服务协议》';
            $serviceUrl = '/Introduction/index/serviceManDeal';
            $backUrl = '/Customer/Index/inviteOr?role='.$role."&userid=".$userid."&checkcode=".$checkcode;
        } else if($role == 3) {
            $title = '我要成为牛创客';
            $role_money = $role_config['bullenMoney'];
            $editionStr = '《大数据云铺》企业版';
            $editionUrl = '/Introduction/Cloudshop/businessEdition';
            $serviceStr = '《牛创客服务协议》';
            $serviceUrl = '/Introduction/index/serviceMakerDeal';
            $backUrl = '/Customer/Index/inviteEn?role='.$role."&userid=".$userid."&checkcode=".$checkcode;
        } else if($role == 8) {
            $title = '我要成为牛达人';
            $role_money = $role_config['bullTalentMoney'];
            $editionStr = '《大数据云铺》高级版';
            $editionUrl = '/Introduction/Cloudshop/topgradeEdition';
            $serviceStr = '《牛达人服务协议》';
            $serviceUrl = '/Introduction/index/serviceTarentoDeal';
            $backUrl = '/Customer/Index/inviteNd?role='.$role."&userid=".$userid."&checkcode=".$checkcode;
        }
        // 获取用户实名信息
//         $cusInfo = Model::ins("CusCustomerInfo")->getRow(["id"=>$customerid],"isnameauth,realname,idnumber");
//         if($cusInfo['isnameauth'] == 1) {
//             $cus = Model::ins("CusCustomer")->getRow(["id"=>$customerid],"mobile");
            
//             $cusInfo['mobile'] = $cus['mobile'];
//         }
        
        $parent = Model::ins("CusCustomer")->getRow(["id"=>$userid],"mobile");
        
        $otherData['roleupdateauth'] = CommonRoleModel::getShareRoleMobile(["applyRole"=>$parentrole,"instoducerMobile"=>$parent['mobile']]) ? 0 : 1;
        if($otherData['roleupdateauth'] == 1) {
            if($role == 2) {
                $otherData['errormsg'] = '您的分享人不具备创客身份，您可以填写任意一位创客的手机号或者不填';
            } else if($role == 3) {
                $otherData['errormsg'] = '您的分享人不具备牛创客身份，您可以填写任意一位牛创客的手机号或者不填';
            } else if($role == 8) {
                $otherData['errormsg'] = '您的分享人不具备牛达人身份，您可以填写任意一位牛达人的手机号或者不填';
            }
        } else {
            $otherData['errormsg'] = '';
        }
        
        // 查询数据库成为表 是否已经有数据
//         $roleApplyLog = Model::ins("RoleApplyLog")->getRow(["customerid"=>$customerid,"instrodcermobile"=>$parent['mobile'],"pay_status"=>0],"address,area_code");
//         if(!empty($roleApplyLog)) {
//             $otherData['area_code'] = $roleApplyLog['area_code'];
//             $otherData['address'] = $roleApplyLog['address'];
//         }

        $otherData['parentMobile'] = $parent['mobile'];
        $otherData['companyMobile'] = CommonModel::getCompanyPhone();
        $otherData['roleMoney'] = !empty($role_money) ? DePrice($role_money) : '0.00';
        
        $otherData['introducerrole'] = $parentrole;
        
        $is_alipay = is_alipay();
        
    	$viewData = [
            'title'=>$title,
//     	    'cusInfo'=>$cusInfo,
    	    'otherData'=>$otherData,
    	    'serviceStr'=>$serviceStr,
    	    'serviceUrl'=>$serviceUrl,
    	    'backUrl'=>$backUrl,
            'openid' => $openid,
            'is_weixin' => $is_weixin,
    	    'is_alipay' => $is_alipay,
            'role'=>$role,
            'checkcode'=>$checkcode,
//             'customerid'=>$customerid,
            'userid' => $userid,
            'customercode' => $this->params['customercode'],
            'editionStr'=>$editionStr,
            'editionUrl'=>$editionUrl
        ];

        return $this->view($viewData);
    }
    
    /**
    * @user 检测用户填写的手机号码
    * @param 
    * @author jeeluo
    * @date 2017年8月1日上午10:59:05
    */
    public function checkPhoneAction() {
        $mobile = $this->params['mobile'];
        
        if(phone_filter($mobile)) {
            return ["code"=>"20006","data"=>"手机号码不正确"];
        }
        
        $cus = Model::ins("CusCustomer")->getRow(["mobile"=>$mobile],"id");
        
        if(!empty($cus['id'])) {
            $role = $this->params['role'];
            // 查看填写的手机号码对应用户角色值是否存在
            $cusRole = Model::ins("CusRole")->getRow(["customerid"=>$cus['id'],"role"=>$role,"enable"=>1],"id");
            
            if(!empty($cusRole['id'])) {
                // 角色存在  提示20001
                return ["code"=>"20001","data"=>"用户角色已存在"];
            }
        }
        return ["code"=>"200"];
    }

    /**
    * @user ajax 提交申请数据
    * @param 
    * @author jeeluo
    * @date 2017年7月24日下午12:03:45
    */
    public function submitDataAction() {
//         $captcha = $this->params["captcha"];
//         $captcha_result = captcha_check($captcha);
//         if(empty($captcha_result))
//             // return captcha_check($captcha);
//             return ["code"=>"400","data"=>"验证码错误"];

        // 截取地区编号
        $area_code_arr = array_filter(explode(",",$this->params['area_code']));
        if(empty($area_code_arr[2])) {
            return ["code"=>"400","data"=>"省市区请选择完整"];
        }
        
        if(phone_filter($this->params['mobile'])) {
            return ["code"=>"20006","data"=>"手机号码不正确"];
        }
        
        // 校验短信验证码
        $params['mobile'] = $this->params['mobile'];
        $valicode = $this->params['valicode'];
        $params['valicode'] = strtoupper(md5($valicode.getConfigKey()));
        
        $cus = new CusCustomerModel();
        $isUser = $cus->compare($params, self::sendRegisterType);
        if(empty($isUser)) {
            return ["code"=>"20005","data"=>"短信验证码错误"];
        }
        
        if(!CommonModel::validation_filter_idcard($this->params['idnumber'])) {
            return ["code"=>"20003","data"=>"身份证号码错误"];
        }
        
        // 提交申请数据
        // 校验当前用户和检验码是否匹配
//         $params['customerid'] = $this->params['customerid'];
//         $params['customercode'] = $this->params['customercode'];

//         if(md5($params['customerid'].getConfigKey()) != $params['customercode']) {
//             return ["code"=>"400","data"=>"数据异常，请重新扫描操作"];
//         }

        $params['role_type'] = $this->params['role_type'];
        // 获取当前用户id值
        $cus = Model::ins("CusCustomer")->getRow(["mobile"=>$this->params['mobile']],"id");
        if(!empty($cus['id'])) {
            $params['customerid'] = $cus['id'];
            // 查看用户是否有对应的角色
            $cusRole = Model::ins("CusRole")->getRow(["customerid"=>$cus['id'],"role"=>$params['role_type']],"id");
            if(!empty($cusRole['id'])) {
                return ["code"=>"20001","data"=>"用户角色已存在"];
            }
        } else {
            // 写入用户表
            $cus_arr = array("mobile" => $this->params['mobile'], "username" => $this->params['mobile'], "createtime" => getFormatNow());
            $returnId = Model::ins("CusCustomer")->add($cus_arr);
            
            // 写入用户详情表
            $cus_login_arr = array("id" => $returnId, "nickname" => $this->params['realname'], "realname"=>$this->params['realname'], "lastlogintime" => getFormatNow(), 
                "idnumber"=>$this->params['idnumber'],"area_code"=>$area_code_arr[2],"address"=>$this->params['address'], "loginnum" => self::initNumber);
            Model::ins("CusCustomerInfo")->add($cus_login_arr);
            
            $cus_role_arr = array("customerid" => $returnId, "role" => self::defaultRole, "addtime" => getFormatNow());
            Model::ins("CusRole")->add($cus_role_arr);
            
            $params['customerid'] = $cusData['customerid'] = $returnId;
            $cusData['userid'] = -1;
            $cusData['checkcode'] = '';
            // 全新的，产生用户 还是关系(牛粉)
            $parentCus = Model::ins("CusCustomer")->getRow(["mobile"=>$this->params['parentMobile']],"id");
            if(!empty($parentCus['id'])) {
                $cusData['userid'] = $parentCus['id'];
                $cusData['checkcode'] = md5($cusData['userid'].getConfigKey());
            }
            
            Model::new("User.Role")->pushCusRelation($cusData);
            //Model::new("Sys.Mq")->submit();
            CommonRoleModel::roleRelationRole($cusData);
        }
        
        
        // 数据无误，获取数据 调用app那边的接口
        $params['version'] = '99.0.0';
        $params['realname'] = $this->params['realname'];
        $params['idnumber'] = $this->params['idnumber'];
        $params['introducermobile'] = $this->params['parentMobile'];
        $params['introducerrole'] = !empty($this->params['introducerrole']) ? $this->params['introducerrole'] : '';
        $params['area'] = $this->params['area'];
        // $params['area_code'] = "440305";
        $params['area_code'] = $area_code_arr[2];
        $params['address'] = $this->params['address'];

        $result = Model::new("User.Role")->beforeApplyExam($params);

        if($result["code"] != "200") {
            return ["code"=>$result["code"],"data"=>$result['data']];
        } else {
            // 顺便检测用户余额是否足够支付
            $payResult = Model::new("Pay.Pay")->checkbalance(["orderno"=>$result['result']['orderno'],"userid"=>$params['customerid']]);
            if($payResult["code"] != "200") {
                return ["code"=>$payResult['code'],"data"=>"操作有误"];
            }
            $result['result']['balance'] = $payResult['data']['balance'];
            $result['result']['issetpaypwd'] = $payResult['data']['issetpaypwd'];
            $result['result']['customerid'] = $params['customerid'];

            return ["code"=>$result["code"],"data"=>$result['result']];
        }
    }

    /**
    * @user ajax 请求余额支付
    * @param 
    * @author jeeluo
    * @date 2017年7月24日下午12:04:00
    */
    public function balancepayAction() {
        $paypwd = $this->params['paypwd'];
        $orderno = $this->params['orderno'];
        $customerid = $this->params['customerid'];
        $customercode = $this->params['customercode'];

        if(empty($paypwd) || empty($orderno) || empty($customerid) || empty($customercode)) {
            return ["code"=>"404","data"=>"参数有误"];
        }
        if(md5($customerid.getConfigKey()) != $customercode) {
            return ["code"=>"400","data"=>"数据异常，请重新扫描操作"];
        }

        $payResult = Model::new("Pay.Pay")->balancepay(["paypwd"=>md5($paypwd),"orderno"=>$orderno,"userid"=>$customerid]);
        
        return ["code"=>$payResult["code"],"data"=>$payResult['msg']];
    }

    /**
     * [check_captcha 验证验证码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-13T19:57:44+0800
     * @return   [type]                   [description]
     */
    public function checkCaptchaAction(){
        $data =  $this->getParam("captcha");
        return  captcha_check($data);
    }


    /**
     * 阿里wap支付
     * @Author   zhuangqm
     * @Datetime 2016-12-26T10:49:39+0800
     * @return   [type]                   [description]
     */
    public function aliwappayorderAction(){

        header("Content-type:text/html;charset=utf-8");
        $amount = $this->params['amount'];
        $openid =  $this->params['openid'];

        $orderno = $this->params['orderno'];

        $customerid = $this->params['customerid'];
      
        //$amount =0.01;
        if(empty($amount) || !is_numeric($amount) || empty($orderno)){
            echo "参数有误";
            exit;
        }

        //判断订单是否存在
        //$order_row = Model::ins("StoPayFlow")->getRow(["pay_code"=>$orderno],"*");
        $order_row = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");
        
        if(empty($order_row)){
            echo "参数有误";
            exit;
        }

        if($order_row['pay_status']>0){
            echo "订单已支付";
            exit;
        }

        if(DePrice($order_row['amount'])!=$amount){
            echo "金额不正确";
            exit;
        }

        //添加支付请求记录
        $result = Model::new("Pay.Pay")->addPayOrder([
                "orderno"=>$orderno,
                "amount"=>$order_row['amount'],
                "pay_type"=>"ali_web",
                "userid"=>$customerid ,
            ]);

        $ali_config = Config::get("pay_ali");

        $AliWap = new AliWap();

        $AliWap->addorder([
                "WIDtotal_amount"=>DePrice($order_row['amount']), //支付金额 '0.01',//
                "WIDout_trade_no"=>$orderno, //订单号
                "WIDsubject"=>"牛人牛达人牛创客申请金额支付",
                "WIDbody"=>"牛店支付",
                "return_url"=>$ali_config['return_becomeTarent_url'].$orderno."&customerid=".$customerid,
                "passback_params"=>urlencode(json_encode(["orderno"=>$orderno])),
                "noityUrl"=>$ali_config['web_noityUrl'],
            ]);
        exit;
    }
    
    /**
    * @user 快捷支付
    * @param 
    * @author jeeluo
    * @date 2017年7月25日下午4:43:26
    */
    public function quickpayAction() {
        $orderno = $this->params['orderno'];
        $customerid = $this->params['customerid'];
        // $customercode = $this->params['customercode'];
        
        if(empty($orderno) || empty($customerid)) {
            echo "参数有误";
            exit;
        }
        
        // 检验用户有效性
        // if(md5($customerid.getConfigKey()) != $customercode) {
        //     echo "用户数据有异";
        //     exit;
        // }
        
        // 判断订单是否存在(查询id customerid addtime pay_status)
        $order = Model::ins("RoleApplyLog")->getRow(["orderno"=>$orderno],"id,customerid,addtime,pay_status,amount");
        if(empty($order)) {
            echo "订单不存在";
            exit;
        }
        
        // 检测订单是否是当前操作用户下的订单
        if($order['customerid'] != $customerid) {
            echo "无操作权限";
            exit;
        }
        
        if($order['pay_status']==1) {
            echo "订单已支付";
            exit;
        }
        
        // 调用快捷支付web接口
        $result = Allinpay::QuickWeb(["orderno"=>$orderno,"pay_price"=>$order['amount'],"userid"=>$customerid,"order_time"=>$order['addtime']]);
        
        echo $result;
        exit;
    }

    /**
     * [callpay 支付]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-12T15:49:39+0800
     * @return   [type]                   [description]
     */
    public function callpayAction(){

        $amount = $this->params['amount'];
        $openid =  $this->params['openid'];

        $orderno = $this->params['orderno'];

        $customerid = $this->params['customerid'];
       
      
        //$amount =0.01;
        if(empty($amount)  || empty($orderno)){
            echo "参数有误";
            exit;
        }
        
        //判断订单是否存在
        $order_row = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");

        if(empty($order_row)){
            echo "参数有误";
            exit;
        }

        if($order_row['pay_status']>0){
            echo "订单已支付";
            exit;
        }

        if(DePrice($order_row['amount'])!=$amount){
            echo "金额不正确";
            exit;
        }
        

        $weixin_config = Config::get("weixin");

        // $opnData = json_decode($opnData,true);
        // 
        //添加支付请求记录
        $result = Model::new("Pay.Pay")->addPayOrder([
                "orderno"=>$orderno,
                "amount"=>$order_row['amount'],
                "pay_type"=>"weixin_web",
                "userid"=>$customerid,
            ]);

        $WeixinWeb = new WeixinWeb();
        $jsApiParameters = $WeixinWeb->getWeixinPay([
                "openid"=>$openid,
                "total_fee"=>$order_row['amount'],//'0.01',//
                "notify_url"=>$weixin_config['notify_url'],
                "body"=>'牛人牛达人牛创客申请金额支付',
                "out_trade_no"=>$orderno,
            ]);

        echo $jsApiParameters;
        exit();
    }

    /**
     * [paysuccessAction 支付成功]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-23T11:42:14+0800
     * @return   [type]                   [description]
     */
    public function paysuccessAction(){
        $orderno = $this->params['orderno'];
      
        if(empty($orderno)){
            echo "参数有误";
            exit;
        }
       

        $viewData = [
            'title' => '支付结果',
            'orderno' => $orderno
        ];

        return $this->view($viewData);
    }
    
    /**
     * [confirmSuccessAction 确认成功页面]
     * @Author   ISir<673638498@qq.com>    jeeluo edit 2017-07-24 11:20:36
     * @DateTime 2017-07-06T17:46:38+0800
     * @return   [type]                   [description]
     */
    public function confirmSuccessAction(){
        $orderno = $this->params['orderno'];
        $customerid = $this->params['customerid'];
        if(empty($orderno) || empty($customerid)) {
            echo "参数有误";
            exit;
        }
        
        // 查询申请表订单数据
        $order = Model::ins("RoleApplyLog")->getRow(["orderno"=>$orderno],"id,customerid,role_type");
        if(empty($order['id'])) {
            echo "订单有误";
            exit;
        }
        
        if($order['customerid'] != $customerid) {
            echo "无操作权限";
            exit;
        }
        
        // 根据申请的角色类型 获取提示内容
        $returnData['roleName'] = '';
        $returnData['cashMoney'] = 0;
        $returnData['profitMoney'] = 0;

        // 牛人牛豆:apply_nr_return   牛粮奖励金apply_nr_bonus_return
        // 牛达人牛豆:apply_nd_return   牛粮奖励金apply_nd_bonus_return
        // 牛创客牛豆:apply_nc_return   牛粮奖励金apply_nc_bonus_return

        $cashProfit = Config::get("cash_profit");
        if($order['role_type'] == 2) {
            $returnData['roleName'] = '牛人';
            $returnData['bullMoney']  = $cashProfit['apply_nr_return'];
            $returnData['bullBonus'] = $cashProfit['apply_nr_bonus_return'];
//             $returnData['cashMoney'] = $cashProfit['role_reco_nr_parent_cash'];
//             $returnData['profitMoney'] = $cashProfit['role_reco_nr_parent_profit'];
        } else if($order['role_type'] == 3) {
            $returnData['roleName'] = '牛创客';
            $returnData['bullMoney'] = $cashProfit['apply_nc_return'];
            $returnData['bullBonus'] = $cashProfit['apply_nc_bonus_return'];
//             $returnData['cashMoney'] = $cashProfit['role_reco_nc_parent_cash'];
//             $returnData['profitMoney'] = $cashProfit['role_reco_nc_parent_profit'];
        } else if($order['role_type'] == 8) {
            $returnData['roleName'] = '牛达人';
            $returnData['bullMoney'] = $cashProfit['apply_nd_return'];
            $returnData['bullBonus'] = $cashProfit['apply_nd_bonus_return'];
//             $returnData['cashMoney'] = $cashProfit['role_reco_nd_parent_cash'];
//             $returnData['profitMoney'] = $cashProfit['role_reco_nd_parent_profit'];
        }
        
        $returnData['bullMoney'] = !empty($returnData['bullMoney']) ? DePrice($returnData['bullMoney']) : '0.00';
//         $returnData['profitMoney'] = !empty($returnData['profitMoney']) ? DePrice($returnData['profitMoney']) : '0.00';
        
        $viewData = [
            'title'=>"提交成功",
            'returnData'=>$returnData,
        ];
        return $this->view($viewData);
    }

    /**
     * [ajaxPayStatusAction ajax获取支付状态]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-15T11:29:05+0800
     * @return   [type]                   [description]
     */
    public function ajaxPayStatusAction(){
        $orderno = $this->params['orderno'];
        $order_row = Model::ins("RoleApplyLog")->getRow(['orderno'=>$orderno],"*");
        //var_dump($order_row);
        $order_row['amount'] = DePrice($order_row['amount']);
        $order_row['pay_amount'] = DePrice($order_row['pay_amount']);
        echo json_encode(['code'=>200,'data'=>$order_row]);
    }

    /**
     * [valicodeAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-20T21:22:14+0800
     * @return   [type]                   [description]
     */
    public function valicodeAction(){
        $role = $this->getParam('role');
        $customerid = $this->getParam('customerid');
        $checkcode = $this->getParam('checkcode');
        $customercode = $this->getParam('customercode');

        $customer = Model::ins('CusCustomer')->getRow(['id'=>$customerid],'id,mobile');
        $realmobile = $customer['mobile'];
        $mobile = substr_replace($customer['mobile'],'****',3,4);
        $viewData = [
            'title' => '验证手机',
            'mobile' => $mobile,
            'realmobile' => $realmobile,
            'customerid' => $customerid,
            'role' => $role,
            'checkcode' => $checkcode,
            'customercode' => $customercode
        ];

        return $this->view($viewData);
    }

    /**
     * [docheckAction 验证验证码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-15T16:04:29+0800
     * @return   [type]                   [description]
     */
    public function checkvalicodeAction(){

        $this->params['valicode'] = strtoupper(md5($this->params['valicode'].getConfigKey()));
        
        //验证手机号码 假如用户已经注册 顺便返回用户id
        $cus = new CusCustomerModel();
       
        $isUser = $cus->compare($this->params, self::sendType);
        if($isUser){
            return $this->json(200);
        }else{
            return $this->json(400);
        }
    }


     /**
     * [changepasswordAction 设置余额支付密码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-15T17:01:00+0800
     * @return   [type]                   [description]
     */
    public function changepasswordAction(){

        $role = $this->getParam('role');
        $customerid = $this->getParam('customerid');
        $checkcode = $this->getParam('checkcode');
        $customercode = $this->getParam('customercode');

        $viewData = [
            'title' => '设置支付密码',
            'customerid' => $customerid,
            'role'  => $role,
            'checkcode' => $checkcode,
            'customercode' => $customercode
        ];

        return $this->view($viewData);
    }


      /**
     * [dochagepasswordAction 修改密码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-15T17:27:23+0800
     * @return   [type]                   [description]
     */
    public function dochagepasswordAction(){
        $customerid = $this->getParam('customerid');
        
        if(empty($this->params['paypwd'])) {
           return $this->json(404);
        }

        if(!preg_match('/^[\d]{6}$/',$this->params['paypwd'])){
            return $this->json(201);
        }
        
        $cusPwd = new CusCustomerPaypwdModel();
        $oldArr = $cusPwd->setId($customerid)->getById($customerid);
      
        if(empty($oldArr)) {
            return $this->json(10003);
        }
        
        $this->params['paypwd'] = md5($this->params['paypwd']);

        $data['paypwd'] = md5($this->params['paypwd'].getConfigKey());
        if($data['paypwd'] != $oldArr['paypwd']) {
            // 操作成功，进行修改数据
            $status = $cusPwd->updatemodify($data, array("id" => $customerid));
            if($status) {

                //清空支付密码的限制
                Model::new("Sys.ActLimit")->del("paypwd".$customerid);

                return $this->json(200);
            }
            return $this->json(400);
        }
        return $this->json(10001);
    }

    /**
    * @user 关系已经建立提示页面
    * @param $role 请求的角色值
    * @author jeeluo
    * @date 2017年7月24日上午11:46:05
    */
    public function roleBuildedTipAction() {
        $role = $this->params['role'];
        
        // 前面已经做角色校验了，这里不再进行处理
        $returnData['content'] = '';
        if($role == 1) {
            $returnData['content'] = '您已建立过牛粉关系，不可再次建立';
        } else if($role == 2) {
            $returnData['content'] = '您已经是牛人身份，不可再次申请';
        } else if($role == 3) {
            $returnData['content'] = '您已经是牛创客身份，不可再次申请';
        } else if($role == 5) {
            $returnData['content'] = '您已经是牛店的粉丝，不可再次绑定';
        } else if($role == 8) {
            $returnData['content'] = '您已经是牛达人身份，不可再次申请';
        }
        
        $viewData = [
            "title"=>"提示",
            "returnData"=>$returnData,
        ];
        return $this->view($viewData);
    }

    /**
    * @user 错误提示页面内
    * @param 
    * @author jeeluo
    * @date 2017年7月24日下午3:17:16
    */
    public function errorAction() {
        $returnData['content'] = $this->params['content'];
        
        $viewData = [
            "title"=>"提示",
            "returnData"=>$returnData,
        ];
        
        return $this->view($viewData);
    }


    /**
     * [captchaAction 验证码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-28T14:13:39+0800
     * @return   [type]                   [description]
     */
    public function captchaAction(){
        $config =    [
            // 验证码字符集合
            'codeSet'  => '1234567890', 
            // 验证码字体大小(px)
            'fontSize' => 25, 
            // 是否画混淆曲线
            'useCurve' => false, 
             // 验证码图片高度
            'imageH'   => 60,
            // 验证码图片宽度
            'imageW'   => 200, 
            // 验证码位数
            'length'   => 4, 
            // 验证成功后是否重置        
            'reset'    => true
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    /**
     * [inviteniufenAction 邀请牛粉]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-18T14:43:35+0800
     * @return   [type]                   [description]
     */
    public function inviteniufenAction(){
        $type = $this->getParam('type');
        $userid = $this->getParam('userid');
        $stocode = $this->getParam('stocode');
        $checkcode = $this->getParam('checkcode');

        $viewData = [
            'title' => "邀请注册",
            'type'  => $type, 
            'userids'  => $userid, 
            'stocode'  => $stocode, 
            'checkcode'  => $checkcode, 
        ];
        return $this->view($viewData);
    }

    /**
     * [doinviteniufenAction 邀请注册]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-18T15:25:48+0800
     * @return   [type]                   [description]
     */
    public function doinviteniufenAction(){

        $time = empty(Cookie::get('time')) ? 1 : Cookie::get('time');
        //$time = empty(Session::get('time')) ? 1 :  Session::get('time');
       

        $type = $this->getParam('type');
        $userid = $this->getParam('userid');
        $stocode = $this->getParam('stocode');
        $checkcode = $this->getParam('checkcode');
        $mobile = $this->getParam('mobile');
        if($type != 3){
            return $this->json(400,[],'推荐类型错误，禁止操作');
        }

        $cus_row = Model::ins('CusCustomer')->getRow(['mobile'=>$mobile],'id');

        if(!empty($cus_row))
            return $this->json(4000,[],'您已经是牛粉，请下载APP');

        if($time >= 3)
            return $this->json(4000,[],'最多只能领取三次');
        

        $login_param['version'] = '99.0.0';
        $login_param['parentid']  = $userid;
        $login_param['stocode']  = $stocode;
        $login_param['checkcode']  = $checkcode;
        $login_param['valicode'] = '170220';
        $login_param['mobile'] = $mobile;
        $login_param['isMobile'] = 1;

        $result = Model::new("User.Login")->login($login_param);
        

        // $login_param['version'] = '1.0.4';
        // $login_param['parentid']  = $userid;
        // $login_param['stocode']  = $stocode;
        // $login_param['checkcode']  = $checkcode;
        // $login_param['valicode'] = '170220';
        // $login_param['mobile'] = $mobile;
        // $result = UserModel::loginOrRegister($login_param);
       
        //$time = Session::set('time',$time);
        $time++;
        $time = Cookie::set('time',$time,3600*24*300);
        return $this->json($result['code'],$result['data'],$result['msg']);
    }

    public function checkrecoroleAction() {
        if(empty($this->params['role'])) {
            return ["code"=>"404","data"=>"数据有误"];
        }
        $data['roleupdateauth'] = 1;
        $data['errormsg'] = '';
        if(!empty($this->params['mobile'])) {
            $result = CommonRoleModel::getShareRoleMobile(["applyRole"=>$this->params['role'],"instoducerMobile"=>$this->params['mobile']]);
            $data['roleupdateauth'] = $result ? 0 : 1;
        }
        
        if($data['roleupdateauth'] == 1) {
            if($this->params['role'] == 2) {
                $data['errormsg'] = '您的分享人不具备创客身份，您可以填写任意一位创客的手机号或者不填';
            } else if($this->params['role'] == 3) {
                $data['errormsg'] = '您的分享人不具备牛创客身份，您可以填写任意一位牛创客的手机号或者不填';
            } else if($this->params['role'] == 8) {
                $data['errormsg'] = '您的分享人不具备牛达人身份，您可以填写任意一位牛达人的手机号或者不填';
            }
        }
        
        return ["code"=>"200","data"=>$data];
    }
}