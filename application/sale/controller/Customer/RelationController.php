<?php
// +----------------------------------------------------------------------
// |  [ 用户关系 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年7月6日11:49:06}}
// +----------------------------------------------------------------------
namespace app\mobile\controller\Customer;
use app\mobile\ActionController;

use app\api\model\User\LoginFactory;
use app\model\User\TokenModel;
use app\model\CusCustomerModel;
use app\model\CusCustomerInfoModel;
use app\model\CusMtokenModel;
use app\model\Sys\CommonModel;
use app\model\Sys\CommonRoleModel;
use app\model\CusCustomerLoginModel;
use app\model\CusRoleModel;
use app\lib\Model;
use think\Config;
use think\Cookie;
use app\model\StoBusiness\StobusinessModel;

class RelationController extends ActionController{

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
    const sendType = "wap_register_";


	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function buildRelationshipsAction(){
        $userid = $this->params['userid'];
        $stocode = $this->params['stocode'];
        $checkcode = $this->params['checkcode'];
        $role = $this->params['role'];
    	$viewData = [
    		'tittle' => '牛店关系建立',
            'userid' => $userid,
            'checkcode' => $checkcode,
            'stocode' => $stocode,
            'role' => $role
    	];
    	return $this->view($viewData);
    }



    /**
     * [dobuildAction 绑定信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-06T14:23:47+0800
     * @return   [type]                   [description]
     */
    // public function dobuildAction(){

    //     if(empty($this->params['mobile'])) {
    //         return $this->json('404',[],'参数错误');
    //         //$this->showErrorPage('参数错误','/Index/Index/login?'.$paramString);
    //     }
        
    //     if(phone_filter($this->params['mobile'])) {
    //         return $this->json('404',[],'参数错误');
    //         // 手机验证不通过
    //         //$this->showErrorPage('请填写正确的手机号码','/Index/Index/login?'.$paramString);
    //     }
        
    //     if(empty($this->params['roleid']) || empty($this->params['userid'])) {
    //         $content = '参悟有误，请重新扫描';
    //         $url = '/Customer/Index/error?content='.$content;
    //         return $this->json('200',['data'=>urldecode($url)],'操作成功');
    //         // 数据异常
    //         // header('Location: /Customer/Index/error?content='.$content);

    //         // echo "<script type='text/javascript'>window.location.href='/Customer/Index/error?content='.$content.';</script>";
    //         // exit;
    //     }

    //       // 确保引荐人数据正确
    //     if(md5($this->params['userid'].getConfigKey()) != $this->params['checkcode']) {
    //         $content = '引荐人数据有异，请重新扫描';
    //         $url = '/Customer/Index/error?content='.$content;
    //         return $this->json('200',['data'=>urldecode($url)],'操作成功');
    //         // header('Location: /Customer/Index/error?content='.$content);
    //         // echo "<script type='text/javascript'>window.location.href='/Customer/Index/error?content='.$content.';</script>";
    //         // exit;
    //     }

    //     $this->params['roleid'] = empty($this->params['roleid']) ? 5 : $this->params['roleid'];

    //     $this->params['valicode'] = strtoupper(md5($this->params['valicode'].getConfigKey()));

    //       //验证手机号码 假如用户已经注册 顺便返回用户id
    //     $cus = new CusCustomerModel();
       
    //     $isUser = $cus->compare($this->params, self::sendType);
    //     //var_dump($isUser);
    //     if($isUser) {
    //         $now = date('Y-m-d H:i:s', time());
    //         // 验证码正确
    //         $loginActioin = LoginFactory::loginRegister($isUser);
    //         if($loginActioin->login($this->params)) {

    //             $customerInfo = new CusCustomerInfoModel();
    //             $returnId = self::initNumber;
    //             $result['mtoken'] = self::initNumber;
               
    //             if(is_array($isUser)) {
    //                 // 登录模式(号码存在，令牌不相等)
    //                    // 查询登录用户是否已经有此角色值
                    

