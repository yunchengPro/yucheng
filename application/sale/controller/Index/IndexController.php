<?php
namespace app\sale\controller\Index;
use app\sale\ActionController;
use app\lib\Model;
use app\model\Sys\CommonModel;

class IndexController extends ActionController
{
	/**
     * 固定不变1
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
        
        
        $title = "登录/注册";
        $redirect_uri = $this->params['redirect_uri'];
        $checkcode = '';
        $recommendid = '';
        
        if(!empty($redirect_uri)) {
            $parameter = explode('&',end(explode('?',$redirect_uri)));
            
            foreach ($parameter as $val) {
                $tmp = explode('=',$val);
                if(!empty($tmp[1])) {
                    $pardata[$tmp[0]] = $tmp[1];
                }
            }
            
            if(!empty($pardata['checkcode'])) {
                $checkcode = $pardata['checkcode'];
                unset($pardata['checkcode']);
            }
            
            if(!empty($pardata['recommendid'])) {
                $recommendid = $pardata['recommendid'];
                unset($pardata['recommendid']);
            }
            
            $url = explode('?',$redirect_uri);

            $redirect_uri = $url[0]."?";
            
            if(!empty($pardata)) {
                foreach ($pardata as $k => $v) {
                    $redirect_uri .= "$k=$v&";
                }
            }
            
//             $checkcode = !empty(($pardata['checkcode'])) ? $pardata['checkcode'] : '';
//             $recommendid = !empty(($pardata['recommendid'])) ? $pardata['recommendid'] : '';
        }
        
        $sendType = 'login_register_';
        $viewData = [
            "title" => $title,
            "redirect_uri" => $redirect_uri,
            "sendType" => $sendType,
            "checkcode" => $checkcode,
            "recommendid" => $recommendid
        ];
        
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
       
        $param['valicode'] = $this->params['valicode'];
     
        
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
        $param['sendType'] = $this->params['sendType'];
        
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
                echo "<script>alert('请正确操作');</script>";
//                 exit("请正确操作");
                exit;
            }
            $customerid = Model::new("User.Open")->getUserid(["openid"=>$this->openid]);
        }
        
        $checkCus = Model::ins("CusCustomer")->getRow(["id"=>$customerid],"enable");
        
        if($checkCus["enable"] == 1) {
            echo "<script>alert('请正确操作');</script>";
            // header('Location:/User/Index/index');
            exit;
//             exit("请正确操作");
        } else if($checkCus["enable"] == 2) {
            echo "<script>alert('用户被禁用');</script>";
            // header('Location:/User/Index/index');
            exit;
//             exit("用户被禁用");
        }
        
        $title = "绑定上级";
        
        $viewData = [
            'title' => $title,
            'customerid' => $customerid
        ];
        
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
        $customerid = $this->params['customerid'];
        $recommendMobile = $this->params['recommendMobile'];
        
        $result = Model::new("User.User")->bindUser(["customerid"=>$customerid,"recommendMobile"=>$recommendMobile]);

        return $this->json($result["code"], $result["data"]);
        
        // return ["code"=>$result["code"],"data"=>$result["data"]];
    }

    public function indexAction(){

       
        //test
        $viewData = [
            
        ];
        return $this->view($viewData);

    }



}
