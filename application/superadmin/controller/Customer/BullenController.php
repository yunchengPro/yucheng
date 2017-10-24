<?php
// +----------------------------------------------------------------------
// |  [ 牛创客管理]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年3月28日 21:17:18
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Customer;
use app\superadmin\ActionController;
//use app\superadmin\controller\Customer\CustomerController;

use \think\Config;
//use app\lib\Db;
use app\lib\Model;

use app\form\RoleRecoEn\RoleRecoEnAdd;
use app\model\Sys\CommonRoleModel;
use app\model\Sys\CommonModel;

class BullenController extends ActionController{
	
    const enRole = 3;
	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();        
    }
    
    /**
     * [layoutAction 页面tab布局]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月28日 21:17:18
     * @return [type]    [description]
     */
    public function layoutAction(){
        return $this->view(array('title' => '牛创客管理'));
    }

	/**
	 * [indexAction 牛创客列表]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017-03-25T10:47:03+0800
	 * @return   [type]                   [description]
	 */
	public function indexAction(){
	    //var_dump('/Customer/Bullen/index'); exit();
	    //parent::indexAction();	    	    
	    
	    $username = strval($this->getParam('username'));
        
	    $where = array();
// 	    $where = $this->searchWhere([
// 	        "username"=>"like"
// 	    ],$where);
	    if(!empty($username)){
	        $where = array_merge($where, array('username' => $username));
	    }
	    //var_dump($where); exit();
	    
	    $bullenRole = Model::new('Customer.BullenRole');
	    if(!empty($where)){
	        $list = $bullenRole->getWhereList($where);
	    }else{
	        $list = $bullenRole->getSimpleList();
	    }	    	    
	    //var_dump($list); exit();
        
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total']+3000, //总数
        );

        return $this->view($viewData);
	}

    /**
     * [indexAction 待审核列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月29日 下午8:10:04
     * @return [type]    [description]
     */
    public function waitAuditAction()
    {
        //var_dump('/Customer/Bullen/waitAudit'); exit();
    
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "like",
        ],$where);
        
        $list  = Model::new('Customer.BullenRole')->getWaitAuditList($where);
        //var_dump($list); exit();
    
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
        );
    
        return $this->view($viewData);
    }
    
    /**
     * [noPassListAction 未通过审核列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月1日 下午4:22:34
     * @return [type]    [description]
     */
    public function noPassListAction()
    {
        //var_dump('/Customer/BullPeo/noPassList'); exit();
    
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "like",
        ], $where);
        $list  = Model::new('Customer.BullenRole')->getNoPassList($where);
        //var_dump($list); exit();
    
        $viewData = array(
            "pagelist"=>$list['list'], //列表数据
            "total"=>$list['total'], //总数
        );
    
        return $this->view($viewData);
    }
    
    /**
     * [passAction 通过审核]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月29日 下午8:10:04
     * @return [type]    [description]
     */
    public function passAction()
    {
        //var_dump('/Customer/Bullen/pass'); exit();
    
        $bullenId = $this->getParam('id');
    
        if(empty($bullenId)){
            $this->showError('请选择用户');
        }
    
        $roleRecoEnModel = Model::ins('RoleRecoEn');
        $roleRecoEn      = $roleRecoEnModel->getById($bullenId);
        //var_dump($roleRecoOr); exit();
        if(empty($roleRecoEn)){
            $this->showError('不存在该用户');
        }
    
        $result = Model::new('Customer.BullenRole')->pass($roleRecoEn);
        if($result["code"] != 200) {
            $this->showError($result["data"]);
        }
        
        Model::new("Sys.Mq")->add([
            "url"=>"Customer.BullenRole.examSend",
            "param"=>[
                "recoId"=>$bullenId,
            ],
        ]);
        Model::new("Sys.Mq")->submit();
        //var_dump($result); exit();
    
        $this->showSuccess('成功通过审核');
    }    
    
    /**
     * [noPass 不通过审核表单]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月1日 下午3:41:31
     * @return [type]    [description]
     */
    public function noPassAction()
    {
        //var_dump('/Customer/Bullen/noPass'); exit();
    
        $action = '/Customer/Bullen/saveNoPass';
    
        $bullenId = $this->getParam('id');
    
        if(empty($bullenId)){
            $this->showError('请选择用户');
        }
    
        $roleRecoEnModel = Model::ins('RoleRecoEn');
        $roleRecoEn      = $roleRecoEnModel->getById($bullenId);
        //var_dump($roleRecoEn); exit();
        if(empty($roleRecoEn)){
            $this->showError('不存在该用户');
        }
    
        //form验证token
        $formtoken = $this->Btoken('Customer-Bullen-noPass');
        $viewData = array(
            "title"=>"未通过原因",
            "roleRecoEn"=>$roleRecoEn,
            'formtoken'=>$formtoken,
            "action"=>$action
        );
    
        return $this->view($viewData);
    }
    
    /**
     * [saveNoPass 保存不通过审核]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月1日 下午3:41:31
     * @return [type]    [description]
     */
    public function saveNoPassAction()
    {
        //var_dump('/Customer/Bullen/saveNoPass'); exit();
    
        if($this->Ctoken()){
            //if(1){
            $bullenId = $this->getParam('id');
            $post = $this->params;
    
            if(empty($bullenId)){
                $this->showError('请选择用户');
            }
    
            $roleRecoEnModel = Model::ins('RoleRecoEn');
            $post = $roleRecoEnModel->_facade($post);
    
            //自动验证表单 需要修改form对应表名
            $RoleRecoEnAdd = new RoleRecoEnAdd();
            if(!$RoleRecoEnAdd->isValid($post)){//验证是否正确
                $this->showError($RoleRecoEnAdd->getErr());//提示报错信息
            }else{
                //$roleRecoEnModel = Model::ins('RoleRecoEn');
                $roleRecoEn      = $roleRecoEnModel->getById($bullenId);
                //var_dump($roleRecoEn); exit();
                if(empty($roleRecoEn)){
                    $this->showError('不存在该用户');
                }
    
                $roleRecoEn['remark'] = $post['remark'];
                $result = Model::new('Customer.BullenRole')->noPass($roleRecoEn);
               
                // 审核失败
                Model::new("Sys.Mq")->add([
                    "url"=>"Customer.BullenRole.examSend",
                    "param"=>[
                        "recoId"=>$bullenId,
                    ],
                ]);
                Model::new("Sys.Mq")->submit();
    
                $this->showSuccess('操作成功');
            }
        }else{
            $this->showError('token错误，禁止操作');
        }
    }
    
    /**
    * @user 编辑牛创客信息
    * @param 
    * @author jeeluo
    * @date 2017年4月26日上午11:09:51
    */
    public function editBullenInfoAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        
        $customerid = $this->getParam("id");
        $result = Model::new("Customer.BullenRole")->getCusCustomerInfo($customerid);
        
        if($result['code'] != "200") {
            $this->showError($result["data"]);
        }
        
        $action = "/Customer/Bullen/updateInfo?id=".$customerid;
        
        $viewData = array(
            "cusInfo" => $result['data'],
            "actionUrl" => $action,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 更改用户数据
    * @param 
    * @author jeeluo
    * @date 2017年4月26日上午11:40:18
    */
    public function updateInfoAction() {
        if(empty($this->params["id"])) {
            $this->showError("请选择正确用户");
        }
        $params = array();
        if(!empty($this->params['nickname'])) {
            $params['nickname'] = $this->params['nickname'];
        }
        if(!empty($this->params['realname'])) {
            $params['realname'] = $this->params['realname'];
        }
        
        Model::ins("CusCustomerInfo")->modify($params, array("id" => $this->params['id']));
        
        $this->showSuccess("操作成功");
    }
    
    /**
    * @user 获取推荐牛创客填写的资料
    * @param 
    * @author jeeluo
    * @date 2017年4月26日下午2:11:23
    */
    public function editBullenRecoAction() {
        if(!$this->getParam("id")) {
            $this->showError("请正确操作");
        }
        
        $id = $this->getParam("id");
        $result = Model::new("Customer.BullenRole")->getRecoEnInfo($id);
        
        if($result["code"] != "200") {
            $this->showError($result['data']);
        }
        
        $action = "/Customer/Bullen/updateRecoInfo?id=".$id;
        
//         $goodslists = CommonRoleModel::getPresentList(self::enRole);
        
//         $goodslist = array();
//         foreach ($goodslists as $goods) {
//             $goodslist[$goods['id']] = $goods['productname'];
//         }
        
//         $giftInfo = array();
//         // 获取订单信息
//         $recoInfo = Model::ins("RoleRecoEn")->getRow(array("id" => $id), "orderno");
        
//         $roleOrder = Model::ins("RoleOrder")->getRow(array("role_orderno" => $recoInfo['orderno']), "id");
//         if(!empty($roleOrder['id'])) {
//             $goodsItem = Model::ins("RoleOrderItem")->getRow(array("orderid" => $roleOrder['id']), "productid");
//             $giftInfo['item'] = $goodsItem;
        
//             $goodslogistics = Model::ins("RoleOrderLogistics")->getRow(array("orderid" => $roleOrder['id']), "realname,mobile,city_id,address");
//             $giftInfo['logistics'] = $goodslogistics;
//         }
        
        $viewData = array(
            "recoInfo" => $result["data"],
            "actionUrl" => $action,
//             "goodslist" => $goodslist,
//             "giftInfo" => $giftInfo,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 修改推荐信息操作
    * @param 
    * @author jeeluo
    * @date 2017年4月26日下午2:15:18
    */
    public function updateRecoInfoAction() {
        
        if(empty($this->params['realname']) || empty($this->params['address']) || empty($this->params['instroducerMobile'])) {
            $this->showError("请填写完整数据");
        }
        
        // 根据id 查看订单
//         $recoInfo = Model::ins("RoleRecoEn")->getRow(array("id" => $this->params['id']), "orderno");
//         $roleOrder = Model::ins("RoleOrder")->getRow(array("role_orderno" => $recoInfo['orderno']), "id");
//         if(!empty($roleOrder)) {
//             // 有礼品订单
//             if(empty($this->params['productid']) || empty($this->params['logisticsName']) || empty($this->params['logisticsMobile']) || empty($this->params['logisticsArea_county'])
//                 || empty($this->params['logisticsAddress'])) {
        
//                     $this->showError("新版订单信息，收货人信息必须填写完整");
//                 }
                
//             if(phone_filter($this->params['logisticsMobile'])) {
//                 $this->showError("收货人手机号码不正确");
//             }
//         }
        
        if(phone_filter($this->params['instroducerMobile'])) {
            $this->showError("手机号码不正确");
        }
        
        if(phone_filter($this->params['instroducerMobile'])) {
            $this->showError("手机号码规格有误");
        }
        
//         if(!empty($this->params['instroducerMobile'])) {
//             $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $this->params['instroducerMobile']), "id");
//             if(!empty($cus["id"])) {
//                 // 查询用户填写的分享人有对应的分享角色么
//                 $isIntroducerRole = Model::ins("CusRole")->getRow(["customerid"=>$cus["id"],"role"=>3],"id");
//                 if(empty($isIntroducerRole['id'])) {
//                     $this->showError("用户无对应角色，无法分享");
//                 }
//             } else {
//                 $this->showError("无此分享人");
//             }
//         }
        
        if(!empty($this->params['instroducerMobile'])) {
            $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $this->params['instroducerMobile']), "id");
            if(!empty($cus)) {
                $params['instroducerid'] = $cus['id'];
            } else {
                $params['instroducerid'] = -1;
            }
        }
        
        $params['realname'] = $this->params['realname'];
        $params['address'] = $this->params['address'];
        
//         if(!empty($roleOrder)) {
//             $orderItem['productid'] = $this->params["productid"];
//             $role_product = Model::ins("RoleProduct")->getRow(array("id" => $orderItem['productid']), "businessid,productname,thumb,prouctprice");
//             $busInfo = Model::ins("BusBusiness")->getRow(array("id" => $role_product['businessid']), "businessname");
//             $orderItem['businessid'] = $role_product['businessid'];
//             $orderItem['businessname'] = $busInfo['businessname'];
//             $orderItem['productname'] = $role_product['productname'];
//             $orderItem['thumb'] = $role_product['thumb'];
//             $orderItem['prouctprice'] = $role_product['prouctprice'];
        
//             $areaname = CommonModel::getSysArea($this->params['logisticsArea_county']);
//             $logistics['realname'] = $this->params['logisticsName'];
//             $logistics['mobile'] = $this->params['logisticsMobile'];
//             $logistics['city_id'] = $this->params['logisticsArea_county'];
//             $logistics['city'] = $areaname['data'];
//             $logistics['address'] = $this->params['logisticsAddress'];
        
//             Model::ins("RoleOrderItem")->modify($orderItem, array("orderid" => $roleOrder['id']));
//             Model::ins("RoleOrderLogistics")->modify($logistics, array("orderid" => $roleOrder['id']));
//         }
        
        Model::ins("RoleRecoEn")->modify($params, array("id" => $this->params['id']));
        
        $this->showSuccess("修改成功!");
    }
    
    /**
    * @user 还原待审核
    * @param 
    * @author jeeluo
    * @date 2017年4月26日下午2:20:33
    */
    public function restoreEnAction() {
        if(empty($this->getParam("id"))) {
            $this->showError("请正确操作");
        }
        
        $recoOr = Model::ins("RoleRecoEn")->getRow(array("id" => $this->getParam("id")), "status");
        
        if(empty($recoOr)) {
            // 数据异常
            $this->showError("数据异常，请正确操作");
        }
        
        if($recoOr['status'] != 3) {
            $this->showError("无法还原该推荐数据");
        }
        
        $status = Model::ins("RoleRecoEn")->modify(array("status"=>1), array("id"=>$this->getParam("id")));
        if($status) {
            $this->showSuccess("操作成功");
        }
        $this->showSuccess("操作失败，请联系管理员");
    }
    
    /**
    * @user 添加牛创客角色页面
    * @param 
    * @author jeeluo
    * @date 2017年4月26日上午10:48:16
    */
    public function addBullenInfoAction() {
        $action = "/Customer/Bullen/addBullen";
        $role_money = DePrice(Config::get("role_money.bullenMoney"));
        
        $viewData = array(
            "title" => "添加牛创客角色",
            "actionUrl" => $action,
            "role_money" => $role_money,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 添加用户牛创客角色
    * @param 
    * @author jeeluo
    * @date 2017年4月26日上午10:48:49
    */
    public function addBullenAction() {
    
        if(empty($this->params['realname']) || empty($this->params['mobile']) || empty($this->params['area_province']) || empty($this->params['area_city']) || empty($this->params["area_county"])
            || empty($this->params['address']) || empty($this->params['instroducerMobile'])) {
                $this->showError("请填写必填选项");
            }
            
//             if($this->params['role_status'] == 1) {
//                 if(empty($this->params['amount'])) {
//                     $this->showError("请填写必填选项");
//                 }
//             }
            
            if(phone_filter($this->params['mobile'])) {
                $this->showError("手机号码规格有误");
            }
            
            if(phone_filter($this->params['instroducerMobile'])) {
                $this->showError("手机号码规格有误");
            }
            
//             if(!empty($this->params['instroducerMobile'])) {
//                 $cus = Model::ins("CusCustomer")->getRow(array("mobile" => $this->params['instroducerMobile']), "id");
//                 if(!empty($cus["id"])) {
//                     // 查询用户填写的分享人有对应的分享角色么
//                     $isIntroducerRole = Model::ins("CusRole")->getRow(["customerid"=>$cus["id"],"role"=>3],"id");
//                     if(empty($isIntroducerRole['id'])) {
//                         $this->showError("用户无对应角色，无法分享");
//                     }
//                 } else {
//                     $this->showError("无此分享人");
//                 }
//             }
    
            $result = Model::new("Customer.BullenRole")->addCusRole($this->params);
    
            if($result["code"] != "200") {
                $this->showError($result['data']);
            }
    
            $this->showSuccess("操作成功");
    }
}