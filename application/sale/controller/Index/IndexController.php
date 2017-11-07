<?php
namespace app\sale\controller\Index;
use app\sale\ActionController;
use app\lib\Model;
use app\model\Sys\CommonModel;
use app\model\Sys\BannerModel;
use app\model\Product\ProductModel;

class IndexController extends ActionController
{
    
    private $loginType = ["1","2"];
	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    
    /**
    * @user 登录/注册页面
    * @param 
    * @author jeeluo
    * @date 2017年10月18日上午10:21:07
    */
    public function loginAction() {
        
        if(!empty($this->userid)){
            header('Location:/User/Index/index');
        }
        $title = "登录/注册";

        $viewData = [
            "title" => $title,
            "redirect_uri" => $redirect_uri,
//             "sendType" => $sendType,
            "recommendid" => $recommendid
        ];
        
        //$this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 密码登录页面
    * @param 
    * @author jeeluo
    * @date 2017年10月31日下午5:18:48
    */
    public function loginbypwdAction() {
        
        $title = "密码登录";
        $checkcode = $this->params['checkcode'];
        $recommendid = $this->params['recommendid'];
        $viewData = [
            "title" => $title,
            "checkcode" => $checkcode,
            "recommendid" => $recommendid
        ];
        
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 登录/注册操作
    * @param 
    * @author jeeluo
    * @date 2017年10月18日上午10:21:28
    */
    public function dologinAction() {
        
        

        $param['mobile'] = $this->params['mobile'];
       
        // $param['valicode']  = $this->params['valicode'];
        $param['valicode'] = $this->params['valicode'];
        
        if(empty($param["mobile"]) || empty($param['valicode'])) {
            return $this->json("404");
        }
        
  
        
        // 执行登录/注册
        $result = Model::new("User.User")->login($param);
        
        return $this->json($result['code'],$result['data'],$result['msg']);
    }
    
    /**
    * @user 发送短信验证码
    * @param 
    * @author jeeluo
    * @date 2017年10月10日下午7:51:52
    */
    public function sendValicodeAction() {
        $param['mobile'] = $this->params['mobile'];
        $param['privatekey'] = md5($param['mobile'].getConfigKey());
        $param['sendType'] = "login_register_";
        $param['devicenumber'] = 'device'.$param['mobile'];
        
        $result = CommonModel::sendValidate($param);
        return $this->json($result['code']);
    }
    
    public function sendloginpwdAction() {
        $param['mobile'] = $this->params['mobile'];
        $param['privatekey'] = md5($param['mobile'].getConfigKey());
        $param['sendType'] = "update_loginpwd_";
        $param['devicenumber'] = 'device'.$param['mobile'];
        
        $result = CommonModel::sendValidate($param);
        return $this->json($result['code']);
    }
    
    /**
    * @user 注册用户 用户绑定手机页面
    * @param 
    * @author jeeluo
    * @date 2017年10月18日上午11:50:47
    */
    public function bindMobileAction() {
        $customerid = $this->params['customerid'];
        
        if(empty($customerid)) {
            if(empty($this->openid)) {
                header('Content-type:application/json;charset=utf-8');
                exit($this->json("408",[],"请求无效，请正确操作"));
                // echo "<script>alert('请正确操作');</script>";
                // exit;
            }
            $customerid = Model::new("User.Open")->getUserid(["openid"=>$this->openid]);
        }
        
        $checkCus = Model::ins("CusCustomer")->getRow(["id"=>$customerid],"enable");
        
        if($checkCus["enable"] == 1) {
            header('Content-type:application/json;charset=utf-8');
            exit($this->json("408",[],"请求无效，请正确操作"));
            // echo "<script>alert('请正确操作');</script>";
            // exit;
//             exit("请正确操作");
        } else if($checkCus["enable"] == 2) {
            header('Content-type:application/json;charset=utf-8');
            exit($this->json("408",[],"请求无效，用户被禁用"));
            // echo "<script>alert('用户被禁用');</script>";
            // exit;
//             exit("用户被禁用");
        }
        
        $title = "填写邀请人";
        
        $UserModel = Model::new("User.User");
        $userInfo = $UserModel->userInfo($customerid);
        $mobile = $userInfo['completemobile'];
        $encrypt = md5($mobile.getConfigKey());
        
        $viewData = [
            'title' => $title,
            'customerid' => $customerid,
            'mobile' => $mobile,
            'encrypt' => $encrypt
        ];
        
        $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 绑定用户操作
    * @param $customerid 用户id值
    * @param $mobile 手机号码
    * @author jeeluo
    * @date 2017年10月18日下午3:17:05
    */
    public function bindRecomendAction() {
        $this->checktokenHandle();
        $customerid = $this->params['customerid'];
        $recommendMobile = $this->params['recommendMobile'];
        
        $result = Model::new("User.User")->bindUser(["customerid"=>$customerid,"recommendMobile"=>$recommendMobile]);

        return $this->json($result["code"], $result["data"]);
        
        // return ["code"=>$result["code"],"data"=>$result["data"]];
    }

    public function indexAction(){

        // header('Location:/User/Index/index');
        // exit;

        $viewData = [
            
        ];
        return $this->view($viewData);

    }

    /**
     * [getbannerlistAction 获取轮播图数据]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-21T10:27:14+0800
     * @return   [type]                   [description]
     */
    public function getbannerlistAction(){
        $result = BannerModel::bannerList();
      
        return $this->json($result['code'],$result['data']);
    }

    /**
     * [getcategorylistAction 获取首页推荐分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-21T11:08:09+0800
     * @return   [type]                   [description]
     */
    public function getcategorylistAction(){
        $result = ProductModel::getCategoryData();
        return $this->json($result['code'],$result['data']);
    }

    /**
     * [getonerecomAction 获取推荐商品]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-21T11:27:38+0800
     * @return   [type]                   [description]
     */
    public function getonerecomAction(){
        $result = ProductModel::getOneRecommendProduct();
        return $this->json($result['code'],$result['data']);
    }

    /**
     * [goodsListAction 商品列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-09T17:04:57+0800
     * @return   [type]                   [description]
     */
    public function goodslistAction(){
        $categoryid = $this->getParam('categoryid');
        if($categoryid>0){
            $param['where']['categoryid'] = $categoryid;
        }
        $param['where']['enable'] = 1;
        $param['where']['checksatus'] = 1;

       
        $list = ProductModel::ProductList($param);
        $maxPage = ceil($list['data']['total']/20);
        $list['data']['maxPage'] =  $maxPage;
        return $this->json($list['code'],$list['data']);
    }

    /**
     * [downAction 下载app]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-26T14:17:59+0800
     * @return   [type]                   [description]
     */
    public function downloadAction(){
        $viewData = [
            'title' => '牛牛汇商家下载'
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 验证手机号码
    * @param 
    * @author jeeluo
    * @date 2017年11月1日下午3:05:47
    */
    public function validloginpwdAction() {
    
        $title = "验证手机";
        $recommendid = $this->params['recommendid'];
        $checkcode = $this->params['checkcode'];
        
        $viewData = [
            "title" => $title,
            "recommendid" => $recommendid,
            "checkcode" => $checkcode
        ];
        
        $this->addcheck();
        
        return $this->view($viewData);
    }
    
    /**
    * @user 校验找回密码 修改密码 短信验证
    * @param 
    * @author jeeluo
    * @date 2017年11月1日下午3:41:27
    */
    public function validateloginpwdAction() {
        $this->checktokenHandle();
        
        $param['mobile'] = $this->params['mobile'];
        $param['valicode'] = strtoupper(md5($this->params['valicode'].getConfigKey()));
        
        // 校验
        $result = Model::new("User.Setting")->validLoginPwd($param);
        
        return $this->json($result["code"], $result["data"]);
    }
    
    /**
    * @user 找回密码设置页面
    * @param 
    * @author jeeluo
    * @date 2017年11月1日下午3:46:13
    */
    public function updateloginnumberAction() {
        
        $title = "设置登录密码";
        $recommendid = $this->params['recommendid'];
        $checkcode = $this->params['checkcode'];
        $mobile = $this->params['mobile'];
        $encrypt = $this->params['encrypt'];
        $returnType = $this->params['returnType'];
        
        $viewData = [
            "title" => $title,
            "mobile" => $mobile,
            "encrypt" => $encrypt,
            "recommendid" => $recommendid,
            "checkcode" => $checkcode,
            "returnType" => $returnType
        ];
        
        $this->addcheck();
        return $this->view($viewData);
    }
    
    /**
    * @user 找回登录密码/修改登录密码  操作
    * @param 
    * @author jeeluo
    * @date 2017年11月1日下午5:11:39
    */
    public function updateloginpwdAction() {
        
        $this->checktokenHandle();
        
        $loginpwd = $this->params['loginpwd'];
        if(empty($loginpwd)) {
            return $this->json("404");
        }
        if(!CommonModel::validate_filter_loginpwd($loginpwd)) {
            return $this->json("2007");
        }
        
        $param['mobile'] = $this->params['mobile'];
        $param['encrypt'] = $this->params['encrypt'];
        $param['loginpwd'] = md5($this->params['loginpwd']);
        $param['confirmpwd'] = md5($this->params['confirmpwd']);
        
        // 操作
        $result = Model::new("User.Setting")->updateLoginPwd($param);
        
        return $this->json($result["code"]);
    }
}
