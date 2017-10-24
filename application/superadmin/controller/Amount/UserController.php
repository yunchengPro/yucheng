<?php
// +----------------------------------------------------------------------
// |  [ 店铺管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-05-18
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Amount;
use app\lib\Model;
use app\superadmin\ActionController;

class UserController extends ActionController {

	var $role_arr = array('1' => '牛粉', '2' => '牛人', '8' => "牛达人", '3' => '牛创客', '4' => '牛商', '5' => '牛掌柜', "6" => "孵化中心", "7" => "运营中心");

    public function __construct() {
        parent::__construct();
    }
    
//     public function userListAction() {
//         $where = array();
//         $where = $this->searchWhere([
//             "mobile" => "=",
//         ],$where);
        
//         $bullCusRoleModel = Model::new("Customer.BullCusRole");
//         $list = $bullCusRoleModel->userSimpleList($where);
        
//         foreach($list['list'] as $k => $v) {
//             $list['list'][$k]['fansCount'] = Model::new("User.UserAmount")->getUserCount(array("userid"=>$v['id']));
//             $list['list'][$k]['amount'] = Model::new("User.UserAmount")->getUserAmount(array("userid"=>$v['id'],"selfRole"=>1,"recoRole"=>1));
//             // 获取商家名称
//             $bus_business = Model::ins("BusBusiness")->getRow(array("customerid"=>$v['id']),"businessname");
//             $list['list'][$k]['busBusinessname'] = !empty($bus_business['businessname']) ? $bus_business['businessname'] : '';
//             $sto_business = Model::ins("StoBusiness")->getRow(array("customerid"=>$v['id']),"businessname");
//             $list['list'][$k]['stoBusinessname'] = !empty($sto_business['stoBusinessname']) ? $sto_business['businessname'] : '';
//         }

//         $viewData = array(
//             "pagelist" => $list['list'],
//             "total" => $list['total'],
//         );
        
//         return $this->view($viewData);
//     }
    
