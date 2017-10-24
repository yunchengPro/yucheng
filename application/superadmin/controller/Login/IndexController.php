<?php
namespace app\superadmin\controller\Login;
use app\superadmin\ActionController;

use \think\Config;
use app\model\BusinessLogin\LoginModel;
use think\Session;
class IndexController extends ActionController
{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function indexAction(){
    	$viewData = ['title'=>"供应商后台管理系统"];
        return $this->view($viewData);
    }

    public function doLoginAction(){
        $loginname = $this->getParam("loginname");
        $password = $this->getParam("password");
        
        $checkData  = LoginModel::check_superadmin_login(array('login_name'=>$loginname,'password'=>$password));
       
        if($checkData['status']){
          
            //设置商家id 用户名 权限id
            // Session::set('businessid',$checkData['userData']['businessid']);
            Session::set('roleid',$checkData['userData']['roleid']);
            Session::set('username',$checkData['userData']['username']);
            Session::set('customerid',$checkData['userData']['id']);
            $this->showSuccessPage($checkData['msg'],'/');
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
            $checkData  = LoginModel::check_superadmin_login(array('login_name'=>$loginname,'password'=>$password));
            
            if($checkData['status'])
                exit(json_encode(true));
            else
                exit(json_encode(false));

        }else{
            exit(json_encode(false));
        }
    }
}
