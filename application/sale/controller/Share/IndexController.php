<?php
// +----------------------------------------------------------------------
// |  [ 分享控制器 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{datetime}}
// +----------------------------------------------------------------------
namespace app\mobile\controller\Share;
use app\sale\ActionController;

use think\Config;
use app\model\User\TokenModel;
use app\model\User\UserModel;
use app\lib\Model;
class IndexController extends ActionController{

	/**
	 * [sharecodeAction 分享编码]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-04-12T10:40:25+0800
	 * @return   [type]                   [description]
	 */
	public function sharecodeAction(){
		$share = Config::get('webview');
		$data = $this->params;
		foreach ($data as $k => $v) {
            $paramString .= "&$k=$v";
        }
        if(!empty($data['stocode'])){
        	$title = '平台号：'.$data['stocode'];
        }else if(!empty($data['userid'])){
        	$userInfo = Model::ins('CusCustomer')->getRow(['id'=>$data['userid']],'mobile,username');

        	$title = empty($userInfo['username']) ? $userInfo['mobile'] : $userInfo['username'];
        }
		$url = $share['share_code_url'] .$paramString;
		$viewData = [
			'title' => $title,
			'img' => $url
		];
		//print_r($viewData);
		return $this->view($viewData);
	}

	/**
	 * [sharestocodeAction description]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-05-13T14:55:14+0800
	 * @return   [type]                   [description]
	 */
	public function sharestocodeAction(){
		$businessid = $this->getParam('businessid');

		$busData = Model::ins('StoBusinessBaseinfo')->getRow(['id'=>$businessid],'business_code,businessname');

		$domain = Config::get("stobusiness.domain");
        $url = $domain."/?_apiname=Stobusiness.PhysicalShop.getsotCodeUrl&businessid=".$this->params['businessid'];
        
		$viewData = [
			'business_code' => $busData['business_code'],
			'businessname' => $busData['businessname'],
			'img' => $url
		];
		//print_r($viewData);
		return $this->view($viewData);

	}
}