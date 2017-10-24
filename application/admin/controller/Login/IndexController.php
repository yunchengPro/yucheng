<?php
namespace app\admin\controller\Login;
use app\admin\ActionController;
use app\model\User\UserModel;

use \think\Config;
use app\model\BusinessLogin\LoginModel;
use think\Session;
use app\model\Sys\CommonModel;
use app\lib\Sms;
use app\model\CusCustomerModel;
use app\model\BusBusinessModel;
use app\lib\Model;

class IndexController extends ActionController
{

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
    const oneMaxCount = 5;

     // 消费者角色
    const defaultRole = 1;
    
    const companyType = -1;

    // 注册手机操作类型前缀
    const sendType = "forgetPwd_";

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function indexAction(){
    	$viewData = ['title'=>"牛商后台管理系统"];
        return $this->view($viewData);
    }

 
    public function doLoginAction(){
        $loginname = $this->getParam("loginname");
        $password = $this->getParam("password");
        $checkData  = LoginModel::check_login(array('login_name'=>$loginname,'password'=>$password));
       
        if($checkData['status']){

            //设置商家id 用户名 权限id
            Session::set('businessid',$checkData['userData']['businessid']);
            Session::set('business_roleid',$checkData['userData']['business_roleid']);
            Session::set('username',$checkData['userData']['username']);
            Session::set('customerid',$checkData['userData']['id']);

            $loginLog = LoginModel::checkLog(['customerid'=>$checkData['userData']['id']]);
            if($loginLog['status']){

                $this->showSuccessPage($loginLog['msg'],'/login/index/setPwd');
            }else{
                LoginModel::addLoginLog(["customerid"=>$checkData['userData']['id'],"mtoken"=>$checkData['userData']['mtoken']]);
                $this->showSuccessPage($checkData['msg'],'/');
                
            }

           
        }else{
             $this->showErrorPage($checkData['msg'],'/Login');
        }
    }

    /**
     * [loginOutAction 退出登录]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-13T20:46:05+0800
     * @return   [type]                   [description]
     */
    public function loginOutAction(){
        Session::set('businessid','');
        Session::set('business_roleid','');
        Session::set('username','');
        Session::set('customerid','');
        $this->showSuccessPage('成功退出','/login');

    }

    /**
     * [setPwdAction 重置密码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-10T14:07:37+0800
     */
    public function setPwdAction(){
        $businessid = Session::get('businessid');
        if(empty($businessid))
            $this->showErrorPage('请重新登录','/login');

        $userObj = new UserModel();
        $userData = $userObj->getBusinessCus($businessid);

        if(empty($userData))
            $this->showErrorPage('账号不存在,请重新登录','/login');

        $viewData = ['title'=>"重置密码","userData"=>$username];

        return $this->view($viewData);
    }

    /**
     * [setAdminPwdAction 修改密码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-13T21:10:05+0800
     */
    public function setAdminPwdAction(){

        $view = [
            'action' => '/Login/Index/doSetpwdAdmin'
        ];
        return $this->view($view);
    }

    /**
     * [doSetpwdAction 设置新密码动作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-10T14:25:22+0800
     * @return   [type]                   [description]
     */
    public function doSetpwdAdminAction(){
        $businessid = Session::get('businessid');
        if(empty($businessid))
            $this->showErrorPage('请重新登录','/login');

        $userObj = new UserModel();
        $userData = $userObj->getBusinessCus($businessid);

        if(empty($userData))
            $this->showErrorPage('账号不存在,请重新登录','/login');

        $newPwd = $this->getParam("newpwd");
        $newPwd2 = $this->getParam("newpwd2");

        if(empty($newPwd))
            $this->showError('设置密码不能为空');
        
        if(empty($newPwd2)) 
            $this->showError('重复密码不能为空');

        if($newPwd != $newPwd2)
            $this->showError('两次输入密码不一样');

        $data = $userObj->updatePassword(['pwd'=>$newPwd,'customerid'=>$userData['id']]);
        if($data >0 ){
            $this->showSuccess('成功修改,请牢记新密码以便下次登录！','/');
        }else{
            $this->showError('修改错误','/');
        }
    }


