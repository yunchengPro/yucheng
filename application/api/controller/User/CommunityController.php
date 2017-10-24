<?php
namespace app\api\controller\User;

use app\api\ActionController;
use app\lib\Model;
use app\lib\Img;
use app\model\Sys\CommonModel;

/**
* @user 个人中心社群控制器
* @param 
* @author jeeluo
* @date 2017年8月10日下午3:22:59
*/
class CommunityController extends ActionController {
    
    const defaultRole = 1;
    const orRole = 2;
    const enRole = 3;
    const ndRole = 8;
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 牛人列表数据
    * @param 
    * @author jeeluo
    * @date 2017年8月10日下午5:01:19
    */
    public function orcommunityAction() {
        if(empty($this->params['selfRoleType'])) {
            return $this->json(404);
        }
        
        $role = $this->params['selfRoleType'];
        
        if($role == self::orRole)
            $list = Model::new("User.Relation")->getNRCommunityRelation($this->userid);
        else if($role == self::enRole)
            $list = Model::new("User.Relation")->getENCommunityRelation($this->userid);
        else if($role == self::ndRole)
            $list = Model::new("User.Relation")->getNDCommunityRelation($this->userid);
        else
            $list = array();
        
        if(!empty($list['list'])) {
            $cusModel = Model::ins("CusCustomer");
            $cusInfoModel = Model::ins("CusCustomerInfo");
            $userAmountModel = Model::new("User.UserAmount");
            
            foreach ($list['list'] as $k => $v) {
                $role = $this->userid == $v['customerid'] ? $this->params['selfRoleType'] : 2;
                $cus = $cusModel->getRow(["id"=>$v['customerid']],"mobile");
                $cusInfo = $cusInfoModel->getRow(["id"=>$v['customerid']],"headerpic,realname,nickname");
                
                $list['list'][$k]['headerpic'] = !empty($cusInfo['headerpic']) ? Img::url($cusInfo['headerpic']) : '';
                $list['list'][$k]['nickname'] = $cusInfo['nickname'];
                $list['list'][$k]['mobile'] = CommonModel::mobile_format($cus['mobile']);
                $list['list'][$k]['stoCount'] = $userAmountModel->stoUserCount(["userid"=>$v['customerid'],"role"=>$role]);
            }
        }
        
        return $this->json(200, $list);
    }
    
    /**
    * @user 牛人收益数据
    * @param 
    * @author jeeluo
    * @date 2017年8月10日下午8:23:11
    */
    public function orprofitAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['type'])) {
            return $this->json(404);
        }
        
        $params['type'] = $this->params['type'];
        $params['role'] = $this->params['selfRoleType'];
        $params['customerid'] = $this->userid;
        $params['begintime'] = !empty($this->params['begintime']) ? $this->params['begintime'] : '';
        $params['endtime'] = !empty($this->params['endtime']) ? $this->params['endtime'] : '';
        $params['isAndroid'] = $this->Version($this->params['version'],"A");
        
        $result = Model::new("User.Community")->orflowProfit($params);
        
        if($result['code'] != "200") {
            return $this->json($result['code']);
        }
        return $this->json($result['code'], $result['data']);
    }
    
    /**
    * @user 牛粮奖励金列表
    * @param 
    * @author jeeluo
    * @date 2017年8月14日下午4:42:58
    */
    public function flowbonusAction() {
        if(empty($this->params['selfRoleType'])) {
            return $this->json(404);
        }
        
        // 限制只有牛粉才能有进入
        if($this->params['selfRoleType'] != self::defaultRole) {
            return $this->json(1001);
        }
        
        $params['customerid'] = $this->userid;
        $params['begintime'] = !empty($this->params['begintime']) ? $this->params['begintime'] : '';
        $params['endtime'] = !empty($this->params['endtime']) ? $this->params['endtime'] : '';
        $params['isAndroid'] = $this->Version($this->params['version'],"A");
        
        $result = Model::new("User.Community")->bonusFlow($params);
        
        if($result['code'] != "200") {
            return $this->json($result['code']);
        }
        
        return $this->json($result['code'], $result['data']);
    }
    
    /**
    * @user 牛人小组内用户的牛店列表
    * @param 
    * @author jeeluo
    * @date 2017年8月14日下午5:26:32
    */
    public function communitystolistAction() {
        if(empty($this->params['selfRoleType']) || empty($this->params['customerid']) || empty($this->params['role'])) {
            return $this->json(404);
        }
        
        $params['userid'] = $this->userid;
        $params['customerid'] = $this->params['customerid'];
        $params['selfRoleType'] = $this->params['selfRoleType'];
        $params['role'] = $this->params['role'];
        
        $result = Model::new("User.Community")->communityStoList($params);
        
        if($result["code"] != "200") {
            return $this->json($result["code"]);
        }
        
        return $this->json($result["code"], $result['data']);
    }
}