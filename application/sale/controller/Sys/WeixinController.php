<?php
// +----------------------------------------------------------------------
// |  [ 实体店支付提交付款页面 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{datetime}}
// +----------------------------------------------------------------------


namespace app\sale\controller\Sys;
use app\sale\ActionController;

use think\Config;

use app\lib\Model;

use \think\Cookie;

class WeixinController extends ActionController{

    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [setpayamountAction 设置金额]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-23T11:20:53+0800
     * @return   [type]                   [description]
     */
    public function oauthAction(){

        $redirect_uri = $this->params['redirect_uri'];

        $weixin_config = Config::get("weixin");

        $domain_config = Config::get("mobiledomain");

        $redirect_uri = $domain_config['Domain'].$redirect_uri;

        $redirect_uri = $weixin_config['oauth_url']."Sys/Weixin/urlto?urlto=".urlencode($redirect_uri);

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$weixin_config['appid']."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_base#wechat_redirect";

        echo $url;
        exit;
        header('Location:'.$url);

        exit;

    }

    public function urltoAction(){
        // echo "aaaaa";
        // echo "-------------------";
        // print_r($this->params);

        $code = $this->params['code'];
        $urlto = $this->params['urlto'];

        if(empty($code) || empty($urlto))
            exit("参数有误-201");

        $weixin_config = Config::get("weixin");
        $openData = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$weixin_config['appid'].'&secret='.$weixin_config['appsecret'].'&code='.$code.'&grant_type=authorization_code');

        //var_dump($openData);
        $openData = json_decode($openData,true);
        // print_r($openData);

        $openid = $openData['openid'];

        // echo "openid:".$openid."<br>";
        if(!empty($openid)){
            //$urlto.=strpos($urlto,"?")!==false?"&openid=".$openid:"?openid=".$openid."&version=".rand(1000,9999);

            Cookie::set('openid',$openid,3600*24*300);
            // echo "urlto:".$urlto;
            // 
            $urlto = $urlto==''?"/":$urlto;

            header('Location:'.$urlto);
        }else{
            print_r($openData);
        }
        exit;
    }

    /**
     * 授权获取用户详细信息
     * @Author   zhuangqm
     * @DateTime 2017-09-27T15:23:55+0800
     * @return   [type]                   [description]
     */
    public function oauthinfoAction(){

        $redirect_uri = $this->params['redirect_uri'];

        $weixin_config = Config::get("weixin");

        $domain_config = Config::get("mobiledomain");

        $redirect_uri = $domain_config['Domain'].$redirect_uri;

        $redirect_uri = $weixin_config['oauth_url']."Sys/Weixin/infourlto?urlto=".urlencode($redirect_uri);
        //$redirect_uri = "http://mobile.niuniuhuiapp.com/Sys/Weixin/infourlto?urlto=".urlencode($redirect_uri);

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$weixin_config['appid']."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_userinfo#wechat_redirect";

        
        header('Location:'.$url);

        exit;

    }

    /**
     * 获取详细信息后跳转
     * @Author   zhuangqm
     * @DateTime 2017-09-27T15:56:01+0800
     * @return   [type]                   [description]
     */
    public function infourltoAction(){
        // echo "aaaaa";
        // echo "-------------------";
        // print_r($this->params);

        $code = $this->params['code'];
        $urlto = $this->params['urlto'];
        

        if(empty($code) || empty($urlto))
            exit("参数有误-201");

        $weixin_config = Config::get("weixin");
        $openData = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$weixin_config['appid'].'&secret='.$weixin_config['appsecret'].'&code='.$code.'&grant_type=authorization_code');

        //var_dump($openData);
        $openData = json_decode($openData,true);
        //print_r($openData);
        $openid = $openData['openid'];

        /*if($openid!=''){
            $row = Model::ins("CusCustomerOpen")->getRow(["openid"=>$openid],"count(*) as count");
            if($row['count']==0)
                Model::ins("CusCustomerOpen")->insert(["openid"=>$openid,"open_type"=>"weixin_web","addtime"=>date("Y-m-d H:i:s")]);
        }*/
        

        $WeixinRedis = Model::Redis("Weixin");
        //获取用户详细信息
        $redis_key = "sale:auth_access_token";
        $access_token = '';
        if (!$WeixinRedis->exists($redis_key) && $openData['access_token']!=''){
            $WeixinRedis->set($redis_key,$openData['access_token'],120*60);
            $access_token = $openData['access_token'];
        }else{
            $access_token = $openData['access_token']!=''?$openData['access_token']:$WeixinRedis->get($redis_key);
        }

        //获取用户授权信息
        //获取用户信息
        $userinfo = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        $userinfo_arr = json_decode($userinfo,true);

        $userinfoparam = "";
        if($userinfo_arr['openid']!=''){

            Model::new("User.Open")->addUserOpenInfo($userinfo_arr);

            $openItem = Model::ins("CusCustomerOpen")->getRow(["openid"=>$userinfo_arr['openid']],"customerid");

            if($openItem['customerid']>0)
                Model::new("User.User")->cusLogin([
                    "customerid"=>$openItem['customerid']
                ]);

            // $userinfoparam = "&openuserinfo=".urlencode($userinfo);
            
            Cookie::set('openid',$userinfo_arr['openid'],3600*24*300);
        }

        //$urlto.=strpos($urlto,"?")!==false?"&openid=".$openid.$userinfoparam:"?openid=".$openid.$userinfoparam;

        // echo "urlto:".$urlto;
        // 
        $urlto = $urlto==''?"/":$urlto;

        header('Location:'.$urlto);

        exit;
    }
    
}