    /**
     * [doSetpwdAction 设置新密码动作]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-10T14:25:22+0800
     * @return   [type]                   [description]
     */
    public function doSetpwdAction(){
        $businessid = Session::get('businessid');
        if(empty($businessid))
            $this->showErrorPage('请重新登录','/login');

        $userObj = new UserModel();
        $userData = $userObj->getBusinessCus($businessid);
       
        if(empty($userData))
            $this->showErrorPage('账号不存在,请重新登录','/login');

        $newPwd = $this->getParam("newpwd");
        $newPwd2 = $this->getParam("newpwd2");
        if(empty($newPwd))
            $this->showErrorPage('密码不能为空','/login/index/setPwd');

         if(empty($newPwd2))
            $this->showErrorPage('确认密码不能为空','/login/index/setPwd');

        if($newPwd != $newPwd2)
            $this->showErrorPage('两次输入密码不一样','/login/index/setPwd');

        $data = $userObj->updatePassword(['pwd'=>$newPwd,'customerid'=>$userData['id']]);
        if($data >0 ){


            LoginModel::addLoginLog(["customerid"=>$userData['id'],"mtoken"=>$newPwd]);
            $this->showSuccessPage('成功修改','/login');
        }else{
            $this->showErrorPage('修改错误,请重新登录','/login');
        }
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
     * [checkLoginAction js验证用户名密码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-13T20:45:35+0800
     * @return   [type]                   [description]
     */
    public function checkLoginAction(){

        $loginname = $this->getParam("loginname");
        $password = $this->getParam("password");
      
        
        if($loginname!='' && $password!=''){
            $checkData  = LoginModel::check_login(array('login_name'=>$loginname,'password'=>$password));
            
            if($checkData['status'])
                exit(json_encode(true));
            else
                exit(json_encode($checkData));

        }else{
            exit(json_encode(false));
        }
    }

    /**
     * [forgetPwdAction 忘记密码]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-10T22:26:01+0800
     * @return   [type]                   [description]
     */
    public function forgetPwdAction(){
        return $this->view();
    }

    /**
     * [doforgetPwdAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-11T16:36:00+0800
     * @return   [type]                   [description]
     */
    public function doforgetPwdAction(){
        $mobile = $this->params['mobile'];
        $valicode = $this->params['valicode'];
        $customerOBJ = new CusCustomerModel();
       
        $cusData = $customerOBJ->getRow(['mobile'=>$mobile],'id');
        
        $busOBj = new BusBusinessModel();
        if(empty($cusData))
            $this->showErrorPage('账号信息不存在','/Login/Index/forgetPwd');
        
        $arr = [
            'valicode' => strtoupper(md5($valicode.getConfigKey())),
            'mobile'  => $mobile
        ];

        $compare = $customerOBJ->compare($arr,self::sendType);
       
        if(!$compare)
            $this->showErrorPage('验证码错误','/Login/Index/forgetPwd');

        $busData = $busOBj->getRow(['customerid'=>$cusData['id']],'id');
        if(empty($busData))
            $this->showErrorPage('商家账号信息错误','/Login/Index/forgetPwd');
        
        Session::set('customerid',$cusData['id']);
        Session::set('businessid',$busData['id']);
        $this->showSuccessPage('请设置密码','/Login/index/setPwd');
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
            echo $this->json(20006);
             exit();
        }
     
        // 获取cache内的次数信息
        //$countNumber = CommonModel::getCacheNumber(self::sendType.$this->params['mobile']);
        
        $countNumber = self::initNumber;
        $MessageRedis = Model::Redis("MessageValicode");

        if($MessageRedis->exists(CommonModel::getDeviceProfix().$this->params['devicenumber'])) {
            $countNumber = $MessageRedis->get(CommonModel::getDeviceProfix().$this->params['devicenumber']);
        }

        // if($countNumber >= CommonModel::getMaxDevice()) {
        //     echo $this->json(405);
        //     exit();
        // }

        $randNumber = getRandNumber(self::minRand, self::maxRand);
        
        $result['valicode'] = 0;
        if(Sms::send("$mobile", ["$randNumber", self::minute])) {
            echo $this->json(2001);
             exit();
        } else {
            // 验证码发送成功
            // 设置valicode的cache
            //var_dump(CommonModel::setCacheNumber(self::sendType.$mobile, $result['valicode'], self::minute * self::minuteToSecond));
            $MessageRedis->set(self::sendType.$mobile, $randNumber, self::minute*self::minuteToSecond);

            $MessageRedis->set(CommonModel::getDeviceProfix().$this->params['devicenumber'], ++$countNumber, strtotime(date('Y-m-d', time()+self::oneDaySecond))-time());

            // 设置手机今天发送验证的次数
            //CommonModel::setCacheNumber(self::sendType.$this->params['devicenumber'], ++$countNumber, strtotime(date('Y-m-d', time()+self::oneDaySecond)) - time());
        }
        echo $this->json(200);
        exit();
    }
}