    /**
    * @user 牛粉列表
    * @param 
    * @author jeeluo
    * @date 2017年5月18日下午8:30:03
    */
    public function cusListAction() {
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "=",
            "busname"=>"=",
            "stoname"=>"=",
        ],$where);

        $cusroleOBJ = Model::ins("CusRole");
        $busOBJ = Model::ins("BusBusiness");
        $stoOBJ = Model::ins("StoBusiness");
        $useramountOBJ = Model::new("User.UserAmount");
        
        if(!empty($where['busname'])){
            $where['id'] = ["in", "select customerid from bus_business where businessname = '".$where['busname']."'"];
        }
        
        if(!empty($where['stoname'])) {
            if(!empty($where['id'])) {
                $where['id'] = ["in", "select customerid from sto_business where customerid in (select customerid from bus_business where businessname = '".$where['busname']."') and businessname = '".$where['stoname']."'"];
            } else {
                $where['id'] = ["in", "select customerid from sto_business where businessname = '".$where['stoname']."'"];
            }
        }

        $list = Model::ins("CusCustomer")->pageList($where, "*", "id desc");
        foreach ($list['list'] as $k => $v) {
            $cusInfo = Model::ins("CusCustomerInfo")->getRow(array("id" => $v['id']),"realname,nickname");
            $list['list'][$k] = array_merge($v, $cusInfo);
            
            $cusrolelist = $cusroleOBJ->getList(["customerid"=>$v['id']],"role");

            $cusrolestr = "";
            foreach($cusrolelist as $role){
            	$cusrolestr.=$this->role_arr[$role['role']].",";

            	//牛商
            	if($role['role']==4){
            		$businfo = $busOBJ->getRow(["customerid"=>$v['id']],"businessname");
            		$list['list'][$k]['busname'] = $businfo['businessname'];
            	}
            	//牛掌柜
            	if($role['role']==5){
            		$stoinfo = $stoOBJ->getRow(["customerid"=>$v['id']],"businessname");
            		$list['list'][$k]['stoname'] = $stoinfo['businessname'];
            	}
            }
            $list['list'][$k]['cusrole'] = $cusrolestr!=''?substr($cusrolestr,0,-1):"";

            // 粉丝数
            $list['list'][$k]['usercount'] = $useramountOBJ->getUserCount(["userid"=>$v['id']]);
        }

        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        
        return $this->view($viewData);
    }


    public function showusercountAction(){

    	$userid = $this->params["userid"];

    	$cusroleOBJ = Model::ins("CusRole");
    	$cusrolelist = $cusroleOBJ->getList(["customerid"=>$userid],"role");
    	
    	$urlarr = [];
    	$firsturl = [];
    	foreach($cusrolelist as $value){
    		$tmp = [
    			"role"=>$value['role'],
    			"rolename"=>$this->role_arr[$value['role']]."分享",
    			"url"=>"/Amount/User/showusercountinfo?userid=".$userid."&role=".$value['role'],
    		];
    		$urlarr[] = $tmp;
    		if(empty($firsturl))
    			$firsturl = $tmp;
    	}

    	return $this->view([
    			"urlarr"=>$urlarr,
    			"firsturl"=>$firsturl,
    		]);
    }

    // 详情页
    public function showusercountinfoAction(){

    	// 1牛粉2牛人3牛创客4牛商5牛掌柜6孵化中心7运营中心
    	$role = $this->params["role"];
    	$userid = $this->params["userid"];

    	$useramountOBJ = Model::new("User.UserAmount");

    	$list = $useramountOBJ->getRoleRelation([
    			"userid"=>$userid,
    			"role"=>$role,
    		]);

    	return $this->view([
    			"pagelist" => $list['list'],
            	"total" => $list['total'],
                "userid"=>$userid,
                "role"=>$role,
    		]);
    }

    public function userlistAction(){

    	$roletype = $this->params['roletype'];
        $userid = $this->params['userid'];
        $role = $this->params['role'];

    	$where = array();
        $where = $this->searchWhere([
            "mobile" => "=",
        ],$where);

//         if(!empty($where['mobile'])) {
//             $where['customerid'] = ["in","select customerid from cus_customer where mobile = '".$where['mobile']."'"];
//             unset($where['mobile']);
//         }

        $cusOBJ = Model::ins("CusCustomer");

        $UserAmount = Model::new("User.UserAmount");

        $list = $UserAmount->getUserList([
        		"roletype"=>$roletype,
                "userid"=>$userid,
        		"where"=>$where,
                "role"=>$role,
        	]);

        foreach ($list['list'] as $k => $v) {
            
            $cusinfo = $cusOBJ->getRow(["id"=>$v['customerid']],"mobile,createtime");

            $list['list'][$k]['mobile']     = $cusinfo['mobile'];
            $list['list'][$k]['createtime'] = $cusinfo['createtime'];

            // $profit_role = array("in", $flowOBJ->recoProfitRole($recoRole));
            $amount = $UserAmount->getRoleCount([
                    "userid"=>$userid,
                    "roletype"=>$roletype,
                    "parent_userid"=>$v['customerid'],
                    "role"=>$role,
                    "fromuserid"=>$v['customerid'],
                    "countflag"=>false,
                ]);

            // $list['list'][$k]['amount3'] = $amount['useramount'];
            $list['list'][$k]['amount1'] = $amount['amount1'];
            $list['list'][$k]['amount2'] = $amount['amount2'];
            $list['list'][$k]['amount3'] = $amount['amount3'];
        }

        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
            "userid"=>$userid,
            "roletype"=>$roletype,
            "urlPath"=>"?roletype=".$roletype."&userid=".$userid."&role=".$role,
        );
        
        return $this->view($viewData);
    }
    
    /**
    * @user 牛人列表
    * @param 
    * @author jeeluo
    * @date 2017年5月18日下午8:30:12
    */
    public function peoListAction() {
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "=",
        ],$where);
        $where['role'] = 2;
        
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile = '".$where['mobile']."'"];
            unset($where['mobile']);
        }
        $list = Model::ins("CusRole")->pageList($where, "customerid", "id desc");
        foreach ($list['list'] as $k => $v) {
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']));
            $cusInfo = Model::ins("CusCustomerInfo")->getRow(array("id" => $v['customerid']));
            $list['list'][$k] = array_merge($cus, $cusInfo);
        }
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        return $this->view($viewData);
    }
    
    /**
    * @user 牛创客列表
    * @param 
    * @author jeeluo
    * @date 2017年5月18日下午8:30:18
    */
    public function enListAction() {
        $where = array();
        $where = $this->searchWhere([
            "mobile" => "=",
        ],$where);
        $where['role'] = 3;
    
        if(!empty($where['mobile'])) {
            $where['customerid'] = ["in", "select id from cus_customer where mobile = '".$where['mobile']."'"];
            unset($where['mobile']);
        }
        $list = Model::ins("CusRole")->pageList($where, "customerid", "id desc");
        foreach ($list['list'] as $k => $v) {
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $v['customerid']));
            $cusInfo = Model::ins("CusCustomerInfo")->getRow(array("id" => $v['customerid']));
            $list['list'][$k] = array_merge($cus, $cusInfo);
        }
        $viewData = array(
            "pagelist" => $list['list'],
            "total" => $list['total'],
        );
        return $this->view($viewData);
    }
    
    public function stoListAction() {
        $where = array();
        $list = Model::ins("StoBusiness")->pageList($where, "*", "id desc");
//         foreach ($list)
    }
}