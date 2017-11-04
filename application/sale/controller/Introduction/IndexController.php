<?php
// +----------------------------------------------------------------------
// |  [ 用户协议 等相关平台介绍页面  ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-04-05
// +----------------------------------------------------------------------
namespace app\sale\controller\Introduction;
use app\sale\ActionController;
use think\Config;

class IndexController extends ActionController{

        
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [registDeal 用户注册协议]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T16:51:44+0800
     * @return   [type]                   [description]
     */
    public function registDealAction(){

        $webviewUrl = Config::get('webview.webviewUrl'); 
        $type = $this->getParam('type');
        $_v = $this->getParam('_v');
        $v = 0;
        if(substr($_v,0,1) == 'I' || substr($_v,0,1) == 'i'){
            $v = 1;
        }
        $title =  "用户注册协议";
        $viewData = [
            'userDeal' =>$userDeal,
            'webviewUrl' => $webviewUrl,
            'title' => $title,
            'v' => $v,
            'type'=>$type,
            '_v' => $_v
        ];
        return $this->view($viewData);
    }

    /**
     * [serviceDealAction 牛人服务协议]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-28T18:35:03+0800
     * @return   [type]                   [description]
     */
    public function serviceManDealAction(){
        $webviewUrl = Config::get('webview.webviewUrl'); 
        $_v = $this->getParam('_v');
        $v = 0;
        if(substr($_v,0,1) == 'I' || substr($_v,0,1) == 'i'){
            $v = 1;
        }
        $title =  "牛人服务协议";
        $viewData = [
            'userDeal' =>$userDeal,
            'webviewUrl' => $webviewUrl,
            'title' => $title,
            'v' => $v
        ];
        return $this->view($viewData);
    }

    /**
     * [serviceDealAction 牛创客服务协议]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-28T18:35:03+0800
     * @return   [type]                   [description]
     */
    public function serviceMakerDealAction(){
        $webviewUrl = Config::get('webview.webviewUrl'); 
        $title =  "牛创客服务协议";
        $_v = $this->getParam('_v');
        $v = 0;
        if(substr($_v,0,1) == 'I' || substr($_v,0,1) == 'i'){
            $v = 1;
        }
        $viewData = [
            'userDeal' =>$userDeal,
            'webviewUrl' => $webviewUrl,
            'title' => $title,
            'v' => $v
        ];
        return $this->view($viewData);
    }

    /**
     * [BusAccountIntroAction 企业账户介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-06T11:12:16+0800
     */
    public function BusAccountIntroAction(){
        
        $viewData = [
            'title'=>"企业账户介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [bigManIntroAction 牛人介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T17:44:31+0800
     * @return   [type]                   [description]
     */
    public function bigManAction(){

        $viewData = [
            'title'=>"牛人介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [niuDaRenAction 牛达人介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-12T16:02:13+0800
     * @return   [type]                   [description]
     */
    public function niuDaRenAction(){
        
        $viewData = [
            'title'=>"牛达人介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [niuChongKeAction 牛创客介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T17:52:35+0800
     * @return   [type]                   [description]
     */
    public function niuChongKeAction(){

        $viewData = [
            'title'=>"牛创客介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [cattleBusinessAction 牛商介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T17:53:38+0800
     * @return   [type]                   [description]
     */
    public function cattleBusinessAction(){

        $viewData = [
            'title'=>"牛商介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [cowShopkeepeAction 牛掌柜介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T17:54:41+0800
     * @return   [type]                   [description]
     */
    public function cowShopkeepeAction(){

        $viewData = [
            'title'=>"牛掌柜介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [incubatorCentreAction 孵化中心介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T17:55:51+0800
     * @return   [type]                   [description]
     */
    public function incubatorCentreAction(){

        $viewData = [
            'title'=>"孵化中心介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [operationCenterAction 运营中心介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-05T17:58:04+0800
     * @return   [type]                   [description]
     */
    public function operationCenterAction(){

        $viewData = [
            'title'=>"运营中心介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [serviceTarentoDealAction  牛达人 协议 平台消费分享服务协议]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-17T11:24:37+0800
     * @return   [type]                   [description]
     */
    public function serviceTarentoDealAction(){
       
       
        $_v = $this->getParam('_v');
        $v = 0;
        if(substr($_v,0,1) == 'I' || substr($_v,0,1) == 'i'){
            $v = 1;
        }
        $viewData = [
            'title'=>"牛达人服务协议",
            'v' => $v
        ];

        return $this->view($viewData);
    }

    /**
     * [groupNiudarenAction 牛人牛达人社区介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-14T18:59:25+0800
     * @return   [type]                   [description]
     */
    public function groupNiudarenAction(){
        $type = $this->params['type'];
        $title = '牛人朋友圈介绍';
        if ($type ==1) {
            $title = '牛人朋友圈介绍';
        }
        if($type == 2){
            $title = '牛达人朋友圈介绍';
        }
        if($type == 3){
            $title = '牛创客朋友圈介绍';
        }
        $viewData = [
            'title'=>$title
        ];

        return $this->view($viewData);
    }

    /**
     * [bonusUseRuleAction 牛粮奖励金获取和使用规则]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-14T19:02:53+0800
     * @return   [type]                   [description]
     */
    public function bonusUseRuleAction(){
        $viewData = [
            'title'=>"牛粮奖励金获取和使用规则"
        ];

        return $this->view($viewData);
    }

    /**
     * [balanceintroAction 余额介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-31T11:07:11+0800
     * @return   [type]                   [description]
     */
    public function balanceintroAction(){
        $viewData = [
            'title'=>"余额介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [registerSpreadAction 注册推广说明]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-11T15:03:10+0800
     * @return   [type]                   [description]
     */
    public function registerSpreadAction(){
        $viewData = [
            'title'=>"注册推广说明"
        ];

        return $this->view($viewData);
    }

    /**
     * [niufunintroAction 牛粉介绍]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-14T17:16:52+0800
     * @return   [type]                   [description]
     */
    public function niufunintroAction(){
        $viewData = [
            'title'=>"牛粉介绍"
        ];

        return $this->view($viewData);
    }

    /**
     * [receivedgoodsintroAction 待收货款名词解释]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-09-16T13:29:14+0800
     * @return   [type]                   [description]
     */
    public function receivedgoodsintroAction(){
         $viewData = [
            'title'=>"待收货款名词解释"
        ];

        return $this->view($viewData);
    }
}