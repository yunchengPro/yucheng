<?php
// +----------------------------------------------------------------------
// |  [ 商城综合首页 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-27
// +----------------------------------------------------------------------
namespace app\api\controller\Msg;

use app\api\ActionController;

use app\lib\Model;

use app\lib\Img;

use app\lib\Rongcloud\RongcloudClass;

class IndexController extends ActionController{
	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    // 获取token
    public function gettokenAction(){

        $result = Model::new("Msg.RongCloud")->getToken($this->userid);

        return $this->json($result['code'],[
            "userid"=>$result['userid'],
            "token"=>$result['token'],
            "name"=>$result['name'],
            "portraitUri"=>$result['portraitUri'],
        ]);

    }

    // 获取商家的token
    public function getbusinesstokenAction(){

        $businessid = $this->params['businessid'];

        // if(empty($businessid))
        //     return $this->json("404");

        // $business_row = Model::ins("BusBusiness")->getRow(["id"=>$businessid],"id,customerid");
        
        $business = Model::ins("BusBusiness")->getRow(["id"=>$businessid],"id,customerid,businessname");
       
        if(empty($business))
            return $this->json("8010");

        $cusData = Model::ins('CusCustomer')->getRow(['mobile'=>'13670186752'],'id');
        $business_row = Model::ins("BusBusiness")->getRow(["customerid"=>$cusData['id']],"id,customerid");

        if(empty($business_row))
            return $this->json("8010");

        if(empty($business_row['customerid']))
            return $this->json("8010");

        $result = Model::new("Msg.RongCloud")->getToken($business_row['customerid'],$businessid);

        return $this->json($result['code'],[
            "userid"=>$result['userid'],
            "token"=>$result['token'],
            "name"=>$business['businessname'],
            "portraitUri"=>$result['portraitUri'],
        ]);
    }

    // 
    public function getuserinfoAction(){
        $userid = $this->params['userid'];

        if(empty($userid))
            return $this->json("userid");

        $tmp_arr = explode(",",$userid);

        $RongcloudClass = new RongcloudClass();

        $useridstr = '';
        $count=0;
        foreach($tmp_arr as $id){
            $useridstr.="'".$id."',";
            $count++;
            if($count>50)
                break;
        }
        $useridstr = $useridstr!=''?substr($useridstr,0,-1):0;

        $result = Model::ins("CusRongcloud")->getList(["userid"=>["in",$useridstr]],"customerid,userid,name,portraitUri",'',50);
        //print_r( $result);
       

        $CusCustomerInfo = Model::ins("CusCustomerInfo");

        foreach($result as $k=>$v){
        
            if($v['customerid']>0){
               
                $result[$k]['portraitUri'] = Img::url($v['portraitUri']);
                $cusinfo = $CusCustomerInfo->getRow(["id"=>$v['customerid']],"id,nickname,headerpic");
               
                $cusinfo['headerpic'] = Img::url($cusinfo['headerpic']);
                $cusData = Model::ins('CusCustomer')->getRow(['id'=>$v['customerid']],'id,mobile');
               // var_dump($cusData);
                if($cusData['mobile'] == '13670186752'){
                    $cusinfo['nickname'] = '客服';
                    $result[$k]['name'] = '客服';
                    $result[$k]['portraitUri'] = $cusinfo['headerpic'];
                }else{
                    $result[$k]['name'] = $cusinfo['nickname'];
                    $result[$k]['portraitUri'] = $cusinfo['headerpic'];
                }
                
                if($v['name']!=$cusinfo['nickname'] || $v['portraitUri']!=$cusinfo['headerpic']){
                    $token_result = $RongcloudClass->refresh([
                        "userid"=>$v['userid'],
                        "name"=>$cusinfo['nickname'],
                        "headerpic"=>$cusinfo['headerpic'],
                    ]);
                }
            }
        }
       
        return $this->json("200",$result);
    }
}