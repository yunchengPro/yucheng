<?php
// +----------------------------------------------------------------------
// |  [ 牛人模型]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年3月30日 21:12:02
// +----------------------------------------------------------------------
namespace app\model\Customer;

use \think\Request;
//获取配置
use \think\Config;
//Config::get('webview.webviewUrl');

//use app\lib\Db;
use app\lib\Model;
//use app\lib\Img;

use app\model\Customer\CustomerModel;

class BullPeoRoleModel extends CustomerModel
{    
    protected $_role = 'bullPeoRole';    
    protected $_recoModelName     = 'RoleRecoOr';
    protected $_relationModelName = 'CusRelationOr';
    
    protected $_upgradeDescendantCount  = 300;
    
    /**
     * [getWaitAuditList 获取待审核列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月30日 21:12:02
     * @return [type]    [description]
     */
    public function getWaitAuditList($where)
    {
        $list = $this->_getWaitAuditList($where);
        
        return $list;
    }
    
    /**
     * [getNoPassList 获取待审核列表]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月1日 16:19:02
     * @return [type]    [description]
     */
    public function getNoPassList($where)
    {
        $list = $this->_getNoPassList($where);
    
        return $list;
    }
    
    /**
     * [pass 通过审核]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月30日 21:12:02
     * @return [type]    [description]
     */
    public function pass($roleReco)
    {
        //var_dump('BullPeoRoleModel pass'); exit();
        
        $result = $this->_pass($roleReco);
        
        // 生成流水号
        $flowid = Model::new("Amount.Flow")->getFlowId($roleReco['orderno']);
        
        $user = Model::ins("CusCustomer")->getIdByMobile($roleReco['mobile']);
        Model::new("Amount.Role")->pay_tnr([
            "userid" => $user['id'],
            "amount" => $roleReco['amount'],
            "orderno" => $roleReco['orderno'],
            "flowid" => $flowid,
        ]);
        
        return $result;
    }
    
    public function payPass($roleReco) {
        $result = $this->_pass($roleReco);
        return $result;
    }
    
    /**
    * @user 申请成为牛人操作
    * @param  $applyInfo 牛人信息
    * @author jeeluo
    * @date 2017年4月5日下午8:54:43
    */
    public function apply($applyInfo) {
        $result = $this->_apply($applyInfo);
        
        return $result;
    }
    
    /**
     * [noPass 不通过审核]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年4月1日 下午2:07:04
     * @return [type]    [description]
     */
    public function noPass($roleReco)
    {
        //var_dump('BullPeoRoleModel noPass'); exit();
    
        $result = $this->_noPass($roleReco);
    
        return $result;
    }
    
    /**
     * [_upgradeToBullenRole 升级到牛创客]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月30日 21:12:02
     * @return [type]    [description]
     */
    protected function _upgradeToBullenRole($customerId, $createtime)
    {
        $cusCustomerInfoModel = Model::ins("CusCustomerInfo");
        $cusRoleModel         = Model::ins("CusRole");
        $cusRelationOrModel   = $this->_getRelationModel();
        //var_dump($cusRelationOrModel); //exit();
        
        //是否已为牛创客
        $bullenRoleId = Config::get('role_money.bullenRole');
        $where = array(
            'customerid' => $customerId,
            'role' => $bullenRoleId
        );
        $bullenRole = $cusRoleModel->getRow($where);
        
        $cusRoleId = 0;
        if(empty($bullenRole)){
            //若未为牛创客，则判断粉丝数
        
            // $descendantCount = $cusRelationOrModel->getDescendantCount($customerId);
            $descendantCount = Model::ins("CusRelation")->getDescendantCount($customerId);

            if($descendantCount >= $this->_upgradeDescendantCount){
            //if(1){
                $customerInfo = $cusCustomerInfoModel->getById($customerId);
                //var_dump($parentCustomerInfo);
        
                $cusRole = array(
                    'customerid' => $customerId,
                    'role' => $bullenRoleId,
                    'area' => $customerInfo['area'],
                    'address' => $customerInfo['address'],
                    'area_code' => $customerInfo['area_code'],
                    'addtime' => $createtime
                );
                $cusRoleId = $cusRoleModel->add($cusRole);
                //var_dump($cusRoleId);
        
                //插入牛创客推荐关系，一般不需判断是否已有推荐关系
                $cusRelationEnModel = Model::ins('CusRelationEn');
                $cusRelationEn = array(
                    'customerid' => $customerId,
                    'parentid'   => -1,
                    'grandpaid'  => -1,
                );
                $cusRelationEnId = $cusRelationEnModel->add($cusRelationEn);
                //var_dump($cusRelationEnId);
            }
        }else{
            $cusRoleId = $bullenRole['id'];
        }
        
        return $cusRoleId;
    }
    
    /**
    * @user 获取用户详情
    * @param 
    * @author jeeluo
    * @date 2017年4月24日下午2:18:25
    */
    public function getCusCustomerInfo($customerid) {
        return $this->_getCusCustomerInfo($customerid);
    }
    
    /**
    * @user 获取推荐牛人填写的资料
    * @param 
    * @author jeeluo
    * @date 2017年4月24日下午3:50:22
    */
    public function getRecoOrInfo($id) {
        $recoInfo = Model::ins("RoleRecoOr")->getRow(array("id" => $id));
        if(empty($recoInfo)) {
            return ["code" => "1000", "data" => "记录不存在"];
        }
        
        
        
        if($recoInfo['instroducerid'] == -1) {
            $recoInfo['instroducerMobile'] = '';
        } else {
            $cus = Model::ins("CusCustomer")->getRow(array("id" => $recoInfo['instroducerid']), "mobile");
            $recoInfo['instroducerMobile'] = $cus['mobile'];
        }
        
        return ["code" => "200", "data" => $recoInfo];
    }
    
    /**
    * @user 添加用户角色
    * @param 
    * @author jeeluo
    * @date 2017年4月26日上午10:39:10
    */
    public function addCusRole($params) {
        
        $result = $this->_addCusRole($params);
        
//         if($result["code"] == "200") {
//             // 生成流水号
//             $flowid = Model::new("Amount.Flow")->getFlowid($result['data']["orderno"]);
            
//             $user = Model::ins("CusCustomer")->getIdByMobile($result['data']['mobile']);
            
//             Model::new("Amount.Role")->pay_tnr([
//                 "userid" => $user['id'],
//                 "amount" => $result['data']['pay_amount'],
//                 "orderno" => $result['data']['orderno'],
//                 "flowid" => $flowid,
//             ]);
//         }
        
        return $result;
    }
    
    public function examSend($params) {
        $title = '';
        $content = '';
        $roleRecoOr = Model::ins("RoleRecoOr")->getRow(["id"=>$params['recoId']],"instroducerid,realname,status,remark");
        
        if($roleRecoOr['status'] == 2) {
            $title = "分享成功";
            $content = "您分享".$roleRecoOr['realname']."为牛人,已审核成功!";
        } else {
            $title = "分享失败";
            $content = "您分享".$roleRecoOr['realname']."为牛人,未通过审核!原因:".$roleRecoOr['remark'];
        }
        
        Model::new("Msg.SendMsg")->SendSysMsg(["userid"=>$roleRecoOr['instroducerid'],"title"=>$title,"content"=>$content]);
    }
}