    //                 $cusInfo = $cus->getRow(array("id" => $isUser['id']), "enable");
    //                 if($cusInfo['enable'] == self::disenable) {
    //                     // 用户被禁用
    //                     return $this->json('404',[],'您被禁用，请联系客服');
    //                     //$this->showErrorPage("您被禁用，请联系客服", '/Index/Index/login?'.$paramString);
    //                 }
    //                 $cusRole = Model::ins("CusRole")->getRow(["customerid"=>$isUser['id'],"role"=>$this->params['roleid']]);
    //                 if(!empty($cusRole)) {
    //                     $url = '/Customer/Index/roleBuildedTip?role='.$this->params['roleid'];
    //                     return $this->json('200',['data'=>urldecode($url)],'操作成功');
    //                     // 跳转到提示页面
    //                     // header('Location: /Customer/Index/roleBuildedTip?role='.$this->params['roleid']);
    //                     // echo "<script type='text/javascript'>window.location.href='/Customer/Index/roleBuildedTip?role='.$role.';</script>";
    //                     // exit;
    //                 }
                 
    //                 // 查询登录信息
    //                 $infoNumArr = $customerInfo->setId($isUser['id'])->getLoginById();
    //                 $info_arr = array("lastlogintime" => $now, "loginnum" => $infoNumArr['loginnum']+1, "id" => $isUser['id']);
    //                 $customerInfo->modifyUpdate($info_arr, "id = ".$isUser['id']);
                    
    //                 $mtoken = new CusMtokenModel();
                    
    //                 $result['mtoken'] = TokenModel::buildToken($returnId);
    //                 $result['role'] = CommonModel::getNowCusRole(array("customerid" => $isUser['id']));
                    
    //                 $this->params = array_merge($this->params, array("customerid" => $isUser['id']));

    //                 Model::new("User.Role")->pushCusRelation($this->params);

    //                 // $cus_role_arr = array("customerid" => $isUser['id'], "role" => $this->params['roleid'], "addtime" => getFormatNow());
    //                 // $cus_role = new CusRoleModel();
    //                 // $cus_role->add($cus_role_arr);
                    
    //                 $role_status = CommonRoleModel::roleRelationRole($this->params);
                 
    //                 $returnId = $isUser['id'];
    //                 $loginType = 'login';
    //                 $url = '/Customer/Relation/buildsuccess?type=login';

    //             } else {
                      
    //                 // 注册模式(号码不存在，令牌不存在)
    //                 // 写入用户表
    //                 $cus_arr = array("mobile" => $this->params['mobile'], "username" => $this->params['mobile'], "createtime" => $now);
    //                 $returnId = $cus->add($cus_arr);
    //                 // 写入用户详情表
    //                 $cus_login_arr = array("id" => $returnId, "nickname" => "匿名", "lastlogintime" => $now, "loginnum" => self::initNumber);
    //                 $customerInfo->add($cus_login_arr);



    //                 $this->params = array_merge($this->params, array("customerid" => $returnId));

    //                 Model::new("User.Role")->pushCusRelation($this->params);
                  
    //                 $cus_role_arr = array("customerid" => $returnId, "role" => self::defaultRole, "addtime" => getFormatNow());
    //                 $cus_role = new CusRoleModel();
    //                 $cus_role->add($cus_role_arr);
                    
    //                 // 添加分润关系表数据
    //                 $role_status = CommonRoleModel::roleRelationRole($this->params);
    //                 // if($role_status["code"] != 200) {
    //                 //     return $this->json($role_status["code"]);
    //                 // }
                    
    //                 $result['mtoken'] = TokenModel::buildToken($returnId);
    //                 $result['role'] = self::defaultRole;
    //                 // 写入用户登录令牌表

                    
    //                 $token_add = array("id" => $returnId, "mtoken" => $result['mtoken'], "devicetoken" => $this->params['devicetoken'], "devtype" => $this->params['devtype'],
    //                     "addtime" => $now);
    //                 $mtoken = new CusMtokenModel();
    //                 $mtoken->add($token_add);
    //                 $loginType = 'register';
    //                 $url = '/Customer/Relation/buildsuccess?type=register';
    //             }

    //             // 修改令牌登录
    //             $token_login = array("customerid" => $returnId, "mtoken" => $result['mtoken'], "devicetoken" => $this->params['devicetoken'], "devtype" => $this->params['devtype'],
    //                 "logintime" => $now);
    //             $customerLogin = new CusCustomerLoginModel();
    //             $customerLogin->add($token_login);

              
    //             //print_r($this->params);
    //             // if(!empty($this->params['url']))
    //             //     $this->params['url'] = $this->params['url'] . '&customerid='.$returnId;

               
                
    //             return $this->json('200',['data'=>urldecode($url)],'操作成功');
    //             //header('Location: '.urldecode($url));
    //             //$this->showSuccessPage('操作成功',$url);
               

    //         }

    //     }else{
    //         return $this->json('404',[],'验证码错误');
    //     }
    // }

    public function dobuildAction(){
        if(empty($this->params['mobile'])) {
            return $this->json('404',[],'参数错误');
        }
        
        if(phone_filter($this->params['mobile'])) {
            return $this->json('404',[],'参数错误');
        }
        
        if(empty($this->params['roleid']) || empty($this->params['userid'])) {
            $content = '参悟有误，请重新扫描';
            $url = '/Customer/Index/error?content='.$content;
            return $this->json('200',['data'=>urldecode($url)],'操作成功');
        }

          // 确保引荐人数据正确
        if(md5($this->params['userid'].getConfigKey()) != $this->params['checkcode']) {
            $content = '引荐人数据有异，请重新扫描';
            $url = '/Customer/Index/error?content='.$content;
            return $this->json('200',['data'=>urldecode($url)],'操作成功');
        }
        
        $loginType = 'register';
        // 查询填写的手机号码 是否已经有对应角色
        $user = Model::ins("CusCustomer")->getRow(["mobile"=>$this->params['mobile']],"id");
        if(!empty($user['id'])) {
            $cusRole = Model::ins("CusRole")->getRow(["customerid"=>$user['id'],"role"=>$this->params['roleid']]);
            if(!empty($cusRole)) {
                $url = '/Customer/Index/roleBuildedTip?role='.$this->params['roleid'];
                return $this->json('200',['data'=>urldecode($url)],'操作成功');
            }
            $loginType = 'login';
        }
        
        // 判断是否有openid
        // if(!empty($this->openid)) {
        //     // 假如有 执行微信第三方登录
        //     $wparams['openid'] = $this->openid;
        //     // 查询第三方登录数据 在数据库表中 是否存在
        //     $customerOpenInfo = Model::ins("CusCustomerOpen")->getRow(["openid"=>$this->openid],"id,customerid,openid,open_type,
        //                         nickname,sex,province,city,country,headimgurl,privilege,unionid,open_type");
        //     if(empty($customerOpenInfo['id'])) {
        //         // 写入记录到数据库中
        //         $wparams['nickname'] = $this->params['nickname']?:'';
        //         $wparams['sex'] = $this->params['sex'] ?: 0;
        //         $wparams['province'] = $this->params['province'];
        //         $wparams['city'] = $this->params['city'];
        //         $wparams['country'] = $this->params['country'];
        //         $wparams['headimgurl'] = $this->params['headimgurl'];
        //         $wparams['privilege'] = $this->params['privilege'];
        //         $wparams['unionid'] = $this->params['unionid'];
        //         $wparams['open_type'] = CommonModel::getWeixinWebType();
        //         $wparams['addtime'] = getFormatNow();
        //         Model::ins("CusCustomerOpen")->insert($wparams);
        //     } else {
        //         $wparams['nickname'] = $this->params['nickname'] ? $this->params['nickname']:$customerOpenInfo['nickname'];
        //         $wparams['sex'] = $this->params['sex'] ? $this->params['sex']:$customerOpenInfo['sex'];
        //         $wparams['province'] = $this->params['province'] ? $this->params['province']:$customerOpenInfo['province'];
        //         $wparams['city'] = $this->params['city'] ? $this->params['city']:$customerOpenInfo['city'];
        //         $wparams['country'] = $this->params['country'] ? $this->params['country']:$customerOpenInfo['country'];
        //         $wparams['headimgurl'] = $this->params['headimgurl'] ? $this->params['headimgurl']:$customerOpenInfo['headimgurl'];
        //         $wparams['privilege'] = $this->params['privilege'] ? $this->params['privilege']:$customerOpenInfo['privilege'];
        //         $wparams['unionid'] = $this->params['unionid'] ? $this->params['unionid']:$customerOpenInfo['unionid'];
        //         $wparams['open_type'] = CommonModel::getWeixinWebType();
        //         Model::ins("CusCustomerOpen")->update($wparams,["id"=>$customerOpenInfo['id']]);
        //     }
        // }
        
        $param['version'] = '99.0.0';
        $param['stocode'] = !empty($this->params['stocode']) ? $this->params['stocode'] : '';
        $param['parentid'] = $this->params['userid'];
        $param['checkcode'] = $this->params['checkcode'];
        $param['roleid'] = empty($this->params['roleid']) ? 5 : $this->params['roleid'];
        $param['valicode'] = strtoupper(md5($this->params['valicode'].getConfigKey()));
        $param['mobile'] = $this->params['mobile'];
        // $param['openid'] = !empty($this->openid) ? $this->openid : '';
        $param['devicetoken'] = $this->params['devicetoken'];
        $param['devtype'] = $this->params['devtype'];
        $param['isMobile'] = 1;
        
        $result = Model::new("User.Login")->login($param);
        
        if($result["code"] == "200") {
            // 根据返回的手机号码，查找用户id值
            $cus = Model::ins("CusCustomer")->getRow(["mobile"=>$result['data']['mobile']],"id");
            
            // 更新实体店牛粉数
            // $sto = Model::ins("StoBusinessCode")->getRow(["business_code"=>$param['stocode']],"businessid");
            // if(!empty($sto['businessid'])) {
            //     StobusinessModel::updateFanscount(["businessid"=>$sto['businessid']]);
            // }
            
            // 登录成功，设置cookie
            // Cookie::set('customerid',$cus['id'],3600*24*300);
            // Cookie::set('mtoken',md5($cus['id'].getConfigKey()),3600*24*300);
            
            $url = '/Customer/Relation/buildsuccess?type='.$loginType;
            
            return $this->json('200',['data'=>urldecode($url)],'操作成功');
        }
        return $this->json($result['code'],[],$result['msg']);
    }

    /**
     * [buildalreadyAction 已经绑定关系]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-07-06T14:55:42+0800
     * @return   [type]                   [description]
     */
    public function buildalreadyAction(){
        $viewData = [
            'tittle' => '已经建立牛店关系',
        ];
        return $this->view($viewData);
    }

    public function buildsuccessAction(){
        $type = $this->getParam('type');

        if($type =='register'){
            $config = Config::get('cash_profit');
            //牛粮奖励金
            $share_recoed_bonus_return = SubstrPrice(DePrice($config['share_recoed_bonus_return']));
            //牛豆
            $share_recoed_bull_return = SubstrPrice(DePrice($config['share_recoed_bull_return']));

        }else{
            $share_recoed_bonus_return = 0;
            $share_recoed_bull_return = 0;
        }
        $viewData = [
            'tittle' => '成功建立牛店关系',
            'share_recoed_bonus_return' => $share_recoed_bonus_return,
            'share_recoed_bull_return'  => $share_recoed_bull_return
        ];
        return $this->view($viewData);
    }

  
}